<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('designs', function (Blueprint $table) {
            $table->foreignId('reviewer_id')->nullable()->after('pembuat_id')->constrained('users')->onDelete('set null');
            $table->timestamp('direview_pada')->nullable()->after('due_date');
        });
    }

    public function down()
    {
        Schema::table('designs', function (Blueprint $table) {
            $table->dropForeign(['reviewer_id']);
            $table->dropColumn(['reviewer_id', 'direview_pada']);
        });
    }
};
