<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Requests\TimeTable\TSRequest;
use App\Http\Requests\TimeTable\TTRecordRequest;
use App\Http\Requests\TimeTable\TTRequest;
use App\Models\Setting;
use App\Repositories\ExamRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\TimeTableRepo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\TimetableImport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class TimeTableController extends Controller
{
    protected $tt, $my_class, $exam, $year;

    public function __construct(TimeTableRepo $tt, MyClassRepo $mc, ExamRepo $exam)
    {
        $this->tt = $tt;
        $this->my_class = $mc;
        $this->exam = $exam;
        $this->year = Qs::getCurrentSession();
    }

    public function index()
    {
        $d['exams'] = $this->exam->getExam(['year' => $this->year]);
        $d['my_classes'] = $this->my_class->all();
        $d['tt_records'] = $this->tt->getAllRecords();

        return view('pages.support_team.timetables.index', $d);
    }

    public function manage($ttr_id)
    {
        $d['ttr_id'] = $ttr_id;
        $d['ttr'] = $ttr = $this->tt->findRecord($ttr_id);
        $d['time_slots'] = $this->tt->getTimeSlotByTTR($ttr_id);
        $d['ts_existing'] = $this->tt->getExistingTS($ttr_id);
        $d['subjects'] = $this->my_class->getSubject(['my_class_id' => $ttr->my_class_id])->get();
        $d['my_class'] = $this->my_class->find($ttr->my_class_id);

        if($ttr->exam_id){
            $d['exam_id'] = $ttr->exam_id;
            $d['exam'] = $this->exam->find($ttr->exam_id);
        }

        $d['tts'] = $this->tt->getTimeTable(['ttr_id' => $ttr_id]);

        return view('pages.support_team.timetables.manage', $d);
    }

    public function store(TTRequest $req)
    {
        $data = $req->all();
        $tms = $this->tt->findTimeSlot($req->ts_id);
        $d_date = $req->exam_date ?? $req->day;
        $data['timestamp_from'] = strtotime($d_date.' '.$tms->time_from);
        $data['timestamp_to'] = strtotime($d_date.' '.$tms->time_to);

        $this->tt->create($data);

        return Qs::jsonStoreOk();
    }

    public function update(TTRequest $req, $tt_id)
    {
        $data = $req->all();
        $tms = $this->tt->findTimeSlot($req->ts_id);
        $d_date = $req->exam_date ?? $req->day;
        $data['timestamp_from'] = strtotime($d_date.' '.$tms->time_from);
        $data['timestamp_to'] = strtotime($d_date.' '.$tms->time_to);

        $this->tt->update($tt_id, $data);

        return back()->with('flash_success', __('msg.update_ok'));

    }

    public function delete($tt_id)
    {
        $this->tt->delete($tt_id);
        return back()->with('flash_success', __('msg.delete_ok'));
    }

    /*********** TIME SLOTS *************/

    public function store_time_slot(TSRequest $req)
    {
        $data = $req->all();
        $data['time_from'] = $tf =$req->hour_from.':'.$req->min_from.' '.$req->meridian_from;
        $data['time_to'] = $tt = $req->hour_to.':'.$req->min_to.' '.$req->meridian_to;
        $data['timestamp_from'] = strtotime($tf);
        $data['timestamp_to'] = strtotime($tt);
        $data['full'] = $tf.' - '.$tt;

        if($tf == $tt){
            return response()->json(['msg' => __('msg.invalid_time_slot'), 'ok' => FALSE]);
        }

        $this->tt->createTimeSlot($data);
        return Qs::jsonStoreOk();
    }

    public function use_time_slot(Request $req, $ttr_id)
    {
        $this->validate($req, ['ttr_id' => 'required'], [], ['ttr_id' => 'TimeTable Record']);

        $d = [];  //  Empty Current Time Slot Before Adding New
        $this->tt->deleteTimeSlots(['ttr_id' => $ttr_id]);
        $time_slots = $this->tt->getTimeSlotByTTR($req->ttr_id)->toArray();

        foreach($time_slots as $ts){
            $ts['ttr_id'] = $ttr_id;
            $this->tt->createTimeSlot($ts);
        }

        return redirect()->route('ttr.manage', $ttr_id)->with('flash_success', __('msg.update_ok'));

    }

    public function edit_time_slot($ts_id)
    {
        $d['tms'] = $this->tt->findTimeSlot($ts_id);
        return view('pages.support_team.timetables.time_slots.edit', $d);
    }

    public function update_time_slot(TSRequest $req, $ts_id)
    {
        $data = $req->all();
        $data['time_from'] = $tf =$req->hour_from.':'.$req->min_from.' '.$req->meridian_from;
        $data['time_to'] = $tt = $req->hour_to.':'.$req->min_to.' '.$req->meridian_to;
        $data['timestamp_from'] = strtotime($tf);
        $data['timestamp_to'] = strtotime($tt);
        $data['full'] = $tf.' - '.$tt;

        if($tf == $tt){
            return back()->with('flash_danger', __('msg.invalid_time_slot'));
        }

        $this->tt->updateTimeSlot($ts_id, $data);
        return redirect()->route('ttr.manage', $req->ttr_id)->with('flash_success', __('msg.update_ok'));
    }

    public function delete_time_slot($ts_id)
    {
        $this->tt->deleteTimeSlot($ts_id);
        return back()->with('flash_success', __('msg.delete_ok'));
    }


    /*********** RECORDS *************/

    public function edit_record($ttr_id)
    {
        $d['ttr'] = $ttr = $this->tt->findRecord($ttr_id);
        $d['exams'] = $this->exam->getExam(['year' => $ttr->year]);
        $d['my_classes'] = $this->my_class->all();

        return view('pages.support_team.timetables.edit', $d);
    }

    public function show_record($ttr_id)
    {
        $d_time = [];
        $d['ttr'] = $ttr = $this->tt->findRecord($ttr_id);
        $d['ttr_id'] = $ttr_id;
        $d['my_class'] = $this->my_class->find($ttr->my_class_id);

        $d['time_slots'] = $tms = $this->tt->getTimeSlotByTTR($ttr_id);
        $d['tts'] = $tts = $this->tt->getTimeTable(['ttr_id' => $ttr_id]);

        if($ttr->exam_id){
            $d['exam_id'] = $ttr->exam_id;
            $d['exam'] = $this->exam->find($ttr->exam_id);
            $d['days'] = $days = $tts->unique('exam_date')->pluck('exam_date');
            $d_date = 'exam_date';
        }

        else{
            $d['days'] = $days = $tts->unique('day')->pluck('day');
            $d_date = 'day';
        }

        foreach ($days as $day) {
            foreach ($tms as $tm) {
                $d_time[] = ['day' => $day, 'time' => $tm->full, 'subject' => $tts->where('ts_id', $tm->id)->where($d_date, $day)->first()->subject->name ?? NULL ];
            }
        }

        $d['d_time'] = collect($d_time);

        return view('pages.support_team.timetables.show', $d);
    }
    public function print_record($ttr_id)
    {
        $d_time = [];
        $d['ttr'] = $ttr = $this->tt->findRecord($ttr_id);
        $d['ttr_id'] = $ttr_id;
        $d['my_class'] = $this->my_class->find($ttr->my_class_id);

        $d['time_slots'] = $tms = $this->tt->getTimeSlotByTTR($ttr_id);
        $d['tts'] = $tts = $this->tt->getTimeTable(['ttr_id' => $ttr_id]);

        if($ttr->exam_id){
            $d['exam_id'] = $ttr->exam_id;
            $d['exam'] = $this->exam->find($ttr->exam_id);
            $d['days'] = $days = $tts->unique('exam_date')->pluck('exam_date');
            $d_date = 'exam_date';
        }

        else{
            $d['days'] = $days = $tts->unique('day')->pluck('day');
            $d_date = 'day';
        }

        foreach ($days as $day) {
            foreach ($tms as $tm) {
                $d_time[] = ['day' => $day, 'time' => $tm->full, 'subject' => $tts->where('ts_id', $tm->id)->where($d_date, $day)->first()->subject->name ?? NULL ];
            }
        }

        $d['d_time'] = collect($d_time);
        $d['s'] = Setting::all()->flatMap(function($s){
            return [$s->type => $s->description];
        });

        return view('pages.support_team.timetables.print', $d);
    }

    public function store_record(TTRecordRequest $req)
    {
        $data = $req->all();
        $data['year'] = $this->year;
        $this->tt->createRecord($data);

        return Qs::jsonStoreOk();
    }

    public function update_record(TTRecordRequest $req, $id)
    {
        $data = $req->all();
        $this->tt->updateRecord($id, $data);

        return Qs::jsonUpdateOk();
    }

    public function delete_record($ttr_id)
    {
        $this->tt->deleteRecord($ttr_id);
        return back()->with('flash_success', __('msg.delete_ok'));
    }

    /*********** IMPORT/EXPORT EXCEL *************/

    /**
     * Download Excel template for timetable import
     */
    public function download_template($ttr_id)
    {
        $ttr = $this->tt->findRecord($ttr_id);
        $my_class = $this->my_class->find($ttr->my_class_id);
        $subjects = $this->my_class->getSubject(['my_class_id' => $ttr->my_class_id])->get();

        // Create new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'Jour');
        $sheet->setCellValue('B1', 'Créneau Horaire');
        $sheet->setCellValue('C1', 'Matière');

        // Style headers
        $headerStyle = [
            'font' => ['bold' => true, 'size' => 12],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50']
            ],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A1:C1')->applyFromArray($headerStyle);

        // Add example data
        $sheet->setCellValue('A2', 'Monday');
        $sheet->setCellValue('B2', '08:00 AM - 09:00 AM');
        $sheet->setCellValue('C2', $subjects->first()->name ?? 'Mathématiques');

        $sheet->setCellValue('A3', 'Monday');
        $sheet->setCellValue('B3', '09:00 AM - 10:00 AM');
        $sheet->setCellValue('C3', $subjects->skip(1)->first()->name ?? 'Français');

        // Add instructions sheet
        $instructionsSheet = $spreadsheet->createSheet();
        $instructionsSheet->setTitle('Instructions');
        $instructionsSheet->setCellValue('A1', 'INSTRUCTIONS D\'IMPORTATION');
        $instructionsSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $instructionsSheet->setCellValue('A3', 'Format du fichier:');
        $instructionsSheet->setCellValue('A4', '1. Colonne A: Jour de la semaine (Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday)');
        $instructionsSheet->setCellValue('A5', '2. Colonne B: Créneau horaire (Format: HH:MM AM/PM - HH:MM AM/PM ou HH:MM - HH:MM)');
        $instructionsSheet->setCellValue('A6', '3. Colonne C: Nom de la matière (doit exister dans le système)');

        $instructionsSheet->setCellValue('A8', 'Matières disponibles pour ' . $my_class->name . ':');
        $row = 9;
        foreach ($subjects as $subject) {
            $instructionsSheet->setCellValue('A' . $row, '- ' . $subject->name);
            $row++;
        }

        $instructionsSheet->setCellValue('A' . ($row + 1), 'Exemples de créneaux horaires valides:');
        $instructionsSheet->setCellValue('A' . ($row + 2), '- 08:00 AM - 09:00 AM');
        $instructionsSheet->setCellValue('A' . ($row + 3), '- 8:00 AM - 9:00 AM');
        $instructionsSheet->setCellValue('A' . ($row + 4), '- 14:00 - 15:00');

        // Auto-size columns
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $instructionsSheet->getColumnDimension('A')->setWidth(80);

        // Set active sheet back to first sheet
        $spreadsheet->setActiveSheetIndex(0);

        // Generate filename
        $filename = 'emploi_du_temps_template_' . str_replace(' ', '_', $my_class->name) . '.xlsx';

        // Create writer and download
        $writer = new Xlsx($spreadsheet);
        
        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Import timetable from Excel file
     */
    public function import_timetable(Request $req, $ttr_id)
    {
        $req->validate([
            'timetable_file' => 'required|file|mimes:xlsx,xls|max:2048'
        ]);

        $ttr = $this->tt->findRecord($ttr_id);
        $file = $req->file('timetable_file');
        
        // Save file temporarily
        $path = $file->store('temp');
        $full_path = Storage::path($path);

        try {
            // Import
            $importer = new TimetableImport($ttr_id, $ttr->my_class_id);
            $result = $importer->import($full_path);

            // Delete temporary file using Storage facade
            if (Storage::exists($path)) {
                Storage::delete($path);
            }

            if ($result['success']) {
                return back()->with('flash_success', 
                    "Import réussi! {$result['imported']} cours importés, {$result['time_slots_created']} créneaux horaires créés."
                );
            } else {
                $error_message = "Erreurs lors de l'import:<br>" . implode("<br>", $result['errors']);
                return back()->with('flash_danger', $error_message);
            }
        } catch (\Exception $e) {
            // Delete temporary file in case of error
            if (Storage::exists($path)) {
                Storage::delete($path);
            }
            
            return back()->with('flash_danger', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    /**
     * Export current timetable to Excel
     */
    public function export_timetable($ttr_id)
    {
        $ttr = $this->tt->findRecord($ttr_id);
        $my_class = $this->my_class->find($ttr->my_class_id);
        $time_slots = $this->tt->getTimeSlotByTTR($ttr_id);
        $timetables = $this->tt->getTimeTable(['ttr_id' => $ttr_id]);

        // Create new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set title
        $sheet->setCellValue('A1', 'Emploi du temps - ' . $my_class->name);
        $sheet->mergeCells('A1:C1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Set headers
        $sheet->setCellValue('A3', 'Jour');
        $sheet->setCellValue('B3', 'Créneau Horaire');
        $sheet->setCellValue('C3', 'Matière');

        // Style headers
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50']
            ],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A3:C3')->applyFromArray($headerStyle);

        // Add data
        $row = 4;
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        
        foreach ($days as $day) {
            $day_timetables = $timetables->where('day', $day)->sortBy('timestamp_from');
            
            foreach ($day_timetables as $tt) {
                if ($tt->time_slot && $tt->subject) {
                    $sheet->setCellValue('A' . $row, $day);
                    $sheet->setCellValue('B' . $row, $tt->time_slot->full);
                    $sheet->setCellValue('C' . $row, $tt->subject->name);
                    $row++;
                }
            }
        }

        // Auto-size columns
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Generate filename
        $filename = 'emploi_du_temps_' . str_replace(' ', '_', $my_class->name) . '_' . date('Y-m-d') . '.xlsx';

        // Create writer and download
        $writer = new Xlsx($spreadsheet);
        
        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
