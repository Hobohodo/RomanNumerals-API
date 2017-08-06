<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Total
 *
 * @mixin \Eloquent
 */
class Total extends Model
{
    /** @var string column for primary key */
    public $primaryKey = "integer";

    /** @var bool let the model builder know not to increment the primary key */
    public $incrementing = false;

    /** @var array which fields can be set */
    protected $fillable = array("integer", "total");


}
