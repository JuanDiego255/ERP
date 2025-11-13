<?php

namespace App\Jobs;

use App\Models\SmsCampaignRecipient;
use App\Services\SmsEmpresarialService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCampaignSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $recipientId;

    /**
     * Crear el Job
     */
    public function __construct(int $recipientId)
    {
        $this->recipientId = $recipientId;
    }

    /**
     * Lógica del Job
     */
    public function handle(SmsEmpresarialService $smsService): void
    {
        $recipient = SmsCampaignRecipient::with('campaign')->find($this->recipientId);

        if (! $recipient || $recipient->status !== 'pending') {
            return;
        }

        $telefono = $recipient->telefono;
        $mensaje  = $recipient->campaign->message;

        $result = $smsService->send($telefono, $mensaje);

        if ($result['ok']) {
            $recipient->update([
                'status'            => 'sent',
                'provider_response' => is_array($result['data']) ? json_encode($result['data']) : $result['data'],
                'sent_at'           => now(),
            ]);

            $recipient->campaign()->increment('sent_count');
        } else {
            $recipient->update([
                'status'         => 'failed',
                'error_message'  => $result['error'] ?? 'Error desconocido',
                'provider_response' => $result['status'] ?? null,
            ]);

            $recipient->campaign()->increment('failed_count');
        }
    }
}
