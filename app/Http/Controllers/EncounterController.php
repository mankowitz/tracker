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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Encounter/Index', [
            'users' => User::all(),
            'encounters' => Encounter::with('patient')->where('is_complete', 0)->get(),
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

        $encounter = Encounter::find($request->encounter_id) ?? new Encounter;
        $patient = Patient::find($request->patient_id) ?? new Patient;

        $histories = [
            "pmh" => $request->pmh,
            "psh" => $request->psh,
            "meds" => $request->meds,
            "all" => $request->all,
            "sh" => $request->sh,
            "fh" => $request->fh,
        ];

        $patient->fill($request->only(['sex','mrn','lastname','firstname']));
        $patient->dob = (new Carbon($patient->dob))->format("Y-m-d");
        $patient->histories = json_encode($histories);
        $patient->save();

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
        if (\in_array($request->field_name, ['comments','is_complete','provider_id','chief_complaint'])) {
            $encounter->update([$request->field_name => $request->new_value]);
        }
        return Redirect::route('encounter.index');
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
