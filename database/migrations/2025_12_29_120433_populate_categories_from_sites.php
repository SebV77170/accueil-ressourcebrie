<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $categories = DB::table('sites')
            ->select('categorie')
            ->distinct()
            ->whereNotNull('categorie')
            ->pluck('categorie');

        foreach ($categories as $nom) {
            DB::table('categories')->insertOrIgnore([
                'nom' => $nom,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('categories')->truncate();
    }
};
