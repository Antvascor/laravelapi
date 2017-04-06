<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JobsController extends Controller
{
    public function index()
    {
        $jobs = Job::with('company')->get();
        return response()->json($jobs);
    }
    public function show($id)
    {
        $job = Job::with('company')->find($id);

        if(!$job) {
            return response()->json([
                'message'   => 'Record not found',
            ], 404);
        }

        return response()->json($job);
    }
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'title' => 'required|max:255',
            'description' => 'required',
            'local' => 'required',
            'remote' => 'in:yes,no',
            'type' => 'integer',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message'   => 'Validation Failed',
                'errors'    => $validator->errors()->all()
            ], 422);
        }

        $job = new Job();
        $job->fill($data);
        $job->company_id = \Auth::user()->id;
        $job->save();

        return response()->json($job, 201);
    }
    public function update(Request $request, $id)
    {
        $job = Job::find($id);

        if(!$job) {
            return response()->json([
                'message'   => 'Record not found',
            ], 404);
        }

        if(\Auth::user()->id != $job->company_id) {
            return response()->json([
                'message'   => 'You haven\'t permission to change this entry',
            ], 401);
        }

        $validator = Validator::make($data, [
            'title' => 'max:255',
            'remote' => 'in:yes,no',
            'type' => 'integer',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message'   => 'Validation Failed',
                'errors'    => $validator->errors()->all()
            ], 422);
        }

        $job->fill($request->all());
        $job->save();

        return response()->json($job);
    }
    public function destroy($id)
    {
        $job = Job::find($id);

        if(!$job) {
            return response()->json([
                'message'   => 'Record not found',
            ], 404);
        }

        if(\Auth::user()->id != $job->company_id) {
            return response()->json([
                'message'   => 'You haven\'t permission to delete this entry',
            ], 401);
        }

        $job->delete();
    }
}
