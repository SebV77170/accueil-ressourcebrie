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
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->enum('categorie', [
                'gestion_rh',
                'gestion_associative',
                'informatique',
                'autre'
            ]);
            $table->string('url');
            $table->text('description');
            $table->timestamps(); // created_at + updated_at
            $table->string('icone')->nullable();
            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
