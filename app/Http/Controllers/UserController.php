<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\OrangTua;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%"))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('user.index', compact('users'));
    }

    public function create()
    {
        return view('user.create', [
            'guruList' => Guru::whereDoesntHave('user')->orderBy('nama')->get(),
            'orangTuaList' => OrangTua::whereDoesntHave('user')->orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('user.edit', [
            'user' => $user,
            'guruList' => Guru::whereDoesntHave('user')->orWhere('id', $user->guru_id)->orderBy('nama')->get(),
            'orangTuaList' => OrangTua::whereDoesntHave('user')->orWhere('id', $user->orang_tua_id)->orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $this->validateData($request, $user);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
    }

    private function validateData(Request $request, ?User $user = null): array
    {
        $passwordRule = $user ? ['nullable', 'string', 'min:8', 'confirmed'] : ['required', 'string', 'min:8', 'confirmed'];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'role' => ['required', 'in:admin,guru,orang_tua'],
            'password' => $passwordRule,
            'guru_id' => ['nullable', 'required_if:role,guru', 'exists:guru,id'],
            'orang_tua_id' => ['nullable', 'required_if:role,orang_tua', 'exists:orang_tua,id'],
        ]);

        // Kosongkan field yang tidak relevan dengan role terpilih
        if ($validated['role'] !== 'guru') {
            $validated['guru_id'] = null;
        }
        if ($validated['role'] !== 'orang_tua') {
            $validated['orang_tua_id'] = null;
        }

        return $validated;
    }
}
