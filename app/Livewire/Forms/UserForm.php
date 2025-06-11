<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Form;

class UserForm extends Form
{
    public ?User $user;

    public ?int $userId = null;
    public string $name = '';
    public string $email = '';
    public string $role = 'mahasiswa';
    public ?string $password = null;
    public ?string $password_confirmation = null;

    /**
     * Define the validation rules for the password.
     */
    protected function passwordRules(): array
    {
        return ['required', 'string', Password::min(8), 'confirmed'];
    }

    /**
     * Define the main validation rules for the form.
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->userId),
            ],
            'role' => ['required', Rule::in(['admin', 'mahasiswa'])],
        ];

        if (!$this->userId) {
            // Password is required when creating a new user.
            $rules['password'] = $this->passwordRules();
        } else {
            // Password is optional when editing, but must be valid if provided.
            $rules['password'] = ['nullable', 'string', Password::min(8), 'confirmed'];
        }

        return $rules;
    }

    /**
     * Set the form properties from an existing User model.
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = null;
        $this->password_confirmation = null;
    }

    /**
     * Get the validated and cleaned data from the form.
     */
    public function data(): array
    {
        // Get all validated properties from the form.
        $data = $this->only(['name', 'email', 'role']);
        
        // Only include the password if it's not empty.
        if ($this->password) {
            $data['password'] = $this->password;
        }
        
        return $data;
    }
}