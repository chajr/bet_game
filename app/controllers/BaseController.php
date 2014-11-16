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
        return View::make('login', $this->_getMessages());
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
            /** @var User $user */
            $user       = User::find(Auth::id());
            $user->log  += 1;
            $message    = $user->checkForLoginBonus();
            Session::set('successMessage', $message);
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
            /** @var User $user */
            $user = User::find(Auth::id());
            Debugbar::info([
                'user_id'       => $user->user_id,
                'user_name'     => $user->name . ' ' . $user->last_name,
                'email'         => $user->email,
                'log'           => $user->log,
            ]);

            $variables          = $this->_getMessages();
            $variables['user']  = $user->name . ' ' . $user->last_name;
            $variables          = array_merge($user->getUserWallets(), $variables);

            return View::make('account', $variables);
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

    /**
     * allow to add user real money by ajax
     * 
     * @return string
     */
    public function makeDepositAction()
    {
        $response = [
            'status'    => 'fail',
            'message'   => ''
        ];

        if (!Input::has('value')) {
            $response['message'] = 'You must give some value';
        }

        if (!preg_match('#^([\d]+)([,.][\d]+)?$#', Input::get('value'))) {
            $response['message'] = 'Given value is incorrect (only numbers separated by , or .)';
        }

        if (Auth::check()) {
            /** @var Wallet $wallet */
            $wallet = Wallet::where('user_id', '=', Auth::id())
                ->where('origin', '=', 1)
                ->get();

            $wallet->first()->value = $wallet->first()->value + Input::get('value');
            $wallet->first()->save();

            $response['message'] = 'Amount has ben added';
            $response['status']  = 'success';
        } else {
            $response['message'] = 'You are not logged in';
        }

        return json_encode($response);
    }

    /**
     * return messages for user
     * 
     * @return array
     */
    protected function _getMessages()
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

        return [
            'errorMessage'      => $errorMessage,
            'successMessage'    => $successMessage,
        ];
    }
}
