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
        
        $users = User::where('email', '!=', 'rune@yrgobanken.vip')->orderByDesc('balance')->get();

        $rune = User::where('email', 'rune@yrgobanken.vip')->first();

        $amusementVotes = DB::table('amusements')
        ->leftJoin('votes', 'amusements.id', '=', 'votes.amusement_id')
        ->select('amusements.id', 'amusements.name', DB::raw('COUNT(votes.id) as vote_count'))
        ->groupBy('amusements.id', 'amusements.name')
        ->orderByDesc('vote_count')
        ->get();

        return view('admin.dashboard', [
        'users' => $users,
        'rune' => $rune,
        'amusementVotes' => $amusementVotes,
        ]);
    }

    public function showUserStamps()
    {
        $userStamps = DB::table('user_stamps')
        ->leftJoin('users', 'user_stamps.user_id', '=', 'users.id')
        ->leftJoin('stamps', 'user_stamps.stamp_id', '=', 'stamps.id')
        ->select(
            'user_stamps.id', 
            'user_stamps.user_id',
            'users.name as user_name', 
            'stamps.id as stamp_id',
            DB::raw('CASE 
                WHEN stamps.premium_attribute IS NOT NULL 
                THEN CONCAT(stamps.premium_attribute, " ", stamps.animal)
                ELSE stamps.animal 
                END as stamp_name'),
            'user_stamps.created_at'
        )
        ->get()
        ->groupBy('user_id')
        ->map(function ($stamps, $userId) {
        return (object) [
            'user_id' => $userId,
            'user_name' => $stamps->first()->user_name,
            'stamps' => $stamps->pluck('stamp_name')->toArray(),
            'stamp_count' => $stamps->count()
        ];
    });

    return view('admin.vp', [
        'userStamps' => $userStamps,
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

    public function updateRuneBalance(Request $request)
    {
        if (!Session::has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'balance' => 'required|numeric|min:0',
        ]);

        $rune = User::where('email', 'rune@yrgobanken.vip')->first();

        if ($rune) {
            $rune->balance = $request->balance;
            $rune->save();
            return redirect()->back()->with('success', 'Rune\'s balance updated successfully.');
        }

        return redirect()->back()->with('error', 'Rune not found.');
    }

    public function resetVotes()
    {
        DB::table('votes')->truncate();
        return back()->with('success', 'All votes have been reset');
    }
}