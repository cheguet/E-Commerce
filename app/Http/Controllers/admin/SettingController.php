<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class SettingController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('admin.change-password');
    }

    public function  savePasswordChange(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required|min:3|same:confirm_password',
            'confirm_password' => 'required',
        ]);

        if ($validator->passes())
        {
            $admin = User::where('id', Auth::guard('admin')->user()->id)->first();

            if(!Hash::check($request->old_password, $admin->password)){
                    
                session()->flash('error', 'Your old password is incorrect please try again');
                return response()->json([
                    'status' => true,
                ]);
            }

            User::where('id', Auth::guard('admin')->user()->id)->update([
            'password' => Hash::make($request->new_password),
            ]);

            session()->flash('success', 'Password Change');
            return response()->json([
                'status' => true,
            ]);


        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);

        }
    }
}
