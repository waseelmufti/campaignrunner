<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subject')->nullable();
            $table->longText('body')->nullable();
            $table->enum("status", config("campaignrunner.campaign_statuses"))->default("Inactive");
            $table->date("expiry_date");
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('campaigns');
    }
};
