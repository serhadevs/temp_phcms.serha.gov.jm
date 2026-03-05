<?php

namespace App\Http\Controllers;

use App\Models\WaiverApprovals;
use App\Models\Waivers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WaiverApprovalController extends Controller
{
    public function index()
    {
        $waivers = Waivers::with('establishment', 'user')->get();
        return view('waiver_approvals.index', compact('waivers'));
    }

    public function store(Request $request)
{
    $request->validate([
        'waiver_establishment_id' => 'required|exists:waiver_establishments,id',
        'application_id' => 'required|exists:establishment_clinics,id',
        'waiver_amount' => 'required|numeric|min:0',
    ]);

    // Check if a waiver already exists for this establishment/application
    $existingWaiver = Waivers::where('waiver_establishment_id', $request->waiver_establishment_id)
        ->where('application_id', $request->application_id)
        ->first();

    if ($existingWaiver) {
        return response()->json([
            'status' => 'error',
            'message' => 'A waiver request already exists for this establishment.'
        ], 400);
    }

    // Create new waiver
    Waivers::create([
        'waiver_establishment_id' => $request->waiver_establishment_id,
        'application_id' => $request->application_id,
        'user_id' => auth()->id(),
        'application_type_id' => 4,
        'waiver_reason' => 'Establishment has applied for Food Handlers Clinic Waiver',
        'amount' => $request->waiver_amount,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Waiver request submitted successfully.'
    ]);
}


    public function approve($id)
    {
        try {
            // Start a database transaction
            DB::beginTransaction();

            // Check if waiver exists
            $waiver = Waivers::findOrFail($id);

            // Check if already approved
            $existingApproval = WaiverApprovals::where('waiver_id', $id)->first();

            if ($existingApproval) {
                return response()->json([
                    'message' => 'This waiver has already been processed.',
                    'status' => 'error'
                ], 400);
            }

            // Create approval record
            WaiverApprovals::create([
                'waiver_id'   => $id,
                'approval_status'      => 'approved',
                'approved_by' => auth()->id(),
                'establishment_id' => $waiver->application_id,

            ]);

            // Optional: Update the waiver status
            $waiver->update([
                'deleted_at' => now()
            ]);

            // Commit the transaction
            DB::commit();

            // Log the approval
            Log::info("Waiver {$id} approved by user " . auth()->id());

            return response()->json([
                'message' => 'Waiver approved successfully.',
                'status' => 'success',
                'waiver_id' => $id
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::error("Waiver not found: {$id}");

            return response()->json([
                'message' => 'Waiver not found.',
                'status' => 'error'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error approving waiver {$id}: " . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while approving the waiver.',
                'status' => 'error',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
