<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Mail\SendForgotPasswordEmail;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->getCredentials();

        if(!Auth::validate($credentials)):
            return redirect()->to('login')
                ->withErrors(trans('auth.failed'));
        endif;

        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        Auth::login($user);

        return $this->authenticated($request, $user);
    }

    protected function authenticated(Request $request, $user)
    {
        return redirect()->intended();
    }

    public function forgotPasswordShow()
    {
        return view('auth.forgot_password');
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $temp_password = bin2hex(openssl_random_pseudo_bytes(6));
        $user = User::where('email',$request->email)->first();
        if($user != null){
            $user->password = $temp_password;
            $user->save();

            $message['name'] = $user->name;
            $message['email'] = $user->email;
            $message['password'] = $temp_password;
            $email = new SendForgotPasswordEmail($message);
            try{
                Mail::to($request->email)->send($email);
                return redirect()->back()->with('message', 'Successfully sent an email.');
            }catch (Exception $exception){

            }
        }

        return redirect()->back()->with('message', 'Something went wrong. Please try again.');
    }
}