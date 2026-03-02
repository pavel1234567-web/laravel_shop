<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Использовать поле 'login' вместо 'email'
     */
    public function username()
    {
        return 'login'; // ← поле из формы
    }

    /**
     * Авторизация по login (может быть email или name)
     * Переопределяем credentials чтобы искать по email
     */
    protected function credentials(\Illuminate\Http\Request $request)
    {
        // dd($request->all()); // ← покажет что пришло из формы
        return [
            'name' => $request->input('login'), // ищем по name
            // 'email'    => $request->input('login'), // ищем в БД по полю email
            'password' => $request->input('password'),
        ];
    }

    /**
     * Сообщение после успешного входа
     */
    protected function authenticated(Request $request, $user)
    {
        session()->flash('success', 'Добро пожаловать, ' . $user->name . '!');
        return redirect($this->redirectTo);
    }

    /**
     * Сообщение после выхода
     */
    protected function loggedOut(Request $request)
    {
        session()->flash('info', 'Вы вышли из системы.');
        return redirect('/');
    }

}

