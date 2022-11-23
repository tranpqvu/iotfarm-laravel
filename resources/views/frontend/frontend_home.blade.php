@extends('frontend.frontend_master')
@section('content')


<?php
   use App\Util;

?>

<?php

    $aa = Util::utf8tourl("!@#t%^#$%^ổn&*(&)_+_!g hợp năm mới.csv");
    print_r($aa);

    $objPHPExcel = PHPExcel_IOFactory::load(base_path('aa.xlsx')); // load file ra object PHPExcel
       $provinceSheet = $objPHPExcel->setActiveSheetIndex(0); // Set sheet sẽ được đọc dữ liệu
       $highestRow    = $provinceSheet->getHighestRow(); // Lấy số row lớn nhất trong sheet
       $data=[];
       for ($row = 2; $row <= $highestRow; $row++) { // For chạy từ 2 vì row 1 là title
          $data[] = array(
               'id' => $provinceSheet->getCellByColumnAndRow(0, $row)->getValue(), // lấy dữ liệu từng ô theo col và row
               'name' => $provinceSheet->getCellByColumnAndRow(1, $row)->getValue(),
               'type' => $provinceSheet->getCellByColumnAndRow(2, $row)->getValue());
       }

       print_r($data);



        $objPHPExcel = new \PHPExcel();

        //foreach ($provinces as $key => $province) {
            $key = 0;
            $objPHPExcel->createSheet(); // tạo 1 sheet mới
            $activeSheet = $objPHPExcel->setActiveSheetIndex($key);
            $activeSheet->setTitle("Thừ Thiên Huế"); // đặt tên sheet là tên tỉnh
            $activeSheet->setCellValue('A1', 'Quận/Huyện')
                ->setCellValue('B1', 'Xã/Phường')
                ->setCellValue('C1', 'Kinh độ, vĩ độ'); // set title cho dòng đầu tiên
           {{--  $i = 2;
            $j = 2;
            foreach ($province->districts as $district) {
                $activeSheet->setCellValue("A$i", $district->type . ' ' . $district->name); // set tên quận/huyện
                foreach ($district->wards as $ward) {
                    $activeSheet->setCellValue("B$j", $ward->type . ' ' . $ward->name); // tương ứng mỗi quận huyện set tên xã/phường
                    $activeSheet->setCellValue("C$j", $ward->location);
                    $j++;
                }
                $rowMerge = $j - 1;
                if ($i < $rowMerge) {
                    $activeSheet->mergeCells("A$i:A$rowMerge"); // merge các cell có cùng 1 quận/huyện
                }
                $i = $j;
            } --}}

            foreach (range('A', 'C') as $columnId) {
                $activeSheet->getColumnDimension($columnId)->setAutoSize(true);
            }
        //}
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(base_path('DUC.xlsx'));

 ?>

<textarea ></textarea>
{{-- <script src='https://cdn.tiny.cloud/1/3awejbqbqo8s9vuv1q3kcaq6uzag57u96gsc6zglzon5m8nu/tinymce/5/tinymce.min.js' referrerpolicy="origin"></script> --}}
<script>


