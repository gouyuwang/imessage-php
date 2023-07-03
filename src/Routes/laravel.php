<?php

Route::group(['namespace' => 'Gouyuwang\IMessage\Controllers'], function () {
    Route::post('/imessage/master', 'IndexController@start');
});

