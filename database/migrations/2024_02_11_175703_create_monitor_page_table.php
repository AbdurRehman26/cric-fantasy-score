<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitor_page', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('monitor_id');
            $table->unsignedBigInteger('page_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitor_page');
    }
};
