<?php

namespace App\Models;

use Illuminate\Container\Attributes\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Apartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'city_id',
        'description',
        'address',
        'size',
        'num_of_rooms',
        'price',
        'apartment_images',
        'apartment_status',
    ];

    // علاقة مع المالك
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // علاقة مع المدينة
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    

  //  public function getUrlAttribute()
   // {
   //     return asset(Storage::url($this->picture));
   // }
}
