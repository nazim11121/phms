<?php

namespace App\Http\Controllers\Location;

use App\Models\SystemState;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatesController extends Controller
{
    // HANDLE AJAX REQUEST

    /**
     * getCountryWiseState
     *
     * @param  mixed $request
     * @return void
     */
    public function getCountryWiseState(Request $request)
    {
        $states = SystemState::where('country_id', $request->id)->get();

        return response()->json([
            'data'  =>  $states
        ]);
    }
}
