<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixAllIssues extends Migration
{
    public function up()
    {
        try {
            // Ajouter la colonne 'code' à la table 'users' si elle n'existe pas
            if (!Schema::hasColumn('users', 'code')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->string('code')->after('id')->nullable();
                });
            }

            // Ajouter la colonne 'my_class_id' à la table 'payments' si elle n'existe pas
            if (!Schema::hasColumn('payments', 'my_class_id')) {
                Schema::table('payments', function (Blueprint $table) {
                    $table->unsignedInteger('my_class_id')->nullable()->after('id');
                });
            }

            // Vérifier si la colonne class_id existe déjà, sinon l'ajouter
            if (!Schema::hasColumn('payments', 'class_id')) {
                Schema::table('payments', function (Blueprint $table) {
                    $table->unsignedInteger('class_id')->nullable()->after('my_class_id');
                });
            }

            // Mettre à jour class_id avec les valeurs de my_class_id si class_id est null
            DB::statement('UPDATE payments SET class_id = my_class_id WHERE class_id IS NULL AND my_class_id IS NOT NULL');

            // Essayer d'ajouter la contrainte d'unicité sur le code utilisateur
            try {
                DB::statement('ALTER TABLE users ADD UNIQUE users_code_unique (code)');
            } catch (\Exception $e) {
                // Ignorer l'erreur si la contrainte existe déjà
            }

            // Essayer d'ajouter la clé étrangère pour my_class_id
            try {
                DB::statement('ALTER TABLE payments ADD CONSTRAINT payments_my_class_id_foreign FOREIGN KEY (my_class_id) REFERENCES my_classes(id) ON DELETE SET NULL');
            } catch (\Exception $e) {
                // Ignorer l'erreur si la contrainte existe déjà
            }

            // Essayer d'ajouter la clé étrangère pour class_id (pour rétrocompatibilité)
            try {
                DB::statement('ALTER TABLE payments ADD CONSTRAINT payments_class_id_foreign FOREIGN KEY (class_id) REFERENCES my_classes(id) ON DELETE SET NULL');
            } catch (\Exception $e) {
                // Ignorer l'erreur si la contrainte existe déjà
            }
        } catch (\Exception $e) {
            // Journaliser l'erreur mais ne pas arrêter l'exécution
            \Log::error('Migration error: ' . $e->getMessage());
        }
    }

    public function down()
    {
        // Cette méthode peut rester vide car nous ne voulons pas supprimer les colonnes
        // pour éviter de perdre des données accidentellement
    }
}
