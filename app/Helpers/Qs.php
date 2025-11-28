<?php

namespace App\Helpers;

use App\Models\Setting;
use App\Models\StudentRecord;
use App\Models\Subject;
use Hashids\Hashids;
use Illuminate\Support\Facades\Auth;

class Qs
{
    public static function displayError($errors)
    {
        foreach ($errors as $err) {
            $data[] = $err;
        }
        return '
                <div class="alert alert-danger alert-styled-left alert-dismissible">
									<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
									<span class="font-weight-semibold">Oops!</span> '.
        implode(' ', $data).'
							    </div>
                ';
    }

    public static function getAppCode()
    {
        return self::getSetting('system_title') ?: 'CJ';
    }

    public static function getDefaultUserImage()
    {
        return asset('global_assets/images/user.png');
    }

    /**
     * Vérifie et retourne une photo valide ou l'image par défaut
     */
    public static function getUserPhoto($photo)
    {
        // Si aucune photo n'est définie
        if (!$photo || $photo === '') {
            return self::getDefaultUserImage();
        }

        // Si la photo est une URL complète
        if (filter_var($photo, FILTER_VALIDATE_URL)) {
            $relativePath = str_replace(url('/'), '', $photo);
            $relativePath = ltrim($relativePath, '/');
            if (file_exists(public_path($relativePath))) {
                return $photo;
            }
            return self::getDefaultUserImage();
        }

        // Si c'est un chemin relatif (storage/uploads/...)
        if (str_starts_with($photo, 'storage/') || str_starts_with($photo, '/storage/')) {
            $cleanPath = ltrim($photo, '/');
            if (file_exists(public_path($cleanPath))) {
                return asset($cleanPath);
            }
            return self::getDefaultUserImage();
        }

        // Ancien format ou autre
        if (file_exists(public_path($photo))) {
            return asset($photo);
        }

        return self::getDefaultUserImage();
    }

    public static function getPanelOptions()
    {
        return '    <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                        <a class="list-icons-item" data-action="remove"></a>
                    </div>
                </div>';
    }

    public static function displaySuccess($msg)
    {
        return '
 <div class="alert alert-success alert-bordered">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button> '.
        $msg.'  </div>
                ';
    }

    public static function getTeamSA()
    {
        return ['admin', 'super_admin'];
    }

    public static function getTeamAccount()
    {
        return ['admin', 'super_admin', 'accountant'];
    }

    public static function getTeamSAT()
    {
        return ['admin', 'super_admin', 'teacher'];
    }

    public static function getTeamAcademic()
    {
        return ['admin', 'super_admin', 'teacher', 'student'];
    }

    public static function getTeamAdministrative()
    {
        return ['admin', 'super_admin', 'accountant'];
    }

    public static function hash($id)
    {
        $date = date('dMY').'CJ';
        $hash = new Hashids($date, 14);
        return $hash->encode($id);
    }

    public static function getUserRecord($remove = [])
    {
        $data = ['name', 'email', 'phone', 'phone2', 'dob', 'gender', 'address', 'bg_id', 'nal_id', 'state_id', 'lga_id'];

        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }

    public static function getStaffRecord($remove = [])
    {
        $data = ['emp_date',];

        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }

    public static function getStudentData($remove = [])
    {
        $data = ['my_class_id', 'section_id', 'my_parent_id', 'dorm_id', 'dorm_room_no', 'year_admitted', 'house', 'age'];

        return $remove ? array_values(array_diff($data, $remove)) : $data;

    }

    public static function decodeHash($str, $toString = true)
    {
        $date = date('dMY').'CJ';
        $hash = new Hashids($date, 14);
        $decoded = $hash->decode($str);
        return $toString ? implode(',', $decoded) : $decoded;
    }

    public static function userIsTeamAccount()
    {
        return in_array(Auth::user()->user_type, self::getTeamAccount());
    }

    public static function userIsTeamSA()
    {
        return in_array(Auth::user()->user_type, self::getTeamSA());
    }

