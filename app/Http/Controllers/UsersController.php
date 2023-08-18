<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();
        $companies = Company::all();
        return view('user')->with(['users' => $users, 'companies' => $companies]);
    }

    //addedBy
    private function getAddedByInfo()
    {
        if (Auth::check()) {
            $user = Auth::user()->name;
            $time = Carbon::now()->format('m/d/y - g:i A');

            return "Added by $user at $time";
        } else {
            return null; // Return null if not authenticated
        }
    }

    public function store(Request $request): RedirectResponse
    {
        // dd($request);
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => $request->status,
            'addedBy' => $this->getAddedByInfo(), // Call a method to generate the 'addedBy' value
        ]);

        // Attach selected companies to the user
        $user->companies()->attach($request->input('company_ids'));

        // If you want to set a specific value (e.g., 1) for each selected company in the second list
        $user->companies()->updateExistingPivot($request->input('selected_company_ids'), ['checkPrice' => 1]);

        // Redirect or do something else after successful registration
        return redirect()->route('collection')->with('success_message', 'User created successfully.');
    }
}
