<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

use App\Models\{
    car,
    User
};

class CarsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $cars = car::where('isActive', 1)->paginate(10)->through(function ($car) {
            return [
                'id' => $car->id,
                'name' => $car->name,
                'car_type' => $car->car_type,
                'rating' => $car->rating,
                'fuel' => $car->fuel,
                'image' => $car->image,
                'hour_rate' => $car->hour_rate,
                'day_rate' => $car->day_rate,
                'month_rate' => $car->month_rate,
                'isActive' => $car->isActive,
                'created_at' => $car->created_at,
                'updated_at' => $car->updated_at
            ];
        });
        return response()->json([
            'cars' => $cars,
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
                'name' => 'required',
                'car_type' => 'required',
                'rating' => 'required',
                'fuel' => 'required',
                'image' => 'required|max:10000|mimes:jpg,jpeg,png',
                'hour_rate' => 'required',
                'day_rate' => 'required',
                'month_rate' => 'required',
            ]);

            $car = new car;

            $file_name = time().'_'.$request->image->getClientOriginalName();
            $file_path = $request->image->storeAs('uploads', $file_name, 'public');

            $car->name = $request->name;
            $car->car_type = $request->car_type;
            $car->rating = $request->rating;
            $car->fuel = $request->fuel;
            $car->image = '/storage/'.$file_path;
            $car->hour_rate = $request->hour_rate;
            $car->day_rate = $request->day_rate;
            $car->month_rate = $request->month_rate;
            $car->save();

            if ($car->id) {
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
        //
        try {
            $car = car::findOrFail($id);

            if ($car) {
                return response()->json([
                    'car' => $car,
                ]);
            } else {
                throw new Exception("not found", 422);

            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => 'car not found',
            ]);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        try {
            $request->validate([
                'name' => 'required',
                'car_type' => 'required',
                'rating' => 'required',
                'fuel' => 'required',
                // 'image' => 'required|max:10000|mimes:jpg,jpeg,png',
                'hour_rate' => 'required',
                'day_rate' => 'required',
                'month_rate' => 'required',
            ]);

            $car = car::findOrFail($id);

            if ($request->hasFile('image')) {
                $file_name = time().'_'.$request->image->getClientOriginalName();
                $file_path = $request->image->storeAs('uploads', $file_name, 'public');

                $car->name = $request->name;
                $car->car_type = $request->car_type;
                $car->rating = $request->rating;
                $car->fuel = $request->fuel;
                $car->image = '/storage/'.$file_path;
                $car->hour_rate = $request->hour_rate;
                $car->day_rate = $request->day_rate;
                $car->month_rate = $request->month_rate;
                $car->save();
            } else {
                $car->name = $request->name;
                $car->car_type = $request->car_type;
                $car->rating = $request->rating;
                $car->fuel = $request->fuel;
                $car->hour_rate = $request->hour_rate;
                $car->day_rate = $request->day_rate;
                $car->month_rate = $request->month_rate;
                $car->save();
            }

            if ($car->id) {
                return response('success', 200);
            } else {
                throw new Exception("Data not recorded", 422);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response($th, 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            $car = car::findOrFail($id);
            $car->isActive = 0;
            $car->save();

            if ($car->id) {
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
