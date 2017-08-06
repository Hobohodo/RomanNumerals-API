<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Conversion
 *
 * @mixin \Eloquent
 */
class Conversion extends Model
{
    //Say which attributes are mass assignable
    protected $fillable = array("integer", "numeral");
}
