<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\SchoolYearController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ClassesController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\ExamClassController;
use App\Http\Controllers\Admin\TeacherClassSubjectController;
use App\Http\Controllers\Admin\MarkController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\FarmController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\DeviceController;
use App\Http\Controllers\Admin\UdeviceController;
use App\Http\Controllers\Admin\ZoneController;
use App\Http\Controllers\AccountController;
use App\Util;

use App\Http\Controllers\Frontend\ExamController as Exam_Controller;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('home');
// });

Auth::routes();




Route::get('/', [HomeController::class, 'index'])->name("home");
Route::get('/home', [HomeController::class, 'index'])->name("home");
Route::get('/trang-chu', [HomeController::class, 'index'])->name("home");
Route::get('/trangchu', [HomeController::class, 'index'])->name("home");

Route::prefix('/bai-kiem-tra')->group(function () {
    Route::get('', [Exam_Controller::class, 'index']);
    Route::get('{exam_class_id}/lam-bai/{exam_id}',[Exam_Controller::class, 'answer']);
    Route::get('{exam_class_id}/danh-gia/{exam_id}',[Exam_Controller::class, 'assess']);
    Route::get('{exam_class_id}/xem-bai-lam/{exam_id}',[Exam_Controller::class, 'view_answer']);
    Route::post('get_data', [Exam_Controller::class, 'find']);
    Route::post('luu-bai-lam',[Exam_Controller::class, 'store'] );
    Route::post('luu-diem-hs',[Exam_Controller::class, 'studentMark'] );
   // Route::post('luu-diem-gv',[Exam_Controller::class, 'teacherMark'] );

    Route::get('xem-danh-gia',[Exam_Controller::class, 'viewEvaluation']);
   
    Route::post('xoa',[Exam_Controller::class, 'delete'] );
});

Route::prefix('admin/tai-khoan')->group(function () {
    Route::get('/', [AccountController::class, 'index']);
    Route::post('cap-nhat',[AccountController::class, 'update'] );
});


Route::prefix('/tai-khoan')->group(function () {
    Route::get('/', [AccountController::class, 'index']);
    Route::post('cap-nhat',[AccountController::class, 'update'] );
});

Route::prefix('ho-tro')->group(function () {
    Route::post('upload-file',[Exam_Controller::class, 'uploadFile'] );
});

Route::get('/download/{file}', function ($file='') {
   return response()->download(storage_path("app/" . Util::$answer_file_folder . "/" .$file));
});

Route::get('/download_add_user/{file}', function ($file='') {
    return response()->download(storage_path("app/" . "public/excel_files/" .$file));
 });

Route::get('/xem-file/{file}', function ($file='') {
    return response()->file(storage_path("app/" . Util::$answer_file_folder . "/" .$file));
});


Route::prefix('admin/ql-diem-kiem-tra')->group(function () {
    Route::get('/{id}', [MarkController::class, 'new_index']);
    Route::get('{id}/gv-cham-diem', [MarkController::class, 'markTeacher'])->name('gv-cham-diem');
    Route::post('get_data', [MarkController::class, 'find']);
    Route::post('luu-diem-gv',[MarkController::class, 'teacherMark'] );
    Route::get('xem-danh-gia',[MarkController::class, 'reviewEvaluation']);
});

Route::prefix('admin/get-content-for-dialog')->group(function () {
    Route::get('xem-danh-gia',[MarkController::class, 'reviewEvaluation']);
});


Route::get('/admin', [ManagerController::class, 'index'])->name("admin");
// Route::get('/admin/ql-bai-kiem-tra', [AdminController::class, 'index'])->name("admin");

