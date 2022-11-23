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
        <h4 style="text-align:center; color:#0090d2; font-weight: bold;">DANH SÁCH GIÁO VIÊN</h4>
        <div class="row justify-content-md-center">           
            <div class=" col-lg-5 col-md-5 col-sm-6 col-xs-12">
                <label for="subject_id" style="color:#0093ff; font-weight:bold;">Môn Học</label>
                <select class="form-control" id="subject_id" name="subject_id" style="color:#0093ff;">
                    <option value="">Tất cả</option>
                    @isset($subjects)
                    @foreach ($subjects as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                    @endisset
                </select>
            </div>
        </div>
    </div>
    <br/>
    <div class="asi-table-container">
        <div class="table-responsive">
            <table id="grid-data" class="table table-condensed table-hover table-striped">
                <thead>
                    <tr>
                        <th data-column-id="id" data-identifier="true" data-type="numeric" data-visible="false">ID</th>
                        <th data-column-id="full_name" >Họ Tên</th>
                        <th data-column-id="sex" >Giới Tính</th>
                        <th data-column-id="date_of_birth" >Ngày Sinh</th>
                        <th data-column-id="phone_number" >Số ĐT</th>
                        <th data-column-id="address" >Địa Chỉ</th>
                        <th data-column-id="email" >Email</th>
                        <th data-column-id="teacher_subject" >Môn Học Giảng Dạy</th>
                        <th data-column-id="status" data-formatter="status" >Trạng Thái</th>
                        <th data-column-id="commands"  data-formatter="commands" data-sortable="false"  data-headerAlign="center" data-align="center">Function</th>                
                        
                        {{-- <th data-column-id="created_by">Người Tạo</th> --}}
                        <th data-column-id="created_at">Ngày Tạo</th>
                        {{-- <th data-column-id="updated_by">Người Sửa</th> --}}
                        <th data-column-id="updated_at">Ngày Sửa</th>
                        
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="img01">
        <div id="caption"></div>
    </div>
</div>

<script>
  
  
  // $("#header-page-name").html("Quản Lý Bài Kiểm Tra");
    
    // $(".admin-sidebar").removeClass("active");
    // $("#tab-question").addClass("active")


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
	        //rowSelect : true,
	        post : function ()
	        {
	            return {
	                id : "b0df282a-0d67-40e5-8558-c9e93b7befed"
	            };
	        },
	        url        : "{{ url('/admin/ql-giao-vien/get_data') }}" ,
	        formatters : {
	            "commands" : function(column, row)
	            {	              
                    return  "<button title='Sửa' type=\"button\" class=\"bootgrid-btn btn-xs btn-default command-edit\" data-row-id=\"" + row.id + "\"><span class=\"fa fa-edit  \"></span></button> " +
	                        "<button title='Xóa' type=\"button\" class=\"bootgrid-btn btn-xs btn-default command-delete\" data-row-id=\"" + row.id + "\"><span class=\"fa fa-trash \"></span></button>" + 
                            "<button title='Reset Mật Khẩu' type=\"button\" class=\"btn btn-sm bootgrid-btn-outline btn-outline-danger command-reset-password\" data-row-id=\"" +
					row.id + "\" data-row-title=\"" + row.full_name + "\" ><span class=\"color:#028e11;\">Reset Mật Khẩu</span></button>"
                            ;
	                
	            },
	            "status" : function(column, row){
	             	if(row.status==1){
	             		return "<button title='Hủy Kích Hoạt' type=\"button\" class=\"bootgrid-btn btn-xs btn-default status-enable command-status\" data-row-id=\"" + row.id + "\"><span class=\"fa fa-check  \"></span></button> ";
	             	}else{
						return "<button title='Kích Hoạt' type=\"button\" class=\"bootgrid-btn btn-xs btn-default status-disable command-status\" data-row-id=\"" + row.id + "\"><span class=\"fa fa-times  \"></span></button> ";
	             	}
	            },
	        //    "image_url" : function(column, row){
            //         // return "<img onclick='MymodalImage(this);' alt='"+row.slogan_title+"' src='/local/public/upload/slide/"+ row.image_url +"' style='cursor: zoom-in;' width='60'/>";
            //          return "<img onclick='MymodalImage(this);' alt='"+row.slogan_title+"' src='"+ row.image_url +"' style='cursor: zoom-in;' width='60'/>";
            //    },
	        }
	   }).on("loaded.rs.jquery.bootgrid", function(){
        	/* Executes after data is loaded and rendered */
            $("#grid-data .command-edit").on("click", function(e){
                var id   = $(this).data("row-id");
                var link = 'admin/ql-giao-vien/' + id + '/edit';
               // {{ url('admin/ql-giao-vien/create') }}
                window.location.href = link;
        });

        $("#grid-data .command-reset-password").on("click", function(e)
        {
            
            var id       = $(this).data("row-id");
            var name     = $(this).data("row-title");   

 
                question_data_edit = undefined;
                var confirm_dialog = $.confirm({
                    title: "Reset Mật Khẩu - " + name,
                    //closeIcon: true,
                    //content: 'url:' +  "{{ url('/admin/ql-bai-kiem-tra/" +id+"/xem-cau-hoi') }}" , //'url:callback.html',
                    content: "URL:/admin/ql-giao-vien/reset-mk?id=" + id,
                    type: "blue",
                    columnClass: "col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12",
                    draggable: true,
                    buttons: {
                        Save: {
                            text: "&nbsp;&nbsp;Lưu&nbsp;&nbsp;",
                            btnClass: "btn-blue",
                            action: function() {
                                var id = $("#reset_id").val();
                                var password = $.trim($("#reset_password").val());
                                if(password==""){
                                    $.alert({
                                        title:     "<strong class='alert-msg-error'>Lỗi!</strong>",
                                        content:   "<p class='text-center'>Mật Khẩu không hợp lệ!</p>",
                                        type:      "red",
                                        autoClose: "OK|3000",
                                        buttons: {
                                            OK: function () {
                                            }
                                        },
                                        onDestroy:function(){
                                        },
                                    });
                                }else{
                                    reset_pasword(id, password);
                                }
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
             

            // $.confirm({
            //     title:   "Thông Báo!",
            //     content: "Bạn Muốn Xóa Dòng Này?",
            //     buttons: {
            //             Yes: {
            //                 text:     "Yes",
            //                 btnClass: "btn-blue",
            //                 action: function () {
            //                     delete_many_row([id]);
            //                 }
            //             },
            //             No:function(){

            //             }
            //     },
            //     onOpenBefore: function () {
            //       $(".jconfirm-content").css("text-align","center");
            //     },
            // })

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

            // if(current_row.length > 0){
            //     name = current_row[0].name;
            // }

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
                        No:function(){

                        }
                },
                onOpenBefore: function () {
                  $(".jconfirm-content").css("text-align","center");
                },
            })
        });

  $("#grid-data .command-status").on("click", function(e)
        {
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
	                        No:function(){

	                        }
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
    window.location.href = "{{ url('admin/ql-giao-vien/create') }}";
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
        url: "{{ url('/admin/ql-giao-vien/delete') }}",
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
        url        : "{{ url('/admin/ql-giao-vien/status') }}",
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
                    OK: function () {
                    // location.reload();
                    }
                },
                onDestroy:function(){
                // location.reload();
                },
            });
            $("#grid-data").bootgrid("reload");
        }
    });
 return false;
}

function reset_pasword(id, pass){
    
  
   $.ajax({
       url        : "{{ url('/admin/ql-giao-vien/change-password') }}",
       data       : {id: id, password: pass},
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
          // $("#grid-data").bootgrid("reload");
       },
       error: function (xhr, ajaxOptions, thrownError) {
           $.alert({
               title:     "<strong class='alert-msg-error'>Error!</strong>",
               content:   "<p class='text-center'>Connect to server fail. Please reload page and try again!</p>",
               type:      "red",
               autoClose: "OK|3000",
               buttons: {
                   OK: function () {
                   // location.reload();
                   }
               },
               onDestroy:function(){
               // location.reload();
               },
           });
          // $("#grid-data").bootgrid("reload");
       }
   });
return false;
}
 
</script>
@endsection