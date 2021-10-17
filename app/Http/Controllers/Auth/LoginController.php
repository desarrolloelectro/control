<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;



use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;



class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = '/usuarios';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $usuario = Usuario::find($request['coduser']);
        //$usuario = Usuario::where('coduser',$request['coduser'])->first();
        if($usuario != null){
            if($usuario->contrasena == md5($request->contrasena) && $usuario->nivel_control != '0'){
                
                //Session::put('coduser',$usuario->coduser);
                //dd($usuario->coduser);
                Auth::loginUsingId($usuario->coduser);
                return redirect()->route('principal.index');
            }else{
                return redirect()->route('login')->with('mensaje', 'Error de autenticación!');
            }
        }else{
            return redirect()->route('login')->with('mensaje', 'Error de autenticación, verifique el nombre de usuario!');
        }
    }
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'contrasena' => 'required|string',
        ]);
    }
    public function username()
    {
        return 'coduser';
    }
}
