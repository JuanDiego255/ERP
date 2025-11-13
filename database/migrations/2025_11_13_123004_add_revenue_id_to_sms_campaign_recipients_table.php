<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRevenueIdToSmsCampaignRecipientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('sms_campaign_recipients', function (Blueprint $table) {
            $table->unsignedBigInteger('revenue_id')->nullable()->after('cliente_id');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sms_campaign_recipients', function (Blueprint $table) {
            //
        });
    }
}
