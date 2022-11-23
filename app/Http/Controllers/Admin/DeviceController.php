<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\AdminController;
use App\Models\User;
use App\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class DeviceController extends AdminController
{
    private $table_name = "devices";
    private $view_name = "admin.device.";
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view($this->view_name . 'list');
    }
    public function find(Request $request)
    {
        $current = intval($request->input("current"));
        $rowCount = intval($request->input("rowCount"));
        $searchPhrase = $request->input("searchPhrase");
        $query = DB::table($this->table_name)
        ->leftJoin('farms', $this->table_name . '.farm_id', '=', 'farms.id')
        //->leftJoin('users', $this->table_name . '.user_id', '=', 'users.id')
               
        ;
        //search
        if (!empty($searchPhrase)) {
            $query->where($this->table_name . ".name", "LIKE", '%' . $searchPhrase . "%")
            ->orWhere($this->table_name . ".mac", "LIKE", '%' . $searchPhrase . "%")
            ->orWhere("farms.name", "LIKE", '%' . $searchPhrase . "%")
            ;
        }

        //sort
        if (isset($request->sort) && is_array( $request->sort)) {
            foreach ($request->sort as $key => $value) {                
                switch ($key) {
                    case "status":
                    $query->orderBy( $this->table_name . "." . $key, $value);
                    break;
                    case "name":
                    $query->orderBy( $this->table_name . ".first_name" , $value);
                    break;
                    case "mac":
                        $query->orderBy( $this->table_name . ".mac" , $value);
                        break;
                    case "farm_name":
                        $query->orderBy( "farms.name" , $value);
                        break;
                    case "email":
                        $query->orderBy( "users.email" , $value);
                    break;
                }
            }
        } else {
            $query->orderBy($this->table_name . ".name", "asc")
            ->orderBy($this->table_name . ".created_at", "asc");
        }
        // print_r((string)$query);exit;
        $query = $query->select(
                $this->table_name. '.id as id' , 
                $this->table_name. '.name' , 
                $this->table_name. '.mac' , 
                'farms.name as farm_name' , 
                //'users.email as email' , 
                $this->table_name. '.status', 
                $this->table_name.'.created_at', 
                $this->table_name.'.updated_at'
                );
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
                $json['rows'][] = array(
                    "id" => $item->id,
                    "name" => $item->name,
                    "mac" => $item->mac,
                    "farm_name" => $item->farm_name,
                    //"email" => $item->email,
                    "status" => $item->status,
                    "created_at" => $item->created_at,
                    "updated_at" => $item->updated_at
                );
            }
        }
        return response()->json($json);
    }
    public function create()
    {
        //Hiển thị trang thêm slide
        $getData = DB::table("farms")
            ->select('id', 'name')->get();
        return view($this->view_name . 'create')->with('farms', $getData);
    }
    public function store(Request $request)
    {
        //Kiểm tra giá trị slogan_title, slogan_value
        $this->validate($request,
            [
                //Kiểm tra giá trị rỗng
                'name' => 'required',
            ],
            [
                //Tùy chỉnh hiển thị thông báo
                'name.required' => 'Bạn chưa nhập Tên Thiết Bị!',                
            ]
        );
        //Lấy giá trị slide đã nhập
        date_default_timezone_set(Util::$time_zone);
        $name = trim(ucwords($request->name));
        $mac = $request->mac;
        $phone = $request->phone;
        $status = (($request->status) == 'on') ? 1 : 0;
            //Gán giá trị vào array
        $dataInsertToDatabase = array(
            'name' => $name,
            'mac' => $mac,
            'phone' => $phone,
            'status' => $status ,
            'created_at' => date(Util::$date_time_format),
            'created_by' => auth()->user()->id,
        );
        if($this->checkDataExists($dataInsertToDatabase, null)==true){
            Session::flash('error', 'Thiết bị này đã tồn tại!');
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
        return redirect('admin/device/create');
    }
    public function checkDataExists($data, $id){
        $res = false;
        if($id!=null){
            if(DB::table($this->table_name)
                ->where('name', $data["name"])
                ->where('id',"!=", $id)
                ->exists())
            {
                $res = true;
            }
        }else{
            if(DB::table($this->table_name)
                ->where('name', $data["name"])
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
        $getData = DB::table($this->table_name)
            ->select('id', 'name', 'mac' , 'farm_id', 'status')
            ->where('id', $id)->get();
        $farm= DB::table("farms")
        ->select('id', 'name')->get();
        //Gọi đến file edit.blade.php trong thư mục "resources/views/slide" với giá trị gửi đi tên getSlideById = $getData
        return view($this->view_name . 'edit')->with('getDataById', $getData)->with('farms',$farm);
    }
    public function update(Request $request)
    {
        //Cap nhat sua thiết bị
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        //Kiểm tra giá trị tenhocsinh, sodienthoai, khoi
        $this->validate($request,
            [
                //Kiểm tra giá trị rỗng
                'name' => 'required',
            ],
            [
                //Tùy chỉnh hiển thị thông báo
                'name.required' => 'Bạn chưa nhập Tên!',
            ]
        );
        $id = $request->id;
        $name = trim(ucwords($request->name));
        $mac = trim(ucwords($request->mac));
        $farm_id = $request->farm_id;
        $status = (($request->status) == 'on') ? 1 : 0;

        $updateData = array(
            'name' => $name,
            'mac' => $mac,
            'farm_id' => $farm_id,
            'status' => $status ,
            'updated_at' => date(Util::$date_time_format)            
        );  
       

        if($this->checkDataExists($updateData, $id)){
            Session::flash('error', 'Thiết bị này đã tồn tại!');
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
        return redirect('admin/device/' . $request->id . '/edit');
    }

    public function destroy($id)
    {
        if($this->checkBeforeDelete($id)==0){
            $deleteData = DB::table($this->table_name)
                ->where('id', $id)->delete();
            if ($deleteData) {
                Session::flash('success', 'Xóa thành công!');
            } else {
                Session::flash('error', 'Xóa thất bại!');
            }
        }else{
            Session::flash('error', 'Không thể xóa. Dữ liệu đã được dùng cho các chức năng khác!');
        }

        return redirect('admin/device');
    }
    public function status(Request $request)
    {
        try
        {
            $id = $request->input("id");
            $status = $request->input("status");
            if ($id != -1) {
                DB::table($this->table_name)
                ->where('id', $id)
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
                    $deleteData = DB::table($this->table_name)
                    ->where('id', $item)
                    ->delete();
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
            /*
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
            }*/

            return($res);
        } catch (exception $ex) {
            return(-1);
        }
    }

    
}