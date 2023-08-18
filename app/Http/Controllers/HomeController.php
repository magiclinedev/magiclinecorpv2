<?php

namespace App\Http\Controllers;
use App\Models\Mannequin;

use Illuminate\Http\Request;

use DataTables;
use Illuminate\Support\Facades\Crypt;
class HomeController extends Controller
{
    public function collection()
    {
 
        $data = Mannequin::select('*');

        return DataTables::of($data)
        ->make(true);

        return view('collection');

    }

}
