<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Apartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'address', 'price', 'price_currency',
        'status', 'rooms', 'bathrooms', 'area', 'is_published', 'images',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'images'       => 'array',
        'price'        => 'decimal:2',
        'area'         => 'decimal:2',
    ];

    public function getFormattedPriceAttribute(): string
    {
        if (!$this->price) return __('apartments.price_on_request');
        return number_format($this->price, 0, ',', ' ') . ' ' . $this->price_currency;
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'for_rent' => __('apartments.for_rent'),
            'for_sale' => __('apartments.for_sale'),
            'sold'     => __('apartments.sold'),
            'rented'   => __('apartments.rented'),
            default    => $this->status,
        };
    }

    public function getMainImageUrlAttribute(): string
    {
        $images = $this->images ?? [];
        return !empty($images[0]) ? $images[0] : asset('assets/images/resources/apartment-placeholder.jpg');
    }

    public function getThumbnailUrlAttribute(): string
    {
        return $this->main_image_url;
    }
}
