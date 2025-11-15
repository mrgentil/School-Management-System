<?php

namespace App\Imports;

use App\Models\TimeTable;
use App\Models\TimeSlot;
use App\Models\Subject;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TimetableImport
{
    protected $ttr_id;
    protected $my_class_id;
    protected $errors = [];
    protected $success_count = 0;
    protected $time_slots_created = [];
    
    public function __construct($ttr_id, $my_class_id)
    {
        $this->ttr_id = $ttr_id;
        $this->my_class_id = $my_class_id;
    }
    
    /**
     * Import timetable from Excel file
     */
    public function import($file_path)
    {
        try {
            $spreadsheet = IOFactory::load($file_path);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            // Skip header row
            array_shift($rows);
            
            // Validate all rows first
            $validated_data = $this->validateRows($rows);
            
            if (!empty($this->errors)) {
                return [
                    'success' => false,
                    'errors' => $this->errors,
                    'imported' => 0
                ];
            }
            
            // Import data in transaction
            DB::beginTransaction();
            
            try {
                foreach ($validated_data as $data) {
                    $this->importRow($data);
                }
                
                DB::commit();
                
                return [
                    'success' => true,
                    'imported' => $this->success_count,
                    'time_slots_created' => count($this->time_slots_created),
                    'errors' => []
                ];
                
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'errors' => ['Erreur lors de la lecture du fichier: ' . $e->getMessage()],
                'imported' => 0
            ];
        }
    }
    
    /**
     * Validate all rows
     */
    protected function validateRows($rows)
    {
        $validated_data = [];
        $row_number = 2; // Start at 2 (after header)
        
        foreach ($rows as $row) {
            // Skip empty rows
            if (empty(array_filter($row))) {
                $row_number++;
                continue;
            }
            
            $day = trim($row[0] ?? '');
            $time_slot = trim($row[1] ?? '');
            $subject_name = trim($row[2] ?? '');
            
            // Normalize spaces (remove non-breaking spaces and extra whitespace)
            $time_slot = preg_replace('/\s+/u', ' ', $time_slot);
            
            // Validate required fields
            if (empty($day) || empty($time_slot) || empty($subject_name)) {
                $this->errors[] = "Ligne {$row_number}: Tous les champs sont requis (Jour, Créneau, Matière)";
                $row_number++;
                continue;
            }
            
            // Validate day
            $valid_days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            
            // Skip header rows (if day is "Jour" or other header text)
            if (in_array(strtolower($day), ['jour', 'day', 'jours', 'days'])) {
                $row_number++;
                continue;
            }
            
            if (!in_array($day, $valid_days)) {
                $this->errors[] = "Ligne {$row_number}: Jour invalide '{$day}'. Utilisez: " . implode(', ', $valid_days);
                $row_number++;
                continue;
            }
            
            // Parse time slot (format: "08:00 - 09:00" or "8:00 AM - 9:00 AM")
            $time_parts = $this->parseTimeSlot($time_slot);
            if (!$time_parts) {
                $this->errors[] = "Ligne {$row_number}: Format de créneau horaire invalide '{$time_slot}'. Utilisez: HH:MM - HH:MM ou HH:MM AM/PM - HH:MM AM/PM";
                $row_number++;
                continue;
            }
            
            // Validate subject exists for this class
            $subject = Subject::where('name', $subject_name)
                ->where('my_class_id', $this->my_class_id)
                ->first();
                
            if (!$subject) {
                $this->errors[] = "Ligne {$row_number}: La matière '{$subject_name}' n'existe pas pour cette classe";
                $row_number++;
                continue;
            }
            
            $validated_data[] = [
                'day' => $day,
                'time_from' => $time_parts['time_from'],
                'time_to' => $time_parts['time_to'],
                'hour_from' => $time_parts['hour_from'],
                'min_from' => $time_parts['min_from'],
                'meridian_from' => $time_parts['meridian_from'],
                'hour_to' => $time_parts['hour_to'],
                'min_to' => $time_parts['min_to'],
                'meridian_to' => $time_parts['meridian_to'],
                'subject_id' => $subject->id,
                'row_number' => $row_number
            ];
            
            $row_number++;
        }
        
        return $validated_data;
    }
    
    /**
     * Parse time slot string
     */
    protected function parseTimeSlot($time_slot)
    {
        // Remove extra spaces
        $time_slot = preg_replace('/\s+/', ' ', trim($time_slot));
        
        // Try to match format: "08:00 - 09:00" or "8:00 AM - 9:00 AM"
        if (preg_match('/^(\d{1,2}):(\d{2})\s*(AM|PM)?\s*-\s*(\d{1,2}):(\d{2})\s*(AM|PM)?$/i', $time_slot, $matches)) {
            $hour_from = (int)$matches[1];
            $min_from = $matches[2];
            $meridian_from = strtoupper($matches[3] ?? '');
            $hour_to = (int)$matches[4];
            $min_to = $matches[5];
            $meridian_to = strtoupper($matches[6] ?? '');
            
            // If no meridian specified, assume 24-hour format
            if (empty($meridian_from)) {
                // Convert to 12-hour format
                if ($hour_from >= 12) {
                    $meridian_from = 'PM';
                    if ($hour_from > 12) $hour_from -= 12;
                } else {
                    $meridian_from = 'AM';
                    if ($hour_from == 0) $hour_from = 12;
                }
            }
            
            if (empty($meridian_to)) {
                if ($hour_to >= 12) {
                    $meridian_to = 'PM';
                    if ($hour_to > 12) $hour_to -= 12;
                } else {
                    $meridian_to = 'AM';
                    if ($hour_to == 0) $hour_to = 12;
                }
            }
            
            // Validate hours and minutes
            if ($hour_from < 1 || $hour_from > 12 || $hour_to < 1 || $hour_to > 12) {
                return false;
            }
            
            if (!preg_match('/^\d{2}$/', $min_from) || !preg_match('/^\d{2}$/', $min_to)) {
                return false;
            }
            
            $time_from = $hour_from . ':' . $min_from . ' ' . $meridian_from;
            $time_to = $hour_to . ':' . $min_to . ' ' . $meridian_to;
            
            return [
                'time_from' => $time_from,
                'time_to' => $time_to,
                'hour_from' => $hour_from,
                'min_from' => $min_from,
                'meridian_from' => $meridian_from,
                'hour_to' => $hour_to,
                'min_to' => $min_to,
                'meridian_to' => $meridian_to
            ];
        }
        
        return false;
    }
    
    /**
     * Import a single row
     */
    protected function importRow($data)
    {
        // Find or create time slot
        $time_slot = $this->findOrCreateTimeSlot($data);
        
        // Check if timetable entry already exists
        $existing = TimeTable::where('ttr_id', $this->ttr_id)
            ->where('ts_id', $time_slot->id)
            ->where('day', $data['day'])
            ->first();
            
        if ($existing) {
            // Update existing entry
            $existing->update([
                'subject_id' => $data['subject_id'],
                'timestamp_from' => strtotime($data['day'] . ' ' . $data['time_from']),
                'timestamp_to' => strtotime($data['day'] . ' ' . $data['time_to'])
            ]);
        } else {
            // Create new entry
            TimeTable::create([
                'ttr_id' => $this->ttr_id,
                'ts_id' => $time_slot->id,
                'subject_id' => $data['subject_id'],
                'day' => $data['day'],
                'timestamp_from' => strtotime($data['day'] . ' ' . $data['time_from']),
                'timestamp_to' => strtotime($data['day'] . ' ' . $data['time_to'])
            ]);
        }
        
        $this->success_count++;
    }
    
    /**
     * Find or create time slot
     */
    protected function findOrCreateTimeSlot($data)
    {
        $timestamp_from = strtotime($data['time_from']);
        $timestamp_to = strtotime($data['time_to']);
        
        // Try to find existing time slot
        $time_slot = TimeSlot::where('ttr_id', $this->ttr_id)
            ->where('timestamp_from', $timestamp_from)
            ->where('timestamp_to', $timestamp_to)
            ->first();
            
        if (!$time_slot) {
            // Create new time slot
            $time_slot = TimeSlot::create([
                'ttr_id' => $this->ttr_id,
                'hour_from' => $data['hour_from'],
                'min_from' => $data['min_from'],
                'meridian_from' => $data['meridian_from'],
                'hour_to' => $data['hour_to'],
                'min_to' => $data['min_to'],
                'meridian_to' => $data['meridian_to'],
                'time_from' => $data['time_from'],
                'time_to' => $data['time_to'],
                'timestamp_from' => $timestamp_from,
                'timestamp_to' => $timestamp_to,
                'full' => $data['time_from'] . ' - ' . $data['time_to']
            ]);
            
            $this->time_slots_created[] = $time_slot->id;
        }
        
        return $time_slot;
    }
    
    /**
     * Get errors
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
