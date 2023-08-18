<?php

namespace App\Http\Controllers;
use App\Models\Collection as P;
use App\Models\Mannequin;
use App\Models\Category;
use App\Models\Company;
use App\Models\Type;
use App\Models\User;
use App\Models\AuditTrail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Str;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

use DataTables;

class CollectionController extends Controller
{
    use WithPagination;

    // FILTER FOR USERS
    public function __construct()
    {
        // Define gates here
        Gate::define('add-product', function ($user) {
            return in_array($user->status, [1, 2]);
        });
        Gate::define('edit-product', function ($user) {
            return in_array($user->status, [1, 2]);
        });
        Gate::define('edit-delete', function ($user) {
            return in_array($user->status, [1]);
        });
    }

    public function index()
    {
        $user = Auth::user(); // Assuming you are using the built-in Auth facade
        // dd($user->status);
        if (!$user) {
            // User is not authenticated, redirect to login page
            return redirect()->route('login');
        }
        $categories = Category::all();
        // $companies = Company::all(); // Fetch all companies

        if ($user->status == 1) {
            $mannequins = Mannequin::all(); // Super users see all data
            $companies = Company::all();
        } else {
            $mannequins = Mannequin::whereIn('company', $user->companies->pluck('name'))->get();
            $companies = $user->companies;
        }

        return view('collection')->with([
            'categories' => $categories,
            'mannequins' => $mannequins,
            'companies' => $companies,
        ]);
    }


    //addedBy
    private function setActionBy($model, $action)
    {
        if (Auth::check()) {
            $user = Auth::user()->name;
            $time = Carbon::now()->format('m/d/y - g:i A');

            $newHistory = "$action by $user at $time";

            $model->addedBy = $newHistory;
        }
    }

        // public function sanitizeAndValidateDescription($description)
        // {
        //     // Remove any potentially dangerous HTML/JS tags
        //     $sanitizedDescription = strip_tags($description);

        //     // Trim any extra spaces
        //     $sanitizedDescription = trim($sanitizedDescription);

        //     // Ensure the description length is within a reasonable limit
        //     $sanitizedDescription = Str::limit($sanitizedDescription, 1000); // Adjust the limit as needed

        // }

    // VIEW MODULE FOR ADD PRODUCT
    public function add()
    {
        $user = Auth::user(); // Assuming you are using the built-in Auth facade
        $types = Type::all();
        $categories = Category::all();
        if ($user->status == 1) {
            $companies = Company::all();
        } else {
            $companies= Mannequin::whereIn('company', $user->companies->pluck('name'))->get();
            $companies = $user->companies;
        }
        return view('collection-add')->with(['categories' => $categories, 'types' => $types, 'companies' => $companies]);
    }

    //View selected product for editing
    public function edit($id)
    {
        $user = Auth::user();
        $categories = Category::all();
        if ($user->status == 1) {
            $companies = Company::all();
        } else {
            $companies= Mannequin::whereIn('company', $user->companies->pluck('name'))->get();
            $companies = $user->companies;
        }
        $types = Type::all();
        $mannequin = Mannequin::find($id);
        if (!$mannequin) {
            return redirect()->route('collection')->with('danger_message', 'Mannequin not found.');
        }

        // Return the view with the mannequin data
        // return view('collection-edit')->with('mannequin', $mannequin);
        return view('collection-edit')->with([
            'categories' => $categories,
            'mannequin' => $mannequin,
            'types' => $types,
            'companies' => $companies,
        ]);
    }

    // View selected Product

    public function view($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
        } catch (DecryptException $e) {
            return redirect()->route('collection')->with('danger_message', 'Invalid URL.');
        }

        $mannequin = Mannequin::find($id);
        if (!$mannequin) {
            return redirect()->route('collection')->with('danger_message', 'Mannequin not found.');
        }

        // Check if the user's status is 1 and get the checkPrice value for the user's company
        $user = Auth::user();
        $canViewPrice = $user->status == 1 || $user->companies()->where('companies.id', $mannequin->company_id)->whereNotNull('company_user.checkPrice')->exists();//TO BE CHANGED

