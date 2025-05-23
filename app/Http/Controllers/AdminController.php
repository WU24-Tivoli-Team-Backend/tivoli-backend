<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Helpers\VictoryPoints;
use Illuminate\Support\Facades\Hash;
use App\Models\Group;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    public function index()
    {
        if (!Session::has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $users = User::where('email', '!=', 'rune@yrgobanken.vip',)->orderByDesc('balance')->get();

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
            $stampNames = $stamps->pluck('stamp_name')->toArray();
            return (object) [
                'user_id' => $userId,
                'user_name' => $stamps->first()->user_name,
                'stamps' => $stampNames,
                'stamp_count' => count($stampNames),
                'victory_points' => VictoryPoints::calculate($stampNames),
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
        User::where('email', '!=', 'rune@yrgobanken.vip', 'hans.2.andersson@educ.goteborg.se')
            ->update(['balance' => 25.00]);

        return back()->with('success', 'All user balances (except designated test users) have been reset to €25');
    }

    public function updateRuneBalance(Request $request)
    {
        if (!Session::has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'rune_balance' => 'required|numeric|min:0',
            'hans_balance' => 'nullable|numeric|min:0',
        ]);

        $rune = User::where('email', 'rune@yrgobanken.vip')->first();
        if ($rune && $request->filled('rune_balance')) {
            $rune->balance = $request->input('rune_balance');
            $rune->save();
            $messages[] = "Rune's balance updated.";
        }

        $hans = User::where('email', 'hans.2.andersson@educ.goteborg.se')->first();
        if ($hans && $request->filled('hans_balance')) {
            $hans->balance = $request->input('hans_balance');
            $hans->save();
            $messages[] = "Hans's balance updated.";
        }

        if (count($messages)) {
            return redirect()->back()->with('success', implode(' ', $messages));
        }

        return redirect()->back()->with('error', 'No updates made. Make sure at least one user exists and a balance was entered.');
    }

    public function resetVotes()
    {
        DB::table('votes')->truncate();
        return back()->with('success', 'All votes have been reset');
    }

    public function showUsers()
    {
        if (!Session::has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $users = User::orderBy('name')->get();
        
        return view('admin.users.index', [
            'users' => $users,
        ]);
    }

    public function createUser()
    {
        if (!Session::has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $groups = Group::all();
        
        return view('admin.users.create', [
            'groups' => $groups,
        ]);
    }

    public function storeUser(Request $request)
    {
        if (!Session::has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'group_id' => ['required', 'integer', 'exists:groups,id'],
            'balance' => ['required', 'numeric', 'min:0'],
            'image_url' => ['nullable', 'url'],
            'github' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'url'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'group_id' => $request->group_id,
            'balance' => $request->balance,
            'image_url' => $request->image_url,
            'github' => $request->github,
            'url' => $request->url,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    public function editUser(User $user)
    {
        if (!Session::has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $groups = Group::all();
        
        return view('admin.users.edit', [
            'user' => $user,
            'groups' => $groups,
        ]);
    }

    public function updateUser(Request $request, User $user)
    {
        if (!Session::has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'group_id' => ['required', 'integer', 'exists:groups,id'],
            'balance' => ['required', 'numeric', 'min:0'],
            'image_url' => ['nullable', 'url'],
            'github' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'url'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        }

        $validated = $request->validate($rules);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }

    public function deleteUser(User $user)
    {
        if (!Session::has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        if (in_array($user->email, ['rune@yrgobanken.vip', 'hans.2.andersson@educ.goteborg.se'])) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete special user accounts');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');


    public function resetStamps()
    {
        DB::table('user_stamps')->truncate();
        return back()->with('success', 'All stamps have been reset');
    }
}
