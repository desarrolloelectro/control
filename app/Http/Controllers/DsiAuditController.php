<?php

namespace App\Http\Controllers;

use App\DsiAudit;
use Illuminate\Http\Request;

class DsiAuditController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DsiAudit  $dsiAudit
     * @return \Illuminate\Http\Response
     */
    public function show(DsiAudit $id)
    {
        dd($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DsiAudit  $dsiAudit
     * @return \Illuminate\Http\Response
     */
    public function edit(DsiAudit $dsiAudit)
    {
        dd($dsiAudit);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DsiAudit  $dsiAudit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        dd($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DsiAudit  $dsiAudit
     * @return \Illuminate\Http\Response
     */
    public function destroy(DsiAudit $dsiAudit)
    {
        //
    }
}
