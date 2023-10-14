<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use DateTime;

class ticketController extends Controller
{
    function show(){
        $tickets = DB::table('tickets')->orderBy('id')->get();
        $users = DB::table('users')->orderBy('username')->get();
        $movies = DB::table('movies')->orderBy('title')->get();
        return view('ticket.ticket', ['Tickets'=>$tickets, 'users'=>$users, 'movies'=>$movies]);
    }

    function showMine(){
        $tickets = DB::table('tickets')->where('user', Auth::user()->username)->orderBy('id')->get();
        $users = DB::table('users')->orderBy('username')->get();
        $movies = DB::table('movies')->orderBy('title')->get();
        return view('ticket.ticket', ['Tickets'=>$tickets, 'users'=>$users, 'movies'=>$movies]);
    }

    public function createTicket(Request $request)
    {
        if(Auth::user()->role == 'Ticket Controller'){
            $return_code=0;
            $return_code = DB::table('tickets')->insertOrIgnore([
                'user'=>$request->user,
                'coupon'=>$request->coupon,
            ]);
            if ($return_code == 1) {
                $previous = DB::table('users')->where('username', $request->user)->first();
                $new = $previous->account + $request->coupon;
                $now = DB::table('users')->where('username', $request->user)->update(['account' => $new]);

                return redirect('/ticket')->with('success', "User $request->user successfully bought $request->coupon ammount coupon!");
            }
            else{
                return redirect('/ticket')->with('danger', 'Unable to bought the coupon now! Try again later please.');
        }
    }
        else {
            return redirect('/403');
        }
    }

    public function updateTicket(Request $request, $coupon_id)
    {
        if(Auth::user()->role == 'Ticket Controller'){
            $coupon = DB::table('coupons')->where('id', $coupon_id)->first();
            $return_code=0;
            $return_code = DB::table('coupons')->where('id', $coupon_id)->update([
                'user'=>$request->user,
                'coupon'=>$request->coupon,
            ]);
            if ($return_code == 1) {
                $previous = DB::table('users')->where('username', $request->user)->first();
                if ($request->coupon >= $coupon->coupon) {
                    $new = $previous->account + ($request->coupon - $coupon->coupon);
                } else {
                    $new = $previous->account - ($coupon->coupon - $request->coupon);
                }

                $now = DB::table('users')->where('username', $request->user)->update(['account'=>$new]);

                return redirect('/ticket')->with('success', "User $request->user's coupon is successfully updated from $coupon->coupon to $request->coupon!");
            }
            else{
                return redirect('/ticket')->with('danger', 'Unable to bought the coupon now! Try again later please.');
        }
    }
        else {
            return redirect('/403');
        }
    }

    function deleteTicket($id){

        if (Auth::user()->role =='Ticket Controller') {
            $return_code=0;
            $return_code = DB::table('tickets')->where('id', $id)->delete();
            if ($return_code == 1) {
                return redirect('/ticket')->with('success', 'ticket information successfully Deleted!');
            }
            else{
                return redirect('/ticket')->with('danger', 'Unable to Delete the ticket information!');
            }
        }
        else {
            return redirect('/403');
        }
    }

    public static function tickets($id)
    {
        $tickets = DB::table('tickets')->where('movie', $id)->where('user', Auth::user()->username)->count();
        if ($tickets == 0) {
            return false;
        } else {
            return true;
        }
    }

    public static function ticketid($id)
    {
        $tkts = DB::table('tickets')->where('movie', $id)->where('user', Auth::user()->username)->count();
        $tickets = DB::table('tickets')->where('movie', $id)->where('user', Auth::user()->username)->get();
        $id = 0;

        $count1 = 0;
        foreach ($tickets as $tt) {
            if ($tkts == $count1 + 1) {
                $id = $tt->id;
                break;
            }
            $count1++;
        }
        return $id;
    }

    public static function occupied($movie, $z)
    {
        $seat = DB::table('seats')->where('movie', $movie)
                                     ->where('seat', $z)->count();
        if ($seat == 0) {
            return false;
        } else {
            return true;
        }
    }


    public static function ticketTime($movie)
    {
        $ticket = DB::table('shows')->where('movie', $movie)->orderBy('id', 'DESC')->get();
        foreach ($ticket as $t) {
            $schedule = $t->show_date.' '.$t->show_time;
            $currentDate = date('Y-m-d H:i:s');
            if (strtotime($currentDate) < strtotime($schedule)) {
                return true;
            } else {
                return false;
            }
            break;
        }
    }

    public static function chairs($movie, $type)
    {
        $chairs = DB::table('tickets')->where('movie', $movie)->where('type', $type)->count();
        if ($chairs == 0) {
            return false;
        } else {
            return true;
        }
    }

    // public function seats($input){
    //     $movies = DB::table('movies')->where('id', $input)->get();
    //     return view('movie.seats', ['movies'=>$movies]);
    // }

    public function buyTicket($movie, Request $request){
        return view("accessX");
    }

    public function cancelTicket($movie, $page){
        return view("accessX");
    }

    function searchTicket($input){
        $movies = DB::table('movies')->get();
        $movsC = DB::table('movies')->where('title', 'Like', '%'.$input.'%')->count();
        $movs = DB::table('movies')->where('title', 'Like', '%'.$input.'%')->orderBy('id')->first();
        $movieTitle = "0";
        if ($movsC > 0) {
            $movieTitle = $movs->id;
        }
        $Ucount = DB::table('tickets')->where('id', 'Like', '%'.$input.'%')->orWhere('user', 'Like', '%'.$input.'%')->orWhere('movie', 'Like', '%'.$movieTitle.'%')->count();
        $Tickets = DB::table('tickets')->where('id', 'Like', '%'.$input.'%')->orWhere('user', 'Like', '%'.$input.'%')->orWhere('movie', 'Like', '%'.$movieTitle.'%')->get();
        $output='';
        $no=0;

        foreach ($Tickets as $Ticket) {
            $no++;
            if(Auth::user()->role == "Ticket Controller" || Auth::user()->role == "Admin" || Auth::user()->username == $Ticket->user){
                $no++;
                $output .= '<tr style="height: 35px;">
                <th scope="row"><a href="#" data-bs-toggle="modal" data-bs-target="#ticket'.$Ticket->id.'">'.$Ticket->id.'</a></th>
                    <td><a href="/userProfile/'.$Ticket->id.'" style="text-decoration: none;"><img src="../image/userProfile/'.TicketController::profile1($Ticket->user).' style="width: 30px; height: 30px;" alt=""> '.$Ticket->user.' </a></td>';
                $mov ='';

                foreach ($movies as $movie){
                    if ($movie->id == $Ticket->movie){
                        $mov =$movie->title;
                    }
                }
                $output .= '<td >'. $mov .'</td>
                    <td>'. $Ticket->place_no .'</td>
                    <td>'. $Ticket->chairs .'</td>
                    <td>'. $Ticket->amount .'</td></tr>';
            }
        }

        return response()->json(['output'=>$output], 200);
    }

    public static function profile($user)
    {
        $profile = DB::table('users')->where('username', $user)->first();
        return $profile->profile;
    }

    public function profile1($user)
    {
        $profile = DB::table('users')->where('username', $user)->first();
        return $profile->profile;
    }

    public function ticketValue($selected)
    {
        $ticket_Value = DB::table('movies')->where('title', $selected)->first();
        $vip = $ticket_Value->vip;
        $normal =$ticket_Value->normal;
        return response()->json(['vip'=> $vip, 'normal'=>$normal], 200);
    }
}
