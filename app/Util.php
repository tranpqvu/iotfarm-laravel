<?php
namespace App;

use Illuminate\Support\Facades\DB;

class Util
{

    public static $time_zone = "Asia/Ho_Chi_Minh";
    public static $date_time_format = 'Y-m-d H:i:s';
    public static $gallery_folder = "local/public/upload/gallery";
    public static $answer_file_folder = "uploads/answer_files";
    public static $item_per_page = 10;

    public static function utf8convert($str)
    {

        if (!$str) {
            return false;
        }

        $utf8 = array(

            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',

            'd' => 'đ|Đ',

            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',

            'i' => 'í|ì|ỉ|ĩ|ị|Í|Ì|Ỉ|Ĩ|Ị',

            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',

            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',

            'y' => 'ý|ỳ|ỷ|ỹ|ỵ|Ý|Ỳ|Ỷ|Ỹ|Ỵ',

        );

        foreach ($utf8 as $ascii => $uni) {
            $str = preg_replace("/($uni)/i", $ascii, $str);
        }

        return $str;

    }

    public static function utf8tourl($text)
    {

        $text = strtolower(Util::utf8convert($text));

        $text = str_replace("ß", "ss", $text);

        $text = str_replace("%", "", $text);

        $text = preg_replace("/[^_a-zA-Z0-9 -] /", "", $text);

        $text = str_replace(array('%20', ' '), '-', $text);

        $text = str_replace("----", "-", $text);

        $text = str_replace("---", "-", $text);

        $text = str_replace("--", "-", $text);

        return $text;

    }

    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    public static function getAllImagesInFolder($path, $page, $quantity)
    {
        $images = glob("$path/*.{jpg,png,jpeg,bmp,JPG,PNG,JPEG,BMP}", GLOB_BRACE);
        if (count($images) % $quantity > 0) {
            $total_page = (count($images) / $quantity) + 1;
        } else {
            $total_page = count($images) / $quantity;
        }

        if ($page <= $total_page) {
            $selectedFiles = array_slice($images, ($page - 1) * $quantity, $quantity);
        } else {
            $selectedFiles = array();
        }
        return array("data" => $selectedFiles, "page" => $page, "total_page" => $total_page);
    }

    public static function checkExam($exam_class_id, $exam_id, $user_id)
    {
        date_default_timezone_set(Util::$time_zone);
        $current_year = date("Y");
        $current_date = date(Util::$date_time_format);
        $query = DB::table("users")
        ->select("users.last_name", "users.first_name", "school_years.id as year_id", "school_years.year_open as year_open", "school_years.year_close as year_close", "exams.id as exam_id", "classes.id as class_id", "classes.name as class_name", "exams.title as exam_title", "exam_classes.id as exam_class_id", "exam_classes.time_open as time_open", "exam_classes.time_close as time_close", "exam_classes.time_limit as time_limit", "subjects.id as subject_id", "subjects.name as subject_name")
        ->join("student_class", "users.id", "=", "student_class.student_id")
        ->join('classes', 'classes.id', '=', 'student_class.class_id')
        ->join('school_years', 'school_years.id', '=', 'classes.school_year_id')
        ->join('exam_classes', 'classes.id', '=', 'exam_classes.class_id')
        ->join('exams', 'exams.id', '=', 'exam_classes.exam_id')
        ->join('subjects', 'subjects.id', '=', 'exams.subject_id')
        // ->leftJoin('teacher_class_subject', function($join)
        // {
        //  $join->on('teacher_class_subject.class_id', '=', 'classes.id');
        //  $join->on('teacher_class_subject.teacher_id','=',"users.id");
        //  $join->on('teacher_class_subject.subject_id','=',"subjects.id");
        // })
        ->where("users.id", $user_id)
        ->where("exams.id", $exam_id)
        ->where("exam_classes.id", $exam_class_id)
        ->where("school_years.year_open", "<=", $current_year)
        ->where("school_years.year_close", ">=", $current_year)
        ->where("exam_classes.time_open", "<=", $current_date)
        ->where("exam_classes.time_close", ">=", $current_date)
        ->get()
        ;
        //dd($query->toSql(), $query->getBindings());

        if (count($query) > 0) {
            return $query;
        } else {
            return null;
        }

    }

