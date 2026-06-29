<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::orderBy('name')->paginate(15);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:4', 'max:50', 'alpha_dash', 'unique:users,username'],
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
            'password' => ['required', 'confirmed', $this->passwordRule()],
            'is_active' => ['nullable', 'boolean'],
        ], [], $this->attr());

        $data['is_active'] = $request->boolean('is_active', true);

        User::create($data);

        return redirect()->route('users.index')
            ->with('success', 'Akaun pengguna berjaya dicipta.');
    }

    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:4', 'max:50', 'alpha_dash', Rule::unique('users', 'username')->ignore($user->id)],
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
            'password' => ['nullable', 'confirmed', $this->passwordRule()],
            'is_active' => ['nullable', 'boolean'],
        ], [], $this->attr());

        $user->name = $data['name'];
        $user->username = $data['username'];
        $user->role = $data['role'];
        $user->is_active = $request->boolean('is_active');

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'Maklumat pengguna berjaya dikemas kini.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak boleh menyahaktifkan akaun sendiri.');
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        return back()->with('success', $user->is_active
            ? 'Akaun diaktifkan semula.'
            : 'Akaun telah dinyahaktifkan.');
    }

    /**
     * Dasar kata laluan: min 8 aksara, huruf besar + kecil, nombor, aksara khas.
     */
    private function passwordRule(): Password
    {
        return Password::min(8)->mixedCase()->numbers()->symbols();
    }

    private function attr(): array
    {
        return [
            'name' => 'nama',
            'username' => 'nama pengguna',
            'role' => 'peranan',
            'password' => 'kata laluan',
        ];
    }
}
