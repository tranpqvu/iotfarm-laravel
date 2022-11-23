@extends('admin.admin_master')
@section('content')
<?php //Hiển thị thông báo thành công?>
@if ( Session::has('success') )
    <div class="alert alert-success alert-dismissible" role="alert">
        <strong>{{ Session::get('success') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
        </button>
    </div>
@endif

<?php //Hiển thị thông báo lỗi?>
@if ( Session::has('error') )
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
        <h4 style="text-align:center; color:#0090d2; font-weight: bold;">Thông Tin Thiết Bị</h4>      
            <div class="btn-group">
                <button type="button" class="btn btn-primary" onclick="location.href='/admin/udevice/{!! $id !!}/htcp';">Điều Khiển Máy</button>
                <button type="button" class="btn btn-primary" onclick="location.href='/admin/udevice/{!! $id !!}/lsttn';">Lịch Sử Tưới</button>
                <button type="button" class="btn btn-primary" onclick="location.href='/admin/udevice/{!! $id !!}/khht';">Khí Hậu</button>
                <button type="button" class="btn btn-primary" onclick="location.href='/admin/udevice/{!! $id !!}/llb';">Kiếm Tra Bơm</button>
            </div>
            <div>
            <br/>Máy châm phân tự động IOT Farm<br/>
            Công Nghệ Inverter<br/><br/>
            Hình Thức Châm Phân Đang Chạy<br/>
            @if (($data!==null)&&($data[0]->htcp==0))
                <input type="radio" id="tds" name="htcp" value="0" checked="checked">
            @else 
                <input type="radio" id="tds" name="htcp" value="0">
            @endif
            <label for="tds"> TDS</label>&nbsp;&nbsp;&nbsp;&nbsp;
            @if (($data!==null)&&($data[0]->htcp==1))
                <input type="radio" id="tl" name="htcp" value="1" checked="checked">
            @else
                <input type="radio" id="tl" name="htcp" value="1">
            @endif
            <label for="tl"> Tỷ Lệ</label><br>
            Chế Độ Hoạt Động Đang Chạy<br/>
            @if (($data!==null)&&($data[0]->cdhd==0))
            <input type="radio" id="tc" name="cdc" value="0" checked="checked">
            @else                
                <input type="radio" id="tc" name="cdc" value="0">
            @endif
            <label for="tc"> Thủ Công</label>&nbsp;&nbsp;&nbsp;&nbsp;
            @if (($data!==null)&&($data[0]->cdhd==1))
            <input type="radio" id="td" name="cdc" value="1" checked="checked">   
            @else    
                <input type="radio" id="td" name="cdc" value="1">   
            @endif
            <label for="td"> Tự Động</label><br><br>
            <button type="button" class="btn btn-outline-primary" onclick="saveControl({!! $id !!})">Tới trang hoạt động</button><br>
            </div>
    </div>
    <br/>
</div>

<script>

    function saveControl(id){
        //alert('toi trang hoat dong');
        var ele = document.getElementsByName('htcp');
        var htcp="";              
        for(i = 0; i < ele.length; i++) {
            if(ele[i].checked)
            htcp = ele[i].value;
        }
        var ele = document.getElementsByName('cdc'); 
        var cdhd="";
        for(i = 0; i < ele.length; i++) {
            if(ele[i].checked)
            cdhd = ele[i].value;
        }
        
        if ((htcp=="")||(cdhd=="")){
            alert('ban phai chon Hinh Thuc Cham Phan va Che Do Chay');
        }else{
            $.ajax({
                url        : "{{ url('/admin/udevice/status') }}",
                data       : {id: id, htcp: htcp, cdhd: cdhd},
                dataType   : "json",
                type       : "POST",
                cache      : false,
                beforeSend : function() {
                    $("body").addClass("loading");
                },
                complete: function() {
                    $("body").removeClass("loading");
                },
                success: function(response) {
                    switch(response.result){
                        case 1:
                            switch(htcp.concat(cdhd)){
                                case '00':
                                        location.href='/admin/udevice/'+id+'/tdstc';
                                    break;
                                case '01':
                                        location.href='/admin/udevice/'+id+'/tdstd';
                                    break;
                                case '10':
                                        location.href='/admin/udevice/'+id+'/tltc';
                                    break;
                                case '11':
                                        location.href='/admin/udevice/'+id+'/tltd';
                                    break;
                            }
                        return 1;
                        default:
                            $.alert({
                                title:     "<strong class='alert-msg-eror'>Error!</strong>",
                                content:   "<p class='text-center'>" + response.msg + "</p>",
                                type:      "red",
                                autoClose: "OK|3000",
                                buttons: {
                                    OK: function () {
                                    }
                                },
                                onDestroy:function(){
                                    return false;
                                }
                            });
                        return 0;
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    $.alert({
                        title:     "<strong class='alert-msg-error'>Error!</strong>",
                        content:   "<p class='text-center'>Connect to server fail. Please reload page and try again!</p>",
                        type:      "red",
                        autoClose: "OK|3000",
                        buttons: {
                            OK: function () {}
                        },
                        onDestroy:function(){},
                    });
                    return 0;
                }
                });
        }
    }
    var rowIds = [];
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var grid = $("#grid-data").bootgrid({
            templates : { select : ""},
            buttons: [{
                        name: "  Thêm",
                        bclass: "add btn-primary fa fa-plus",
                        onpress: "process_action_add"
                    },
                    {
                        name: "  Xóa",
                        bclass: "delete btn-danger fa fa-trash",
                        onpress: "process_action_delete"
                    }
            ],
            ajax        : true,
            selection   : true,
            multiSelect : true,
            post : function ()
            {
                return {
                    id : "b0df282a-0d67-40e5-8558-c9e93b7befed"
                };
            },
            url        : "{{ url('/admin/device/get_data') }}" ,
            formatters : {
                "commands" : function(column, row)
                {	              
                    return  "<button title='Sửa' type=\"button\" class=\"bootgrid-btn btn-xs btn-default command-edit\" data-row-id=\"" + row.id + "\"><span class=\"fa fa-edit  \"></span></button> " +
                            "<button title='Xóa' type=\"button\" class=\"bootgrid-btn btn-xs btn-default command-delete\" data-row-id=\"" + row.id + "\"><span class=\"fa fa-trash \"></span></button>";
                },
                "status" : function(column, row){
                    if(row.status==1){
                        return "<button title='Hủy Kích Hoạt' type=\"button\" class=\"bootgrid-btn btn-xs btn-default status-enable command-status\" data-row-id=\"" + row.id + "\"><span class=\"fa fa-check  \"></span></button> ";
                    }else{
                        return "<button title='Kích Hoạt' type=\"button\" class=\"bootgrid-btn btn-xs btn-default status-disable command-status\" data-row-id=\"" + row.id + "\"><span class=\"fa fa-times  \"></span></button> ";
                    }
                },
            }
       }).on("loaded.rs.jquery.bootgrid", function(){
            /* Executes after data is loaded and rendered */
            $("#grid-data .command-edit").on("click", function(e){
                var id   = $(this).data("row-id");
                var link = 'admin/device/' + id + '/edit';
                window.location.href = link;
        });

       $("#grid-data .command-delete").on("click", function(e)
        {
            //alert("You pressed delete on row: " + $(this).data("row-id"));
            var id       = $(this).data("row-id");
            var name     = "";
            current_row  = $("#grid-data").bootgrid("getCurrentRows").filter(
                function ( elem ) {
                   return elem.id == parseInt(id);
                }
            );

            $.confirm({
                title:   "Thông Báo!",
                content: "Bạn Muốn Xóa Dòng Này?",
                buttons: {
                        Yes: {
                            text:     "Yes",
                            btnClass: "btn-blue",
                            action: function () {
                                delete_many_row([id]);
                            }
                        },
                        No:function(){}
                },
                onOpenBefore: function () {
                  $(".jconfirm-content").css("text-align","center");
                },
            })
        });

    $("#grid-data .command-status").on("click", function(e){
            //alert("You pressed delete on row: " + $(this).data("row-id"));
            var id   = $(this).data("row-id");
        
            current_row  = $("#grid-data").bootgrid("getCurrentRows").filter(
                    function ( elem ) {
                       return elem.id == parseInt(id);
                    }
            );

            if(current_row.length > 0){
                status = current_row[0].status;
            if(status == 1){
                msg = "Bạn muốn hủy kích hoạt?";
            }else{
                msg = "Bạn muốn kích hoạt?";
            }
                $.confirm({
                    title:   "Thông Báo!",
                    content: msg,
                    buttons: {
                            Yes: {
                                text:     "Yes",
                                btnClass: "btn-blue",
                                action: function () {
                                    change_status(id, status);
                                }
                            },
                            No:function(){}
                    },
                    onOpenBefore: function () {
                      $(".jconfirm-content").css("text-align","center");
                    },
                });
            }
        });
    });
});

function process_action_add(){
    window.location.href = "{{ url('admin/device/create') }}";

}


function process_action_delete(){
    var arr_id = $("#grid-data").bootgrid("getSelectedRows");
    console.log(arr_id);

    if(arr_id.length>0){
        if(arr_id.length > 1){
            content = "<p style='text-align:center;'>Bạn muốn xóa những dòng đã chọn?</p>";
        }else{
            content = "<p style='text-align:center;'>Bạn muốn xóa dòng này?</p>";
        }
        $.confirm({
            title:   "Bạn Có Chắc!",
            content: content,
            buttons: {
                Yes: {
                    text:     "Yes",
                    btnClass: "btn-blue",
                    action: function () {
                        delete_many_row(arr_id);
                    }
                },
                No:function(){
                }
            },
            onOpenBefore: function () {
                $(".jconfirm-content").css("text-align","center");
            },
        })
    }else{
	   $.alert({
            title:     "<strong class='alert-msg-warning'>Thông Báo!</strong>",
            content:   "Xin chọn dòng dữ liệu cần xóa!",
            type:      "yellow",
            autoClose: "OK|3000",
            buttons: {
                OK: function () {

                }
            },
            onOpenBefore: function () {
              $(".jconfirm-content").css("text-align","center");
            },
      });
    }
}

/*
Delete many template
*/
function delete_many_row(arr_id){
    $.ajax({
        url: "{{ url('/admin/device/delete') }}",
        data: {
                id: arr_id
            },
        dataType: "json",
        type:     "POST",
        cache:    false,
     	beforeSend: function() {
            $("body").addClass("loading");
        },
        complete: function() {
            $("body").removeClass("loading");
        },
        success: function(response) {
            switch(response.result){
                case 1:
                     $.alert({
                            title:     "<strong class='alert-msg-success'>Success!</strong>",
                            content:   response.msg,
                            type:      "green",
                            autoClose: "OK|3000",
                            buttons: {
                                OK: function () {

                                }
                            },
                            onOpenBefore: function () {
                                $(".jconfirm-content").css("text-align", "center");
                            },
                      });
                break;
                    default:
                          $.alert({
                                title:     "<strong class='alert-msg-eror'>Error!</strong>",
                                content:   response.msg,
                                type:      "red",
                                autoClose: "OK|3000",
                                buttons: {
                                    OK: function () {

                                    }
                                },
                                onOpenBefore: function () {
                                  $(".jconfirm-content").css("text-align","center");
                                },
                          });

                    break;
                }
                $("#grid-data").bootgrid("reload");
            
                
            },
            error: function (xhr, ajaxOptions, thrownError) {
            $.alert({
                title:   "<strong class='alert-msg-error'>Error!</strong>",
                content: "Không kết nối được server!",
                type:    "red",
                buttons: {
                    OK: function () {
                        location.reload();
                    }
                },
                onDestroy:function(){
                     $("#grid-data").bootgrid("reload");
                }
            });
        }
    });
}

function change_status(id, status){
    
 	new_status = status == 1 ? 0: 1;
   
    $.ajax({
        url        : "{{ url('/admin/device/status') }}",
        data       : {id: id, status: new_status},
        dataType   : "json",
        type       : "POST",
        cache      : false,
        beforeSend : function() {
            $("body").addClass("loading");
        },
        complete: function() {
            $("body").removeClass("loading");
        },
        success: function(response) {
            switch(response.result){
                case 1:
                    $.alert({
                        title:     "<strong class='alert-msg-success'>Success!</strong>",
                        content:   "<p class='text-center'>" + response.msg + "</p>",
                        type:      "green",
                        autoClose: "OK|3000",
                        buttons: {
                            OK: function () {
                                 return true;
                            }
                        },
                        onDestroy: function(){
                            return true;
                        }
                    });
                break;
                default:
                    $.alert({
                        title:     "<strong class='alert-msg-eror'>Error!</strong>",
                        content:   "<p class='text-center'>" + response.msg + "</p>",
                        type:      "red",
                        autoClose: "OK|3000",
                        buttons: {
                            OK: function () {
                            }
                        },
                        onDestroy:function(){
                            return false;
                        }
                    });
                break;
            }
            $("#grid-data").bootgrid("reload");
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $.alert({
                title:     "<strong class='alert-msg-error'>Error!</strong>",
                content:   "<p class='text-center'>Connect to server fail. Please reload page and try again!</p>",
                type:      "red",
                autoClose: "OK|3000",
                buttons: {
                    OK: function () {}
                },
                onDestroy:function(){},
            });
            $("#grid-data").bootgrid("reload");
        }
    });
 return false;
}
</script>
@endsection
