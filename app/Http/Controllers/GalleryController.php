<?php

namespace App\Http\Controllers;

use App\Models\Template;

class GalleryController extends Controller
{
    public function index()
    {
        $templates = Template::where('is_active', true)->get();
        return view('home', compact('templates'));
    }
}
