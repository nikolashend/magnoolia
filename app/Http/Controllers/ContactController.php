<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('pages.contact');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        // TODO: Send email notification
        // Mail::to(config('contact.email', 'info@company.com'))
        //     ->send(new \App\Mail\ContactFormMail($validated));

        return redirect()->route('contact')->with('success', __('contact.success_message'));
    }
}
