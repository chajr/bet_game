<?php
/**
 * Basic controller for application
 *
 * @package     controllers
 * @subpackage  BaseController
 * @author      Michał Adamiak <michal.adamiak@spj.com.pl>
 */

use Zend\Json\Json;

class BaseController extends Controller
{
    /**
     * common regular expression to validate money value
     */
    const MONEY_EXPRESSION = '#^([\d]+)([,.]{1}[\d]+)?$#';

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
        return View::make('login', $this->getMessages());
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
            Session::set('errorMessage', 'Login or password are incorrect');
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

            $variables          = $this->getMessages();
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

        if (!preg_match(self::MONEY_EXPRESSION, Input::get('value'))) {
            $response['message'] = 'Given value is incorrect (only numbers separated by , or .)';
        }

        if (Auth::check()) {
            /** @var Wallet $wallet */
            $wallet = Wallet::where('user_id', '=', Auth::id())
                ->where('origin', '=', 1)
                ->get();

            $wallet->first()->value = $wallet->first()->value + Input::get('value');
            $wallet->first()->save();

            /** @var User $user */
            $user       = User::find(Auth::id());
            $message    = $user->checkForDepositBonus(Input::get('value'));

            $response['message'] = 'Amount has ben added. ' . $message;
            $response['status']  = 'success';
        } else {
            $response['message'] = 'You are not logged in';
        }

        $response = Response::make(Json::encode($response), 200);
        return $response;
    }

    /**
     * return messages for user
     * 
     * @return array
     */
    protected function getMessages()
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

    /**
     * check that user win or loose and send message
     * 
     * @return string
     */
    public function makeBetAction()
    {
        $flag     = true;
        $response = [
            'status'    => 'fail',
            'message'   => ''
        ];

        /** @var Wallet $userRealWallet */
        $userRealWallet = Wallet::where('user_id', '=', Auth::id())
            ->where('origin', '=', 1)
            ->get()->first();

        if ($userRealWallet->value <= 0) {
            $response['message'] = 'Not enough funds to place a bet';
            $flag                = false;
        }

        $availableFunds = DB::table('wallets')
            ->where('user_id', '=', Auth::id())
            ->where('status', '=', 1)
            ->sum('value');

        if ($availableFunds < Input::get('value')) {
            $response['message'] = 'Not enough funds to place that bet';
            $flag                = false;
        }

        if ($flag) {
            $response = $this->makeBet($response);
        }

        $jsonResponse = Response::make(Json::encode($response), 200);
        return $jsonResponse;
    }

    /**
     * place a bet for user
     * 
     * @param $response
     * @return mixed
     */
    protected function makeBet($response)
    {
        if (Input::has('value') && preg_match(self::MONEY_EXPRESSION, Input::get('value'))) {
            $response['status'] = 'success';
            $value              = Input::get('value');

            if ($this->winLose()) {
                $response['message']            = 'win';
                $response['data']['message']    = 'You win :)';

                /** @var Wallet $wallet */
                $wallet = new Wallet();
                $wallet->saveWin($value,  Auth::id());

            } else {
                $response['message']            = 'loose';
                $response['data']['message']    = 'You loose :(';
            }

        } else {
            $response['message'] = 'You must give bet amount or value is incorrect (only numbers separated by , or .)';
        }

        return $response;
    }

    /**
     * check that user should win or loose bet
     * 
     * @return bool
     */
    protected function winLose()
    {
        return (bool)mt_rand(0, 1);
    }
}
