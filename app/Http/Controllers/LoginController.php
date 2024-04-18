<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Empresa;
use Auth;

class LoginController extends Controller
{
    public function showLoginForm(){
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        //$credentials = $request->only('username', 'password');
        $credentials['username'] = strtolower($request->username);
        $credentials['password'] = $request->password;
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->estado == 1) {
                return redirect()->intended('/dashboard');
            } else {
                Auth::logout();
                return redirect()->route('login')->with(['danger_message' => 'Usuario No Autorizado']);
            }
        }

        return redirect()->route('login')->with(['danger_message' => 'Credenciales inválidas']);
    }

    public function dominio($user){
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $url = explode("/", $url);
        if($url[2] == 'localhost:8000'){
            return true;
        }
        if($user->id != 1){
            $empresa = Empresa::find($user->empresa_id);
            if($empresa != null){
                if($url[2] == $empresa->dominio){
                    return true;
                }
            }
            return false;
        }
        return true;
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
