<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
class UserController extends Controller {
    public function index() {
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }

    public function create() {
        $roles = \Spatie\Permission\Models\Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);
        if ($request->filled('role')) {
            $user->assignRole($request->role);
        }
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function edit($id) {
        $user = User::findOrFail($id);
        $roles = \Spatie\Permission\Models\Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }
        $user->save();
        if ($request->filled('role')) {
            $user->syncRoles([$request->role]);
        }
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui!');
    }

    public function destroy($id) {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Anda tidak bisa menghapus akun Anda sendiri!']);
        }
        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus!');
    }
}
