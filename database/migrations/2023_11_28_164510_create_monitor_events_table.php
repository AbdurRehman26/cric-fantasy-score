<?php

use App\Enums\MonitorEventType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitor_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('monitor_id');
            $table->string('location', 2);
            $table->enum('type', [
                MonitorEventType::FIRING->value,
                MonitorEventType::NORMAL->value,
                MonitorEventType::FAILED->value,
            ]);
            $table->json('data');
            $table->timestamps();

            $table->index(['monitor_id', 'location', 'created_at'], 'monitor_location_date');
            $table->index(['monitor_id', 'location', 'type', 'created_at'], 'monitor_location_type_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitor_events');
    }
};
