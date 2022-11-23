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
            <p><a class="btn btn-primary" href="{{ url('admin/ql-mon-hoc') }}">Về danh sách</a></p>
            <div class="card">
                <div class="card-header">
                    <h4>Thêm Môn Học</h4>
                </div>
                <div class="card-body">
                    <div class="col-xs-4 col-xs-offset-4">
                        <form method="POST" action="{{ url('admin/ql-mon-hoc/create') }}" enctype="multipart/form-data">
                            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}" />
                            <div class="form-group">
                                <label for="name" style="color:#0093ff; font-weight:bold;">Môn Học</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Môn Học"
                                    maxlength="255" required placeholder="Môn Học" value="@php echo Session::has('data')? Session::get('data')["name"] : ""; @endphp" />
                            </div>
                            <div class="form-group">
                                <label for="status"><input type="checkbox" class="" name="status" id="status" @php echo Session::has('data')? Session::get('data')['status']==1? 'checked="checked"': '' : 'checked="checked"' ; @endphp /><b> Kích hoạt</b></label><br />
                            </div>
                            <center><button type="submit" class="btn btn-info btn-save">Thêm</button></center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection