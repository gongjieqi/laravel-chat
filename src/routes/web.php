<?php


Route::post('/chat/bind', 'Gongjieqi\LaravelChat\Controllers\Chat\ChatController@bind')->name('chat-bind');

Route::post('/chat/sendmessage', 'Gongjieqi\LaravelChat\Controllers\Chat\ChatController@sendMessage')->name('chat-send');