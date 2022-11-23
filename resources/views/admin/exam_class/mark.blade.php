@extends('admin.admin_master')


@section('content')

<?php
    $data_export_id = [];
    $data_export_file_name = "";

    // print_r($data_export_file_name);
    // print_r($data_export_id);

    // Session::flash("data_export_id_" . auth()->user()->id, []);
    // Session::flash("data_export_file_name_" . auth()->user()->id, []);

    // print_r( Session::get("data_export_id_" . auth()->user()->id));
    // print_r( Session::get("data_export_file_name_" . auth()->user()->id));

    // $data_export_id[] = ["aa"=>"bb", "bb"=>"cc"];
    // $data_export_id[] = ["aa"=>"bb", "bb"=>"cc"];
    // print_r($data_export_id);
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
<div class="row justify-content-center">
    <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
        {{-- <p><a class="btn btn-primary" href="{{ url('admin/cau-hinh-bai-kiem-tra') }}">Về danh sách</a></p> --}}
        <div class="card">
            <div class="card-header text-center" style="background: #0093db;">
                <h4 style="color:#fbd403;">
                    @isset($exam)
                    @if($exam!=null)
                    {{ $exam->title }}
                    <?php $data_export_id[] = [$exam->title]; ?>
                    
                    @endif
                    @endisset
                </h4>
                <div class="container">
                     <div class="row ">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 dc-info-title" >
                            Năm Học:
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 dc-info-value">
                            @isset($exam)
                            @php
                            if ($exam != null) {
                                echo "<font style=\"color:#fbd403;\">" . $class->year_open . '-' . $class->year_close . '</font>';
                                $data_export_id[] = ["Năm Học:",  $class->year_open . '-' . $class->year_close];
                            } else {
                                echo '<font style=\"color:#fbd403;\"></font>';
                                $data_export_id[] = ["Năm Học:", ""];
                            }
                            @endphp
                            @endisset
                        </div>
                    </div>

                <div class="row ">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 dc-info-title" >
                        Môn Học:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 dc-info-value">
                        @isset($exam)
                        @php
                        if ($exam != null) {
                            echo "<font style=\"color:#fbd403;\">" . $exam->subject_name . '</font>';
                            $data_export_id[] = ["Môn Học:", $exam->subject_name];
                        } else {
                            echo '<font style=\"color:#fbd403;\"></font>';
                            $data_export_id[] = ["Môn Học:", ""];
                        }
                        @endphp
                        @endisset
                    </div>
                </div>

                 <div class="row ">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 dc-info-title" >
                        Lớp:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 dc-info-value">
                        @isset($class)
                        @php
                        if ($class != null) {
                            echo "<font style=\"color:#fbd403;\">" . $class->name . '</font>';
                            $data_export_id[] = ["Lớp Học:", $class->name];
                            $data_export_file_name = $class->name;
                        } else {
                            echo '<font style=\"color:#fbd403;\"></font>';
                            $data_export_id[] = ["Lớp Học:", ""];
                        }
                        @endphp
                        @endisset
                    </div>
                </div>

               {{--  <div class="row ">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 dc-info-title" >
                        Công Thức:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 dc-info-value">
                        @isset($mark_data)
                        @php
                        if ($mark_data["is_pa"] == 1) {
                            echo "<font style=\"color:#fbd403;\"> (Điểm 1 x "  . $mark_data["percent_1"] . ") + (Điểm 2 x " . $mark_data["percent_2"] .") + (Điểm 3 x " . $mark_data["percent_3"] . ") </font>";
                        } else {
                            echo "<font style=\"color:#fbd403;\">Điểm 1</font>";
                        }
                        @endphp
                        @endisset
                    </div>
                </div> --}}
                @isset($mark_data)
                @php
                if ($mark_data["is_pa"] == 1) { 
                    $formular = "(Điểm 1 x  " . $mark_data["percent_1"] . ") + (Điểm 2 x " . $mark_data["percent_2"] . ") + (Điểm 3 x " . $mark_data["percent_3"] . " )";
                    
                    @endphp
                 <div class="row ">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 dc-info-title" >
                        Tính Điểm:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 dc-info-value">
                        <font style="color:#fbd403;"> (Điểm 1 x  {{ $mark_data["percent_1"] }}) + (Điểm 2 x {{ $mark_data["percent_2"] }}) + (Điểm 3 x {{ $mark_data["percent_3"] }} ) </font>
                    </div>
                </div>
                @php
                $data_export_id[] = ["Tính Điểm:", $formular];
                }
                @endphp
                @endisset



            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive-sm table-responsive-xs ">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th scope="col" class="col-1 text-center">#</th>
                      <th scope="col" class="col-3 text-left" style="color:#0093db;">Họ Tên </th>                     
                      <th scope="col" class="col-1 text-center" style="color:#0093db;">Giới Tính</th>

                       @php
                       if ($mark_data["is_pa"] == 1) {
                        $data_export_id[] = ["STT", "Họ Tên", "Giới Tính", "Điểm 1", "Điểm 2", "Điểm 3", "Tổng Điểm", "Ghi Chú"];   
                        @endphp
                      <th scope="col" class="col-1 text-center" style="color:#0093db;">Điểm 1</th>
                      <th scope="col" class="col-1 text-center" style="color:#0093db;">Điểm 2</th>
                      <th scope="col" class="col-1 text-center" style="color:#0093db;">Điểm 3</th>
                       @php
                       }else{
                        $data_export_id[] = ["STT", "Họ Tên", "Giới Tính", "Tổng Điểm", "Ghi Chú"];
                       }
                       @endphp
                      <th scope="col" class="col-1 text-center" style="color:#0093db;">Tổng Điểm</th>
                      <th scope="col" class="col-3 text-center" style="color:#0093db;">Ghi Chú</th>
                  </tr>
              </thead>
              <tbody>
                  @isset($mark_data)
                  @php
                  $i = 1;

                  @endphp
                  @foreach ($mark_data["mark_info"] as $item)
                  <tr <?php 
                    if($item["has_mark"]==0 && $item["is_pa"]==1){
                        echo " style = \"color:#ff0000;\" ";
                    }else{
                        if($item["has_mark"]==0 && $item["is_pa"]==0){
                            echo " style = \"color:#ff0000;\" ";
                        }
                    } 
                  ?>>
                      <th scope="row" class="col-1 text-center">{{ $i }}</th>
                      <td class="text-left" class="col-3">
                          {{ $item["last_name"]." ".$item["first_name"] }}
                      </td>
                      <td class="text-center" class="col-1">{{ $item["sex"]==1?"Nam":"Nữ" }}</td>

                       @php
                       if ($mark_data["is_pa"] == 1) {
                            $note_tmp = "";
                            if($item["has_mark"]==0 && $item["is_pa"]==1){
                                $note_tmp = "Giáo viên chưa chấm điểm hoặc bài kiểm tra chưa được đánh giá";
                            }
                        $data_export_id[] = [$i, $item["last_name"]." ".$item["first_name"], $item["sex"]==1?"Nam":"Nữ", $item["mark_1"], $item["mark_2"], $item["mark_3"], $item["total_mark"], $note_tmp];
                    
                        @endphp
                      <td class="text-center" class="col-1">{{ $item["mark_1"] }}</td>
                      <td class="text-center" class="col-1">{{ $item["mark_2"] }}</td>
                      <td class="text-center" class="col-1">{{ $item["mark_3"] }}</td>

                       @php
                       }else{
                            $note_tmp = "";
                            if($item["has_mark"]==0 && $item["is_pa"]==0){
                                $note_tmp =  "Giáo viên chưa chấm điểm hoặc chưa làm bài kiểm tra";
                            }
                           
                            $data_export_id[] = [$i, $item["last_name"]." ".$item["first_name"], $item["sex"]==1?"Nam":"Nữ", $item["total_mark"], $note_tmp];
                    
                       }
                       @endphp
                      <td class="text-center" class="col-1">{{ $item["total_mark"] }}</td>
                      <td class="text-center" class="col-3">
                        @php

                            if($item["has_mark"]==0 && $item["is_pa"]==1){
                              echo "Giáo viên chưa chấm điểm hoặc bài kiểm tra chưa được đánh giá";
                            }else{
                                if($item["has_mark"]==0 && $item["is_pa"]==0){
                                    echo "Giáo viên chưa chấm điểm hoặc chưa làm bài kiểm tra";
                                }
                            } 
                       @endphp
                        </td>
                  </tr>
                  @php
                  $i = $i+1;
                 // print_r($item["tmp"]);
                  // echo ("</br/>");
                  // print_r($item["has_mark"]);
                  // echo ("</br/>");
                  // print_r($item["tmp"]);
                  //  echo ("</br/>");
                  // print_r($item["tmp_1"]);
                  @endphp
                  @endforeach
                  @endisset

              </tbody>
          </table>
          <center style="margin-top:1em;">
          <span data-href="{{url("/admin/cau-hinh-bai-kiem-tra/export_mark?id=14")}}" id="export" class="btn btn-success btn-sm" onclick="exportTasks(event.target);">Export CSV File</span>
        </center>
      </div>
  </div>
</div>
</div>
</div>

<?php
  

    Session::flash("data_export", $data_export_id);
    Session::flash("data_export_file_name", $data_export_file_name);

?>


<script>
    $(document).ready(function() {

    //alert(new Date());

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

function exportTasks(_this) {
      let _url = $(_this).data('href');
      window.location.href = _url;
   }

</script>

<style>

.table-hover tbody tr:hover {
    color: #077fff !important;
}

.dc-info-title{
    text-align: right;
    color: #ffffff;
    padding-right: 5px;
}

.dc-info-value{
    text-align: left;
    padding-left: 5px;
}

@media (max-width: 768px) {
  .col-xs-12.dc-info-title, .col-xs-12.dc-info-value {
      text-align: center;
  }
}

</style>
@endsection
