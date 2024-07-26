<div class="pos-tab-content">
    <div class="row">
        <div class="col-xs-12">
            @if (config('app.env') != 'demo')
                <p>
                    Para enviar <mark>alerta de expiración de suscripción</mark> y el proceso de <mark>respaldo
                        automático de la aplicación</mark> debes configurar un trabajo cron con este comando:<br />
                    <code>{{ $cron_job_command }}</code>
                </p>
                <p>
                    Configúralo en la pestaña de trabajos cron en cPanel o DirectAdmin o panel similar. <br />O edita
                    crontab si estás usando hosting en la nube/dedicado. <br />O contacta al servicio de alojamiento
                    para obtener ayuda con la configuración de trabajos cron.
                </p>
            @else
                @lang('lang_v1.disabled_in_demo')
            @endif
        </div>
    </div>
</div>
