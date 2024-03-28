<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExpensePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Expense $expense)
    {
        return $user->id === $expense->user_id
        ? Response::allow()
        : response()->json(['message' => 'You do not own this expense.'], 403);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Expense $expense)
    {
        return $user->id === $expense->user_id
        ? Response::allow()
        : response()->json(['message' => 'You do not own this expense.'], 403);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Expense $expense)
    {
        return $user->id === $expense->user_id
        ? Response::allow()
        : response()->json(['message' => 'You do not own this expense.'], 403);
    }


}
