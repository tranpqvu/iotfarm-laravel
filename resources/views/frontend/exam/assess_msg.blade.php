@extends('frontend.frontend_master')
@section('content')
<div class="row justify-content-center">
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <h4 style="color:#0089ff; text-align:center;">
                        @isset($exam)
                            @php
                                if (count($exam) > 0) {
                                    echo $exam[0]->title;
                                    $exam_id = $exam[0]->id;
                                } else {
                                    echo 'BÀI KIỂM TRA';
                                }
                            @endphp
                        @endisset
                    </h4>
                </div>

                <div class="card-body">
                    <div class="col-xs-10 col-xs-offset-1">
                      <div class="alert alert-success alert-dismissible" role="alert">
                        <strong>{{ $msg }}</strong>
                    </div>
                    <center style="margin-top:1.5em;">
                            <button id="dc-btn-finish" class="btn btn-info btn-block" style="width:50%;">Thoát</button>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $("#dc-btn-finish").click(function() {
                var confirm = $.confirm({
                    title: 'Xác Nhận!',
                    content: 'Bạn muốn thoát?',
                    buttons: {
                        OK: {
                            text: 'Yes',
                            btnClass: 'btn-blue',
                            //keys: ['enter', 'shift'],
                            action: function() {
                               window.top.close();
                            }
                        },

                        Cancel: {
                            text: 'No',
                            btnClass: 'btn-orange',
                            //keys: ['enter', 'shift'],
                            action: function() {

                            }
                        }
                    }
                });

            });


        }); // end-document-ready
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
@endsection
