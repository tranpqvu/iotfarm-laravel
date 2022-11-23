<div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="card">
            <!-- <div class="card-header">
                <h4>Thêm Câu Hỏi</h4>
            </div> -->
            <div class="card-body">
                <div class="col-xs-4 col-xs-offset-4">
                    <div class="form-group">
                        <label for="question_type" style="color:#0093ff; font-weight:bold;">Loại câu hỏi</label>
                        <select class="form-control" id="question_type" name="question_type" style="color:#0093ff;">
                            <option value="0" @isset($data) @if ($data['question_type'] == '0') selected @endif @endisset>Câu tự luận</option>
                            <option value="1" @isset($data) @if ($data['question_type'] == '1') selected @endif @endisset>Câu hỏi trắc nghiệm</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="question_mark" style="color:#0093ff; font-weight:bold;">Điểm</label>
                        <input type="text" class=" txt-float-value form-control" id="question_mark" name="question_mark"
                            placeholder="Điểm" required />
                    </div>

                    <div class="form-group">
                        <label for="question_content" style="color:#0093ff; font-weight:bold;">Nội Dung</label>
                        <textarea class="form-control dc-ques-tiny-mce-class" id="question_content" name="question_content"
                            placeholder="Nội Dung" required></textarea>
                    </div>

                    <div class="container" id="answer_container">
                        <div class="form-group answer_item">
                            <input type="radio" class="question_name" id="question_A" name="question_answer" value="A"
                                checked>&nbsp
                            <label for="question_A">A</label>
                            <textarea class="form-control dc-ques-tiny-mce-class" id="content_A" name="content_A" required></textarea>
                        </div>
                        <div class="form-group answer_item">
                            <input type="radio" id="question_B" name="question_answer" value="B">&nbsp
                            <label for="question_B">B</label>
                            <textarea class="form-control dc-ques-tiny-mce-class" id="content_B" name="content_B" required></textarea>
                        </div>
                        <div class="form-group" id="add_answer_button_container">
                            <span class="btn btn-warning">Thêm đáp án</span>
                        </div>
                    </div>
                    {{-- <center style="margin-top:1em;"><button type="submit" class="btn btn-info btn-block"
                            style="width:50%;">Thêm</button></center> --}}

                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .btn-delete-question {
        margin-top: 0.1em;
    }

