<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name} {--c} {--r} {--u}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate service classes (Getter, Creator, Updater)';

    /**
     * Filesystem instance.
     *
     * @var Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * Constructor.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $servicePath = app_path("Services/{$name}");

        // Check if the directory for the repository exists
        $repositoryPath = app_path("Repositories/{$name}");
        if (!file_exists($repositoryPath)) {
            // If the repository doesn't exist, ask if the user wants to create it
            if ($this->confirm("Repository {$name} does not exist. Do you want to create it?", true)) {
                // Logic to create repository files (You can add your logic to create repository here)
                Artisan::call('make:repository', ['path' => $name, 'model' => $name]);
            }
        }

        // Ensure directory exists
        if (!$this->filesystem->exists($servicePath)) {
            $this->filesystem->makeDirectory($servicePath, 0755, true);
        }

        $nameVariable = lcfirst($name);

        // Check if no flags are provided, and generate all classes if that's the case
        if (!$this->option('c') && !$this->option('r') && !$this->option('u')) {
            // Generate all classes if no flags are provided
            $this->generateFile($servicePath, "{$name}Creator.php", $this->creatorTemplate($name, $nameVariable));
            $this->generateFile($servicePath, "{$name}Getter.php", $this->getterTemplate($name, $nameVariable));
            $this->generateFile($servicePath, "{$name}Updater.php", $this->updaterTemplate($name, $nameVariable));
        } else {
            // Generate files based on the flags
            if ($this->option('c')) {
                $this->generateFile($servicePath, "{$name}Creator.php", $this->creatorTemplate($name, $nameVariable));
            }

            if ($this->option('r')) {
                $this->generateFile($servicePath, "{$name}Getter.php", $this->getterTemplate($name, $nameVariable));
            }

            if ($this->option('u')) {
                $this->generateFile($servicePath, "{$name}Updater.php", $this->updaterTemplate($name, $nameVariable));
            }
        }

        $this->info("Service classes for {$name} have been created successfully.");
    }

    /**
     * Generate a file with given content.
     */
    protected function generateFile($path, $filename, $content)
    {
        $file = "{$path}/{$filename}";
        if (!$this->filesystem->exists($file)) {
            $this->filesystem->put($file, $content);
            $this->info("Created: {$file}");
        } else {
            $this->warn("Skipped: {$file} (already exists)");
        }
    }

    /**
     * Getter class template.
     */
    protected function getterTemplate($name, $nameVariable)
    {
        return <<<PHP
        <?php

        namespace App\Services\\{$name};

        use Illuminate\Http\Request;
        use App\Repositories\\{$name}\\{$name}Repository;
        use Illuminate\Contracts\Pagination\LengthAwarePaginator;

        /**
         * Class {$name}Getter
         * @package App\Services\\$name
         */
        class {$name}Getter
        {
            /**
             * @var {$name}Repository
             */
            protected \${$nameVariable}Repository;

            /**
             * {$name}Getter constructor.
             * @param {$name}Repository \${$nameVariable}Repository
             */
            public function __construct({$name}Repository \${$nameVariable}Repository)
            {
                \$this->{$nameVariable}Repository = \${$nameVariable}Repository;
            }

            /**
             * Get paginated \${$nameVariable} list
             * @param Request \$request
             * @return LengthAwarePaginator
             */
            public function getPaginatedList(Request \$request): LengthAwarePaginator
            {
                return \$this->{$nameVariable}Repository->getPaginatedList(\$request);
            }

            /**
             * Get a single \${$nameVariable}
             * @param \$id
             * @return Object|null
             */
            public function show(\$id)
            {
                return \$this->{$nameVariable}Repository->findOrFail(\$id);
            }
        }
        PHP;
    }

    /**
     * Creator class template.
     */
    protected function creatorTemplate($name, $nameVariable)
    {
        return <<<PHP
        <?php

        namespace App\Services\\{$name};

        use App\Repositories\\{$name}\\{$name}Repository;
        use Carbon\Carbon;
        use Exception;
        use Illuminate\Support\Facades\DB;

        class {$name}Creator
        {
            /**
             * @var {$name}Repository
             */
            protected \${$nameVariable}Repository;

            /**
             * {$name}Creator constructor.
             * @param {$name}Repository \${$nameVariable}Repository
             */
            public function __construct(
                {$name}Repository \${$nameVariable}Repository,
            ) {
                \$this->{$nameVariable}Repository = \${$nameVariable}Repository;
            }

            /**
             * Store an \${$name}
             * @param array \$data
             * @return \Illuminate\Database\Eloquent\Model
             */
            public function store(array \$data)
            {
                try {
                    DB::beginTransaction();
                    \$item = \$this->{$nameVariable}Repository->store(\$data);
                    DB::commit();
                    return \$item->refresh();
                } catch (Exception \$e) {
                    DB::rollBack();
                    throw \$e;
                }
            }
        }
        PHP;
    }

    /**
     * Updater class template.
     */
    protected function updaterTemplate($name, $nameVariable)
    {
        return <<<PHP
        <?php

        namespace App\Services\\{$name};

        use App\Repositories\\{$name}\\{$name}Repository;
        use Exception;
        use Illuminate\Database\Eloquent\ModelNotFoundException;
        use Illuminate\Support\Facades\DB;
        use Illuminate\Support\Facades\Log;


        class {$name}Updater
        {
            /**
             * @var {$name}Repository
             */
            protected \${$nameVariable}Repository;

            /**
             * {$name}Updater constructor.
             * @param {$name}Repository \${$nameVariable}Repository
             */
            public function __construct(
                {$name}Repository \${$nameVariable}Repository,
            ) {
                \$this->{$nameVariable}Repository = \${$nameVariable}Repository;
            }

            /**
             * Update an  \${$name}
             * @param array \$data
             * @return \Illuminate\Database\Eloquent\Model
             */
            public function update(int \$id, array \$data)
            {
                try {
                    \$this->{$nameVariable}Repository->findOrFail(\$id);
                    \$this->{$nameVariable}Repository->update(\$id, \$data);
                    return \$this->{$nameVariable}Repository->find(\$id);
                } catch (ModelNotFoundException \$e) {
                    Log::warning("{$name} item with ID {\$id} not found: " . \$e->getMessage());
                    throw new Exception(__("{$name} item not found."), 404);
                } catch (Exception \$e) {
                    DB::rollBack();
                    Log::error("Error updating {$name} item with ID {\$id}: " . \$e->getMessage());
                    throw new Exception(__("An unexpected error occurred while updating the {$name} item."));
                }
            }

            public function destroy(int \$id)
            {
                try {
                    \$item = \$this->{$nameVariable}Repository->findOrFail(\$id);
                    \$item->delete();
                    return true;
                } catch (ModelNotFoundException \$e) {
                    throw \$e;
                } catch (Exception \$e) {
                    Log::error("Failed to delete {$name} item with ID {\$id}: " . \$e->getMessage());
                    throw \$e;
                }
            }
        }
        PHP;
    }
}
