<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class BackupController extends Controller
{
    protected $backupPath;

    public function __construct()
    {
        $this->middleware('super_admin');
        $this->backupPath = storage_path('app/backups');
        
        // Créer le dossier s'il n'existe pas
        if (!File::exists($this->backupPath)) {
            File::makeDirectory($this->backupPath, 0755, true);
        }
    }

    /**
     * Page de gestion des backups
     */
    public function index()
    {
        $backups = $this->getBackupFiles();
        
        // Stats
        $stats = [
            'total_backups' => count($backups),
            'total_size' => array_sum(array_column($backups, 'size_bytes')),
            'last_backup' => count($backups) > 0 ? $backups[0]['date'] : null,
        ];

        return view('pages.support_team.backup.index', compact('backups', 'stats'));
    }

    /**
     * Créer un nouveau backup
     */
    public function create()
    {
        try {
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');

            $fileName = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filePath = $this->backupPath . '/' . $fileName;

            // Commande mysqldump
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($host),
                escapeshellarg($database),
                escapeshellarg($filePath)
            );

            // Exécuter la commande
            $result = null;
            $output = null;
            exec($command . ' 2>&1', $output, $result);

            if ($result === 0 && File::exists($filePath)) {
                $size = $this->formatBytes(File::size($filePath));
                return back()->with('flash_success', "✅ Backup créé avec succès ! ($fileName - $size)");
            }

            // Si mysqldump échoue, essayer la méthode PHP pure
            return $this->createPHPBackup($fileName);

        } catch (\Exception $e) {
            return back()->with('flash_danger', '❌ Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Backup via PHP (fallback)
     */
    protected function createPHPBackup($fileName)
    {
        try {
            $filePath = $this->backupPath . '/' . $fileName;
            $tables = DB::select('SHOW TABLES');
            $database = config('database.connections.mysql.database');
            
            $sql = "-- Backup de la base de données: $database\n";
            $sql .= "-- Date: " . date('Y-m-d H:i:s') . "\n";
            $sql .= "-- Généré par: E-School Backup System\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $table) {
                $tableName = array_values((array)$table)[0];
                
                // Structure de la table
                $createTable = DB::select("SHOW CREATE TABLE `$tableName`");
                $sql .= "-- Table: $tableName\n";
                $sql .= "DROP TABLE IF EXISTS `$tableName`;\n";
                $sql .= $createTable[0]->{'Create Table'} . ";\n\n";

                // Données
                $rows = DB::table($tableName)->get();
                if ($rows->count() > 0) {
                    foreach ($rows as $row) {
                        $values = array_map(function ($value) {
                            if ($value === null) return 'NULL';
                            return "'" . addslashes($value) . "'";
                        }, (array)$row);
                        
                        $sql .= "INSERT INTO `$tableName` VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $sql .= "\n";
                }
            }

            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

            File::put($filePath, $sql);
            $size = $this->formatBytes(File::size($filePath));
            
            return back()->with('flash_success', "✅ Backup créé avec succès ! ($fileName - $size)");

        } catch (\Exception $e) {
            return back()->with('flash_danger', '❌ Erreur lors du backup: ' . $e->getMessage());
        }
    }

    /**
     * Télécharger un backup
     */
    public function download($filename)
    {
        $filePath = $this->backupPath . '/' . $filename;
        
        if (!File::exists($filePath)) {
            return back()->with('flash_danger', '❌ Fichier non trouvé');
        }

        return response()->download($filePath);
    }

    /**
     * Supprimer un backup
     */
    public function destroy($filename)
    {
        $filePath = $this->backupPath . '/' . $filename;
        
        if (File::exists($filePath)) {
            File::delete($filePath);
            return back()->with('flash_success', '✅ Backup supprimé');
        }

        return back()->with('flash_danger', '❌ Fichier non trouvé');
    }

    /**
     * Restaurer un backup
     */
    public function restore($filename)
    {
        try {
            $filePath = $this->backupPath . '/' . $filename;
            
            if (!File::exists($filePath)) {
                return back()->with('flash_danger', '❌ Fichier non trouvé');
            }

            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');

            // Essayer avec mysql CLI
            $command = sprintf(
                'mysql --user=%s --password=%s --host=%s %s < %s',
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($host),
                escapeshellarg($database),
                escapeshellarg($filePath)
            );

            $result = null;
            exec($command . ' 2>&1', $output, $result);

            if ($result === 0) {
                return back()->with('flash_success', '✅ Base de données restaurée avec succès !');
            }

            // Fallback: restauration via PHP
            return $this->restorePHP($filePath);

        } catch (\Exception $e) {
            return back()->with('flash_danger', '❌ Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Restauration via PHP
     */
    protected function restorePHP($filePath)
    {
        try {
            $sql = File::get($filePath);
            
            // Désactiver les contraintes FK
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // Exécuter le SQL par blocs
            $statements = array_filter(array_map('trim', explode(";\n", $sql)));
            
            foreach ($statements as $statement) {
                if (!empty($statement) && !str_starts_with($statement, '--')) {
                    DB::unprepared($statement);
                }
            }
            
            // Réactiver les contraintes FK
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            return back()->with('flash_success', '✅ Base de données restaurée avec succès !');

        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            return back()->with('flash_danger', '❌ Erreur lors de la restauration: ' . $e->getMessage());
        }
    }

    /**
     * Obtenir la liste des fichiers de backup
     */
    protected function getBackupFiles()
    {
        $files = File::files($this->backupPath);
        $backups = [];

        foreach ($files as $file) {
            if ($file->getExtension() === 'sql') {
                $backups[] = [
                    'name' => $file->getFilename(),
                    'size' => $this->formatBytes($file->getSize()),
                    'size_bytes' => $file->getSize(),
                    'date' => Carbon::createFromTimestamp($file->getMTime())->format('d/m/Y H:i'),
                    'timestamp' => $file->getMTime(),
                ];
            }
        }

        // Trier par date décroissante
        usort($backups, fn($a, $b) => $b['timestamp'] <=> $a['timestamp']);

        return $backups;
    }

    /**
     * Formater les bytes
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
    }
}
