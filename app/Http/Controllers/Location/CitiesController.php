<?php

namespace App\Http\Controllers\Location;

use App\Models\SystemCity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CitiesController extends Controller
{
    //...............HANDLE AJAX REQUEST

    /**
     * getStateWiseCity
     *
     * @param  mixed $request
     * @return void
     */
    public function getStateWiseCity(Request $request)
    {
        $cities = SystemCity::where('state_id', $request->id)->get();

        return response()->json([
            'data'  =>  $cities
        ]);
    }
}
