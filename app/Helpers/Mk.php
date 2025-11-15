<?php

namespace App\Helpers;

use App\Models\Exam;
use App\Models\ExamRecord;
use App\Models\Grade;
use App\Models\Mark;
use Illuminate\Database\Eloquent\Collection;

class Mk extends Qs
{
    public static function examIsLocked()
    {
        return self::getSetting('lock_exam');
    }

    public static function getRemarks()
    {
        return ['Average', 'Credit', 'Distinction', 'Excellent', 'Fail', 'Fair', 'Good', 'Pass', 'Poor', 'Very Good', 'Very Poor'];
    }

    /** ADD ORDINAL SUFFIX TO POSITION **/
    public static function getSuffix($number)
    {
        if($number < 1){ return NULL;}

        $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if ((($number % 100) >= 11) && (($number % 100) <= 13))
            return $number . '<sup>th</sup>';
        else
            return $number . '<sup>' . $ends[$number % 10] . '</sup>';
    }

    /*Get Subject Total Per Period (1-4)*/
    public static function getSubTotalPeriod($st_id, $sub_id, $period, $class_id, $year)
    {
        $d = ['student_id' => $st_id, 'subject_id' => $sub_id, 'my_class_id' => $class_id, 'year' => $year];

        // Pour compatibilité: p1_avg, p2_avg, p3_avg, p4_avg
        $p_col = 'p'.$period.'_avg';
        $sub_total = Mark::where($d)->select($p_col)->get()->where($p_col, '>', 0);
        return $sub_total->count() > 0 ? $sub_total->first()->$p_col : '-';
    }

    /*Get Subject Total Per Term (legacy - maintenant par période)*/
    public static function getSubTotalTerm($st_id, $sub_id, $term, $class_id, $year)
    {
        // Redirection vers la nouvelle méthode par période
        return self::getSubTotalPeriod($st_id, $sub_id, $term, $class_id, $year);
    }

    public static function countDistinctions(Collection $marks)
    {
        $gradeIDS = Grade::where('name', 'LIKE', 'A%')->orWhere('name', 'LIKE', 'B%')->get()->pluck('id')->toArray();
        return self::markGradeFilter($marks, $gradeIDS);
    }

    public static function countPasses(Collection $marks)
    {
        $gradeIDS = Grade::where('name', 'LIKE', 'D%')->orWhere('name', 'LIKE', 'E%')->get()->pluck('id')->toArray();
        return self::markGradeFilter($marks, $gradeIDS);
    }

    public static function countCredits(Collection $marks)
    {
        $gradeIDS = Grade::where('name', 'LIKE', 'C%')->get()->pluck('id')->toArray();
        return self::markGradeFilter($marks, $gradeIDS);
    }

    public static function countFailures(Collection $marks)
    {
        $gradeIDS = Grade::where('name', 'LIKE', 'F%')->get()->pluck('id')->toArray();
        return self::markGradeFilter($marks, $gradeIDS);
    }

    public static function countStudents($exam_id, $class_id, $section_id, $year)
    {
        $d = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'section_id' => $section_id, 'year' => $year];
        return Mark::where($d)->select('student_id')->distinct()->get()->count();
    }

    protected static function markGradeFilter(Collection $marks, $gradeIDS)
    {
        return $marks->filter(function($mks) use ($gradeIDS){
            return in_array($mks->grade_id, $gradeIDS);
        })->count();
    }

    public static function countSubjectsOffered(Collection $mark)
    {
        return $mark->filter(function($mk) {
            return ($mk->tca + $mk->exm) > 0 ;
        })->count();
    }

    /*Get Exam Avg Per Semester (1 ou 2)*/
    public static function getSemesterAverage($st_id, $semester, $year)
    {
        $exam = self::getExamBySemester($semester, $year);
        if (!$exam) return null;
        
        $d = ['exam_id' => $exam->id, 'student_id' => $st_id, 'year' => $year];
        $exr = ExamRecord::where($d)->first();
        
        if ($exr) {
            $avg = $exr->ave ?: NULL;
            return $avg > 0 ? round($avg, 1) : $avg;
        }
        
        return null;
    }

    /*Get Exam Avg Per Term (legacy - compatibilité)*/
    public static function getTermAverage($st_id, $term, $year)
    {
        // Convertir term (1-3) en semester (1-2)
        $semester = $term <= 2 ? 1 : 2;
        return self::getSemesterAverage($st_id, $semester, $year);
    }

    public static function getSemesterTotal($st_id, $semester, $year)
    {
        $exam = self::getExamBySemester($semester, $year);
        if (!$exam) return null;
        
        $d = ['exam_id' => $exam->id, 'student_id' => $st_id, 'year' => $year];
        $exr = ExamRecord::where($d)->first();
        
        return $exr ? ($exr->total ?? NULL) : NULL;
    }

    /*Get Term Total (legacy)*/
    public static function getTermTotal($st_id, $term, $year)
    {
        $semester = $term <= 2 ? 1 : 2;
        return self::getSemesterTotal($st_id, $semester, $year);
    }

    public static function getExamBySemester($semester, $year)
    {
        $d = ['semester' => $semester, 'year' => $year];
        return Exam::where($d)->first();
    }

    /*Get Exam By Term (legacy - compatibilité)*/
    public static function getExamByTerm($term, $year)
    {
        // Convertir term en semester
        $semester = $term <= 2 ? 1 : 2;
        return self::getExamBySemester($semester, $year);
    }

    public static function getGradeList($class_type_id)
    {
        $grades = Grade::where(['class_type_id' => $class_type_id])->orderBy('name')->get();

        if($grades->count() < 1){
            $grades = Grade::whereNull('class_type_id')->orderBy('name')->get();
        }
        return $grades;
    }

    /**
     * If Class/Section is Changed in Same Year,
     * Delete Marks/ExamRecord of Previous Class/Section
     *
     * @param int $st_id
     * @param int $class_id
     * @return bool
     * @static
     */
    public static function deleteOldRecord($st_id, $class_id)
    {
        $d = ['student_id' => $st_id, 'year' => self::getCurrentSession()];

        $marks = Mark::where('my_class_id', '<>', $class_id)->where($d);
        if($marks->get()->count() > 0){
            $exr = ExamRecord::where('my_class_id', '<>', $class_id)->where($d);
            $marks->delete();
            $exr->delete();
        }
        return true;
    }

}
