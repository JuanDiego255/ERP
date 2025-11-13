<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsCampaignRecipient extends Model
{
    protected $fillable = [
        'sms_campaign_id',
        'cliente_id',
        'revenue_id',   // <-- nuevo
        'telefono',
        'status',
        'provider_response',
        'error_message',
        'sent_at',
    ];


    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(SmsCampaign::class, 'sms_campaign_id');
    }
}
