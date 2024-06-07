<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitor_notification_channel', function (Blueprint $table) {
            $table->unsignedBigInteger('monitor_id');
            $table->unsignedBigInteger('notification_channel_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitor_notification_channel');
    }
};
