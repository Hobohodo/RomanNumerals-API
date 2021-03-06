<?php

namespace App;


use InvalidArgumentException;

/**
 * This class exists to perform integer conversions to various formats.
 * Class IntegerConversion
 * @package App
 */

class IntegerConversion implements IntegerConversionInterface
{

    /**
     * @var array Map of roman numerals with their values.
     */
    private $numeralValues = [
        'M' => 1000,
        'CM' => 900,
        'D' => 500,
        'CD' => 400,
        'C' => 100,
        'XC' => 90,
        'L' => 50,
        'XL' => 40,
        'X' => 10,
        'IX' => 9,
        'V' => 5,
        'IV' => 4,
        'I' => 1
    ];

    /**
     * @param $integer
     * @return string
     */
    public function toRomanNumerals($integer) {
        if(!is_int($integer)) {
            //passing a non-integer to IntegerConversion is just wrong.
            throw new InvalidArgumentException("Non-integer passed for conversion", 400);
        }

        if($integer < 1 || $integer > 3999) {
            throw new InvalidArgumentException("Invalid integer passed for conversion", 400);
        }

        $romanNumeral = "";

        foreach($this->numeralValues as $numeral => $value) {
            //how many times current numeral goes into the integer
            $matches = intval($integer/$value);

            //Add the numeral to the string that many times
            $romanNumeral.= str_repeat($numeral, $matches);

            //remove that factor from the integer before continuing. Variables are mutable!
            $integer = $integer % $value;
        }

        return $romanNumeral;
    }

}