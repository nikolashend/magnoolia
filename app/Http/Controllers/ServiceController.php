<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('is_published', true)->get();

        return view('pages.services.index', compact('services'));
    }

    public function show(string $slug)
    {
        $service = Service::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('pages.services.show', compact('service'));
    }
}
