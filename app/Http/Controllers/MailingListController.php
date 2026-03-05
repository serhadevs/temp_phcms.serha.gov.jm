<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\Auth;
use App\Models\MailingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class MailingListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mailing_list = MailingList::all();

        return view('admin.mailing_list.index', compact('mailing_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create(Request $request)
    // {
    //     try {
    //         DB::beginTransaction();
    //         if(MailingList::where('email', $request->data['email'])->first()){
    //             throw new Exception("THis user already exists in the mailing list");
    //         }
    //         if (MailingList::create(['email' => $request->data['email']])) {
    //                 DB::commit();
    //                 return [
    //                     'success',
    //                     'New Personnel: ' . $request->data['est_cat_name'] . ' has been added to the mailing list successfully.'
    //                 ];
               
    //         } else {
    //             throw new Exception("Error adding new personnel to mailing list. Unable to store record.");
    //         }
    //     } catch (Exception $ex) {
    //         DB::rollBack();
    //         return $ex->getMessage();
    //     }
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            if (MailingList::where('email', $request->data['email'])->first()) {
                throw new Exception("THis user already exists in the mailing list");
            }
            if (MailingList::create([
                'first_name' => $request->data['first_name'],
                'last_name' => $request->data['last_name'],
                'email' => $request->data['email'],
                'is_active' => 1,
                'user_id' => Auth()->user()->id
            ])) {
                DB::commit();
                return [
                    'success',
                    'New Personnel: ' . $request->data['email'] . ' has been added to the mailing list successfully.'
                ];
            } else {
                throw new Exception("Error adding new personnel to mailing list. Unable to store record.");
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $ex->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MailingList  $mailingList
     * @return \Illuminate\Http\Response
     */
    public function show(MailingList $mailingList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MailingList  $mailingList
     * @return \Illuminate\Http\Response
     */
    public function edit(MailingList $mailingList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MailingList  $mailingList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MailingList $mailingList)
    {
        try {
            DB::beginTransaction();
            if ($mailingList) {
                if ($mailingList->update([
                    'email' => $request->data['email'],
                    'first_name' => $request->data['first_name'],
                    'last_name' => $request->data['last_name']
                ])) {
                    DB::commit();
                    return [
                        'success',
                        'Mailing list personnel has been updated successfully'
                    ];
                } else {
                    throw new Exception("Error updating establishment category. Unable to update record.");
                }
            } else {
                throw new Exception("Unable to update mailing list personnel. This personnel no longer exists.");
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $ex->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MailingList  $mailingList
     * @return \Illuminate\Http\Response
     */
    public function destroy(MailingList $mailingList)
    {
        try {
            DB::beginTransaction();
            if ($mailingList) {
                if ($mailingList->update(['deleted_at' => date('Y-m-d H:i:s')])) {
                    DB::commit();
                    return [
                        'success',
                        'Mailing list personnel deleted successfully'
                    ];
                } else {
                    throw new Exception("Error deleting mailing list personnel. Unable to delete record.");
                }
            } else {
                throw new Exception("Unable to delete mailing list personnel. This mailing list personnel does not exist.");
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $ex->getMessage();
        }
    }

    public function changeActiveStatus(MailingList $mailingList)
    {
        try {
            DB::beginTransaction();
            if ($mailingList) {
                if ($mailingList->update(['is_active' => ($mailingList->is_active == '1' ? 0 : 1)])) {
                    DB::commit();
                    return [
                        'success',
                        'Mailing list personnel activated status updated successfully'
                    ];
                } else {
                    throw new Exception("Error changing activation status of mailing list personnel");
                }
            } else {
                throw new Exception("Error changing activation status of mailing list personnel. This mailing list personnel does not exist.");
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $ex->getMessage();
        }
    }
}
