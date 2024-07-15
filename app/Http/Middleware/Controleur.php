<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Controleur
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = User::where('users.id',Auth::user()->id)->join('avoirs', 'users.id','=','avoirs.user_id')
        ->join('roles', 'roles.id','=','avoirs.role_id')->where('libelle', 'CONTROLEUR VENTE')->exists();
        if ($user) {
            return $next($request);
        }else{
            abort(403);
        }
    }
}
