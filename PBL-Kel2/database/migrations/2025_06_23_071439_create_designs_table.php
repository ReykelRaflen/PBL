<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('designs', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('cover')->nullable();
            $table->foreignId('pembuat_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['draft', 'review', 'approved', 'rejected', 'completed'])->default('draft');
            $table->date('due_date')->nullable();
            $table->timestamp('tanggal_upload')->useCurrent();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('designs');
    }
};
