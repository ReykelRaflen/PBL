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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable(); // Nomor telepon
            $table->string('email')->unique();
            $table->string('address')->nullable(); // Alamat
            $table->date('birthdate')->nullable(); // Tanggal lahir
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable(); // Jenis kelamin
            $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'])->nullable(); // Agama
            $table->string('foto')->nullable(); // Foto profil (ganti dari 'image')
            $table->string('role')->default('user'); // Role user
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('otp')->nullable(); // OTP untuk verifikasi email
            $table->timestamp('otp_created_at')->nullable(); // Waktu pembuatan OTP
            $table->boolean('is_verified')->default(false); // Status verifikasi
            $table->rememberToken();
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['email', 'is_verified']);
            $table->index('role');
        });

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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
