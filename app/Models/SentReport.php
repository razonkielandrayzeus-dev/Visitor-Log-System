<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SentReport extends Model
{
    protected $fillable = [
        'sent_by',
        'recipient_email',
        'report_date',
        'total_visitors',
        'completed_visits',
        'active_visitors',
        'status',
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
