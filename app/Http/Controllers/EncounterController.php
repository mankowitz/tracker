<?php

namespace App\Http\Controllers;

use App\Models\Encounter;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use \Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;

class EncounterController extends Controller
{
    /**
     * Display a listing of encounters.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $encounters = Encounter::with(['patient','provider:name,id','nurse:name,id','caseManager:name,id']);
        if ($request->is_complete==1) {
            if ($request->startDate && $request->endDate) {
                $encounters = $encounters->whereBetween('created_at', [
                    new Carbon($request->startDate),
                    new Carbon($request->endDate)
                ]);
            }
        }
        $encounters = $encounters->where('is_complete', $request->input('is_complete', 0));
        return Inertia::render('Encounter/Index', [
            'users' => User::with('userType')->get(),
            'encounters' => $encounters->get(),
            'params' => [
                'is_complete' => $request->input('is_complete', 0),
                'startDate' => $request->input('startDate', \Carbon\Carbon::now()->add(-2, "week")->format("m/d/Y")),
                'endDate' => $request->input('endDate', \Carbon\Carbon::now()->add(1, "day")->format("m/d/Y")),
            ]
        ]);
    }

    /**
     * Display a listing of encounters for print.
     *
     * @return \Illuminate\Http\Response
     */
    public function print()
    {
        return Inertia::render('Encounter/IndexPrint', [
            'encounters' => Encounter::with(['patient','provider:name,id','nurse:name,id','caseManager:name,id'])->where('is_complete', 0)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Encounter/EditModal', [
            'encounter'=>false,
            'create'=>true,
            'users' => User::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'dob' => 'date|before:today|nullable',
            'sex' => 'required|max:1',
            'mrn' => 'required',
            'lastname' => 'required|max:50',
            'firstname' => 'required|max:50',
            'chief_complaint' => 'required|max:50',
        ]);


        $histories = [
            "pmh" => $request->pmh,
            "psh" => $request->psh,
            "meds" => $request->meds,
            "all" => $request->all,
            "sh" => $request->sh,
            "fh" => $request->fh,
        ];

        $patient = Patient::firstOrNew(['id'=> $request->patient_id]);
        $patient->fill($request->only(['sex','mrn','lastname','firstname']));
        $patient->dob = new Carbon($request->dob);
        $patient->histories = json_encode($histories);
        $patient->save();

        $encounter = Encounter::firstOrNew(['id'=> $request->encounter_id]);
        $encounter->fill($request->only(['chief_complaint', 'provider_id', 'location', 'comments']));
        $encounter->patient_id = $patient->id;
        $encounter->save();

        return redirect('/encounter');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Encounter  $encounter
     * @return \Illuminate\Http\Response
     */
    public function show(Encounter $encounter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Encounter  $encounter
     * @return \Illuminate\Http\Response
     */
    public function edit(Encounter $encounter)
    {
        $encounter->load("patient");
        return Inertia::render('Encounter/Edit', [
            'encounter'=>$encounter,
            'users' => User::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Encounter  $encounter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Encounter $encounter)
    {
        $encounter->update([$request->field_name => $request->new_value]);
        return Redirect::route('encounter.index', $request->queryString);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Encounter  $encounter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Encounter $encounter)
    {
        $encounter->delete();
    }
}
