@extends('admin.admin_master')

@section('title', 'Cập Nhật Bài Kiểm Tra')

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
        <div class="col-lg-10 col-md-10">
            <p><a class="btn btn-primary" href="{{ url('admin/ql-bai-kiem-tra') }}">Về danh sách</a></p>
            <div class="card">
                <div class="card-header">
                    <h4>Cập Nhật Bài Kiểm Tra</h4>
                </div>
                <div class="card-body">
                    <div class="col-xs-4 col-xs-offset-4">
                        <form method="POST" action="{{ url('admin/ql-bai-kiem-tra/create') }}"
                            enctype="multipart/form-data">
                            <!-- <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}" /> -->
                            <input type="hidden" id="id" name="id" value="{!! $getDataById[0]->id !!}" />
                            <div class="form-group">
                                <label for="subject_id" style="color:#0093ff; font-weight:bold;">Môn Học</label>
                                <select class="form-control" id="subject_id" name="subject_id" style="color:#0093ff;">                              
                                    @isset($subjects)
                                        @foreach ($subjects as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == $getDataById[0]->subject_id? "selected":"" }}>{{ $item->name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title" style="color:#0093ff; font-weight:bold;">Tiêu Đề</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Tiêu Đề"
                                    maxlength="255" value="{{ $getDataById[0]->title }}"
                                    placeholder="{{ $getDataById[0]->title }}" required />
                            </div>
                            <div class="form-group">
                                <label for="mark" style="color:#0093ff; font-weight:bold;">Tổng Điểm</label>
                                <input type="text" class="txt-float-value form-control" id="mark" name="mark" placeholder="Tổng Điểm"
                                    value="{{ $getDataById[0]->mark }}"
                                    placeholder="{{ $getDataById[0]->mark }}" required />
                            </div>
                            <div class="form-group">
                                <label for="title" style="color:#0093ff; font-weight:bold;">Ghi Chú</label>
                                <textarea type="text" class="form-control" id="note" name="note" placeholder="Ghi Chú">
                                {{ $getDataById[0]->note }}
                                </textarea>
                            </div>
                            <div class="form-group">
                            <input type="checkbox" class="" name="status" id="status" 
                                <?php if
                                ($getDataById[0]->status) {
                                    echo 'checked';
                                }?> /> 
                                <b>Kích hoạt</b><br />
                            </div>
                            <div class="asi-table-container">
                                <div class="table-responsive">
                                    <div class="container" style="padding: 1em; 0.5em;">
                                        <span class="btn btn-primary" id="mc-btn-add-question">Thêm Câu Hỏi</span>
                                    </div>
                                    <table id="grid-data-add-question"
                                        class="grid-data table table-condensed table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th data-column-id="idx" data-style="min-width:20px;">Thứ Tự</th>
                                          
                                                <th data-column-id="content" data-style="min-width:400px;"
                                                    data-visible="false">Nội Dung</th>     
                                                    <th data-column-id="mark" data-style="min-width:100px;"
                                                    data-visible="false" data-headerAlign="center" data-align="right">Điểm</th>                                    
                                                <th data-column-id="commands" data-formatter="commands"
                                                    data-sortable="false" data-headerAlign="center" data-align="center"
                                                    data-style="min-width:200px;">Function</th>
                                            </tr>
                                        </thead>
                                        <tbody>  
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <center style="margin-top:1em;"><span id="btn_save_question"class="btn btn-info btn-block"
                                    style="width:50%;">Lưu</span></center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        button.command-delete {
            color: #ff0000;
        }

        button.command-edit {
            color: #b5017f;
        }

        button.command-up {
            color: #00ad3b;
        }

        button.command-down {
            color: #d5a000;
        }

    </style>


    <script>
        var data_edit = <?php echo json_encode($data); ?>; 
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            LoadQuestionData(data_edit)

            $("#btn_save_question").click(function(){
                id = $("#id").val();
                subject_id = $("#subject_id").val();
                title = $("#title").val();
                mark = $("#mark").val();
                note =$("#note").val();
                status = $("#status").is(":checked");
                questions = data_edit;
    
                $.ajax({
                    url: "{{ url('/admin/ql-bai-kiem-tra/update') }}",
                    data: {
                        //_token : $("#_token").val(),
                        id:id,
                        subject_id: subject_id,
                        title:title,
                        mark:mark,
                        note:note,
                        status:status,
                        questions:questions
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
                                    onClose: function () {
                                        location.reload();

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
                                        //$(".jconfirm-content").css("text-align", "center");
                                    },
                                });

                                break;
                        }


                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log(xhr.responseText);
                        $.alert({
                            title: "<strong class='alert-msg-error'>Error!</strong>",
                            content: xhr.responseText["message"],
                            type: "red",
                            buttons: {
                                OK: function() {
                                   // location.reload();
                                }
                            },
                            onDestroy: function() {

                            }
                        });
                    }
                });

            });

            $("#mc-btn-add-question").click(function() {
                question_data_edit = undefined;
                var confirm_dialog = $.confirm({
                    title: "Thêm Câu Hỏi",
                    draggable: true,
                    //closeIcon: true,
                    //content: 'url:' +  "{{ url('/admin/ql-bai-kiem-tra/" +id+"/xem-cau-hoi') }}" , //'url:callback.html',
                    content: "URL:/admin/ql-bai-kiem-tra/cau-hoi",
                    type: "blue",
                    columnClass: "col-lg-12 col-lg-offset-0 col-md-12 col-md-offset-0 col-sm-12 col-sm-offset-0 col-xs-12",
                    draggable: true,
                    buttons: {
                        Save: {
                            text: "&nbsp;&nbsp;Lưu&nbsp;&nbsp;",
                            btnClass: "btn-blue",
                            action: function() {
                                // alert("lưu");
                                var tmp = getData(data_edit.length + 1);
                                data_edit.push(tmp);
                                console.log(data_edit);
                                LoadQuestionData(data_edit);
                                return false;
                            }
                        },
                        Cancel: {
                            text: "Thoát",
                            btnClass: "btn-dark",
                            action: function() {
                                confirm_dialog.close();
                            }
                        },
                    },
                    onContentReady: function() {
                        $(".jsconfirm-button-container").remove();
                         $(".jconfirm-buttons").css({"width": "100%", "text-align": "center"})
                        // when content is fetched & rendered in DOM
                        //  alert('onContentReady');
                        /*   var self = this;
                           this.buttons.ok.disable();
                           this.$content.find('.btn').click(function() {
                               self.$content.find('input').val('Chuck norris');
                               self.buttons.ok.enable();
                           });*/
                    },
                    contentLoaded: function(data, status, xhr) {
                        // when content is fetched
                        //alert('contentLoaded: ' + status);
                    },
                    onOpenBefore: function() {
                        //$(".jconfirm-buttons").addClass("jsconfirm-button-container")
                        // before the modal is displayed.
                        //alert('onOpenBefore');
                    },
                    onOpen: function() {
                        // after the modal is displayed.
                        //alert('onOpen');
                    },
                    onClose: function() {
                        // before the modal is hidden.
                        //alert('onClose');
                    },
                    onDestroy: function() {
                        // when the modal is removed from DOM
                        //alert('onDestroy');
                    },
                    onAction: function(btnName) {
                        // when a button is clicked, with the button name
                        //alert('onAction: ' + btnName);
                    },

                });
            });

        });

        $('.txt-float-value').keypress(function(event) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });

        function LoadQuestionData(data_edit) {
            row = "";
            $.each(data_edit, function(key, value) {
                //if(value["question_type"]=="0"){
                row = row + "<tr data-idx=\"" + value["question_idx"] + "\">";
                row = row + "<td>" + value["question_idx"] + "</td>";
               
                row = row + "<td>";
                row = row + value["question_content"];
                //row = row + '<br />';
                $.each(value["answers"], function(k, val) {
                    key_id = key.toString() + '_' + k.toString();
                    key_name = (key + 1).toString();
                    is_answer = val["is_answer"] == 1 ? " checked " : " disabled ";
                    row = row + "<br><input type=\"radio\" id=\"question_" + key_id +
                        "\" name=\"question_" + key_name + "\" value=\"" + val["answer_name"] + "\" " +
                        is_answer + " > <label for=\"question_" + key_id + "\">" + val['answer_name'] +
                        ". " + val["answer_content"] + "</label>";
                });

                row = row + "</td>";
                row = row + "<td>" + value["question_mark"] + "</td>";
                row = row + "<td>";
                row = row +
                    "<button title=\"Lên\" type=\"button\" class=\"bootgrid-btn btn-xs btn-default command-up\" data-row-id=\"1\"><span class=\"fa fa-arrow-up \"></span></button>";
                row = row +
                    "<button title=\"Xuống\" type=\"button\" class=\"bootgrid-btn btn-xs btn-default command-down\" data-row-id=\"1\"><span class=\"fa fa-arrow-down \"></span></button>";
                row = row +
                    "<button title=\"Xóa\" type=\"button\" class=\"bootgrid-btn btn-xs btn-default command-edit\" data-row-id=\"1\"><span class=\"fa fa-edit \"></span></button>";
                row = row +
                    "<button title=\"Xóa\" type=\"button\" class=\"bootgrid-btn btn-xs btn-default command-delete\" data-row-id=\"1\"><span class=\"fa fa-trash \"></span></button>";
                row = row + "</td>";
                row = row + "</tr>";
                //}
            });

            $("#grid-data-add-question tbody").html(row);

            //initFunction();

            $(".command-delete").click(function() {
                idx = $(this).parent().parent().data("idx");
                if (data_edit.length > 0) {
                    for(i=0 ;  i < data_edit.length; i++){
                        if(data_edit[i].question_idx == idx){
                             data_edit.splice(i,1);
                             break;
                        }
                    }
                    // $.each(data_edit, function(key, value) {
                    //     console.log(data_edit[key].question_idx);
                        
                    // });
                    // data_edit = data_edit.filter(function(object) {
                    //     return object.question_idx != idx;
                    // });
                }
                $.each(data_edit, function(key, value) {
                    value.question_idx = key + 1;
                });

                LoadQuestionData(data_edit);
                console.log("aftter delete : ");
                console.log(data_edit);
            });

            $(".command-edit").click(function() {
                idx = $(this).parent().parent().data("idx");
                if (data_edit.length > 0) {
                    question_data_edit = data_edit.filter(function(object) {
                        return object.question_idx == idx;
                    });
                    console.log(question_data_edit);
                    var confirm_dialog_edit = $.confirm({
                        title: "Cập Nhật Câu Hỏi",
                        draggable: true,
                        content: "URL:/admin/ql-bai-kiem-tra/cau-hoi",
                        type: "blue",
                        columnClass: "col-lg-12 col-lg-offset-0 col-md-12 col-md-offset-0 col-sm-12 col-sm-offset-0 col-xs-12",
                        draggable: true,
                        buttons: {
                            Save: {
                                text: "Lưu",
                                btnClass: "btn-blue",
                                action: function() {
                                    //alert("lưu");
                                    var tmp = getData(question_data_edit[0]["question_idx"]);
                                    //if(data.length)
                                    $.each(data_edit, function(key, value) {
                                        if(value["question_idx"]==question_data_edit[0]["question_idx"]){
                                            data_edit[key] = tmp;

                                        }
                                    });
                                    //data.push(tmp);
                                    console.log(data_edit);
                                    LoadQuestionData(data_edit);
                                    //return false;
                                }
                            },
                            Cancel: {
                                text: "Thoát",
                                btnClass: "btn-dark",
                                action: function() {
                                    confirm_dialog_edit.close();
                                }
                            },
                        },
                        onContentReady: function() {

                            $(".jsconfirm-button-container").remove();

                            $(".jconfirm-buttons").css({"width": "100%", "text-align": "center"})

                        },
                        contentLoaded: function(data, status, xhr) {
                            // when content is fetched
                            //alert('contentLoaded: ' + status);

                        },
                        onOpenBefore: function() {
                            //$(".jconfirm-buttons").addClass("jsconfirm-button-container")
                            // before the modal is displayed.
                            //alert('onOpenBefore');
                        },
                        onOpen: function() {
                            // after the modal is displayed.
                            //alert('onOpen');

                        },
                        onClose: function() {
                            // before the modal is hidden.
                            //alert('onClose');
                        },
                        onDestroy: function() {
                            // when the modal is removed from DOM
                            //alert('onDestroy');
                        },
                        onAction: function(btnName) {
                            // when a button is clicked, with the button name
                            //alert('onAction: ' + btnName);
                        },

                    });
                }
            });
        }

    </script>




   
@endsection
