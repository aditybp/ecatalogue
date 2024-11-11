<?php

namespace App\Http\Controllers;

use App\Models\KuisionerPdfData;
use Illuminate\Http\Request;

class KuisionerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getKuisionerData($id) {}

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
     * @param  \App\Models\KuisionerPdfData  $kuisionerPdfData
     * @return \Illuminate\Http\Response
     */
    public function show(KuisionerPdfData $kuisionerPdfData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\KuisionerPdfData  $kuisionerPdfData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KuisionerPdfData $kuisionerPdfData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\KuisionerPdfData  $kuisionerPdfData
     * @return \Illuminate\Http\Response
     */
    public function destroy(KuisionerPdfData $kuisionerPdfData)
    {
        //
    }
}
