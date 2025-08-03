<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('organization', function (Blueprint $table) {
            $table->id('organizationID');

            $table->string('title', 255)->nullable();

            $table->foreignId('phoneID')
                ->constrained('phone', 'phoneID')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('buildingID')
                ->constrained('building', 'buildingID')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('activityID')
                ->constrained('activity', 'activityID')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization');
    }
};
