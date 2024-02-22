<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("reports.payments.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showReport(Request $request)
    {
        $timeline = $request->validate([
            'starting_date'=>'required',
            'ending_date'=>'required'
        ]);

        //Only allow the following roles to view all the transactions 
        //Roles: 

        if(in_array(auth()->user()->role_id,[1,5,8,9])){

        $payments = DB::table('payments')
        ->join('application_types', 'application_types.id','=', 'payments.application_type_id')
        ->selectRaw("application_types.name as app_type, payments.application_id as app_number, payments.receipt_no, payments.total_cost, payments.amount_paid, payments.change_amt, payments.created_at as payment_date")
        ->whereBetween('payments.created_at', [$timeline['starting_date'], date_format(new DateTime($timeline['ending_date']." 23:59:59"),'Y-m-d H:m:s')])
        ->where('payments.facility_id',auth()->user()->facility_id)
        ->whereNull('payments.deleted_at')

        ->get();
        }else{
            $payments = DB::table('payments')
            ->join('application_types', 'application_types.id','=', 'payments.application_type_id')
            ->selectRaw("application_types.name as app_type, payments.application_id as app_number, payments.receipt_no, payments.total_cost, payments.amount_paid, payments.change_amt, payments.created_at as payment_date")
            ->whereBetween('payments.created_at', [$timeline['starting_date'], date_format(new DateTime($timeline['ending_date']." 23:59:59"),'Y-m-d H:m:s')])
            ->where('payments.facility_id',auth()->user()->facility_id)
            ->whereNull('payments.deleted_at')
            ->where('cashier_user_id',auth()->user()->id)
            ->get();

        }

        $json_payments = json_encode($payments);

        // dd($payments);

        return view('reports.payments.report', compact('json_payments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
  }