Route::prefix('admin/udevice')->group(function(){
    Route::get('', [UdeviceController::class, 'index']);
    Route::get('{id}/tttb', [UdeviceController::class, 'tttb']);
    Route::get('{id}/htcp', [UdeviceController::class, 'htcp']);
    Route::get('{id}/tltc',[UdeviceController::class, 'tltc']);
    Route::get('{id}/tltd',[UdeviceController::class, 'tltd']);
    Route::get('{id}/tdstc',[UdeviceController::class, 'tdstc']);
    Route::get('{id}/tdstd',[UdeviceController::class, 'tdstd']);
    Route::get('{id}/httl', [UdeviceController::class, 'httl']);
    Route::get('{id}/httds', [UdeviceController::class, 'httds']);
    Route::get('{id}/lsttn', [UdeviceController::class, 'lsttn']);
    Route::get('{id}/llb', [UdeviceController::class, 'llb']);
    Route::get('{id}/khht', [UdeviceController::class, 'khht']);
    Route::post('status', [UdeviceController::class, 'status']);
});

Route::prefix('admin/farm')->group(function(){
    Route::get('', [FarmController::class, 'index']);
    Route::get('create', [FarmController::class, 'create']);
    Route::post('create',[FarmController::class, 'store'] );
    Route::get('{id}/edit',[FarmController::class, 'edit'])->name('edit_farm');
    Route::post('update',[FarmController::class, 'update']);
    Route::get('{id}/delete',[FarmController::class, 'destroy']);
    Route::post('get_data', [FarmController::class, 'find']);
    Route::post('status', [FarmController::class, 'status']);
    Route::post('delete', [FarmController::class, 'delete']);
});

Route::prefix('admin/area')->group(function(){
    Route::get('', [AreaController::class, 'index']);
    Route::get('create', [AreaController::class, 'create']);
    Route::post('create',[AreaController::class, 'store'] );
    Route::get('{id}/edit',[AreaController::class, 'edit'])->name('edit_farm');
    Route::post('update',[AreaController::class, 'update']);
    Route::get('{id}/delete',[AreaController::class, 'destroy']);
    Route::post('get_data', [AreaController::class, 'find']);
    Route::post('status', [AreaController::class, 'status']);
    Route::post('delete', [AreaController::class, 'delete']);
});


Route::prefix('admin/device')->group(function(){
    Route::get('', [DeviceController::class, 'index']);
    Route::get('create', [DeviceController::class, 'create']);
    Route::post('create',[DeviceController::class, 'store'] );
    Route::get('{id}/edit',[DeviceController::class, 'edit'])->name('edit_farm');
    Route::post('update',[DeviceController::class, 'update']);
    Route::get('{id}/delete',[DeviceController::class, 'destroy']);
    Route::post('get_data', [DeviceController::class, 'find']);
    Route::post('status', [DeviceController::class, 'status']);
    Route::post('delete', [DeviceController::class, 'delete']);
});

// Route::prefix('admin/zone-mana')->group(function(){
//     Route::get('', [ZoneController::class, 'index']);
//     Route::get('create', [ZoneController::class, 'create']);
//     Route::post('create',[ZoneController::class, 'store'] );
//     Route::get('{id}/edit',[ZoneController::class, 'edit'])->name('edit_farm');
//     Route::post('update',[ZoneController::class, 'update']);
//     Route::get('{id}/delete',[ZoneController::class, 'destroy']);
//     Route::post('get_data', [ZoneController::class, 'find']);
//     Route::post('status', [ZoneController::class, 'status']);
//     Route::post('delete', [ZoneController::class, 'delete']);
// });

