<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AppLicense;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function index()
    {
        // Get latest license (you can change logic later if needed)
        $license = AppLicense::latest()->first();

        return view('product_key.index', compact('license'));
    }
}
