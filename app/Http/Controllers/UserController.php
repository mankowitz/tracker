<?php
  
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $validationRules = [
        'name' => ['required'],
        'email' => ['required'],
        'user_type_id' => ['required', 'min:1'],
    ];

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function index()
    {
        return Inertia::render('User/Index', [
            'users' => User::with('userType')->get(),
            'userTypes' => UserType::all()
        ]);
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), $this->validationRules)->validate();

        $request->merge(["password"=>"TEMP"]);
        User::create($request->only(['name','email','password','user_type_id']));
  
        return redirect()->back()->with('message', 'User Created Successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Encounter  $encounter
     * @return \Illuminate\Http\Response
     */
    public function hotUpdate(Request $request, Encounter $encounter)
    {
        $encounter->update([$request->field_name => $request->new_value]);
        return Redirect::route('encounter.index');
    }

    /**
     * Update a whole user
     *
     * @return Response
     */
    public function update(Request $request)
    {
        Validator::make($request->all(), $this->validationRules)->validate();
  
        if ($request->has('id')) {
            User::find($request->input('id'))->update($request->only(['name','email','user_type_id']));
            return redirect()->back()->with('message', 'User Updated Successfully.');
        }
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function destroy(Request $request)
    {
        if ($request->has('id')) {
            user::find($request->input('id'))->delete();
            return redirect()->back();
        }
    }
}
