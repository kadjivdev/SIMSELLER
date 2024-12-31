<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('libelle')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        request()->validate([
            'libelle' => ['required', 'string', 'max:255', 'unique:roles'],
        ]);

        $roles = Role::create([
            'libelle' => strtoupper($request->libelle),
        ]);

        if ($roles) {
            Session()->flash('message', 'Rôle ajouté avec succès!');
            return redirect()->route('roles.index');
        }
    }

    public function show(role $role)
    {
        return view('roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        request()->validate(
            [
                'libelle' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role->id)],
            ],
        );

        $roles = $role->update([
            'libelle' => strtoupper($request->libelle),
        ]);

        if ($roles) {
            Session()->flash('message', 'Rôle moidifié avec succès!');
            return redirect()->route('roles.index');
        }
    }

    public function delete(Request $request, Role $role)
    {
        return view('roles.delete', compact('role'));
    }

    public function destroy(Role $role)
    {
        $roles = $role->delete();
        if ($roles) {
            Session()->flash('message', 'Rôle supprimé avec succès!');
            return redirect()->route('roles.index');
        }
    }

    public function user($role)
    {
        return view('roles.avoir', compact('role'));
    }
}