    public static function userIsTeamSAT()
    {
        return in_array(Auth::user()->user_type, self::getTeamSAT());
    }

    public static function userIsAcademic()
    {
        return in_array(Auth::user()->user_type, self::getTeamAcademic());
    }

    public static function userIsAdministrative()
    {
        return in_array(Auth::user()->user_type, self::getTeamAdministrative());
    }

    public static function userIsAdmin()
    {
        return Auth::user()->user_type == 'admin';
    }

    public static function getUserType()
    {
        return Auth::user()->user_type;
    }

    public static function userIsSuperAdmin()
    {
        return Auth::user()->user_type == 'super_admin';
    }

    public static function userIsStudent()
    {
        return Auth::user()->user_type == 'student';
    }

    public static function userIsTeacher()
    {
        return Auth::user()->user_type == 'teacher';
    }

    public static function userIsParent()
    {
        return Auth::user()->user_type == 'parent';
    }

    public static function userIsLibrarian()
    {
        return Auth::user()->user_type == 'librarian';
    }

    public static function userIsAccountant()
    {
        return Auth::user()->user_type == 'accountant';
    }

    public static function userIsStaff()
    {
        return in_array(Auth::user()->user_type, self::getStaff());
    }

    public static function getStaff($remove=[])
    {
        $data =  ['super_admin', 'admin', 'teacher', 'accountant', 'librarian'];
        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }

    public static function getAllUserTypes($remove=[])
    {
        $data =  ['super_admin', 'admin', 'teacher', 'accountant', 'librarian', 'student', 'parent'];
        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }

    // Check if User is Head of Super Admins (Untouchable)
    public static function headSA(int $user_id)
    {
        return $user_id === 1;
    }

    public static function userIsPTA()
    {
        return in_array(Auth::user()->user_type, self::getPTA());
    }

    public static function userIsMyChild($student_id, $parent_id)
    {
        $data = ['user_id' => $student_id, 'my_parent_id' =>$parent_id];
        return StudentRecord::where($data)->exists();
    }

    public static function getSRByUserID($user_id)
    {
        return StudentRecord::where('user_id', $user_id)->first();
    }

    public static function getPTA()
    {
        // Tous les utilisateurs qui peuvent modifier leur profil et photo
        return ['super_admin', 'admin', 'teacher', 'parent', 'student', 'accountant', 'librarian'];
    }

    /*public static function filesToUpload($programme)
    {
        return ['birth_cert', 'passport',  'neco_cert', 'waec_cert', 'ref1', 'ref2'];
    }*/

    public static function getPublicUploadPath()
    {
        return 'uploads/';
    }

    public static function getUserUploadPath()
    {
        return 'uploads/'.date('Y').'/'.date('m').'/'.date('d').'/';
    }

    public static function getUploadPath($user_type)
    {
        return 'uploads/'.$user_type.'/';
    }

    public static function getFileMetaData($file)
    {
        //$dataFile['name'] = $file->getClientOriginalName();
        $dataFile['ext'] = $file->getClientOriginalExtension();
        $dataFile['type'] = $file->getClientMimeType();
        $dataFile['size'] = self::formatBytes($file->getSize());
        return $dataFile;
    }

    public static function generateUserCode()
    {
        return substr(uniqid(mt_rand()), -7, 7);
    }