</style>
<script src="{{ asset('assets/js/tinymce/js/tinymce/tinymce.min.js')}}"></script>
<script>

    tinymce.init({
        selector: '.dc-ques-tiny-mce-class',
        readonly: false,
        height: 250,

          // plugins: [
          //   'advlist autolink lists link image charmap print preview anchor',
          //   'searchreplace visualblocks fullscreen',
          //   'insertdatetime media table imagetools hr code'
          // ],
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

    $(document).ready(function() {

        tinymce.init({
            selector: '.dc-ques-tiny-mce-class',
            readonly: false,
            height: 250,
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

        $("#answer_container").hide();
        $("#add_answer_button_container").hide();

        $("#question_type").change(function() {
            console.log(this.value)
            switch (this.value) {
                case "0":
                    $("#answer_container").hide();
                    $("#add_answer_button_container").hide();
                    break;

                case "1":
                    $("#answer_container").show();
                    $("#add_answer_button_container").show();
                    break;
            }
        });

        $("#add_answer_button_container").click(function() {
            var items = $("#answer_container").find(".answer_item");
            console.log(items.length);
            console.log(colName(items.length));
            $(".btn-delete-question").remove();
            $(genAnswer(colName(items.length))).insertBefore("#add_answer_button_container");

            $(".btn-delete-question").click(function() {
                //$(this).closest(".answer_item").fadeOut(300);
                $(this).closest(".answer_item").remove();
                var items = $("#answer_container").find(".answer_item");
                if (items.length > 2) {
                    var content_id = "#content_" + colName(items.length - 1);
                    console.log(content_id);

                    // $("<span class=\"btn btn-danger fa fa-remove btn-delete-question\"></span>")
                    //     .insertAfter(content_id);

                    $(content_id).parent().append("<span class=\"btn btn-danger fa fa-remove btn-delete-question\"></span>");

                    initDeleteButtonAction();
                }

            })
             console.log(tinymce.EditorManager.editors);
             tinymce.init({
                selector: '.dc-ques-tiny-mce-class',
                readonly: false,
                height: 250,
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

                var editor_id = 'content_' + colName(items.length);
                if(tinyMCE.get(editor_id)){
                     // Remove instance by id
                     tinymce.remove('#' + editor_id);
                   }

                //tinymce.EditorManager.execCommand('mceAddEditor',true, editor_id);
              //  tinymce.EditorManager.execCommand('mceAddControl',true, editor_id);
                 tinymce.init({
                    selector: '#' + editor_id,
                    readonly: false,
                    height: 250,
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

        });

        $(".btn-delete-question").click(function() {
            //$(this).closest(".answer_item").fadeOut(300);
            $(this).closest(".answer_item").remove();

            var items = $("#answer_container").find(".answer_item");
            if (items.length > 2) {
                var content_id = "#content_" + colName(items.length - 1);
                console.log(content_id);

                // $("<span class=\"btn btn-danger fa fa-remove btn-delete-question\"></span>")
                //     .insertAfter(
                //         content_id);
                $(content_id).parent().append("<span class=\"btn btn-danger fa fa-remove btn-delete-question\"></span>");

                initDeleteButtonAction();
            }

        })


        if (typeof question_data_edit != 'undefined' && question_data_edit.length > 0) {
            console.log(question_data_edit[0]);
            $("#question_type").val(question_data_edit[0]["question_type"]).trigger('change');
            $("#question_content").val(question_data_edit[0]["question_content"]);
            $("#question_mark").val(question_data_edit[0]["question_mark"]);
            switch (parseInt(question_data_edit[0]["question_type"])) {
                case 0:

                    break;

                case 1:
                    if (question_data_edit[0]["answers"].length > 0) {
                        var answers = question_data_edit[0]["answers"];
                        for (i = 0; i < answers.length; i++) {
                            txt_id = "#content_" + answers[i]["answer_name"]; // colName(i);
                            if ($(txt_id).length <= 0) {
                                LoadDataForEdit(answers[i]["answer_name"]);
                                //$("#add_answer_button_container").trigger("click");
                            }
                            $(txt_id).val(answers[i]["answer_content"]);
                            if (answers[i]["is_answer"] == 1) {
                                $("#question_" + answers[i]["answer_name"]).attr('checked', true);
                            } else {
                                $("#question_" + answers[i]["answer_name"]).attr('checked', false);
                            }
                        }
                    }
                    console.log(question_data_edit[0]["answers"].length);
                    break;
            }

             tinymce.init({
            selector: '.dc-ques-tiny-mce-class',
             readonly: false,
            height: 250,
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

        }


    });

    $('.txt-float-value').keypress(function(event) {
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    function LoadDataForEdit(idx_name) {
        $(".btn-delete-question").remove();
        $(genAnswer(idx_name)).insertBefore("#add_answer_button_container");

        $(".btn-delete-question").click(function() {
            //$(this).closest(".answer_item").fadeOut(300);
            $(this).closest(".answer_item").remove();
            var items = $("#answer_container").find(".answer_item");
            if (items.length > 2) {
                var content_id = "#content_" + colName(items.length - 1);

                // $("<span class=\"btn btn-danger fa fa-remove btn-delete-question\"></span>")
                //     .insertAfter(content_id);
                $(content_id).parent().append("<span class=\"btn btn-danger fa fa-remove btn-delete-question\"></span>");

                initDeleteButtonAction();
            }
        });

        tinymce.init({
            selector: '.dc-ques-tiny-mce-class',
            readonly: false,
            height: 250,
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
    }

    function initDeleteButtonAction() {
        $(".btn-delete-question").click(function() {
            //$(this).closest(".answer_item").fadeOut(300);
            $(this).closest(".answer_item").remove();

            var items = $("#answer_container").find(".answer_item");
            if (items.length > 2) {
                var content_id = "#content_" + colName(items.length - 1);
                console.log(content_id);

                $("<span class=\"btn btn-danger fa fa-remove btn-delete-question\"></span>").insertAfter(
                    content_id);
                initDeleteButtonAction();
            }
              tinymce.init({
            selector: '.dc-ques-tiny-mce-class',
            readonly: false,
            height: 250,
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
        })
          tinymce.init({
            selector: '.dc-ques-tiny-mce-class',
            readonly: false,
            height: 250,
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
    }

    function genAnswer(name) {
        var str = "<div class=\"form-group answer_item\"><input type=\"radio\" id=\"question_" + name +
            "\" name=\"question_answer\" value=\"" + name + "\">&nbsp<label for=\"question_" + name + "\">" + name +
            "</label><textarea class=\"form-control dc-ques-tiny-mce-class\" id=\"content_" + name + "\" name=\"content_" + name +
            "\" required></textarea><span class=\"btn btn-danger fa fa-remove btn-delete-question\"></span></div>";
        return str;
    }

    function colName(n) {
        var ordA = 'a'.charCodeAt(0);
        var ordZ = 'z'.charCodeAt(0);
        var len = ordZ - ordA + 1;

        var s = "";
        while (n >= 0) {
            s = String.fromCharCode(n % len + ordA) + s;
            n = Math.floor(n / len) - 1;
        }
        return s.toUpperCase();
    }

    function getData(idx) {
        var question_type = $("#question_type").val();
        var question_content = tinyMCE.get("question_content").getContent() ;//  tinyMCE.get("#question_content"); //$("#question_content").val();
        var question_mark = $("#question_mark").val();
        console.log(typeof question_mark);
        if($.isNumeric(question_mark) == false){
            $.alert({
                title: "<strong class='alert-msg-success'>Warning!</strong>",
                content: "<p style='text-align:center;'>Điểm không hợp lệ!</p>",
                type: "orange",
                autoClose: "OK|3000",
                buttons: {
                    OK: function() {

                    }
                },
                onOpenBefore: function() {
                   // $(".jconfirm-content").css("text-align","center");
                },
                onDestroy:function(){
                    $("#question_mark").focus();
                }
            });

            return false;
        }

        var data = {
            question_id: null,
            question_idx: idx,
            question_type: question_type,
            question_mark: question_mark,
            question_content: question_content,
            answers: []
        };

        if (question_type == "1") {
            var items = $("#answer_container").find(".answer_item");
            $.each(items, function(key, value) {
                // console.log(value);
                answer_name = "";
                answer_content = "";
                is_answer = 0;

                radio = $(value).find('input[type=radio]');
                if (radio.length > 0) {
                    answer_name = $(radio[0]).val();
                    //console.log(answer_name);
                    if ($(radio[0]).is(':checked')) {
                        is_answer = 1;
                    }
                }

                //console.log(radio);
                text_area = $(value).find('textarea');
                if (text_area.length > 0) {
                    //answer_content = $(text_area[0]).val();
                    answer_content = tinyMCE.get($(text_area[0]).attr('id')).getContent();
                    //console.log(answer_content);
                }
                //console.log(text_area);

                answer_item = {
                    answer_id: null,
                    answer_name: answer_name,
                    answer_content: answer_content,
                    is_answer: is_answer
                }
                data["answers"].push(answer_item);

            });


        }

        //  console.log(data);

        return (data);
    }

</script>

<style type="text/css">
    .tox-tinymce-aux{z-index:99999999999 !important;}
</style>
