<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        // $campaigns = Campaign::with('productCategory')->paginate(2);
        $products = [];
        return view('product.index', compact('products'));
    }
}