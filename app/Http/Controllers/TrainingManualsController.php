<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrainingManualsController extends Controller
{
    public function index(){
        Log::channel('systemOperations')->info('Fetching training manual list', ['user_id' => auth()->user()->id]);
        return view('training.index');
    }
}
