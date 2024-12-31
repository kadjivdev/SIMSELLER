<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Rules\AvoirRule;
use Illuminate\Http\Request;

class AvoirController extends Controller
{
    public function create(User $user)
    {
        $roles = Role::orderBy('libelle')->get();
        return view('avoirs.create', compact('user', 'roles'));
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'role_id' => ['required', new AvoirRule($user)],
        ]);

        $roles = $request->role_id;
        $tab = $request->all()['role_id'];
        $roles = $user->roles()->whereIn('id', $tab)->get();
        if ((in_array(1, $tab) || in_array(2, $tab))) {
            $rolesExist = $user->roles()->where('id', 2)->first();
            $rolesExist2 = $user->roles()->where('id', 1)->first();
            if ($rolesExist || $rolesExist2) {
                Session()->flash('error', 'vous ne pouvez pas associé Un Superviseur et un Gestionnaire ');
                return redirect()->route('avoirs.index', ['user' => $user->id]);
            }
        }

        if ((in_array(2, $tab)|| in_array(6, $tab))) {
            $rolesExist = $user->roles()->where('id', 6)->first();
            $rolesExist2 = $user->roles()->where('id', 2)->first();
            if ($rolesExist || $rolesExist2) {
                Session()->flash('error', 'vous ne pouvez pas associé un Gestionnaire  et un Validateur');
                return redirect()->route('avoirs.index', ['user' => $user->id]);
            }
        }
        if ((in_array(2, $tab)|| in_array(6, $tab))) {
            $rolesExist = $user->roles()->where('id', 6)->first();
            $rolesExist2 = $user->roles()->where('id', 2)->first();
            if ($rolesExist || $rolesExist2) {
                Session()->flash('error', 'vous ne pouvez pas associé un Gestionnaire  et un Validateur');
                return redirect()->route('avoirs.index', ['user' => $user->id]);
            }
        }

        $avoir ='';
        $roles = $request->role_id;


        foreach($roles as $role) {

            $avoir = $user->roles()->attach([$role ]);

        }

        if (!$avoir) {
            Session()->flash('message', 'Rôle(s) associé(s) à l\'utilisateur avec succès!');
            return redirect()->route('avoirs.index', ['user' => $user->id]);
        }
    }



    public function index(User $user)
    {
        return view('avoirs.index', compact('user'));
    }

    public function delete(User $user, Role $role)
    {
        $role = $user->roles()->findOrFail($role->id);

        return view('avoirs.delete', compact('role', 'user'));
    }

    public function destroy(User $user, Role $role)
    {
        $role = $user->roles()->detach([$role->id]);
        if ($role) {
            Session()->flash('message', "Rôle de l'utilisateur enlever avec succès!");
            return redirect()->route('avoirs.index', ['user' => $user->id]);
        }
    }
}
