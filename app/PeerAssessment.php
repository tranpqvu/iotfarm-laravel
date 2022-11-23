<?php 
namespace App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Util;

class PeerAssessment {

    public static function CalcPeerAssessmentMark ($exam_class_id) {
        $res = [];
        $mark_info = [];
        $exam_class =  DB::table("exam_classes")
        ->select()
        ->where("exam_classes.id", $exam_class_id)
        ->first();

        $students = PeerAssessment::getAllStudentOfClass($exam_class_id);
        $mark_all_teachers = PeerAssessment::getAllTeacherMarkOfExam($exam_class_id);
        $mark_max =  PeerAssessment::getQuestionMaxMark($exam_class_id);
        $exam = PeerAssessment::getExamInfo($exam_class_id);
        if($exam_class!=null){
            $percent_1 = $exam_class->ratio_1!=null ? $exam_class->ratio_1 : 0.6;
            $percent_2 = $exam_class->ratio_2!=null ? $exam_class->ratio_2 : 0.2;
            $percent_3 = $exam_class->ratio_3!=null ? $exam_class->ratio_3 : 0.2;
        }else{
            $percent_1 = 0.6;
            $percent_2 = 0.2;
            $percent_3 = 0.2;
        }
        $is_pa = $exam!=null ? $exam->is_assessment : 0 ;


        foreach ($students as $key => $value) {
            $answer_questions = PeerAssessment::getAnswer($exam_class_id, $value->id);
            $mark_all_student = PeerAssessment::getStudentMark($exam_class_id, $value->id);
            $mark_peer_assessment = PeerAssessment::getAllStudentMarkOfExam($exam_class_id, $value->id, $mark_all_teachers, $mark_max);
            $arr_tmp = PeerAssessment::getQuestionDetailsID($exam_class_id);
            $mark_tmp = [];
            $total = 0;
            $mark_total = 0;
            $tmp = [];
            $has_mark= 0;
            $mark_teacher = 0;
            $mark_student = 0;
            $mark_calc = 0;

            // $mark_1 = [];
            // $mark_2 = [];
            // $mark_3 = [];

            $mark_1 = 0;
            $mark_2 = 0;
            $mark_3 = 0;
    
           
                foreach ($answer_questions as $key_1 => $value_1) {
                    $answer_question_id = $value_1->answer_question_id;

                    if($exam->is_assessment == 1){
                       
                      
                        if(array_key_exists($answer_question_id, $mark_all_student)                           
                           && array_key_exists($value->id, $mark_all_teachers)
                           && array_key_exists($answer_question_id, $mark_all_teachers[$value->id])
                       )
                        {

                         
                            $mark_teacher = $mark_all_teachers[$value->id][$answer_question_id];
                            $mark_student = $mark_all_student[$answer_question_id]["average"];
                            if( array_key_exists($arr_tmp[$answer_question_id], $mark_peer_assessment)){
                                $mark_calc = $mark_peer_assessment[$arr_tmp[$answer_question_id]]["average"];
                            }else{
                                $mark_calc = 0;
                            }
                            
                            
                            $mark_total = $mark_teacher*$percent_1 + $mark_student*$percent_2 + $mark_calc*$percent_3;
                            $total = $total + $mark_total;
                            $mark_1 = $mark_1 + $mark_teacher;
                            $mark_2 = $mark_2 + $mark_student;
                            $mark_3 = $mark_3 + $mark_calc;


                            $mark_tmp[] = $mark_total;
                            $has_mark= 1;
                        }else{
                            if(                        
                                array_key_exists($value->id, $mark_all_teachers)
                                && array_key_exists($answer_question_id, $mark_all_teachers[$value->id])
                            ){
                                $mark_teacher = $mark_all_teachers[$value->id][$answer_question_id];
                              
                                $mark_total = $mark_teacher*$percent_1 + $mark_student*$percent_2 + $mark_calc*$percent_3;
                                $total = $total + $mark_total;
                                $mark_1 = $mark_1 + $mark_teacher;
                                $mark_2 = $mark_2 + $mark_student;
                                $mark_3 = $mark_3 + $mark_calc;
    
    
                                $mark_tmp[] = $mark_total;

                            }else{
                                if(                        
                                    array_key_exists($answer_question_id, $mark_all_student) 
                                ){
                                    $mark_student = $mark_all_student[$answer_question_id]["average"];
                                    $mark_total = $mark_teacher*$percent_1 + $mark_student*$percent_2 + $mark_calc*$percent_3;
                                    $total = $total + $mark_total;
                                    $mark_1 = $mark_1 + $mark_teacher;
                                    $mark_2 = $mark_2 + $mark_student;
                                    $mark_3 = $mark_3 + $mark_calc;
        
        
                                    $mark_tmp[] = $mark_total;
    
                                }
                            }
                        }

                        $tmp[] = array(
                            "m_teacher" => $mark_teacher,
                            "m_student" => $mark_student,
                            "pa" => $mark_calc,
                        );

                    }else{
                        if(
                            array_key_exists($value->id, $mark_all_teachers)
                            && array_key_exists($answer_question_id, $mark_all_teachers[$value->id])
                        ){
                           // Log::info("abc: " . $value->id . " - " . $answer_question_id . " = " .$mark_all_teachers[$value->id][$answer_question_id]);
                            $mark_teacher = $mark_all_teachers[$value->id][$answer_question_id];
                            $mark_1 = $mark_1 + $mark_teacher;
                            $total = $total + $mark_teacher;
                            $has_mark= 1;
                        }
                    }
                

               // $tmp = $mark_all_student;
            }

            $mark_info[] = array(
                "student_id"      => $value->id,
                "first_name"      => $value->first_name,
                "last_name"       => $value->last_name,
                "sex"             => $value->sex,
                "has_mark"        => $has_mark,
                "is_pa"           => $exam->is_assessment,
                // "mark_1"          => implode(",", $mark_1),
                // "mark_2"          => implode(",", $mark_2),
                // "mark_3"          => implode(",", $mark_3),
                "mark_1"          => $mark_1,
                "mark_2"          => $mark_2,
                "mark_3"          => $mark_3,
                "total_mark"      => $total,
                "tmp" => $tmp,
                //"tmp_1" => $mark_tmp,
                "tmp_1" => $mark_all_teachers,
            );

        } // end-foreach

        $res = array(
                "is_pa"       => $is_pa,
                "percent_1"   => $percent_1,
                "percent_2"   => $percent_2,
                "percent_3"   => $percent_3,
                "mark_info"   => $mark_info,

        );

        return($res);

    }

