<?php
/**
 * User model
 *
 * @author      Michał Adamiak <michal.adamiak@spj.com.pl>
 */
Route::get('/', 'BaseController@indexAction');
Route::get('/login', 'BaseController@loginAction');
Route::get('/account', 'BaseController@userAccountAction');
Route::get('/logout', 'BaseController@logoutAction');
Route::post('/authenticate', 'BaseController@authenticateAction');
Route::post('/make_deposit', 'BaseController@makeDepositAction');
Route::post('/make_bet', 'BaseController@makeBetAction');