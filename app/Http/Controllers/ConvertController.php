<?php
/**
 * @author Richard Allum <richard.allum@gradintel.com>
 * Date: 01/08/2017
 * Time: 22:27
 */

namespace App\Http\Controllers;


use App\Conversion;
use App\IntegerConversion;
use App\Total;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConvertController extends Controller
{

    /**
     * Take an integer and convert it into a roman numeral.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function convert(Request $request) {
        $integer = $request->input('integer');

        $integerConverter = new IntegerConversion();

        $romanNumeral = $integerConverter->toRomanNumerals(intval($integer));

        $conversion = Conversion::create(array(
            "integer" => $integer,
            "numeral" => $romanNumeral
        ));

        $total = Total::firstOrNew(array("integer" => $integer));

        if($total->exists) {
            $newTotal = $total->total + 1;
            echo $newTotal;
            $total->setAttribute("total", $newTotal);
            $total->save();
        } else {
            $total->setAttribute("total", 1);
            $total->save();
        }

        //TODO: Return Fractal Collection
        return view("conversion", ["conversion" => $conversion]);
    }

    public function recent(Request $request) {
        $lastWeekStart = strtotime("-1 week");

        $lastWeek = date("Y-m-d", $lastWeekStart);

        $recent = Conversion::whereTime("updated_at", ">", $lastWeek);

        //TODO: Return Fractal collection
    }

    public function mostCommon(Request $request) {
        //TODO: Get "Totals", return Fractal Conversion
    }

}