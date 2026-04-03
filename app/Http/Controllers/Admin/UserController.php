<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', '!=', 'super_admin')
                     ->where('role', '!=', 'sub_admin');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name',  'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }

        $users = $query->orderBy('created_at', 'desc')
                       ->paginate($request->get('entries', 10));

        return view('admin.users.index', compact('users'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateBasic(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:mongodb.users,email,' . $id . ',_id',
            'mobile' => 'nullable|string|max:30',
        ]);

        $user->update([
            'name'   => $request->name,
            'email'  => $request->email,
            'mobile' => $request->mobile,
        ]);

        return redirect()->route('admin.users.edit', $id)
                         ->with('success', 'Basic details updated.')
                         ->with('active_tab', 'basic');
    }

    public function updateCompany(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'company_name' => 'nullable|string|max:255',
            'vat_id'       => 'nullable|string|max:100',
        ]);

        $user->update([
            'company_name'         => $request->company_name,
            'vat_id'               => $request->vat_id,
            'enable_editable'      => $request->boolean('enable_editable'),
            'allow_document_upload'=> $request->boolean('allow_document_upload'),
            'company_verified'     => $request->boolean('company_verified'),
            'show_verified_batch'  => $request->boolean('show_verified_batch'),
        ]);

        return redirect()->route('admin.users.edit', $id)
                         ->with('success', 'Company details updated.')
                         ->with('active_tab', 'company');
    }

    public function toggleCompanyVerified($id)
    {
        $user = User::findOrFail($id);
        $user->update(['company_verified' => !($user->company_verified ?? false)]);

        return redirect()->route('admin.users.edit', $id)
                         ->with('success', 'Company verification status updated.')
                         ->with('active_tab', session('active_tab', 'basic'));
    }
}