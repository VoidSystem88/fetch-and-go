<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        // Logout the user after registration
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Redirect to welcome page
        return redirect('/');
    }
}