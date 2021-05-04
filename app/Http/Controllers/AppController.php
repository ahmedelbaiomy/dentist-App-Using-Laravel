<?php

namespace App\Http\Controllers;

use App\Models\note;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Patientstorage;
use App\Library\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Library\Services\DbHelperTools;
use Illuminate\Support\Facades\Storage;

class AppController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function patientProfile($patient_id)
    {
        $services = DB::select("SELECT patient_notes.id, patient_notes.patient_id,patient_notes.doctor_id, patient_notes.teeth_id,patient_notes.invoiced, patient_notes.note,patient_notes.type, patient_notes.category_id, service_categories.name, services.price 
                                FROM patient_notes 
                                    LEFT JOIN service_categories on patient_notes.category_id = service_categories.id
                                    left join services on service_categories.id = services.category_id
                                    WHERE patient_notes.patient_id = ? 
                                    GROUP BY patient_notes.id", [$patient_id]);


        $patient_data = DB::select("SELECT * FROM patients WHERE id=?", [$patient_id]);
        $name = explode(" ", $patient_data[0]->name);
        
        if(count($name) == 2)
           $short_name = $name[0][0].$name[1][0];
        else if(count($name) == 1)
           $short_name = $short_name = $name[0][0].$name[0][1];



        $data = DB::select("SELECT * FROM service_categories");
        $cat_arrs = array();
        foreach($data as $row) { 
            $cat_arrs[] = array(
                "id"     => $row ->id,
                "parent" => $row->parent_id,
                "text"   => $row->name
            );
        }
        $datas = json_encode($cat_arrs);

        $storages = DB::select("SELECT * FROM patient_storage WHERE patient_id=?", [$patient_id]);


        $invoices = DB::select("SELECT invoices.id, invoices.code, invoices.from,invoices.paid, users.email AS d_email, invoices.to, patients.email AS p_email, t.amount  FROM invoices
            LEFT JOIN users ON invoices.from  = users.id
            LEFT JOIN patients ON invoices.to = patients.id
            LEFT JOIN (SELECT invoice_lists.invoice_code, SUM(invoice_lists.amount) AS amount FROM invoice_lists GROUP BY invoice_lists.invoice_code) AS t ON t.invoice_code = invoices.code
            WHERE invoices.to = ?", [$patient_id]);
        
        $total = 0;
        $dept  = 0;
        $paid  = 0;

        //$notes = DB::table('notes')->where('patient_id',$patient_id)->get();
        $notes = note::where('patient_id',$patient_id)->get();
        return view('profile.patient.profile', compact('patient_id', 'datas','notes','services', 'invoices', 'patient_data', 'short_name', 'storages','total','paid','dept'));
    }
    public function formNote($patient_id,$note_id){
        $note=null;
        if ($note_id > 0) {
                $note = note::find ( $note_id );
        }
        return view('profile.form.note',compact('note','patient_id'));
    }
    public function storeFormNote(Request $request) {
		$success = false;
        $msg = 'Oops, something went wrong !';
        $id = 0;
        $resultPath='';
        if($request->hasFile('audio_data')){
            $uploadedFile = $request->file ( 'audio_data' );
            $original_name=$uploadedFile->getClientOriginalName();
            $size=$uploadedFile->getSize();
            $path = 'uploads/files/audio/';
            $audioPath='files/audio/';
            if(!File::exists($path)) {
                File::makeDirectory($path, 0755, true, true);
            }

			$resultPath=Storage::disk('public_uploads')->putFileAs ( $audioPath, $uploadedFile, $original_name );
			$exists = Storage::disk ( 'public_uploads' )->exists ( $audioPath."{$original_name}" );
			if(!$exists) {
				$resultPath=null;
			}
        }
        if ($request->isMethod('post')) {
            $DbHelperTools=new DbHelperTools();
            $data = array(
                'id'=>$request->id,
                'patient_id'=>$request->patient_id,
                'user_id'=>auth()->user()->id,
                'note'=>$request->note,
                'audio_file'=>(isset($resultPath))?base64_encode('uploads/'.$resultPath):null,
            );
            //dd($data);
            $note_id=$DbHelperTools->manageNote($data);
            $success = true;
            $msg = 'Your note have been saved successfully';
        }         
        return response ()->json ( [ 
                'success' => $success,
                'msg' => $msg 
        ] );
    }
    public function deleteNote($id){
        /**
         * forceDelete
         */
        $success = false;
        $DbHelperTools=new DbHelperTools();
        if($id){
            //unlink audio
            $d = note::select('audio_file')->where('id',$id)->first();
            if(File::exists(base64_decode($d->audio_file))){
                File::delete(base64_decode($d->audio_file));
            }
            //delete from database
            $deletedRows = $DbHelperTools->massDeletes([$id],'note',0);
            if($deletedRows>0){
              $success = true;
            }
        }
        return response()->json(['success'=>$success]);
    }
    public function deletePatientStorage($id){
        /**
         * forceDelete
         */
        $success = false;
        $DbHelperTools=new DbHelperTools();
        if($id){
            //unlink audio
            $d = Patientstorage::select('url')->where('id',$id)->first();
            //dd(base64_decode($d->url));
            if(File::exists('uploads/'.base64_decode($d->url))){
                File::delete('uploads/'.base64_decode($d->url));
            }
            //delete from database
            $deletedRows = $DbHelperTools->massDeletes([$id],'patientstorage',0);
            if($deletedRows>0){
              $success = true;
            }
        }
        return response()->json(['success'=>$success]);
    }
    public function sdtNotes(Request $request,$patient_id)
    {
        $data=$meta=[];
        $notes = note::where('patient_id',$patient_id)->orderByDesc('id')->get();
        foreach ($notes as $d) {
            $row=array();
                //ID
                $row[]='#NOTE-'.$d->id;
                //<th>Description</th>
                $row[]=$d->note;
                //attachment
                $audio=(isset($d->audio_file))?'<audio controls><source src="/'.base64_decode($d->audio_file).'" type="audio/wav"></audio>':'';
                $row[]=$audio;
                //<th>Created</th>
                $created='<p class="mb-0"><span class="badge badge-light-primary">Created at : '.$d->created_at->format('Y/m/d h:i:s').'</span></p>';
                $created.='<p class="mb-0"><span class="badge badge-light-primary">By : '.$d->user->name.'</span></p>';
                $row[]=$created;
                //Actions
                $btn_delete='<button class="btn btn-icon btn-outline-danger" onclick="_deleteNote('.$d->id.')" title="Delete">'.Helper::getSvgIconeByAction('DELETE').'</button>';
                $row[]=$btn_delete;
            $data[]=$row;
        }    
        $result = [
            'data' => $data,
        ];
        return response()->json($result);
    }
    public function storeFormStorage(Request $request) {
		$success = false;
        $msg = 'Oops, something went wrong !';
        $id = 0;
        $resultPath='';
        if($request->hasFile('file')){
            $uploadedFile = $request->file ( 'file' );
            $original_name=time().'_'.$uploadedFile->getClientOriginalName();
            
            $path = 'uploads/files/docs/';
            $filePath='files/docs/';
            if(!File::exists($path)) {
                File::makeDirectory($path, 0755, true, true);
            }

			$resultPath=Storage::disk('public_uploads')->putFileAs ( $filePath, $uploadedFile, $original_name );
			$exists = Storage::disk ( 'public_uploads' )->exists ( $filePath."{$original_name}" );
			if(!$exists) {
				$resultPath=null;
			}
        }
        if ($request->isMethod('post')) {

            //dd($request->all());

            $DbHelperTools=new DbHelperTools();
            $data = array(
                'id'=>$request->id,
                'patient_id'=>$request->patient_id,
                'title'=>$request->title,
                'description'=>$request->description,
                'url'=>(isset($resultPath))?base64_encode($resultPath):null,
            );
            $storage_id=$DbHelperTools->managePatientStorage($data);
            $success = true;
            $msg = 'You have successfully upload file.';
        }         
        return response ()->json ( [ 
                'success' => $success,
                'msg' => $msg 
        ] );
    }
    public function downloadPatientFile($id)
    {
        $storage = Patientstorage::where('id', $id)->firstOrFail();
		//$exists = Storage::disk ( 'public_uploads' )->exists ( base64_decode($storage->url));
        $pathToFile='uploads/'.base64_decode($storage->url);
        //dd($pathToFile);
        return response()->download($pathToFile);
    }
    public function sdtStorages(Request $request,$patient_id)
    {
        $data=$meta=[];
        $files = Patientstorage::where('patient_id',$patient_id)->orderByDesc('id')->get();
        foreach ($files as $d) {
            $row=array();
                //ID
                $row[]='#FILE-'.$d->id;
                //<th>Title</th>
                $row[]=$d->title;
                //<th>Description</th>
                $row[]=$d->description;
                //attachment
                $btn_download='<a href="/profile/patient/storage/'.$d->id.'/download" class="btn btn-icon btn-outline-primary" title="download">'.Helper::getSvgIconeByAction('DOWNLOAD').'</a>';
                $file=(isset($d->url))?base64_decode($d->url):'';
                $btn_fancybox='<a class="btn btn-icon btn-outline-info mr-1 fancybox-file" href="/uploads/'.$file.'">'.Helper::getSvgIconeByAction('VIEW').'</a>';
                $row[]=$btn_fancybox.$btn_download;
                //<th>Created</th>
                $created='<p class="mb-0"><span class="badge badge-light-primary">Created at : '.$d->created_at->format('Y/m/d h:i:s').'</span></p>';
                $row[]=$created;
                //Actions
                $btn_delete='<button class="btn btn-icon btn-outline-danger" onclick="_deletePatientStorage('.$d->id.')" title="Delete">'.Helper::getSvgIconeByAction('DELETE').'</button>';
                $row[]=$btn_delete;
            $data[]=$row;
        }    
        $result = [
            'data' => $data,
        ];
        return response()->json($result);
    }
}
