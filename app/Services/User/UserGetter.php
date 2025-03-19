<?php

namespace App\Services\User;

use Illuminate\Http\Request;
use App\Repositories\User\UserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class UserGetter
 * @package App\Services\User
 */
class UserGetter
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UserGetter constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get paginated $user list
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getPaginatedList(Request $request): LengthAwarePaginator
    {
        return $this->userRepository->getPaginatedList($request);
    }

    /**
     * Get a single $user
     * @param $id
     * @return Object|null
     */
    public function show($id)
    {
        return $this->userRepository->findOrFail($id);
    }
}