Route::prefix('admin/cau-hinh-bai-kiem-tra')->group(function () {
    Route::get('', [ExamClassController::class, 'index']);
    Route::get('them-moi', [ExamClassController::class, 'create']);
    Route::post('luu-moi',[ExamClassController::class, 'store'] );
    Route::get('{id}/cap-nhat',[ExamClassController::class, 'edit'])->name('edit_exam_class');
    Route::get('{id}/xem-bang-diem',[ExamClassController::class, 'calcMark'])->name('view_mark_class');
    Route::post('cap-nhat',[ExamClassController::class, 'update']);
    Route::get('{id}/xoa',[ExamClassController::class, 'destroy']);

    Route::get('/export_mark', [MarkController::class, 'exportCsv']);

    // Route::post('{subject_id}/get_data', [ExamController::class, 'find']);
    Route::post('get_data', [ExamClassController::class, 'find']);
    Route::post('status', [ExamClassController::class, 'status']);
    Route::post('xoa', [ExamClassController::class, 'delete']);
    Route::post('get_class', [ExamClassController::class, 'get_class']);
    Route::post('get_exam', [ExamClassController::class, 'get_exam']);

    // Route::get('{id}/xem-cau-hoi',[ExamController::class, 'viewExamContent'])->name('view_exam_content');
    // Route::get('{id}/them-cau-hoi',[ExamController::class, 'addExamContent'])->name('add_exam_content');
    // Route::get('cau-hoi',[ExamController::class, 'addQuestion']);
//  Route::get('{id}/mon-hoc',[QuestionController::class, 'edit'])->name('edit_question');

});

Route::prefix('admin/ql-bai-kiem-tra')->group(function () {
    Route::get('', [ExamController::class, 'index']);
    Route::get('create', [ExamController::class, 'create']);
    Route::post('create',[ExamController::class, 'store'] );
    Route::get('{id}/edit',[ExamController::class, 'edit'])->name('edit_exam');
    Route::post('update',[ExamController::class, 'update']);
    Route::get('{id}/delete',[ExamController::class, 'destroy']);

    // Route::post('{subject_id}/get_data', [ExamController::class, 'find']);
    Route::post('get_data', [ExamController::class, 'find']);
    Route::post('status', [ExamController::class, 'status']);
    Route::post('delete', [ExamController::class, 'delete']);

    Route::get('{id}/xem-cau-hoi',[ExamController::class, 'viewExamContent'])->name('view_exam_content');
    Route::get('{id}/them-cau-hoi',[ExamController::class, 'addExamContent'])->name('add_exam_content');
    Route::get('cau-hoi',[ExamController::class, 'addQuestion']);
//  Route::get('{id}/mon-hoc',[QuestionController::class, 'edit'])->name('edit_question');

});

Route::prefix('admin/ql-nam-hoc')->group(function () {
    Route::get('', [SchoolYearController::class, 'index']);
    Route::get('create', [SchoolYearController::class, 'create']);
    Route::post('create',[SchoolYearController::class, 'store'] );
    Route::get('{id}/edit',[SchoolYearController::class, 'edit'])->name('edit_nam_hoc');
    Route::post('update',[SchoolYearController::class, 'update']);
    Route::get('{id}/delete',[SchoolYearController::class, 'destroy']);

    Route::post('get_data', [SchoolYearController::class, 'find']);
    Route::post('status', [SchoolYearController::class, 'status']);
    Route::post('delete', [SchoolYearController::class, 'delete']);
});

Route::prefix('admin/ql-giao-vien')->group(function () {
    Route::get('', [TeacherController::class, 'index']);
    Route::get('create', [TeacherController::class, 'create']);
    Route::post('create',[TeacherController::class, 'store'] );
    Route::get('{id}/edit',[TeacherController::class, 'edit'])->name('edit_giao_vien');
    Route::post('update',[TeacherController::class, 'update']);
    Route::get('{id}/delete',[TeacherController::class, 'destroy']);
    Route::post('get_data', [TeacherController::class, 'find']);
    Route::post('status', [TeacherController::class, 'status']);
    Route::post('delete', [TeacherController::class, 'delete']);
    Route::post('change-password', [TeacherController::class, 'changePassword']);

    Route::get('reset-mk',[TeacherController::class, 'resetPassword']);
});

