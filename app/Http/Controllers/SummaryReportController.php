<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointments;
use App\Models\EstablishmentApplications;
use App\Models\EstablishmentClinics;
use App\Models\HealthCertApplications;
use App\Models\TestResult;
use App\Models\Payments;
use App\Models\PaymentTypeFacilities;
use App\Models\PaymentTypes;
use App\Models\PermitApplication;
use App\Models\Renewals;
use App\Models\SignOff;
use App\Models\SwimmingPoolsApplications;
use App\Models\TouristEstablishments;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SummaryReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $payment_types = PaymentTypes::with('paymentTypeFacilities')
            ->whereRelation('paymentTypeFacilities', 'facility_id', auth()->user()->facility_id)
            ->get();

        $facilities_with_pos = PaymentTypeFacilities::where('payment_type_id', 2)
            ->pluck('facility_id')
            ->toArray()
            // ->get()
        ;

        // dd(array($facilities_with_pos));

        return view('reports.summaryreport.index', compact('payment_types', 'facilities_with_pos'));
    }

    public function show(Request $request)
    {
        $timeline = $request->validate([
            'starting_date' => 'required|date',
            'ending_date' => 'required|date',
        ]);

        //$request->payment_type_id == 100
        //This is for both cash and card

        $foodHandlers = $this->foodhandlerSummary($timeline['starting_date'], $timeline['ending_date'], $request->payment_type_id);
        $barberCosmet = $this->barberCosmetSummary($timeline['starting_date'], $timeline['ending_date'], $request->payment_type_id);
        $foodEstablishments = $this->foodEstablishmentSummary($timeline['starting_date'], $timeline['ending_date'], $request->payment_type_id);
        $swimmingPools = $this->swimmingPoolSummary($timeline['starting_date'], $timeline['ending_date'], $request->payment_type_id);
        $touristEstablishments = $this->touristEstablishmentSummary($timeline['starting_date'], $timeline['ending_date'], $request->payment_type_id);
        $foodClinics = $this->foodHandlerClinicSummary($timeline['starting_date'], $timeline['ending_date'], $request->payment_type_id);

        $starting_date = $timeline['starting_date'];
        $ending_date = $timeline['ending_date'];

        return view('reports.summaryreport.report', compact('foodHandlers', 'barberCosmet', 'foodEstablishments', 'swimmingPools', 'touristEstablishments', 'foodClinics', 'starting_date', 'ending_date'));
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

    private function foodhandlerSummary($starting_date, $ending_date, $payment_type)
    {
        // dd($payment_type);
        $count = PermitApplication::with('payment')
            //Need to be changed
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->when($payment_type, function ($query, string $payment_type) {
                $query->has('payment');
                $query->when($payment_type == 1, function ($query2, string $payment_type) {
                    $query2->whereHas('payment', function ($q) {
                        $q->where('payment_type_id', 1)
                            ->orWhere('payment_type_id', NULL);
                    });
                });

                $query->when($payment_type == 2, function ($query2, string $payment_type) {
                    $query2->whereRelation('payment', 'payment_type_id', 2);
                });
            })
            ->whereBetween('application_date', [$starting_date, $ending_date])
            ->count();

        $noSignOffs = SignOff::with('permitApplication.payment')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('sign_off_date', [$starting_date, $ending_date])
            ->where('application_type_id', 1)
            ->when($payment_type, function ($query, string $payment_type) {
                $query->has('permitApplication.payment');
                $query->when($payment_type == 1, function ($query2, string $payment_type) {
                    $query2->whereHas('permitApplication.payment', function ($q) {
                        $q->where('payment_type_id', 1)
                            ->orWhere('payment_type_id', NULL);
                    });
                });

                $query->when($payment_type == 2, function ($query2, string $payment_type) {
                    $query2->whereRelation('permitApplication.payment', 'payment_type_id', 2);
                });
            })
            ->count();

        //Where in might be wrong
        $noRenewals = Renewals::with('permitApplication.payment')
            ->when($payment_type, function ($query, string $payment_type) {
                $query->has('permitApplication.payment');
                $query->when($payment_type == 1, function ($query2, string $payment_type) {
                    $query2->whereHas('permitApplication.payment', function ($q) {
                        $q->where('payment_type_id', 1)
                            ->orWhere('payment_type_id', NULL);
                    });
                });

                $query->when($payment_type == 2, function ($query2, string $payment_type) {
                    $query2->whereRelation('permitApplication.payment', 'payment_type_id', 2);
                });
            })
            ->whereRelation('permitApplication', 'application_date', '>=', $starting_date)
            ->whereRelation('permitApplication', 'application_date', '<=', $ending_date)
            ->where('application_type_id', 1)
            ->whereHas('permitApplication', function ($query4) {
                $query4->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten());
            })
            ->count();

        $cats = PermitApplication::with('payment')
            ->when($payment_type, function ($query, string $payment_type) {
                $query->has('payment');
                $query->when($payment_type == 1, function ($query2, string $payment_type) {
                    $query2->whereHas('payment', function ($q) {
                        $q->where('payment_type_id', 1)
                            ->orWhere('payment_type_id', NULL);
                    });
                });

                $query->when($payment_type == 2, function ($query2, string $payment_type) {
                    $query2->whereRelation('payment', 'payment_type_id', 2);
                });
            })
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('application_date', [$starting_date, $ending_date])
            ->groupBy('permit_category_id')
            ->select('permit_category_id', DB::raw('count(*) as total'))
            ->get();

        if ($payment_type == 1) {
            $sum_foodHandlers = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 1)
                ->where(function ($query3) {
                    $query3->where('payment_type_id', 1)
                        ->orWhere('payment_type_id', NULL);
                })
                ->select('total_cost')
                ->get();
        } else if ($payment_type == 2) {
            $sum_foodHandlers = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 1)
                ->where('payment_type_id', 2)
                ->select('total_cost')
                ->get();
        } else {
            $sum_foodHandlers = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 1)
                ->select('total_cost')
                ->get();
        }

        $sum_foodHandlers = $sum_foodHandlers->sum('total_cost');

        $max = 0;
        $min = 0;
        $maxCat = "None Found";
        $minCat = "none Found";

        if (!$cats->isEmpty()) {
            $max = $cats->max('total');
            $maxCat = $cats->where('total', $max)->first()->permitCategory->name;
            $min = $cats->min('total');
            $minCat = $cats->where('total', $min)->first()->permitCategory->name;
        }

        $noTrainingSessions = Appointments::with('applications.payment')
            ->when($payment_type, function ($query, string $payment_type) {
                $query->has('applications.payment');
                $query->when($payment_type == 1, function ($query2, string $payment_type) {
                    $query2->whereHas('applications.payment', function ($q) {
                        $q->where('payment_type_id', 1)
                            ->orWhere('payment_type_id', NULL);
                    });
                });

                $query->when($payment_type == 2, function ($query2, string $payment_type) {
                    $query2->whereRelation('applications.payment', 'payment_type_id', 2);
                });
            })
            ->where('facility_id', auth()->user()->facility_id)
            ->whereBetween('appointment_date', [$starting_date, $ending_date])
            ->count();

        $data = array($count, $count - $noRenewals, $noRenewals, $noSignOffs, $max . '-' . $maxCat, $min . '-' . $minCat, $noTrainingSessions, $sum_foodHandlers);
        return $data;
    }

    private function barberCosmetSummary($starting_date, $ending_date, $payment_type)
    {
        $count = HealthCertApplications::with('payment')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('application_date', [$starting_date, $ending_date])
            ->when($payment_type, function ($query, string $payment_type) {
                $query->has('payment');
                $query->when($payment_type == 1, function ($query2, string $payment_type) {
                    $query2->whereHas('payment', function ($q) {
                        $q->where('payment_type_id', 1)
                            ->orWhere('payment_type_id', NULL);
                    });
                });

                $query->when($payment_type == 2, function ($query2, string $payment_type) {
                    $query2->whereRelation('payment', 'payment_type_id', 2);
                });
            })
            ->count();

        $noSignOffs = SignOff::with('healthCertApplication.payment')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('sign_off_date', [$starting_date, $ending_date])
            ->where('application_type_id', 2)
            ->when($payment_type, function ($query, string $payment_type) {
                $query->has('healthCertApplication.payment');
                $query->when($payment_type == 1, function ($query2, string $payment_type) {
                    $query2->whereHas('healthCertApplication.payment', function ($q) {
                        $q->where('payment_type_id', 1)
                            ->orWhere('payment_type_id', NULL);
                    });
                });

                $query->when($payment_type == 2, function ($query2, string $payment_type) {
                    $query2->whereRelation('healthCertApplication.payment', 'payment_type_id', 2);
                });
            })
            ->count();

        $noRenewals = Renewals::with('healthCertApplication.payment')
            ->when($payment_type, function ($query, string $payment_type) {
                $query->has('healthCertApplication.payment');
                $query->when($payment_type == 1, function ($query2, string $payment_type) {
                    $query2->whereHas('healthCertApplication.payment', function ($q) {
                        $q->where('payment_type_id', 1)
                            ->orWhere('payment_type_id', NULL);
                    });
                });

                $query->when($payment_type == 2, function ($query2, string $payment_type) {
                    $query2->whereRelation('healthCertApplication.payment', 'payment_type_id', 2);
                });
            })
            ->whereRelation('healthCertApplication', 'application_date', '>=', $starting_date)
            ->whereRelation('healthCertApplication', 'application_date', '<=', $ending_date)
            ->where('application_type_id', 2)
            ->whereHas('healthCertApplication', function ($query4) {
                $query4->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten());
            })
            ->count();


        // $sum_barberCosmet = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
        //     ->where('facility_id', auth()->user()->facility_id)
        //     ->where('application_type_id', 2)
        //     ->selectRaw('total_cost')
        //     ->get();
        if ($payment_type == 1) {
            $sum_barberCosmet = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 2)
                ->where(function ($query3) {
                    $query3->where('payment_type_id', 1)
                        ->orWhere('payment_type_id', NULL);
                })
                ->select('total_cost')
                ->get();
        } else if ($payment_type == 2) {
            $sum_barberCosmet = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 2)
                ->where('payment_type_id', 2)
                ->select('total_cost')
                ->get();
        } else {
            $sum_barberCosmet = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 2)
                ->select('total_cost')
                ->get();
        }

        $sum_barberCosmet = $sum_barberCosmet->sum('total_cost');

        $data = array(
            $count,
            $count - $noRenewals,
            $noRenewals,
            $noSignOffs,
            'N/A',
            'N/A',
            'N/A',
            $sum_barberCosmet
        );

        return $data;
    }

    private function foodEstablishmentSummary($starting_date, $ending_date, $payment_type)
    {
        $count = EstablishmentApplications::with('payment')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->when($payment_type, function ($query, string $payment_type) {
                $query->has('payment');
                $query->when($payment_type == 1, function ($query2, string $payment_type) {
                    $query2->whereHas('payment', function ($q) {
                        $q->where('payment_type_id', 1)
                            ->orWhere('payment_type_id', NULL);
                    });
                });

                $query->when($payment_type == 2, function ($query2, string $payment_type) {
                    $query2->whereRelation('payment', 'payment_type_id', 2);
                });
            })
            ->whereBetween('application_date', [$starting_date, $ending_date])
            ->count();

        $noSignOffs = SignOff::with('estApplication.payment')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('sign_off_date', [$starting_date, $ending_date])
            ->where('application_type_id', 3)
            ->when($payment_type, function ($query, string $payment_type) {
                $query->has('estApplication.payment');
                $query->when($payment_type == 1, function ($query2, string $payment_type) {
                    $query2->whereHas('estApplication.payment', function ($q) {
                        $q->where('payment_type_id', 1)
                            ->orWhere('payment_type_id', NULL);
                    });
                });

                $query->when($payment_type == 2, function ($query2, string $payment_type) {
                    $query2->whereRelation('estApplication.payment', 'payment_type_id', 2);
                });
            })
            ->count();

        $noRenewals = Renewals::with('establishmentApplication.payment')
            // ->whereBetween('application_date', [$starting_date, $ending_date])
            // ->whereIn('ea.user_id', User::facilityUsers()->pluck('id')->flatten())
            // ->whereNotNull('ea.id')
            ->when($payment_type, function ($query, string $payment_type) {
                $query->has('establishmentApplication.payment');
                $query->when($payment_type == 1, function ($query2, string $payment_type) {
                    $query2->whereHas('establishmentApplication.payment', function ($q) {
                        $q->where('payment_type_id', 1)
                            ->orWhere('payment_type_id', NULL);
                    });
                });

                $query->when($payment_type == 2, function ($query2, string $payment_type) {
                    $query2->whereRelation('establishmentApplication.payment', 'payment_type_id', 2);
                });
            })
            ->whereRelation('establishmentApplication', 'application_date', '>=', $starting_date)
            ->whereRelation('establishmentApplication', 'application_date', '<=', $ending_date)
            ->where('application_type_id', 3)
            ->whereHas('establishmentApplication', function ($query4) {
                $query4->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten());
            })
            ->count();

        $cats = EstablishmentApplications::with('payment')
            ->when($payment_type, function ($query, string $payment_type) {
                $query->has('payment');
                $query->when($payment_type == 1, function ($query2, string $payment_type) {
                    $query2->whereHas('payment', function ($q) {
                        $q->where('payment_type_id', 1)
                            ->orWhere('payment_type_id', NULL);
                    });
                });

                $query->when($payment_type == 2, function ($query2, string $payment_type) {
                    $query2->whereRelation('payment', 'payment_type_id', 2);
                });
            })
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->groupBy('establishment_category_id')
            ->whereBetween('application_date', [$starting_date, $ending_date])
            ->select('establishment_category_id', DB::raw('count(*) as total'))
            ->get();

        // $sum_foodEstablishment = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
        //     ->where('facility_id', auth()->user()->facility_id)
        //     ->where('application_type_id', 3)
        //     ->selectRaw('total_cost')
        //     ->get();
        if ($payment_type == 1) {
            $sum_foodEstablishment = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 3)
                ->where(function ($query3) {
                    $query3->where('payment_type_id', 1)
                        ->orWhere('payment_type_id', NULL);
                })
                ->select('total_cost')
                ->get();
        } else if ($payment_type == 2) {
            $sum_foodEstablishment = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 3)
                ->where('payment_type_id', 2)
                ->select('total_cost')
                ->get();
        } else {
            $sum_foodEstablishment = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 3)
                ->select('total_cost')
                ->get();
        }


        $sum_foodEstablishment = $sum_foodEstablishment->sum('total_cost');

        $max = 0;
        $min = 0;
        $maxCat = "None Found";
        $minCat = "none Found";

        if (!$cats->isEmpty()) {
            $max = $cats->max('total');
            $maxCat = $cats->where('total', $max)->first()->establishmentCategory?->name;
            $min = $cats->min('total');
            $minCat = $cats->where('total', $min)->first()->establishmentCategory?->name;
        }

        $data = array(
            $count,
            $count - $noRenewals,
            $noRenewals,
            $noSignOffs,
            $max . '-' . $maxCat,
            $min . '-' . $minCat,
            'N/A',
            $sum_foodEstablishment
        );

        return $data;
    }

    private function swimmingPoolSummary($starting_date, $ending_date, $payment_type)
    {
        $count = SwimmingPoolsApplications::with('payment')
            ->when($payment_type, function ($query, string $payment_type) {
                $query->has('payment');
                $query->when($payment_type == 1, function ($query2, string $payment_type) {
                    $query2->whereHas('payment', function ($q) {
                        $q->where('payment_type_id', 1)
                            ->orWhere('payment_type_id', NULL);
                    });
                });

                $query->when($payment_type == 2, function ($query2, string $payment_type) {
                    $query2->whereRelation('payment', 'payment_type_id', 2);
                });
            })
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('application_date', [$starting_date, $ending_date])
            ->count();

        $noSignOffs = SignOff::with('swimmingPool.payment')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('sign_off_date', [$starting_date, $ending_date])
            ->where('application_type_id', 5)
            ->when($payment_type, function ($query, string $payment_type) {
                $query->has('swimmingPool.payment');
                $query->when($payment_type == 1, function ($query2, string $payment_type) {
                    $query2->whereHas('swimmingPool.payment', function ($q) {
                        $q->where('payment_type_id', 1)
                            ->orWhere('payment_type_id', NULL);
                    });
                });

                $query->when($payment_type == 2, function ($query2, string $payment_type) {
                    $query2->whereRelation('swimmingPool.payment', 'payment_type_id', 2);
                });
            })
            ->count();

        $noRenewals = Renewals::with('swimmingPoolApplication.payment')
            // ->whereBetween('application_date', [$starting_date, $ending_date])
            // ->whereIn('sa.user_id', User::facilityUsers()->pluck('id')->flatten())
            ->when($payment_type, function ($query, string $payment_type) {
                $query->has('swimmingPoolApplication.payment');
                $query->when($payment_type == 1, function ($query2, string $payment_type) {
                    $query2->whereHas('swimmingPoolApplication.payment', function ($q) {
                        $q->where('payment_type_id', 1)
                            ->orWhere('payment_type_id', NULL);
                    });
                });

                $query->when($payment_type == 2, function ($query2, string $payment_type) {
                    $query2->whereRelation('swimmingPoolApplication.payment', 'payment_type_id', 2);
                });
            })
            ->whereRelation('swimmingPoolApplication', 'application_date', '>=', $starting_date)
            ->whereRelation('swimmingPoolApplication', 'application_date', '<=', $ending_date)
            ->where('application_type_id', 5)
            ->whereHas('permitApplication', function ($query4) {
                $query4->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten());
            })
            // ->where('sa.deleted_at', null)
            ->count();


        // $sum_swimmingPool = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
        //     ->where('facility_id', auth()->user()->facility_id)
        //     ->where('application_type_id', 5)
        //     ->selectRaw('total_cost')
        //     ->get();
        if ($payment_type == 1) {
            $sum_swimmingPool = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 5)
                ->where(function ($query3) {
                    $query3->where('payment_type_id', 1)
                        ->orWhere('payment_type_id', NULL);
                })
                ->select('total_cost')
                ->get();
        } else if ($payment_type == 2) {
            $sum_swimmingPool = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 5)
                ->where('payment_type_id', 2)
                ->select('total_cost')
                ->get();
        } else {
            $sum_swimmingPool = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 5)
                ->select('total_cost')
                ->get();
        }

        $sum_swimmingPool = $sum_swimmingPool->sum('total_cost');

        return array(
            $count,
            $count - $noRenewals,
            $noRenewals,
            $noSignOffs,
            'N/A',
            'N/A',
            'N/A',
            $sum_swimmingPool
        );
    }

    private function touristEstablishmentSummary($starting_date, $ending_date, $payment_type)
    {
        $count = TouristEstablishments::with('payments')
            ->when($payment_type, function ($query, string $payment_type) {
                $query->has('payments');
                $query->when($payment_type == 1, function ($query2, string $payment_type) {
                    $query2->whereHas('payments', function ($q) {
                        $q->where('payment_type_id', 1)
                            ->orWhere('payment_type_id', NULL);
                    });
                });

                $query->when($payment_type == 2, function ($query2, string $payment_type) {
                    $query2->whereRelation('payments', 'payment_type_id', 2);
                });
            })
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('application_date', [$starting_date, $ending_date])
            ->count();

        $noSignOffs = SignOff::with('touristEstApplication.payments')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('sign_off_date', [$starting_date, $ending_date])
            ->where('application_type_id', 6)
            ->when($payment_type, function ($query, string $payment_type) {
                $query->has('touristEstApplication.payments');
                $query->when($payment_type == 1, function ($query2, string $payment_type) {
                    $query2->whereHas('touristEstApplication.payments', function ($q) {
                        $q->where('payment_type_id', 1)
                            ->orWhere('payment_type_id', NULL);
                    });
                });

                $query->when($payment_type == 2, function ($query2, string $payment_type) {
                    $query2->whereRelation('touristEstApplication.payments', 'payment_type_id', 2);
                });
            })
            ->count();

        $noRenewals = Renewals::with('touristEstApplication.payments')
            ->when($payment_type, function ($query, string $payment_type) {
                $query->has('touristEstApplication.payments');
                $query->when($payment_type == 1, function ($query2, string $payment_type) {
                    $query2->whereHas('touristEstApplication.payments', function ($q) {
                        $q->where('payment_type_id', 1)
                            ->orWhere('payment_type_id', NULL);
                    });
                });

                $query->when($payment_type == 2, function ($query2, string $payment_type) {
                    $query2->whereRelation('touristEstApplication.payments', 'payment_type_id', 2);
                });
            })
            ->whereRelation('touristEstApplication', 'application_date', '>=', $starting_date)
            ->whereRelation('touristEstApplication', 'application_date', '<=', $ending_date)
            // ->whereBetween('application_date', [$starting_date, $ending_date])
            // ->whereIn('ta.user_id', User::facilityUsers()->pluck('id')->flatten())
            ->where('application_type_id', 6)
            // ->where('ta.deleted_at', null)
            ->whereHas('touristEstApplication', function ($query4) {
                $query4->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten());
            })
            ->count();

        // $sum_touristEstablishments = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
        //     ->where('facility_id', auth()->user()->facility_id)
        //     ->where('application_type_id', 6)
        //     ->selectRaw('total_cost')
        //     ->get();
        if ($payment_type == 1) {
            $sum_touristEstablishments = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 6)
                ->where(function ($query3) {
                    $query3->where('payment_type_id', 1)
                        ->orWhere('payment_type_id', NULL);
                })
                ->select('total_cost')
                ->get();
        } else if ($payment_type == 2) {
            $sum_touristEstablishments = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 6)
                ->where('payment_type_id', 2)
                ->select('total_cost')
                ->get();
        } else {
            $sum_touristEstablishments = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 6)
                ->select('total_cost')
                ->get();
        }

        // $persons_trained = TestResult::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
        //     ->where('facility_id', auth()->user()->facility_id)
        //     ->where('application_type_id', 6)->count();

        $persons_trained = TestResult::with('touristEstablishment.payments')
            ->when($payment_type, function ($query, string $payment_type) {
                $query->has('touristEstablishment.payments');
                $query->when($payment_type == 1, function ($query2, string $payment_type) {
                    $query2->whereHas('touristEstablishment.payments', function ($q) {
                        $q->where('payment_type_id', 1)
                            ->orWhere('payment_type_id', NULL);
                    });
                });

                $query->when($payment_type == 2, function ($query2, string $payment_type) {
                    $query2->whereRelation('touristEstablishment.payments', 'payment_type_id', 2);
                });
            })
            ->whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
            ->where('facility_id', auth()->user()->facility_id)
            ->where('application_type_id', 6)
            ->count();
        // $noTrainingSessions = Appointments::where('facility_id', auth()->user()->facility_id)
        //     ->whereBetween('appointment_date', [$starting_date, $ending_date])
        //     ->count();

        //dd($persons_trained);

        $sum_touristEstablishments = $sum_touristEstablishments->sum('total_cost');

        return array(
            $count,
            $count - $noRenewals,
            $noRenewals,
            $noSignOffs,
            'N/A',
            'N/A',
            $persons_trained,
            $sum_touristEstablishments
        );
    }

    private function foodHandlerClinicSummary($starting_date, $ending_date, $payment_type)
    {
        $count = EstablishmentClinics::with('payment')
            ->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten())
            ->whereBetween('application_date', [$starting_date, $ending_date])
            ->when($payment_type, function ($query, string $payment_type) {
                $query->has('payment');
                $query->when($payment_type == 1, function ($query2, string $payment_type) {
                    $query2->whereHas('payment', function ($q) {
                        $q->where('payment_type_id', 1)
                            ->orWhere('payment_type_id', NULL);
                    });
                });

                $query->when($payment_type == 2, function ($query2, string $payment_type) {
                    $query2->whereRelation('payment', 'payment_type_id', 2);
                });
            })
            ->count();

        $noSignOffs = 0;

        $noRenewals = Renewals::with('estClinic.payment')
            ->when($payment_type, function ($query, string $payment_type) {
                $query->has('estClinic.payment');
                $query->when($payment_type == 1, function ($query2, string $payment_type) {
                    $query2->whereHas('estClinic.payment', function ($q) {
                        $q->where('payment_type_id', 1)
                            ->orWhere('payment_type_id', NULL);
                    });
                });

                $query->when($payment_type == 2, function ($query2, string $payment_type) {
                    $query2->whereRelation('estClinic.payment', 'payment_type_id', 2);
                });
            })
            ->whereRelation('estClinic', 'application_date', '>=', $starting_date)
            ->whereRelation('estClinic', 'application_date', '<=', $ending_date)
            // ->whereBetween('application_date', [$starting_date, $ending_date])
            // ->whereIn('eca.user_id', User::facilityUsers()->pluck('id')->flatten())
            ->where('application_type_id', 4)
            // ->where('eca.deleted_at', null)
            ->whereHas('estClinic', function ($query4) {
                $query4->whereIn('user_id', User::facilityUsers()->pluck('id')->flatten());
            })
            ->count();

        // $sum_foodClinics = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
        //     ->where('facility_id', auth()->user()->facility_id)
        //     ->where('application_type_id', 4)
        //     ->selectRaw('total_cost')
        //     ->get();
        if ($payment_type == 1) {
            $sum_foodClinics = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 4)
                ->where(function ($query3) {
                    $query3->where('payment_type_id', 1)
                        ->orWhere('payment_type_id', NULL);
                })
                ->select('total_cost')
                ->get();
        } else if ($payment_type == 2) {
            $sum_foodClinics = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 4)
                ->where('payment_type_id', 2)
                ->select('total_cost')
                ->get();
        } else {
            $sum_foodClinics = Payments::whereBetween('created_at', [$starting_date, $ending_date . " 23:59:59"])
                ->where('facility_id', auth()->user()->facility_id)
                ->where('application_type_id', 4)
                ->select('total_cost')
                ->get();
        }

        $sum_foodClinics = $sum_foodClinics->sum('total_cost');

        return array(
            $count,
            $count - $noRenewals,
            $noRenewals,
            $noSignOffs,
            'N/A',
            'N/A',
            'N/A',
            $sum_foodClinics
        );
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
