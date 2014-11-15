<?php
/**
 * Basic controller for application
 *
 * @package     controllers
 * @subpackage  BaseController
 * @author      Michał Adamiak <michal.adamiak@spj.com.pl>
 */

class BaseController extends Controller
{
    /**
     * check that user is logged in and redirect to correct page
     *
     * @return void
     */
    public function indexAction()
    {
        if (Auth::check()) {
            return Redirect::to('/account');
        }

        return Redirect::to('/login');
    }

    /**
     * show up login form
     * 
     * @return string
     */
    public function loginAction()
    {
        $errorMessage   = '';
        $successMessage = '';

        if (Session::has('errorMessage')) {
            $errorMessage = Session::get('errorMessage');
            Session::forget('errorMessage');
        }

        if (Session::has('successMessage')) {
            $successMessage = Session::get('successMessage');
            Session::forget('successMessage');
        }

        return View::make('login', [
            'errorMessage'      => $errorMessage,
            'successMessage'    => $successMessage,
        ]);
    }

    /**
     * try to authenticate user
     * 
     * @return mixed
     */
    protected function authenticateAction()
    {
        if (!Input::has('email') || !Input::has('password')) {
            Session::set('message', 'You need to give login and password');
            return Redirect::to('/login');
        }

        if (Auth::attempt(
                [
                    'email'     => Input::get('email'),
                    'password'  => Input::get('password')
                ],
                true
            )
        ) {
            $user = User::find(Auth::id());
            $user->log = $user->log +1;
            $user->save();

            return Redirect::to('/account');
        } else {
            Session::set('errorMessage', 'Login or password is incorrect');
            return Redirect::to('/login');
        }
    }

    /**
     * show up user page
     * 
     * @return mixed
     */
    public function userAccountAction()
    {
        if (Auth::check()) {
            $user = User::find(Auth::id());
            Debugbar::info([
                'user_id'       => $user->user_id,
                'user_name'     => $user->name . ' ' . $user->last_name,
                'email'         => $user->email,
                'log'           => $user->log,
            ]);

            return View::make('account', ['user' => $user->name . ' ' . $user->last_name]);
        }

        Session::set('errorMessage', 'You are not logged in');
        return Redirect::to('/login');
    }

    /**
     * log out user
     * 
     * @return mixed
     */
    public function logoutAction()
    {
        Session::set('successMessage', 'You are log out');
        Auth::logout();

        return Redirect::to('/');
    }
}
