<?php

namespace App;

use League\Fractal;

class TotalTransformer extends Fractal\TransformerAbstract
{

    protected $availableIncludes = ["timestamps"];


    public function transform(Total $total) {
        return [
            'integer' => (int) $total->integer,
            'total' => (int) $total->total
        ];
    }

    /**
     * @param Total $total
     * @return Fractal\Resource\Item
     */
    public function includeTimestamps(Total $total) {
        return $this->item($total, new TimestampTransformer);
    }


}