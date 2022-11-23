@extends('admin.admin_master')
@section('title', 'Thêm mới Môn Học')
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
                <h4>Phân Môn</h4>
            </div>
            <div class="card-body">
                <div class="col-xs-4 col-xs-offset-4">
                    <form method="POST" action="{{ url('admin/ql-phan-mon/luu-moi') }}" enctype="multipart/form-data">
                        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}" />
                        <div class="form-group">
                            <label for="school_year_id" style="color:#0093ff; font-weight:bold;">Năm Học</label>
                            <select class="form-control" id="school_year_id" name="school_year_id"
                                style="color:#0093ff;">
                                @isset($school_years)
                                @foreach ($school_years as $item)
                                    <option value="{{ $item->id }}"  @php echo Session::has('data')? Session::get('data')['school_year_id']==$item->id? 'selected': '' : '' ; @endphp > {{ $item->name }}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="class_id" style="color:#0093ff; font-weight:bold;">Lớp Học</label>
                            <select class="form-control" id="class_id" name="class_id" style="color:#0093ff;">
                                <option value="" selected>Xin chọn lớp học</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="subject_id" style="color:#0093ff; font-weight:bold;">Môn Học</label>
                            <select class="form-control" id="subject_id" name="subject_id" style="color:#0093ff;">
                                @isset($subjects)
                                <option value="" selected>Xin chọn môn học</option>
                                @foreach ($subjects as $item)
                                    <option value="{{ $item->id }}"  @php echo Session::has('data')? Session::get('data')['subject_id']==$item->id? 'selected': '' : '' ; @endphp>{{ $item->name }}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="class_id" style="color:#0093ff; font-weight:bold;">Giáo Viên</label>
                            <select class="form-control" id="teacher_id" name="teacher_id" style="color:#0093ff;">
                                  @isset($teachers)
                                <option value="" selected>Xin chọn giáo viên</option>
                                @foreach ($teachers as $item)
                                    <option value="{{ $item->id }}"  @php echo Session::has('data')? Session::get('data')['teacher_id']==$item->id? 'selected': '' : '' ; @endphp>{{ $item->last_name . " " . $item->first_name }}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>

                        <center><button type="submit" class="btn btn-info btn-save">Thêm</button></center>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    var APP_URL = {!! json_encode(url('/')) !!}
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("#school_year_id").change(function() {
        get_Class();
    });
    get_Class();

});


function get_Class() {
    var school_year_id = $("#school_year_id").val();

    if (school_year_id != "" && school_year_id > 0 ) {
        $.ajax({
            url: "{{ url('/admin/ql-phan-mon/get_class') }}",
            data: {
                school_year_id: school_year_id
            },
            dataType: "json",
            type: "POST",
            cache: false,
            beforeSend: function() {
                $("body").addClass("loading");
            },
            complete: function() {
                $("body").removeClass("loading");
            },
            success: function(response) {
                switch (response.result) {
                    case 1:
                        console.log(response.data);
                        $("#class_id").empty();
                        $('#class_id').append('<option value="">Xin chọn lớp</option>');
                        $.each(response.data, function(i, item) {
                            console.log(response.data[i].name);
                            $('#class_id').append('<option value="' + response.data[i].id + '">' +
                                response.data[i].name + '</option>');
                        });
                    break;
                    default:
                    $.alert({
                        title: "<strong class='alert-msg-eror'>Error!</strong>",
                        content: response.msg,
                        type: "red",
                        autoClose: "OK|3000",
                        buttons: {
                            OK: function() {}
                        },
                        onOpenBefore: function() {
                            $(".jconfirm-content").css("text-align", "center");
                        },
                    });
                    break;
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                $.alert({
                    title: "<strong class='alert-msg-error'>Error!</strong>",
                    content: "Không kết nối được server!",
                    type: "red",
                    buttons: {
                        OK: function() {
                            location.reload();
                        }
                    },
                    onDestroy: function() {}
                });
            }
        });
    } else {
        $("#class_id").empty();
        $('#class_id').append('<option value="">Tất Cả</option>');
    }
}

</script>
@endsection
