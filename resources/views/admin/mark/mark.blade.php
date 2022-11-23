@extends('admin.admin_master')
@section('content')
    @php
    $exam_id = -1;
    $save_data = [];
    $answer_id = null;
    $arr_answer_ques_id = [];
    @endphp
    <div class="row justify-content-center">
        @isset($answer)
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <h5>BÀI LÀM</h5>
                    <div class="card">
                        <div class="card-header">
                            <h4 style="color:#0089ff; text-align:center;">
                                @php
                                    echo $answer[0]->exam_title != '' ? $answer[0]->exam_title : 'BÀI KIỂM TRA';
                                @endphp
                            </h4>
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 dc-exam-info-title">
                                        Niên Khóa:
                                    </div>
                                    <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6 dc-exam-info-content">

                                            @php
                                                echo "<strong style=\"color:#0089ff;\">" . $answer[0]->school_year_open . '-' . $answer[0]->school_year_close . '</strong>';
                                            @endphp
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 dc-exam-info-title">
                                        Môn Học:
                                    </div>
                                    <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6 dc-exam-info-content">
                                        <strong style=\"color:#0089ff;\">{{ $answer[0]->subject_name }} </strong>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 dc-exam-info-title">
                                        Lớp:
                                    </div>
                                    <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6 dc-exam-info-content">

                                            @php

                                                    echo "<strong style=\"color:#0089ff;\">" . $answer[0]->class_name . '</strong>';

                                            @endphp
                                    </div>
                                </div>
                                {{-- <div class="row">
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 dc-exam-info-title">
                                        Giáo Viên:
                                    </div>
                                    <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6 dc-exam-info-content">
                                        @isset($teacher)
                                            @php
                                                if ($teacher != null && count($teacher) > 0) {
                                                    echo "<strong style=\"color:#0089ff;\">" . $teacher[0]->last_name . ' ' . $teacher[0]->first_name . '</strong>';
                                                } else {
                                                    echo '<strong style=\"color:#0089ff;\"></strong>';
                                                }
                                            @endphp
                                        @endisset
                                    </div>
                                </div> --}}

                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 dc-exam-info-title">
                                        Học Sinh:
                                    </div>
                                    <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6 dc-exam-info-content">
                                        @isset($student_info)
                                            @php
                                                if ($student_info != null ) {
                                                    echo "<strong style=\"color:#0089ff;\">" . $student_info->last_name . ' ' . $student_info->first_name . '</strong>';
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
                                        <strong style=\"color:#0089ff;\">{{ $answer[0]->exam_class_time_limit }} (phút) </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-xs-10 col-xs-offset-1">
                                @isset($answer_details)
                                    <?php $arr_answer_ques_id = []; ?>
                                    @foreach ($answer_details as $ques)
                                        @if ($ques['question_type'] == 1)
                                            <div class="container dc-question-container">
                                                <ul class="nav dc-nav-tabs nav-tabs" role="tablist">
                                                    <li class="nav-item dc-nav-item waves-effect waves-light">
                                                        <a class="nav-link dc-nav-link active" data-toggle="tab" role="tab">Câu
                                                            {{ $ques['question_idx'] }}. ( {{ $ques['question_mark'] }} đ)</a>
                                                        <div class="nav-link dc-nav-link active"
                                                            style="padding: 0em 0.5em; border-radius: 0 !important; border-top:0 !important; text-align: center !important;">
                                                            @php
                                                                $answer_ques_id = 'answer_' . $ques['answer_question_id'];
                                                                $eval_ques_id = 'eval_' . $ques['answer_question_id'];
                                                                $arr_answer_ques_id[] = [
                                                                    'answer_id' => $answer[0]->id,
                                                                    'answer_question_id' => $ques['answer_question_id'],
                                                                    'answer_element' => $answer_ques_id,
                                                                    'eval_element' => $eval_ques_id,
                                                                    'question_type' => $ques['question_type'],
                                                                    'question_mark' => $ques['question_mark'],
                                                                    'exam_mark' => $answer[0]->mark,
                                                                    'real_mark' => null,
                                                                    'evaluation' => null,
                                                                ];
                                                            @endphp
                                                            {{-- <input class="assess_mark_input" type="text" class="form-control" name="{{ $answer_ques_id }}" id="{{ $answer_ques_id }}"> --}}
                                                            <label class="radio-inline" style="color:#0018FF !important;">
                                                                <input class="" type="checkbox" class="form-control"
                                                                    name="{{ $answer_ques_id }}" id="{{ $answer_ques_id }}">
                                                                Đúng
                                                            </label>
                                                        </div>
                                                    </li>
                                                </ul>
                                                <div class="tab-content dc-tab-content">
                                                    <div class="tab-pane active" role="tabpanel">
                                                        <div class="form-group">
                                                            <textarea class="form-control dc-question-tiny-mce-class"
                                                                style="min-height: 20px;" rows="3"
                                                                readonly="true">{{ $ques['question_content'] }}</textarea>
                                                        </div>
                                                        @foreach ($ques['question_details'] as $ans)
                                                            @php
                                                                $ans_id = 'ans_' . $ques['answer_question_id'] . '_' . $ans['answer_question_detail_id'];
                                                                $ans_name = 'ans_' . $ques['answer_question_id'];
                                                            @endphp
                                                            <div class="form-group answer_item"
                                                                style="margin: 0rem; padding:0.3em;">
                                                                <label class="radio-inline">
                                                                    <input <?php echo $ans['real_answer']==1
                                                                        ? "checked=\" checked\"" : "disabled=\" disabled\""; ?> type="radio" class="question_name"
                                                                        id="{{ $ans_id }}" name="{{ $ans_name }}"
                                                                        value="{{ $ans['answer_question_detail_id'] }}">
                                                                    <strong style="font-weight:bold; color:#1827f8;">
                                                                        {{ $ans['name'] }}.</strong>
                                                                  {{--   {{ $ans['content'] }} --}}
                                                                </label>
                                                                 <textarea class="form-control dc-question-tiny-mce-class" id="{{ 'rad_' . $ques['answer_question_id'] . '_' . $ans['answer_question_detail_id'] }}" name="{{ 'rad_' . $ques['answer_question_id'] . '_' . $ans['answer_question_detail_id'] }}" required>{{ $ans['content'] }}</textarea>
                                                            </div>
                                                        @endforeach
                                                        <div class="form-group answer_item">
                                                            {{-- <label for="title" class="lb-answer-title">Nhận xét</label> --}}
                                                            <div class="row">
                                                                <div class="col-6" style="text-align: left;">
                                                                    <label for="title" class="lb-answer-title">Nhận xét</label>
                                                                </div>
                                                                <div class="col-6" style="text-align: right;">
                                                                    {{-- <button type="button" data-id="{{ $answer[0]->id }}" data-answer_question_id="{{ $ques['answer_question_id'] }}" data-number={{ $ques['question_idx'] }} class="btn btn-sm bootgrid-btn-outline btn-outline-primary command-reivew-evaluation">
                                                                        <span class="color:#028e11;">Xem Đánh Giá</span>
                                                                    </button> --}}
                                                                    <span onclick="getAllEvaluation({{ $answer[0]->id }}, {{ $ques['answer_question_id'] }})"
                                                                        class="btn btn-sm bootgrid-btn-outline btn-outline-primary command-reivew-evaluation">
                                                                       Xem Đánh Giá</span>
                                                                </div>
                                                            </div>  
                                                            <textarea class="form-control dc-answer-tiny-mce-class"
                                                                id="{{ $eval_ques_id }}"
                                                                style="min-height: 40px;" rows="3"
                                                                ></textarea>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="container dc-question-container">
                                                <ul class="nav dc-nav-tabs nav-tabs" role="tablist">
                                                    <li class="nav-item dc-nav-item waves-effect waves-light">
                                                        <a class="nav-link dc-nav-link active" data-toggle="tab" role="tab">Câu
                                                            {{ $ques['question_idx'] }}. ( {{ $ques['question_mark'] }} đ)</a>
                                                        <div class="nav-link dc-nav-link active"
                                                            style="padding: 0em 0.5em; border-radius: 0 !important; border-top:0 !important; text-align: center !important;">
                                                            @php
                                                                $answer_ques_id = 'answer_' . $ques['answer_question_id'];
                                                                $eval_ques_id = 'eval_' . $ques['answer_question_id'];
                                                                $arr_answer_ques_id[] = [
                                                                    'answer_id' => $answer[0]->id,
                                                                    'answer_question_id' => $ques['answer_question_id'],
                                                                    'answer_element' => $answer_ques_id,
                                                                    'eval_element' => $eval_ques_id,
                                                                    'question_type' => $ques['question_type'],
                                                                    'question_mark' => $ques['question_mark'],
                                                                    'exam_mark' => $answer[0]->mark,
                                                                    'real_mark' => null,
                                                                    'evaluation' => null,
                                                                ];
                                                            @endphp
                                                            <input class="assess_mark_input" placeholder="Điểm" type="text"
                                                                class="form-control" name="{{ $answer_ques_id }}"
                                                                id="{{ $answer_ques_id }}">
                                                        </div>
                                                    </li>
                                                </ul>
                                                <div class="tab-content dc-tab-content">
                                                    <div class="tab-pane active" role="tabpanel">
                                                        <div class="form-group">
                                                            <textarea class="form-control dc-question-tiny-mce-class"
                                                                style="min-height: 20px;"
                                                                rows="3">{{ $ques['question_content'] }}</textarea>
                                                        </div>
                                                        <div class="form-group answer_item">
                                                            <label for="title" class="lb-answer-title">Trả lời</label>
                                                            <textarea class="form-control dc-question-tiny-mce-class"
                                                                id="ques_{{ $ques['answer_question_id'] }}"
                                                                style="min-height: 40px;" rows="3"
                                                                required>{{ $ques['answer_content'] }}</textarea>
                                                        </div>
                                                        <div class="form-group answer_item">
                                                            <div class="row">
                                                                <div class="col-6" style="text-align: left;">
                                                                    <label for="title" class="lb-answer-title">Nhận xét</label>
                                                                </div>
                                                                <div class="col-6" style="text-align: right;">
                                                                    {{-- <button type="button" data-id="{{ $answer[0]->id }}" data-answer-question-id="{{ $ques['answer_question_id'] }}" data-number={{ $ques['question_idx'] }}
                                                                        class="btn btn-sm bootgrid-btn-outline btn-outline-primary command-reivew-evaluation">
                                                                        <span class="color:#028e11;">Xem Đánh Giá</span>
                                                                    </button> --}}
                                                                    <span onclick="getAllEvaluation({{ $answer[0]->id }}, {{ $ques['answer_question_id'] }})"
                                                                        class="btn btn-sm bootgrid-btn-outline btn-outline-primary command-reivew-evaluation">
                                                                       Xem Đánh Giá</span>
                                                                </div>
                                                            </div>                                                           
                                                            <textarea class="form-control dc-answer-tiny-mce-class"
                                                                id="{{ $eval_ques_id }}"
                                                                style="min-height: 40px;" rows="3"
                                                                ></textarea>
                                                        </div>
                                                        {{-- <div class="form-group upload_file_container" >
                                                            <span class="form-control upload_file_item"> <a href="download/2021-08-11_213258_42151715_Trong thời đại công nghiệp 4.docx"><i class="fa fa-download remove-upload-file" style="color:#ff0000;"></i></a> &nbsp; <a href="xem-file/2021-08-11_213258_42151715_Trong thời đại công nghiệp 4.docx"><i class="fa fa-eye remove-upload-file" style="color:#1b8f02;"></i> &nbsp; 2021-08-11_213258_42151715_Trong thời đại công nghiệp 4.docx</a></span>
                                                        </div> --}}
                                                        <div class="form-group upload_file_container" >
                                                             <label for="title" class="lb-answer-title">File đính kèm</label>
                                                            @foreach ($ques['answer_files'] as $ff)
                                                                  <span class="form-control upload_file_item"> <a href="download/{{ $ff->file_name }}" target="_blank"> {{ $ff->file_name }}</a></span>
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endisset
                                <?php 
                                    $save_data[] = array(
                                        'answer_id' =>  $answer[0]->id,
                                        "data" => $arr_answer_ques_id
                                    );

                                    $arr_answer_ques_id = [];
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

        @endisset
        <div class="container justify-content-center" style="padding: 1em 0em;">
            <center>
                <button id="dc-btn-finish" class="btn btn-info btn-block" style="width:50%;">Nộp Bài</button>
            </center>
        </div>
    </div>
    <script>
        tinymce.init({
            selector: '.dc-question-tiny-mce-class',
            readonly: 1,
            menubar: false,
            statusbar: false,
            toolbar: false,
            height: 150,
            plugins: "autoresize",
        });
        tinymce.init({
            selector: '.dc-answer-tiny-mce-class',
            readonly: 0,
            height: 350,
            plugins: "autoresize",
        });

        var APP_URL = {!! json_encode(url('/')) !!}

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var answer = <?php echo json_encode($answer); ?>;
            var all_data = <?php echo json_encode($answer_details); ?>;
            var arr_answer_info = <?php echo json_encode($save_data); ?>;
            console.log("test:");
            console.log(answer);
            console.log(all_data);
            console.log(arr_answer_info);
    
           

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
                                saveMarkToDatabase();
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
        }); // end-document-ready

        
        function reviewEvaluation(id, answer_question_id){
            var link = APP_URL + '/admin/ql-diem-kiem-tra/' + id +
                        '/xem-danh-gia/' + answer_question_id;
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
             
        }

         function getAllEvaluation(id, answer_question_id){

                question_data_edit = undefined;
                var confirm_dialog = $.confirm({
                    title: "Nhận xét",
                    //closeIcon: true,

                    content: "URL:/admin/get-content-for-dialog/xem-danh-gia?id=" + id + "&answer_question_id=" + answer_question_id,
                    type: "blue",
                    columnClass: "col-lg-12 col-lg-offset-0 col-md-12 col-md-offset-0 col-sm-12 col-sm-offset-0 col-xs-12",
                    draggable: true,
                    buttons: {
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
                        $(".jconfirm-buttons").css({
                            "width": "100%",
                            "text-align": "center"
                        })
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

        }

        function saveMarkToDatabase() {
            //var pathname = window.location.pathname; // Returns path only (/path/example.html)
            //var url      = window.location.href;     // Returns full URL (https://example.com/path/example.html)
            //var origin   = window.location.origin;   // Returns base URL (https://example.com)
            //var url = pathname;
            //var id = url.substring(url.lastIndexOf('/') + 1);
            var data_post = [];
            var error = 0;
            //var check_id = getIdFromUrl();
            var exam_class_id = parseInt( <?php echo $answer[0]->exam_class_id; ?> );
            var exam_id = parseInt( <?php echo $exam_id; ?> );
            var arr_answer_info = <?php echo json_encode($save_data); ?>; 

            console.log(arr_answer_info);

            arr_element_eror = [];

            if (arr_answer_info.length > 0) {
                var i=0, j=0;
                for (i = 0; i < arr_answer_info.length; i++) {
                    var itm = arr_answer_info[i];
                    //console.log(item["answer_id"]);
                    if(itm["data"].length>0){
                        for(j=0;j<itm["data"].length;j++){
                            item = itm["data"][j]
                            item["evaluation"] = tinyMCE.get(item["eval_element"]).getContent();
                            if(item["question_type"] == 1){
                                console.log($("#" + item["answer_element"]).is(":checked"));
                                if($("#" + item["answer_element"]).is(":checked")){
                                   // arr_answer_info[i]["data"][j]["real_mark"] = item["question_mark"];
                                   item["real_mark"] = item["question_mark"];
                                }else{
                                   // arr_answer_info[i]["data"][j]["real_mark"] = 0;
                                   item["real_mark"] = 0;
                                }
                                itm["data"][j] = item;
                            }else{
                                var mark = parseFloat($("#" + item["answer_element"]).val());
                                element_id =  item["answer_element"];
                                console.log(mark);                        
                                if(mark != NaN){
                                    if(mark >=0 && mark<= item["question_mark"]){
                                        item["real_mark"] = mark;
                                        itm["data"][j] = item;
                                    }else{
                                        error = error+1;
                                        arr_element_eror[error-1] = element_id;
                                        // $.alert({
                                        //     title: "<strong class='alert-msg-eror'>Cảnh Báo!</strong>",
                                        //     content: "Điểm số không hợp lệ 11111 " + item["answer_element"],
                                        //     type: "yellow",
                                        //     autoClose: "OK|3000",
                                        //     buttons: {
                                        //         OK: function() {
                                                
                                        //         }
                                        //     },
                                        //     onClose:function(){
                                        //         scrollAndFocusToElement(element_id);     
                                        //     },
                                        //     onOpenBefore: function() {
                                        //         //$(".jconfirm-content").css("text-align", "center");
                                        //     },
                                        // });
                                    }
                                }else{
                                    error = error+1;
                                    arr_element_eror[error-1] = element_id;
                                    // $.alert({
                                    //     title: "<strong class='alert-msg-eror'>Cảnh Báo!</strong>",
                                    //     content: "Điểm số không hợp lệ 22222222",
                                    //     type: "yellow",
                                    //     autoClose: "OK|3000",
                                    //     buttons: {
                                    //         OK: function() {

                                    //         }
                                    //     },
                                    //     onClose:function(){
                                    //         scrollAndFocusToElement(element_id);  
                                    //     },
                                    //     onOpenBefore: function() {
                                    //         //$(".jconfirm-content").css("text-align", "center");
                                    //     },
                                    // });
                                }// end-if
                            } // end-if  
                        } // for
                        arr_answer_info[i] = itm;
                    }

                                    
                } // end-for
            } else{    
                error = error+1;
            } // end-if
            console.log(arr_answer_info);
        
            if(error == 0 && arr_element_eror.length == 0){
                console.log("OK");
                $.ajax({
                    url: "{{ url('/admin/ql-diem-kiem-tra/luu-diem-gv') }}",
                    data: {
                     
                        data: arr_answer_info
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
                        // switch (response.result) {
                        //     case 1:
                        //         $.alert({
                        //             title: "<strong class='alert-msg-success'>Success!</strong>",
                        //             content: response.msg,
                        //             type: "green",
                        //             autoClose: "OK|3000",
                        //             buttons: {
                        //                 OK: function() {
                        //                 }
                        //             },
                        //             onOpenBefore: function() {
                        //                 $(".jconfirm-content").css("text-align",
                        //                     "center");
                        //             },
                        //         });
                        //         break;
                        //     default:
                        //         $.alert({
                        //             title: "<strong class='alert-msg-eror'>Error!</strong>",
                        //             content: response.msg,
                        //             type: "red",
                        //             autoClose: "OK|3000",
                        //             buttons: {
                        //                 OK: function() {
                        //                 }
                        //             },
                        //             onOpenBefore: function() {
                        //                 //$(".jconfirm-content").css("text-align", "center");
                        //             },
                        //         });
                        //         break;
                        // }
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
                            onDestroy: function() {}
                        });
                    }
                });

            }else{
                console.log("error");
            //     console.log(arr_element_eror);
                $.alert({
                    title: "<strong class='alert-msg-eror'>Error!</strong>",
                    content: "Điểm số không hợp lệ. Xin vui lòng kiểm tra lại!",
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

            }
            

           // console.log(data_post);
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

        function scrollAndFocusToElement(id){
            element_id = "#"+id;
            $('html, body').animate({
                scrollTop: $(element_id).offset().top - 10
            });
            if (element_id) $(element_id).focus();
        }

    </script>
    <style>
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

        .assess_mark_input {
            min-width: 30px !important;
            width: 5em !important;
            text-align: center;
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

    </style>
@endsection
