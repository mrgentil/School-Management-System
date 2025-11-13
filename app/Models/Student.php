<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id', 'class_id', 'section_id', 'admission_number', 'guardian_name', 'guardian_phone', 'address', 'date_of_birth', 'gender'
    ];
    
    /**
     * Get the class that owns the student.
     */
    public function myClass()
    {
        return $this->belongsTo(MyClass::class, 'class_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class, 'student_id');
    }

    public function paymentRecords()
    {
        return $this->hasMany(\App\Models\PaymentRecord::class, 'student_id');
    }

    public function receipts()
    {
        return $this->hasManyThrough(
            \App\Models\Receipt::class,
            \App\Models\PaymentRecord::class,
            'student_id', // Clé étrangère sur payment_records (student_id = user_id)
            'pr_id',     // Clé étrangère sur receipts
            'user_id',   // Clé locale sur students
            'id'         // Clé locale sur payment_records
        );
    }

    public function fee_assignments()
    {
        return $this->hasMany(\App\Models\Finance\FeeAssignment::class, 'student_id');
    }

    public function attendances()
    {
        return $this->hasMany(\App\Models\Attendance\Attendance::class, 'student_id');
    }

    public function assignments()
    {
        return $this->belongsToMany(
            \App\Models\Assignment\Assignment::class,
            'assignment_submissions',
            'student_id',
            'assignment_id'
        )->withPivot('submission', 'submitted_at', 'marks', 'feedback');
    }

    public function bookRequests()
    {
        return $this->hasMany(\App\Models\BookRequest::class, 'student_id', 'user_id');
    }

    /**
     * Accesseur pour my_class_id (alias de class_id)
     */
    public function getMyClassIdAttribute()
    {
        return $this->class_id;
    }

    /**
     * Accesseur pour section_id depuis student_records
     */
    public function getSectionIdAttribute()
    {
        // Récupérer depuis student_records si disponible
        $record = \App\Models\StudentRecord::where('user_id', $this->user_id)->first();
        return $record ? $record->section_id : $this->attributes['section_id'] ?? null;
    }
}
