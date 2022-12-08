<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class feedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function show(){
        if(Auth::user()->role == 'Admin'){
            $feedback = DB::table('feedbacks')->orderBy('id')->get();
            DB::table('feedbacks')->update([
                'status'=>'seen',
            ]);
            return view('feedback.feedback', ['feedbacks'=>$feedback]);
        }
        else {
            return redirect('/403');
        }
    }

    public function send(Request $request)
    {
        if(Auth::user()->role != 'Admin'){
            $return_code=0;
            $return_code = DB::table('feedbacks')->insertOrIgnore([
                'profile'=>Auth::user()->profile,
                'user'=>Auth::user()->username,
                'message'=>$request->message,
                'status'=>'new',
            ]);
            if ($return_code == 1) {
                return redirect('/')->with('success', "Feedback sent successfully. Thank You!");
            }
            else{
                return redirect('/')->with('danger', 'Unable to send feedback!');
            }
        }
        else {
            return redirect('/403');
        }
    }

    public function profile($user)
    {
        $profile = DB::table('users')->where('username', $user)->first();
        return $profile->profile;
    }

    function newFeedback(){
        if(Auth::user()->role == 'Admin'){
            $countF = DB::table('feedbacks')->where('status', 'new')->count();
            return response()->json(['count'=>$countF], 200);
        }
        else {
            return redirect('/403');
        }
    }
}
