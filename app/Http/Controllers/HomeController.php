<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return redirect('/');
    }

    public function welcome()
    {
        $movies = DB::table('movies')->paginate(30);
        $movie_count = DB::table('movies')->count();
        $most_likes = DB::table('movies')->where('likes', '>=', '1')->orderBy('likes','DESC')->paginate(6);

        $users = DB::table('users')->count();
        $films = DB::table('movies')->where('catagory', 'film')->count();
        $theatres = DB::table('movies')->where('catagory', 'theatre')->count();
        $shows = DB::table('shows')->distinct()->count();
        $feedbacks = DB::table('feedbacks')->orderBy('id')->get();
        $Tickets = DB::table('tickets')->orderBy('id')->get();

        return view('welcome', ['movies'=>$movies,
                    'most_likes'=>$most_likes,
                    'movie_count'=>$movie_count,
                    'users' => $users,
                    'films' => $films,
                    'theatres' => $theatres,
                    'shows' => $shows,
                    'feedbacks' => $feedbacks,
                    'Tickets' => $Tickets,
                    ]);
    }

    public function report(Request $request)
    {
        $Tcount = DB::table('tickets')->where('created_at', '>=', $request->first_date)
                                        ->where('created_at', '<=', $request->last_date)->count();

        $tickets = DB::table('tickets')->where('created_at', '>=', $request->first_date)
                                        ->where('created_at', '<=', $request->last_date)->get();

        $users = DB::table('users')->orderBy('username')->get();
        $movies = DB::table('movies')->orderBy('title')->get();


        return view('report', ['Tickets'=>$tickets, 'users'=>$users, 'movies'=>$movies, 'Tcount'=>$Tcount, 'from'=>$request->first_date, 'to'=>$request->last_date]);
    }

    public function reportRequest()
    {
        return view('reportR');
    }
}
