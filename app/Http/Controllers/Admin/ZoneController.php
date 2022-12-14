<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\AdminController;
use App\Models\User;
use App\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class ZoneController extends AdminController
{
    private $table_name = "users";
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.teacher.list')
            //->with("aa", User::find(1))
            ;
    }
    public function find(Request $request)
    {
        $subject_id = intval($request->input("subject_id"));
        $current = intval($request->input("current"));
        $rowCount = intval($request->input("rowCount"));
        $searchPhrase = $request->input("searchPhrase");
        $query = DB::table($this->table_name)
               
        ;
        //search
        if (!empty($searchPhrase)) {
            $query->where($this->table_name . ".first_name", "LIKE", '%' . $searchPhrase . "%")
                ->orWhere($this->table_name . ".last_name", "LIKE", '%' . $searchPhrase . "%")
            ;
        }

        // if($subject_id!=""){
        //    // $query->where("teacher_class_subject.subject_id", $subject_id);
        // }

        //sort
        if (isset($request->sort) && is_array( $request->sort)) {
            foreach ($request->sort as $key => $value) {                
                switch ($key) {
                    case "status":
                    $query->orderBy( $this->table_name . "." . $key, $value);
                    break;
                    case "full_name":
                    $query->orderBy( $this->table_name . ".first_name" , $value);
                    break;
                }
            }
        } else {
            $query->orderBy($this->table_name . ".first_name", "asc")
            ->orderBy($this->table_name . ".created_at", "asc");
        }
        // print_r((string)$query);exit;
        $query = $query->select(
                $this->table_name. '.id as id' , 
                $this->table_name. '.first_name' , 
                $this->table_name. '.last_name',
                $this->table_name. '.sex', 
                $this->table_name. '.date_of_birth', 
                $this->table_name. '.email', 
                $this->table_name. '.phone_number', 
                $this->table_name. '.address', 
                $this->table_name. '.status', 
                $this->table_name.'.created_at', 
                $this->table_name.'.updated_at'
                )->where("level",1);
        $data = $query->get();
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
                // $created_by = User::find($item->created_by);
                // $updated_by = User::find($item->updated_by);
                $json['rows'][] = array(
                    "id" => $item->id,
                    "full_name" => $item->last_name . " " . $item->first_name ,
                    "sex" => $item->sex == 1? "Nam": "N???" ,
                    "teacher_subject" => "" ,
                    "date_of_birth" => $item->date_of_birth,
                    "email" => $item->email,
                    "phone_number" => $item->phone_number,
                    "address" => $item->address,
                    "status" => $item->status,
                    "created_at" => $item->created_at,
                    "updated_at" => $item->updated_at,
                    // "created_by" => $created_by != null ? $created_by->last_name . " "  . $created_by->first_name : "",
                    // "updated_by" => $updated_by != null ? $created_by->last_name . " "  . $created_by->first_name : "",
                );
            }
        }
        return response()->json($json);
    }
    public function create()
    {
        //Hi???n th??? trang th??m slide
        return view('admin.teacher.create');
    }
    public function store(Request $request)
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
        $last_name = Util::removeWhiteSpace(ucwords($request->last_name));
        $first_name = Util::removeWhiteSpace(ucwords($request->first_name));
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
            'level' => 1 ,
            'created_at' => date(Util::$date_time_format),
        );
        if($this->checkDataExists($dataInsertToDatabase, null)==true){
            Session::flash('error', 'Gi??o Vi??n n??y ???? t???n t???i!');
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
        return redirect('admin/ql-giao-vien/create');
    }
    public function checkDataExists($data, $id){
        $res = false;
        if($id!=null){
            if(DB::table($this->table_name)
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
            if(DB::table($this->table_name)
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
    public function edit($id)
    {
        //L???y d??? li???u t??? Database v???i c??c tr?????ng ???????c l???y v?? v???i ??i???u ki???n id = $id
        $getData = DB::table($this->table_name)->select('id', 'last_name', 'first_name' , 'sex' , 'email', 'phone_number' , 'last_name' , 'date_of_birth', "address",  'status')->where('id', $id)->get();
        //G???i ?????n file edit.blade.php trong th?? m???c "resources/views/slide" v???i gi?? tr??? g???i ??i t??n getSlideById = $getData
        return view('admin.teacher.edit')->with('getDataById', $getData);
    }
    public function update(Request $request)
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
        $id = $request->id;
        $last_name = Util::removeWhiteSpace(ucwords($request->last_name));
        $first_name = Util::removeWhiteSpace(ucwords($request->first_name));
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
                'level' => 1 ,
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
                'level' => 1 ,
                'updated_at' => date(Util::$date_time_format),
            );    
        }
       

        if($this->checkDataExists($updateData, $id)){
            Session::flash('error', 'Gi??o Vi??n n??y ???? t???n t???i!');
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
        return redirect('admin/ql-giao-vien/' . $request->id . '/edit');
    }

    public function destroy($id)
    {
        if($this->checkBeforeDelete($id)==0){
            $deleteData = DB::table($this->table_name)->where('id', $id)->delete();
            if ($deleteData) {
                Session::flash('success', 'X??a th??nh c??ng!');
            } else {
                Session::flash('error', 'X??a th???t b???i!');
            }
        }else{
            Session::flash('error', 'Kh??ng th??? x??a. D??? li???u ???? ???????c d??ng cho c??c ch???c n??ng kh??c!');
        }

        return redirect('admin/ql-giao-vien');
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
                if($this->checkBeforeDelete($item)==0){
                    $deleteData = DB::table($this->table_name)->where('id', $item)->delete();
                    if ($deleteData) {
                        $count += 1;
                    }
                }
            }
            if ($count == count($arr_id)) {
                $response = array("result" => 1, "msg" => "X??a th??nh c??ng!");
            } else {
                if ($count == 0) {
                    $response = array("result" => 0, "msg" => "Kh??ng th??? x??a. D??? li???u ???? ???????c d??ng cho c??c ch???c n??ng kh??c!");
                } else {
                    $response = array("result" => 2, "msg" => "M???t s??? d??? li???u kh??ng th??? x??a ???????c do ???? ???????c d??ng cho c??c ch???c n??ng kh??c!");
                }
            }
            return response()->json($response);
        } catch (exception $ex) {
            $response = array("result" => 0, "msg" => "X??a th???t b???i!");
            return response()->json($response);
        }
    }

    public function checkBeforeDelete($id)
    {
        try
        {
            $res = 0;
            if(DB::table("teacher_class_subject")
                ->where('teacher_id', $id)
                ->exists()
            )
            {
                $res = $res + 1;
            }

            if(DB::table("exams")
                ->where('created_by', $id)
                ->exists()
            )
            {
                $res = $res + 1;
            }

            if(DB::table("exam_classes")
                ->where('created_by', $id)
                ->exists()
            )
            {
                $res = $res + 1;
            }

            if(DB::table("teacher_subject")
                ->where('teacher_id', $id)
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


    public function resetPassword(Request $request)
    {
        if ($request->has("id")) {
            $id = $request->input("id");
            $user = User::find($id);
            return view("admin/teacher/reset_password")
            ->with("id", $id)
            ->with("email", $user->email)
            ;
        }
        return view("admin/teacher/reset_password")
        ;
    }

    public function changePassword(Request $request)
    {
        try
        {
            $id = $request->input("id");
            $password = $request->input("password");
            if ($id != -1 && auth()->user()->level == 0) {
                DB::table($this->table_name)->where('id', $id)
                ->update(['password' => Hash::make($password)]);
                $response = array("result" => 1, "msg" => "Reset M???t Kh???u th??nh c??ng!");
            } else {
                $response = array("result" => 0, "msg" => "B???n kh??ng c?? quy???n ho???c kh??ng tim th???y d??? li???u!");
            }
            return response()->json($response);
        } catch (exception $ex) {
            $response = array("result" => 0, "msg" => "L???i server!");
            return response()->json($response);
        }
    }

    
}
