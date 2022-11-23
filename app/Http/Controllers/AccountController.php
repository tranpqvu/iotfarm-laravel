<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    private $table_name = "users";
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.account.info')
                ->with("data", auth()->user());
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
                
            ],
            [
                //Tùy chỉnh hiển thị thông báo
                'last_name.required' => 'Bạn chưa nhập Họ Và Tên Đệm!',
                'first_name.required' => 'Bạn chưa nhập Tên!',
                'date_of_birth.required' => 'Bạn chưa nhập Ngày Sinh!',
            ]
        );
        $id = auth()->user()->id;
        $last_name = trim($request->last_name);
        $first_name = trim($request->first_name);
        $phone_number = $request->phone_number;
        $date_of_birth = $request->date_of_birth;      
        $password = Hash::make($request->password);
        $address = $request->address;
        $sex = (($request->status) == 'on') ? 1 : 0;
            //Gán giá trị vào array
        if(trim($request->password)!=""){
            $updateData = array(
                'last_name' => $last_name,
                'first_name' => $first_name,
                'date_of_birth' => $date_of_birth,
                'sex' => $sex,               
                'password' => $password,
                'phone_number' => $phone_number,
                'address' => $address,               
                'updated_at' => date(Util::$date_time_format),
            );
    
        }else{
            $updateData = array(
                'last_name' => $last_name,
                'first_name' => $first_name,
                'date_of_birth' => $date_of_birth,
                'sex' => $sex,                          
                'phone_number' => $phone_number,
                'address' => $address,               
                'updated_at' => date(Util::$date_time_format),
            );    
        }
       

        
        $res = DB::table($this->table_name)->where('id', $id)
        ->update($updateData);
                //Kiểm tra lệnh update để trả về một thông báo
        if ($res) {
            Session::flash('success', 'Cập nhật thông tin thành công!');
        } else {
            Session::flash('error', 'Cập nhật thông tin thất bại!');
            Session::flash('data', $updateData);
        }
        
        //Thực hiện chuyển trang
        if(auth()->user()->level == 2){
            return redirect('tai-khoan');
        }else{
            return redirect('admin/tai-khoan');
        }
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

}
