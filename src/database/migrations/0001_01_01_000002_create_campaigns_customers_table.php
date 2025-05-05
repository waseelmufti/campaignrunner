<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('campaigns_customers', function (Blueprint $table) {
            $table->bigInteger('campaign_id')->unsigned();
            $table->bigInteger('customer_id')->unsigned();
            $table->enum("delivery_status", config("campaignrunner.delivery_status"))->default("Queued");
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('campaigns_customers');
    }
};
