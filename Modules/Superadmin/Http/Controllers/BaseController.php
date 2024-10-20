<?php

namespace Modules\Superadmin\Http\Controllers;

use \Notification;
use App\Models\System;
use Illuminate\Routing\Controller;
use Modules\Superadmin\Entities\Subscription;
use Modules\Superadmin\Notifications\NewSubscriptionNotification;

class BaseController extends Controller
{

    /**
     * Returns the list of all configured payment gateway
     * @return Response
     */
    protected function _payment_gateways()
    {
        $gateways = [];
        
        //Check if stripe is configured or not
        if (env('MERCADOPAGO_PUBLIC_KEY') && env('MERCADOPAGO_ACCESS_TOKEN')) {
            $gateways['mercado_pago'] = 'Mecado pago';
        }

        // check if offline payment is enabled or not
        $is_offline_payment_enabled = System::getProperty('enable_offline_payment');

        if ($is_offline_payment_enabled) {
            $gateways['offline'] = 'Offline';
        }

        return $gateways;
    }

    /**
     * Enter details for subscriptions
     * @return object
     */
    protected function _add_subscription($business_id, $package, $gateway, $payment_transaction_id, $user_id, $is_superadmin = false)
    {
        $subscription = ['business_id' => $business_id,
                        'package_id' => $package->id,
                        'paid_via' => $gateway,
                        'payment_transaction_id' => $payment_transaction_id
                    ];

        if (in_array($gateway, ['offline', 'pesapal']) && !$is_superadmin) {
            //If offline then dates will be decided when approved by superadmin
            $subscription['start_date'] = null;
            $subscription['end_date'] = null;
            $subscription['trial_end_date'] = null;
            $subscription['status'] = 'waiting';
        } else {
            $dates = $this->_get_package_dates($business_id, $package);

            $subscription['start_date'] = $dates['start'];
            $subscription['end_date'] = $dates['end'];
            $subscription['trial_end_date'] = $dates['trial'];
            $subscription['status'] = 'approved';
        }

        $subscription['package_price'] = $package->price;
        $subscription['package_details'] = [
                'location_count' => $package->location_count,
                'user_count' => $package->user_count,
                'product_count' => $package->product_count,
                'invoice_count' => $package->invoice_count,
                'name' => $package->name
            ];
        //Custom permissions.
        if (!empty($package->custom_permissions)) {
            foreach ($package->custom_permissions as $name => $value) {
                $subscription['package_details'][$name] = $value;
            }
        }
        
        $subscription['created_id'] = $user_id;

        $subscription = Subscription::create($subscription);

        if (!$is_superadmin) {
            $email = System::getProperty('email');
            $is_notif_enabled = System::getProperty('enable_new_subscription_notification');

            if (!empty($email) && $is_notif_enabled == 1) {
                Notification::route('mail', $email)
                ->notify(new NewSubscriptionNotification($subscription));
            }
        }

        return $subscription;
    }

    /**
     * The function returns the start/end/trial end date for a package.
     *
     * @param int $business_id
     * @param object $package
     *
     * @return array
     */
    protected function _get_package_dates($business_id, $package)
    {
        $output = ['start' => '', 'end' => '', 'trial' => ''];

        //calculate start date
        $start_date = Subscription::end_date($business_id);
        $output['start'] = $start_date->toDateString();

        //Calculate end date
        if ($package->interval == 'days') {
            $output['end'] = $start_date->addDays($package->interval_count)->toDateString();
        } elseif ($package->interval == 'months') {
            $output['end'] = $start_date->addMonths($package->interval_count)->toDateString();
        } elseif ($package->interval == 'years') {
            $output['end'] = $start_date->addYears($package->interval_count)->toDateString();
        }
        
        $output['trial'] = $start_date->addDays($package->trial_days);

        return $output;
    }
}
