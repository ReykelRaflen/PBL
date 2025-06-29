<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('design_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('design_id')->constrained()->onDelete('cascade');
            $table->string('status_from');
            $table->string('status_to');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['design_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('design_status_histories');
    }
};