    public static function checkExamAssessment($exam_class_id, $exam_id, $user_id)
    {
        date_default_timezone_set(Util::$time_zone);
        $current_year = date("Y");
        $current_date = date(Util::$date_time_format);
        $query = DB::table("users")
        ->select("users.last_name", "users.first_name", "school_years.id as year_id", "school_years.year_open as year_open", "school_years.year_close as year_close", "exams.id as exam_id", "classes.id as class_id", "classes.name as class_name", "exams.title as exam_title", "exam_classes.id as exam_class_id", "exam_classes.time_open as time_open", "exam_classes.time_close as time_close", "exam_classes.time_limit as time_limit", "subjects.id as subject_id", "subjects.name as subject_name")
        ->join("student_class", "users.id", "=", "student_class.student_id")
        ->join('classes', 'classes.id', '=', 'student_class.class_id')
        ->join('school_years', 'school_years.id', '=', 'classes.school_year_id')
        ->join('exam_classes', 'classes.id', '=', 'exam_classes.class_id')
        ->join('exams', 'exams.id', '=', 'exam_classes.exam_id')
        ->join('subjects', 'subjects.id', '=', 'exams.subject_id')
        // ->leftJoin('teacher_class_subject', function($join)
        // {
        //  $join->on('teacher_class_subject.class_id', '=', 'classes.id');
        //  $join->on('teacher_class_subject.teacher_id','=',"users.id");
        //  $join->on('teacher_class_subject.subject_id','=',"subjects.id");
        // })
        ->where("users.id", $user_id)
        ->where("exams.id", $exam_id)
        ->where("exam_classes.id", $exam_class_id)
        ->where("school_years.year_open", "<=", $current_year)
        ->where("school_years.year_close", ">=", $current_year)
        ->where("exam_classes.time_pa_open", "<=", $current_date)
        ->where("exam_classes.time_pa_close", ">=", $current_date)
        ->get()
        ;
        //dd($query->toSql(), $query->getBindings());
        //print_r($query);
        if (count($query) > 0) {
            return $query;
        } else {
            return null;
        }

    }


     public static function checkExamAssessmentToReview($exam_class_id, $exam_id, $user_id)
    {
        date_default_timezone_set(Util::$time_zone);
        $current_year = date("Y");
        $current_date = date(Util::$date_time_format);
        $query = DB::table("users")
        ->select("users.last_name", "users.first_name", "school_years.id as year_id", "school_years.year_open as year_open", "school_years.year_close as year_close", "exams.id as exam_id", "classes.id as class_id", "classes.name as class_name", "exams.title as exam_title", "exam_classes.id as exam_class_id", "exam_classes.time_open as time_open", "exam_classes.time_close as time_close", "exam_classes.time_limit as time_limit", "subjects.id as subject_id", "subjects.name as subject_name")
        ->join("student_class", "users.id", "=", "student_class.student_id")
        ->join('classes', 'classes.id', '=', 'student_class.class_id')
        ->join('school_years', 'school_years.id', '=', 'classes.school_year_id')
        ->join('exam_classes', 'classes.id', '=', 'exam_classes.class_id')
        ->join('exams', 'exams.id', '=', 'exam_classes.exam_id')
        ->join('subjects', 'subjects.id', '=', 'exams.subject_id')
        // ->leftJoin('teacher_class_subject', function($join)
        // {
        //  $join->on('teacher_class_subject.class_id', '=', 'classes.id');
        //  $join->on('teacher_class_subject.teacher_id','=',"users.id");
        //  $join->on('teacher_class_subject.subject_id','=',"subjects.id");
        // })
        ->where("users.id", $user_id)
        ->where("exams.id", $exam_id)
        ->where("exam_classes.id", $exam_class_id)
        ->where("school_years.year_open", "<=", $current_year)
        ->where("school_years.year_close", ">=", $current_year)
       // ->where("exam_classes.time_pa_open", "<=", $current_date)
        //->where("exam_classes.time_pa_close", ">=", $current_date)
        ->get()
        ;
        //dd($query->toSql(), $query->getBindings());
        //print_r($query);
        if (count($query) > 0) {
            return $query;
        } else {
            return null;
        }

    }

    public static function checkPeerAssessment($exam_class_id, $exam_id, $student_id)
    {
        $delta = 0;
        $exam_class = DB::table("exam_classes")->where("id", $exam_class_id)->first();
        $check_complete = DB::table('answers')->where('exam_class_id', $exam_class_id)->where('exam_id', $exam_id)->where('student_id', $student_id)->exists();
        if ($exam_class != null && $exam_class->is_assessment == 1 && $check_complete) {
            // $count = DB::table('answers')
            //         ->join("mark_student", "answers.id", "=", "mark_student.answer_id")
            //         ->where("answers.exam_class_id",$exam_class_id)
            //         ->where("answers.exam_id",$exam_id)
            //         ->where("mark_student.student_id", $student_id)
            //         ->count();

            $count = DB::table('answers')
            ->join("mark_student", "answers.id", "=", "mark_student.answer_id")
            ->where("answers.exam_class_id", $exam_class_id)
            ->where("answers.exam_id", $exam_id)
            ->where("mark_student.student_id", $student_id)
            ->count();

            if ($exam_class->assessment_quantity > $count) {
                $delta = $exam_class->assessment_quantity - $count;
            }
        }
        return ($delta);
        // return($exam_class->assessment_quantity);
    }

