<?php

namespace App;

use League\Fractal;

/**
 * Used to generate a Fractal Resource friendly array, to ease creating JSON responses.
 * Class ConversionTransformer
 * @package App
 */
class ConversionTransformer extends Fractal\TransformerAbstract
{

    protected $availableIncludes = [
        'timestamps'
    ];

    public function transform(Conversion $conversion) {
        return [
            'integer' => (int) $conversion->integer,
            'numeral' => $conversion->numeral
        ];
    }

    /**
     * @param Conversion $conversion
     * @return Fractal\Resource\Item
     */
    public function includeTimestamps(Conversion $conversion) {
        return $this->item($conversion, new TimestampTransformer);
    }


}