
<div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">      
            <div class="card-header">
                @isset($exam)
                {{$exam->title}}
                @endisset
                @isset($answer_question)
                {{"  - Câu " . $answer_question->idx}}
                @endisset
            </div>     
            <div class="card-body">
                @isset($data)
                @foreach ($data as $item)                    
                
                <div class="container dc-question-container">
                    <ul class="nav dc-nav-tabs nav-tabs" role="tablist">
                        <li class="nav-item dc-nav-item waves-effect waves-light">
                            <a class="nav-link dc-nav-link active" data-toggle="tab" role="tab">{{ $item->last_name . " " . $item->first_name}} - {{ $item->mark . "(đ)" }}</a>                           
                        </li>
                    </ul>
                    <div class="tab-content dc-tab-content">
                        <div class="tab-pane active" role="tabpanel">                            
                            <div class="form-group answer_item">
                                <div class="row">
                                    <div class="col-6" style="text-align: left;">
                                        <label for="title" class="lb-answer-title">Nhận xét</label>
                                    </div>                                    
                                </div>                                                           
                                <textarea class="form-control dc-question-tiny-mce-class"
                                    style="min-height: 100px;" rows="10"
                                    >{{ $item->evaluation }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endisset

            </div>
        </div>
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

</style>

