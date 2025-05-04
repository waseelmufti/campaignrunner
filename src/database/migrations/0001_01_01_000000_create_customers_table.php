<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_number', 50)->unique();
            $table->enum("status", config("campaignrunner.customer_statuses"))->default("Grace period");
            $table->date("plan_expiry_date");
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('customers');
    }
};