Route::prefix('admin/ql-mon-hoc')->group(function () {
    Route::get('', [SubjectController::class, 'index']);
    Route::get('create', [SubjectController::class, 'create']);
    Route::post('create',[SubjectController::class, 'store'] );
    Route::get('{id}/edit',[SubjectController::class, 'edit'])->name('edit_mon_hoc');
    Route::post('update',[SubjectController::class, 'update']);
    Route::get('{id}/delete',[SubjectController::class, 'destroy']);

    Route::post('get_data', [SubjectController::class, 'find']);
    Route::post('status', [SubjectController::class, 'status']);
    Route::post('delete', [SubjectController::class, 'delete']);
});

Route::prefix('admin/ql-lop-hoc')->group(function () {
    Route::get('', [ClassesController::class, 'index']);
    Route::get('them-moi', [ClassesController::class, 'create']);
    Route::post('luu-moi',[ClassesController::class, 'store'] );
    Route::get('{id}/cap-nhat',[ClassesController::class, 'edit'])->name('edit_lop_hoc');
    Route::post('cap-nhat',[ClassesController::class, 'update']);
    Route::get('{id}/xoa',[ClassesController::class, 'destroy']);
    Route::get('{id}/ds-hoc-sinh',[ClassesController::class, 'view_student']);

    Route::post('get_data', [ClassesController::class, 'find']);
    Route::post('get_data_student', [ClassesController::class, 'find_student']);
    Route::post('status', [ClassesController::class, 'status']);
    Route::post('xoa', [ClassesController::class, 'delete']);

    Route::get('{id}/them-moi-hs', [ClassesController::class, 'create_student']);
    Route::get('{id}/them-hs', [ClassesController::class, 'create_student_new']);
    Route::get('{id}/upload-hs', [ClassesController::class, 'upload_student_new']);
    Route::post('/them-hs', [ClassesController::class, 'store_student_new']);
    Route::post('/upload-ds-hs', [ClassesController::class, 'upload_student']);
    Route::post('/export-ds-hs', [ClassesController::class, 'export_student']);

    Route::get('{id}/cap-nhat-hs/{class_id}',[ClassesController::class, 'edit_student_new'])->name('edit_hoc_sinh_new');
    Route::post('cap-nhat-hs',[ClassesController::class, 'update_student_new']);


    //Route::post('{id}/luu-moi-hs',[ClassesController::class, 'store_student'] );
    // Route::get('{id}/cap-nhat-hs/{class_id}',[ClassesController::class, 'edit_student'])->name('edit_hoc_sinh');
    // Route::post('{id}/cap-nhat-hs/{class_id}',[ClassesController::class, 'update_student']);
    Route::post('{id}/xoa-hs',[ClassesController::class, 'destroy_student']);

});

Route::prefix('admin/ql-phan-mon')->group(function () {
    Route::get('', [TeacherClassSubjectController::class, 'index']);
    Route::get('them-moi', [TeacherClassSubjectController::class, 'create']);
    Route::post('luu-moi',[TeacherClassSubjectController::class, 'store'] );
    Route::get('{id}/cap-nhat',[TeacherClassSubjectController::class, 'edit'])->name('edit_lop_hoc');
    Route::post('cap-nhat',[TeacherClassSubjectController::class, 'update']);
    Route::get('{id}/xoa',[TeacherClassSubjectController::class, 'destroy']);
    Route::get('{id}/ds-hoc-sinh',[TeacherClassSubjectController::class, 'view_student']);

    Route::post('get_data', [TeacherClassSubjectController::class, 'find']);
    Route::post('get_data_student', [TeacherClassSubjectController::class, 'find_student']);
    Route::post('status', [TeacherClassSubjectController::class, 'status']);
    Route::post('xoa', [TeacherClassSubjectController::class, 'delete']);
    Route::post('get_class', [TeacherClassSubjectController::class, 'get_class']);
});




// Route::prefix('admin')->group(function () {
//     Route::get('/users', function () {
//         // Matches The "/admin/users" URL
//     });
// });



