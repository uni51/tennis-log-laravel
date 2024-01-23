<?php

namespace App\Policies;

use App\Models\Memo;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class MemoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Memo  $memo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Memo $memo)
    {
        //
    }

    /**
     * Determine whether the user can view the dashboard memo.
     *
     * @param  User  $user
     * @param  Memo  $memo
     * @return Response|bool
     */
    public function dashboardMemoShow(User $user, Memo $memo): Response|bool
    {
        return $user->id === $memo->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }


    /**
     * Determine whether the user can create dashboard memo.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Memo $memo)
    {
        return $user->id === $memo->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Memo  $memo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Memo $memo)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Memo  $memo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Memo $memo)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Memo  $memo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Memo $memo)
    {
        //
    }
}
