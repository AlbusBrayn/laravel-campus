<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MapController extends Controller
{

    public function index(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'status' => 'required|boolean'
        ]);

        $validator->setAttributeNames([
            'status' => 'Durum'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'validate error!', 'data' => $validator->errors()], 400);
        }

        $user = $request->user();
        $user->hide_location = $request->status;
        $user->save();

        return response(['status' => 'success', 'message' => 'Konum gizleme durumu güncellendi!']);
    }


    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'latitude' => 'required|string',
            'longitude' => 'required|string'
        ]);

        $validator->setAttributeNames([
            'latitude' => 'Enlem',
            'longitude' => 'Boylam'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'validate error!', 'data' => $validator->errors()], 400);
        }

        $user = $request->user();
        $user->lat = $request->latitude;
        $user->lng = $request->longitude;
        $user->save();

        return response(['status' => 'success', 'message' => 'Konum güncellendi!']);
    }

    public function getMap(Request $request)
    {
        $user = $request->user();
    }
}
