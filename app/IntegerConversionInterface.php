<?php

namespace App;

interface IntegerConversionInterface
{
    /** convert a given integer to roman numerals.
     * @param $integer
     * @return string a Roman Numeral
     */
    public function toRomanNumerals($integer);
}