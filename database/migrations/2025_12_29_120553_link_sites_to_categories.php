<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $sites = DB::table('sites')->get();

        foreach ($sites as $site) {
            $categoryId = DB::table('categories')
                ->where('nom', $site->categorie)
                ->value('id');

            if ($categoryId) {
                DB::table('sites')
                    ->where('id', $site->id)
                    ->update(['category_id' => $categoryId]);
            }
        }
    }

    public function down(): void
    {
        DB::table('sites')->update(['category_id' => null]);
    }
};
