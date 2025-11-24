<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\SubjectGradeConfig;
use App\Models\MyClass;
use App\Models\Subject;
use App\Helpers\Qs;
use Illuminate\Http\Request;

class SubjectGradeConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA'); // Super Admin seulement
    }

    /**
     * Afficher la page de configuration des cotes
     */
    public function index()
    {
        $data['my_classes'] = MyClass::orderBy('name')->get();
        $data['subjects'] = Subject::orderBy('name')->get();
        $data['current_year'] = Qs::getSetting('current_session');
        $data['selected_class'] = null;
        $data['configs'] = collect();

        return view('pages.support_team.subject_grades_config.index', $data);
    }

    /**
     * Afficher les configurations pour une classe spécifique
     */
    public function show($classId)
    {
        $data['my_classes'] = MyClass::orderBy('name')->get();
        $data['subjects'] = Subject::orderBy('name')->get();
        $data['current_year'] = Qs::getSetting('current_session');
        $data['selected_class'] = MyClass::findOrFail($classId);
        
        // Récupérer les configurations existantes
        $data['configs'] = SubjectGradeConfig::getClassConfigs($classId);
        
        // Récupérer toutes les matières pour cette classe
        $data['class_subjects'] = Subject::where('my_class_id', $classId)->orderBy('name')->get();

        return view('pages.support_team.subject_grades_config.index', $data);
    }

    /**
     * Sauvegarder les configurations
     */
    public function store(Request $request)
    {
        $request->validate([
            'my_class_id' => 'required|exists:my_classes,id',
            'configs' => 'required|array',
            'configs.*.subject_id' => 'required|exists:subjects,id',
            'configs.*.period_max_points' => 'required|numeric|min:1|max:999',
            'configs.*.exam_max_points' => 'required|numeric|min:1|max:999',
        ]);

        $classId = $request->my_class_id;
        $configs = $request->configs;
        $year = Qs::getSetting('current_session');

        $savedCount = 0;

        foreach ($configs as $config) {
            SubjectGradeConfig::setConfig(
                $classId,
                $config['subject_id'],
                $config['period_max_points'],
                $config['exam_max_points'],
                $year
            );
            $savedCount++;
        }

        return redirect()->route('subject-grades-config.show', $classId)
                        ->with('flash_success', "Configuration sauvegardée avec succès pour {$savedCount} matière(s).");
    }

    /**
     * Mettre à jour une configuration spécifique
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'period_max_points' => 'required|numeric|min:1|max:999',
            'exam_max_points' => 'required|numeric|min:1|max:999',
            'notes' => 'nullable|string|max:500'
        ]);

        $config = SubjectGradeConfig::findOrFail($id);
        $config->update([
            'period_max_points' => $request->period_max_points,
            'exam_max_points' => $request->exam_max_points,
            'notes' => $request->notes
        ]);

        return redirect()->back()
                        ->with('flash_success', 'Configuration mise à jour avec succès.');
    }

    /**
     * Supprimer une configuration
     */
    public function destroy($id)
    {
        $config = SubjectGradeConfig::findOrFail($id);
        $config->delete();

        return redirect()->back()
                        ->with('flash_success', 'Configuration supprimée avec succès.');
    }

    /**
     * Dupliquer les configurations d'une classe vers une autre
     */
    public function duplicate(Request $request)
    {
        $request->validate([
            'source_class_id' => 'required|exists:my_classes,id',
            'target_class_id' => 'required|exists:my_classes,id|different:source_class_id',
        ]);

        $sourceConfigs = SubjectGradeConfig::getClassConfigs($request->source_class_id);
        $year = Qs::getSetting('current_session');
        $duplicatedCount = 0;

        foreach ($sourceConfigs as $config) {
            // Vérifier si la matière existe pour la classe cible
            $subjectExists = Subject::where('my_class_id', $request->target_class_id)
                                   ->where('id', $config->subject_id)
                                   ->exists();

            if ($subjectExists) {
                SubjectGradeConfig::setConfig(
                    $request->target_class_id,
                    $config->subject_id,
                    $config->period_max_points,
                    $config->exam_max_points,
                    $year
                );
                $duplicatedCount++;
            }
        }

        return redirect()->route('subject-grades-config.show', $request->target_class_id)
                        ->with('flash_success', "Configuration dupliquée avec succès pour {$duplicatedCount} matière(s).");
    }

    /**
     * Initialiser les configurations par défaut pour une classe
     */
    public function initializeDefaults($classId)
    {
        $class = MyClass::findOrFail($classId);
        $year = Qs::getSetting('current_session');
        
        // Récupérer toutes les matières pour cette classe
        $subjects = Subject::where('my_class_id', $classId)->get();

        $initializedCount = 0;

        foreach ($subjects as $subject) {
            // Vérifier si la configuration n'existe pas déjà
            $existingConfig = SubjectGradeConfig::getConfig($classId, $subject->id, $year);
            
            if (!$existingConfig) {
                SubjectGradeConfig::setConfig(
                    $classId,
                    $subject->id,
                    20, // Valeur par défaut période
                    40, // Valeur par défaut examen
                    $year
                );
                $initializedCount++;
            }
        }

        return redirect()->route('subject-grades-config.show', $classId)
                        ->with('flash_success', "Configuration par défaut initialisée pour {$initializedCount} matière(s).");
    }

    /**
     * Get configuration for a specific class and subject (AJAX)
     */
    public function getConfig(Request $request)
    {
        $classId = $request->get('class_id');
        $subjectId = $request->get('subject_id');
        
        if (!$classId || !$subjectId) {
            return response()->json([
                'success' => false,
                'message' => 'Paramètres manquants'
            ]);
        }
        
        $config = SubjectGradeConfig::getConfig($classId, $subjectId, Qs::getCurrentSession());
        
        if ($config) {
            return response()->json([
                'success' => true,
                'config' => [
                    'period_max_score' => $config->period_max_score,
                    'exam_max_score' => $config->exam_max_score,
                    'class_name' => $config->myClass ? $config->myClass->name : 'N/A',
                    'subject_name' => $config->subject ? $config->subject->name : 'N/A'
                ]
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Configuration non trouvée'
        ]);
    }
}
