<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->string('lead_name', 160)->nullable();
            $table->foreignId('doctor_user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('status_id')->constrained('appointment_statuses')->restrictOnDelete();
            $table->string('title', 150)->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->unsignedSmallInteger('duration_minutes');
            $table->decimal('agreed_price', 10, 2)->nullable();
            $table->text('comment')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();

            $table->index(['starts_at', 'ends_at']);
            $table->index(['doctor_user_id', 'starts_at']);
            $table->index(['status_id', 'starts_at']);
            $table->index(['patient_id', 'starts_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
