<?php

use App\Enums\PriceType;
use App\Models\RoomType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 3)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('room_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('weekday');
            $table->unsignedInteger('weekend');
            $table->enum('type', PriceType::all());
            $table->string('promotion_name')->nullable();
            $table->timestamp('effective_from');
            $table->timestamp('effective_to')->nullable();
            $table->foreignIdFor(RoomType::class)->nullable()->constrained();
            $table->string('room_type_name');
            $table->string('room_type_code');
            $table->timestamps();

            $table->unique(['room_type_id', 'type', 'effective_from'], 'unique_active_standard_price');

            $table->index(['room_type_id', 'type', 'effective_from', 'effective_to'], 'idx_room_price_lookup');
            $table->index(['type', 'effective_from', 'effective_to'], 'idx_price_date_range');
        });

        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignIdFor(RoomType::class)->nullable()->constrained();
            $table->string('room_type_name');
            $table->string('room_type_code');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('room_prices');
        Schema::dropIfExists('room_types');
    }
};
