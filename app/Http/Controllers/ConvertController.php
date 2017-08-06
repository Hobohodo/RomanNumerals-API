<?php
/**
 * @author Richard Allum <richard.allum@gradintel.com>
 * Date: 01/08/2017
 * Time: 22:27
 */

namespace App\Http\Controllers;


use App\Conversion;
use App\ConversionTransformer;
use App\IntegerConversion;
use App\Total;
use App\TotalTransformer;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

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

        try {
            $romanNumeral = $integerConverter->toRomanNumerals(intval($integer));
            $conversion = Conversion::create([
                "integer" => $integer,
                "numeral" => $romanNumeral
            ]);

            //sort out the conversions
            $total = Total::firstOrNew(["integer" => $integer]);

            if ($total->exists) {
                $newTotal = $total->total + 1;
                echo $newTotal;
                $total->setAttribute("total", $newTotal);
                $total->save();
            } else {
                $total->setAttribute("total", 1);
                $total->save();
            }

            $fractal = new Manager();

            $resource = new Item($conversion, new ConversionTransformer());

        } catch(\Exception $ex) {
            //exception handling is poor, but prevents the entire stack trace from being shown to the user
            return response()->json([
                "error" => $ex->getCode(),
                "message" => $ex->getMessage()
            ]);
        }
        return response()->json($fractal->createData($resource)->toJson());
    }

    public function recent(Request $request) {
        //TODO:Use the request to determine the start date
        $lastWeekStart = strtotime("-1 week");
        $lastWeek = date('Y-m-d', $lastWeekStart);

        try {

            $recentConversions = Conversion::whereDate('updated_at', '>', $lastWeek)->orderBy('updated_at', 'DESC')->get();

            $fractal = new Manager();
            $fractal->parseIncludes("timestamps");

            $resource = new Collection($recentConversions, new ConversionTransformer());
        } catch(\Exception $ex) {
            //exception handling is poor, but prevents the entire stack trace from being shown to the user
            return response()->json([
                "error" => $ex->getCode(),
                "message" => $ex->getMessage()
            ]);
        }
        return response($fractal->createData($resource)->toJson())->json();
    }

    public function common(Request $request) {

        try {
            $commonConversions = Total::orderBy('total', 'DESC')->limit(10)->get();

            $fractal = new Manager();
            $fractal->parseIncludes("timestamps");

            $resource = new Collection($commonConversions, new TotalTransformer());

        } catch(\Exception $ex) {
            //exception handling is poor, but prevents the entire stack trace from being shown to the user
            return response()->json([
                "error" => $ex->getCode(),
                "message" => $ex->getMessage()
            ]);
        }
        return response($fractal->createData($resource)->toJson())->header("Content-Type", "application/json");
    }

}