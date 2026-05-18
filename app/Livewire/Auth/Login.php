<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected function throttleKey()
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            $seconds = RateLimiter::availableIn($this->throttleKey());
            $this->addError('email', "Demasiados intentos fallidos. Por favor, intente de nuevo en {$seconds} segundos.");
            return;
        }

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::clear($this->throttleKey());
            session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        RateLimiter::hit($this->throttleKey(), 60);

        $this->addError('email', 'Las credenciales proporcionadas no coinciden con nuestros registros.');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('components.layouts.app', ['title' => 'Iniciar Sesión']);
    }
}
