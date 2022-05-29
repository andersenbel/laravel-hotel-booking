<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomResource;
use App\Models\Bookings;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\v;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MainController extends Controller {


    public function roomslist() {
        return view('rooms', [
            'roomslist' =>  RoomResource::collection(Room::with("bookings")->paginate(6)),
            'dfrom' => 'From',
            'dto' => 'To',
        ]);
    }


    public function search(Request $request) {
        $dfrom = 'From';
        $dto = 'To';
        if (
            preg_match("/(\d+)\/(\d+)\/(\d+)/", $request->get('dfrom'), $m1) &&
            preg_match("/(\d+)\/(\d+)\/(\d+)/", $request->get('dto'), $m2)
        ) {
            $dfrom =  $request->get('dfrom');
            $dto =  $request->get('dto');
            $y = $m1[3];
            $m = $m1[1];
            $d = $m1[2];
            $sdfrom = "$y-$m-$d";
            $y = $m2[3];
            $m = $m2[1];
            $d = $m2[2];
            $sdto = "$y-$m-$d";
            $sroom = Room::distinct()->select('rooms.id', 'rooms.name', 'photo', 'price')->leftjoin('bookings', 'bookings.room_id', '=', 'rooms.id')->whereNull('bookings.dfrom')
                ->orwhere(
                    function ($query) use ($sdfrom, $sdto) {
                        $query->where('bookings.dto', '<', "'$sdto'")->where('bookings.dfrom', '>', "'$sdfrom'");
                    }
                )->paginate(6);

            // $query = str_replace(array('?'), array('%s'), $sroom->toSql());
            // $query = vsprintf($query, $sroom->getBindings());
            // dd($query);
            // dd($sroom->count());
        } elseif (
            preg_match("/(\d+)\/(\d+)\/(\d+)/", $request->get('dfrom'), $m1) &&
            !preg_match("/(\d+)\/(\d+)\/(\d+)/", $request->get('dto'), $m2)
        ) {
            $dfrom =  $request->get('dfrom');
            $y = $m1[3];
            $m = $m1[1];
            $d = $m1[2];
            $sdfrom = "$y-$m-$d";
            $sroom = Room::distinct()->leftjoin('bookings', 'bookings.room_id', '=', 'rooms.id')->whereNull('bookings.dfrom')
                ->orwhere(
                    function ($query) use ($sdfrom) {
                        $query->where('bookings.dfrom', '>', "'$sdfrom'");
                    }
                )->paginate(6);
        } elseif (
            !preg_match("/(\d+)\/(\d+)\/(\d+)/", $request->get('dfrom'), $m1) &&
            preg_match("/(\d+)\/(\d+)\/(\d+)/", $request->get('dto'), $m2)
        ) {
            $dto =  $request->get('dto');
            $y = $m2[3];
            $m = $m2[1];
            $d = $m2[2];
            $sdto = "$y-$m-$d";
            $sroom = Room::distinct()->leftjoin('bookings', 'bookings.room_id', '=', 'rooms.id')->whereNull('bookings.dfrom')
                ->orwhere(
                    function ($query) use ($sdto) {
                        $query->where('bookings.dto', '<', "'$sdto'");
                    }
                )->paginate(6);
        } else {
            $sroom = Room::with("bookings")->paginate(6);
        }

        return view('rooms', [
            'roomslist' =>  $sroom, // RoomResource::collection($sroom),
            'dfrom' => $dfrom,
            'dto' => $dto,
        ]);
    }

    public function oneroom($id, Request $request) {
        $phone_codes = [];
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/phone_countries_codes.json')) {
            try {
                $phone_codes = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/phone_countries_codes.json'), true);
            } catch (Exeption $e) {
                echo  $e->getMessage(), "\n";
            }
        }


        if ($request->input('name') != null)
            $name = $request->input('name');
        else $name = '';

        return view('oneroom', [
            'room' =>  RoomResource::collection(Room::where('id', $id)->with("bookings")->paginate(1)),
            'phone_codes' => $phone_codes,
            'name' => $name
        ]);
    }

    public function bookin(Request $request) {
        $phone_codes = [];
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/phone_countries_codes.json')) {
            try {
                $phone_codes = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/phone_countries_codes.json'), true);
            } catch (Exeption $e) {
                echo  $e->getMessage(), "\n";
            }
        }
        $rules = [
            'room_id' => 'required|Numeric',
            'name' => 'required|min:2|max:100',
            'guests' => 'required|Numeric',
            'phone_code' => 'required|min:1|max:100',
            'phone_number' => 'required|min:2|max:100',
            'email' => 'required|email|min:4|max:100',
            'dfrom' => 'required|min:2|max:100',
            'dto' => 'required|min:2|max:100',
        ];

        $validate =  Validator::make($request->all(), $rules, []);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->messages())->withInput($request->all());
        }


        if (
            preg_match("/(\d+)\/(\d+)\/(\d+)/", $request->get('dfrom'), $m1) &&
            preg_match("/(\d+)\/(\d+)\/(\d+)/", $request->get('dto'), $m2)
        ) {
            $y = $m1[3];
            $m = $m1[1];
            $d = $m1[2];
            $sdfrom = "$y-$m-$d";
            $y = $m2[3];
            $m = $m2[1];
            $d = $m2[2];
            $sdto = "$y-$m-$d";

            $sroom = Bookings::where('room_id', $request->get('room_id'))->where(
                function ($query) use ($sdfrom, $sdto) {
                    $query
                        ->orwhere(
                            function ($query) use ($sdfrom, $sdto) {
                                $query
                                    ->where('dto', '>=', $sdfrom)
                                    ->where('dto', '<=',  $sdto);
                            }
                        )
                        ->orwhere(
                            function ($query) use ($sdfrom, $sdto) {
                                $query
                                    ->where('dfrom', '>=', $sdfrom)
                                    ->where('dfrom', '<=',  $sdto);
                            }
                        );
                }
            );
            // dd($sroom);
            // $query = str_replace(array('?'), array('%s'), $sroom->toSql());
            // $query = vsprintf($query, $sroom->getBindings());
            // // dd($query);
            // dd($sroom->get()->count());


            $date1 = Carbon::createFromFormat('Y-m-d', $sdfrom);
            $date2 = Carbon::createFromFormat('Y-m-d', $sdto);


            if ($sroom->get()->count() > 0) {
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    'not availeble' => ['This room has been booked for the dates you have selected'],
                ]);
                throw $error;
            } elseif ($date1->gt($date2)) {
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    'date error' => ['Date TO is less than date FROM'],
                ]);
                throw $error;
            }
        } else {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'dates' => ['Dates From or  To not set'],
            ]);
        }



        $bookin = new Bookings();

        $bookin->room_id = $request->input('room_id');
        $bookin->name = $request->input('name');
        $bookin->guests = $request->input('guests');
        $bookin->phone_code = $request->input('phone_code');
        $bookin->phone_number = $request->input('phone_number');
        $bookin->email = $request->input('email');
        $bookin->dfrom = $sdfrom;
        $bookin->dto = $sdto;

        $bookin->save();
        return view('bookin', []);
    }
}
