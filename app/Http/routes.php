<?php

Route::get('/', function() {
    //file_put_contents('php://stdout', "hello");
    return view('home');
});


Route::post("/convert", "ConvertController@convert");