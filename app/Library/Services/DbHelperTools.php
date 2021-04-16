<?php
namespace App\Library\Services;
use Illuminate\Support\Facades\DB;
  
class DbHelperTools
{
    public function manageNote($data){
        $id=0;
        if (count($data)>0){
            $row = new Note();
            $id_pwd=(isset($data['id']))?$data['id']:0;
            if ($id_pwd > 0) {
                $row = Note::find ( $id_pwd );
            }
            $row->type = (isset($data['type']))?$data['type']:null;
            $row->title = (isset($data['title']))?$data['title']:null;
            $row->note = (isset($data['note']))?$data['note']:null;
            $row->is_favorite=(isset($data['is_favorite']))?$data['is_favorite']:null;
            $row->user_id=(isset($data['user_id']))?$data['user_id']:null;
            $row->save ();
            $id = $row->id;
        }
        return $id;
    }      
}
