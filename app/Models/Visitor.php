<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    // Disable auto timestamp updates
    const UPDATED_AT = null;

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
        'time_in' => 'datetime',
        'time_out' => 'datetime',
    ];

    // Renamed from guard() to loggedByUser() to avoid conflict with Model::guard()
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
        return $this->time_in->diffForHumans($this->time_out, true);
    }
}
