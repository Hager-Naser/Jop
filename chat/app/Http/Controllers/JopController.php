<?php

namespace App\Http\Controllers;

use App\Http\Requests\JopUploadRequest;
use App\Models\Category;
use App\Models\JobTypes;
use Illuminate\Http\Request;

class JopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('website.jobs.all-jobs');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('status' , 1)->get();
        $jobTypes = JobTypes::where('status' , 1)->get();
        return view('website.jobs.upload-job' , compact('categories' ,'jobTypes' ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JopUploadRequest $request)
    {

        $data = $request->except('_token');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
