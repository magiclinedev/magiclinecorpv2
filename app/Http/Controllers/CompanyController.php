<?php

namespace App\Http\Controllers;
use App\Models\Company;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class CompanyController extends Controller
{
    public function index()
    {
        $company = Company::all();
        return view('company')->with(['company' => $company]);
    }

    public function company(Request $request)
    {
        $photoPath = null;
    
        if ($request->hasFile('images')) {
            $photo = $request->file('images');
            $photoName = time() . '.' . $photo->getClientOriginalExtension();
            $photo->storeAs('public/images', $photoName);
    
            // Update the $photoPath with the path to the uploaded photo
            $photoPath = 'images/' . $photoName;
        }
    
        $validator = \Validator::make($request->all(), [
            'company' => 'required|unique:categories,name'
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $company = new Company();
        $company->name = strtoupper($request->company);
    
        // Set the 'images' field to NULL if no image is uploaded
        $company->images = $photoPath;
    
        // Automatically set the 'addedBy' field with the authenticated user's name
        if (Auth::check()) {
            $user = Auth::user()->name;
            $time = Carbon::now()->format('m/d/y - g:i A');
            $company->addedBy = "$user at $time";
        }
    
        $company->save();
    
        return redirect()->route('company')->with('success_message', 'Company added successfully!');
    }
    
}