    public static function getAllStudentOfClass($exam_class_id){

        $query = DB::table("exam_classes")
        ->select("users.id", "users.first_name", "users.last_name", "users.sex", )
        ->leftJoin("classes","classes.id","=","exam_classes.class_id")
        ->leftJoin("student_class","student_class.class_id","=","classes.id")
        ->leftJoin("users","users.id","=","student_class.student_id")
        ->where("exam_classes.id", $exam_class_id)
        ->orderBy("users.first_name")
        ->get();
        return($query);
    }

    public static function getAllStudentIDOfClass($exam_class_id){

        $query = DB::table("exam_classes")
        ->select("users.id", "users.first_name", "users.last_name", "users.sex", )
        ->leftJoin("classes","classes.id","=","exam_classes.class_id")
        ->leftJoin("student_class","student_class.class_id","=","classes.id")
        ->leftJoin("users","users.id","=","student_class.student_id")
        ->where("exam_classes.id", $exam_class_id)
        ->orderBy("users.first_name")
        ->get();
        return($query);
    }

    public static function getExamInfo($exam_class_id){
        $query = DB::table("exams")
        ->select("exams.title as exam_title", "exams.mark as exam_mark", "exam_classes.*")
        ->leftJoin("exam_classes","exams.id","=","exam_classes.exam_id")
        ->where("exam_classes.id", $exam_class_id)
        ->first();
        return($query);
    }


