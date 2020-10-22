<?php
  
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function index()
    {
        $data = User::all();
        return Inertia::render('User/Index', ['data' => $data]);
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required'],
        ])->validate();

        $request->merge(["password"=>"TEMP"]);  
        User::create($request->only(['name','email','password']));
  
        return redirect()->back()->with('message', 'User Created Successfully.');
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function update(Request $request)
    {
        Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required'],
        ])->validate();
  
        if ($request->has('id')) {
            User::find($request->input('id'))->update($request->only(['name','email','password']));
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
