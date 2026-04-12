<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'purpose',
        'host_name',
        'time_in',
        'time_out',
        'logged_by',
        'ip_address',
        'location',
    ];

    protected $casts = [
        'time_in'  => 'datetime',
        'time_out' => 'datetime',
    ];

    // Prevent Eloquent from auto-modifying time_in on updates
    protected $guarded = [];

    public function loggedByUser()
    {
        return $this->belongsTo(User::class, 'logged_by');
    }

    public function isActive(): bool
    {
        return is_null($this->time_out);
    }

    public function duration()
    {
        if (!$this->time_out) {
            return null;
        }

        $diffInMinutes = $this->time_in->diffInMinutes($this->time_out);

        if ($diffInMinutes < 1) {
            return number_format($this->time_in->floatDiffInSeconds($this->time_out), 2) . ' seconds';
        } elseif ($diffInMinutes < 60) {
            return $diffInMinutes . ' min';
        } else {
            $hours   = floor($diffInMinutes / 60);
            $minutes = $diffInMinutes % 60;
            return $hours . 'h ' . ($minutes > 0 ? $minutes . 'm' : '');
        }
    }
}
