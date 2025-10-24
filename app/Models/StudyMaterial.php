<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'subject_id',
        'my_class_id',
        'uploaded_by',
        'is_public',
        'download_count'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'download_count' => 'integer',
        'file_size' => 'integer'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function myClass()
    {
        return $this->belongsTo(MyClass::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    public function getFileIconAttribute()
    {
        $extension = strtolower(pathinfo($this->file_name, PATHINFO_EXTENSION));
        
        switch ($extension) {
            case 'pdf':
                return 'icon-file-pdf';
            case 'doc':
            case 'docx':
                return 'icon-file-word';
            case 'xls':
            case 'xlsx':
                return 'icon-file-excel';
            case 'ppt':
            case 'pptx':
                return 'icon-file-presentation';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                return 'icon-file-picture';
            case 'mp4':
            case 'avi':
            case 'mov':
                return 'icon-file-video';
            case 'mp3':
            case 'wav':
                return 'icon-file-music';
            default:
                return 'icon-file-text2';
        }
    }

    public function scopeForClass($query, $classId)
    {
        return $query->where('my_class_id', $classId);
    }

    public function scopeForSubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }
}
