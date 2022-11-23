<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ManagerController;
use App\Models\User;
use App\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MarkController extends ManagerController
{
     private $table_name = "answers";
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function new_index($id)
    {
        return view('admin.mark.list')
            ->with('exam_class_id', $id)
        ;
    }

    public function find(Request $request)
    {
        $exam_class_id = intval($request->input("exam_class_id"));
        $current = intval($request->input("current"));
        $rowCount = intval($request->input("rowCount"));
        $searchPhrase = $request->input("searchPhrase");

        $query = DB::table($this->table_name)
            ->leftJoin('users', $this->table_name . '.student_id', '=', 'users.id')
            ->leftJoin('exams', $this->table_name . '.exam_id', '=', 'exams.id')
            ->leftJoin('subjects', 'exams.subject_id', '=', 'subjects.id')
            ->leftJoin('exam_classes', $this->table_name.'.exam_class_id', '=', 'exam_classes.id')
            ->leftJoin('classes','exam_classes.class_id', '=', 'classes.id')
            ;

        $query->where($this->table_name . ".exam_class_id", $exam_class_id);

        // $query->where($this->table_name . ".created_by", auth()->user()->id);

        //search
        if (!empty($searchPhrase)) {
            $query->where("users.first_name", "LIKE", '%' . $searchPhrase . "%")
               // ->orWhere("classes.name", "LIKE", '%' . $searchPhrase . "%")
               // ->orWhere("subjects.name", "LIKE", '%' . $searchPhrase . "%")
            ;
        }

        //sort
        if (isset($request->sort) && is_array( $request->sort)) {
            foreach ($request->sort as $key => $value) {
                switch ($key) {
                    case "first_name":
                        $query->orderBy("users." . $key, $value);
                        break;
                }
            }
        } else {
            $query->orderBy("users.first_name", "asc")
                  ->orderBy($this->table_name . ".created_at", "asc")
                  ;
        }

        // print_r((string)$query);exit;

        $data = $query->get();
        $total = count($data);
        if ($rowCount > 1 && $current <= $total) {
            $limit = $rowCount * ($current - 1);
            $query->limit($rowCount)->offset($limit);
        } else {
            // $query->limit($rowCount);
        }
        $data = $query->select($this->table_name. '.id as answer_id' , $this->table_name. '.time_start',$this->table_name. '.time_end',$this->table_name. '.subject_name as subject_name', $this->table_name. '.exam_title as exam_title',"users.first_name", "users.last_name" , 'exams.id as exam_id', 'exams.title',"classes.name as class_name", $this->table_name.'.created_at', $this->table_name.'.created_by', $this->table_name.'.modified_at', $this->table_name.'.modified_by')
            ->get();

        $json = array(
            "current" => $current,
            "rowCount" => $rowCount,
            "total" => intval($total),
            "rows" => []
        );

        if (count($data) > 0) {
            foreach ($data as $key => $item) {
                $mark_info =  DB::table('mark_teacher')
                ->where('answer_id', $item->answer_id)
                ->first();

                $created_by = null;
                $updated_by = null;
                $created_at = "";
                $updated_at = "";

                if($mark_info!=null){
                    $created_by = User::find($mark_info->created_by);
                    $updated_by = User::find($mark_info->updated_by);
                    $created_at = $mark_info->created_at;
                    $updated_at = $mark_info->updated_at;
                }

                $mark = $this->getMark($item->answer_id);
                $json['rows'][] = array(
                    "id" => $item->answer_id,
                    "exam_title" => $item->exam_title,
                    "last_name" => $item->last_name,
                    "first_name" => $item->first_name,
                    "has_mark" => $mark,
                    "total_mark" => $mark>=0?$mark:"",
                    "class_name" => $item->class_name,
                    "subject_name" => $item->subject_name,
                    "created_at" => $created_at,
                    "updated_at" => $updated_at,
                    "created_by" => $created_by != null ? $created_by->last_name . " " . $created_by->first_name : "",
                    "updated_by" => $updated_by != null ? $updated_by->last_name . " " . $updated_by->first_name  : "",
                    //date('Y-m-d H:i:s')
                );
            }
        }
        return response()->json($json);
    }

    public function markTeacher($answer_id)
    {

        $answer = DB::table($this->table_name)
            ->leftJoin('users', $this->table_name . '.student_id', '=', 'users.id')
            ->leftJoin('exam_classes', $this->table_name.'.exam_class_id', '=', 'exam_classes.id')
            ->leftJoin('classes','exam_classes.class_id', '=', 'classes.id')
            ->where($this->table_name . ".id", $answer_id)
            ->select($this->table_name.".*", "classes.name as class_name")
            ->get();
            ;

        //if ($answer != null && count($answer) > 0 && $this->checkMark($answer_id)==false) {
        if ($answer != null && count($answer) > 0 ) {
            $student_info = User::find($answer[0]->created_by);

            $ans_questions = DB::table("answer_questions")
                                    ->where("answer_id",$answer_id)
                                    ->orderBy("idx")
                                    ->get();
            $ques = [];
            foreach ($ans_questions as $key_1 => $value_1) {
                $details = [];
                $ans_ques_files = null;
                if( $value_1->question_type_id == 1){
                    $ans_ques_details = DB::table("answer_question_details")
                    ->where("answer_question_id",$value_1->id)
                    ->orderBy("name")
                    ->get();

                    foreach ($ans_ques_details as $key_2 => $value_2) {
                        $details[] = array(
                            "answer_question_detail_id" => $value_2->id,
                            "name" => $value_2->name,
                            "content" => $value_2->content,
                            "real_answer" => $value_2->real_answer,
                        );
                    } // end-foreach
                } // end-if
                else{
                    $ans_ques_files = DB::table("answer_question_files")
                    ->where("answer_question_id",$value_1->id)
                    ->orderBy("file_name")
                    ->get();
                }



                $ques[] = array(
                    "answer_question_id" => $value_1->id,
                    "question_idx" => $value_1->idx,
                    "question_content"=> $value_1->content,
                    "answer_content"=> $value_1->content_answer,
                    "question_mark"=> $value_1->mark,
                    "question_type"=> $value_1->question_type_id,
                    "question_details" => $details,
                    "answer_files" => $ans_ques_files
                );
            } // end-foreach

            return view('admin.mark.mark')
                ->with('answer', $answer)
                ->with('answer_details', $ques)
                ->with('student_info', $student_info)
            ;

        } else {
           return abort(404);

        }
    }

    public function teacherMark(Request $request)
    {

        //Lấy giá trị slide đã nhập
        date_default_timezone_set(Util::$time_zone);
        //$allRequest = $request->all();

        $data = $request->data;
        $current_date = date(Util::$date_time_format);
        $count_error = 0;
        $count_exist = 0;
        $count_error_details = 0;

        foreach ($data as $k => $val) {
            $answer_id = $val["answer_id"];
            $mark_info = DB::table('mark_teacher')
            ->where('answer_id', $answer_id)
            ->where('teacher_id', auth()->user()->id)
                ->first();

            // if (DB::table('mark_teacher')
            // ->where('answer_id', $answer_id)
            // ->where('teacher_id', auth()->user()->id)
            //     ->doesntExist())
            if($mark_info==null)
            {
                    $save_data_1 = array(
                        "answer_id"=>$answer_id,
                        "teacher_id" => auth()->user()->id,
                        "created_by" => auth()->user()->id,
                        "created_at" => $current_date,
                        "updated_by" => null,
                        "updated_at" => null
                    );

                    $teacher_mark_id = DB::table("mark_teacher")->insertGetId($save_data_1);
                    if ($teacher_mark_id != null) {
                        foreach ($val["data"] as $key => $value) {
                            $save_data_2 = array(
                                "mark_teacher_id"=>$teacher_mark_id,
                                "answer_question_id"=>$value["answer_question_id"],
                                "question_type_id"=>$value["question_type"],
                                "mark" => $value["real_mark"],
                                "evaluation" => $value["evaluation"],
                                "created_by" => auth()->user()->id,
                                "created_at" => $current_date,
                                "updated_by" => null,
                                "updated_at" => null
                            );

                            $mark_teacher_detail_id = DB::table("mark_teacher_details")->insertGetId($save_data_2);
                            if ($mark_teacher_detail_id == null) {
                                $count_error_details += 1;
                            }
                        }

                       // $response = array("result" =>1, "msg" => "Lưu thành công!");
                    }else{
                        $count_error = $count_error+1;
                       // $response = array("result" => 0, "msg" => "Lỗi server không thể lưu lại!");
                    }

            }else{

                $count_exist = $count_exist + 1;

                $save_data_1 = array(
                    //"answer_id"=>$answer_id,
                    //"teacher_id" => auth()->user()->id,
                    //"created_by" => auth()->user()->id,
                    //"created_at" => $current_date,
                    "updated_by" => auth()->user()->id,
                    "updated_at" => $current_date
                );

                //$teacher_mark_id = DB::table("mark_teacher")->insertGetId($save_data_1);
                $check = DB::table("mark_teacher")->where("id", $mark_info->id)->update($save_data_1);
                if ($check != null) {
                    foreach ($val["data"] as $key => $value) {

                        $mark_teacher_detail = DB::table("mark_teacher_details")
                                                ->where("mark_teacher_id", $mark_info->id)
                                                ->where("answer_question_id", $value["answer_question_id"])
                                                ->where("question_type_id", $value["question_type"])
                                                ->first();
                        if($mark_teacher_detail!=null){
                            $save_data_2 = array(
                                "mark" => $value["real_mark"],
                               // "created_by" => auth()->user()->id,
                               // "created_at" => $current_date,
                                "updated_by" => auth()->user()->id,
                                "updated_at" => $current_date
                            );
                            $check_1 = DB::table("mark_teacher_details")->where("id", $mark_teacher_detail->id)->update($save_data_2);
                        }else{
                            $save_data_2 = array(
                                "mark_teacher_id"=>$mark_info->id,
                                "answer_question_id"=>$value["answer_question_id"],
                                "question_type_id"=>$value["question_type"],
                                "mark" => $value["real_mark"],
                                "evaluation" => $value["evaluation"],
                                "created_by" => auth()->user()->id,
                                "created_at" => $current_date,
                                "updated_by" => null,
                                "updated_at" => null
                            );

                            $mark_teacher_detail_id = DB::table("mark_teacher_details")->insertGetId($save_data_2);
                            if ($mark_teacher_detail_id == null) {
                                $count_error_details += 1;
                            }
                        }
                    }

                   // $response = array("result" =>1, "msg" => "Lưu thành công!");
                }else{
                    $count_error = $count_error+1;
                   // $response = array("result" => 0, "msg" => "Lỗi server không thể lưu lại!");
                }

                //$response = array("result" => 0, "msg" => "Bạn đã đánh giá bài KT này rồi!");
            }
        }

        $response = array("result" => 1, "msg" => $request->data);
        return response()->json($response);
        //Thực hiện chuyển trang
        //return redirect('admin/ql-bai-kiem-tra/create');
    }


    public function checkMark($answer_id){
        $mark = DB::table("mark_teacher")
            ->where("mark_teacher.answer_id", $answer_id)
            ->select("mark_teacher.id")
            ->get();
            ;
        if($mark!=null && count($mark)>0 ){
            return(true);
        }else{
            return(false);
        }
    }

    public function getMark($answer_id){
        $mark = -1;
        $qr = DB::table("mark_teacher")
            ->where("mark_teacher.answer_id", $answer_id)
            ->select("mark_teacher.id")
            ->get();
            ;
        if($qr!=null && count($qr)>0 ){
            $mark = 0;
             $mark_details = DB::table("mark_teacher_details")
            ->where("mark_teacher_details.mark_teacher_id", $qr[0]->id)
            ->select("mark_teacher_details.id","mark_teacher_details.mark")
            ->get();

            foreach ($mark_details as $key => $value) {
                if($value->mark >=0){
                    $mark = $mark + $value->mark;
                }
            }
        }

        return($mark);
    }

    // public function reviewEvaluation($id, $answer_question_id)
    // {
    //     $answer_question =  DB::table("answer_questions")
    //         ->select()
    //         ->where('id', $answer_question_id)
    //         ->first()
    //     ;

    //     $exam = DB::table("answers")
    //         ->select(
    //            'exams.*'
    //         )
    //         ->leftJoin('exams', 'exams.id', '=', 'answers.exam_id')
    //         ->where('answers.id', $answer_question->answer_id)
    //         ->first()
    //     ;

    //     $evaluations = DB::table("mark_student")
    //         ->select(
    //             "mark_student_details.answer_question_id",
    //             "mark_student_details.mark",
    //             "mark_student_details.evaluation",
    //             "users.last_name",
    //             "users.first_name",
    //         )
    //         ->leftJoin('mark_student_details', 'mark_student_details.mark_student_id', '=', 'mark_student.id')
    //         ->leftJoin('users', 'mark_student.student_id', '=', 'users.id')
    //         ->where('mark_student.answer_id', $id)
    //         ->where('mark_student_details.answer_question_id', $answer_question_id)
    //         ->get()
    //     ;
    //     return view('admin.mark.review_evaluation')
    //         ->with("data", $evaluations)
    //         ->with("answer_question", $answer_question)
    //         ->with("exam", $exam)
            

    //         ;
    // }

    public function reviewEvaluation(Request $request)
    {
        if ($request->has("id") && $request->has("answer_question_id")) {
            $id = $request->input("id");
            $answer_question_id = $request->input("answer_question_id");


            $answer_question =  DB::table("answer_questions")
                ->select()
                ->where('id', $answer_question_id)
                ->first()
            ;

            $exam = DB::table("answers")
                ->select(
                   'exams.*'
                )
                ->leftJoin('exams', 'exams.id', '=', 'answers.exam_id')
                ->where('answers.id', $answer_question->answer_id)
                ->first()
            ;

            $evaluations = DB::table("mark_student")
                ->select(
                    "mark_student_details.answer_question_id",
                    "mark_student_details.mark",
                    "mark_student_details.evaluation",
                    "users.last_name",
                    "users.first_name",
                )
                ->leftJoin('mark_student_details', 'mark_student_details.mark_student_id', '=', 'mark_student.id')
                ->leftJoin('users', 'mark_student.student_id', '=', 'users.id')
                ->where('mark_student.answer_id', $id)
                ->where('mark_student_details.answer_question_id', $answer_question_id)
                ->get()
            ;
            return view("admin/mark/review_evaluation")
                ->with("data", $evaluations)
                ->with("answer_question", $answer_question)
                ->with("exam", $exam)


            ;
        }else{
           return view("admin/mark/review_evaluation")
           ;
       }
    }

    public function exportCsv(Request $request)
    {
        
       $fileName = Session::get("data_export_file_name") . '_Bang_Diem.csv';
       $all_data = Session::get("data_export");
            $headers = array(
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );
    
            //$columns = array('Title', 'Assign', 'Description', 'Start Date', 'Due Date');
    
            $callback = function() use($all_data) {
                echo "\xEF\xBB\xBF";
                $file = fopen('php://output', 'w');
               // fputcsv($file, $columns);
    
                foreach ($all_data as $row) {
               // for($i=0;$i<10;$i++){
                    // $row['Title']  = "title";// $task->title;
                    // $row['Assign']    = "assign" ; //$task->assign->name;
                    // $row['Description']    = "des" ; //$task->description;
                    // $row['Start Date']  = "dd" ; //$task->start_at;
                    // $row['Due Date']  = "ádasd" ; // $task->end_at;
    
                   // fputcsv($file, array($row['Title'], $row['Assign'], $row['Description'], $row['Start Date'], $row['Due Date']));
                    fputcsv($file, $row);
                }
    
                fclose($file);
            };
    
            return response()->stream($callback, 200, $headers);
        }
    


}
