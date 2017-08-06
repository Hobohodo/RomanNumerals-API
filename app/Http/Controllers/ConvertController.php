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
use Carbon\Carbon;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class ConvertController extends Controller
{

    /** @const int number of records to retrieve for a "common" request */
    const COMMON_LIMIT = 10;

    const PERIOD_DAY = "day";
    const PERIOD_WEEK = "week";
    const PERIOD_MONTH = "month";

    /** @const array valid time periods for "recent" call */
    const ALLOWED_PERIODS = [self::PERIOD_DAY, self::PERIOD_WEEK, self::PERIOD_MONTH];

    /**
     * Take an integer and convert it into a roman numeral.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function convert(Request $request) {

        $integer = $request->input('integer');

        $integerConverter = new IntegerConversion();

        try {
            if(empty($integer)){
                throw new \InvalidArgumentException("No integer given to convert. Please pass any value to be converted using 'integer' in your request", 400);
            }

            $romanNumeral = $integerConverter->toRomanNumerals(intval($integer));
            //store this conversion
            $conversion = Conversion::create([
                "integer" => $integer,
                "numeral" => $romanNumeral
            ]);


            $total = Total::firstOrNew(["integer" => $integer]);


            if($total->exists) { //record already exists, increment counter
                $conversionCount = $total->total +1;
            } else { //first time converting
                $conversionCount = 1;
            }

            $total->setAttribute("total", $conversionCount);
            $total->save();

            $fractal = new Manager();

            $resource = new Item($conversion, new ConversionTransformer());

        } catch(\Exception $ex) {
            return response()->json([
                "error" => $ex->getCode(),
                "message" => $ex->getMessage()
            ]);
        }
        return response($fractal->createData($resource)->toJson())->header("Content-Type", "application/json");
    }

    /**
     * Get the most recent Conversions and show them to the end user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recent(Request $request) {

        try {
            $timePeriod = $request->get("period", self::PERIOD_WEEK);
            $startDate = $this->getStartDate($timePeriod);

            $recentConversions = Conversion::whereDate('updated_at', '>', $startDate)->orderBy('updated_at', 'DESC')->get();

            $fractal = new Manager();
            //Always include timestamps for most recent conversions
            $fractal->parseIncludes("timestamps");

            $resource = new Collection($recentConversions, new ConversionTransformer());

        } catch(\Exception $ex) {
            return response()->json([
                "error" => $ex->getCode(),
                "message" => $ex->getMessage()
            ]);
        }
        return response($fractal->createData($resource)->toJson())->header("Content-Type", "application/json");
    }

    /**
     * Return the top 10 most commonly requested integer conversions
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function common(Request $request) {

        try {

            $commonConversions = Total::orderBy('total', 'DESC')->limit(self::COMMON_LIMIT)->get();

            $fractal = new Manager();

            $includeTimestamps = $request->get("timestamps", false);
            if($includeTimestamps){
                $fractal->parseIncludes("timestamps");
            }


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

    /**
     * Retrieve a date string a set period of time before now
     * @param $timePeriod string period of time to search
     * @return string
     */
    private function getStartDate($timePeriod) {

        if(!in_array($timePeriod, self::ALLOWED_PERIODS)) {
            throw new \InvalidArgumentException("Invalid time period specified", 400);
        }

        $currentDate = Carbon::today();

        //Check available options
        if($timePeriod === self::PERIOD_DAY) {
            $startDate = $currentDate->copy()->subDay();
        } else if($timePeriod === self::PERIOD_WEEK) {
            $startDate = $currentDate->copy()->subWeek();
        } else {
            $startDate = $currentDate->copy()->subMonth();
        }


        return $startDate->toDateString();
    }

}