<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;

class UpdateCurrentSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:update {session?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mettre à jour la session scolaire courante';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentSetting = Setting::where('type', 'current_session')->first();
        
        if (!$currentSetting) {
            $this->error('Paramètre "current_session" non trouvé dans la base de données!');
            return 1;
        }

        $this->info("Session actuelle: {$currentSetting->description}");
        
        // Si une session est fournie en argument
        $newSession = $this->argument('session');
        
        if (!$newSession) {
            // Proposer la session actuelle (année en cours)
            $currentYear = date('Y');
            $nextYear = $currentYear + 1;
            $suggestedSession = $currentYear . '-' . $nextYear;
            
            $this->info("Session suggérée: {$suggestedSession}");
            
            // Demander confirmation
            $newSession = $this->ask('Entrez la nouvelle session (format: YYYY-YYYY)', $suggestedSession);
        }
        
        // Valider le format
        if (!preg_match('/^\d{4}-\d{4}$/', $newSession)) {
            $this->error('Format invalide! Utilisez le format: YYYY-YYYY (ex: 2024-2025)');
            return 1;
        }
        
        // Confirmer la mise à jour
        if ($this->confirm("Voulez-vous vraiment changer la session de '{$currentSetting->description}' à '{$newSession}'?", true)) {
            $currentSetting->description = $newSession;
            $currentSetting->save();
            
            $this->info("✓ Session mise à jour avec succès!");
            $this->line("Nouvelle session: {$newSession}");
            
            // Vider le cache de configuration
            $this->call('config:clear');
            $this->call('cache:clear');
            
            return 0;
        }
        
        $this->warn('Opération annulée.');
        return 0;
    }
}
