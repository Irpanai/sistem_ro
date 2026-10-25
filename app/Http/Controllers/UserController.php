<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('area', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role') && $request->role !== 'Semua') {
            $query->where('role', $request->role);
        }

        if ($request->filled('area') && $request->area !== 'Semua') {
            $query->where('area', 'like', "%{$request->area}%");
        }

        $users = $query->orderByDesc('created_at')->paginate(12)->withQueryString();

        $totalUsers = User::count();
        $adminCount = User::where('role', 'admin')->count();
        $technicianCount = User::where('role', 'teknisi')->count();
        $clientCount = User::where('role', 'klien')->count();

        return view('users.index', compact(
            'users',
            'totalUsers',
            'adminCount',
            'technicianCount',
            'clientCount'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,teknisi,klien',
            'phone' => 'nullable|string|max:30',
            'area' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'technician_status' => 'nullable|in:Available,On Field,Off Duty',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        if ($validated['role'] === 'teknisi') {
            $validated['technician_status'] = $validated['technician_status'] ?? 'Available';
            $validated['rating'] = 5.0;
        }

        User::create($validated);

        $roleLabel = match ($validated['role']) {
            'admin' => 'Admin Kantor (Banjarmasin)',
            'teknisi' => 'Teknisi Lapangan',
            'klien' => 'Klien Pelanggan',
        };

        return redirect()->route('users.index')
            ->with('success', "Pengguna baru [{$validated['name']}] ({$roleLabel}) berhasil didaftarkan ke sistem.");
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,teknisi,klien',
            'phone' => 'nullable|string|max:30',
            'area' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'technician_status' => 'nullable|in:Available,On Field,Off Duty',
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', "Data pengguna [{$user->name}] berhasil diperbarui.");
    }

    public function destroy(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return back()->with('warning', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "Pengguna [{$name}] berhasil dihapus dari sistem.");
    }

    public function printDocument(User $user): View
    {
        return view('users.print', compact('user'));
    }
}