    public static function getAnswer($exam_class_id, $student_id){
        $answers = DB::table("answers")
        ->select(
            "answers.id as answer_id",
            "answers.student_id as answer_student_id",
            "answers.exam_id as answer_exam_id",
            "answers.exam_class_id as answer_exam_class_id",
            "answer_questions.id as answer_question_id"
        )
        ->leftJoin("answer_questions","answers.id","=","answer_questions.answer_id")
        ->where("answers.student_id", $student_id)
        ->where("answers.exam_class_id", $exam_class_id)
        ->get();

        return($answers);
    }

    public static function getTeacherMark($answer_id, $student_id){
        $res = array();
        $query = DB::table("mark_teacher")
        ->select("mark_teacher_details.*")
        ->leftJoin("answers","answers.id","=","mark_teacher.answer_id")
        ->leftJoin("mark_teacher_details","mark_teacher.id","=","mark_teacher_details.mark_teacher_id")
        ->where("answers.student_id", $student_id)
        ->where("mark_teacher.answer_id", $answer_id)
        ->get();
        foreach ($query as $key => $value) {
            $res[$value->answer_question_id] = $value->mark;
        }
        return($res);
    }

    public static function getAllTeacherMarkOfExam($exam_class_id){
        $res = array();
        $query = DB::table("answers")
        ->select(
            "answers.id as answer_id", 
            "answers.student_id as answer_student_id", 
            "mark_teacher_details.answer_question_id",
            "mark_teacher_details.mark"
        )
        ->leftJoin("mark_teacher","mark_teacher.answer_id","=","answers.id")
        ->leftJoin("mark_teacher_details","mark_teacher_details.mark_teacher_id","=","mark_teacher.id")
        ->where("answers.exam_class_id", $exam_class_id)
       // ->where()
        ->orderBy("answers.student_id")
        ->get()
        //        ->toSql()
        ;
        //print_r($query);
        foreach ($query as $key => $value) {
            if($value->answer_question_id!=null){
                $res[$value->answer_student_id][$value->answer_question_id] = $value->mark;
            //}else{
               // $res[$value->answer_student_id][$value->answer_question_id] = $value->mark;
            }
        } // end-foreach
       // print_r($res);
        return($res);
    }

  
    public static function getQuestionDetailsID($exam_class_id){
        $res = array();
        $query = DB::table("answers")
        ->select("answer_questions.id", "answer_questions.question_id")
        ->leftJoin("answer_questions","answer_questions.answer_id","=","answers.id")
        ->where("answers.exam_class_id", $exam_class_id)
        ->get()
               // ->toSql()
        ;
        foreach ($query as $key => $value) {
            $res[$value->id] = $value->question_id;
        } // end-foreach
        return($res);
    }

    public static function getQuestionMaxMark($exam_class_id){
        $res = array();
        $query = DB::table("answers")
        ->select("answer_questions.id", "answer_questions.mark")
        ->leftJoin("answer_questions","answer_questions.answer_id","=","answers.id")
        ->where("answers.exam_class_id", $exam_class_id)
        ->groupBy()
        ->get()
               // ->toSql()
        ;
        foreach ($query as $key => $value) {
            $res[$value->id] = $value->mark;
        } // end-foreach
        return($res);
    }


