<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ManagerController;
use App\Models\User;
use App\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ExamController extends ManagerController
{
    private $table_name = "exams";
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.exam.list');
    }

    public function find(Request $request)
    {
        $subject_id = intval($request->input("subject_id"));
        $current = intval($request->input("current"));
        $rowCount = intval($request->input("rowCount"));
        $searchPhrase = $request->input("searchPhrase");

        $query = DB::table($this->table_name)
        ->leftJoin('subjects', $this->table_name . '.subject_id', '=', 'subjects.id');
        if($subject_id!=-1){
            $query->where($this->table_name . ".subject_id", $subject_id);
        }

        if ( auth()->user()->level == 1){
            $query->where($this->table_name . ".created_by", auth()->user()->id);
        }

        //search
        if (!empty($searchPhrase)) {
            $query->where($this->table_name . ".title", "LIKE", '%' . $searchPhrase . "%")
            ->orWhere("subjects.name", "LIKE", '%' . $searchPhrase . "%");
        }

        //sort
        if (isset($request->sort) && is_array( $request->sort)) {
            
            foreach ($request->sort as $key => $value) { 
                switch ($key) {
                    case "status":
                    $query->orderBy($this->table_name . $key, $value);
                    break;
                }
            }
        } else {
            $query->orderBy($this->table_name . ".title", "asc")
            ->orderBy($this->table_name . ".created_at", "asc");
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
        $data = $query->select('subjects.id as subject_id', "exams.mark" , 'subjects.name as subject_name', 'exams.id as exam_id', 'exams.title', 'exams.created_at', 'exams.created_by', 'exams.updated_at', 'exams.updated_by', 'exams.status as exam_status')
        ->get();

        $json = array(
            "current" => $current,
            "rowCount" => $rowCount,
            "total" => intval($total),
            "rows" => []
        );

        if (count($data) > 0) {
            foreach ($data as $key => $item) {
                //$roles  = Util::get_role_name($item->id);
                $created_by = User::find($item->created_by);
                $updated_by = User::find($item->updated_by);
                $json['rows'][] = array(
                    "id" => $item->exam_id,
                    "title" => $item->title,
                    "subject_id" => $item->subject_id,
                    "subject_name" => $item->subject_name,
                    "status" => $item->exam_status,
                    "mark" => $item->mark,
                    "created_at" => $item->created_at,
                    "updated_at" => $item->updated_at,
                    "created_by" => $created_by != null ? $created_by->last_name . " " . $created_by->first_name : "",
                    "updated_by" => $updated_by != null ? $updated_by->last_name . " " . $updated_by->first_name : "",
                    //date('Y-m-d H:i:s')ss
                );
            }
        }
        return response()->json($json);
    }

    public function create()
    {
        return view('admin.exam.create');
    }

    public function store(Request $request)
    {
        //Kiểm tra giá trị slogan_title, slogan_value
        // $this->validate(
        //     $request,
        //     [
        //         //Kiểm tra giá trị rỗng
        //         'title' => 'required'
        //     ],
        //     [
        //         //Tùy chỉnh hiển thị thông báo
        //         'title.required' => 'Bạn chưa nhập tiêu đề!'
        //     ]
        // );

        //Lấy giá trị slide đã nhập
        date_default_timezone_set(Util::$time_zone);
        $allRequest = $request->all();
        $title = $allRequest['title'];
        $mark = $allRequest['mark'];
        $note = $allRequest['note'];
        $subject_id = $allRequest['subject_id'];
        $status = $allRequest['status'] == "true" ? 1 : 0;
        $questions = $allRequest['questions'];

        // if (array_key_exists("status", $allRequest)) {
        //     $status = $allRequest['status'];
        // } else {
        //     $status = "";
        // }

        //Gán giá trị vào array
        $dataInsertToDatabase = array(
            'title' => $title,
            'subject_id' => $subject_id,
            'note' => $note,
            'mark' => $mark,
            'status' => $status,
            'created_by' => auth()->user()->id,
            'created_at' => date(Util::$date_time_format),
        );

        //Insert
        $id = DB::table($this->table_name)->insertGetId($dataInsertToDatabase);
        $insertData = DB::table($this->table_name)->where('id', $id)->get();
        $count_error_questions = 0;
        $count_error_details = 0;
        if (count($insertData) > 0) {
            foreach ($questions as $key => $item) {
                $question = array(
                    "exam_id" => $id,
                    "idx" => $item["question_idx"],
                    "mark" => $item["question_mark"],
                    "content" => $item["question_content"],
                    "question_type_id" => intval($item["question_type"]),
                    'created_by' => auth()->user()->id,
                    'created_at' => date(Util::$date_time_format),
                );
                $question_id = DB::table("questions")->insertGetId($question);
                if (count(DB::table("questions")->where('id', $question_id)->get()) > 0) {
                    if ($question["question_type_id"] == 1) {
                        foreach ($item["answers"] as $k => $itm) {
                            $answer = array(
                                "question_id" => $question_id,
                                "name" => $itm["answer_name"],
                                "content" => $itm["answer_content"],
                                "is_answer" => $itm["is_answer"],
                                'created_by' => auth()->user()->id,
                                'created_at' => date(Util::$date_time_format),
                            );
                            if (DB::table("question_details")->insert($answer)) {

                            } else {
                                $count_error_details += 1;
                            }
                        }
                    }
                } else {
                    $count_error_questions += 1;
                }

            } // end-foreach
            //Session::flash('success', 'Thêm mới thành công!');
            $response = array("result" => 1, "msg" => "Thêm mới thành công!");
        } else {
            //Session::flash('error', 'Thêm mới thất bại!');
            $response = array("result" => 0, "msg" => "Thêm mới thất bại!");
        }

        return response()->json($response);
        //Thực hiện chuyển trang
        //return redirect('admin/ql-bai-kiem-tra/create');
    }

    public function edit($id)
    {
        //Lấy dữ liệu từ Database với các trường được lấy và với điều kiện id = $id
        $getData = DB::table($this->table_name)->select('id', 'title', "mark" , 'subject_id', 'note', 'status')->where('id', $id)->get();
        $getDataQuestion = DB::table("questions")->select()->where('exam_id', $id)->orderBy('idx')->get();
        $datas = [];
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
                        "is_answer" => $itm->is_answer,
                    );
                }
                $data["answers"] = $details;
            }
            $datas[] = $data;

        }
        //Gọi đến file edit.blade.php trong thư mục "resources/views/slide" với giá trị gửi đi tên getSlideById = $getData
        return view('admin.exam.edit')
        ->with('getDataById', $getData)
        ->with('question_data', $getDataQuestion)
        ->with('data', $datas)
        ;
    }

    public function update(Request $request)
    {
        //Cap nhat sua hoc sinh
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $questions = $request['questions'];

        $updateData = DB::table($this->table_name)->where('id', $request->id)->update([
            'title' => $request->title,
            'subject_id' => $request->subject_id,
            'mark' => $request->mark,
            'note' => $request->note,
            'status' => $request->status == "true" ? 1 : 0,
            'updated_by' => auth()->user()->id,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $arr_question_id = [];
        $arr_answer_id =[];
        $count_error_details =0;
        $count_error_questions =0;
        //Kiểm tra lệnh update để trả về một thông báo
        if ($updateData) {
         foreach ($questions as $key => $item) {
            $question = array(
                "exam_id" => $request->id,
                "idx" => $item["question_idx"],
                "mark" => $item["question_mark"],
                "content" => $item["question_content"],
                "question_type_id" => intval($item["question_type"]),
                'created_by' => auth()->user()->id,
                'created_at' => date(Util::$date_time_format),
            );

				//$question_id = DB::table("questions")->insertGetId($question);
            $question_id = null;
            if($item["question_id"]!=null){
               $res= DB::table("questions")->where('id', $item["question_id"])->update([
						//"exam_id" => $request->id,
                  "idx" => $item["question_idx"],
                  "content" => $item["question_content"],
                  "question_type_id" => intval($item["question_type"]),
                  'updated_by' => auth()->user()->id,
                  'updated_at' => date('Y-m-d H:i:s'),
              ]);
               $question_id = intval($item["question_id"]);

           }else{
               $question = array(
                  "exam_id" => $request->id,
                  "idx" => $item["question_idx"],
                  "content" => $item["question_content"],
                  "question_type_id" => intval($item["question_type"]),
                  'created_by' => auth()->user()->id,
                  'created_at' => date(Util::$date_time_format),
              );
               $question_id = DB::table("questions")->insertGetId($question);

           }



           if ($question_id!=null) {
               $arr_question_id[] = $question_id;
               $arr_answer_id =[];
               if (intval($item["question_type"]) == 1) {
                foreach ($item["answers"] as $k => $itm) {
                 if($itm["answer_id"]!=null){
                    $res= DB::table("question_details")->where('id', $itm["answer_id"])->update([
                       "question_id" => $question_id,
                       "name" => $itm["answer_name"],
                       "content" => $itm["answer_content"],
                       "is_answer" => $itm["is_answer"],
                       'updated_by' => auth()->user()->id,
                       'updated_at' => date('Y-m-d H:i:s'),
                   ]);

                    if ($res){
                       $arr_answer_id[] = intval($itm["answer_id"]);
                   }else{
                       $count_error_details += 1;
                   }

               }else{
                $answer = array(
                   "question_id" => $question_id,
                   "name" => $itm["answer_name"],
                   "content" => $itm["answer_content"],
                   "is_answer" => $itm["is_answer"],
                   'created_by' => auth()->user()->id,
                   'created_at' => date(Util::$date_time_format),
               );
                $answer_id = DB::table("question_details")->insertGetId($answer);
                if ($answer_id!=null) {
                   $arr_answer_id[] = intval($itm["answer_id"]);
               } else {
                   $count_error_details += 1;
               }
           }
       }

   }

   if($count_error_details == 0){
      $data = DB::table("question_details")->where('question_id', $item["question_id"])->whereNotIn("id", $arr_answer_id)->pluck('id')->toArray();

      if($data!=null){
        DB::table("question_details")->whereIn('id', $data)->delete();
    }
}
} else {
    $count_error_questions += 1;
}

            } // end-foreach

            if($count_error_questions == 0){
                $data = DB::table("questions")->where('exam_id', $request->id)->whereNotIn("id", $arr_question_id)->pluck('id')->toArray();
                
                if($data!=null){
                    DB::table("questions")->whereIn('id', $data)->delete();
                }
            }



           //Session::flash('success', 'Cập nhật thông tin thành công!');
            $response = array("result" => 1, "msg" => "Cập nhật thành công!");
        } else {
            //Session::flash('error', 'Cập nhật thông tin thất bại!');
         $response = array("result" => 0, "msg" => "Thêm mới thất bại!");
     }

     return response()->json($response);

        //Thực hiện chuyển trang
       // return redirect('admin/ql-bai-kiem-tra/' . $request->id . '/edit');
 }

 public function destroy($id)
 {
        //Thực hiện câu lệnh xóa với giá trị id = $id trả về
    $deleteData = DB::table($this->table_name)->where('id', $id)->delete();

        //Kiểm tra lệnh delete để trả về một thông báo
    if ($deleteData) {
        Session::flash('success', 'Xóa thành công!');
    } else {
        Session::flash('error', 'Xóa thất bại!');
    }

        //Thực hiện chuyển trang
    return redirect('admin/ql-bai-kiem-tra');
}

public function status(Request $request)
{
    try {
        $id = $request->input("id");
        $status = $request->input("status");
        if ($id != -1) {
            DB::table($this->table_name)->where('id', $id)
            ->update(['status' => $status]);
            $response = array("result" => 1, "msg" => "Cập Nhật thành công!");
        } else {
            $response = array("result" => 0, "msg" => "Không tim thấy dữ liệu!");
        }
        return response()->json($response);
    } catch (exception $ex) {
        $response = array("result" => 0, "msg" => "Lỗi server!");
        return response()->json($response);
    }
}

public function delete(Request $request)
{
    try {
        $arr_id = $request->input("id");
        $count = 0;
        foreach ($arr_id as $key => $item) {

            if($this->checkBeforeDelete($item) == 0){
                $deleteData = DB::table($this->table_name)->where('id', $item)->delete();
            
                //Kiểm tra lệnh delete để trả về một thông báo
                if ($deleteData) {
                    $count += 1;
                }
            }
        }

        if ($count == count($arr_id)) {
            $response = array("result" => 1, "msg" => "Xóa thành công!");
        } else {
            if ($count == 0) {
                $response = array("result" => 0, "msg" => "Xóa thất bại. Một số dữ liệu đã được gán cho lớp học làm bài KT!");
            } else {
                $response = array("result" => 2, "msg" => "Xóa thất bại. Một số dữ liệu đã được gán cho lớp học làm bài KT");
            }
        }

        return response()->json($response);
    } catch (exception $ex) {
        $response = array("result" => 0, "msg" => "Xóa thất bại!");
        return response()->json($response);
    }
}

public function viewExamContent($id)
{

    $json = [];
    $question_details = "";
        //echo $id;
    $datas = DB::table("questions")
    ->where('exam_id', $id)
    ->orderBy("idx")
    ->get();

    foreach ($datas as $key => $item) {
            //$roles  = Util::get_role_name($item->id);
        $created_by = User::find($item->created_by);
        $updated_by = User::find($item->updated_by);
        $question_details = "";
        if($item->question_type_id == 1){
            $details = DB::table("question_details")->where('question_id', $item->id)->get();
            foreach($details as $kk => $dt){
                if($dt->is_answer == 1){
                    $question_details = $question_details . "<br/> <input type=\"radio\" name=\"".$item->id."\" value=\"".$dt->name."\" checked=\"checked\"> " . $dt->name . ". " . $dt->content;
                }else{
                    $question_details = $question_details . "<br/> <input type=\"radio\" name=\"".$item->id."\" value=\"".$dt->name."\" disabled=\"true\"> " . $dt->name . ". " . $dt->content;
                }
            }
        }
        $json[] = array(
            "id" => $item->id,
            "idx" => "Câu " . $item->idx,
            "content" => "<strong>" .  $item->content . "</strong> " .  $question_details,
            "mark" => $item->mark,
            "exam_id" => $item->exam_id,
            "created_at" => $item->created_at,
            "updated_at" => $item->updated_at,
            "created_by" => $created_by != null ?  $created_by->last_name . " " . $created_by->fist_name : "",
            "updated_by" => $updated_by != null ?  $updated_by->last_name . " " . $updated_by->fist_name : "",
                //date('Y-m-d H:i:s')
        );
    }

    return view("admin/exam/view_exam")
    ->with("id", $id)
    ->with("datas", $json);
}

public function addExamContent($id)
{

    $json = [];
        //echo $id;
    $datas = DB::table("questions")
    ->where('exam_id', $id)
    ->orderBy("idx")
    ->get();

    foreach ($datas as $key => $item) {
            //$roles  = Util::get_role_name($item->id);
        $created_by = User::find($item->created_by);
        $updated_by = User::find($item->updated_by);
        $json[] = array(
            "id" => $item->exam_id,
            "idx" => "Câu " . $item->idx,
            "mark" => $item->mark,
            "content" => $item->content,
            "exam_id" => $item->exam_id,
            "created_at" => $item->created_at,
            "updated_at" => $item->updated_at,
            "created_by" => $created_by != null ? $created_by->last_name . " " . $created_by->fist_name : "",
            "updated_by" => $updated_by != null ? $updated_by->last_name . " " . $updated_by->fist_name : "",
                //date('Y-m-d H:i:s')
        );
    }

    return view("admin/exam/view_exam")
    ->with("id", $id)
    ->with("datas", $json);
}

public function addQuestion(Request $request)
{
    if ($request->has("data")) {
        $data = $request->input("data");
        return view("admin/exam/add_question")
        ->with("data", $data)
        ;
    }
    return view("admin/exam/add_question")
    ;
}

    public function checkBeforeDelete($id)
    {
        try
        {
            $res = 0;
            if(DB::table("exam_classes")
                ->where('exam_id', $id)
                ->exists()
            )
            {
                $res = $res + 1;
            }

            if(DB::table("answers")
            ->where('exam_id', $id)
            ->exists()
            )
            {
                $res = $res + 1;
            }

            return($res);
        } catch (exception $ex) {
            return(-1);
        }
    }

}
