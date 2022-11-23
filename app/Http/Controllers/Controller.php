<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller as BaseController;
use App\Util;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


  /**
 * Import file into database Code
 *
 * @var array
 */
public function importExcel(Request $request)
{
    if($request->hasFile('import_file')){

        $path = $request->file('import_file')->getRealPath();
        $data = Excel::load($path, function($reader) {})->get();

        if(!empty($data) && $data->count()){

            foreach ($data->toArray() as $key => $value) {

                if(!empty($value)){

                    foreach ($value as $v) {

                        $insert[] = ['title' => $v['title'], 'description' => $v['description']];

                    }
                }
            }

            if(!empty($insert)){
                // Item::insert($insert);
                print_r($insert);
                return back()->with('success','Insert Record successfully.');
            }
        }
    }

    return back()->with('error','Please Check your file, Something is wrong there.');

}

}
