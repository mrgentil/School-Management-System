<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\ExamNotification;
use App\Repositories\ExamRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;
use Illuminate\Http\Request;

class ExamPublicationController extends Controller
{
    protected $exam, $my_class, $student;

    public function __construct(ExamRepo $exam, MyClassRepo $my_class, StudentRepo $student)
    {
        $this->middleware('teamSA');
        $this->exam = $exam;
        $this->my_class = $my_class;
        $this->student = $student;
    }

    public function show($exam_id)
    {
        $d['exam'] = $exam = $this->exam->find($exam_id);
        $d['marks'] = $this->exam->getMark(['exam_id' => $exam_id, 'year' => $exam->year]);
        $d['records'] = $this->exam->getRecord(['exam_id' => $exam_id, 'year' => $exam->year]);
        $d['my_classes'] = $this->my_class->all();
        
        // Statistiques par classe
        $d['class_stats'] = [];
        foreach ($d['my_classes'] as $class) {
            $class_marks = $d['marks']->where('my_class_id', $class->id);
            $class_records = $d['records']->where('my_class_id', $class->id);
            
            if ($class_marks->count() > 0) {
                $d['class_stats'][$class->id] = [
                    'total_students' => $class_records->count(),
                    'graded' => $class_marks->where('tca', '>', 0)->count(),
                    'percentage' => $class_records->count() > 0 
                        ? round(($class_marks->where('tca', '>', 0)->count() / $class_records->count()) * 100, 1)
                        : 0,
                ];
            }
        }

        return view('pages.support_team.exam_publication.show', $d);
    }

    public function publish(Request $req, $exam_id)
    {
        $exam = $this->exam->find($exam_id);

        // Vérifier que toutes les notes sont saisies
        $marks = $this->exam->getMark(['exam_id' => $exam_id, 'year' => $exam->year]);
        $incomplete = $marks->filter(function($mark) {
            return is_null($mark->tca) || is_null($mark->exm);
        })->count();

        if ($incomplete > 0 && !$req->force) {
            return back()->with('flash_warning', "Attention: $incomplete note(s) incomplète(s). Ajoutez '?force=1' pour forcer la publication.");
        }

        // Mettre à jour le statut
        $this->exam->update($exam_id, [
            'status' => 'published',
            'results_published' => true,
            'published_at' => now(),
        ]);

        // Créer la notification
        ExamNotification::create([
            'exam_id' => $exam_id,
            'type' => 'results_published',
            'title' => 'Résultats Publiés - ' . $exam->name,
            'message' => "Les résultats de {$exam->name} ({$exam->year}) sont maintenant disponibles.",
            'recipients' => ['all_students'],
            'sent' => false,
        ]);

        return back()->with('flash_success', 'Résultats publiés avec succès');
    }

    public function unpublish($exam_id)
    {
        $this->exam->update($exam_id, [
            'results_published' => false,
            'status' => 'grading',
        ]);

        return back()->with('flash_success', 'Publication annulée');
    }

    public function sendNotification(Request $req, $exam_id)
    {
        $data = $req->validate([
            'type' => 'required|in:schedule_published,results_published,reminder,cancellation,modification',
            'title' => 'required|string',
            'message' => 'required|string',
            'classes' => 'nullable|array',
        ]);

        $exam = $this->exam->find($exam_id);
        
        $recipients = $req->classes ?: ['all_students'];

        ExamNotification::create([
            'exam_id' => $exam_id,
            'type' => $data['type'],
            'title' => $data['title'],
            'message' => $data['message'],
            'recipients' => $recipients,
            'sent' => false,
        ]);

        return back()->with('flash_success', 'Notification créée et sera envoyée');
    }
}
