<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserMajor;
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
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string'
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
        $users = $user->getFriends()->where('hide_location', false);

        $mapUsers = [];
        foreach ($users as $key) {
            if ($key->lat && $key->lng) {
                if ($key->major) {
                    $major = [
                        'id' => $key->major->major->id,
                        'title' => $key->major->major->title,
                        'major_user_count' => UserMajor::where(['school_id' => $key->school_id, 'major_id' => $key->major->major_id])->count()
                    ];
                } else {
                    $major = [];
                }

                $mapUsers[] = [
                    'id' => $key->id,
                    'name' => $key->name,
                    'major' => $major,
                    'lat' => $key->lat,
                    'lng' => $key->lng,
                    'avatar' => $key->avatar,
                ];
            }
        }

        return response(['status' => 'success', 'message' => 'Konumlar getirildi!', 'data' => $mapUsers]);
    }
}
