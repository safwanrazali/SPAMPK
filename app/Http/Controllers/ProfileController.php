<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:4', 'max:50', 'alpha_dash', Rule::unique('users', 'username')->ignore($user->id)],
            'current_password' => ['nullable', 'required_with:password', 'string'],
            'password' => ['nullable', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ], [], [
            'name' => 'nama',
            'username' => 'nama pengguna',
            'current_password' => 'kata laluan semasa',
            'password' => 'kata laluan baharu',
        ]);

        if (! empty($data['password'])) {
            if (! Hash::check($data['current_password'] ?? '', $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Kata laluan semasa tidak tepat.'])
                    ->onlyInput('name', 'username');
            }

            $user->password = $data['password'];
        }

        $user->name = $data['name'];
        $user->username = $data['username'];
        $user->save();

        return back()->with('success', 'Profil anda berjaya dikemas kini.');
    }
}