    public static function checkPeerAssessmentExp($exam_class_id, $exam_id, $student_id)
    {
        $delta = 0;
        $exam_class = DB::table("exam_classes")->where("id", $exam_class_id)->first();
        $check_complete = DB::table('answers')->where('exam_class_id', $exam_class_id)->where('exam_id', $exam_id)->where('student_id', $student_id)->exists();
        if ($exam_class != null && $exam_class->is_assessment == 1 && $check_complete) {
            // $count = DB::table('answers')
            //         ->join("mark_student", "answers.id", "=", "mark_student.answer_id")
            //         ->where("answers.exam_class_id",$exam_class_id)
            //         ->where("answers.exam_id",$exam_id)
            //         ->where("mark_student.student_id", $student_id)
            //         ->count();

            $count = DB::table('answers')
            ->join("mark_student", "answers.id", "=", "mark_student.answer_id")
            ->where("answers.exam_class_id", $exam_class_id)
            ->where("answers.exam_id", $exam_id)
            ->where("mark_student.student_id", $student_id)            
            ->count();
            
            date_default_timezone_set(Util::$time_zone);
            $current_year = date("Y");
            $current_date = date(Util::$date_time_format);
            if($exam_class->time_pa_open <= $current_date && $exam_class->time_pa_close <= $current_date){
                if ($exam_class->assessment_quantity > $count) {
                    $delta = $exam_class->assessment_quantity - $count;
                }
            }else{
                $delta = null;
            }
            // ->where("exam_classes.time_pa_open", "<=", $current_date)
            // ->where("exam_classes.time_pa_close", ">=", $current_date)

            
        }
        return ($delta);
        // return($exam_class->assessment_quantity);
    }


    public static function checkExamComplete($exam_class_id, $exam_id, $student_id)
    {
        $check_complete = DB::table('answers')->where('exam_class_id', $exam_class_id)->where('exam_id', $exam_id)->where('student_id', $student_id)->exists();
        return ($check_complete);
    }

    public static function checkAnswer($exam_class_id, $exam_id, $user_id)
    {
        $query = DB::table("answers")
        ->where("answers.student_id", $user_id)
        ->where("answers.exam_id", $exam_id)
        ->where("answers.exam_class_id", $exam_class_id)
        ->get()
        ;

        if (count($query) > 0) {
            return $query;
        } else {
            return null;
        }

    }

    public static function checkMarkStudent($answer_id, $student_id)
    {
        $query = DB::table("mark_student")
        ->where("mark_student.student_id", $student_id)
        ->where("mark_student.answer_id", $answer_id)
        ->get()
        ;

        if (count($query) > 0) {
            return $query;
        } else {
            return null;
        }

    }

    public static function checkMarkTeacher($answer_id, $teacher_id)
    {
        $query = DB::table("mark_student")
        ->where("mark_student.teacher_id", $teacher_id)
        ->where("mark_student.answer_id", $answer_id)
        ->get()
        ;

        if (count($query) > 0) {
            return $query;
        } else {
            return null;
        }

    }

    public static function checkStartExam($exam_class_id, $user_id)
    {
        date_default_timezone_set(Util::$time_zone);
        $res = null;

        $exam_class = DB::table("exam_classes")
        ->select()
        ->where("exam_classes.id", $exam_class_id)
        ->first()
        ;

        if ($exam_class != null) {
            $query = DB::table("time_start_exam")
            ->select()
            ->where("time_start_exam.student_id", $user_id)
            ->where("time_start_exam.exam_class_id", $exam_class_id)
            ->get()
            ;
            //dd($query->toSql(), $query->getBindings());
            if ($query != null && count($query) > 0) {
                $res = (array) $query[0];
                    if($res != null){
                        $seconds = Util::Compare_Datetime( date(Util::$date_time_format), $res["time_start"]);
                        $res["time_tmp"] = $seconds;
                        $res["flag"] = abs($res["time_limit"]*60) -  $res["time_tmp"] ;
                     }
            } else {

                    $current_date =  date(Util::$date_time_format);

                    $data = array(
                        "exam_class_id" => $exam_class_id,
                        "student_id" => $user_id,
                        "time_limit" => $exam_class->time_limit,
                        "time_start" => $current_date,
                        "time_end" => null,
                    );

                    $id = DB::table("time_start_exam")->insertGetId($data);
                    $insertData = DB::table("time_start_exam")->where('id', $id)->get();

                    if (count($insertData) > 0) {
                        $res = (array) $insertData[0];
                        $res["time_tmp"] = 0;
                        $res["flag"] = abs($res["time_limit"]*60) - $res["time_tmp"];
                    } else {
                        $res = null;
                    }
                }
            }

            return $res;
        }


