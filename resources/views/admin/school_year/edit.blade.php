@extends('admin.admin_master')


@section('content')

    <?php
    //Hiển thị thông báo thành công
    ?>
    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <strong>{{ Session::get('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
        </div>
    @endif

    <?php
    //Hiển thị thông báo lỗi
    ?>
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
            <p><a class="btn btn-primary" href="{{ url('admin/ql-nam-hoc') }}">Về danh sách</a></p>
            <div class="card">
                <div class="card-header">
                    <h4>Cập nhật thông tin</h4>
                </div>

                <div class="card-body">
                    <?php
                    //Hiển thị form sửa ảnh
                    ?>
                    <div class="col-xs-4 col-xs-offset-4">
                        <form action="{{ url('admin/ql-nam-hoc/update') }}" method="post" enctype="multipart/form-data">
                            <input type="hidden" id="_token" name="_token" value="{!! csrf_token() !!}" />
                            <input type="hidden" id="id" name="id" value="{!! $getDataById[0]->id !!}" />
                             <div class="form-group">
                                <label for="subject_id" style="color:#0093ff; font-weight:bold;">Năm Bắt Đầu</label>
                                <input type="text" class="form-control" id="year_open" name="year_open" placeholder="Năm Học"
                                    maxlength="255" required value="{{ $getDataById[0]->year_open}}"
                                    placeholder="{{ $getDataById[0]->year_open }}" />
                            </div>
                            <div class="form-group">
                                <label for="subject_id" style="color:#0093ff; font-weight:bold;">Năm Kết Thúc</label>
                                <input type="text" class="form-control" id="year_close" name="year_close" placeholder="Năm Học"
                                    maxlength="255" required value="{{ $getDataById[0]->year_close}}"
                                    placeholder="{{ $getDataById[0]->year_close }}" />
                            </div>
                            <!-- <div class="form-group">
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Năm Học" maxlength="255"
                                    value="{{ $getDataById[0]->name}}"
                                    placeholder="{{ $getDataById[0]->name }}" required />
                            </div>   -->                        
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
    {{-- <script type="text/javascript">
        setInputFilter(document.getElementById("idx"), function(value) {
            return /^\d*$/.test(value) && (value === "" || parseInt(value) <= 255);
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width("100%")
                        .height("auto");
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
        // Restricts input for the given textbox to the given inputFilter.
        function setInputFilter(textbox, inputFilter) {
            ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
                textbox.addEventListener(event, function() {
                    if (inputFilter(this.value)) {
                        this.oldValue = this.value;
                        this.oldSelectionStart = this.selectionStart;
                        this.oldSelectionEnd = this.selectionEnd;
                    } else if (this.hasOwnProperty("oldValue")) {
                        this.value = this.oldValue;
                        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                    }
                });
            });
        }

    </script> --}}
@endsection
