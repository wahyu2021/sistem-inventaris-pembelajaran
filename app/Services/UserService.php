<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Exception;

class UserService
{
    /**
     * Create a new user.
     *
     * @param array $data Data from the UserForm.
     * @return User
     */
    public function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Update an existing user's data.
     *
     * @param User $user The User model to update.
     * @param array $data Data from the UserForm.
     * @return User
     */
    public function updateUser(User $user, array $data): User
    {
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ];

        // Only update the password if a new one is provided.
        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        return $user;
    }

    /**
     * Delete a user.
     *
     * @param User $user The User model to delete.
     * @throws \Exception If the user tries to delete their own account.
     */
    public function deleteUser(User $user): void
    {
        if (Auth::id() === $user->id) {
            throw new Exception('Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Additional logic can be added here, e.g.,
        // to prevent deletion of the last admin account.

        $user->delete();
    }
}
