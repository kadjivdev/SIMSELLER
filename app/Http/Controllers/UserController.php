<?php

namespace App\Http\Controllers;

use App\Mail\MessagePasseword;
use App\Models\Representant;
use App\Models\UpdateVente;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $users = User::join('representants', 'representants.id', '=', 'representent_id')
            ->select('users.id', 'users.name', 'users.email', 'representants.nom', 'representants.prenom')->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $representents = Representant::all();

        return view('users.create', compact('representents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'representent_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required', 'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
        ]);

        $user = User::create([
            'representent_id' => $request->representent_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        #3. Envoi du mail
        Mail::to($user)->queue(new MessagePasseword($request->password));


        if ($user) {
            Session()->flash('message', 'Utilisateur ajouté avec succès!');
            return redirect()->route('users.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */

    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        $representents = Representant::all();
        $zones = Zone::all();
        return view('users.edit', compact('user', 'representents',"zones"));
    }


    public function update(Request $request, User $user)
    {
        if ($request->password == NULL) {

            $request->validate([
                'representent_id' => ['required', 'integer'],
                'zone_id' => ['required', 'integer'],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['string', 'email'],
            ]);

            $verif = User::all()->whereNotIn('id', $user->id)->firstWhere('email', '=', $request->email);

            if ($verif) {
                Session()->flash('error', 'L\'email existe déjà');
                return redirect()->route('users.index');
            }

            $user->representent_id = $request->representent_id;
            $user->zone_id = $request->zone_id;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->update();

            if ($user) {
                Session()->flash('message', 'Utilisateur modifié avec succès!');
                return redirect()->route('users.index');
            }
        } else {

            $request->validate([
                'representent_id' => ['required', 'integer'],
                'zone_id' => ['required', 'integer'],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['string', 'email', 'max:255', 'unique:users'],
                'password' => [
                    'required', 'confirmed',
                    Password::min(8)
                        ->letters()
                        ->numbers()
                        ->symbols()
                        ->uncompromised()
                ],
            ]);

            $verif = User::all()->whereNotIn('id', $user->id)->firstWhere('email', '=', $request->email);

            if ($verif) {

                Session()->flash('error', 'L\'email existe déjà');
                return redirect()->route('users.index');
            }

            $user = $user->update([
                'representent_id' => $request->representent_id,
                'zone_id' => ['required', 'integer'],
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            #3. Envoi du mail
            Mail::to($user)->queue(new MessagePasseword($request->password));


            if ($user) {
                Session()->flash('message', 'Utilisateur modifié avec succès!');
                return redirect()->route('users.index');
            }
        }
    }


    public function delete(User $user)
    {
        return view('users.delete', compact('user'));
    }

    public function destroy(User $user)
    {
        $users = $user->delete();
        if ($users) {
            Session()->flash('message', 'Utilisateur supprimé avec succès!');
            return redirect()->route('users.index');
        }
    }
}
