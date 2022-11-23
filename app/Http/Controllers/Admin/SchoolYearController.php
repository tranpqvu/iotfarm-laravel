<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Models\User;
use App\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SchoolYearController extends AdminController
{
    private $table_name = "school_years";




    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.school_year.list');
    }

    public function find(Request $request)
    {
        $current = intval($request->input("current"));
        $rowCount = intval($request->input("rowCount"));
        $searchPhrase = $request->input("searchPhrase");

        $query = DB::table($this->table_name);

        //search
        if (!empty($searchPhrase)) {
            $query->where($this->table_name . ".name", "LIKE", '%' . $searchPhrase . "%")
            ;
        }

        //sort
        if (isset($request->sort) && is_array( $request->sort)) {

            foreach ($request->sort as $key => $value) {                
                switch ($key) {
                    case "status":
                    $query->orderBy($this->table_name . "." . $key, $value);
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
                    "id" => $item->id,
                    "name" => $item->name,
                    "status" => $item->status,
                    "created_at" => $item->created_at,
                    "updated_at" => $item->updated_at,
                    "created_by" => $created_by != null ? $created_by->last_name .  " " . $created_by->first_name : "",
                    "updated_by" => $updated_by != null ? $created_by->last_name .  " " . $created_by->first_name : "",
                    //date('Y-m-d H:i:s')
                );

            }
        }
        return response()->json($json);
    }

    public function create()
    {
        //Hiển thị trang thêm slide
        return view('admin.school_year.create');
    }

    public function store(Request $request)
    {

        //Kiểm tra giá trị slogan_title, slogan_value
        $this->validate($request,
            [
                //Kiểm tra giá trị rỗng
                'year_open' => 'required',
                'year_close' => 'required',
            ],
            [
                //Tùy chỉnh hiển thị thông báo
                'year_open.required' => 'Bạn chưa nhập thông tin năm học bắt đầu!',
                'year_close.required' => 'Bạn chưa nhập thông tin năm học kết thúc!',
            ]
        );

        //Lấy giá trị slide đã nhập
        date_default_timezone_set(Util::$time_zone);
        $allRequest = $request->all();
        $year_open = intval($allRequest['year_open']);
        $year_close = intval($allRequest['year_close']);
        $count_data_ok = 0;
        if (array_key_exists("status", $allRequest)) {
            $status = $allRequest['status'];
        } else {
            $status = "";
        }

        $dataInsertToDatabase = array(
            'name' => strval($year_open) . "-" . strval($year_close),
            'year_open' => $year_open,
            'year_close' => $year_close,
            'status' => ($status == 'on') ? 1 : 0,
            'created_by' => auth()->user()->id,
            'created_at' => date(Util::$date_time_format),
        );

        if($year_open > 2000){
            $count_data_ok = $count_data_ok + 1;
        }else{
            Session::flash('error', 'Năm Học Bắt Đầu không hợp lệ!');
            Session::flash('data', $dataInsertToDatabase);
        }

        if($year_close > 2000){
            $count_data_ok = $count_data_ok + 1;
        }else{
            Session::flash('error', 'Năm Học Kết Thúc không hợp lệ!');
            Session::flash('data', $dataInsertToDatabase);
        }

        if($count_data_ok == 2 && $year_open <= $year_close ){


            //Gán giá trị vào array

            if($this->checkDataExists($dataInsertToDatabase, null)==true){
                Session::flash('error', 'Năm Học này đã tồn tại!');
                Session::flash('data', $dataInsertToDatabase);
            }else{

                //Insert vào database
                $id = DB::table($this->table_name)->insertGetId($dataInsertToDatabase);
                $insertData = DB::table($this->table_name)->where('id', $id)->get();
                //echo($insertData);
                if (count($insertData) > 0) {
                    Session::flash('success', 'Thêm mới năm học thành công!');
                } else {
                    Session::flash('error', 'Thêm mới thất bại!');
                    Session::flash('data', $dataInsertToDatabase);
                }
            }

        }else{
            Session::flash('error', 'Năm Học Kết Thúc không hợp lệ!');
        }
        //Thực hiện chuyển trang
        return redirect('admin/ql-nam-hoc/create');

    }

    public function checkDataExists($data, $id){
        $res = false;
        if($id!=null){
            if(DB::table($this->table_name)
                ->where('year_open', $data["year_open"])
                ->where('year_close', $data["year_close"])
                ->where('id',"!=", $id)
                ->exists())
            {
                $res = true;
            }
        }else{
            if(DB::table($this->table_name)
                ->where('year_open', $data["year_open"])
                ->where('year_close', $data["year_close"])
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
        $getData = DB::table($this->table_name)->select('id', 'name', 'year_open', 'year_close', 'status')->where('id', $id)->get();

        //Gọi đến file edit.blade.php trong thư mục "resources/views/slide" với giá trị gửi đi tên getSlideById = $getData
        return view('admin.school_year.edit')->with('getDataById', $getData);
    }

    public function update(Request $request)
    {
        //Cap nhat sua hoc sinh
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        //Kiểm tra giá trị tenhocsinh, sodienthoai, khoi
        $this->validate($request,
            [
                //Kiểm tra giá trị rỗng
                'year_open' => 'required',
                'year_close' => 'required',
            ],
            [
                //Tùy chỉnh hiển thị thông báo
                'year_open.required' => 'Bạn chưa nhập thông tin năm học bắt đầu!',
                'year_close.required' => 'Bạn chưa nhập thông tin năm học kết thúc!',
            ]
        );
        $count_data_ok = 0;
        $id = $request->id;
        $year_open = intval($request->year_open);
        $year_close = intval($request->year_close);
        $status = (($request->status) == 'on') ? 1 : 0;

        $updateData = array(
            'name' => strval($year_open)."-".strval($year_close),
            'year_open' => $year_open,
            'year_close' => $year_close,
            'status' => $status,
            'updated_by' => auth()->user()->id,
            'updated_at' => date('Y-m-d H:i:s'),
        );

        if($year_open > 2000){
            $count_data_ok = $count_data_ok + 1;
        }else{
            Session::flash('error', 'Năm Học Bắt Đầu không hợp lệ!');
            Session::flash('data', $updateData);
        }

        if($year_close > 2000){
            $count_data_ok = $count_data_ok + 1;
        }else{
            Session::flash('error', 'Năm Học Kết Thúc không hợp lệ!');
        }

        if($count_data_ok == 2 && $year_open <= $year_close ){



            if($this->checkDataExists($updateData, $request->id)){
                Session::flash('error', 'Năm Học này đã tồn tại!');
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
        }


        //Thực hiện chuyển trang
        return redirect('admin/ql-nam-hoc/' . $request->id . '/edit');
    }

    public function destroy($id)
    {
        if($this->checkBeforeDelete($id)==0)
        {
            $deleteData = DB::table($this->table_name)->where('id', $id)->delete();

            if ($deleteData) {
                Session::flash('success', 'Xóa thành công!');
            } else {
                Session::flash('error', 'Xóa thất bại!');
            }
        }else{
            Session::flash('error', 'Không thể xóa. Dữ liệu đã được dùng cho các chức năng khác!');
        }
        return redirect('admin/ql-nam-hoc');
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
            if($this->checkBeforeDelete($item)==0)
            {
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
        if(DB::table("classes")
            ->where('school_year_id', $id)
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
