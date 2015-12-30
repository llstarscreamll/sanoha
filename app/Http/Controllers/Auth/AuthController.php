<?php
namespace sanoha\Http\Controllers\Auth;

use Validator;
use sanoha\Models\User;
use Illuminate\Http\Request;
use sanoha\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers;

    private $messages = [
        'name.required'         =>  'El nombre es un campo obligatorio.',
        'name.max'              =>  'El nombre debe tener un máximo de :max cataracteres.',
        'name.min'              =>  'El nombre debe tener un mínimo de :min cataracteres.',
        'name.alpha_spaces'     =>  'El nombre sólo puede contener letras y/o espacios.',
        
        'lastname.min'          =>  'El apellido debe tener un mínimo de :min cataracteres.',
        'lastname.max'          =>  'El apellido debe tener un máximo de :max cataracteres.',
        'lastname.alpha_spaces' =>  'El apellido sólo puede contener letras y/o espacios.',

        'email.required'        =>  'La dirección de correo electrónico es un campo requerido.',
        'email.email'           =>  'Debes digitar una dirección de correo electrónico válida.',
        'email.max'             =>  'La dirección de correo no debe superar los :max caracteres.',
        'email.unique'          =>  'La dirección de correo ya está en uso.',

        'password.required'     =>  'Por favor digita una contraseña.',
        'password.confirmed'    =>  'Las contraseñas no coinciden.',
        'password.min'          =>  'La contraseña debe tener al menos :min caracteres.'
    ];
    
    /**
     * 
     */
    private $loginValidationMessages = [
        'email.required'    =>    'Debes digitar tu dirección de correo electrónico.',
        'email.email'        =>    'Digita una dirección de correo electrónico válida.',
        
        'password.required'    =>    'Digita tu contraseña.'
        ];

    /**
     * Create a new authentication controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard  $auth
     * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }
    
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email', 'password' => 'required',
        ], $this->loginValidationMessages);

        $credentials = $request->only('email', 'password');

        if (\Auth::attempt($credentials, $request->has('remember'))) {
            return redirect()->intended($this->redirectPath());
        }

        return redirect($this->loginPath())
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors([
                        'email' => $this->getFailedLoginMessage(),
                    ]);
    }
    
    /**
    * Get the failed login message.
    *
    * @return string
    */
    protected function getFailedLoginMessage()
    {
        return 'Correo o contraseña incorrectos, intenta de nuevo.';
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        return Validator::make($data, [
            'name'      => 'required|max:50|min:3|alpha_spaces',
            'lastname'  => 'required|max:50|min:3|alpha_spaces',
            'email'     => 'required|email|max:100|unique:users,email',
            'password'  => 'required|confirmed|min:6',
        ], $this->messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function create(array $data)
    {
        $user = User::create([
            'name'      =>  $data['name'],
            'lastname'  =>  $data['lastname'],
            'email'     =>  $data['email'],
            'password'  =>  bcrypt($data['password']),
        ]);
        
        $user->attachRole(1); // default user role = "user"
        
        return $user;
    }
}