    public static function getAllStudentMarkOfExam($exam_class_id, $student_id, $teacher_mark, $mark_max){
        $res = array();

        $query = DB::table("mark_student")
        ->select("answers.id as answer_id", "answers.student_id as answer_student_id" , "answer_questions.question_id as question_id" , "mark_student_details.answer_question_id", "mark_student_details.mark")
        ->leftJoin("mark_student_details","mark_student_details.mark_student_id","=","mark_student.id")
        ->leftJoin("answer_questions","answer_questions.id","=","mark_student_details.answer_question_id")
        ->leftJoin("answers","answers.id","=","answer_questions.answer_id")
        ->where("answers.exam_class_id", $exam_class_id)
        ->where("mark_student.student_id", $student_id)
        ->where("answers.student_id","!=" ,$student_id)
        ->orderBy("answers.student_id")

       // ->get()
        //    ->toSql()
        ;

        // Log::info($query->toSql());
        // Log::info($exam_class_id);
        // Log::info($student_id);

       // return($query);
        $query = $query->get();
      //  Log::info(print_r($mark_max));
        //Log::info(var_dump($teacher_mark));
        
        foreach ($query as $key => $value) {
          //Log::info(var_dump($value));
           // Log::info($value->answer_id . "  " . $value->answer_student_id . "  " . $value->question_id . "  " . $value->answer_question_id . "  " . $value->mark);
          //Log::info(print_r($teacher_mark[$value->answer_student_id]));
          if(array_key_exists($value->question_id, $res)){
            if(array_key_exists("total_mark", $res[$value->question_id]) && array_key_exists($value->answer_student_id, $teacher_mark) && array_key_exists($value->answer_question_id, $teacher_mark[$value->answer_student_id]) ){
                $res[$value->question_id]["total_mark"] = $res[$value->question_id]["total_mark"] + PeerAssessment::calcMarkByFormula($mark_max[$value->answer_question_id], $teacher_mark[$value->answer_student_id][$value->answer_question_id] , $value->mark)  ;
            }else{
                if(array_key_exists($value->answer_student_id, $teacher_mark) && array_key_exists($value->answer_question_id, $teacher_mark[$value->answer_student_id]) ){
           
                    $res[$value->question_id]["total_mark"] = PeerAssessment::calcMarkByFormula($mark_max[$value->answer_question_id], $teacher_mark[$value->answer_student_id][$value->answer_question_id] , $value->mark);
                }
            }

            if(array_key_exists("total_count", $res[$value->question_id])){
                $res[$value->question_id]["total_count"]=$res[$value->question_id]["total_count"]+1;
            }else{
                $res[$value->question_id]["total_count"]=1;
            }
        }else{
            $res[$value->question_id]["total_mark"] = PeerAssessment::calcMarkByFormula($mark_max[$value->answer_question_id], $teacher_mark[$value->answer_student_id][$value->answer_question_id] , $value->mark);
            $res[$value->question_id]["total_count"]=1;
        }

        $res[$value->question_id]["average"] =  $res[$value->question_id]["total_mark"]/$res[$value->question_id]["total_count"];

        } // end-foreach


      //  Log::info(var_dump($res));
        return($res);

    }

   
    public static function getStudentMark($exam_class_id, $student_id){
        $res = array();
        $query = DB::table("mark_student")
        ->select("mark_student_details.*")
        ->leftJoin("answers","answers.id","=","mark_student.answer_id")
        ->leftJoin("mark_student_details","mark_student.id","=","mark_student_details.mark_student_id")
        ->where("answers.student_id", $student_id)
        ->where("answers.exam_class_id", $exam_class_id)
                //->where("mark_student.answer_id", $answer_id)
        ->get();

        foreach ($query as $key => $value) {
            if(array_key_exists($value->answer_question_id, $res)){
                if(array_key_exists("total_mark", $res[$value->answer_question_id])){
                    $res[$value->answer_question_id]["total_mark"] = $res[$value->answer_question_id]["total_mark"] + $value->mark ;
                }else{
                    $res[$value->answer_question_id]["total_mark"] = $value->mark;
                }

                if(array_key_exists("total_count", $res[$value->answer_question_id])){
                    $res[$value->answer_question_id]["total_count"]=$res[$value->answer_question_id]["total_count"]+1;
                }else{
                    $res[$value->answer_question_id]["total_count"]=1;
                }
            }else{
                $res[$value->answer_question_id]["total_mark"] = $value->mark;
                $res[$value->answer_question_id]["total_count"]=1;
            }


            $res[$value->answer_question_id]["average"] =  $res[$value->answer_question_id]["total_mark"]/$res[$value->answer_question_id]["total_count"];

        }
        return($res);
    }

    public static function calcMarkByFormula($max, $mark_teacher, $mark_student){
       // Log::info('------------------ calcMarkByFormula -------------------');

        $res = $max - sqrt(pow($mark_teacher - $mark_student, 2));
        //Log::info($max . " - " . $mark_teacher . " - " . $mark_student . " = " . $res);
        return($res);
    }

  

}

?>
