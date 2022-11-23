<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\AdminController;
use App\Models\User;
use App\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class TeacherController extends AdminController
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
                    "sex" => $item->sex == 1? "Nam": "Nữ" ,
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
        //Hiển thị trang thêm slide
        return view('admin.teacher.create');
    }
    public function store(Request $request)
    {
        //Kiểm tra giá trị slogan_title, slogan_value
        $this->validate($request,
            [
                //Kiểm tra giá trị rỗng
                'last_name' => 'required',
                'first_name' => 'required',
                'date_of_birth' => 'required',
                'email' => 'required',
                'password' => 'required',
            ],
            [
                //Tùy chỉnh hiển thị thông báo
                'last_name.required' => 'Bạn chưa nhập Họ Và Tên Đệm!',
                'first_name.required' => 'Bạn chưa nhập Tên!',
                'email.required' => 'Bạn chưa nhập Email!',
                'password.required' => 'Bạn chưa nhập Mật Khẩu!',
                'date_of_birth.required' => 'Bạn chưa nhập Ngày Sinh!',
            ]
        );
        //Lấy giá trị slide đã nhập
        date_default_timezone_set(Util::$time_zone);
        $last_name = trim($request->last_name);
        $first_name = trim($request->first_name);
        $phone_number = $request->phone_number;
        $date_of_birth = $request->date_of_birth;
        $email = $request->email;
        $address = $request->address;
        $password = Hash::make($request->password);
        $sex = (($request->status) == 'on') ? 1 : 0;
        $status = (($request->status) == 'on') ? 1 : 0;
            //Gán giá trị vào array
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
            Session::flash('error', 'Giáo Viên này đã tồn tại!');
            Session::flash('data', $dataInsertToDatabase);
        }else{
                //Insert vào database
            $id = DB::table($this->table_name)->insertGetId($dataInsertToDatabase);
            $insertData = DB::table($this->table_name)->where('id', $id)->get();
                //echo($insertData);
            if (count($insertData) > 0) {
                Session::flash('success', 'Thêm mới thành công!');
            } else {
                Session::flash('error', 'Thêm mới thất bại!');
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
        //Lấy dữ liệu từ Database với các trường được lấy và với điều kiện id = $id
        $getData = DB::table($this->table_name)->select('id', 'last_name', 'first_name' , 'sex' , 'email', 'phone_number' , 'last_name' , 'date_of_birth', "address",  'status')->where('id', $id)->get();
        //Gọi đến file edit.blade.php trong thư mục "resources/views/slide" với giá trị gửi đi tên getSlideById = $getData
        return view('admin.teacher.edit')->with('getDataById', $getData);
    }
    public function update(Request $request)
    {
        //Cap nhat sua hoc sinh
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        //Kiểm tra giá trị tenhocsinh, sodienthoai, khoi
        $this->validate($request,
            [
                //Kiểm tra giá trị rỗng
                'last_name' => 'required',
                'first_name' => 'required',
                'date_of_birth' => 'required',
                'email' => 'required',
               // 'password' => 'required',
            ],
            [
                //Tùy chỉnh hiển thị thông báo
                'last_name.required' => 'Bạn chưa nhập Họ Và Tên Đệm!',
                'first_name.required' => 'Bạn chưa nhập Tên!',
                'email.required' => 'Bạn chưa nhập Email!',
                //'password.required' => 'Bạn chưa nhập Mật Khẩu!',
                'date_of_birth.required' => 'Bạn chưa nhập Ngày Sinh!',
            ]
        );
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
            //Gán giá trị vào array
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
            Session::flash('error', 'Giáo Viên này đã tồn tại!');
            Session::flash('data', $updateData);
        }else{
            $res = DB::table($this->table_name)->where('id', $id)
            ->update($updateData);
                  //Kiểm tra lệnh update để trả về một thông báo
            if ($res) {
                Session::flash('success', 'Cập nhật thông tin thành công!');
            } else {
                Session::flash('error', 'Cập nhật thông tin thất bại!');
                Session::flash('data', $updateData);
            }
        }
        //Thực hiện chuyển trang
        return redirect('admin/ql-giao-vien/' . $request->id . '/edit');
    }

    public function destroy($id)
    {
        if($this->checkBeforeDelete($id)==0){
            $deleteData = DB::table($this->table_name)->where('id', $id)->delete();
            if ($deleteData) {
                Session::flash('success', 'Xóa thành công!');
            } else {
                Session::flash('error', 'Xóa thất bại!');
            }
        }else{
            Session::flash('error', 'Không thể xóa. Dữ liệu đã được dùng cho các chức năng khác!');
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
                $response = array("result" => 1, "msg" => "Xóa thành công!");
            } else {
                if ($count == 0) {
                    $response = array("result" => 0, "msg" => "Không thể xóa. Dữ liệu đã được dùng cho các chức năng khác!");
                } else {
                    $response = array("result" => 2, "msg" => "Một số dữ liệu không thể xóa được do đã được dùng cho các chức năng khác!");
                }
            }
            return response()->json($response);
        } catch (exception $ex) {
            $response = array("result" => 0, "msg" => "Xóa thất bại!");
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
                $response = array("result" => 1, "msg" => "Reset Mật Khẩu thành công!");
            } else {
                $response = array("result" => 0, "msg" => "Bạn không có quyền hoặc không tim thấy dữ liệu!");
            }
            return response()->json($response);
        } catch (exception $ex) {
            $response = array("result" => 0, "msg" => "Lỗi server!");
            return response()->json($response);
        }
    }

    
}
