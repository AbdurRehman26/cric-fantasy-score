<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitor_metrics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('monitor_id');
            $table->string('location', 2);
            $table->json('data');
            $table->timestamps();

            $table->index(['monitor_id', 'location', 'created_at'], 'monitor_location_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitor_metrics');
    }
};
