<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\AcademicSection;
use App\Models\MyClass;
use App\Models\Option;
use App\Models\Section;
use App\Models\StudentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentStatsController extends Controller
{
    public function index(Request $request)
    {
        if (!Qs::userIsTeamSAT() && !Qs::userIsSuperAdmin()) {
            abort(403, __('msg.denied'));
        }

        $filters = $request->only([
            'my_class_id',
            'section_id',
            'academic_section_id',
            'option_id',
            'session',
        ]);

        $query = StudentRecord::query()
            ->select([
                'my_classes.name as class_name',
                'sections.name as division_name',
                'academic_sections.name as academic_section_name',
                'options.name as option_name',
                DB::raw('COUNT(student_records.id) as total_students'),
            ])
            ->leftJoin('my_classes', 'student_records.my_class_id', '=', 'my_classes.id')
            ->leftJoin('sections', 'student_records.section_id', '=', 'sections.id')
            ->leftJoin('academic_sections', 'student_records.academic_section_id', '=', 'academic_sections.id')
            ->leftJoin('options', 'student_records.option_id', '=', 'options.id');

        if (!empty($filters['my_class_id'])) {
            $query->where('student_records.my_class_id', $filters['my_class_id']);
        }
        if (!empty($filters['section_id'])) {
            $query->where('student_records.section_id', $filters['section_id']);
        }
        if (!empty($filters['academic_section_id'])) {
            $query->where('student_records.academic_section_id', $filters['academic_section_id']);
        }
        if (!empty($filters['option_id'])) {
            $query->where('student_records.option_id', $filters['option_id']);
        }
        if (!empty($filters['session'])) {
            $query->where('student_records.session', $filters['session']);
        }

        $stats = $query
            ->groupBy('class_name', 'division_name', 'academic_section_name', 'option_name')
            ->orderBy('class_name')
            ->orderBy('division_name')
            ->get();

        $data = [
            'stats' => $stats,
            'classes' => MyClass::orderBy('name')->get(),
            'divisions' => Section::with('my_class')->orderBy('name')->get(),
            'academic_sections' => AcademicSection::orderBy('name')->get(),
            'options' => Option::with('academic_section')->orderBy('name')->get(),
            'sessions' => StudentRecord::select('session')->distinct()->orderBy('session', 'desc')->pluck('session'),
            'filters' => $filters,
        ];

        return view('pages.support_team.students.statistics', $data);
    }
}
