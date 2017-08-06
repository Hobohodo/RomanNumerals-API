<?php

/** This route is used to convert an integer.
 * Not currently using the "/convert/{$id}" syntax as wanted to use Request objects.
 */
Route::get('/convert', "ConvertController@convert");

Route::get("/recent", "ConvertController@recent");