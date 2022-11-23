@extends('frontend.frontend_master')
@section('content')
@php
$all_element = [];
$exam_id = -1;
$school_year_id = null;
$school_year_open = null;
$school_year_close = null;
$teacher_id = null;
$teacher_first_name = null;
$teacher_last_name = null;
$class_id = null;
$class_name = null;
$upload_file_id=null;
@endphp
<div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <h4 style="color:#0089ff; text-align:center;">
                    @isset($exam)
                    @php
                    if (count($exam) > 0) {
                        echo $exam[0]->title . ' - ' . $check_assessment;
                        $exam_id = $exam[0]->id;
                    } else {
                        echo 'BÀI KIỂM TRA';
                    }
                    @endphp
                    @endisset
                </h4>

                <div class="container">
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 dc-exam-info-title">
                            Niên Khóa:
                        </div>
                        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6 dc-exam-info-content">
                            @isset($exam_config)
                            @php
                            if ($exam_config != null && count($exam_config) > 0) {
                                echo "<strong style=\"color:#0089ff;\">" . $exam_config[0]->year_open . '-' . $exam_config[0]->year_close . '</strong>';
                                $school_year_id = $exam_config[0]->year_id;
                                $school_year_open = $exam_config[0]->year_open;
                                $school_year_close = $exam_config[0]->year_close;
                            } else {
                                echo '<strong style=\"color:#0089ff;\"></strong>';
                            }
                            @endphp
                            @endisset
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 dc-exam-info-title">
                            Môn Học:
                        </div>
                        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6 dc-exam-info-content">
                            @isset($exam_config)
                            @php
                            if ($exam_config != null && count($exam_config) > 0) {
                                echo "<strong style=\"color:#0089ff;\">" . $exam_config[0]->subject_name . '</strong>';
                            } else {
                                echo '<strong style=\"color:#0089ff;\"></strong>';
                            }
                            @endphp
                            @endisset
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 dc-exam-info-title">
                            Lớp:
                        </div>
                        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6 dc-exam-info-content">
                            @isset($exam_config)
                            @php
                            if ($exam_config != null && count($exam_config) > 0) {
                                echo "<strong style=\"color:#0089ff;\">" . $exam_config[0]->class_name . '</strong>';
                                $class_id = $exam_config[0]->class_id;
                                $class_name = $exam_config[0]->class_name;
                            } else {
                                echo '<strong style=\"color:#0089ff;\"></strong>';
                            }
                            @endphp
                            @endisset
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 dc-exam-info-title">
                            Giáo Viên:
                        </div>
                        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6 dc-exam-info-content">
                            @isset($teacher)
                            @php

                            if ($teacher != null && count($teacher) > 0) {
                                $teacher_id = $teacher[0]->id;
                                $teacher_first_name = $teacher[0]->first_name;

                                $teacher_last_name = $teacher[0]->last_name;

                                echo "<strong style=\"color:#0089ff;\">" . $teacher[0]->last_name . ' ' . $teacher[0]->first_name . '</strong>';
                            } else {
                                echo '<strong style=\"color:#0089ff;\"></strong>';
                            }
                            @endphp
                            @endisset
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 dc-exam-info-title">
                            Học Sinh:
                        </div>
                        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6 dc-exam-info-content">
                            @isset($exam_config)
                            @php
                            if ($exam_config != null && count($exam_config) > 0) {
                                echo "<strong style=\"color:#0089ff;\">" . $exam_config[0]->last_name . ' ' . $exam_config[0]->first_name . '</strong>';
                            } else {
                                echo '<strong style=\"color:#0089ff;\"></strong>';
                            }
                            @endphp
                            @endisset
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 dc-exam-info-title">
                            Thời Gian:
                        </div>
                        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6 dc-exam-info-content">
                            @isset($exam_config)
                            @php
                            if ($exam_config != null && count($exam_config) > 0) {
                                echo "<strong style=\"color:#0089ff;\">" . $exam_config[0]->time_limit . ' phút</strong>';
                            } else {
                                echo '<strong style=\"color:#0089ff;\"> phút</strong>';
                            }
                            @endphp
                            @endisset
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="col-xs-10 col-xs-offset-1">
                    @isset($questions)
                    @foreach ($questions as $item)
                    @if ($item['question_type'] == 1)
                    <div class="container dc-question-container">
                        <ul class="nav dc-nav-tabs nav-tabs" role="tablist">
                            <li class="nav-item dc-nav-item waves-effect waves-light">
                                <a class="nav-link dc-nav-link active" data-toggle="tab" role="tab">Câu
                                {{ $item['question_idx'] }}. ( {{ $item['question_mark'] }} đ)</a>
                            </li>

                        </ul>
                        <div class="tab-content dc-tab-content">
                            <div class="tab-pane active" role="tabpanel">
                                <div class="form-group">
                                    <textarea class="form-control dc-question-tiny-mce-class"
                                    style="min-height: 20px;" rows="3"
                                    readonly="true">{{ $item['question_content'] }}</textarea>
                                </div>

                                @foreach ($item['answers'] as $ans)
                                @php
                                $ans_id = 'ans_' . $item['question_id'] . '_' . $ans['answer_id'];
                                $ans_name = 'ans_' . $item['question_id'];

                                @endphp

                                <div class="form-group answer_item" style="margin: 0rem; padding:0.3em;">
                                    <label class="radio-inline">
                                        <input type="radio" class="question_name" id="{{ $ans_id }}"
                                        name="{{ $ans_name }}" value="{{ $ans['answer_id'] }}">
                                        <strong style="font-weight:bold; color:#1827f8;">
                                        {{ $ans['answer_name'] }}.</strong>
                                        {{--  {{ $ans['answer_content'] }} --}}
                                    </label>
                                    <textarea class="form-control dc-question-tiny-mce-class" id="{{ 'rad_' . $item['question_id'] . '_' . $ans['answer_id'] . '_' . $ans['answer_name'] }}" name="{{ 'rad_' . $item['question_id'] . '_' . $ans['answer_id'] . '_' . $ans['answer_name'] }}" required>{{ $ans['answer_content'] }}</textarea>
                                </div>

                                @endforeach
                                @php
                                if (isset($ans_name) && $ans_name != null && $ans_name != '') {
                                    $all_element[] = ['ques_type' => $item['question_type'], 'ques_id' => $item['question_id'], 'ques_element' => $ans_name];
                                }
                                @endphp
                            </div>

                        </div>
                    </div>

                    @else
                    <div class="container dc-question-container">
                        <ul class="nav dc-nav-tabs nav-tabs" role="tablist">
                            <li class="nav-item dc-nav-item waves-effect waves-light">
                                <a class="nav-link dc-nav-link active" data-toggle="tab" role="tab">Câu
                                {{ $item['question_idx'] }}. ( {{ $item['question_mark'] }} đ)</a>
                            </li>
                        </ul>
                        <div class="tab-content dc-tab-content">
                            <div class="tab-pane active" role="tabpanel">
                                <div class="form-group">
                                    <textarea class="form-control dc-question-tiny-mce-class"
                                    style="min-height: 20px;"
                                    rows="3">{{ $item['question_content'] }}</textarea>
                                </div>

                                <div class="form-group answer_item">
                                    <label for="title" class="lb-answer-title">Trả lời</label>
                                    <textarea class="form-control dc-answer-tiny-mce-class"
                                    id="ques_{{ $item['question_id'] }}" style="min-height: 40px;"
                                    rows="3" required></textarea>
                                </div>

                                <div class="form-group">
                                 <input id="upload_file_{{ $item['question_id'] }}" type="file" name="filenames[]" class="answer_upload_file form-control" multiple>
                                 <div class="form-group upload_file_container" id = "upload_file_{{ $item['question_id'] }}_list" >

                                 </div>
                                 </div

                                 @php
                                 $all_element[] = ['ques_type' => $item['question_type'], 'ques_id' => $item['question_id'], 'ques_element' => 'ques_' . strval($item['question_id']), "upload_file" => "upload_file_" . $item['question_id'] ];
                                 @endphp

                             </div>
                         </div>
                     </div>

                     @endif
                     @endforeach
                     @endisset


                        <center style="margin-top:1.5em;">
                            <?php
                            if($time_start_exam["flag"] > 0){
                            ?>
                            <button id="dc-btn-finish" class="btn btn-info btn-block" style="width:50%;">Nộp Bài</button>
                            <?php
                        }
                    ?>
                        </center>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="float">
        <i class="timer-tick" id="timer-tick">0</i>
    </div>




    @php
