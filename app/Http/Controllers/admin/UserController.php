<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;




class UserController extends Controller
{
    public function index(Request $request)
    {
       $users = User::latest();
       if(!empty($request->get('keyword'))){
        $users = $users->where('name','like','%'.$request->get('keyword').'%');
        $users = $users->orwhere('email','like','%'.$request->get('keyword').'%');
        $users = $users->orwhere('role','like','%'.$request->get('keyword').'%');
      }
      $users = $users->paginate(10);
      return view('admin.users.list',[
        'users' => $users,
      ]);
    }

    public function create(Request $request)
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:5',
            'phone' => 'required',


        ]);

        if ($validator->passes())
        {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->status = $request->status;
            $user->save();

            $request->session()->flash('success','User added successfully');

              return response()->json([
                'status' => true,
                'message' => 'User added successfully'
             ]);
        }else{

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
             ]);

        }
           
    }


    public function edit($id, Request $request)
    {
        $user = User::find($id);

        if(empty($user)){
          return redirect()->route('users.index');
        }
       return view('admin.users.edit',compact('user'));
    }


    public function update($id, Request $request)
    {
        $user = User::find($id);

        if(empty($user)){
            $request->session()->flash('error','User not found');

            return redirect()->json([
            'status' => false,
            'NotFound' => true,
            'message' => 'User Not Found'
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->passes())
        {

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->status = $request->status;
            $user->save();

            $request->session()->flash('success','User Updated successfully');

              return response()->json([
                'status' => true,
                'message' => 'User Updated successfully'
             ]);
        }else{

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
             ]);

        }

    }

    public function destroy($id, Request $request)
    {
        $user = User::find($id);

        if(empty($user)){
            $request->session()->flash('error','User not found');

            return redirect()->json([
            'status' => false,
            'NotFound' => true,
            'message' => 'User Not Found'
            ]);
        }

        $user->delete();
        
        $request->session()->flash('success','User deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully'
        ]);

    }
}
