<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        if (!Session::has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        $users = User::where('email', '!=', 'rune@yrgobanken.vip')->get();

        $amusementVotes = DB::table('amusements')
        ->leftJoin('votes', 'amusements.id', '=', 'votes.amusement_id')
        ->select('amusements.id', 'amusements.name', DB::raw('COUNT(votes.id) as vote_count'))
        ->groupBy('amusements.id', 'amusements.name')
        ->get();

        return view('admin.dashboard', [
        'users' => $users,
        'amusementVotes' => $amusementVotes,
        ]);
    }
    
    public function showLogin()
    {
        if (Session::has('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'api_key' => 'required',
        ]);
        
        if ($request->api_key === env('GROUP1_API_KEY')) {
            Session::put('admin_logged_in', true);
            
            return redirect()->route('admin.dashboard')->with('success', 'Logged in successfully');
        }
        
        return back()->withErrors(['api_key' => 'Invalid Password']);
    }
    
    public function logout()
    {
        Session::forget('admin_logged_in');
        
        return redirect()->route('admin.login')->with('success', 'Logged out successfully');
    }
    
    public function resetBalances()
    {
        User::where('email', '!=', 'rune@yrgobanken.vip')
            ->update(['balance' => 25.00]);
            
        return back()->with('success', 'All user balances (except designated test users) have been reset to €25');
    }

    public function resetVotes()
    {
        DB::table('votes')->truncate();
        return back()->with('success', 'All votes have been reset');
    }
}