<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Models\User;
use App\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class ClassesController extends AdminController
{
    private $table_name = "classes";
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.classes.list')
        ;
    }

    public function find(Request $request)
    {
        $subject_id = intval($request->input("subject_id"));
        $school_year_id = intval($request->input("school_year_id"));
        $current = intval($request->input("current"));
        $rowCount = intval($request->input("rowCount"));
        $searchPhrase = $request->input("searchPhrase");

        $query = DB::table($this->table_name)
        ->leftJoin('teacher_class_subject', $this->table_name. '.id', '=', 'teacher_class_subject.class_id')
        //->leftJoin('subjects', 'teacher_class_subject.subject_id', '=', 'subjects.id')
        ->leftJoin('school_years', $this->table_name . '.school_year_id', '=', 'school_years.id')
        ;

        if($school_year_id!=""){
            $query->where($this->table_name . ".school_year_id", $school_year_id);
        }

        // if($subject_id!=""){
        //     $query->where("teacher_class_subject.subject_id", $subject_id);
        // }

        //$query->where($this->table_name . ".created_by", auth()->user()->id);

        //search
        if (!empty($searchPhrase)) {
            $query->where($this->table_name . ".name", "LIKE", '%' . $searchPhrase . "%")
                //->orWhere("subjects.name", "LIKE", '%' . $searchPhrase . "%")
                ->orWhere("school_years.name", "LIKE", '%' . $searchPhrase . "%");
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
            $query->orderBy($this->table_name . ".name", "asc")
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
        $data = $query->select( 'school_years.name as school_year_name', 'school_years.year_open as year_open', 'school_years.year_close as year_close', $this->table_name . '.id', $this->table_name . '.created_at', $this->table_name . '.created_by', $this->table_name . '.updated_at', $this->table_name . '.updated_by', $this->table_name . '.name as name', $this->table_name . '.status as status')
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
                    "name" => $item->name,
                    "school_year_name" => $item->school_year_name,                  
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

    public function create()
    {
        return view('admin.classes.create');
    }

   public function store(Request $request)
    {

        //Ki???m tra gi?? tr??? slogan_title, slogan_value
        $this->validate($request,
            [
                //Ki???m tra gi?? tr??? r???ng
                'school_year_id' => 'required',
                'name' => 'required',
            ],
            [
                //T??y ch???nh hi???n th??? th??ng b??o
                'school_year_id.required' => 'B???n ch??a ch???n th??ng tin N??m H???c!',
                'name.required' => 'B???n ch??a nh???p th??ng tin T??n L???p!',
            ]
        );

        //L???y gi?? tr??? slide ???? nh???p
        date_default_timezone_set(Util::$time_zone);

        $school_year_id = intval( $request->school_year_id);
        $name = $request->name;
        $status = (($request->status) == 'on') ? 1 : 0;

        $dataInsertToDatabase = array(
            'name' => $name,
            'school_year_id' => $school_year_id,
            'status' => $status,
            'created_by' => auth()->user()->id,
            'created_at' => date(Util::$date_time_format),
        );


        if($this->checkDataExists($dataInsertToDatabase, null)==true){
            Session::flash('error', 'L???p H???c n??y ???? t???n t???i!');
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
                Session::flash('data', $dataInsertToDatabase);
            }
        }
        //Th???c hi???n chuy???n trang
        return redirect('admin/ql-lop-hoc/them-moi');
    }

    public function checkDataExists($data, $id){
        $res = false;
        if($id!=null){
            if(DB::table($this->table_name)
                ->where('school_year_id', $data["school_year_id"])
                ->where('name', $data["name"])
                ->where('id',"!=", $id)
                ->exists())
            {
                $res = true;
            }
        }else{
            if(DB::table($this->table_name)
                ->where('school_year_id', $data["school_year_id"])
                ->where('name', $data["name"])
                ->exists())
            {
                $res = true;
            }
        }
        return($res);
    }

    public function edit($id)
    {
        $getData = DB::table($this->table_name)->select('id', 'name', 'school_year_id', 'status')->where('id', $id)->get();
        return view('admin.classes.edit')->with('getDataById', $getData);
    }

    public function update(Request $request)
    {
        //Cap nhat sua hoc sinh
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        //Ki???m tra gi?? tr??? tenhocsinh, sodienthoai, khoi
        $this->validate($request,
            [
                //Ki???m tra gi?? tr??? r???ng
                'school_year_id' => 'required',
                'name' => 'required',
            ],
            [
                //T??y ch???nh hi???n th??? th??ng b??o
                'school_year_id.required' => 'B???n ch??a ch???n th??ng tin N??m H???c!',
                'name.required' => 'B???n ch??a nh???p th??ng tin T??n L???p!',
            ]
        );

        $id = $request->id;
        $school_year_id = intval( $request->school_year_id);
        $name = $request->name;
        $status = (($request->status) == 'on') ? 1 : 0;

        $updateData = array(
            'name' => $name,
            'school_year_id' => $school_year_id,
            'status' => $status,
            'updated_by' => auth()->user()->id,
            'updated_at' => date('Y-m-d H:i:s'),
        );



        if($this->checkDataExists($updateData, $request->id)){
            Session::flash('error', 'L???p H???c n??y ???? t???n t???i!');
            Session::flash('data', $updateData);
        }else{
            $res = DB::table($this->table_name)->where('id', $id)
            ->update($updateData);
              //Ki???m tra l???nh update ????? tr??? v??? m???t th??ng b??o
            if ($res) {
                Session::flash('success', 'C???p nh???t th??ng tin th??nh c??ng!');
            } else {
                Session::flash('error', 'C???p nh???t th??ng tin th???t b???i!');
                Session::flash('data', $updateData);
            }
        }
        //Th???c hi???n chuy???n trang
        return redirect('admin/ql-lop-hoc/' . $request->id . '/cap-nhat');
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
        return redirect('admin/ql-lop-hoc');
    }

    public function status(Request $request)
    {
        try
        {
            $id = $request->input("id");
            $status = $request->input("status");
            if ($id != -1) {
                DB::table($this->table_name)->where('id', $id)
                ->update(['status' => $status]);
                $response = array("result" => 1, "msg" => "C???p Nh???t th??nh c??ng!");
            } else {
                $response = array("result" => 0, "msg" => "Kh??ng tim th???y d??? li???u!");
            }
            return response()->json($response);
        } catch (exception $ex) {
            $response = array("result" => 0, "msg" => "L???i server!");
            return response()->json($response);
        }
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


    public function find_student(Request $request)
    {
        $class_id = intval($request->input("class_id"));
        $current = intval($request->input("current"));
        $rowCount = intval($request->input("rowCount"));
        $searchPhrase = $request->input("searchPhrase");

        $query = DB::table("student_class")
                    ->leftJoin('users', 'student_class.student_id', '=', 'users.id')
                    ->leftJoin('classes', 'student_class.class_id', '=', 'classes.id')
                    ->leftJoin('school_years', 'classes.school_year_id', '=', 'school_years.id');


        $query->where("student_class.class_id", $class_id);

        //search
        if (!empty($searchPhrase)) {
            $query->where("users.first_name", "LIKE", '%' . $searchPhrase . "%")
                ->orWhere("users.last_name", "LIKE", '%' . $searchPhrase . "%")
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
            $query->orderBy( "users.first_name", "asc")
                ->orderBy("student_class.id", "asc")
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
        $data = $query->select("student_class.*",  "users.id as user_id",  "users.email", "users.sex","users.first_name", "users.last_name", "classes.name as class_name", "school_years.name as school_year_name", "school_years.year_open", "school_years.year_close")
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
                    "id" => $item->user_id,
                    "first_name" => $item->first_name,
                    "last_name" => $item->last_name,
                    "sex" => $item->sex,
                    "email" => $item->email,
                    "class_name" => $item->class_name,
                    "school_year_name" => $item->school_year_name,
                    "created_at" => $item->created_at,
                    "updated_at" => $item->updated_at,
                    "created_by" => $created_by != null ? $created_by->last_name . " "  . $created_by->first_name : "",
                    "updated_by" => $updated_by != null ? $updated_by->last_name . " "  . $updated_by->first_name : "",
                );
            }
        }
        return response()->json($json);
    }

    public function view_student($class_id)
    {
        // $getData = DB::table("student_class")
        //             ->leftJoin('users', 'student_class.student_id', '=', 'users.id')
        //             ->leftJoin('classes', 'student_class.class_id', '=', 'classes.id')
        //             ->leftJoin('school_years', 'classes.school_year_id', '=', 'school_years.id')
        //             ->where("student_class.class_id",$id)
        //             ->select('student_class.*', "users.id as user_id", "users.sex","users.first_name", "users.last_name", "classes.name as class_name",, "school_years.name as school_year_name" "school_years.year_open", "school_years.year_close")->where('student_class.class_id', $class_id)
        //             ->orderBy("users.first_name", "asc")
        //             ->orderBy("users.id", "asc")
        //             ->get();
        return view('admin.classes.view_student')->with('class_id', $class_id);
    }

    public function create_student($class_id)
    {
        //Hi???n th??? trang th??m slide
        return view('admin.classes.create_student')
        ->with('class_id', $class_id)
        ;
    }
    public function store_student(Request $request)
    {
        //Ki???m tra gi?? tr??? slogan_title, slogan_value
        $this->validate($request,
            [
                //Ki???m tra gi?? tr??? r???ng
                'last_name' => 'required',
                'first_name' => 'required',
                'date_of_birth' => 'required',
                'email' => 'required',
                'password' => 'required',
            ],
            [
                //T??y ch???nh hi???n th??? th??ng b??o
                'last_name.required' => 'B???n ch??a nh???p H??? V?? T??n ?????m!',
                'first_name.required' => 'B???n ch??a nh???p T??n!',
                'email.required' => 'B???n ch??a nh???p Email!',
                'password.required' => 'B???n ch??a nh???p M???t Kh???u!',
                'date_of_birth.required' => 'B???n ch??a nh???p Ng??y Sinh!',
            ]
        );
        //L???y gi?? tr??? slide ???? nh???p
        date_default_timezone_set(Util::$time_zone);
        $class_id = $request->class_id;
        $last_name = trim($request->last_name);
        $first_name = trim($request->first_name);
        $phone_number = $request->phone_number;
        $date_of_birth = $request->date_of_birth;
        $email = $request->email;
        $address = $request->address;
        $password = Hash::make($request->password);
        $sex = (($request->status) == 'on') ? 1 : 0;
        $status = (($request->status) == 'on') ? 1 : 0;
            //G??n gi?? tr??? v??o array
        $dataInsertToDatabase = array(
            'last_name' => $last_name,
            'first_name' => $first_name,
            'date_of_birth' => $date_of_birth,
            'sex' => $sex,
            'email' => $email,
            'password' => $password,
            'phone_number' => $phone_number,
            'address' => $address,
            'status' => $status ,
            'level' => 2 ,
            'created_at' => date(Util::$date_time_format),
        );
        if($this->checkDataExistsStudent($dataInsertToDatabase, null)==true){
            Session::flash('error', 'H???c Sinh n??y ???? t???n t???i!');
            Session::flash('data', $dataInsertToDatabase);
        }else{
                //Insert v??o database
            $id = DB::table("users")->insertGetId($dataInsertToDatabase);
            $insertData = DB::table("users")->where('id', $id)->get();
                //echo($insertData);
            if (count($insertData) > 0) {
                $tmp_id = DB::table("student_class")->insertGetId(
                    array("student_id" => $id, "class_id" => $class_id, "created_at" =>  date(Util::$date_time_format), "created_by" => auth()->user()->id)
                );
                Session::flash('success', 'Th??m m???i th??nh c??ng!');
            } else {
                Session::flash('error', 'Th??m m???i th???t b???i!');
                Session::flash('data', $dataInsertToDatabase);
            }
        }
        return redirect('admin/ql-lop-hoc/'.$class_id.'/ds-hoc-sinh');
    }


    public function edit_student($id, $class_id)
    {
        //L???y d??? li???u t??? Database v???i c??c tr?????ng ???????c l???y v?? v???i ??i???u ki???n id = $id
        $getData = DB::table("users")->select('id', 'last_name', 'first_name' , 'sex' , 'email', 'phone_number' , 'last_name' , 'date_of_birth', "address",  'status')->where('id', $id)->get();
        //G???i ?????n file edit.blade.php trong th?? m???c "resources/views/slide" v???i gi?? tr??? g???i ??i t??n getSlideById = $getData
        return view('admin.classes.edit_student')
        ->with('getDataById', $getData)
        ->with('class_id', $class_id)
        ;
    }

    public function update_student(Request $request)
    {
        //Cap nhat sua hoc sinh
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        //Ki???m tra gi?? tr??? tenhocsinh, sodienthoai, khoi
        $this->validate($request,
            [
                //Ki???m tra gi?? tr??? r???ng
                'last_name' => 'required',
                'first_name' => 'required',
                'date_of_birth' => 'required',
                'email' => 'required',
               // 'password' => 'required',
            ],
            [
                //T??y ch???nh hi???n th??? th??ng b??o
                'last_name.required' => 'B???n ch??a nh???p H??? V?? T??n ?????m!',
                'first_name.required' => 'B???n ch??a nh???p T??n!',
                'email.required' => 'B???n ch??a nh???p Email!',
                //'password.required' => 'B???n ch??a nh???p M???t Kh???u!',
                'date_of_birth.required' => 'B???n ch??a nh???p Ng??y Sinh!',
            ]
        );
        $class_id = $request->class_id;
        $id = $request->id;
        $last_name = trim($request->last_name);
        $first_name = trim($request->first_name);
        $phone_number = $request->phone_number;
        $date_of_birth = $request->date_of_birth;
        $email = $request->email;
        $password = Hash::make($request->password);
        $address = $request->address;
        $sex = (($request->status) == 'on') ? 1 : 0;
        $status = (($request->status) == 'on') ? 1 : 0;
            //G??n gi?? tr??? v??o array
        if(trim($request->password)!=""){
            $updateData = array(
                'last_name' => $last_name,
                'first_name' => $first_name,
                'date_of_birth' => $date_of_birth,
                'sex' => $sex,
                'email' => $email,
                'password' => $password,
                'phone_number' => $phone_number,
                'address' => $address,
                'status' => $status ,
                'level' => 2 ,
                'updated_at' => date(Util::$date_time_format),
            );
    
        }else{
            $updateData = array(
                'last_name' => $last_name,
                'first_name' => $first_name,
                'date_of_birth' => $date_of_birth,
                'sex' => $sex,
                'email' => $email,              
                'phone_number' => $phone_number,
                'address' => $address,
                'status' => $status ,
                'level' => 2 ,
                'updated_at' => date(Util::$date_time_format),
            );    
        }
       

        if($this->checkDataExistsStudent($updateData, $id)){
            Session::flash('error', 'H???c Sinh n??y ???? t???n t???i!');
            Session::flash('data', $updateData);
        }else{
            $res = DB::table("users")->where('id', $id)
            ->update($updateData);
                  //Ki???m tra l???nh update ????? tr??? v??? m???t th??ng b??o
            if ($res) {
                Session::flash('success', 'C???p nh???t th??ng tin th??nh c??ng!');
            } else {
                Session::flash('error', 'C???p nh???t th??ng tin th???t b???i!');
                Session::flash('data', $updateData);
            }
        }
        //Th???c hi???n chuy???n trang
        return redirect('admin/ql-lop-hoc/'. $id .'/cap-nhat-hs/' . $class_id);
    }


    public function checkDataExistsStudent($data, $id){
        $res = false;
        if($id!=null){
            if(DB::table("users")
                ->where('last_name', $data["last_name"])
                ->where('first_name', $data["first_name"])
                ->where('sex', $data["sex"])
                ->where('date_of_birth', $data["date_of_birth"])
                ->where('id',"!=", $id)
                ->exists())
            {
                $res = true;
            }
        }else{
            if(DB::table("users")
                ->where('last_name', $data["last_name"])
                ->where('first_name', $data["first_name"])
                ->where('sex', $data["sex"])
                ->where('date_of_birth', $data["date_of_birth"])
                ->where('id',"!=", $id)
                ->exists())
            {
                $res = true;
            }
        }
        return($res);
    }

    public function destroy_student($id)
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
        return redirect('admin/ql-lop-hoc');
    }


}
