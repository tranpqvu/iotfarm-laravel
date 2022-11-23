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


    <div class="container-fluid" style="background-color: #fff; padding: 1em;">
        <div class="container">
            <h4 class="title-table-list-data">CHẤM ĐIỂM BÀI KIỂM TRA</h4>
        </div>
        {{-- <div class="container col-lg-5 col-md-7 col-sm-7 col-xs-12">
            <label for="subject_id" style="color:#0093ff; font-weight:bold;">MÔN HỌC</label>
            <select class="form-control" id="subject_id" name="subject_id" style="color:#0093ff;">
                <option value="-1">Tất cả</option>
                @isset($subjects)
                    @foreach ($subjects as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                @endisset

            </select>
        </div> --}}
        <br />
        <div class="asi-table-container">
            <div class="table-responsive">
                <table id="grid-data" class="table table-condensed table-hover table-striped">
                    <thead>
                        <tr>
                            <th data-column-id="id" data-identifier="true" data-type="numeric" data-visible="false">ID</th>
                            <th data-column-id="last_name" data-style="min-width:200px;">Họ Và Đệm</th>
                            <th data-column-id="first_name" data-style="min-width:100px;">Tên</th>
                            {{-- <th data-column-id="mark"  data-headerAlign="center" data-align="right" data-style="min-width:120px;">Điểm</th> --}}
                            <th data-column-id="total_mark" data-headerAlign="center" data-align="center"
                                data-style="min-width:100px;">Điểm</th>
                            <th data-column-id="class_name" data-style="min-width:100px;">Lớp</th>
                            <th data-column-id="subject_name" data-style="min-width:150px;">Môn Học</th>
                            <th data-column-id="commands" data-formatter="commands" data-sortable="false"
                                data-headerAlign="center" data-align="center" data-style="min-width:200px;">Function</th>
                            <th data-column-id="exam_title" data-style="min-width:200px;">Tên Bài KT</th>
                            {{-- <th data-column-id="status" data-formatter="status" data-style="min-width:100px;">Trạng Thái
                            </th> --}}


                            <th data-column-id="created_by" data-style="min-width:200px;">Người Tạo</th>
                            <th data-column-id="created_at" data-style="min-width:200px;">Ngày Tạo</th>
                            <th data-column-id="updated_by" data-visible="false" data-style="min-width:200px;">Người Sửa
                            </th>
                            <th data-column-id="updated_at" data-visible="false" data-style="min-width:200px;">Ngày Sửa</th>

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
        var is_first_load = true;
        $(window).focus(function() {
            if (is_first_load == true) {
                is_first_load = false;
            } else {
                $('#grid-data').bootgrid('reload');
            }
            //console.log("windows focus");
            //window_focus = true;
        }).blur(function() {
            //window_focus = false;
            //console.log("windows blur");
        });
        var rowIds = [];
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var APP_URL = {!! json_encode(url('/')) !!}

            var grid = $("#grid-data").bootgrid({
                templates: {
                    select: ""
                },
                // buttons: [{
                //         name: "  Thêm",
                //         bclass: "add btn-primary fa fa-plus",
                //         css: "margin-top:0.1em;",
                //         onpress: "process_action_add"
                //     },
                //     {
                //         name: "  Xóa",
                //         css: "margin-top:0.1em;",
                //         bclass: "delete btn-danger fa fa-trash",
                //         onpress: "process_action_delete"
                //     }

                // ],
                ajax: true,
                selection: true,
                multiSelect: true,
                //rowSelect : true,
                post: function() {
                    return {
                        id: "b0df282a-0d67-40e5-8558-c9e93b7befed",
                        exam_class_id: <?php echo $exam_class_id; ?>
                    };
                },
                url: "{{ url('/admin/ql-diem-kiem-tra/get_data') }}",

                formatters: {
                    "commands": function(column, row) {
                        return "<button type=\"button\" class=\"btn btn-sm btn-outline-success btn-teacher-mark\" data-row-id=\"" +
                            row.id + "\" data-row-lastname=\"" + row.last_name +
                            "\" data-row-firstname=\"" + row.first_name + "\"> Chấm Điểm </button>";

                        // if(row.has_mark == -1){
                        //     return "<button type=\"button\" class=\"btn btn-outline-success btn-teacher-mark\" data-row-id=\"" +
                        //                 row.id + "\" data-row-lastname=\"" + row.last_name + "\" data-row-firstname=\"" + row.first_name +"\"> Chấm Điểm </button>"
                        //     ;
                        // }else{
                        //     return("");
                        // }

                        // return "<button title='Xem Câu Hỏi' type=\"button\" class=\"bootgrid-btn btn-xs btn-default command-view\" data-row-id=\"" +
                        //     row.id + "\"><span class=\"fa fa-eye  \"></span></button> " +
                        //     "<button title='Cấu Hình Bài KT Cho Lớp' type=\"button\" class=\"bootgrid-btn btn-xs btn-default command-setup-timing\" data-row-id=\"" +
                        //     row.id + "\"><span class=\"fa fa-calendar-alt\"></span></button> " +
                        //     "<button title='Sửa' type=\"button\" class=\"bootgrid-btn btn-xs btn-default command-edit\" data-row-id=\"" +
                        //     row.id + "\"><span class=\"fa fa-edit  \"></span></button> " +
                        //     "<button title='Xóa' type=\"button\" class=\"bootgrid-btn btn-xs btn-default command-delete\" data-row-id=\"" +
                        //     row.id + "\"><span class=\"fa fa-trash \"></span></button>";

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
                    "image_url": function(column, row) {
                        // return "<img onclick='MymodalImage(this);' alt='"+row.slogan_title+"' src='/local/public/upload/slide/"+ row.image_url +"' style='cursor: zoom-in;' width='60'/>";
                        return "<img onclick='MymodalImage(this);' alt='" + row.slogan_title +
                            "' src='" + row.image_url + "' style='cursor: zoom-in;' width='60'/>";
                    },
                }
            }).on("loaded.rs.jquery.bootgrid", function() {
                /* Executes after data is loaded and rendered */

                $("#grid-data .btn-teacher-mark").on("click", function(e) {

                    var id = $(this).data("row-id");
                    var full_name = $(this).data("row-lastname") + " " + $(this).data(
                        "row-firstname");

                    var link = APP_URL + '/admin/ql-diem-kiem-tra/' + id +
                        '/gv-cham-diem/';

                    var confirm = $.confirm({
                        title: 'Xác Nhận!',
                        content: "Bạn muốn đánh giá bài KT \"<strong>" + full_name +
                            "\"</strong>?",
                        buttons: {
                            OK: {
                                text: 'Đánh Giá',
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


                // $("#grid-data .command-edit").on("click", function(e) {
                //     var id = $(this).data("row-id");
                //     var link = 'admin/ql-bai-kiem-tra/' + id + '/edit';
                //     window.location.href = link;
                // });

                // $("#grid-data .command-delete").on("click", function(e) {
                //     //alert("You pressed delete on row: " + $(this).data("row-id"));
                //     var id = $(this).data("row-id");
                //     var name = "";
                //     current_row = $("#grid-data").bootgrid("getCurrentRows").filter(
                //         function(elem) {
                //             return elem.id == parseInt(id);
                //         }
                //     );

                //     $.confirm({
                //         title: "Thông Báo!",
                //         content: "Bạn Muốn Xóa Dòng Này?",
                //         buttons: {
                //             Yes: {
                //                 text: "Yes",
                //                 btnClass: "btn-blue",
                //                 action: function() {
                //                     delete_many_row([id]);
                //                 }
                //             },
                //             No: function() {

                //             }
                //         },
                //         onOpenBefore: function() {
                //             $(".jconfirm-content").css("text-align", "center");
                //         },
                //     })
                // });

                // $("#grid-data .command-status").on("click", function(e) {
                //     //alert("You pressed delete on row: " + $(this).data("row-id"));
                //     var id = $(this).data("row-id");

                //     current_row = $("#grid-data").bootgrid("getCurrentRows").filter(
                //         function(elem) {
                //             return elem.id == parseInt(id);
                //         }
                //     );

                //     if (current_row.length > 0) {
                //         status = current_row[0].status;
                //         if (status == 1) {
                //             msg = "Bạn muốn hủy kích hoạt?";
                //         } else {
                //             msg = "Bạn muốn kích hoạt?";
                //         }

                //         $.confirm({
                //             title: "Thông Báo!",
                //             content: msg,
                //             buttons: {
                //                 Yes: {
                //                     text: "Yes",
                //                     btnClass: "btn-blue",
                //                     action: function() {
                //                         change_status(id, status);
                //                     }
                //                 },
                //                 No: function() {

                //                 }
                //             },
                //             onOpenBefore: function() {
                //                 $(".jconfirm-content").css("text-align", "center");
                //             },
                //         });
                //     }

                // });


                // $("#grid-data .command-view").on("click", function(e) {
                //     //alert("You pressed delete on row: " + $(this).data("row-id"));
                //     var id = $(this).data("row-id");

                //     current_row = $("#grid-data").bootgrid("getCurrentRows").filter(
                //         function(elem) {
                //             return elem.id == parseInt(id);
                //         }
                //     );

                //     var link = "'/admin/ql-bai-kiem-tra/" + id + "/xem-cau-hoi'";
                //     console.log(link);
                //     $.confirm({
                //         title: false,
                //         //closeIcon: true,
                //         //content: 'url:' +  "{{ url('/admin/ql-bai-kiem-tra/" +id+"/xem-cau-hoi') }}" , //'url:callback.html',
                //         content: "URL:/admin/ql-bai-kiem-tra/" + id + "/xem-cau-hoi",
                //         type: "blue",
                //         columnClass: "col-lg-12 col-lg-offset-0  col-md-12 col-md-offset-0 col-sm-12 col-sm-offset-0 col-xs-12",
                //         draggable: true,
                //         onContentReady: function() {
                //             // when content is fetched & rendered in DOM
                //             //  alert('onContentReady');
                //             /*   var self = this;
                //                this.buttons.ok.disable();
                //                this.$content.find('.btn').click(function() {
                //                    self.$content.find('input').val('Chuck norris');
                //                    self.buttons.ok.enable();
                //                });*/
                //         },
                //         contentLoaded: function(data, status, xhr) {
                //             // when content is fetched
                //             //alert('contentLoaded: ' + status);
                //         },
                //         onOpenBefore: function() {
                //             $(".jconfirm-buttons").addClass("jsconfirm-button-container")
                //             // before the modal is displayed.
                //             //alert('onOpenBefore');
                //         },
                //         onOpen: function() {
                //             // after the modal is displayed.
                //             //alert('onOpen');
                //         },
                //         onClose: function() {
                //             // before the modal is hidden.
                //             //alert('onClose');
                //         },
                //         onDestroy: function() {
                //             // when the modal is removed from DOM
                //             //alert('onDestroy');
                //         },
                //         onAction: function(btnName) {
                //             // when a button is clicked, with the button name
                //             //alert('onAction: ' + btnName);
                //         },
                //         buttons: {
                //             OK: {
                //                 text:"&nbsp; OK &nbsp;",
                //                 btnClass: 'btn-blue',
                //                 action: function() {
                //                   this.close();
                //                 }
                //             },
                //         }
                //     });


                //     // if(current_row.length > 0){
                //     //     name = current_row[0].name;
                //     // }

                //     /*$.confirm({
                //         title: "Thông Báo!",
                //         content: "Bạn Muốn Xóa Dòng Này?",
                //         buttons: {
                //             Yes: {
                //                 text: "Yes",
                //                 btnClass: "btn-blue",
                //                 action: function() {
                //                     delete_many_row([id]);
                //                 }
                //             },
                //             No: function() {

                //             }
                //         },
                //         onOpenBefore: function() {
                //             $(".jconfirm-content").css("text-align", "center");
                //         },
                //     })*/

                // });


            });



            // $("#subject_id").change(function() {
            //     $('#grid-data').bootgrid('reload');
            // });
        }); // end-documentReady

        function process_action_add() {
            window.location.href = "{{ url('admin/ql-bai-kiem-tra/create') }}";
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
                url: "{{ url('/admin/ql-bai-kiem-tra/delete') }}",
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
                url: "{{ url('/admin/ql-bai-kiem-tra/status') }}",
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

        /* #grid-data tbody tr:nth-child(even) {
                background-color: #f2f2f2 !important;
            } */

    </style>

@endsection
