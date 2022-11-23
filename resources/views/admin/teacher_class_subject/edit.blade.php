@extends('admin.admin_master')
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
        <p><a class="btn btn-primary" href="{{ url('admin/ql-phan-mon') }}">Về danh sách</a></p>
        <div class="card">
            <div class="card-header">
                <h4>Cập nhật thông tin</h4>
            </div>
            <div class="card-body">
                <div class="col-xs-4 col-xs-offset-4">
                    <form action="{{ url('admin/ql-phan-mon/cap-nhat') }}" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="_token" name="_token" value="{!! csrf_token() !!}" />
                        <input type="hidden" id="id" name="id" value="{!! $getDataById[0]->id !!}" />
                           <div class="form-group">
                            <label for="school_year_id" style="color:#0093ff; font-weight:bold;">Năm Học</label>
                            <select class="form-control" id="school_year_id" name="school_year_id"
                                style="color:#0093ff;">
                                @isset($school_years)
                                @foreach ($school_years as $item)
                                    <option value="{{ $item->id }}" @php echo $getDataById[0]->school_year_id==$item->id?"selected" : ""; @endphp >{{ $item->name }}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="class_id" style="color:#0093ff; font-weight:bold;">Lớp Học</label>
                             <select class="form-control" id="class_id" name="class_id" style="color:#0093ff;">
                                @isset($classes)
                                @foreach ($classes as $item)
                                    <option value="{{ $item['id'] }}"  @php echo $getDataById[0]->class_id==$item['id']?"selected" : ""; @endphp >{{ $item['name'] }}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="subject_id" style="color:#0093ff; font-weight:bold;">Môn Học</label>
                            <select class="form-control" id="subject_id" name="subject_id" style="color:#0093ff;">
                                @isset($subjects)
                                @foreach ($subjects as $item)
                                    <option value="{{ $item->id }}"  @php echo $getDataById[0]->subject_id==$item->id?"selected" : ""; @endphp >{{ $item->name }}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="teacher_id" style="color:#0093ff; font-weight:bold;">Giáo Viên</label>
                            <select class="form-control" id="teacher_id" name="teacher_id" style="color:#0093ff;">
                                @isset($teachers)
                                @foreach ($teachers as $item)
                                    <option value="{{ $item->id }}"  @php echo $getDataById[0]->teacher_id==$item->id?"selected" : ""; @endphp >{{ $item->last_name . " " . $item->first_name }}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                        <center><button type="submit" class="btn btn-info btn-block">Lưu lại</button></center>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
