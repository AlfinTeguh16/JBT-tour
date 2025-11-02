<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // password_reset_tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->string('phone', 30)->nullable();
            $table->enum('role', ['admin','staff','driver','guide']);
            $table->boolean('is_active')->default(true);

            // tambahkan remember token secara standar
            $table->rememberToken();

            // batas jam per bulan (nullable)
            $table->decimal('monthly_hours_limit', 6, 2)->nullable();

            $table->timestamps();
        });

        // customers
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('email', 150)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });

        // vehicles
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_no', 30)->unique();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->integer('capacity')->nullable();
            $table->enum('status', ['available','in_use','maintenance'])->default('available');
            $table->timestamps();
        });

        // orders
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

        // assignments
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            // order_id unique — satu order hanya punya satu assignment pada desain ini
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

        // work_sessions
        Schema::create('work_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // driver/guide
            $table->foreignId('assignment_id')->nullable()->constrained()->nullOnDelete();
            $table->dateTime('started_at');
            $table->dateTime('ended_at')->nullable();
            $table->decimal('hours_decimal', 6, 2)->nullable();
            $table->timestamps();
        });

        // notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('body')->nullable();
            $table->boolean('is_read')->default(false);
            $table->foreignId('assignment_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['pending','approved','declined'])->default('pending');
            $table->timestamps();
        });

        // driver_locations
        Schema::create('driver_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // driver
            $table->foreignId('work_session_id')->constrained()->cascadeOnDelete();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_locations');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('work_sessions');
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
    }
};
