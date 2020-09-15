<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function reset(Request $request) {

        $this->validate($request,$this->rules(),$this->validationErrorMessages());
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        //dd($this->broker());    
        $response = $this->broker()->reset(
            $this->credentials($request),function($user,$password){
                $this->resetPassword($user,$password);
            }
        );
        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
                    ? $this->sendResetResponse($request,$response)
                    : $this->sendResetFailedResponse($request,$response);
    }

    protected function rules() {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|digits:8',
            'password_confirmation' => 'required|digits:8',
        ];
    }
    
    protected function validationErrorMessages() {
        return [
            'email' => 'Please enter valid email',
            'password.required' => 'The password field is required.',
            'password.confirmed' => 'The New password and Confirm password does not match.',
            'password_confirmation.required' => 'The Confirm password field is required.',
        ];
    }
    
    public function broker() {
        return Password::broker('users');
    }
    
    protected function credentials(Request $request) {
        return $request->only(
            'email','password','password_confirmation','token'
        );
    }
    
    protected function resetPassword($user,$password) {
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => Str::random(60),
        ])->save();
        // $this->guard()->login($user);
    }
    
    protected function sendResetResponse(Request $request,$response) {
        //dd('in sucess');
        $request->session()->flash('alert-success', 'Your password has beed successfully updated. Please login with new password.'); 
        return redirect(route('login'))
                            ->with('status',trans($response));
    }
    
    protected function sendResetFailedResponse(Request $request,$response) {
        //dd('in error');
        return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]);
    }
}
