<?php

namespace App\Http\Controllers;

use App\Models\PaymentTypes;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::channel('systemOperations')->info('Fetching payment report list', ['user_id' => auth()->user()->id]);
        $payment_types = PaymentTypes::with('paymentTypeFacilities')
            ->whereRelation('paymentTypeFacilities', 'facility_id', auth()->user()->facility_id)
            ->get();

        return view("reports.payments.index", compact('payment_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showReport(Request $request)
    {
        Log::channel('systemOperations')->info('Fetching payment report with custom date range', ['user_id' => auth()->user()->id]);
        $timeline = $request->validate([
            'starting_date' => 'required',
            'ending_date' => 'required',
            'payment_type_id' => 'required'
        ]);

        if (in_array(auth()->user()->role_id, [1, 5, 8, 9])) {
            $payments = DB::table('payments')
                ->join('application_types', 'application_types.id', '=', 'payments.application_type_id')
                ->selectRaw("application_types.name as app_type, payments.application_id as app_number, payments.receipt_no, payments.total_cost, payments.amount_paid, payments.change_amt, payments.created_at as payment_date, payments.manual_receipt_no")
                ->whereBetween('payments.created_at', [$timeline['starting_date'], date_format(new DateTime($timeline['ending_date'] . " 23:59:59"), 'Y-m-d H:m:s')])
                ->where('payments.facility_id', auth()->user()->facility_id)
                ->whereNull('payments.manual_receipt_no')
                ->when($request->payment_type_id == 1, function ($query, string $request) {
                    $query->where(function ($query2) use ($request) {
                        $query2->where('payment_type_id', 1)
                            ->orWhere('payment_type_id', null);
                    });
                })->when($request->payment_type_id == 2, function ($query, string $request) {
                    $query->where('payment_type_id', 2);
                })
                ->whereNull('payments.deleted_at')
                ->get();
        } else {
            $payments = DB::table('payments')
                ->join('application_types', 'application_types.id', '=', 'payments.application_type_id')
                ->selectRaw("application_types.name as app_type, payments.application_id as app_number, payments.receipt_no, payments.total_cost, payments.amount_paid, payments.change_amt, payments.created_at as payment_date, payments.manual_receipt_no")
                ->whereBetween('payments.created_at', [$timeline['starting_date'], date_format(new DateTime($timeline['ending_date'] . " 23:59:59"), 'Y-m-d H:m:s')])
                ->where('payments.facility_id', auth()->user()->facility_id)
                ->whereNull('payments.manual_receipt_no')
                ->when($request->payment_type_id == 1, function ($query, string $request) {
                    $query->where(function ($query2) use ($request) {
                        $query2->where('payment_type_id', 1)
                            ->orWhere('payment_type_id', null);
                    });
                })->when($request->payment_type_id == 2, function ($query, string $request) {
                    $query->where('payment_type_id', 2);
                })
                ->whereNull('payments.deleted_at')
                ->where('cashier_user_id', auth()->user()->id)
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
        Log::channel('systemOperations')->info('Creating payment report', ['user_id' => auth()->user()->id]);
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
        Log::channel('systemOperations')->info('Viewing payment report', ['user_id' => auth()->user()->id, 'id' => $id]);
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
        Log::channel('systemOperations')->info('Loading payment report edit form', ['user_id' => auth()->user()->id, 'id' => $id]);
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
        Log::channel('systemOperations')->info('Updating payment report', ['user_id' => auth()->user()->id, 'id' => $id]);
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
        Log::channel('systemOperations')->info('Deleting payment report', ['user_id' => auth()->user()->id, 'id' => $id]);
        //
    }
}
