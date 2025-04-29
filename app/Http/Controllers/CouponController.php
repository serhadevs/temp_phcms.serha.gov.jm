<?php

namespace App\Http\Controllers;

use App\Models\Coupons;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class CouponController extends Controller
{
    public function index()
    {
        // Logic to display all coupons
        $coupons = Coupons::all();
        return view('coupons.index', compact('coupons'));
    }

    public function create()
    {
        // Logic to show the form for creating a new coupon
    }

    public function store(Request $request)
    {
        // Logic to store a new coupon

        try {
            //Get the fields from the request
            $validatedData = $request->validate([
                'coupon_name' => "required|string",
                'coupon_discount' => "required|integer",
                'coupon_validity' => 'required|date|after_or_equal:today',

            ]);


            //Check to see if the coupon already exists
            $coupon = Coupons::where('coupon_name', $validatedData['coupon_name'])->first();
            if ($coupon) {
                return redirect()->back()->with('error', 'Coupon already exists');
            }
            //Create a uuid for the coupon 
            $validatedData['coupon_id'] = Str::uuid();
            //Create the coupon
            Coupons::create($validatedData);
            //Redirect to the coupons index page
            return redirect()->route('coupons.index')->with('success', 'Coupon created successfully');
        } catch (Exception $e) {
            Log::error('Failed to create coupon', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input_data' => $request->all(),
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while creating the coupon. Please try again.', $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        return view('temp_online.coupon');
    }

    public function edit($id)
    {
        // Logic to show the form for editing a specific coupon
    }

    public function update(Request $request, $id)
    {
        // Logic to update a specific coupon
    }

    public function destroy($id)
    {
        // Logic to delete a specific coupon
    }

    public function redeem(Request $request)
    {
        try {
            $coupon = Coupons::where('coupon_name', $request->coupon_name)->first();

            //dd($coupon);

            if ($coupon && $coupon->coupon_validity >= now()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Coupon applied successfully',
                    'coupon' => $coupon 
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon has expired or is invalid'
                ]);
            }
        } catch (Exception $e) {
            Log::error('Failed to use coupon', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input_data' => $request->all(),
            ]);

            return response()->json(['success' => false, 'message' => 'An error occurred while applying the coupon. Please try again.']);
        }
    }
}