(function($) {
    $.fn.msword_html_filter = function(options) {
        var settings = $.extend( {}, options);

        function word_filter(editor){
            var content = editor.html();

            // Word comments like conditional comments etc
            content = content.replace(/<!--[\s\S]+?-->/gi, '');

            // Remove comments, scripts (e.g., msoShowComment), XML tag, VML content,
            // MS Office namespaced tags, and a few other tags
            content = content.replace(/<(!|script[^>]*>.*?<\/script(?=[>\s])|\/?(\?xml(:\w+)?|img|meta|link|style|\w:\w+)(?=[\s\/>]))[^>]*>/gi, '');

            // Convert <s> into <strike> for line-though
            content = content.replace(/<(\/?)s>/gi, "<$1strike>");

            // Replace nbsp entites to char since it's easier to handle
            //content = content.replace(/&nbsp;/gi, "\u00a0");
            content = content.replace(/&nbsp;/gi, ' ');

            // Convert <span style="mso-spacerun:yes">___</span> to string of alternating
            // breaking/non-breaking spaces of same length
            content = content.replace(/<span\s+style\s*=\s*"\s*mso-spacerun\s*:\s*yes\s*;?\s*"\s*>([\s\u00a0]*)<\/span>/gi, function(str, spaces) {
                return (spaces.length > 0) ? spaces.replace(/./, " ").slice(Math.floor(spaces.length/2)).split("").join("\u00a0") : '';
            });

            editor.html(content);

            // Parse out list indent level for lists
            $('p', editor).each(function(){
                var str = $(this).attr('style');
                var matches = /mso-list:\w+ \w+([0-9]+)/.exec(str);
                if (matches) {
                    $(this).data('_listLevel',  parseInt(matches[1], 10));
                }
            });

            // Parse Lists
            var last_level=0;
            var pnt = null;
            $('p', editor).each(function(){
                var cur_level = $(this).data('_listLevel');
                if(cur_level != undefined){
                    var txt = $(this).text();
                    var list_tag = '<ul></ul>';
                    if (/^\s*\w+\./.test(txt)) {
                        var matches = /([0-9])\./.exec(txt);
                        if (matches) {
                            var start = parseInt(matches[1], 10);
                            list_tag = start>1 ? '<ol start="' + start + '"></ol>' : '<ol></ol>';
                        }else{
                            list_tag = '<ol></ol>';
                        }
                    }

                    if(cur_level>last_level){
                        if(last_level==0){
                            $(this).before(list_tag);
                            pnt = $(this).prev();
                        }else{
                            pnt = $(list_tag).appendTo(pnt);
                        }
                    }
                    if(cur_level<last_level){
                        for(var i=0; i<last_level-cur_level; i++){
                            pnt = pnt.parent();
                        }
                    }
                    $('span:first', this).remove();
                    pnt.append('<li>' + $(this).html() + '</li>')
                    $(this).remove();
                    last_level = cur_level;
                }else{
                    last_level = 0;
                }
            })

            $('[style]', editor).removeAttr('style');
            $('[align]', editor).removeAttr('align');
            $('span', editor).replaceWith(function() {return $(this).contents();});
            $('span:empty', editor).remove();
            $("[class^='Mso']", editor).removeAttr('class');
            $('p:empty', editor).remove();
        }

        return this.each(function() {
            $(this).on('keyup', function(){
              $('#src').text($('#editor').html());

                var content = $(this).html();
                if (/class="?Mso|style="[^"]*\bmso-|style='[^'']*\bmso-|w:WordDocument/i.test( content )) {
                    word_filter( $(this) );
                }
            });
        });
    };
})( jQuery )

$(function(){
  $('#editor').msword_html_filter();
 // $('#src').text($('#editor').html());
})
</script>

 <div id="src">Source here...</div>
    <div id="editor" contenteditable="true">
      <p>Place MS-Word text here...</p>
    </div>
<script>

tinymce.init({
  selector: "textarea",  // change this value according to your HTML
  plugins: ""
});

// tinymce.init({
//   selector: 'textarea',  // change this value according to your HTML
//   external_plugins: {
//     'powerpaste': 'http://www.server.com/application/external_plugins/powerpaste/plugin.js'
//   }
// });


</script>

<!-- Page Heading -->
{{-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
</div>
<!-- Content Row -->
<div class="row">
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Earnings (Monthly)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">$40,000</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Earnings (Annual)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">$215,000</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 50%"
                                        aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pending Requests Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pending Requests</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Content Row -->
<div class="row">
    <!-- Area Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Pie Chart -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="myPieChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-primary"></i> Direct
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-success"></i> Social
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-info"></i> Referral
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Content Row -->
<div class="row">
    <!-- Content Column -->
    <div class="col-lg-6 mb-4">
        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
            </div>
            <div class="card-body">
                <h4 class="small font-weight-bold">Server Migration <span class="float-right">20%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 20%" aria-valuenow="20"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Sales Tracking <span class="float-right">40%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 40%" aria-valuenow="40"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Customer Database <span class="float-right">60%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Payout Details <span class="float-right">80%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 80%" aria-valuenow="80"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Account Setup <span class="float-right">Complete!</span></h4>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
        <!-- Color System -->
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card bg-primary text-white shadow">
                    <div class="card-body">
                        Primary
                        <div class="text-white-50 small">#4e73df</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card bg-success text-white shadow">
                    <div class="card-body">
                        Success
                        <div class="text-white-50 small">#1cc88a</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card bg-info text-white shadow">
                    <div class="card-body">
                        Info
                        <div class="text-white-50 small">#36b9cc</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card bg-warning text-white shadow">
                    <div class="card-body">
                        Warning
                        <div class="text-white-50 small">#f6c23e</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                        Danger
                        <div class="text-white-50 small">#e74a3b</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card bg-secondary text-white shadow">
                    <div class="card-body">
                        Secondary
                        <div class="text-white-50 small">#858796</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card bg-light text-black shadow">
                    <div class="card-body">
                        Light
                        <div class="text-black-50 small">#f8f9fc</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card bg-dark text-white shadow">
                    <div class="card-body">
                        Dark
                        <div class="text-white-50 small">#5a5c69</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <!-- Illustrations -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Illustrations</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                        src="{{asset('assets/assets_admin/img/undraw_posting_photo.svg')}}" alt="">
                </div>
                <p>Add some quality, svg illustrations to your project courtesy of <a target="_blank" rel="nofollow"
                        href="https://undraw.co/">unDraw</a>, a
                    constantly updated collection of beautiful svg images that you can use
                    completely free and without attribution!</p>
                <a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on
                    unDraw &rarr;</a>
            </div>
        </div>
        <!-- Approach -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Development Approach</h6>
            </div>
            <div class="card-body">
                <p>SB Admin 2 makes extensive use of Bootstrap 4 utility classes in order to reduce
                    CSS bloat and poor page performance. Custom CSS classes are used to create
                    custom components and custom utility classes.</p>
                <p class="mb-0">Before working with this theme, you should become familiar with the
                    Bootstrap framework, especially the utility classes.</p>
            </div>
        </div>
    </div>
</div> --}}

@php
// use App\PeerAssessment;
// use App\Util;
// print_r(Util::checkStartExam(9, 3));

//   date_default_timezone_set(Util::$time_zone);
//   $date = date('Y-m-d H:i:s');
//   print_r($date);
//   print_r("<br/>");
// $newdate = strtotime ( '+90 minutes' , strtotime ( $date ) ) ;
// $newdate = date ( 'Y-m-d H:i:s' , $newdate );
// print_r($newdate);
// use Illuminate\Support\Facades\DB;

//     $school_years = DB::table("exam_classes")
//     ->join("classes", "exam_classes.class_id", "=", "classes.id")
//     ->join("school_years", "classes.school_year_id", "=", "school_years.id")
//     ->where("exam_classes.id", 9)
//     ->select("school_years.id", "school_years.year_open", "school_years.year_close")
//     ->get()
//     ;

//     print_r($school_years);    

@endphp

@endsection
