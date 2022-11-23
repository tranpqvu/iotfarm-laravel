<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //

       // View::share('company_info',  DB::table('contacts')->first()); // <= Truyền dữ liệu
         //View::share('subjects', DB::table('subjects')->where("status","=",1)->orderBy("name", "ASC")->get()); // <= Truyền dữ liệu
         //View::share('school_years', DB::table('school_years')->where("status","=",1)->orderBy("year_open", "DESC")->get()); // <= Truyền dữ liệu
         //View::share('teachers', DB::table('users')->where("status","=",1)->where("level","=",1)->orderBy("first_name", "ASC")->get()); // <= Truyền dữ liệu

        //  $user = auth()->user();
        //  var_dump($user);
        //  $subject_by_teacher =  DB::table('subjects')
        //  ->join('teacher_subject', 'subjects.id', '=', 'teacher_subject.subject_id')
        //  ->where("teacher_subject.subject_id", $user->id)
        //  ->select('subjects.*')
        //  ->get();

        //  View::share('subject_by_teacher', $subject_by_teacher); // <= Truyền dữ liệu

        //  view()->composer('dashboard::layouts.*', function ($view) {
        //     $view->with('user', auth()->user());
        // });

        //  $class_data = [];
        //  $school_year = DB::table('school_years')->where("status","=",1)->get();
        //  foreach($school_year as $item){

        //  }
    }

    // public function boot(Request $request)
    // {
    //     //

    //    // View::share('company_info',  DB::table('contacts')->first()); // <= Truyền dữ liệu
    //      View::share('subjects', DB::table('subjects')->where("status","=",1)->get()); // <= Truyền dữ liệu

    //      $user = $request->user();
    //      var_dump($user);
    //      $subject_by_teacher =  DB::table('subjects')
    //      ->join('teacher_subject', 'subjects.id', '=', 'teacher_subject.subject_id')
    //      ->where("teacher_subject.subject_id", $user->id)
    //      ->select('subjects.*')
    //      ->get();

    //      View::share('subject_by_teacher', $subject_by_teacher); // <= Truyền dữ liệu

    //     //  $class_data = [];
    //     //  $school_year = DB::table('school_years')->where("status","=",1)->get();
    //     //  foreach($school_year as $item){

    //     //  }
    // }


    
}
