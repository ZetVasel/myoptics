<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Frontend\BaseController;
use App\Models\Page;
// use Illuminate\Foundation\Auth\ResetsPasswords;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Password;
use Mail;
use Hash;
// use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class PasswordController extends BaseController
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

    // use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        parent::__construct();
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEmail()
    {
        $page = Page::whereSlug('reset_pwd')->firstOrFail();


        return view('auth.password', compact(['page']));
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postEmail(Request $request)
    {
        $email = $request->get('email');
        $password = rand(9999, 99999);
        User::whereEmail($email)->update(['password' => Hash::make($password)]);
        $user = User::whereEmail($email)->first();
        Mail::send('emails.password', ['user' => $user, 'newPassword' => $password, 'site' => $_SERVER['HTTP_HOST']], function ($m) use ($user) {
            $m->from('noreply@'.$_SERVER['HTTP_HOST'], $_SERVER['HTTP_HOST']);
            $m->to($user->email, $user->firstName)->subject('Новый пароль!');
        });

        return redirect('/')->with('message' , 'Новый пароль отправле на вашу почту');

    }

    /**
     * Get the e-mail subject line to be used for the reset link email.
     *
     * @return string
     */
    protected function getEmailSubject()
    {
        return isset($this->subject) ? $this->subject : 'Ссылка для восстановления пароля';
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function getReset($token = null)
    {
        $page = Page::whereSlug('reset_pwd')->firstOrFail();
        if (is_null($token)) {
            throw new NotFoundHttpException;
        }


        return view('auth.reset', compact(['token', 'page']));
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postReset(Request $request)
    {

        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );



        $response = Password::reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });


        switch ($response) {
            case Password::PASSWORD_RESET:
                return redirect($this->redirectPath());

            default:
                return redirect()->back()->withInput($request->only('email'))->withErrors(['email' => trans($response)]);
        }
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = bcrypt($password);

        $user->save();

        Auth::login($user);
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (property_exists($this, 'redirectPath')) {
            return $this->redirectPath;
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/';
    }
}
