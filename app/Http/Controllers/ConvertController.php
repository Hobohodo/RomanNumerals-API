<?php
/**
 * @author Richard Allum <richard.allum@gradintel.com>
 * Date: 01/08/2017
 * Time: 22:27
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class ConvertController extends Controller
{

    /**
     * Take an integer and convert it into a roman numeral.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function convert(Request $request) {
        $integer = $request->input('integer');

        return view("home", ["integer" => $integer]);
    }

}