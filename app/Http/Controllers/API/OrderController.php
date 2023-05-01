<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;

use App\Models\{
    car,
    order,
    User
};

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //

        $role_id = $request->query('role_id');
        $user_id = $request->query('user_id');

        if ($role_id === "1") {
            $orders = order::where('isActive', 1)
            ->paginate(10)
            ->through(function ($order) {
                $user = user::find($order->user_id);
                $car = car::find($order->car_id);
                return [
                    'id' => $order->id,
                    'user_id' => $order->user_id,
                    'user_name' => $user->name,
                    'car_id' => $order->car_id,
                    'car_name' => $car->name,
                    'car_image' => $car->image,
                    'pick_up_loc' => $order->pick_up_loc,
                    'drop_off_loc' => $order->drop_off_loc,
                    'pick_up_date' => $order->pick_up_date,
                    'drop_off_date' => $order->drop_off_date,
                    'pick_up_time' => $order->pick_up_time,
                    'isActive' => $order->isActive,
                ];
            });
        } elseif ($role_id !== "1") {
            $orders = order::where('isActive', 1)
            ->where('user_id', $user_id)
            ->paginate(10)
            ->through(function ($order) {
                $user = user::find($order->user_id);
                $car = car::find($order->car_id);
                return [
                    'id' => $order->id,
                    'user_id' => $order->user_id,
                    'user_name' => $user->name,
                    'car_id' => $order->car_id,
                    'car_name' => $car->name,
                    'car_image' => $car->image,
                    'pick_up_loc' => $order->pick_up_loc,
                    'drop_off_loc' => $order->drop_off_loc,
                    'pick_up_date' => $order->pick_up_date,
                    'drop_off_date' => $order->drop_off_date,
                    'pick_up_time' => $order->pick_up_time,
                    'isActive' => $order->isActive,
                ];
            });
        } else {
            $orders = [];
        }

        return response()->json([
            'orders' => $orders,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            $request->validate([
                'user_id' => 'required',
                'car_id' => 'required',
                'pick_up_loc' => 'required',
                'drop_off_loc' => 'required',
                'pick_up_date' => 'required',
                'drop_off_date' => 'required',
            ]);

            $milliseconds = $request->drop_off_date - $request->pick_up_date;
            $hours = ceil($milliseconds/1000/60/60);

            $order = new order;
            $order->user_id = $request->user_id;
            $order->car_id = $request->car_id;
            $order->pick_up_loc = $request->pick_up_loc;
            $order->drop_off_loc = $request->drop_off_loc;
            $order->pick_up_date = Carbon::createFromTimestampMs($request->pick_up_date, 'Asia/Jakarta');
            $order->drop_off_date = Carbon::createFromTimestampMs($request->drop_off_date, 'Asia/Jakarta');
            $order->pick_up_time = $hours;
            $order->save();

            if ($order->id) {
                return response('success', 200);
            } else {
                throw new Exception("Data not recorded", 422);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response('failed', 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $orders = order::with('user', 'car')->find($id);

            if ($orders) {
                return response()->json([
                    'order' => $orders,
                ]);
            } else {
                throw new Exception("not found", 422);

            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => 'order not found',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'user_id' => 'required',
                'car_id' => 'required',
                'pick_up_loc' => 'required',
                'drop_off_loc' => 'required',
                'pick_up_date' => 'required',
                'drop_off_date' => 'required',
            ]);

            $milliseconds = $request->drop_off_date - $request->pick_up_date;
            $hours = ceil($milliseconds/1000/60/60);

            $order = order::findOrFail($id);
            $order->user_id = $request->user_id;
            $order->car_id = $request->car_id;
            $order->pick_up_loc = $request->pick_up_loc;
            $order->drop_off_loc = $request->drop_off_loc;
            $order->pick_up_date = Carbon::createFromTimestampMs($request->pick_up_date, 'Asia/Jakarta');
            $order->drop_off_date = Carbon::createFromTimestampMs($request->drop_off_date, 'Asia/Jakarta');
            $order->pick_up_time = $hours;
            $order->save();

            if ($order->id) {
                return response('success', 200);
            } else {
                throw new Exception("Data not recorded", 422);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $order = order::findOrFail($id);
            $order->isActive = 0;
            $order->save();

            if ($order->id) {
                return response('success', 200);
            } else {
                throw new Exception("Data not recorded", 422);
            }

        } catch (\Throwable $th) {
            //throw $th;
            return response('failed', 422);
        }
    }
}
