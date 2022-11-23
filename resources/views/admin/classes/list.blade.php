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
<div class="container-fluid" style="background-color: #fff; padding: 1em;">
    <div class="container">
        <h4 class="title-table-list-data">DANH SÁCH LỚP HỌC</h4>
        <div class="row justify-content-md-center">
            <div class=" col-lg-5 col-md-5 col-sm-6 col-xs-12">
                <label for="school_year_id" style="color:#0093ff; font-weight:bold;">Năm Học</label>
                <select class="form-control" id="school_year_id" name="school_year_id" style="color:#0093ff;">
                  {{--   <option value="">Tất cả</option> --}}
                    @isset($school_years)
                    @foreach ($school_years as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                    @endisset
                </select>
            </div>
          {{--   <div class=" col-lg-5 col-md-5 col-sm-6 col-xs-12">
                <label for="subject_id" style="color:#0093ff; font-weight:bold;">Môn Học</label>
                <select class="form-control" id="subject_id" name="subject_id" style="color:#0093ff;">
                    <option value="">Tất cả</option>
                    @isset($subjects)
                    @foreach ($subjects as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                    @endisset
                </select>
            </div> --}}
        </div>
    </div>
    <br />
    <div class="asi-table-container">
        <div class="table-responsive">
            <table id="grid-data" class="table table-condensed table-hover table-striped">
                <thead>
                    <tr>
                        <th data-column-id="id" data-identifier="true" data-type="numeric" data-visible="false">ID</th>
                        <th data-column-id="name" data-headerAlign="center" data-align="center"
                        data-style="min-width:150px;">Lớp Học</th>

                     {{--    <th data-column-id="subject_name" data-headerAlign="center" data-align="center"
                        data-style="min-width:150px;">Môn Học</th>
                         --}}
                        <th data-column-id="school_year_name" data-headerAlign="center" data-align="center"
                        data-style="min-width:150px;">Năm Học</th>
                        
                        <th data-column-id="status" data-headerAlign="center" data-align="center"
                        data-formatter="status" data-style="min-width:120px;">Trạng Thái</th>
                        
                        <th data-column-id="commands" data-formatter="commands" data-sortable="false"
                        data-headerAlign="center" data-align="center" data-style="min-width:150px;">Function</th>
                        
                        <th data-column-id="created_by" data-headerAlign="center" data-align="center"
                        data-style="min-width:200px;">Người Tạo</th>
                        
                        <th data-column-id="created_at" data-headerAlign="center" data-align="center"
                        data-style="min-width:200px;">Ngày Tạo</th>
                        
                        <th data-column-id="updated_by" data-headerAlign="center" data-align="center"
                        data-visible="false" data-style="min-width:200px;">Người Sửa</th>

                        <th data-column-id="updated_at" data-headerAlign="center" data-align="center"
                        data-visible="false" data-style="min-width:200px;">Ngày Sửa</th>
                        <th data-column-id="exam_id" data-visible="false" data-style="min-width:1px;"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="img01">
        <div id="caption"></div>
    </div>
</div>
<script>
// $("#header-page-name").html("Quản Lý Bài Kiểm Tra");
// $(".admin-sidebar").removeClass("active");
// $("#tab-question").addClass("active")
var rowIds = [];
$(document).ready(function() {
    var APP_URL = {!! json_encode(url('/')) !!}
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var grid = $("#grid-data").bootgrid({
        templates: {
            select: ""
        },
        buttons: [{
            name: "  Thêm",
            bclass: "add btn-primary fa fa-plus",
            css: "margin-top:0.1em;",
            onpress: "process_action_add"
        },
        {
            name: "  Xóa",
            css: "margin-top:0.1em;",
            bclass: "delete btn-danger fa fa-trash",
            onpress: "process_action_delete"
        }
        ],
        ajax: true,
        selection: true,
        multiSelect: true,
        //rowSelect : true,
        post: function() {
            return {
                id: "b0df282a-0d67-40e5-8558-c9e93b7befed",
                school_year_id: $("#school_year_id").val(),
                subject_id: $("#subject_id").val()
            };
        },
        url: "{{ url('/admin/ql-lop-hoc/get_data') }}",
        formatters: {
            "commands": function(column, row) {
                return "<button title='Xem Danh Sách Học Sinh' type=\"button\" class=\"bootgrid-btn btn-xs btn-default command-view\" data-row-id=\"" +
                row.id + "\" data-row-title=\"" +
                row.name + "\"><span class=\"fa fa-eye  \"></span></button> " +

                "<button title='Sửa' type=\"button\" class=\"bootgrid-btn btn-xs btn-default command-edit\" data-row-id=\"" +
                row.id + "\"><span class=\"fa fa-edit  \"></span></button> " +
                "<button title='Xóa' type=\"button\" class=\"bootgrid-btn btn-xs btn-default command-delete\" data-row-id=\"" +
                row.id + "\"><span class=\"fa fa-trash \"></span></button>"
                    //+"<button title='Chấm Điểm' type=\"button\" class=\"bootgrid-btn btn-xs btn-default command-mark\" data-row-id=\"" +
                    //row.id + "\" data-row-title=\"" + row.exam_title + "\" ><span class=\"color:#028e11;\">Chấm Điểm</span></button>"
                    ;
                },
                "status": function(column, row) {
                    if (row.status == 1) {
                        return "<button title='Hủy Kích Hoạt' type=\"button\" class=\"bootgrid-btn btn-xs btn-default status-enable command-status\" data-row-id=\"" +
                        row.id + "\"><span class=\"fa fa-check  \"></span></button> ";
                    } else {
                        return "<button title='Kích Hoạt' type=\"button\" class=\"bootgrid-btn btn-xs btn-default status-disable command-status\" data-row-id=\"" +
                        row.id + "\"><span class=\"fa fa-times  \"></span></button> ";
                    }
                },
            // "image_url": function(column, row) {
            //     // return "<img onclick='MymodalImage(this);' alt='"+row.slogan_title+"' src='/local/public/upload/slide/"+ row.image_url +"' style='cursor: zoom-in;' width='60'/>";
            //     return "<img onclick='MymodalImage(this);' alt='" + row.slogan_title +
            //         "' src='" + row.image_url + "' style='cursor: zoom-in;' width='60'/>";
            // },
        }
    }).on("loaded.rs.jquery.bootgrid", function() {
        /* Executes after data is loaded and rendered */
        $("#grid-data .command-edit").on("click", function(e) {
            var id = $(this).data("row-id");
            var link = 'admin/ql-lop-hoc/' + id + '/cap-nhat';
            window.location.href = link;
        });
        $("#grid-data .command-delete").on("click", function(e) {
            //alert("You pressed delete on row: " + $(this).data("row-id"));
            var id = $(this).data("row-id");
            var name = "";
            current_row = $("#grid-data").bootgrid("getCurrentRows").filter(
                function(elem) {
                    return elem.id == parseInt(id);
                }
                );
            $.confirm({
                title: "Thông Báo!",
                content: "Bạn Muốn Xóa Dòng Này?",
                buttons: {
                    Yes: {
                        text: "Yes",
                        btnClass: "btn-blue",
                        action: function() {
                            delete_many_row([id]);
                        }
                    },
                    No: function() {
                    }
                },
                onOpenBefore: function() {
                    $(".jconfirm-content").css("text-align", "center");
                },
            })
        });
        $("#grid-data .command-status").on("click", function(e) {
            //alert("You pressed delete on row: " + $(this).data("row-id"));
            var id = $(this).data("row-id");
            current_row = $("#grid-data").bootgrid("getCurrentRows").filter(
                function(elem) {
                    return elem.id == parseInt(id);
                }
                );
            if (current_row.length > 0) {
                status = current_row[0].status;
                if (status == 1) {
                    msg = "Bạn muốn hủy kích hoạt?";
                } else {
                    msg = "Bạn muốn kích hoạt?";
                }
                $.confirm({
                    title: "Thông Báo!",
                    content: msg,
                    buttons: {
                        Yes: {
                            text: "Yes",
                            btnClass: "btn-blue",
                            action: function() {
                                change_status(id, status);
                            }
                        },
                        No: function() {
                        }
                    },
                    onOpenBefore: function() {
                        $(".jconfirm-content").css("text-align", "center");
                    },
                });
            }
        });
        // $("#grid-data .command-view").on("click", function(e) {
        //     //alert("You pressed delete on row: " + $(this).data("row-id"));
        //     var id = $(this).data("row-id");
        //     current_row = $("#grid-data").bootgrid("getCurrentRows").filter(
        //         function(elem) {
        //             return elem.id == parseInt(id);
        //         }
        //         );
        //     var link = "'/admin/ql-lop-hoc/" + id + "/ds-hoc-sinh'";
        //     console.log(link);

        // });
        $("#grid-data .command-view").on("click", function(e) {
            var exam_title = $(this).data("row-title");
            var id = $(this).data("row-id");
            var link = APP_URL + '/admin/ql-lop-hoc/' + id + "/ds-hoc-sinh";
            var confirm = $.confirm({
                title: 'Xác Nhận!',
                content: "Bạn muốn xem ds học sinh lớp \"<strong>" + exam_title +
                "\"</strong>?",
                buttons: {
                    OK: {
                        text: 'Xem DS Học Sinh',
                        btnClass: 'btn-blue',
                                //keys: ['enter', 'shift'],
                                action: function() {
                                    var win = window.open(link, '_blank');
                                    if (win) {
                                        //Browser has allowed it to be opened
                                        win.focus();
                                    } else {
                                        //Browser has blocked it
                                        //alert('Please allow popups for this website');
                                        $.alert({
                                            title: "<strong class='alert-msg-success'>Cảnh Báo!</strong>",
                                            content: "Please allow popups for this website!",
                                            type: "green",
                                            autoClose: "OK|3000",
                                            buttons: {
                                                OK: function() {
                                                }
                                            },
                                            onOpenBefore: function() {
                                                $(".jconfirm-content").css(
                                                    "text-align",
                                                    "center");
                                            },
                                        });
                                    }
                                    //window.location.href = link;
                                }
                            },
                            Cancel: {
                                text: 'Hủy',
                                btnClass: 'btn-orange',
                                //keys: ['enter', 'shift'],
                                action: function() {
                                    //$.alert('Hủy');
                                    confirm.close();
                                }
                            }
                        }
                    });
        });
    });

    $("#subject_id").change(function() {
        $('#grid-data').bootgrid('reload');
    });

    $("#school_year_id").change(function() {
        $('#grid-data').bootgrid('reload');
    });

}); // end-documentReady
function process_action_add() {
    window.location.href = "{{ url('admin/ql-lop-hoc/them-moi') }}";
}
function process_action_delete() {
    var arr_id = $("#grid-data").bootgrid("getSelectedRows");
    console.log(arr_id);
    if (arr_id.length > 0) {
        if (arr_id.length > 1) {
            content = "<p style='text-align:center;'>Bạn muốn xóa những dòng đã chọn?</p>";
        } else {
            content = "<p style='text-align:center;'>Bạn muốn xóa dòng này?</p>";
        }
        $.confirm({
            title: "Bạn Có Chắc!",
            content: content,
            buttons: {
                Yes: {
                    text: "Yes",
                    btnClass: "btn-blue",
                    action: function() {
                        delete_many_row(arr_id);
                    }
                },
                No: function() {}
            },
            onOpenBefore: function() {
                $(".jconfirm-content").css("text-align", "center");
            },
        })
    } else {
        $.alert({
            title: "<strong class='alert-msg-warning'>Thông Báo!</strong>",
            content: "Xin chọn dòng dữ liệu cần xóa!",
            type: "yellow",
            autoClose: "OK|3000",
            buttons: {
                OK: function() {
                }
            },
            onOpenBefore: function() {
                $(".jconfirm-content").css("text-align", "center");
            },
        });
    }
}
/*
Delete many template
*/
function delete_many_row(arr_id) {
    $.ajax({
        url: "{{ url('/admin/ql-lop-hoc/xoa') }}",
        data: {
            id: arr_id
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
                $.alert({
                    title: "<strong class='alert-msg-success'>Success!</strong>",
                    content: response.msg,
                    type: "green",
                    autoClose: "OK|3000",
                    buttons: {
                        OK: function() {
                        }
                    },
                    onOpenBefore: function() {
                        $(".jconfirm-content").css("text-align", "center");
                    },
                });
                break;
                default:
                $.alert({
                    title: "<strong class='alert-msg-eror'>Error!</strong>",
                    content: response.msg,
                    type: "red",
                    autoClose: "OK|3000",
                    buttons: {
                        OK: function() {
                        }
                    },
                    onOpenBefore: function() {
                        $(".jconfirm-content").css("text-align", "center");
                    },
                });
                break;
            }
            $("#grid-data").bootgrid("reload");
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
                onDestroy: function() {
                    $("#grid-data").bootgrid("reload");
                }
            });
        }
    });
}
function change_status(id, status) {
    new_status = status == 1 ? 0 : 1;
    $.ajax({
        url: "{{ url('/admin/ql-lop-hoc/status') }}",
        data: {
            id: id,
            status: new_status
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
                $.alert({
                    title: "<strong class='alert-msg-success'>Success!</strong>",
                    content: "<p class='text-center'>" + response.msg + "</p>",
                    type: "green",
                    autoClose: "OK|3000",
                    buttons: {
                        OK: function() {
                            return true;
                        }
                    },
                    onDestroy: function() {
                        return true;
                    }
                });
                break;
                default:
                $.alert({
                    title: "<strong class='alert-msg-eror'>Error!</strong>",
                    content: "<p class='text-center'>" + response.msg + "</p>",
                    type: "red",
                    autoClose: "OK|3000",
                    buttons: {
                        OK: function() {}
                    },
                    onDestroy: function() {
                        return false;
                    }
                });
                break;
            }
            $("#grid-data").bootgrid("reload");
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $.alert({
                title: "<strong class='alert-msg-error'>Error!</strong>",
                content: "<p class='text-center'>Connect to server fail. Please reload page and try again!</p>",
                type: "red",
                autoClose: "OK|3000",
                buttons: {
                    OK: function() {
                        // location.reload();
                    }
                },
                onDestroy: function() {
                    // location.reload();
                },
            });
            $("#grid-data").bootgrid("reload");
        }
    });
    return false;
}
</script>
<style>
#grid-data tbody tr:hover {
    color: #3147f7 !important;
    background-color: rgba(0, 141, 168, 0.08) !important;
}
</style>
@endsection
