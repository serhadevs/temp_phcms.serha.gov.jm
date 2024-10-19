<?php

namespace App\Http\Controllers;

use App\Models\CollectedCards;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class CollectCardController extends Controller
{
    public function index() {}

    public function create()
    {
        return view('collectedcards.create');
    }

    public function store(Request $request)
{
    try {
        $validatedData = $request->validate([
            'app_id' => 'required',
            'collected_by' => 'required',
            'application_type' => 'required',
        ]);

        $validatedData['user_id'] = auth()->id();

        $store_card = CollectedCards::create($validatedData);

        if ($store_card) { 
            return response()->json([
                'success' => true,
                'message' => 'Pickup details saved successfully!'
            ], 200);
        } else {
            throw new \Exception('Failed to save pickup details');
        }
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        Log::error('Error storing card info: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while saving pickup details: ' . $e->getMessage()
        ], 500);
    }
}
}
