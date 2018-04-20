<?php

Route::group(['prefix' => '/'], function () {
    Auth::routes();
    Route::get('/', 'Dashboard\\DashboardController@index');
    Route::get('/visitors', 'Visitor\\VisitorController@index');

    Route::group(['prefix' => '/profile'], function () {
        $controller = 'Profile\\ProfileController@';
        Route::get('/', $controller . 'index');
        Route::post('update', $controller . 'update');
        Route::post('setOnlineStatus', $controller . 'setOnlineStatus');
    });

    Route::group(['prefix' => '/users'], function () {
        $controller = 'User\\UserController@';
        Route::get('/', $controller . 'index');
        Route::get('add', $controller . 'add');
        Route::get('edit/{id}', $controller . 'edit');
        Route::post('update/{id}', $controller . 'update');
        Route::post('getDatas', $controller . 'getDatas');
        Route::get('delete/{id}', $controller . 'delete');
    });

    Route::group(['prefix' => '/roles'], function () {
        $controller = 'Role\\RoleController@';
        Route::get('/', $controller . 'index');
        Route::post('add', $controller . 'add');
        Route::post('get/{id}', $controller . 'get');
        Route::get('edit/{id}', $controller . 'edit');
        Route::post('update/{id}', $controller . 'update');
        Route::post('delete/{id}', $controller . 'delete');
        Route::post('saveOrder', $controller . 'saveOrder');
        Route::get('delegation/{id}', $controller . 'delegation');
        Route::post('delegation/update/{id}', $controller . 'delegationUpdate');
    });

    Route::group(['prefix' => 'preparedContents'], function () {
        $controller = 'PreparedContent\\PreparedContentController@';
        Route::get('/', $controller . 'index');
        Route::post('add', $controller . 'add');
        Route::post('get/{id}', $controller . 'get');
        Route::get('edit/{id}', $controller . 'edit');
        Route::get('getContentsToJson', $controller . 'getContentsToJson');
        Route::post('update/{id}', $controller . 'update');
        Route::post('delete/{id}', $controller . 'delete');
        Route::post('saveOrder', $controller . 'saveOrder');
    });

    Route::group(['prefix' => 'welcomeMessages'], function () {
        $controller = 'WelcomeMessage\\WelcomeMessageController@';
        Route::get('/', $controller . 'index');
        Route::post('get/{id}', $controller . 'get');
        Route::get('edit/{id}', $controller . 'edit');
        Route::post('update/{id}', $controller . 'update');
        Route::post('getDatas', $controller . 'getDatas');
    });

    Route::group(['prefix' => 'bannedWords'], function () {
        $controller = 'BannedWord\\BannedWordController@';
        Route::get('/', $controller . 'index');
        Route::get('add', $controller . 'add');
        Route::get('delete/{id}', $controller . 'delete');
        Route::get('edit/{id}', $controller . 'edit');
        Route::post('update/{id}', $controller . 'update');
        Route::post('getDatas', $controller . 'getDatas');
    });

    Route::group(['prefix' => 'shifts'], function () {
        $controller = 'Shift\\ShiftController@';
        Route::get('/', $controller . 'index');
        Route::get('edit/{id}', $controller . 'edit');
        Route::post('getDatas', $controller . 'getDatas');
        Route::post('addShift', $controller . 'addShift');
        Route::post('getShift/{id}', $controller . 'getShift');
        Route::post('saveShift/{id}', $controller . 'saveShift');
        Route::post('deleteShift', $controller . 'deleteShift');
        Route::post('getOffUsers/{id}', $controller . 'getOffUsers');
    });

    Route::group(['prefix' => 'configs'], function () {
        $controller = 'Config\\ConfigController@';
        Route::get('/', $controller . 'index');
        Route::post('update', $controller . 'update');
    });

    Route::group(['prefix' => 'reports'], function () {
        $controller = 'Report\\ReportController@';
        Route::get('/', $controller . 'index');
        Route::post('/getReport', $controller . 'getReport');
    });

    Route::group(['prefix' => 'bannedUsers'], function () {
        $controller = 'BannedUser\\BannedUserController@';
        Route::get('/', $controller . 'index');
        Route::get('delete/{id}', $controller . 'delete');
        Route::get('edit/{id}', $controller . 'edit');
        Route::post('getDatas', $controller . 'getDatas');
    });

    Route::group(['prefix' => 'subjects'], function () {
        $controller = 'Subject\\SubjectController@';
        Route::get('/', $controller . 'index');
        Route::get('/add', $controller . 'add');
        Route::get('delete/{id}', $controller . 'delete');
        Route::get('edit/{id}', $controller . 'edit');
        Route::post('update/{id}', $controller . 'update');
        Route::post('getDatas', $controller . 'getDatas');
    });

    Route::group(['prefix' => 'chats'], function () {
        $controller = 'Chat\\ChatController@';
        Route::get('/{chatId}', $controller . 'getChat');
    });

    Route::group(['prefix' => 'anotherLogin'], function () {
        Route::get('/', function () {
            return view('AnotherLogin.index');
        });
    });

});