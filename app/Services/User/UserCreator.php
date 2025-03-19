<?php

namespace App\Services\User;

use App\Repositories\User\UserRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class UserCreator
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UserCreator constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository,
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * Store an $User
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(array $data)
    {
        try {
            DB::beginTransaction();
            $item = $this->userRepository->store($data);
            DB::commit();
            return $item->refresh();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}