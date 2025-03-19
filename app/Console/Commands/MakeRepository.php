<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;

class MakeRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {path} {model?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new repository class.';

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected Filesystem $files;

    /**
     * Create a new command instance.
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get arguments
        $path = $this->argument('path');
        $model = $this->argument('model');

        // If the model is not provided, prompt for it
        if (!$model) {
            $model = $this->ask('Please provide the model name');
        }

        $modelPath = app_path("Models/{$model}.php");
        if (!file_exists($modelPath)) {
            // If the model doesn't exist, create it using Artisan
            $this->info("Model {$model} does not exist. Creating it now...");
            Artisan::call('make:model', ['name' => $model]);
            $this->info("Model {$model} created successfully.");
        }

        // Format class name & namespace
        $repositoryClass = class_basename($model) . 'Repository';
        $namespace = 'App\\Repositories\\' . str_replace('/', '\\', $path);
        $repositoryPath = app_path("Repositories/{$path}/{$repositoryClass}.php");

        // Check if the repository already exists
        if ($this->files->exists($repositoryPath)) {
            $this->error("Repository {$repositoryClass} already exists!");
            return;
        }

        // Generate the repository content
        $stub = $this->getStub();
        $stub = str_replace('{{ namespace }}', $namespace, $stub);
        $stub = str_replace('{{ model }}', $model, $stub);
        $stub = str_replace('{{ modelVariable }}', lcfirst($model), $stub);
        $stub = str_replace('{{ repositoryClass }}', $repositoryClass, $stub);

        // Ensure directory exists
        $this->files->ensureDirectoryExists(dirname($repositoryPath));

        // Write the repository file
        $this->files->put($repositoryPath, $stub);

        // Success message
        $this->info("Repository {$repositoryClass} created successfully at {$repositoryPath}");
    }

    /**
     * Get the stub content for the repository.
     */
    protected function getStub(): string
    {
        return <<<EOT
        <?php

        namespace {{ namespace }};

        use App\Models\{{ model }};
        use App\Repositories\Repository;
        use Illuminate\Http\Request;
        use Illuminate\Pagination\LengthAwarePaginator;

        class {{ repositoryClass }} extends Repository
        {
            /**
             * {{ repositoryClass }} constructor.
             * @param {{ model }} \${{ modelVariable }}
             */
            public function __construct({{ model }} \${{ modelVariable }})
            {
                parent::__construct(\${{ modelVariable }});
            }

            /**
             * @param Request \$request
             * @param array \$columns
             * @return LengthAwarePaginator
             */
            public function getPaginatedList(Request \$request, \$type = null, array \$columns = ['*']): LengthAwarePaginator
            {
                \$limit = \$request->get('limit', config('app.per_page'));
                return \$this->model->newQuery()
                    ->latest()
                    ->paginate(\$limit);
            }
        }
        EOT;
    }
}
