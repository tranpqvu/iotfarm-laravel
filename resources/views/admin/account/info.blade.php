@extends('admin.admin_master')
@section('title', 'Thông tin tài khoản')
@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <strong>{{ Session::get('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
        </div>
    @endif
    @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <strong>{{ Session::get('error') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-6">
            {{-- <p><a class="btn btn-primary" href="{{ url('admin/ql-tai-khoan') }}">Về danh sách</a></p> --}}
            <div class="card">
                <div class="card-header">
                    <h4>Thông Tin Tài Khoản</h4>
                </div>
                <div class="card-body">
                    <div class="col-xs-4 col-xs-offset-4">
                        <form method="POST" action="{{ auth()->user()->level == 2? url('tai-khoan/cap-nhat') : url('admin/tai-khoan/cap-nhat') }}" enctype="multipart/form-data">
                            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}" />
                            <div class="form-group">
                                <label for="last_name" style="color:#0093ff; font-weight:bold;">Họ Và Đệm</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Họ Và Đệm"
                                    maxlength="255" required value="{{ $data->last_name}}" />
                            </div>
                            <div class="form-group">
                                <label for="last_name" style="color:#0093ff; font-weight:bold;">Tên</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Tên"
                                    maxlength="255" required value="{{ $data->first_name}}" />
                            </div>
                            <div class="form-group">
                                <label for="sex"><input type="checkbox" class="" name="sex" id="sex" {{ $data->sex==1? 'checked="checked"':"" }} /><b> Giới Tính Nam</b></label><br />
                            </div>
                            <div class="form-group">
                                <label for="date_of_birth" style="color:#0093ff; font-weight:bold;">Ngày Sinh</label>
                                <input type="text" readonly class="form-control dc-date-time-picker" id="date_of_birth" name="date_of_birth" placeholder="Ngày Sinh"
                                    maxlength="255"  value="{{$data->date_of_birth }}" />
                            </div>
                            <div class="form-group">
                                <label for="email" style="color:#0093ff; font-weight:bold;">Email</label>
                                <input type="text" readonly class="form-control" id="email" name="email" placeholder="Email"
                                    maxlength="255"  value="{{$data->email}}" />
                            </div>
                            <div class="form-group">
                                <label for="password" style="color:#0093ff; font-weight:bold;">Mật Khẩu</label>
                                <input type="text" class="form-control" id="password" name="password" placeholder="Mật Khẩu"
                                    maxlength="20"  value="" />
                            </div>
                            <div class="form-group">
                                <label for="phone_number" style="color:#0093ff; font-weight:bold;">Điện Thoại</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="phone_number"
                                    maxlength="255"  value="{{$data->phone_number}}" />
                            </div>
                            <div class="form-group">
                                <label for="address" style="color:#0093ff; font-weight:bold;">Địa Chỉ</label>
                                <textarea class="form-control" id="address" name="address" placeholder="address">
                                    {{$data->address}}
                                </textarea>                               
                            </div>
                           
                            <center><button type="submit" class="btn btn-info btn-save">Cập Nhật</button></center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


<script>
 $(document).ready(function() {
   
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });          

            $('#date_of_birth').datetimepicker({
                locale: 'vi',
               // minDate: new Date(),
                format: "YYYY-MM-DD",
                showClose: true,
                showClear: true,
                ignoreReadonly: true,
            });
 });
</script>

@endsection