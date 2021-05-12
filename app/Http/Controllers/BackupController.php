<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Library\Services\DbHelperTools;
use Illuminate\Support\Facades\Response;

class BackupController extends Controller
{
    const PATH_TO_JSON_DATA = "backups/datas/";
    public function export(){
        $DbHelperTools=new DbHelperTools();
        $data = [
            't0' => ['service_categories' => $DbHelperTools->getDatasFromTableToArray('service_categories')],
            't1' => ['services' => $DbHelperTools->getDatasFromTableToArray('services')],  
            't2' => ['pr_tooths' => $DbHelperTools->getDatasFromTableToArray('pr_tooths')],  
         ];
         $data = json_encode($data);
         $backupFileName = time() . '_datas.json';
         $fileNameToDownload = 'datas.json';
        //put backup file
        $path = self::PATH_TO_JSON_DATA.'backup';
        if(!File::exists($path)) {
            File::makeDirectory($path, 0755, true, true);
        } 
        File::put(public_path($path.'/'.$backupFileName),$data);
        //put file 
        File::put(public_path(self::PATH_TO_JSON_DATA.$fileNameToDownload),$data);
        return Response::download(public_path(self::PATH_TO_JSON_DATA.$fileNameToDownload));
    }
    public function import(){
        $path = self::PATH_TO_JSON_DATA."datas.json";
        $json = json_decode(file_get_contents($path), true);
        foreach($json as $key => $datas){
            foreach($datas as $table => $rows){
                echo '------------------'.$table.'--------------------<br>';
                DB::table($table)->insert($rows);
            }
        }
        return true;
    }
    public function clear(){
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        //t1
        DB::table('services')->truncate();
        //t0
        DB::table('service_categories')->truncate();
        //
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        echo '<h1>Datas cleared</h1>';
        exit();
    }
    public function importCategories(){
        $path = self::PATH_TO_JSON_DATA."service_categories.json";
        $DbHelperTools=new DbHelperTools();
        $json = json_decode(file_get_contents($path), true);
        foreach($json as $key => $data){
            $DbHelperTools->manageServiceCategorie($data);
        }
        return true;
    }
    public function importServices(){
        $path = self::PATH_TO_JSON_DATA."services.json";
        $DbHelperTools=new DbHelperTools();
        $json = json_decode(file_get_contents($path), true);
        //dd($json);
        foreach($json as $key => $data){
            $DbHelperTools->manageService($data);
        }
        return true;
    }
}
