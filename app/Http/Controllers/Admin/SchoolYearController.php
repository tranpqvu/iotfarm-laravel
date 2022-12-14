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
        //Hi???n th??? trang th??m slide
        return view('admin.school_year.create');
    }

    public function store(Request $request)
    {

        //Ki???m tra gi?? tr??? slogan_title, slogan_value
        $this->validate($request,
            [
                //Ki???m tra gi?? tr??? r???ng
                'year_open' => 'required',
                'year_close' => 'required',
            ],
            [
                //T??y ch???nh hi???n th??? th??ng b??o
                'year_open.required' => 'B???n ch??a nh???p th??ng tin n??m h???c b???t ?????u!',
                'year_close.required' => 'B???n ch??a nh???p th??ng tin n??m h???c k???t th??c!',
            ]
        );

        //L???y gi?? tr??? slide ???? nh???p
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
            Session::flash('error', 'N??m H???c B???t ?????u kh??ng h???p l???!');
            Session::flash('data', $dataInsertToDatabase);
        }

        if($year_close > 2000){
            $count_data_ok = $count_data_ok + 1;
        }else{
            Session::flash('error', 'N??m H???c K???t Th??c kh??ng h???p l???!');
            Session::flash('data', $dataInsertToDatabase);
        }

        if($count_data_ok == 2 && $year_open <= $year_close ){


            //G??n gi?? tr??? v??o array

            if($this->checkDataExists($dataInsertToDatabase, null)==true){
                Session::flash('error', 'N??m H???c n??y ???? t???n t???i!');
                Session::flash('data', $dataInsertToDatabase);
            }else{

                //Insert v??o database
                $id = DB::table($this->table_name)->insertGetId($dataInsertToDatabase);
                $insertData = DB::table($this->table_name)->where('id', $id)->get();
                //echo($insertData);
                if (count($insertData) > 0) {
                    Session::flash('success', 'Th??m m???i n??m h???c th??nh c??ng!');
                } else {
                    Session::flash('error', 'Th??m m???i th???t b???i!');
                    Session::flash('data', $dataInsertToDatabase);
                }
            }

        }else{
            Session::flash('error', 'N??m H???c K???t Th??c kh??ng h???p l???!');
        }
        //Th???c hi???n chuy???n trang
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
        //L???y d??? li???u t??? Database v???i c??c tr?????ng ???????c l???y v?? v???i ??i???u ki???n id = $id
        $getData = DB::table($this->table_name)->select('id', 'name', 'year_open', 'year_close', 'status')->where('id', $id)->get();

        //G???i ?????n file edit.blade.php trong th?? m???c "resources/views/slide" v???i gi?? tr??? g???i ??i t??n getSlideById = $getData
        return view('admin.school_year.edit')->with('getDataById', $getData);
    }

    public function update(Request $request)
    {
        //Cap nhat sua hoc sinh
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        //Ki???m tra gi?? tr??? tenhocsinh, sodienthoai, khoi
        $this->validate($request,
            [
                //Ki???m tra gi?? tr??? r???ng
                'year_open' => 'required',
                'year_close' => 'required',
            ],
            [
                //T??y ch???nh hi???n th??? th??ng b??o
                'year_open.required' => 'B???n ch??a nh???p th??ng tin n??m h???c b???t ?????u!',
                'year_close.required' => 'B???n ch??a nh???p th??ng tin n??m h???c k???t th??c!',
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
            Session::flash('error', 'N??m H???c B???t ?????u kh??ng h???p l???!');
            Session::flash('data', $updateData);
        }

        if($year_close > 2000){
            $count_data_ok = $count_data_ok + 1;
        }else{
            Session::flash('error', 'N??m H???c K???t Th??c kh??ng h???p l???!');
        }

        if($count_data_ok == 2 && $year_open <= $year_close ){



            if($this->checkDataExists($updateData, $request->id)){
                Session::flash('error', 'N??m H???c n??y ???? t???n t???i!');
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
        }


        //Th???c hi???n chuy???n trang
        return redirect('admin/ql-nam-hoc/' . $request->id . '/edit');
    }

    public function destroy($id)
    {
        if($this->checkBeforeDelete($id)==0)
        {
            $deleteData = DB::table($this->table_name)->where('id', $id)->delete();

            if ($deleteData) {
                Session::flash('success', 'X??a th??nh c??ng!');
            } else {
                Session::flash('error', 'X??a th???t b???i!');
            }
        }else{
            Session::flash('error', 'Kh??ng th??? x??a. D??? li???u ???? ???????c d??ng cho c??c ch???c n??ng kh??c!');
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
            if($this->checkBeforeDelete($item)==0)
            {
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
