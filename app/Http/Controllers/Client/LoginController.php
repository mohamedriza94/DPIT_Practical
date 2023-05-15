<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    
    /**
    * Where to redirect users after login.
    *
    * @var string
    */
    protected $redirectTo = RouteServiceProvider::CLIENT;
    
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('guest:client')->except('logout');
    }
    
    /**
    * Get the guard to be used during authentication.
    *
    * @return \Illuminate\Contracts\Auth\StatefulGuard
    */
    protected function guard()
    {
        return Auth::guard('client');
    }
    
    /**
    * Show the application's login form.
    *
    * @return \Illuminate\Http\Response
    */
    public function showLoginForm()
    {
        $view_data['title'] = 'Login';
        return view('client.login', $view_data);
    }
    
    public function validateLogin(Request $request)
    {
        // Attempt to log the user in
        if ($this->guard()->attempt(['email' => $request->email, 
        'password' => $request->password])) {

            return redirect()->intended(route('client.dashboard'));
        } 
        
        // if unsuccessful, then redirect back to the login with the form data
        return redirect()->back()->withInput($request->only('email'))->withErrors([
            'password' => 'Invalid Email or Password!'
        ]);
    }
    
    protected function redirectPath()
    {
        return method_exists($this, 'redirectTo')
        ? $this->redirectTo()
        : (property_exists($this, 'redirectToRoute')
        ? redirect()->route($this->redirectToRoute)
        : redirect()->route('client.dashboard'));
    }
    
    /**
    * Log the user out of the application.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function logout(Request $request)
    {   
        $this->guard()->logout();
        Session::flush();
        $request->session()->regenerate(true);

        return redirect()->route('client.login');
    }
}
