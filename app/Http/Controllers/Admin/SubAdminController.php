<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SubAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'sub_admin');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name',  'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $subAdmins = $query->orderBy('created_at', 'desc')
                           ->paginate($request->get('entries', 10));

        return view('admin.setup.sub-admins.sub-admins', [
            'mode'      => 'index',
            'subAdmins' => $subAdmins,
        ]);
    }

    public function create()
    {
        return view('admin.setup.sub-admins.sub-admins', [
            'mode'   => 'create',
            'record' => null,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:mongodb.users,email',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'sub_admin',
            'is_active' => true,
        ]);

        return redirect()->route('admin.setup.sub-admins.index')
                         ->with('success', 'Sub Admin created successfully.');
    }

    public function edit($id)
    {
        $record = User::where('role', 'sub_admin')->findOrFail($id);

        return view('admin.setup.sub-admins.sub-admins', [
            'mode'   => 'edit',
            'record' => $record,
        ]);
    }

    public function update(Request $request, $id)
    {
        $record = User::where('role', 'sub_admin')->findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:mongodb.users,email,' . $id . ',_id',
            'password' => 'nullable|string|min:8',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $record->update($data);

        return redirect()->route('admin.setup.sub-admins.index')
                         ->with('success', 'Sub Admin updated successfully.');
    }

    public function destroy($id)
    {
        User::where('role', 'sub_admin')->findOrFail($id)->delete();

        return redirect()->route('admin.setup.sub-admins.index')
                         ->with('success', 'Sub Admin deleted.');
    }
}