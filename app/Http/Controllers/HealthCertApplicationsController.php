<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HealthCertApplicationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::channel('systemOperations')->info('Fetching health certificate application list', ['user_id' => auth()->user()->id]);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Log::channel('systemOperations')->info('Loading health certificate application create form', ['user_id' => auth()->user()->id]);
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
        Log::channel('systemOperations')->info('Creating health certificate application', ['user_id' => auth()->user()->id]);
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
        Log::channel('systemOperations')->info('Viewing health certificate application', ['user_id' => auth()->user()->id, 'id' => $id]);
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
        Log::channel('systemOperations')->info('Loading health certificate application edit form', ['user_id' => auth()->user()->id, 'id' => $id]);
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
        Log::channel('systemOperations')->info('Updating health certificate application', ['user_id' => auth()->user()->id, 'id' => $id]);
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
        Log::channel('systemOperations')->info('Deleting health certificate application', ['user_id' => auth()->user()->id, 'id' => $id]);
        //
    }
}
