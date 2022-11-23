<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


//use App\Http\Controllers\StudentController;

class ExamController extends Controller
{
    private $table_name = "exams";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        //Lấy giá trị slide đã nhập
        date_default_timezone_set(Util::$time_zone);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('frontend.exam.list');
    }

    public function find(Request $request)
    {

        date_default_timezone_set(Util::$time_zone);
        $current = intval($request->input("current"));
        $rowCount = intval($request->input("rowCount"));
        $searchPhrase = $request->input("searchPhrase");

        $current_year = date("Y");

        // $school_years = DB::table("school_years")
        //                 ->where("year_open", "<=", $current_year)
        //                 ->where("year_close", ">=", $current_year)->get();

        //if(count($school_years) == 1){

        // $query = DB::table("users")
        //     ->leftJoin('student_class', 'users.id', '=', 'student_class.student_id')
        //     ->leftJoin('classes', 'classes.id', '=', 'student_class.class_id')
        //     ->leftJoin('school_years', 'school_years.id', '=', 'classes.school_year_id')
        //     ->leftJoin('exam_classes', 'exam_classes.class_id', '=', 'classes.id')
        //     ->leftJoin('exams', 'exam_classes.exam_id', '=', 'exams.id')
        //     ->leftJoin('subjects', 'exams.subject_id', '=', 'subjects.id')
        //     ->where("school_years.year_open", "<=", $current_year)
        //     ->where("school_years.year_close", ">=", $current_year)
        //     ->where("users.id", auth()->user()->id)
        //     ->select("exams.id as exam_id", "exams.title as exam_title", "exam_classes.id as exam_class_id", "exam_classes.time_open as time_open", "exam_classes.time_close as time_close", "exam_classes.time_limit as time_limit", "subjects.id as subject_id", "subjects.name as subject_name"
        //     )
        // ;

        $query = DB::table("users")
            ->join('student_class', 'users.id', '=', 'student_class.student_id')
            ->join('classes', 'classes.id', '=', 'student_class.class_id')
            ->join('school_years', 'school_years.id', '=', 'classes.school_year_id')
            ->join('exam_classes', 'exam_classes.class_id', '=', 'classes.id')
            ->join('exams', 'exam_classes.exam_id', '=', 'exams.id')
            ->join('subjects', 'exams.subject_id', '=', 'subjects.id')
            ->where("school_years.year_open", "<=", $current_year)
            ->where("school_years.year_close", ">=", $current_year)
            ->where("users.id", auth()->user()->id)
            ->select("exams.id as exam_id", "exams.title as exam_title", "exam_classes.id as exam_class_id", "exam_classes.time_open as time_open", "exam_classes.time_close as time_close", "exam_classes.time_limit as time_limit", "subjects.id as subject_id", "subjects.name as subject_name"
            )
        ;

        //}

        //    //search
        if (!empty($searchPhrase)) {
            $query->where("exams.title", "LIKE", '%' . $searchPhrase . "%")
            ;
        }

        //sort

        if (isset($request->sort) && is_array($request->sort)) {

            foreach ($request->sort as $key => $value) {
                switch ($key) {
                    case "time_open":
                        $query->orderBy("exam_classes." . $key, $value);
                        break;

                    case "time_close":
                        $query->orderBy("exam_classes." . $key, $value);
                        break;

                    case "time_limit":
                        $query->orderBy("exam_classes." . $key, $value);
                        break;
                }
            }
        } else {
            $query->orderBy("exam_classes.time_open", "asc")
                ->orderBy("exam_classes.time_close", "asc");
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
        // $data = $query->select('subjects.id as subject_id', 'subjects.name as subject_name', 'exams.id as exam_id', 'exams.title', 'exams.created_at', 'exams.created_by', 'exams.updated_at', 'exams.updated_by', 'exams.status as exam_status')
        // ->get();

        $json = array(
            "current" => $current,
            "rowCount" => $rowCount,
            "total" => intval($total),
            "rows" => []
        );

        if (count($data) > 0) {
            $current_date = date(Util::$date_time_format);

            foreach ($data as $key => $item) {
                $status = 0;
                if (Util::checkExamComplete($item->exam_class_id, $item->exam_id, auth()->user()->id)) {
                    if (Util::checkPeerAssessment($item->exam_class_id, $item->exam_id, auth()->user()->id) > 0) {
                   // if(Util::checkExamAssessment($item->exam_class_id, $item->exam_id, auth()->user()->id)!=null){
                        $status = 3;
                    } else {
                        $status = 4;
                    }
                } else {
                    if ($item->time_open <= $current_date && $item->time_close >= $current_date) {
                        $status = 1;
                    } else if ($item->time_open < $current_date && $item->time_close < $current_date) {
                        $status = 2;
                    } else {
                        $status = 0;
                    }
                }

                //$roles  = Util::get_role_name($item->id);
                // $created_by = User::find($item->created_by);
                // $updated_by = User::find($item->updated_by);
                $json['rows'][] = array(
                    "id" => $item->exam_class_id,
                    "subject_name" => $item->subject_name,
                    "exam_id" => $item->exam_id,
                    "exam_title" => $item->exam_title,
                    "time_open" => $item->time_open,
                    "time_close" => $item->time_close,
                    "time_limit" => $item->time_limit,
                    "time_current" => $current_date,
                    "aa" => Util::checkPeerAssessment($item->exam_class_id, $item->exam_id, auth()->user()->id),
                    //"time_current" => Util::$date_time_format,
                    "status" => $status,
                );
            }
        }
        return response()->json($json);
    }

    public function answer($exam_class_id, $exam_id)
    {
        $assessment_check = Util::checkPeerAssessment($exam_class_id, $exam_id, auth()->user()->id);
        $check_answer = Util::checkAnswer($exam_class_id, $exam_id, auth()->user()->id);
        $check_exam = Util::checkExam($exam_class_id, $exam_id, auth()->user()->id);
       // if ($check_answer == null && $check_exam != null) {
            $current_date = date(Util::$date_time_format);

            $time_start_exam = Util::checkStartExam($exam_class_id, auth()->user()->id);

            $exam = DB::table("exams")->select('id', 'title', 'subject_id', 'note', 'status')->where('id', $exam_id)->get();

            $getDataQuestion = DB::table("questions")->select()->where('exam_id', $exam_id)->orderBy('idx')->get();
            $questions = [];
            foreach ($getDataQuestion as $key => $item) {
                $data = array(
                    "question_id" => $item->id,
                    "question_idx" => $item->idx,
                    "question_mark" => $item->mark,
                    "question_type" => $item->question_type_id,
                    "question_content" => $item->content,
                    "answers" => array(),

                );
                if ($item->question_type_id == 1) {
                    $question_details = DB::table("question_details")->select()->where('question_id', $item->id)->get();
                    $details = [];
                    foreach ($question_details as $k => $itm) {
                        $details[] = array(
                            "answer_id" => $itm->id,
                            "answer_content" => $itm->content,
                            'answer_name' => $itm->name,
                            // "is_answer" => $itm->is_answer,
                        );
                    }
                    $data["answers"] = $details;
                }
                $questions[] = $data;

            }

            $teacher_info = null;
            if ($check_exam != null && count($check_exam) > 0) {
                $teacher_info = DB::table("teacher_class_subject")
                    ->select("users.last_name", "users.first_name", "users.id as id")
                    ->join("users", "users.id", "=", "teacher_class_subject.teacher_id")
                    ->where('class_id', $check_exam[0]->class_id)
                    ->where('subject_id', $check_exam[0]->subject_id)
                    ->get();
            }



            return view('frontend.exam.answer')
                ->with('exam_class_id', $exam_class_id)
                ->with('exam_config', $check_exam)
                ->with('exam', $exam)
                ->with('teacher', $teacher_info)
                ->with('questions', $questions)
                ->with('check_assessment', $assessment_check)
                ->with('time_start_exam', $time_start_exam)
            ;
        // } else {
        //     if ($check_answer != null) {
        //         $exam = DB::table("exams")->select('id', 'title', 'subject_id', 'note', 'status')->where('id', $exam_id)->get();
        //         return view('frontend.exam.answer_success')
        //             ->with('exam', $exam)
        //             ->with('msg', "Bạn đã làm bài kiểm tra này rồi!")
        //         ;
        //     } else {
        //         return abort(404);
        //     }
        // }
    }

    public function assess($exam_class_id, $exam_id)
    {
        $assessment_check = Util::checkPeerAssessment($exam_class_id, $exam_id, auth()->user()->id);
        $check_exam = Util::checkExamAssessment($exam_class_id, $exam_id, auth()->user()->id);
        //var_dump($assessment_check);
        // var_dump($check_exam);
        if ($check_exam != null && $assessment_check > 0) {

            $current_date = date(Util::$date_time_format);
            // $exam_class = $check_exam[0];
            $exam_class = DB::table("exam_classes")->where("id", $exam_class_id)->where("status", 1)->first();

            $test_query = DB::table('mark_student')
                ->join("answers", "answers.id", "mark_student.answer_id")
                ->select('mark_student.answer_id', DB::raw('COUNT(mark_student.answer_id) as count_answer_id'))
                ->where('answers.exam_class_id', '=', $exam_class_id)
                ->where('answers.exam_id', '=', $exam_id)
                ->where('answers.student_id', '!=', auth()->user()->id)
                ->groupBy('mark_student.answer_id')
                ->get();

            $arr_igore_answer_id = [];
            foreach ($test_query as $key => $value) {
                if ($value->count_answer_id >= $exam_class->assessment_quantity) {
                    $arr_igore_answer_id[] = $value->answer_id;
                }
            }

            $answer_data = DB::table('answers')
                ->select('*')
                ->where('answers.exam_class_id', '=', $exam_class_id)
                ->where('answers.exam_id', '=', $exam_id)
                ->where('answers.student_id', '!=', auth()->user()->id)
                ->whereNotIn("answers.id", $arr_igore_answer_id)
                ->inRandomOrder()
                ->take($assessment_check)
                ->get();

            if ($answer_data != null && count($answer_data) == $assessment_check) {
                $all_data = [];
                foreach ($answer_data as $key => $value) {
                    $student_info = User::find($value->created_by);
                    $ans = array(
                        "answer_id" => $value->id,
                        "student" =>  $student_info!=null ? $student_info->last_name . " " . $student_info->first_name : "",
                        "exam_title" => $value->exam_title,
                        "exam_mark" => $value->mark,
                        "subject_name" => $value->subject_name,
                        "exam_class_time_open" => $value->exam_class_time_open,
                        "exam_class_time_close" => $value->exam_class_time_close,
                        "exam_class_time_limit" => $value->exam_class_time_limit,
                        "answer_question" => []
                    );

                    $ans_questions = DB::table("answer_questions")
                        ->where("answer_id", $value->id)
                        ->orderBy("idx")
                        ->get();
                    $ques = [];
                    foreach ($ans_questions as $key_1 => $value_1) {
                        $details = [];
                          $ans_ques_files = null;
                        if ($value_1->question_type_id == 1) {
                            $ans_ques_details = DB::table("answer_question_details")
                                ->where("answer_question_id", $value_1->id)
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
                            "question_content" => $value_1->content,
                            "answer_content" => $value_1->content_answer,
                            "question_mark" => $value_1->mark,
                            "question_type" => $value_1->question_type_id,
                            "question_details" => $details,
                            "answer_files" => $ans_ques_files
                        );
                    } // end-foreach

                    $ans["answer_question"] = $ques;
                    $all_data[] = $ans;

                } // end-foreach

                $exam = DB::table("exams")->select('id', 'title', 'subject_id', 'note', 'status')->where('id', $exam_id)->get();

                $getDataQuestion = DB::table("questions")->select()->where('exam_id', $exam_id)->orderBy('idx')->get();

                $questions = [];
                foreach ($getDataQuestion as $key => $item) {
                    $data = array(
                        "question_id" => $item->id,
                        "question_idx" => $item->idx,
                        "question_mark" => $item->mark,
                        "question_type" => $item->question_type_id,
                        "question_content" => $item->content,
                        "answers" => array(),

                    );
                    if ($item->question_type_id == 1) {
                        $question_details = DB::table("question_details")->select()->where('question_id', $item->id)->get();
                        $details = [];
                        foreach ($question_details as $k => $itm) {
                            $details[] = array(
                                "answer_id" => $itm->id,
                                "answer_content" => $itm->content,
                                'answer_name' => $itm->name,
                                // "is_answer" => $itm->is_answer,
                            );
                        }
                        $data["answers"] = $details;
                    }
                    $questions[] = $data;

                } // end-foreach

                $teacher_info = null;
                if ($check_exam != null && count($check_exam) > 0) {
                    $teacher_info = DB::table("teacher_class_subject")
                        ->select("users.last_name", "users.first_name", "users.id")
                        ->join("users", "users.id", "=", "teacher_class_subject.teacher_id")
                        ->where('class_id', $check_exam[0]->class_id)
                        ->where('subject_id', $check_exam[0]->subject_id)
                        ->get();
                }

                return view('frontend.exam.assess')
                    ->with('exam_class_id', $exam_class_id)
                    ->with('exam_id', $exam_id)
                    ->with('exam_config', $check_exam)
                    ->with('exam', $exam)
                    ->with('teacher', $teacher_info)
                    ->with('questions', $questions)
                    ->with('check_assessment', $assessment_check)
                    ->with('all_data', $all_data)
                ;
            } else {
                $exam = DB::table("exams")->select('id', 'title', 'subject_id', 'note', 'status')->where('id', $exam_id)->get();

                return view('frontend.exam.assess_msg')
                    ->with('exam', $exam)
                    ->with('msg', "Hiện tại bài kiểm tra chưa đủ số lượng để bạn đánh giá. Xin bạn vui lòng quay lại sau!")
                ;

            }

        } else {
            if ($assessment_check == 0) {
                $exam = DB::table("exams")->select('id', 'title', 'subject_id', 'note', 'status')->where('id', $exam_id)->get();

                return view('frontend.exam.assess_msg')
                    ->with('exam', $exam)
                    ->with('msg', "Bạn đã đánh giá bài Kiểm Tra này rồi!")
                ;
            } else {
                $exam = DB::table("exams")->select('id', 'title', 'subject_id', 'note', 'status')->where('id', $exam_id)->get();

                return view('frontend.exam.assess_msg')
                    ->with('exam', $exam)
                    ->with('msg', "Bài kiểm tra đã hết hạn để đánh giá!")
                ;
               // return abort(404);
            }
        }
    }

    public function view_answer($exam_class_id, $exam_id)
    {
        //$assessment_check = Util::checkPeerAssessment($exam_class_id, $exam_id, auth()->user()->id);
        $check_exam = Util::checkExamAssessmentToReview($exam_class_id, $exam_id, auth()->user()->id);
        //var_dump($assessment_check);
        // var_dump($check_exam);
        //if ($check_exam != null && $assessment_check > 0) {
        if ($check_exam != null) {

            $current_date = date(Util::$date_time_format);
            // $exam_class = $check_exam[0];
            //$exam_class = DB::table("exam_classes")->where("id", $exam_class_id)->where("status", 1)->first();

            // $test_query = DB::table('mark_student')
            //     ->join("answers", "answers.id", "mark_student.answer_id")
            //     ->select('mark_student.answer_id', DB::raw('COUNT(mark_student.answer_id) as count_answer_id'))
            //     ->where('answers.exam_class_id', '=', $exam_class_id)
            //     ->where('answers.exam_id', '=', $exam_id)
            //     ->where('answers.student_id', '!=', auth()->user()->id)
            //     ->groupBy('mark_student.answer_id')
            //     ->get();

            // $arr_igore_answer_id = [];
            // foreach ($test_query as $key => $value) {
            //     if ($value->count_answer_id >= $exam_class->assessment_quantity) {
            //         $arr_igore_answer_id[] = $value->answer_id;
            //     }
            // }

            $answer_data = DB::table('answers')
                ->select('*')
                ->where('answers.exam_class_id', '=', $exam_class_id)
                ->where('answers.exam_id', '=', $exam_id)
                ->where('answers.student_id', auth()->user()->id)
               // ->whereNotIn("answers.id", $arr_igore_answer_id)
                // ->inRandomOrder()
                // ->take($assessment_check)
                // ->get()
                ->get()
                ;

            //if ($answer_data != null && count($answer_data) == $assessment_check) {
            if ($answer_data != null) {
                $all_data = [];
                foreach ($answer_data as $key => $value) {
                    $student_info = User::find($value->created_by);
                    $ans = array(
                        "answer_id" => $value->id,
                        "student" => "", // $student_info!=null ? $student_info->last_name . " " . $student_info->first_name : "",
                        "exam_title" => $value->exam_title,
                        "exam_mark" => $value->mark,
                        "subject_name" => $value->subject_name,
                        "exam_class_time_open" => $value->exam_class_time_open,
                        "exam_class_time_close" => $value->exam_class_time_close,
                        "exam_class_time_limit" => $value->exam_class_time_limit,
                        "answer_question" => []
                    );

                    $ans_questions = DB::table("answer_questions")
                        ->where("answer_id", $value->id)
                        ->orderBy("idx")
                        ->get();
                    $ques = [];
                    foreach ($ans_questions as $key_1 => $value_1) {
                        $details = [];
                        $ans_ques_files=null;
                        if ($value_1->question_type_id == 1) {
                            $ans_ques_details = DB::table("answer_question_details")
                                ->where("answer_question_id", $value_1->id)
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
                            "question_content" => $value_1->content,
                            "answer_content" => $value_1->content_answer,
                            "question_mark" => $value_1->mark,
                            "question_type" => $value_1->question_type_id,
                            "question_details" => $details,
                            "answer_files" => $ans_ques_files
                        );
                    } // end-foreach

                    $ans["answer_question"] = $ques;
                    $all_data[] = $ans;

                } // end-foreach

                $exam = DB::table("exams")->select('id', 'title', 'subject_id', 'note', 'status')->where('id', $exam_id)->get();

                $getDataQuestion = DB::table("questions")->select()->where('exam_id', $exam_id)->orderBy('idx')->get();

                $questions = [];
                foreach ($getDataQuestion as $key => $item) {
                    $data = array(
                        "question_id" => $item->id,
                        "question_idx" => $item->idx,
                        "question_mark" => $item->mark,
                        "question_type" => $item->question_type_id,
                        "question_content" => $item->content,
                        "answers" => array(),

                    );
                    $ans_ques_files=null;
                    if ($item->question_type_id == 1) {
                        $question_details = DB::table("question_details")->select()->where('question_id', $item->id)->get();
                        $details = [];
                        foreach ($question_details as $k => $itm) {
                            $details[] = array(
                                "answer_id" => $itm->id,
                                "answer_content" => $itm->content,
                                'answer_name' => $itm->name,
                                // "is_answer" => $itm->is_answer,
                            );
                        }
                        $data["answers"] = $details;
                    }
                    $questions[] = $data;

                } // end-foreach

                $teacher_info = null;
                if ($check_exam != null && count($check_exam) > 0) {
                    $teacher_info = DB::table("teacher_class_subject")
                        ->select("users.last_name", "users.first_name", "users.id")
                        ->join("users", "users.id", "=", "teacher_class_subject.teacher_id")
                        ->where('class_id', $check_exam[0]->class_id)
                        ->where('subject_id', $check_exam[0]->subject_id)
                        ->get();
                }

                return view('frontend.exam.view_answer')
                    ->with('exam_class_id', $exam_class_id)
                    ->with('exam_id', $exam_id)
                    ->with('exam_config', $check_exam)
                    ->with('exam', $exam)
                    ->with('teacher', $teacher_info)
                    ->with('questions', $questions)
                    //->with('check_assessment', $assessment_check)
                    ->with('all_data', $all_data)
                ;
            } else {
                $exam = DB::table("exams")->select('id', 'title', 'subject_id', 'note', 'status')->where('id', $exam_id)->get();

                return view('frontend.exam.assess_msg')
                    ->with('exam', $exam)
                    ->with('msg', "Hiện tại bạn chưa hoặc không có quyền làm bài kiểm tra này!")
                ;
            }

        } else {
            //if ($assessment_check == 0) {
                $exam = DB::table("exams")->select('id', 'title', 'subject_id', 'note', 'status')->where('id', $exam_id)->get();

                return view('frontend.exam.assess_msg')
                    ->with('exam', $exam)
                    ->with('msg', "Hiện tại bạn chưa hoặc không có quyền làm bài kiểm tra này!!")
                ;
            // } else {
            //     return abort(404);
            // }
        }
    }

    // public function checkPeerAssessment($exam_class_id, $exam_id, $student_id){
    //     $check_complete = DB::table('answers')->where('exam_class_id', $exam_class_id)->where('exam_id', $exam_id)->where('student_id', $student_id)->exists();
    //     return($check_complete);
    // }

    public function getExamData($exam_id)
    {
        $questions = DB::table("questions")
            ->where('exam_id', $exam_id)
            ->orderBy("idx")
            ->get();

        $datas = [];

        foreach ($questions as $key => $item) {
            //$roles  = Util::get_role_name($item->id);
            // $created_by = User::find($item->created_by);
            // $updated_by = User::find($item->updated_by);
            $question_details = "";
            if ($item->question_type_id == 1) {
                $details = DB::table("question_details")->where('question_id', $item->id)->get();
                foreach ($details as $kk => $dt) {
                    if ($dt->is_answer == 1) {
                        $question_details = $question_details . "<br/> <input type=\"radio\" name=\"" . $item->id . "\" value=\"" . $dt->name . "\" checked=\"checked\"> " . $dt->name . ". " . $dt->content;
                    } else {
                        $question_details = $question_details . "<br/> <input type=\"radio\" name=\"" . $item->id . "\" value=\"" . $dt->name . "\" disabled=\"true\"> " . $dt->name . ". " . $dt->content;
                    }
                }
            }
            $datas[] = array(
                "id" => $item->id,
                "idx" => "Câu " . $item->idx,
                "content" => "<strong>" . $item->content . "</strong> " . $question_details,
                "exam_id" => $item->exam_id,
                // "created_at" => $item->created_at,
                // "updated_at" => $item->updated_at,
                // "created_by" => $created_by != null ? $created_by->name : "",
                // "updated_by" => $updated_by != null ? $updated_by->name : "",
                //date('Y-m-d H:i:s')
            );
        }

    }

    public function store(Request $request)
    {

        date_default_timezone_set(Util::$time_zone);

        $exam_class_id = $request->exam_class_id;
        $exam_id = $request->exam_id;
        $time_start_exam_id = $request->time_start_exam_id;
        $data = $request->data;
        $check_exam = Util::checkExam($exam_class_id, $exam_id, auth()->user()->id);

        $exam_class = DB::table("exam_classes")->where("id", $exam_class_id)->where("status", 1)->get();
        $exams = DB::table("exams")
            ->join("subjects", "subjects.id", "=", "exams.subject_id")
            ->where("exams.id", $exam_id)
            ->where("exams.status", 1)
            ->select("exams.id", "exams.title", "exams.mark", "exams.subject_id", "subjects.name as subject_name")
            ->get()
        ;

        $school_years = DB::table("exam_classes")
            ->join("classes", "exam_classes.class_id", "=", "classes.id")
            ->join("school_years", "classes.school_year_id", "=", "school_years.id")
            ->where("exam_classes.id", $exam_class_id)
            ->select("school_years.id", "school_years.year_open", "school_years.year_close")
            ->get()
        ;

        $school_year_id = null;
        $school_year_open = null;
        $school_year_close = null;

        $teacher_id = null;
        $teacher_first_name = null;
        $teacher_last_name = null;

        if ($school_years != null && count($school_years) > 0) {
            $school_year_id = $school_years[0]->id;
            $school_year_open = $school_years[0]->year_open;
            $school_year_close = $school_years[0]->year_close;
        }

        $count_error_details = 0;

        if ($check_exam != null && $exams != null && $exam_class != null) {
            $msg = "";
            $check_data_exist = DB::table("answers")
                ->where("student_id", auth()->user()->id)
                ->where("exam_id", $exam_id)
                ->where("exam_class_id", $exam_class_id)
                ->get();

            if ($check_data_exist == null || count($check_data_exist) == 0) { // insert
                $current_date = date(Util::$date_time_format);
                $dataInsertToDatabase = array(
                    'student_id' => auth()->user()->id,
                    'exam_id' => $exam_id,
                    'exam_class_id' => $exam_class_id,
                    'time_start' => $current_date,
                    'exam_title' => $exams[0]->title,
                    'exam_class_time_open' => $exam_class[0]->time_open,
                    'exam_class_time_close' => $exam_class[0]->time_close,
                    'exam_class_time_limit' => $exam_class[0]->time_limit,
                    'mark' => $exams[0]->mark,
                    'subject_id' => $exams[0]->subject_id,
                    'subject_name' => $exams[0]->subject_name,
                    'school_year_id' => $school_year_id,
                    'school_year_open' => $school_year_open,
                    'school_year_close' => $school_year_close,
                    'teacher_id' => $teacher_id,
                    'teacher_first_name' => $teacher_first_name,

                    'teacher_last_name' => $teacher_last_name,
                    'created_by' => auth()->user()->id,
                    'created_at' => $current_date,
                );
                $answer_id = DB::table("answers")->insertGetId($dataInsertToDatabase);
                if ($answer_id != null) {
                    foreach ($data as $key => $item) {
                        $questions = DB::table("questions")->where("id", $item["ques_id"])->first();
                        if ($questions != null) {
                            $question = array(
                                "question_id" => $item["ques_id"],
                                "answer_id" => $answer_id,
                                "idx" => $questions->idx,
                                "mark" => $questions->mark,
                                "content" => $questions->content,
                                "content_answer" => $item["ques_type"] == 0 ? $item["ques_answer"] : null,
                                "question_type_id" => $item["ques_type"],
                                'created_by' => auth()->user()->id,
                                'created_at' => $current_date,
                            );
                            $answer_question_id = DB::table("answer_questions")->insertGetId($question);
                            if ($answer_question_id != null){
                                if($item["ques_type"] == 1) {
                                    $question_details = DB::table("question_details")
                                        ->select()
                                        ->where("question_id", $item["ques_id"])
                                        ->get();
                                    foreach ($question_details as $ke => $val) {
                                        $real_answer = 0;
                                        if (array_key_exists("ques_answer", $item) && intval($val->id) === intval($item["ques_answer"])) {
                                            $real_answer = 1;
                                        }

                                        $answer = array(
                                            "question_id" => $item["ques_id"],
                                            "answer_question_id" => $answer_question_id,
                                            "question_detail_id" => $val->id,
                                            "name" => $val->name,
                                            "content" => $val->content,
                                            "is_answer" => $val->is_answer,
                                            "real_answer" => $real_answer,
                                            'created_by' => auth()->user()->id,
                                            'created_at' => $current_date,
                                        );

                                        $answer_question_detail_id = DB::table("answer_question_details")->insertGetId($answer);
                                        if ($answer_question_detail_id == null) {
                                            $count_error_details += 1;
                                        }
                                    }
                                }else{
                                    if(array_key_exists("answer_file_id", $item)){
                                        foreach ($item["answer_file_id"] as $kk => $vv) {
                                            DB::table("answer_question_files")->where('id', $vv)
                                            ->update(['answer_question_id' => $answer_question_id]);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    DB::table("time_start_exam")->where('id', $time_start_exam_id)
                            ->update(['time_end' => $current_date]);

                    $msg = " insert success " . $answer_id . " - " . $answer_question_id . " - error_add_details: " . $count_error_details;
                    $msg = "Nộp bài Kiểm Tra hoàn tất!";
                    $response = array("result" => 1, "msg" => $msg);

                } else {
                    $msg = "Nộp bài Kiểm Tra Lỗi";
                    $response = array("result" => 0, "msg" => $msg);

                }
            } else {
                $response = array("result" => 0, "msg" => "Bạn đã làm bài KT này rồi!", "aa" => $check_data_exist);
            }
        } else {
            $response = array("result" => 0, "msg" => "Bạn không được cấp quyền làm bài KT này!");
        }
        return response()->json($response);
        //Thực hiện chuyển trang
        //return redirect('admin/ql-bai-kiem-tra/create');
    }

    public function studentMark(Request $request)
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

            if (DB::table('mark_student')
                ->where('answer_id', $answer_id)
                ->where('student_id', auth()->user()->id)
                ->doesntExist()) {
                $save_data_1 = array(
                    "answer_id" => $answer_id,
                    "student_id" => auth()->user()->id,
                    "created_by" => auth()->user()->id,
                    "created_at" => $current_date,
                    "updated_by" => null,
                    "updated_at" => null,
                );

                $studen_mark_id = DB::table("mark_student")->insertGetId($save_data_1);
                if ($studen_mark_id != null) {
                    foreach ($val["data"] as $key => $value) {
                        $save_data_2 = array(
                            "mark_student_id" => $studen_mark_id,
                            "answer_question_id" => $value["answer_question_id"],
                            "question_type_id" => $value["question_type"],
                            "evaluation" => $value["evaluation"],
                            "mark" => $value["real_mark"],
                            "created_by" => auth()->user()->id,
                            "created_at" => $current_date,
                            "updated_by" => null,
                            "updated_at" => null,
                        );

                        $mark_student_detail_id = DB::table("mark_student_details")->insertGetId($save_data_2);
                        if ($mark_student_detail_id == null) {
                            $count_error_details += 1;
                        }
                    }

                    // $response = array("result" =>1, "msg" => "Lưu thành công!");
                } else {
                    $count_error = $count_error + 1;
                    // $response = array("result" => 0, "msg" => "Lỗi server không thể lưu lại!");
                }

            } else {
                $count_exist = $count_exist + 1;
                //$response = array("result" => 0, "msg" => "Bạn đã đánh giá bài KT này rồi!");
            }
        }

        $response = array("result" => 1, "msg" => $request->data);
        return response()->json($response);

        // $school_year_id = $allRequest['school_year_id'];
        // $class_id = $allRequest['class_id'];
        // $subject_id = $allRequest['subject_id'];
        // $exam_id = $allRequest['exam_id'];
        // $time_open = $allRequest['time_open'];
        // $time_close = $allRequest['time_close'];
        // $time_limit = $allRequest['time_limit'];
        // $status = $allRequest['status'] == true ? 1 : 0;

        // //Gán giá trị vào array
        // $dataInsertToDatabase = array(
        //     //'school_year_id' => $school_year_id,
        //     //'subject_id' => $subject_id,
        //     'class_id' => $class_id,
        //     'exam_id' => $exam_id,
        //     'time_open' => $time_open,
        //     'time_close' => $time_close,
        //     'time_limit' => $time_limit,
        //     'status' => $status,
        //     'created_by' => auth()->user()->id,
        //     'created_at' => date(Util::$date_time_format),
        // );

        // //Insert
        // $id = DB::table($this->table_name)->insertGetId($dataInsertToDatabase);
        // $insertData = DB::table($this->table_name)->where('id', $id)->get();
        // $count_error_questions = 0;
        // $count_error_details = 0;
        // if (count($insertData) > 0) {

        //     $response = array("result" => 1, "msg" => "Thêm mới thành công!");
        // } else {
        //     //Session::flash('error', 'Thêm mới thất bại!');
        //     $response = array("result" => 0, "msg" => "Thêm mới thất bại!");
        // }

        return response()->json($response);
        //Thực hiện chuyển trang
        //return redirect('admin/ql-bai-kiem-tra/create');
    }

    public function delete(Request $request)
    {
        DB::table("answers")->delete();
        DB::table("answer_questions")->delete();
        DB::table("answer_question_details")->delete();
        $response = array("result" => 1, "msg" => "Xóa OK!");
        return response()->json($response);
    }

    // public function viewEvaluation(Request $request)
    // {

    //     return view("frontend/exam/view_evaluation")
    //     ;
    // }

//    public function viewEvaluation($id, $answer_question_id)
    public function viewEvaluation(Request $request)
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
      return view("frontend/exam/view_evaluation")
            ->with("data", $evaluations)
            ->with("answer_question", $answer_question)
            ->with("exam", $exam)


            ;
        }else{
           return view("frontend/exam/view_evaluation")
           ;
        }
    }

    // public function uploadFile(Request $request){
    //     $res = null;
    //     if($request->file_data!=null && $request->file_id){
    //         $data = [];
    //         foreach($request->file_data as $ke => $val){
    //             $res_upload = Util::uploadAnswerFile($val, auth()->user()->id, null);
    //             $data[] = $res_upload;
    //         }
    //         $res = $data;
    //     }
    //     return($res);
    // }

    // public function uploadFile(Request $request)
    // {

    //    $validatedData = $request->validate([
    //     'files' => 'required',
    //     'files.*' => 'mimes:csv,txt,xlx,xls,pdf,img,png,jpg,xlsx,doc,docx,ppt,pptx'
    //     ]);

    //     if($request->TotalFiles > 0)
    //     {

    //        for ($x = 0; $x < $request->TotalFiles; $x++)
    //        {

    //            if ($request->hasFile('files'.$x))
    //             {
    //                 $file      = $request->file('files'.$x);
    //                 $res_upload = Util::uploadAnswerFile($file, auth()->user()->id, null);

    //                 // $path = $file->store('public/files');
    //                 // $name = $file->getClientOriginalName();

    //                 // $insert[$x]['name'] = $name;
    //                 // $insert[$x]['path'] = $path;
    //             }
    //        }

    //         //File::insert($insert);

    //         return response()->json(['success'=>'Ajax Multiple fIle has been uploaded']);


    //     }
    //     else
    //     {
    //        return response()->json(["message" => "Please try again."]);
    //     }

    // }

    public function uploadFile(Request $request)
    {
        date_default_timezone_set(Util::$time_zone);
        $arr = [];
        foreach($request->all() as $file){
            if(is_file($file)){
                $string = date('Y-m-d_His_') . gettimeofday()["usec"]; // . "_" . $random = Str::random(32);
                $file_extension=$file->getClientOriginalExtension();
                $file_real_name= basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension());
                //$file->getClientOriginalName();
                $filename = Util::utf8tourl($string . auth()->user()->id . "_" . $file_real_name . '.' . $file_extension);
                $filepath = Util::$answer_file_folder . '/' . $filename;
                $file->storeAs(Util::$answer_file_folder, $filename);
                $ins_data = array(
                    "file_path" => $filepath,
                    "file_name" => $filename,
                    "created_at"=> date(Util::$date_time_format),
                    "created_by" => auth()->user()->id
                );
                $ins_id = DB::table("answer_question_files")->insertGetId($ins_data);
                array_push($arr,$ins_id);
                //array_push($arr, [$filename, $filepath]);
            }
        }
        return $arr;
    }

}
