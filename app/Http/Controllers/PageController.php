<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use App\Models\Faq;

class PageController extends Controller
{
    public function gallery()
    {
        $images = GalleryImage::orderBy('sort_order')->paginate(12);

        return view('pages.gallery.index', compact('images'));
    }

    public function faq()
    {
        $faqs = Faq::where('is_published', true)->orderBy('sort_order')->get();

        return view('pages.faq', compact('faqs'));
    }

    public function team()
    {
        return view('pages.team');
    }

    public function search()
    {
        return view('pages.search');
    }

    public function newsletterSubscribe(\Illuminate\Http\Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // TODO: store subscriber

        return redirect()->back()->with('success', 'Subscribed successfully!');
    }
}
