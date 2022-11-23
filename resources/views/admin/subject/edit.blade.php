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
        <p><a class="btn btn-primary" href="{{ url('admin/ql-mon-hoc') }}">Về danh sách</a></p>
        <div class="card">
            <div class="card-header">
                <h4>Cập nhật thông tin</h4>
            </div>
            <div class="card-body">
                <div class="col-xs-4 col-xs-offset-4">
                    <form action="{{ url('admin/ql-mon-hoc/update') }}" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="_token" name="_token" value="{!! csrf_token() !!}" />
                        <input type="hidden" id="id" name="id" value="{!! $getDataById[0]->id !!}" />
                        <div class="form-group">
                            <label for="name" style="color:#0093ff; font-weight:bold;">Môn Học</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Môn Học"
                            maxlength="255" required value="{{ $getDataById[0]->name}}"
                            placeholder="{{ $getDataById[0]->name }}" />
                        </div>
                        <div class="form-group">
                            <input type="checkbox" class="" name="status" id="status" <?php if
                            ($getDataById[0]->status) {
                                echo 'checked';
                            } ?> > <b>Kích hoạt</b><br />
                        </div>
                        <center><button type="submit" class="btn btn-info btn-block">Lưu lại</button></center>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection