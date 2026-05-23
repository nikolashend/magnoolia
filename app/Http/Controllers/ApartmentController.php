<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Http\Request;

class ApartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Apartment::query()->where('is_published', true);

        if ($request->filled('location')) {
            $query->where('address', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('rooms')) {
            $query->where('rooms', '>=', $request->rooms);
        }

        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default      => $query->latest(),
        };

        $apartments = $query->paginate(9);

        return view('pages.apartments.index', compact('apartments'));
    }

    public function show(string $slug)
    {
        $apartment = Apartment::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('pages.apartments.show', compact('apartment'));
    }

    public function inquiry(Request $request)
    {
        $validated = $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'name'         => 'required|string|max:255',
            'email'        => 'required|email',
            'phone'        => 'nullable|string|max:50',
            'message'      => 'nullable|string',
        ]);

        // TODO: handle inquiry (store, email notification)

        return redirect()->back()->with('success', __('contact.success_message'));
    }
}
