<?php

/**
 * 登录
 */
Route::group(['prefix' => 'login'], function (){
    Route::get('/', 'Login@index')->name('login');
    Route::post('login', 'Login@login');
    Route::get('logout', 'Login@logout');
});

Route::get('common/refresh_repeat_token/{old?}', 'Common@refreshRepeatToken');

/**
 * 需要登录的内容
 */
Route::group(['middleware' => ['auth:web', 'repeat', 'power']], function (){

    //公用
    Route::group(['prefix' => 'common'], function (){
        Route::get('/', 'Common@index');
        Route::post('img_upload', 'Common@imgUpload');
        Route::post('file_upload', 'Common@fileUpload');
    });

    Route::get('/', 'HomeController@index');
    Route::group(['prefix' => 'home'], function (){
        Route::get('', 'HomeController@index')->name('首页');
    });

    //公用
    Route::group(['prefix' => 'common'], function (){
        Route::get('/', 'Common@index');
        Route::post('img_upload', 'Common@imgUpload');
        Route::post('file_upload', 'Common@fileUpload');
    });


    Route::group(['namespace' => 'Manage', 'prefix' => 'manage'], function (){
        Route::get('/', 'Manager@index')->name('系统设置');
        
        Route::group(['prefix' => 'manager'], function (){
            Route::get('/', 'Manager@index')->name('管理员管理');
            Route::get('page', 'Manager@page');
            Route::get('power/{id}', 'Manager@power');
            Route::get('get-power/{id}', 'Manager@getPower');
            Route::post('power_submit', 'Manager@powerSubmit');
            Route::get('profile', 'Manager@profile');
            Route::post('profile_submit', 'Manager@profileSubmit');
            Route::get('edit/{id?}', 'Manager@edit');
            Route::post('edit_submit', 'Manager@editSubmit');
            Route::get('enable/{id}', 'Manager@enable');
            Route::get('disable/{id}', 'Manager@disable');
            Route::get('delete/{id}', 'Manager@delete');
        });

        Route::group(['prefix' => 'node'], function (){
            Route::get('/', 'Node@index')->name('菜单管理');
            Route::get('page', 'Node@page');
            Route::get('load', 'Node@load');
            Route::get('update/{id}/{field}/{value}', 'Node@update');
        });
    });


    Route::group(['prefix' => 'user'], function (){
        Route::get('/', 'UsersController@index')->name('会员管理');
        Route::group(['prefix' => ''], function (){
            Route::get('index', 'UsersController@index')->name('会员列表');
            Route::get('page', 'UsersController@page');
            Route::post('disable', 'UsersController@disable');
        });
    });

    
    Route::group(['prefix' => 'assets'], function (){
        Route::get('/', 'AssetsController@index')->name('资产管理');
        
        Route::get('index', 'AssetsController@index')->name('资产列表');
        Route::get('list', 'AssetsController@lists');
        Route::post('save', 'AssetsController@save');
        Route::post('del', 'AssetsController@del');
    });
    

    Route::group(['prefix' => 'transactions'], function (){
        Route::get('/', 'TransactionsController@index')->name('交易记录');
        
        Route::get('index', 'TransactionsController@index')->name('交易记录');
        Route::get('page', 'TransactionsController@indexPage');
    });
    
    
    Route::group(['prefix' => 'withdraw'], function (){
        Route::get('/', 'WithdrawLogController@index')->name('提现管理');
        
        Route::get('withdraw-log', 'WithdrawLogController@index')->name('提现列表');
        Route::get('withdraw-log-page', 'WithdrawLogController@page');
    });
});