    public static function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');

        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    }

    public static function getSetting($type, $default = null)
    {
        $setting = Setting::where('type', $type)->first();
        return $setting ? $setting->description : $default;
    }

    public static function getCurrentSession()
    {
        return self::getSetting('current_session');
    }

    public static function getNextSession()
    {
        $oy = self::getCurrentSession();
        $old_yr = explode('-', $oy);
        return ++$old_yr[0].'-'.++$old_yr[1];
    }

    public static function getSystemName()
    {
        return self::getSetting('system_name');
    }

    public static function findMyChildren($parent_id)
    {
        return StudentRecord::where('my_parent_id', $parent_id)->with(['user', 'my_class'])->get();
    }

    public static function findTeacherSubjects($teacher_id)
    {
        return Subject::where('teacher_id', $teacher_id)->with('my_class')->get();
    }

    public static function findStudentRecord($user_id)
    {
        return StudentRecord::where('user_id', $user_id)->first();
    }

    public static function getMarkType($class_type)
    {
       switch($class_type){
           case 'J' : return 'junior';
           case 'S' : return 'senior';
           case 'N' : return 'nursery';
           case 'P' : return 'primary';
           case 'PN' : return 'pre_nursery';
           case 'C' : return 'creche';
       }
        return $class_type;
    }

    public static function json($msg, $ok = TRUE, $arr = [])
    {
        return $arr ? response()->json($arr) : response()->json(['ok' => $ok, 'msg' => $msg]);
    }

    public static function jsonStoreOk()
    {
        return self::json(__('msg.store_ok'));
    }

    public static function jsonUpdateOk()
    {
        return self::json(__('msg.update_ok'));
    }

    public static function storeOk($routeName)
    {
        return self::goWithSuccess($routeName, __('msg.store_ok'));
    }

    public static function deleteOk($routeName)
    {
        return self::goWithSuccess($routeName, __('msg.del_ok'));
    }

    public static function updateOk($routeName)
    {
        return self::goWithSuccess($routeName, __('msg.update_ok'));
    }

    public static function goToRoute($goto, $status = 302, $headers = [], $secure = null)
    {
        $data = [];
        $to = (is_array($goto) ? $goto[0] : $goto) ?: 'dashboard';
        if(is_array($goto)){
            array_shift($goto);
            $data = $goto;
        }
        return app('redirect')->to(route($to, $data), $status, $headers, $secure);
    }

    public static function goWithDanger($to = 'dashboard', $msg = NULL)
    {
        $msg = $msg ? $msg : __('msg.rnf');
        return self::goToRoute($to)->with('flash_danger', $msg);
    }

    public static function goWithSuccess($to, $msg)
    {
        return self::goToRoute($to)->with('flash_success', $msg);
    }

    public static function getDaysOfTheWeek()
    {
        return ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    }

    /**
     * Formater un montant avec la devise locale (USD pour RDC)
     */
    public static function formatCurrency($amount, $showSymbol = true)
    {
        $formatted = number_format($amount, 0, ',', ' ');
        return $showSymbol ? $formatted . ' $' : $formatted;
    }

    /**
     * Obtenir le symbole de la devise
     */
    public static function getCurrencySymbol()
    {
        return '$';
    }

    /**
     * Obtenir la devise (alias pour getCurrencySymbol)
     */
    public static function getCurrency()
    {
        return self::getCurrencySymbol();
    }

    /**
     * Obtenir le code de la devise
     */
    public static function getCurrencyCode()
    {
        return 'USD';
    }

    /**
     * Obtenir le nom de la devise
     */
    public static function getCurrencyName()
    {
        return 'Dollar Américain';
    }

    /**
     * Formater le nom complet d'une classe avec option et division
     * Ex: "3e Mécanique A", "JSS 2 Scientifique Blue"
     * 
     * @param object $record - Peut être StudentRecord, ExamSchedule, ou tout objet avec my_class, option, section
     * @return string
     */
    public static function getFullClassName($record)
    {
        if (!$record) {
            return 'N/A';
        }

        $parts = [];
        
        // Nom de la classe
        if (isset($record->my_class) && $record->my_class) {
            $parts[] = $record->my_class->name;
        }
        
        // Option (si existe)
        if (isset($record->option) && $record->option) {
            $parts[] = $record->option->name;
        }
        
        // Division (si existe)
        if (isset($record->section) && $record->section) {
            $parts[] = $record->section->name;
        }
        
        return implode(' ', $parts) ?: 'N/A';
    }

}
