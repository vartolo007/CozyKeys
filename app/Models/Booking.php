<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'apartment_id',
        'check_in_date',
        'check_out_date',
        'booking_status',
        'rating',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'rating' => 'float',
    ];

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     *
     *
     * شرط التداخل: new.check_in < existing.check_out AND new.check_out > existing.check_in
     *
     * @param int $apartmentId
     * @param string $checkIn
     * @param string $checkOut
     * @param array $statuses
     * @param int|null $excludeId
     * @return bool
     */
    public static function hasOverlap(int $apartmentId, string $checkIn, string $checkOut, array $statuses = ['approved'], ?int $excludeId = null): bool
    {
        $query = self::where('apartment_id', $apartmentId)
            ->whereIn('booking_status', $statuses)
            ->where('check_in_date', '<', $checkOut)
            ->where('check_out_date', '>', $checkIn);

        if ($excludeId) {
            $query->where('id', '<>', $excludeId);
        }

        return $query->exists();
    }
}
