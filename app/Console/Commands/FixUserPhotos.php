<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Helpers\Qs;
use Illuminate\Console\Command;

class FixUserPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:fix-photos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Répare les photos manquantes des utilisateurs en définissant l\'image par défaut';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Réparation des photos utilisateurs...');

        // Compter les utilisateurs sans photo ou avec photo invalide
        $usersWithoutPhoto = User::whereNull('photo')
            ->orWhere('photo', '')
            ->count();

        $usersWithInvalidPhoto = User::whereNotNull('photo')
            ->where('photo', '!=', '')
            ->get()
            ->filter(function ($user) {
                $photo = $user->getRawOriginal('photo'); // Obtenir la valeur brute sans l'accesseur
                if (filter_var($photo, FILTER_VALIDATE_URL)) {
                    $relativePath = str_replace(url('/'), '', $photo);
                    return !file_exists(public_path($relativePath));
                }
                return false;
            })
            ->count();

        $totalToFix = $usersWithoutPhoto + $usersWithInvalidPhoto;

        if ($totalToFix === 0) {
            $this->info('✅ Toutes les photos utilisateurs sont correctes !');
            return;
        }

        $this->info("📊 Utilisateurs à réparer : {$totalToFix}");
        $this->info("   - Sans photo : {$usersWithoutPhoto}");
        $this->info("   - Photo invalide : {$usersWithInvalidPhoto}");

        if ($this->confirm('Voulez-vous continuer la réparation ?')) {
            $defaultImage = Qs::getDefaultUserImage();

            // Réparer les utilisateurs sans photo
            User::whereNull('photo')
                ->orWhere('photo', '')
                ->update(['photo' => $defaultImage]);

            // Réparer les utilisateurs avec photo invalide
            User::whereNotNull('photo')
                ->where('photo', '!=', '')
                ->get()
                ->each(function ($user) use ($defaultImage) {
                    $photo = $user->getRawOriginal('photo');
                    if (filter_var($photo, FILTER_VALIDATE_URL)) {
                        $relativePath = str_replace(url('/'), '', $photo);
                        if (!file_exists(public_path($relativePath))) {
                            $user->update(['photo' => $defaultImage]);
                        }
                    }
                });

            $this->info('✅ Réparation terminée avec succès !');
            $this->info("🖼️  Image par défaut utilisée : {$defaultImage}");
        } else {
            $this->info('❌ Réparation annulée.');
        }
    }
}
