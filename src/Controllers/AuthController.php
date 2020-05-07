<?php
namespace Raysirsharp\LaravelEasyAdmin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Auth;

class AuthController extends Controller
{
    /**
     * Display Login View
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('easy-admin::login');
    }
    
    /**
     * Login
     *
     * @return \Illuminate\Http\Redirect
     */
    public function login(Request $request)
    {
        //validate
        $credentials = $request->validate(
            [
                'email' => 'email|required|max:255',
                'password' => 'required|min:6'
            ]
        );
        
        //attempt login
        if (Auth::attempt($credentials)) {
            return redirect('/easy-admin')
                ->with('message', 'Login successful!');
        }
        //else return with message
        return redirect()->back()
            ->with('message', 'Login failed! Credentials provided are not valid.');
    }
    
    /**
     * Logout
     *
     * @return \Illuminate\Http\Redirect
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flush();
        return redirect('/easy-admin/login')
            ->with('message', 'Logout successful!');
    }
}
    