// use App\PeerAssessment;
    use App\Util;
    Util::Compare_Datetime(null, null);
    @endphp

    <script>
        tinymce.init({
            selector: '.dc-question-tiny-mce-class',
            readonly: true,
            menubar: false,
            statusbar: false,
            toolbar: false,
            height: 100,
            plugins: "autoresize",
        });

        tinymce.init({
            selector: '.dc-answer-tiny-mce-class',
            readonly: false,
            height: 250,
           // plugins: "autoresize",
              menubar: true,
         plugins: [
            'searchreplace table image imagetools'
          ],
          toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | image',
          powerpaste_allow_local_images: true,
          powerpaste_word_import: 'prompt',
          powerpaste_html_import: 'prompt',
          content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });

        var timer_countdown = null;
        var time_start = null;
        var upload_file_id = [];
        var upload_file_data = [];
        var list_upload_id = [];

        // window.onbeforeunload = function() {
        //     return "Dude, are you sure you want to leave? Think of the kittens!";
        // }

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //startTimer(1,$("#timer-tick"));

            time_start = <?php echo json_encode($time_start_exam); ?>;


            $(".answer_upload_file").each(function() {
                console.log(this.id);
                upload_file_id[this.id] = null;
                list_upload_id[this.id] = null;
                upload_file_data.push(this.id);
                $("#" + this.id).val("");
            });


            console.log(time_start);

            console.log(upload_file_id);


            if(time_start["flag"] > 0){
                startTimer(time_start["flag"], document.querySelector('#timer-tick'), true);
            }else{
                startTimer(0.02, document.querySelector('#timer-tick'), true);
            }

            $("#dc-btn-delete-all").click(function() {
                $.ajax({
                    url: "{{ url('/bai-kiem-tra/xoa') }}",
                    data: {
                        aa: "aa"
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
                        console.log(response);
                       // alert("ok");
                   },
                   error: function(xhr, ajaxOptions, thrownError) {
                    $.alert({
                        title: "<strong class='alert-msg-error'>Error!</strong>",
                        content: "Không kết nối được server!",
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

            $("#dc-btn-finish").click(function() {
                var confirm = $.confirm({
                    title: 'Xác Nhận!',
                    content: 'Bạn muốn nộp bài?',
                    buttons: {
                        OK: {
                            text: 'Nộp Bài',
                            btnClass: 'btn-blue',
                            //keys: ['enter', 'shift'],
                            action: function() {
                                saveAnswerToDatabase();
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

            $('.answer_upload_file').on('change', function () {
                // var fileReader = new FileReader();
                // fileReader.onload = function () {
                //   var data = fileReader.result;  // data <-- in this var you have the file data in Base64 format
                // };
                // fileReader.readAsDataURL($('#upload_file').prop('files')[0]);
                //console.log($('#upload_file').prop('files')[0]);
                var up_id = this.id;
                console.log(up_id);

                var files = $('#' + up_id).prop("files");
                var data_files = null;
                $.each(files , function (index, value){
                  console.log(value);
                });

                saveUploadFile(files, up_id);
                console.log($("#" + up_id).prop("files"));
               // $("#" + up_id).val("");

            });


        }); // end-document-ready


        function saveUploadFile(files, id){
            console.log(upload_file_id);
            var data = [], data_name = [];
            var is_found = false;
            $.each(files , function (idx_1, val_1){
                is_found = false;
                $.each(upload_file_id[id] , function (idx_2, val_2){
                    if(val_2.name == val_1.name){
                        data.push(val_1);
                        data_name.push(val_1.name);
                        is_found =true;
                    }
                });
                if(is_found==false){
                    data.push(val_1);
                    data_name.push(val_1.name);
                }
            });

            $.each(upload_file_id[id] , function (idx_2, val_2){
                if(data_name.indexOf(val_2.name)){
                    data.push(val_2);
                }
            });

            upload_file_id[id] = data;





            console.log(upload_file_id);

            loadUploadFile(id);

            $(".remove-upload-file").on("click", function(e) {
                var id = $(this).data("id");
               // console.log($(this).data("id"));
                var name = [$(this).data("filename")];
                var data= [];
               // console.log($(this).data("filename"));
                $.each(upload_file_id[id] , function (idx_2, val_2){
                    if(name.indexOf(val_2.name)){
                        data.push(val_2);
                    }
                });

                upload_file_id[id] = data;
                loadUploadFile(id);

                $("#" + id).val("");
            });

        }

    function loadUploadFile(id){
        $("#" + id + "_list").html("");
            $.each(upload_file_id[id] , function (idx_2, val_2){
                $("#" + id + "_list").append('<span class="form-control upload_file_item"> <i class="fa fa-trash remove-upload-file" data-id = "'+id+'" data-filename = "'+val_2.name+'"  style="color:#ff0000;"></i> ' + val_2.name + '</span>');
        });
         $(".remove-upload-file").on("click", function(e) {
            var id = $(this).data("id");
           // console.log($(this).data("id"));
            var name = [$(this).data("filename")];
            var data= [];
           // console.log($(this).data("filename"));
            $.each(upload_file_id[id] , function (idx_2, val_2){
                if(name.indexOf(val_2.name)){
                    data.push(val_2);
                }
            });

            upload_file_id[id] = data;
            loadUploadFile(id);
        });
    }


        function saveAnswerToDatabase() {

            //var pathname = window.location.pathname; // Returns path only (/path/example.html)
            //var url      = window.location.href;     // Returns full URL (https://example.com/path/example.html)
            //var origin   = window.location.origin;   // Returns base URL (https://example.com)
            //var url = pathname;
            //var id = url.substring(url.lastIndexOf('/') + 1);
            var data_post = [];

            var check_id = getIdFromUrl();
            var exam_class_id = parseInt(<?php echo $exam_class_id; ?>);
            var exam_id = parseInt(<?php echo $exam_id; ?>);

            //console.log(check_id);
            //console.log(exam_id);
           // if (exam_id == check_id && check_id != NaN && exam_id != NaN) {
                console.log("OK");
            var data_key = <?php echo json_encode($all_element); ?>; //console.log(data_key);
            //console.log(exam_id);
            $.each(data_key, function(index, value) {

                if (value["ques_type"] == 0) {
                    data_post.push({
                        exam_class_id: exam_class_id,
                        exam_id: exam_id,
                        ques_id: value["ques_id"],
                        ques_type: value["ques_type"],
                        ques_answer: tinyMCE.get(value["ques_element"]).getContent(),
                        upload_file : value["upload_file"],
                        answer_file_id:null,
                    });
                } else {

                    data_post.push({
                        exam_class_id: exam_class_id,
                        exam_id: exam_id,
                        ques_id: value["ques_id"],
                        ques_type: value["ques_type"],
                        ques_answer: $("input[name='" + value["ques_element"] + "']:checked").val(),
                        upload_file : null,
                        answer_file_id:null,
                    });
                }

            });

          //  console.log(upload_file_data);

            list_upload_id = [];
            $.each(upload_file_data, function(key, value){
                AjaxFileUpload(upload_file_id[value], value);
            });

            var data_post_tmp = [];
             $.each(data_post, function(index, value) {
                if (value["ques_type"] == 0) {
                    data_post_tmp.push({
                        exam_class_id: value["exam_class_id"],
                        exam_id: value["exam_id"],
                        ques_id: value["ques_id"],
                        ques_type: value["ques_type"],
                        ques_answer: value["ques_answer"],
                        answer_file_id: list_upload_id[value["upload_file"]],
                    });
                } else {
                    data_post_tmp.push({
                        exam_class_id: value["exam_class_id"],
                        exam_id: value["exam_id"],
                        ques_id: value["ques_id"],
                        ques_type: value["ques_type"],
                        ques_answer: value["ques_answer"],
                        answer_file_id: null,
                    });
                }

            });

             console.log(data_post_tmp);
          // return false;

            $.ajax({
                url: "{{ url('/bai-kiem-tra/luu-bai-lam') }}",
                data: {
                    exam_class_id: exam_class_id,
                    exam_id: exam_id,
                    time_start_exam_id: time_start["id"],
                    data: data_post_tmp,
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

                    console.log(response);
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
                                $(".jconfirm-content").css("text-align",
                                    "center");
                            },
                            onDestroy: function() {
                                window.top.close();
                            }
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
                    $.alert({
                        title: "<strong class='alert-msg-error'>Error!</strong>",
                        content: "Không kết nối được server!",
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

            // } else {
            //     console.log("loi");
            // }
            //console.log(data_post);

        }



function AjaxFileUpload(files, id){
    console.log(files);
    if(files!=null){
    var formData = new FormData;
    for(var i = 0; i < files.length; i++){
      formData.append(files[i].name, files[i])
    }
    formData.append("id", id);

    console.log(formData.entries());

    $.ajax({
        url: "{{ url('/ho-tro/upload-file') }}",
        data: formData,
        type: 'POST',
        cache: false,
        async: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $("body").addClass("loading");
        },
        complete: function() {
            $("body").removeClass("loading");
        },
        success: function(response) {
            console.log(response);
            if(response!=null && response.length > 0){
                list_upload_id[id] = response;
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $.alert({
                title: "<strong class='alert-msg-error'>Error!</strong>",
                content: "Không kết nối được server!",
                type: "red",
                buttons: {
                    OK: function() {
                        // location.reload();
                    }
                },
                onDestroy: function() {

                }
            });
        },
         // Custom XMLHttpRequest
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload) {
                // For handling the progress of the upload
                myXhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        $('progress').attr({
                            value: e.loaded,
                            max: e.total,
                        });
                    }
                } , false);
            }
            return myXhr;
        },
    });

    // $.ajax({
    //     //Server script/controller to process the upload
    //     url: "{{ url('/ho-tro/upload-file') }}",
    //     type: 'POST',

    //     // Form data
    //     data: formData,

    //     // Tell jQuery not to process data or worry about content-type
    //     // You *must* include these options!
    //     cache: false,
    //     contentType: false,
    //     processData: false,
    //     // Error logging
    //     error: function(jqXHR, textStatus, errorThrown){
    //       console.log(JSON.stringify(jqXHR));
    //       console.log('AJAX Error: ' + textStatus + ": " + errorThrown);
    //     },
    //     // Custom XMLHttpRequest
    //     xhr: function() {
    //         var myXhr = $.ajaxSettings.xhr();
    //         if (myXhr.upload) {
    //             // For handling the progress of the upload
    //             myXhr.upload.addEventListener('progress', function(e) {
    //                 if (e.lengthComputable) {
    //                     $('progress').attr({
    //                         value: e.loaded,
    //                         max: e.total,
    //                     });
    //                 }
    //             } , false);
    //         }
    //         return myXhr;
    //     },
    //     success: function(data){
    //       console.log(data);
    //     }
    // });
    }
  }


        function getIdFromUrl() {
            var full_url = document.URL; // Get current url
            var url_array = full_url.split('/');
            var id = "";
            if (url_array.length > 2) {
                id = url_array[url_array.length - 2];
            }
            //console.log( id );
            return (parseInt(id));
        }

        function startTimer(duration, display, is_save) {
            //var timer = Math.ceil(duration * 60);
            var timer = Math.ceil(duration);

            timer_countdown = setInterval(function() {
                display.textContent = secondsTimeSpanToHMS(timer);
                if (--timer < 0) {
                    timer = "00:00:00";
                    clearInterval(timer_countdown);
                    if(is_save){
                        saveAnswerToDatabase();
                    }

                }
            }, 1000);
        }

        function secondsTimeSpanToHMS(s) {
          var h = Math.floor(s / 3600); //Get whole hours
          s -= h * 3600;
          var m = Math.floor(s / 60); //Get remaining minutes
          s -= m * 60;
          return h + ":" + (m < 10 ? '0' + m : m) + ":" + (s < 10 ? '0' + s : s); //zero padding on minutes and seconds
      }
  </script>

  <style>
  .float {
    position: fixed;
    width: 5rem;
    height: 3rem;
    top: 4.5rem;
    right: 2rem;
    // background-color: #0C9;
    // color: #FFF;
    background-color: #0006;
    // color: #20FF00;
    color: #FFFFFF;
    //border-radius: 4.5rem;
    text-align: center;
    box-shadow: 2px 2px 3px #999;
}

.timer-tick {
    // margin-top: 2rem;
    // font-size:2rem;
    font-size: 1.2rem;
    margin: auto;
    width: 4.8rem;
    height: 2.8rem;
    margin: 0.1rem;
    display: block;
    line-height: 2.8rem;
    font-family: sans-serif;
    font-weight: bold;
    font-style:normal;

}

.dc-question-container {
    padding: 0 !important;
    margin-top: 1rem !important;
}

.dc-nav-tabs {
    border: none !important;
}

.dc-nav-item {
    background: #f4ffff !important;
}

.dc-nav-link {
    border: 1px solid transparent !important;
    border-top-left-radius: .25rem !important;
    border-top-right-radius: .25rem !important;
    background: #f4ffff !important;
    border-color: #1827f8 #1827f8 #f4ffff #1827f8 !important;
    color: #fa0000 !important;
    font-weight: 500;
}

.dc-tab-content {
    background: #f4ffff;
    border: 1px solid #1827f8 !important;
    border-radius: .0rem .25rem .25rem .25rem !important;
    padding: 1rem;
    -webkit-box-shadow: 1px 4px 15px -1px rgba(0, 0, 0, 0.82);
    box-shadow: 1px 4px 15px -1px rgba(0, 0, 0, 0.82);
}

.dc-exam-info-title {
    text-align: left;
}

.dc-exam-info-content {
    text-align: left;
    font-weight: 400;
    color: #0089ff;
}

.lb-question-idx {
    color: #fa0000;
    font-weight: bold;
}

.lb-answer-title {
    color: #0089ff;
    font-weight: bold;
}

.dc-answer-container {
    border: 2px solid #b6d4fa;
    border-radius: 3px;
    background: #f4ffff;
}

.dc-answer-container:nth-child(n+1) {
    margin-top: 1em;
}


.upload_file_container{
    margin-top: 1em;
    padding: .5em;
}

.upload_file_item{
    display: block;
    height: auto;
}

.remove-upload-file{
    cursor: pointer;
}

.remove-upload-file :hover{
    border: 1px solid #dfdfdf;
    border-radius: 2px;
}


</style>

@endsection
