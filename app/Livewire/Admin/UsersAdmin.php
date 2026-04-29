<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

class UsersAdmin extends Component
{
    public $users;
    public $view = 'list'; // 'list', 'create', 'edit'
    
    public $name, $email, $password, $role = 'staff', $selected_user_id;

    public function mount()
    {
        if (auth()->user()->role !== User::ROLE_SUPER_ADMIN) {
            abort(403);
        }
    }

    public function openCreate()
    {
        $this->reset(['name', 'email', 'password', 'role', 'selected_user_id']);
        $this->view = 'create';
    }

    public function saveUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:super_admin,organizer,staff',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        $this->view = 'list';
        session()->flash('message', 'Usuario creado con éxito.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $this->selected_user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = '';
        $this->view = 'edit';
    }

    public function updateUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->selected_user_id,
            'role' => 'required|in:super_admin,organizer,staff',
        ]);

        $user = User::findOrFail($this->selected_user_id);
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);

        $this->view = 'list';
        session()->flash('message', 'Usuario actualizado con éxito.');
    }

    public function deleteUser($id)
    {
        if (auth()->id() == $id) {
            session()->flash('error', 'No puedes eliminarte a ti mismo.');
            return;
        }

        User::find($id)?->delete();
        session()->flash('message', 'Usuario eliminado.');
    }

    public function render()
    {
        $this->users = User::orderBy('name')->get();
        return view('livewire.admin.users-admin')
            ->layout('components.layouts.admin', ['title' => 'Gestión de Usuarios']);
    }
}
