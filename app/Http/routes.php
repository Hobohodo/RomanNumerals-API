<?php

Route::get('/', function() {
    //file_put_contents('php://stdout', "hello");
    return view('home', ["integer" => null]);
});

/** This route is used to convert an integer.
 * Not currently using the "/convert/{$id}" syntax as unsure on how to place input into URL from view.
 */
Route::post("/convert", "ConvertController@convert");