<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ManagerController;
use App\Models\User;
use App\Util;
use App\PeerAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamClassController extends ManagerController
{
    private $table_name = "exam_classes";
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.exam_class.list')
        ;
    }

    public function find(Request $request)
    {
        //$subject_id = intval($request->input("subject_id"));
        $current = intval($request->input("current"));
        $rowCount = intval($request->input("rowCount"));
        $searchPhrase = $request->input("searchPhrase");

        $query = DB::table($this->table_name)
        ->leftJoin('exams', $this->table_name . '.exam_id', '=', 'exams.id')
        ->leftJoin('subjects', 'exams.subject_id', '=', 'subjects.id')
        ->leftJoin('classes', $this->table_name . '.class_id', '=', 'classes.id')
        ;

        // if($subject_id!=-1){
        //     $query->where($this->table_name . ".subject_id", $subject_id);
        // }

        if(auth()->user()->level!=0){
            $query->where($this->table_name . ".created_by", auth()->user()->id);
        }
        //search
        // if (!empty($searchPhrase)) {
        //     $query->where($this->table_name . ".title", "LIKE", '%' . $searchPhrase . "%")
        //         ->orWhere("subjects.name", "LIKE", '%' . $searchPhrase . "%");
        // }

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
          //  $query->orderBy("exams.title", "asc")
            $query->orderBy($this->table_name . ".created_at", "desc")
                 ->orderBy($this->table_name . ".id", "desc")
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
        // $data = $query->select('exams.title as exam_title', 'exams.id as exam_id', 'subjects.name as subject_name', 'classes.name as class_name', $this->table_name . '.id', $this->table_name . '.created_at', $this->table_name . '.created_by', $this->table_name . '.updated_at', $this->table_name . '.updated_by', $this->table_name . '.time_open', $this->table_name . '.time_close', $this->table_name . '.time_limit', $this->table_name . '.status as status')

        $data = $query->select('exams.title as exam_title', 'exams.id as exam_id', 'subjects.name as subject_name', 'classes.name as class_name', $this->table_name . '.*')

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
                    "id" => $item->id,
                    "exam_title" => $item->exam_title,
                    "exam_id" => $item->exam_id,
                    "subject_name" => $item->subject_name,
                    "class_name" => $item->class_name,
                    "time_open" => $item->time_open,
                    "time_close" => $item->time_close,
                    "time_limit" => $item->time_limit . " phút",
                    "time_pa_open" => $item->time_pa_open,
                    "time_pa_close" => $item->time_pa_close,
                    "time_close" => $item->time_close,
                    "is_assessment" => $item->is_assessment,
                    "assessment_quantity" => $item->assessment_quantity,
                    "ratio" => $item->ratio_1 . " - " . $item->ratio_2 . " - " . $item->ratio_3,
                    "time_close" => $item->time_close,
                    "status" => $item->status,
                    "created_at" => $item->created_at,
                    "updated_at" => $item->updated_at,
                    "created_by" => $created_by != null ? $created_by->last_name . " "  . $created_by->first_name : "",
                    "updated_by" => $updated_by != null ? $updated_by->last_name . " "  . $updated_by->first_name : "",
                );
            }
        }
        return response()->json($json);
    }


    public function calcMark($exam_class_id)
    {
        //Lấy dữ liệu từ Database với các trường được lấy và với điều kiện id = $id
        $getData = PeerAssessment::CalcPeerAssessmentMark($exam_class_id);

        $class = DB::table("classes")
            ->select("classes.name", "school_years.year_open", "school_years.year_close")
            ->leftJoin('exam_classes', 'exam_classes.class_id', '=', 'classes.id')
            ->leftJoin('school_years', 'school_years.id', '=', 'classes.school_year_id')
            ->where("exam_classes.id", $exam_class_id)
            ->first()
        ;

        $exam = DB::table("exams")
            ->select("exams.title", "subjects.name as subject_name")
            ->leftJoin('exam_classes', 'exam_classes.exam_id', '=', 'exams.id')
            ->leftJoin('subjects', 'subjects.id', '=', 'exams.subject_id')
            ->where("exam_classes.id", $exam_class_id)
            ->first()
        ;

        //Gọi đến file edit.blade.php trong thư mục "resources/views/slide" với giá trị gửi đi tên getSlideById = $getData
        return view('admin.exam_class.mark')
        ->with('mark_data', $getData)
        ->with('class', $class)
        ->with('exam', $exam)
        ;
    }

    public function create()
    {
        return view('admin.exam_class.create');
    }

    public function store(Request $request)
    {

        //Lấy giá trị slide đã nhập
        date_default_timezone_set(Util::$time_zone);
        $allRequest = $request->all();
        $school_year_id = $allRequest['school_year_id'];
        $class_id = $allRequest['class_id'];
        $subject_id = $allRequest['subject_id'];
        $exam_id = $allRequest['exam_id'];
        $time_open = $allRequest['time_open'];
        $time_close = $allRequest['time_close'];
        $time_pa_open = $allRequest['time_pa_open'];
        $time_pa_close = $allRequest['time_pa_close'];
        $time_limit = $allRequest['time_limit'];
        $status = $allRequest['status'] == "true" ? 1 : 0;
        $is_assessment = $allRequest["is_assessment"] == "true" ? 1 : 0;
        $ratio_1 = $allRequest["ratio_1"];
        $ratio_2 = $allRequest["ratio_2"];
        $ratio_3 = $allRequest["ratio_3"];

        //Gán giá trị vào array
        $dataInsertToDatabase = array(
            //'school_year_id' => $school_year_id,
            //'subject_id' => $subject_id,
            'class_id' => $class_id,
            'exam_id' => $exam_id,
            'time_open' => $time_open,
            'time_close' => $time_close,
            'time_pa_open' => $time_pa_open,
            'time_pa_close' => $time_pa_close,
            'time_limit' => $time_limit,
            'status' => $status,
            'is_assessment' => $is_assessment,
            'ratio_1' => $ratio_1,
            'ratio_2' => $ratio_2,
            'ratio_3' => $ratio_3,
            'assessment_quantity' => $request->assessment_quantity,
            'created_by' => auth()->user()->id,
            'created_at' => date(Util::$date_time_format),
        );

        //Insert
        $id = DB::table($this->table_name)->insertGetId($dataInsertToDatabase);
        $insertData = DB::table($this->table_name)->where('id', $id)->get();
        $count_error_questions = 0;
        $count_error_details = 0;
        if (count($insertData) > 0) {

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
        $getData = DB::table($this->table_name)
        ->leftJoin('exams', $this->table_name . '.exam_id', '=', 'exams.id')
        ->leftJoin('classes', $this->table_name . '.class_id', '=', 'classes.id')
        ->select($this->table_name . ".id", $this->table_name . ".ratio_1", $this->table_name . ".ratio_2", $this->table_name . ".ratio_3", $this->table_name . ".exam_id", $this->table_name . ".class_id", "exams.subject_id as subject_id", $this->table_name . ".time_open", $this->table_name . ".time_close", $this->table_name . ".time_pa_open", $this->table_name . ".time_pa_close", $this->table_name . ".time_limit", $this->table_name . ".is_assessment", $this->table_name . ".assessment_quantity", $this->table_name . ".status as status", "classes.school_year_id as school_year_id")->where($this->table_name . '.id', $id)->get();

        $arr_exams = null;
        if (count($getData) > 0) {
            if( auth()->user()->level == 0){
                $arr_exams = DB::table("exams")
                ->where('subject_id', $getData[0]->subject_id)
                ->where('status', 1)
                ->select("exams.id as id", "exams.title as title")
                ->get();
            }else{
                $arr_exams = DB::table("exams")
                ->where('subject_id', $getData[0]->subject_id)
                ->where('created_by', auth()->user()->id)
                ->where('status', 1)
                ->select("exams.id as id", "exams.title as title")
                ->get();
            }


        }

        $arr_classes = null;
        if (count($getData) > 0) {
            if( auth()->user()->level == 0){
               $arr_classes = DB::table("classes")
               ->leftJoin('teacher_class_subject', 'classes.id', '=', 'teacher_class_subject.class_id')
               ->where('classes.school_year_id', $getData[0]->school_year_id)
               ->where('teacher_class_subject.subject_id', $getData[0]->subject_id)
               ->where('status', 1)
               ->select("classes.id as id", "classes.name as name")
               ->distinct()
               ->get();
           }
           else
           {
            $arr_classes = DB::table("classes")
            ->leftJoin('teacher_class_subject', 'classes.id', '=', 'teacher_class_subject.class_id')
            ->where('classes.school_year_id', $getData[0]->school_year_id)
            ->where('teacher_class_subject.subject_id', $getData[0]->subject_id)
            ->where('teacher_id', auth()->user()->id)
            ->where('status', 1)
            ->select("classes.id as id", "classes.name as name")
            ->distinct()
            ->get();
        }
    }

        //Gọi đến file edit.blade.php trong thư mục "resources/views/slide" với giá trị gửi đi tên getSlideById = $getData
    return view('admin.exam_class.edit')
    ->with('getDataById', $getData)
    ->with('arr_exams', $arr_exams)
    ->with('arr_classes', $arr_classes)
    ;
}

public function update(Request $request)
{
        //Cap nhat sua hoc sinh
    date_default_timezone_set("Asia/Ho_Chi_Minh");

    $updateData = DB::table($this->table_name)->where('id', $request->id)->update([
        'class_id' => $request->class_id,
        'exam_id' => $request->exam_id,
        'time_open' => $request->time_open,
        'time_close' => $request->time_close,
        'time_pa_open' => $request->time_pa_open,
        'time_pa_close' => $request->time_pa_close,
        'time_limit' => $request->time_limit,
        'assessment_quantity' => $request->assessment_quantity,
        'ratio_1' => $request->ratio_1,
        'ratio_2' => $request->ratio_2,
        'ratio_3' => $request->ratio_3,
        'status' => $request->status == "true" ? 1 : 0,
        'is_assessment' => $request->is_assessment == "true" ? 1 : 0,
        'updated_by' => auth()->user()->id,
        'updated_at' => date('Y-m-d H:i:s'),
    ]);

    if ($updateData) {
            //Session::flash('success', 'Cập nhật thông tin thành công!');
        $response = array("result" => 1, "msg" => "Cập nhật thành công!");
    } else {
            //Session::flash('error', 'Cập nhật thông tin thất bại!');
        $response = array("result" => 0, "msg" => "Thêm mới thất bại!");
    }

    return response()->json($response);

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

public function get_exam(Request $request)
{
    try {
        $json = array();
        $subject_id = $request->input("subject_id");
        if ($subject_id > 0) {
            if( auth()->user()->level == 0){
                $arr_data = DB::table("exams")
                ->where('subject_id', $subject_id)
                ->where('status', 1)
                ->select("exams.id as exam_id", "exams.title as exam_title")
                ->get();
            }else{
              $arr_data = DB::table("exams")
              ->where('subject_id', $subject_id)
              ->where('created_by', auth()->user()->id)
              ->where('status', 1)
              ->select("exams.id as exam_id", "exams.title as exam_title")
              ->get();
          }

          foreach ($arr_data as $key => $item) {
            $json[] = array(
                "id" => $item->exam_id,
                "title" => $item->exam_title,
            );
        }

        $response = array("result" => 1, "data" => $json, "msg" => "");
    } else {
        $response = array("result" => 0, "data" => $json, "msg" => "Không tim thấy dữ liệu!");
    }
    return response()->json($response);
} catch (exception $ex) {
    $response = array("result" => 0, "msg" => "Lỗi server!");
    return response()->json($response);
}
}

public function get_class(Request $request)
{
    try {
        $json = array();
        $school_year_id = $request->input("school_year_id");
        $subject_id = $request->input("subject_id");
        if ($school_year_id > 0 && $subject_id > 0) {
            if( auth()->user()->level == 0){
                $arr_data = DB::table("classes")
                ->leftJoin('teacher_class_subject', 'classes.id', '=', 'teacher_class_subject.class_id')
                ->where('classes.school_year_id', $school_year_id)
                ->where('teacher_class_subject.subject_id', $subject_id)
                ->where('status', 1)
                ->select("classes.id as class_id", "classes.name as class_name")
                ->distinct()
                ->get();
            }else{
                $arr_data = DB::table("classes")
                ->leftJoin('teacher_class_subject', 'classes.id', '=', 'teacher_class_subject.class_id')
                ->where('classes.school_year_id', $school_year_id)
                ->where('teacher_class_subject.subject_id', $subject_id)
                ->where('teacher_class_subject.teacher_id', auth()->user()->id)
                ->where('status', 1)
                ->select("classes.id as class_id", "classes.name as class_name")
                ->distinct()
                ->get();
            }

            foreach ($arr_data as $key => $item) {
                $json[] = array(
                    "id" => $item->class_id,
                    "name" => $item->class_name,
                );
            }

            $response = array("result" => 1, "data" => $json, "msg" => "");
        } else {
            $response = array("result" => 0, "data" => $json, "msg" => "Không tim thấy dữ liệu!");
        }
        return response()->json($response);
    } catch (exception $ex) {
        $response = array("result" => 0, "msg" => "Lỗi server!");
        return response()->json($response);
    }
}

public function checkBeforeDelete($id)
{
    try
    {
        $res = 0;
        if(DB::table("answers")
            ->where('exam_class_id', $id)
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
