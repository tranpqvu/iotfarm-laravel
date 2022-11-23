@extends('frontend.frontend_master')
@section('content')
    @php
    $all_element = [];
    $exam_id = -1;
    $test_count = 0;
    $save_data = [];
    $answer_id = null;
    $arr_answer_ques_id = [];
    @endphp
    <div class="row justify-content-center">
        @isset($exam_config)
            @foreach ($all_data as $item)
                <?php $test_count += 1; $answer_id=$item["answer_id"]; ?>
                <div class="col-lg-12 col-md-12 col-xs-12">
                    {{-- <h5>BÀI KIỂM TRA SỐ {{ $test_count }} - {{  $item["student"] }}</h5> --}}
                    <div class="card">
                        <div class="card-header">
                            <h4 style="color:#0089ff; text-align:center;">
                                @php
                                    echo $item['exam_title'] != '' ? $item['exam_title'] : 'BÀI KIỂM TRA';
                                @endphp
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
                                        <strong style=\"color:#0089ff;\">{{ $item['subject_name'] }} </strong>
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
                                        Thời Gian:
                                    </div>
                                    <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6 dc-exam-info-content">
                                        <strong style=\"color:#0089ff;\">{{ $item['exam_class_time_limit'] }} (phút) </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-xs-10 col-xs-offset-1">
                                @isset($item['answer_question'])
                                    <?php $arr_answer_ques_id = []; ?>
                                    @foreach ($item['answer_question'] as $ques)
                                        @if ($ques['question_type'] == 1)
                                            <div class="container dc-question-container">
                                                <ul class="nav dc-nav-tabs nav-tabs" role="tablist">
                                                    <li class="nav-item dc-nav-item waves-effect waves-light">
                                                        <a class="nav-link dc-nav-link active" data-toggle="tab" role="tab">Câu
                                                            {{ $ques['question_idx'] }}. ( {{ $ques['question_mark'] }} đ)</a>
                                                        <div class="nav-link dc-nav-link active"
                                                            style="padding: 0em 0.5em; border-radius: 0 !important; border-top:0 !important; text-align: center !important;">
                                                            @php
                                                                $answer_ques_id = 'answer_' . $test_count . "_" . $ques['answer_question_id'];
                                                                $arr_answer_ques_id[] = [
                                                                    'answer_id' => $item['answer_id'],
                                                                    'answer_question_id' => $ques['answer_question_id'],
                                                                    'answer_element' => $answer_ques_id,
                                                                    'question_type' => $ques['question_type'],
                                                                    'question_mark' => $ques['question_mark'],
                                                                    'exam_mark' => $item['exam_mark'],
                                                                    'real_mark' => null,
                                                                ];
                                                            @endphp
                                                          
                                                        </div>
                                                    </li>
                                                </ul>
                                                <div class="tab-content dc-tab-content">
                                                    <div class="tab-pane active" role="tabpanel">
                                                        <div class="form-group">
                                                            <textarea class="form-control dc-question-tiny-mce-class"
                                                                style="min-height: 50px;" rows="5"
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
                                                                    {{-- {{ $ans['content'] }} --}}
                                                                </label>
                                                                <textarea class="form-control dc-question-tiny-mce-class" id="{{ 'rad_' . $ques['answer_question_id'] . '_' . $ans['answer_question_detail_id'] }}" name="{{ 'rad_' . $ques['answer_question_id'] . '_' . $ans['answer_question_detail_id'] }}" required>{{ $ans['content'] }}</textarea>
                                                            </div>
                                                        @endforeach
                                                        @php
                                                            // if (isset($ans_name) && $ans_name != null && $ans_name != '') {
                                                            //     $all_element[] = ['ques_type' => $item['question_type'], 'ques_id' => $item['question_id'], 'ques_element' => $ans_name];
                                                            // }
                                                        @endphp
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
                                                                $answer_ques_id = 'answer_' . $test_count . "_" . $ques['answer_question_id'];
                                                                $arr_answer_ques_id[] = [
                                                                    'answer_id' => $item['answer_id'],
                                                                    'answer_question_id' => $ques['answer_question_id'],
                                                                    'answer_element' => $answer_ques_id,
                                                                    'question_type' => $ques['question_type'],
                                                                    'question_mark' => $ques['question_mark'],
                                                                    'exam_mark' => $item['exam_mark'],
                                                                    'real_mark' => null,
                                                                ];
                                                            @endphp
                                                           
                                                        </div>
                                                    </li>
                                                </ul>
                                                <div class="tab-content dc-tab-content">
                                                    <div class="tab-pane active" role="tabpanel">
                                                        <div class="form-group">
                                                            <textarea class="form-control dc-question-tiny-mce-class"
                                                                style="min-height: 50px;"
                                                                rows="5">{{ $ques['question_content'] }}</textarea>
                                                        </div>
                                                        <div class="form-group answer_item">
                                                            <div class="row">
                                                                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6" style="text-align:left;">
                                                                    <label for="title" class="lb-answer-title">Trả lời</label>
                                                                </div>
                                                                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6" style="text-align:right;">
                                                                    <span class="btn btn-outline-primary btn-sm" onclick="getAllEvaluation({{ $item['answer_id'] }},{{ $ques['answer_question_id'] }})">Xem Nhận Xét</span>
                                                                </div>
                                                            </div>

                                                            <textarea class="form-control dc-question-tiny-mce-class"
                                                                id="ques_{{ $ques['answer_question_id'] }}"
                                                                style="min-height: 100px;" rows="10"
                                                                required>{{ $ques['answer_content'] }}</textarea>
                                                        </div>
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
                                        'answer_id' => $item['answer_id'],
                                        "data" => $arr_answer_ques_id
                                    );

                                    $arr_answer_ques_id = [];
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endisset
        <div class="container justify-content-center" style="padding: 1em 0em;">
            <center>
                <button id="dc-btn-finish" class="btn btn-info btn-block" style="width:50%;">Thoát</button>
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
            plugins: 'autoresize'
        });

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var all_data = <?php echo json_encode($all_data); ?>;        
            var arr_answer_info = <?php echo json_encode($arr_answer_ques_id); ?>;        
            console.log("test:");
            console.log(all_data);
            console.log(arr_answer_info);

            $("#dc-btn-finish").click(function() {
                var confirm = $.confirm({
                    title: 'Xác Nhận!',
                    content: 'Bạn muốn thoát?',
                    buttons: {
                        // OK: {
                        //     text: 'Nộp Bài',
                        //     btnClass: 'btn-blue',
                        //     //keys: ['enter', 'shift'],
                        //     action: function() {
                        //         saveMarkToDatabase();
                        //     }
                        // },
                        Cancel: {
                            text: 'Thoát',
                            btnClass: 'btn-orange',
                            //keys: ['enter', 'shift'],
                            action: function() {
                                //$.alert('Hủy');
                                confirm.close();
                                window.top.close();
                            }
                        }
                    }
                });
            });
        }); // end-document-ready
      
        function scrollAndFocusToElement(id){
            element_id = "#"+id;
            $('html, body').animate({
                scrollTop: $(element_id).offset().top - 10
            });
            if (element_id) $(element_id).focus();
        }

        function getAllEvaluation(id, answer_question_id){

                question_data_edit = undefined;
                var confirm_dialog = $.confirm({
                    title: "Nhận xét",
                    //closeIcon: true,

                   content: "URL:/bai-kiem-tra/xem-danh-gia?id=" + id + "&answer_question_id=" + answer_question_id,
                   // content: "URL:http://localhost/admin/ql-bai-kiem-tra/cau-hoi",
                    type: "blue",
                    columnClass: "col-lg-12 col-lg-offset-0 col-md-12 col-md-offset-0 col-sm-12 col-sm-offset-0 col-xs-12",
                    draggable: true,
                    buttons: {
                        // Save: {
                        //     text: "&nbsp;&nbsp;Lưu&nbsp;&nbsp;",
                        //     btnClass: "btn-blue",
                        //     action: function() {
                        //          alert("lưu");

                        //     }
                        // },
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
