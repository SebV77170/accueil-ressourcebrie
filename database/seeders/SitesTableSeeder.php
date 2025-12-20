<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sites')->insert([
            [
                'nom' => 'BDD RessourceBrie',
                'categorie' => 'gestion_associative',
                'url' => 'https://bdd.ressourcebrie.fr',
                'description' => 'Plateforme interne de gestion de la ressourcerie, incluant la base de données, la caisse et divers outils opérationnels.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Portail Bénévoles',
                'categorie' => 'gestion_rh',
                'url' => 'https://benevoles.ressourcebrie.fr',
                'description' => 'Espace dédié aux bénévoles pour suivre leurs missions, leurs documents et leurs communications internes.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Site Vitrine RessourceBrie',
                'categorie' => 'autre',
                'url' => 'https://www.ressourcebrie.fr',
                'description' => 'Site officiel présentant l’association, son fonctionnement, ses horaires et ses actualités.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'BasiCompta',
                'categorie' => 'gestion_associative',
                'url' => 'https://www.helloasso.com/basicompta',
                'description' => 'Outil de comptabilité associative utilisé pour la gestion financière de la ressourcerie.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'AlwaysData',
                'categorie' => 'informatique',
                'url' => 'https://www.alwaysdata.com',
                'description' => 'Plateforme d’hébergement web et gestion des bases MySQL, FTP et DNS.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'ACMS – Portail Santé Travail',
                'categorie' => 'gestion_rh',
                'url' => 'https://extranet.acms.asso.fr',
                'description' => 'Portail pour le suivi des visites médicales et des obligations de santé au travail.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Portail des Associations',
                'categorie' => 'gestion_associative',
                'url' => 'https://mon-compte.associations.gouv.fr',
                'description' => 'Plateforme officielle permettant la gestion administrative de l’association : démarches, déclarations, documents légaux.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