        // Return the view with the mannequin data, $id, and $canViewPrice
        return view('collection-view', [
            'mannequin' => $mannequin,
            'encryptedId' => $encryptedId,
            'canViewPrice' => $canViewPrice,
        ]);
    }
    // ADD PRODUCT
    public function store(Request $request)
    {
        $photoPaths = [];
        // Check if there are uploaded files
        if ($request->hasFile('images')) {
            $photos = $request->file('images');

            foreach ($photos as $photo) {
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $photo->storeAs('public/images/product/', $photoName);

                // Update the $photoPaths array with the path to each uploaded photo
                $photoPaths[] = 'images/product/' . $photoName;
            }
        }
         // File upload logic for Excel file ('file')
        if ($request->hasFile('file')) {
            $excelFile = $request->file('file');
            $excelFileName = time() . '_' . $excelFile->getClientOriginalName();
            $excelFile->storeAs('public/files/excel/', $excelFileName);
        }

        // File upload logic for PDF file ('pdf')
        if ($request->hasFile('pdf')) {
            $pdfFile = $request->file('pdf');
            $pdfFileName = time() . '_' . $pdfFile->getClientOriginalName();
            $pdfFile->storeAs('public/files/pdf/', $pdfFileName);
        }

        // Create a new Mannequin instance and set its properties
        $mannequin = new Mannequin();
        $mannequin->po = strtoupper($request->po);
        $mannequin->itemref = strtoupper($request->itemRef);
        $mannequin->company = strtoupper($request->company);
        $mannequin->category = strtoupper($request->category);
        $mannequin->type = strtoupper($request->type);
        $mannequin->price = ($request->price);
        $mannequin->description = ($request->description);
        $this->setActionBy($mannequin, 'Added');
        $mannequin->activeStatus = "1";

        // Set the photo paths if there are uploaded photos
        if (!empty($photoPaths)) {
            $mannequin->images = implode(',', $photoPaths); // Save multiple image paths as a comma-separated string
        }

        // $mannequin->file = ($request->file);//excel file costing
        // $mannequin->pdf = ($request->pdf);

        // Set the 'file' property if an Excel file was uploaded
        if (isset($excelFileName)) {
            $mannequin->file = 'files/excel/' . $excelFileName;
        }

        // Set the 'pdf' property if a PDF file was uploaded
        if (isset($pdfFileName)) {
            $mannequin->pdf = 'files/pdf/' . $pdfFileName;
        }
        //3D file tbd

        // Save the data to the database
        if ($mannequin->save()) {
            return redirect('/collection')->with('success_message', 'Collection has been successfully added!');
        } else {
            return redirect('/collection')->with('danger_message', 'DATABASE ERROR!');
        }
    }

    //EDIT Product
    public function update(Request $request, $id)
    {
        // Find the Mannequin by ID or throw a 404 error if not found
        $mannequin = Mannequin::findOrFail($id);

        // Keep the original itemref for audit trail
        // $originalItemref = $mannequin->itemref;

        // Update the fields using the request input directly
        $mannequin->fill([
            'po' => $request->input('po'),
            'itemref' => $request->input('itemref'),
            'company' => $request->input('company'),
            'category' => $request->input('category'),
            'type' => $request->input('type'),
            'price' => $request->input('price'),
            'description' => $request->input('description'),
            $file = $request->file('file'),
        ]);

        // Update images
        if ($request->hasFile('images')) {
            // Remove the old images if they exist
            foreach (explode(',', $mannequin->images) as $oldImagePath) {
                // Delete the old image from storage
                Storage::delete('public/product/' . trim($oldImagePath));
            }

            $imagePaths = [];

            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/images/product', $imageName);
                $imagePaths[] = 'images/product/' . $imageName;
            }

            // Update the images field in the database
            $mannequin->images = implode(',', $imagePaths);
        }

        //FOR COSTING
        if ($request->hasFile('file')) {
            // Remove the old file if it exists
            if ($mannequin->file) {
                // Delete the old file from storage
                Storage::delete('public/files/' . $mannequin->file);
            }

            $file = $request->file('file');
            $filename = $file->getClientOriginalName();

            // Store the file in the desired storage location (public disk in this case)
            $file->storeAs('public/files', $filename);

            // Update the file field in the database
            $mannequin->file = $filename;
        }
        //FOR PDF
        if ($request->hasFile('pdf')) {
            // Remove the old PDF if it exists
            if ($mannequin->pdf) {
                // Delete the old PDF from storage
                Storage::delete('public/files/' . $mannequin->pdf);
            }

            $pdfFile = $request->file('pdf');
            $pdfFilename = $pdfFile->getClientOriginalName();

            // Store the PDF file in the desired storage location (public disk in this case)
            $pdfFile->storeAs('public/files', $pdfFilename);

            // Update the pdf field in the database
            $mannequin->pdf = $pdfFilename;
        }

        $this->setActionBy($mannequin, 'Modified');

        $mannequin->save();

        // Log the audit trail entry for the 'update' activity
        // $activity = "Updated $originalItemref"; // Concatenate the activity
        // $this->logAuditTrail(auth()->user(), $activity, $originalItemref);

        return redirect()->route('collection', $mannequin->id)->with('success_message', 'Product details updated successfully.');
    }

    //DELETE(to trashcan make active status = 0)
    public function trash($id)
    {
        $mannequin = Mannequin::find($id);
        if ($mannequin) {
            $mannequin->activeStatus = 0;
            $this->setActionBy($mannequin, 'Deleted');
            $mannequin->save();


            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    //Delete (PERMANENTLY from database to storage)
    public function destroy($id)
    {
        $mannequin = Mannequin::findOrFail($id);

        // Delete associated images
        foreach (explode(',', $mannequin->images) as $imagePath) {
            Storage::delete('public/' . trim($imagePath));
        }

        // Delete the Mannequin record from the database
        $mannequin->delete();

        // Retrieve the updated $mannequins collection after deletion
        $mannequins = Mannequin::where('activeStatus', '<', 1)->get();

        return redirect()->back()->with('success_message', 'Item deleted permanently.');
    }

    //SHOW TRASHCAN
    public function trashcan()
    {
        $mannequins = Mannequin::where('activeStatus', '<', 1)->get();
        return view('collection-trash')->with(['mannequins' => $mannequins,]);;
    }

    //RESTORE
    public function restore($id)
    {
        $mannequin = Mannequin::findOrFail($id);
        // Check if the item is actually deleted (activeStatus = 0)
        if ($mannequin->activeStatus == 0) {
            $this->setActionBy($mannequin, 'Restored');
            $mannequin->update(['activeStatus' => 1]);
            return redirect()->route('collection')->with('success_message', 'Item restored successfully.');
        } else {
            return redirect()->route('collection')->with('danger_message', 'Item is not deleted.');
        }
        return redirect()->back()->with('error', 'An error occurred while restoring the item.');
    }

    //SHOW CATEGORIES MODULE
    public function category()
    {
        $categories = Category::all();
        return view('collection-category')->with(['categories' => $categories]);
    }

    // SHOW TYPE MODULE
    public function type()
    {
        $types = Type::all();
        return view('collection-type')->with(['types' => $types]);
    }

    // ADD CATEGORY
    public function store_category(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'category' => 'required|unique:categories,name'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('danger_message', 'Duplicate/No input');
        }

        $category = new Category();
        $category->name = strtoupper($request->category);


        // Automatically set the 'addedBy' field with the authenticated user's name
        if (Auth::check()) {
            $user = Auth::user()->name;
            $time = Carbon::now()->format('m/d/y - g:i A');
            $category->addedBy = "$user at $time";
        }

        $category->save();


        return redirect()->route('collection.category')->with('success_message', 'Category added successfully!');
    }

    // ADD TYPE
    public function store_type(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'type' => 'required|unique:categories,name'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('danger_message', 'Duplicate/No input');
        }

        $type = new type();
        $type->name = strtoupper($request->type);


        // Automatically set the 'addedBy' field with the authenticated user's name
        if (Auth::check()) {
            $user = Auth::user()->name;
            $time = Carbon::now()->format('m/d/y - g:i A');
            $type->addedBy = "$user at $time";
        }

        $type->save();


        return redirect()->route('collection.type')->with('success_message', 'type added successfully!');
    }

    // public function logAuditTrail($user, $activity)
    // {
    //     $log = new AuditTrail;
    //     $log->user_id = $user->id;
    //     $log->activity = $activity;
    //     $log->save();
    // }
}

