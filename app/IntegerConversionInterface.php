<?php

namespace App;

interface IntegerConversionInterface
{
    /** convert a given integer to roman numerals.
     * @param $integer
     * @return mixed
     */
    public function toRomanNumerals($integer);
}