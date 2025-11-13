<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsCampaign extends Model
{
    protected $fillable = [
        'name',
        'message',
        'total_recipients',
        'sent_count',
        'failed_count',
    ];

    public function recipients()
    {
        return $this->hasMany(SmsCampaignRecipient::class);
    }
}
