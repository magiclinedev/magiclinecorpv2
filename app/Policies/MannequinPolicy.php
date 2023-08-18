<?php

namespace App\Policies;

use App\Models\Mannequin;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MannequinPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Mannequin $mannequin): bool
    {
        return in_array($user->status, [1, 2]);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->status, [1, 2]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Mannequin $mannequin): bool
    {
        return in_array($user->status, [1, 2]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Mannequin $mannequin): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Mannequin $mannequin): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Mannequin $mannequin): bool
    {
        //
    }
}
