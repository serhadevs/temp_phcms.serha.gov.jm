<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StmpSettingsRequest;
use App\Models\Role;
use App\Models\StmpSettings;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkRole:1');
    }

    public function index()
    {
        
        $stmp = StmpSettings::find(1);
        $roles = DB::table('roles')->get();
        //dd($roles);
        return view('admin.index', compact('stmp','roles'));
    }

    public function store(StmpSettingsRequest $request)
    {

        $stmp_settings = $request->validated();

       try {
        $stmp = StmpSettings::where('id', $stmp_settings['id'])->update($stmp_settings);
        
        if (!$stmp) {
            return redirect()->back()->with('error', 'Unable to update the STMP Settings');
        }
        return redirect()->route('admin.index')->with('success', 'Successfully Updated STMP Settings');

       } catch (Exception $e) {
        return redirect()->route('admin.index')->with('error', 'Unable to update STMP Settings' . $e->getMessage());
       } catch (QueryException $e){
        return redirect()->route('admin.index')->with('error', 'There is an issue with the query' . $e->getMessage());
       }


    }

    public function create(){
        $stmp = StmpSettings::find(1);
        
        return view('admin.stmp', compact('stmp'));
    }
}
