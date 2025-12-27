<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ca_tasks', function (Blueprint $table) {
            $table->id();

            $table->string('titre');
            $table->text('description')->nullable();

            $table->json('responsables')->nullable();
            $table->text('commentaire')->nullable();

            $table->boolean('est_terminee')->default(false);
            $table->timestamp('date_effectuee')->nullable();

            $table->boolean('est_archivee')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ca_tasks');
    }
};
