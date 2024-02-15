<?php

namespace App\Http\Controllers;

use App\Models\FoodEstablishment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class FoodEstablishmentController extends Controller
{
    public function index()
    {
        return view('establishments.index');
    }

    public function view()
    {
        //Fetch all certificates 

        $certificate = FoodEstablishment::join('food_est_operators', 'food_est_operators.establishment_application_id', '=', 'establishment_applications.id')
        ->join('establishment_categories', 'establishment_categories.id', '=', 'establishment_applications.establishment_category_id')
        ->leftJoin('sign_offs', function ($join) {
            $join->on('sign_offs.application_id', '=', 'establishment_applications.id')
                ->where('application_type_id', '=', 3);
        })
        ->join('users', 'users.id', '=', 'establishment_applications.user_id')
        ->leftJoin('payments', function ($join) {
            $join->on('payments.application_id', '=', 'establishment_applications.id')
                ->where('payments.deleted_at','=',null)
                ->where('payments.application_type_id', '=', 3)
                ->where('payments.facility_id', '=', auth()->user()->facility_id);
        })
        ->select('establishment_applications.*', 
            'food_est_operators.name_of_operator as operators',
            'establishment_categories.name as categories', 
            'payments.created_at as payment',
            'sign_offs.expiry_date',
            'users.firstname',
            'users.lastname'
        )
        ->whereIn('establishment_applications.user_id', User::facilityUsers()->pluck('id')->flatten())
        ->where('establishment_applications.deleted_at','=',null)
        //->groupBy('establishment_applications.id')
        ->orderBy('establishment_applications.application_date', 'desc')
        ->distinct('establishment_applications.id')
        ->get();
    
        //dd($certificate);

        return view('establishments.view', compact('certificate'));
    }

    public function showApplications()
    {
        //Show all applications based on date 


    }
}
