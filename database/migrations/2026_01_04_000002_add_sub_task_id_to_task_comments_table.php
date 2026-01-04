<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('task_comments', function (Blueprint $table) {
            $table->foreignId('sub_task_id')
                ->nullable()
                ->after('task_id')
                ->constrained('ca_sub_tasks')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('task_comments', function (Blueprint $table) {
            $table->dropForeign(['sub_task_id']);
            $table->dropColumn('sub_task_id');
        });
    }
};