        public static function checkUpdateStartExam($exam_class_id, $user_id, $date)
        {
            date_default_timezone_set(Util::$time_zone);
        //$current_date =  date(Util::$date_time_format);
            $res = null;

            $exam_class = DB::table("exam_classes")
            ->select()
            ->where("exam_classes.id", $exam_class_id)
            ->first()
            ;

            if ($exam_class != null) {
                $query = DB::table("time_start_exam")
                ->select()
                ->where("time_start_exam.student_id", $user_id)
                ->where("time_start_exam.exam_class_id", $exam_class_id)
                ->get()
                ;
            //dd($query->toSql(), $query->getBindings());
                if ($query != null && count($query) > 0) {
                 $res = DB::table("time_start_exam")->where('id', $query[0]->id)
                 ->update(array("time_start" => date(Util::$date_time_format, $date)));
             }
         }

         return $res;
     }

     public static function checkFinishStartExam($exam_class_id, $user_id, $date)
     {
        date_default_timezone_set(Util::$time_zone);
        //$current_date =  date(Util::$date_time_format);
        $res = null;

        $exam_class = DB::table("exam_classes")
        ->select()
        ->where("exam_classes.id", $exam_class_id)
        ->first()
        ;

        if ($exam_class != null) {
            $query = DB::table("time_start_exam")
            ->select()
            ->where("time_start_exam.student_id", $user_id)
            ->where("time_start_exam.exam_class_id", $exam_class_id)
            ->get()
            ;
            //dd($query->toSql(), $query->getBindings());
            if ($query != null && count($query) > 0) {
             $res = DB::table("time_start_exam")->where('id', $query[0]->id)
             ->update(array("time_end" => date(Util::$date_time_format, $date)));
         }
     }

     return $res;
    }

    public static function Compare_Datetime ($val1, $val2){
         // $val1 = '2014-03-18 10:34:09.939';
         // $val2 = '2014-03-19 10:35:10.940';

        //$res = (intval(abs((new \DateTime($val1))->getTimestamp() - (new \DateTime($val2))->getTimestamp()) / 60));
        $res = (intval(abs((new \DateTime($val1))->getTimestamp() - (new \DateTime($val2))->getTimestamp())));
        return($res);
    }

    /**
     * @param array $columnNames
     * @param array $rows
     * @param string $fileName
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public static function getCsv($columnNames, $rows, $fileName = 'file.csv') {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=" . $fileName,
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
        $callback = function() use ($columnNames, $rows ) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columnNames);
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public static function someOtherControllerFunction() {
        $rows = [['a','b','c'],[1,2,3]];//replace this with your own array of arrays
        $columnNames = ['blah', 'yada', 'hmm'];//replace this with your own array of string column headers        
        return Util::getCsv($columnNames, $rows);
    }

     public static function uploadAnswerFile($original_pic, $user_id){
          //$original_pic = $request->file('original_pic');

            $file_extension=$original_pic->getClientOriginalExtension();
            $file_real_name=$original_pic->getClientOriginalName();
            $filename = time() . "_" . $user_id . "_" . $file_real_name . '.' . $file_extension;

            # upload original image
            Storage::put(Util::$answer_file_folder . '/' . $filename, (string) file_get_contents($original_pic), 'public');

            // # croped image from request.
            // $image_parts = explode(";base64,", $request->input('article_image'));
            // $image_base64 = base64_decode($image_parts[1]);

            // Storage::put('ArticlesImages/croped/' . $filename, (string) $image_base64, 'public');

            // # get image from s3 or local storage.
            // $image_get = Storage::get('ArticlesImages/croped/' . $filename);

            // # resize 50 by 50 1x
            // $image_50_50 = Image::make($image_get)
            //         ->resize(340, 227)
            //         ->encode($file_extension, 80);

            // Storage::put('ArticlesImages/1x/' . $filename, (string) $image_50_50, 'public');

            $file_url = Storage::url(Util::$answer_file_folder . '/' . $filename);
            return(array("file_path" => $file_url, "file_name"=>$filename));
    }

}
