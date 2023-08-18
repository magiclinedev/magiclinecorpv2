<?php

namespace App\Http\Controllers;
use App\Models\AuditTrail;
use App\Models\Users;

use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    public function index()
    {
        $audits = AuditTrail::with('user')->get();
        return view('audit-trail')->with(['audits' => $audits,]);
    }
}
