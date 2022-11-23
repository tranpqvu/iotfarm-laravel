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
        <p><a class="btn btn-primary" href="{{ url('admin/area') }}">Về danh sách</a></p>
        <div class="card">
            <div class="card-header">
                <h4>Cập nhật thông tin Khu Vực</h4>
            </div>
            <div class="card-body">
                <div class="col-xs-4 col-xs-offset-4">
                    <form action="{{ url('admin/area/update') }}" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="_token" name="_token" value="{!! csrf_token() !!}" />
                        <input type="hidden" id="id" name="id" value="{!! $getDataById[0]->id !!}" />
                        
                        <div class="form-group">
                            <label for="name"  class="lbl_form">Tên Khu Vực</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Tên"
                                maxlength="255" required value="{{ $getDataById[0]->name}}"
                                placeholder="{{ $getDataById[0]->name }}" />
                        </div>
                 
                        <div class="form-group">
                        <label for="farmid" class="lbl_form">Nông Trại</label>
                            <select required name="farmid" class="form-control">
                                 <option></option><!--selected by default-->
                                 @foreach($farms as $fam)                  
                                 @if ($fam->id == $getDataById[0]->farmid)
                                 <option value="{{ $fam->id  }}" selected >
                                         {{ $fam->name }}
                                     </option>
                                @else
                                <option value="{{ $fam->id }}" >
                                         {{ $fam->name }}
                                     </option>
                                @endif               
                                  
                                 @endforeach
                            </select>
                            </div>
                        <div class="form-group">
                            <label for="status"><input type="checkbox" class="" name="status" id="status"  @php echo $getDataById[0]->status==1? 'checked="checked"': '' ; @endphp /><b> Kích hoạt</b></label><br />
                        </div>
                        <center><button type="submit" class="btn btn-info btn-block">Lưu lại</button></center>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.lbl_form{
    color:#0093ff; 
    font-weight:bold;
}
</style>
<script>
$(document).ready(function() {
   $.ajaxSetup({
       headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }
   });
});
</script>
@endsection