<?php

namespace App\Http\Controllers;

use App\Models\CollectedCards;
use App\Models\IdentificationTypes;
use App\Models\PermitApplication;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CollectCardController extends Controller
{
    public function index() {}

    public function create($id = null)
    {
        //Find the applicant
        $applicant = PermitApplication::findOrFail($id);
        $id_types = IdentificationTypes::all();
        return view('collectedcards.create', compact('id_types', 'applicant'));
    }

    public function store(Request $request)
    {
        // Get the applicant id from the params 
        $applicant_id = $request->input('app_id');
        $occupation = strtolower($request->input('occupation'));
        $isStudent = $occupation === 'student';
        $pickUpId = $request->input('pick_up_id');

        $validator = Validator::make($request->all(), [
            'app_id' => 'required',
            'collected_by' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'application_type' => 'required',
            'identification_type_id' => 'required|exists:identification_types,id',
            'pick_up_id' => 'required|in:1,2',
            'bearer_firstname' => 'required_if:pick_up_id,2|nullable|string|max:255',
            'bearer_lastname' => 'required_if:pick_up_id,2|nullable|string|max:255',
            'bearer_contact_number' => 'required_if:pick_up_id,2|nullable|string|max:20',

            // Conditionally required based on occupation AND pick_up_id
            'identification_number' => $isStudent ? 'nullable|string|max:255' : 'required|string|max:255',
            'issue_date' => ($isStudent || $pickUpId == '1') ? 'nullable|date' : 'required|date',
            'expiry_date' => ($isStudent || $pickUpId == '1') ? 'nullable|date|after:issue_date' : 'required|date|after:issue_date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();
        $collected_by = $validatedData['collected_by'];

        // Check if card has already been collected
        $existing_card = CollectedCards::where('app_id', $validatedData['app_id'])
            ->where('application_type', $validatedData['application_type'])
            ->first();

        if ($existing_card) {
            return redirect()->back()->withInput()->with('error', 'Card has already been collected for ' . $collected_by . '.');
        }

        try {
            $validatedData['user_id'] = auth()->id();

            // Remove occupation from data to be stored
            unset($validatedData['occupation']);

            // Only include bearer names if pick_up_id is 2
            if ($pickUpId != '2') {
                unset($validatedData['bearer_firstname']);
                unset($validatedData['bearer_lastname']);
            }

            // Remove date fields if student or self pickup
            if ($isStudent || $pickUpId == '1') {
                unset($validatedData['issue_date']);
                unset($validatedData['expiry_date']);
            }

            $store_card = CollectedCards::create($validatedData);

            if ($store_card) {
                return redirect()->route('permit.application.view', ['id' => $applicant_id])
                    ->with('success', 'Pickup details saved successfully.');
            }

            throw new \Exception('Failed to save pickup details');
        } catch (\Exception $e) {
            Log::error('Error storing card info: ' . $e->getMessage());

            return redirect()->back()->withInput()
                ->with('error', 'An error occurred while saving pickup details. Please try again.');
        }
    }
}
