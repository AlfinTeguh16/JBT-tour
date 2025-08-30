<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
        
        // Users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->string('phone', 30)->nullable();
            $table->enum('role', ['admin','staff','driver','guide']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Customers
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('email', 150)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });

        // Vehicles
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_no', 30)->unique();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->integer('capacity')->nullable();
            $table->enum('status', ['available','in_use','maintenance'])->default('available');
            $table->timestamps();
        });

        // Orders (langsung mewakili permintaan jasa transportasi)
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->dateTime('requested_at');             // kapan customer pesan
            $table->dateTime('service_date')->nullable(); // kapan akan dilayani
            $table->string('pickup_location')->nullable();
            $table->string('dropoff_location')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending','assigned','in_progress','completed','cancelled'])->default('pending');
            $table->timestamps();
        });

        // Assignments (penugasan driver/guide/vehicle untuk order)
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained('users'); // staff yang assign
            $table->foreignId('driver_id')->nullable()->constrained('users');
            $table->foreignId('guide_id')->nullable()->constrained('users');
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles');
            $table->dateTime('scheduled_start')->nullable();
            $table->dateTime('scheduled_end')->nullable();
            $table->decimal('estimated_hours', 6, 2)->nullable();
            $table->enum('status', ['assigned','in_progress','completed','cancelled'])->default('assigned');
            $table->timestamps();
        });

        // Work sessions (jam kerja driver/guide)
        Schema::create('work_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // driver/guide
            $table->foreignId('assignment_id')->nullable()->constrained()->nullOnDelete();
            $table->dateTime('started_at');
            $table->dateTime('ended_at')->nullable();
            $table->decimal('hours_decimal', 6, 2)->nullable();
            $table->timestamps();
        });

        // Notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('body')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('work_sessions');
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('users');
    }
};
