@extends('frontend.frontend_master')
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
            <h4 class="title-table-list-data">DANH SÁCH BÀI KIỂM TRA</h4>
        </div>

        <div class="asi-table-container">
            <div class="table-responsive">
                <table id="grid-data" class="table table-condensed table-hover table-striped">
                    <thead>
                        <tr>
                            <th data-column-id="exam_id" data-identifier="true" data-type="numeric" data-visible="false">ID
                            </th>
                            {{-- <th data-column-id="subject_name"  data-headerAlign="center" data-align="left" data-style="min-width:160px;">Môn Học</th> --}}
                            <th data-column-id="commands" data-formatter="commands" data-sortable="false"
                                data-headerAlign="center" data-align="center" data-style="min-width:200px;">Chức Năng</th>
                            <th data-column-id="exam_title" data-headerAlign="center" data-align="left"
                                data-style="min-width:400px;">Tên Bài KT</th>
                            <th data-column-id="time_open" data-headerAlign="center" data-align="center"
                                data-style="min-width:200px;">Thời Gian Mở Bài KT</th>
                            {{-- <th data-column-id="time_current"  data-headerAlign="center" data-align="center" data-style="min-width:200px;">Thời Gian Hiện Tại</th> --}}
                            <th data-column-id="time_close" data-headerAlign="center" data-align="center"
                                data-style="min-width:200px;">Thời Gian Đóng Bài KT</th>
                            <th data-column-id="time_limit" data-headerAlign="center" data-align="right"
                                data-style="min-width:120px;" data-formatter="time_limit">Thời Gian</th>

                            {{-- <th data-column-id="status" data-headerAlign="center" data-align="center" data-formatter="status" data-style="min-width:120px;">Trạng Thái</th> --}}


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
        var is_first_load = true;
        $(window).focus(function() {
            if(is_first_load==true){
                is_first_load = false;
            }else{
                $('#grid-data').bootgrid('reload');
            }
            //console.log("windows focus");
            //window_focus = true;
        }).blur(function() {
            //window_focus = false;
            //console.log("windows blur");
        });


        var APP_URL = {!! json_encode(url('/')) !!}
        var rowIds = [];

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

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
                    };
                },
                url: "{{ url('/bai-kiem-tra/get_data') }}",
                //url : "URL:/admin/ql-bai-kiem-tra/"+$("#subject_id").val()+"/get_data" ,
                formatters: {
                    "commands": function(column, row) {
                        switch (parseInt(row.status)) {
                            case 0 :
                                return "<button type=\"button\" class=\"btn btn-sm btn-outline-danger\"><span class=\"fa fa-unlock  \"></span></button>";
                            break;
                            case 1 :
                                return "<button type=\"button\" class=\"btn btn-sm btn-outline-primary btn-answer-exam\" data-row-id=\"" +
                                row.id + "\" data-exam-id=\"" + row.exam_id + "\" data-exam-title=\"" +
                                row.exam_title + "\">Làm Bài</button>";
                            break;
                            case 2 :
                                return "<button type=\"button\" class=\"btn btn-sm btn-outline-warning\"><span class=\"fa fa-exclamation-triangle  \"></span></button>";
                            break;
                            case 3 :
                                return "<button type=\"button\" class=\"btn btn-sm btn-outline-success btn-assess-exam\" data-row-id=\"" +
                                    row.id + "\" data-exam-id=\"" + row.exam_id + "\" data-exam-title=\"" +
                                    row.exam_title + "\"> Đánh Giá </button>";
                            break;
                            case 4 :
                                return "<button type=\"button\" class=\"btn btn-sm btn-outline-success btn-view-answer\" data-row-id=\"" +
                                    row.id + "\" data-exam-id=\"" + row.exam_id + "\" data-exam-title=\"" +
                                    row.exam_title + "\"> Xem Bài Làm </button>";
                            break;
                            default:
                                return "";
                            break;
                            
                        }
                        
                      
                        // if (row.status == 0) {
                        //     return "<button type=\"button\" class=\"btn btn-outline-danger\"><span class=\"fa fa-unlock  \"></span></button>";
                        // } else if (row.status == 1) {
                        //     return "<button type=\"button\" class=\"btn btn-outline-primary btn-answer-exam\" data-row-id=\"" +
                        //         row.id + "\" data-exam-id=\"" + row.exam_id + "\" data-exam-title=\"" +
                        //         row.exam_title + "\">Làm Bài</button>";
                        // } else {
                        //     return "<button type=\"button\" class=\"btn btn-outline-warning\"><span class=\"fa fa-exclamation-triangle  \"></span></button>";
                        // }

                    },
                    //   "status": function(column, row) {
                    //      switch(row.status) {
                    //         case 1:
                    //         return "<button title='Đã Mở. Có thể làm bài KT!' type=\"button\" class=\"bootgrid-btn btn-xs btn-default status-enable command-status\" data-row-id=\"" +
                    //         row.id + "\"><span class=\"fa fa-unlock  \"></span></button> ";
                    //         break;
                    //         default :
                    //          return "<button title='Đang Khóa. Chưa thể làm bài KT!' type=\"button\" class=\"bootgrid-btn btn-xs btn-default status-disable command-status\" data-row-id=\"" +
                    //         row.id + "\"><span class=\"fa fa-lock  \"></span></button> ";
                    //         break;

                    //     }
                    // },

                    "time_limit": function(column, row) {
                        return row.time_limit + " phút";
                    },

                }
            }).on("loaded.rs.jquery.bootgrid", function() {

                $("#grid-data .btn-answer-exam").on("click", function(e) {
                    var exam_title = $(this).data("exam-title");
                    var id = $(this).data("row-id");
                    var exam_id = $(this).data("exam-id");
                    var link = APP_URL + '/bai-kiem-tra/' + id +
                        '/lam-bai/' + exam_id;

                    var confirm = $.confirm({
                        title: 'Xác Nhận!',
                        content: "Bạn muốn làm bài KT \"<strong>" + exam_title +
                            "\"</strong>?",
                        buttons: {
                            OK: {
                                text: 'Làm Bài',
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

                $("#grid-data .btn-assess-exam").on("click", function(e) {
                    var exam_title = $(this).data("exam-title");
                    var id = $(this).data("row-id");
                    var exam_id = $(this).data("exam-id");
                    var link = APP_URL + '/bai-kiem-tra/' + id +
                        '/danh-gia/' + exam_id;

                    var confirm = $.confirm({
                        title: 'Xác Nhận!',
                        content: "Bạn muốn đánh giá bài KT \"<strong>" + exam_title +
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

                $("#grid-data .btn-view-answer").on("click", function(e) {
                    var exam_title = $(this).data("exam-title");
                    var id = $(this).data("row-id");
                    var exam_id = $(this).data("exam-id");
                    var link = APP_URL + '/bai-kiem-tra/' + id +
                        '/xem-bai-lam/' + exam_id;

                    var confirm = $.confirm({
                        title: 'Xác Nhận!',
                        content: "Bạn muốn xem bài làm \"<strong>" + exam_title +
                            "\"</strong>?",
                        buttons: {
                            OK: {
                                text: 'Xem',
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
                //         );

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



                // $("#grid-data .command-view").on("click", function(e) {
                //     //alert("You pressed delete on row: " + $(this).data("row-id"));
                //     var id = $(this).data("row-id");

                //     current_row = $("#grid-data").bootgrid("getCurrentRows").filter(
                //         function(elem) {
                //             return elem.id == parseInt(id);
                //         }
                //         );

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
                //                var self = this;
                //                this.buttons.ok.disable();
                //                this.$content.find('.btn').click(function() {
                //                    self.$content.find('input').val('Chuck norris');
                //                    self.buttons.ok.enable();
                //                });
                //            },
                //            contentLoaded: function(data, status, xhr) {
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
                //               }
                //           },
                //       }
                //   });


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


        }); // end-documentReady

    </script>

    <style>
        #grid-data tbody tr:hover {
            color: #3147f7 !important;
            background-color: rgba(0, 141, 168, 0.08) !important;

        }

        #grid-data tbody tr td button.status-disable {
            color: #ff0000;
        }

        #grid-data tbody tr td button.status-enable {
            color: #009800;
        }

        /* #grid-data tbody tr:nth-child(even) {
                            background-color: #f2f2f2 !important;
                            } */

    </style>


@endsection
