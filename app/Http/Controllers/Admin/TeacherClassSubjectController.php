<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\AdminController;
use App\Models\User;
use App\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
class TeacherClassSubjectController extends AdminController
{
    private $table_name = "teacher_class_subject";
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.teacher_class_subject.list');
    }
    public function find(Request $request)
    {
        $school_year_id = intval($request->input("school_year_id"));
        $teacher_id = intval($request->input("teacher_id"));
        $subject_id = intval($request->input("subject_id"));

        $current = intval($request->input("current"));
        $rowCount = intval($request->input("rowCount"));
        $searchPhrase = $request->input("searchPhrase");

        $query = DB::table($this->table_name)
                ->leftJoin('users', $this->table_name . '.teacher_id', '=', 'users.id')
                ->leftJoin('classes', $this->table_name . '.class_id', '=', 'classes.id')
                ->leftJoin('subjects', $this->table_name . '.subject_id', '=', 'subjects.id')
                ->leftJoin('school_years', 'classes.school_year_id', '=', 'school_years.id')
                ->where("classes.school_year_id", $school_year_id)
        ;


        if($teacher_id > 0){
             $query->where($this->table_name . ".teacher_id", $teacher_id);
        }



        if($subject_id > 0){
             $query->where($this->table_name . ".subject_id", $subject_id);
        }

        //search
        if (!empty($searchPhrase)) {
            $query->where("users.first_name", "LIKE", '%' . $searchPhrase . "%")
                ->orWhere("users.last_name", "LIKE", '%' . $searchPhrase . "%")
                ->orWhere("classes.name", "LIKE", '%' . $searchPhrase . "%")
                ->orWhere("subjects.name", "LIKE", '%' . $searchPhrase . "%")
            ;
        }
        //sort
        if (isset($request->sort) && is_array( $request->sort)) {
            foreach ($request->sort as $key => $value) {
                switch ($key) {
                    case "class_name":
                        $query->orderBy( "classes.name", $value);
                    break;
                    case "subject_name":
                        $query->orderBy( "subjects.name", $value);
                    break;
                    case "full_name":
                        $query->orderBy( "users.first_name", $value);
                    break;
                }
            }
        } else {
            $query->orderBy("classes.name", "asc")
            ->orderBy($this->table_name . ".created_at", "asc");
        }
        // print_r((string)$query);exit;
        $data = $query->select($this->table_name . '.*', 'users.first_name as first_name', 'users.last_name as last_name', 'subjects.name as subject_name', 'classes.name as class_name', "school_years.name as school_year_name")
            ->get();
        $total = count($data);
        if ($rowCount > 1 && $current <= $total) {
            $limit = $rowCount * ($current - 1);
            $query->limit($rowCount)->offset($limit);
        } else {
            // $query->limit($rowCount);
        }
        $data = $query->get();
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
                    //"id" => $item->teacher_id . "-" . $item->class_id . "-" . $item->subject_id,
                    "id" => $item->id,
                    "subject_name" => $item->subject_name,
                    "class_name" => $item->class_name,
                    "first_name" => $item->first_name,
                    "last_name" => $item->last_name,
                    "full_name" => $item->last_name . " " . $item->first_name,
                    "school_year_name" => $item->school_year_name,
                    "created_at" => $item->created_at,
                    "updated_at" => $item->updated_at,
                    "created_by" => $created_by != null ? $created_by->last_name . " "  . $created_by->first_name : "",
                    "updated_by" => $updated_by != null ? $created_by->last_name . " "  . $created_by->first_name : "",
                );
            }
        }
        return response()->json($json);
    }
    public function create()
    {
        return view('admin.teacher_class_subject.create');
    }
    public function store(Request $request)
    {
        //Ki???m tra gi?? tr??? slogan_title, slogan_value
        $this->validate($request,
            [
                //Ki???m tra gi?? tr??? r???ng
                'class_id' => 'required',
                'subject_id' => 'required',
                'teacher_id' => 'required',
            ],
            [
                //T??y ch???nh hi???n th??? th??ng b??o
                'class_id.required' => 'B???n ch??a ch???n l???p h???c!',
                'subject_id.required' => 'B???n ch??a ch???n m??n h???c!',
                'teacher_id.required' => 'B???n ch??a ch???n gi??o vi??n!',
            ]
        );
        //L???y gi?? tr??? slide ???? nh???p
        date_default_timezone_set(Util::$time_zone);
        $school_year_id = $request->school_year_id;
        $class_id = $request->class_id;
        $subject_id = $request->subject_id;
        $teacher_id = $request->teacher_id;
            //G??n gi?? tr??? v??o array
        $dataInsertToDatabase = array(
            'class_id' => $class_id,
            'subject_id' => $subject_id ,
            'teacher_id' => $teacher_id ,
            'created_by' => auth()->user()->id,
            'created_at' => date(Util::$date_time_format),
        );
        if($this->checkDataExists($dataInsertToDatabase, null)==true){
            $dataInsertToDatabase["school_year_id"] = $school_year_id;
            Session::flash('error', 'Ph??n M??n n??y ???? t???n t???i!');
            Session::flash('data', $dataInsertToDatabase);
        }else{
                //Insert v??o database
            $id = DB::table($this->table_name)->insertGetId($dataInsertToDatabase);
            $insertData = DB::table($this->table_name)->where('id', $id)->get();
                //echo($insertData);
            if (count($insertData) > 0) {
                Session::flash('success', 'Th??m m???i th??nh c??ng!');
            } else {
                Session::flash('error', 'Th??m m???i th???t b???i!');
                $dataInsertToDatabase["school_year_id"] = $school_year_id;
                Session::flash('data', $dataInsertToDatabase);
            }
        }
        return redirect('admin/ql-phan-mon/them-moi');
    }
    public function checkDataExists($data, $id){
        $res = false;
        if($id!=null){
            if(DB::table($this->table_name)
                ->where('class_id', $data["class_id"])
                ->where('subject_id', $data["subject_id"])
                ->where('teacher_id', $data["teacher_id"])
                ->where('id',"!=", $id)
                ->exists())
            {
                $res = true;
            }
        }else{
            if(DB::table($this->table_name)
                ->where('class_id', $data["class_id"])
                ->where('subject_id', $data["subject_id"])
                ->where('teacher_id', $data["teacher_id"])
                ->exists())
            {
                $res = true;
            }
        }
        return($res);
    }
    public function edit($id)
    {
        $getData = DB::table($this->table_name)
                    ->leftJoin("classes", "classes.id", "=", $this->table_name . ".class_id")
                    ->select($this->table_name . '.id', 'subject_id', 'teacher_id', 'class_id', "classes.school_year_id as school_year_id")->where($this->table_name . '.id', $id)->get();
        $classes = null;
        if(count($getData)>0){
            $classes = $this->get_class_by_school_year($getData[0]->school_year_id);
        }
        return view('admin.teacher_class_subject.edit')
                ->with('getDataById', $getData)
                ->with('classes', $classes)
                ;
    }
    public function update(Request $request)
    {
        //Cap nhat sua hoc sinh
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        //Ki???m tra gi?? tr??? tenhocsinh, sodienthoai, khoi
        $this->validate($request,
            [
                //Ki???m tra gi?? tr??? r???ng
                'class_id' => 'required',
                'subject_id' => 'required',
                'teacher_id' => 'required',
            ],
            [
                //T??y ch???nh hi???n th??? th??ng b??o
                'class_id.required' => 'B???n ch??a ch???n l???p h???c!',
                'subject_id.required' => 'B???n ch??a ch???n m??n h???c!',
                'teacher_id.required' => 'B???n ch??a ch???n gi??o vi??n!',
            ]
        );
        $id = $request->id;
        $school_year_id = $request->school_year_id;
        $class_id = $request->class_id;
        $subject_id = $request->subject_id;
        $teacher_id = $request->teacher_id;
        $updateData = array(
            'class_id' => $class_id,
            'subject_id' => $subject_id ,
            'teacher_id' => $teacher_id ,
            'updated_by' => auth()->user()->id,
            'updated_at' => date('Y-m-d H:i:s'),
        );
        if($this->checkDataExists($updateData, $id)){
            Session::flash('error', 'Ph??n M??n n??y ???? t???n t???i!');
            $updateData["school_year_id"] = $school_year_id;
            Session::flash('data', $updateData);
        }else{
            $res = DB::table($this->table_name)->where('id', $id)
            ->update($updateData);
                  //Ki???m tra l???nh update ????? tr??? v??? m???t th??ng b??o
            if ($res) {
                Session::flash('success', 'C???p nh???t th??ng tin th??nh c??ng!');
            } else {
                Session::flash('error', 'C???p nh???t th??ng tin th???t b???i!');
                $updateData["school_year_id"] = $school_year_id;
                Session::flash('data', $updateData);
            }
        }
        //Th???c hi???n chuy???n trang
        return redirect('admin/ql-phan-mon/' . $request->id . '/cap-nhat');
    }

    public function destroy($id)
    {
        //Th???c hi???n c??u l???nh x??a v???i gi?? tr??? id = $id tr??? v???
        $deleteData = DB::table($this->table_name)->where('id', $id)->delete();
        //Ki???m tra l???nh delete ????? tr??? v??? m???t th??ng b??o
        if ($deleteData) {
            Session::flash('success', 'X??a th??nh c??ng!');
        } else {
            Session::flash('error', 'X??a th???t b???i!');
        }
        //Th???c hi???n chuy???n trang
        return redirect('admin/ql-phan-mon');
    }

    public function delete(Request $request)
    {
        try
        {
            $arr_id = $request->input("id");
            $count = 0;
            foreach ($arr_id as $key => $item) {
                //Th???c hi???n c??u l???nh x??a v???i gi?? tr??? id = $id tr??? v???
                $deleteData = DB::table($this->table_name)->where('id', $item)->delete();
                //Ki???m tra l???nh delete ????? tr??? v??? m???t th??ng b??o
                if ($deleteData) {
                    $count += 1;
                }
            }
            if ($count == count($arr_id)) {
                $response = array("result" => 1, "msg" => "X??a th??nh c??ng!");
            } else {
                if ($count == 0) {
                    $response = array("result" => 0, "msg" => "X??a th???t b???i!");
                } else {
                    $response = array("result" => 2, "msg" => "M???t s??? d??? li???u kh??ng x??a ???????c do l???i server. Xin vui l??ng th???c hi???n l???i!");
                }
            }
            return response()->json($response);
        } catch (exception $ex) {
            $response = array("result" => 0, "msg" => "X??a th???t b???i!");
            return response()->json($response);
        }
    }

    public function get_class(Request $request)
    {
        try {

            $school_year_id = $request->input("school_year_id");
            $json = $this->get_class_by_school_year($school_year_id);
            if ($json!=null && count($json) > 0) {
                $response = array("result" => 1, "data" => $json, "msg" => "");
            } else {
                $response = array("result" => 1, "data" => $json, "msg" => "Kh??ng tim th???y d??? li???u!");
            }
            return response()->json($response);
        } catch (exception $ex) {
            $response = array("result" => 0, "msg" => "L???i server!");
            return response()->json($response);
        }
    }

    public function get_class_by_school_year ($school_year_id){
        try {
            $json = array();
            if ($school_year_id > 0) {
                    $arr_data = DB::table("classes")
                    ->where('classes.school_year_id', $school_year_id)
                    ->where('status', 1)
                    ->select("classes.id as class_id", "classes.name as class_name")
                    ->orderBy("classes.name", "ASC")
                    ->distinct()
                    ->get();

                foreach ($arr_data as $key => $item) {
                    $json[] = array(
                        "id" => $item->class_id,
                        "name" => $item->class_name,
                    );
                }
            }
            return($json);
        } catch (exception $ex) {
         return(null);
        }
    }
}
