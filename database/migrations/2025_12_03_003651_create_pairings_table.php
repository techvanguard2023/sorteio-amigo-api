<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pairings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('giver_participant_id')->constrained('participants')->cascadeOnDelete();
            $table->foreignId('receiver_participant_id')->constrained('participants')->cascadeOnDelete();
            $table->timestamps();
            
            $table->unique(['group_id', 'giver_participant_id']);
            $table->unique(['group_id', 'receiver_participant_id']);
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('pairings');
    }
};