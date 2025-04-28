<?php

namespace App\Http\Controllers;

use App\Jobs\SendOnlineUserVerifyEmail;
use App\Mail\OnlineUsersVerifyEmail;
use App\Models\OnlineUser;
use App\Models\PermitApplication;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class OnlineApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->hasValidSignature()) {
            $online_user = OnlineUser::find($request->online_user);
            return view('temp_online.application', compact('online_user'));
        } else {
            $message = "Verification link for continuing application has expired";
            $message_2 = "Want to request another link?";
            return view('temp_online.verify_email_confirmation', compact('message', 'message_2'));
        }
    }

    public function createConfirm()
    {
        return view('temp_online.verify_email');
    }

    public function getStarted()
    {
        return view('temp_online.index');
    }

    public function resendLink(){
        return view('temp_online.verify_email_confirmation');
    }


    // public function verifyEmail(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email'
    //     ]);

    //     $new_online_user = OnlineUser::create([
    //         'email' => $request->email
    //     ]);

    //     $services = new Services();

    //     if ($services->sendOUVerifyEmail($request->email, URL::temporarySignedRoute('permit.online.application', now()->addMinutes(60), ['online_user' => $new_online_user->id]), $new_online_user->id)) {
    //         return view('temp_online.verify_email_confirmation');
    //     }
    // }

    public function verifyEmail(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            $new_online_user = OnlineUser::create([
                'email' => $request->email
            ]);

            $services = new Services();
            $temporaryUrl = URL::temporarySignedRoute(
                'permit.online.application',
                now()->addMinutes(60),
                ['online_user' => $new_online_user->id]
            );

            if ($services->sendOUVerifyEmail($request->email, $temporaryUrl, $new_online_user->id)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Verification email sent successfully',
                    'redirect' => '/permit/online/application/resend-link'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send verification email'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $form_info = $request->validate([
            // 'permit_category_id' => 'required',
            'confirm_fname' => "required",
            'confirm_mname' => "nullable",
            'confirm_lname' => "required",
            'confirm_address' => 'required',
            'confirm_dob' => 'required|date',
            'confirm_sex' => 'required',
            'no_of_years' => ['required_if:permit_type,student'],
            'confirm_cell_num' => 'nullable',
            'confirm_home_num' => 'nullable',
            'confirm_work_num' => 'nullable',
            'confirm_occupation' => 'nullable',
            'confirm_employer' => 'nullable',
            'confirm_employer_address' => 'nullable',
            'confirm_mail_address' => 'nullable|email',
            'confirm_trn' => 'nullable',
            'confirm_applied' => 'required',
            'confirm_granted' => 'required_if:applied_before,=,1',
            'photo_upload' => 'nullable',
            'confirm_parish' => 'required',
            'confirm_employer_parish' => 'required',
            //Student and Teacher
            'confirm_student' => 'required',
            'confirm_teacher' => 'required'
        ]);

        // try {
        $services = new Services();
        $permit_no = $services->generatePermitPermitNo();

        //Image Upload
        if ($request->file('photo_upload')) {
            $path = $request->file('photo_upload')->storeAs('photo_uploads', $permit_no . '.' . $request->photo_upload->extension(), 'public');
            $photo_upload = $path;
        } else {
            $photo_upload = "";
        }

        //Upload Signature
        if ($request->has('signature')) {
            $signature = $request->input('signature');
            $signatureImage = base64_decode(explode(',', $signature)[1]);
            $signatureName = $permit_no . '_signature.png';
            Storage::disk('public')->put('signatures/' . $signatureName, $signatureImage);
            $form_data['signature_link'] = 'signatures/' . $signatureName;
        }


        // $exists = PermitApplication::where([
        //     ['firstname', '=', $permit_application['firstname']],
        //     ['lastname', '=', $permit_application['lastname']],
        //     ['date_of_birth', '=', $permit_application['date_of_birth']],
        //     ['cell_phone', '=', $permit_application['cell_phone']],
        // ])
        //     ->where('created_at', '>', date_format(new DateTime(), 'Y-m-d'))
        //     ->exists();

        $new_application = PermitApplication::create([
            'permit_category_id' => 1,
            'user_id' => 200, //Replace with id for online users,
            'permit_no' => $permit_no,
            'firstname' => $form_info['confirm_fname'],
            'middlename' => $form_info['confirm_mname'],
            'lastname' => $form_info['confirm_lname'],
            'address' => $form_info['confirm_address'] . ", " . $form_info["confirm_parish"],
            'date_of_birth' => $form_info["confirm_dob"],
            'gender' => $form_info["confirm_sex"],
            'permit_type' => $form_info["confirm_student"] == "1" ? "student" : ($form_info['confirm_teacher'] == "1" ? "teacher" : "regular"),
            'cell_phone' => $form_info["confirm_cell_num"],
            'home_phone' => $form_info["confirm_home_num"],
            'work_phone' => $form_info["confirm_work_num"],
            'occupation' => $form_info["confirm_occupation"],
            'employer' => $form_info["confirm_employer"],
            'employer_address' => $form_info["confirm_employer_address"] . ", " . $form_info["confirm_employer_parish"],
            'email' => $form_info["confirm_mail_address"],
            'trn' => $form_info["confirm_trn"],
            'applied_before' => $form_info['confirm_applied'],
            'granted' => $form_info['confirm_granted'],
            'photo_upload' => $photo_upload,
            'application_date' => date("Y-m-d"),
            'signature_link' => $form_data['signature_link']
        ]);

        // if ($new_application) {
        //     return view('temp_online.payment', compact('new_application'));
        // }

        if ($new_application) {
            $expires = time() + 3600; // Expires in 1 hour
            $onlineUser = $request->online_id; 
            
            $data = "expires={$expires}&online_user={$onlineUser}";
            $signature = hash_hmac('sha256', $data, config('app.key'));
            
            return redirect()->route('permit.online.application.payment', [
                'id' => $new_application->id,
                'expires' => $expires,
                'online_user' => $onlineUser,
                'signature' => $signature
            ])->with('success', 'Application created successfully');
            return redirect()->route('permit.online.application.payment.coupon', ['id' => $new_application->id])->with('success', 'Application created successfully');
        }
        // } catch (Exception $e) {
        // }
    }

    public function payment($id)
    {
        $permit_application = PermitApplication::find($id);
        return view('temp_online.payment', compact('permit_application'));
    }

    public function redeemCoupon($id){
        $permit_application = PermitApplication::find($id);
        return view('temp_online.coupon', compact('permit_application'));
    }

    public function completedApplication($id)
    {
        $application = PermitApplication::find($id);
        return view('temp_online.completed_application', compact('application'));
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
