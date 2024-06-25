<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StmpSettingsRequest;
use App\Models\StmpSettings;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function index()
    {
        $stmp = StmpSettings::find(1);
        //dd($stmp);
        return view('admin.index', compact('stmp'));
    }

    public function store(StmpSettingsRequest $request)
    {

        $stmp_settings = $request->validated();

       

       try {
        $stmp = StmpSettings::where('id', $stmp_settings['id'])->update($stmp_settings);
       } catch (Exception $e) {
        return redirect()->route('admin.index')->with('error', 'Unable to update STMP Settings' . $e->getMessage());
       }


        if (!$stmp) {
            return redirect()->back()->with('error', 'Unable to update the STMP Settings');
        }
        return redirect()->route('admin.index')->with('success', 'Successfully Updated STMP Settings');
    }

    public function create(){
        $stmp = StmpSettings::find(1);
        //dd($stmp);
        return view('admin.stmp', compact('stmp'));
    }
}
