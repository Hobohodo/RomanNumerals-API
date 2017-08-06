<?php

namespace App;

use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Model;


/**
 * This may be cheating and insecure if we wish to hide dates for future requests, however right now it allows the user
 * to optionally include the timestamps for any Transformer provided that Transformer supports it.
 * @package App
 */
class TimestampTransformer extends TransformerAbstract
{
    /**
     * Take any Eloquent Model and create a timestamp field for use in other Transformers.
     * @param Model $model
     * @return array
     */
    public function transform(Model $model) {
        return [
            'last_converted' => $model->updated_at
        ];

    }
}