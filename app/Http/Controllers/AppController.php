<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\note;
use App\Models\User;
use App\Models\Teeth;
use App\Models\Invoice;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\Invoicerefund;
use App\Models\Invoicepayment;
use App\Models\Patientstorage;
use App\Library\Helpers\Helper;
use App\Models\service_category;
use Illuminate\Support\Facades\DB;
use App\Models\Procedureserviceitem;
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
        /* $services = DB::select("SELECT patient_notes.id, patient_notes.patient_id,patient_notes.doctor_id, patient_notes.teeth_id,patient_notes.invoiced, patient_notes.note,patient_notes.type, patient_notes.category_id, service_categories.name, services.price 
                                FROM patient_notes 
                                    LEFT JOIN service_categories on patient_notes.category_id = service_categories.id
                                    left join services on service_categories.id = services.category_id
                                    WHERE patient_notes.patient_id = ? 
                                    GROUP BY patient_notes.id", [$patient_id]); */

        $categories =service_category::where('is_active',1)->get();
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
        return view('profile.patient.profile', compact('patient_id', 'datas','notes','categories', 'invoices', 'patient_data', 'short_name','total','paid','dept'));
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
                //By
                $row[]='<p class="mb-0"><span class="badge badge-light-primary">By : '.$d->user->name.'</span></p>';
                //<th>Description</th>
                $row[]=$d->note;
                //attachment
                $audio=(isset($d->audio_file))?'<audio controls><source src="/'.base64_decode($d->audio_file).'" type="audio/wav"></audio>':'';
                $row[]=$audio;
                //<th>Created</th>
                $created='<p class="mb-0"><span class="badge badge-light-primary">Created at : '.$d->created_at->format('Y/m/d h:i:s').'</span></p>';
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
    public function getProceduresTooths(){
        $procedures_row_one=Teeth::where('row_number',1)->orderBy('sort','asc')->get();
        $procedures_row_two=Teeth::where('row_number',2)->orderBy('sort','asc')->get();
        return view('profile.patient.procedures',compact('procedures_row_one','procedures_row_two'));
    }
    public function profileConstruct($viewtype,$patient_id){
        // $note=null;
        // if ($note_id > 0) {
        //         $note = note::find ( $note_id );
        // }
        $categories =service_category::where('is_active',1)->get();
        return view('profile.construct.view',compact('viewtype','patient_id','categories'));
    }
    public function sdtInvoices(Request $request,$patient_id)
    {
        $data=$meta=[];
        $DbHelperTools=new DbHelperTools();
        $notes = Invoice::where('patient_id',$patient_id)->orderByDesc('id')->get();
        foreach ($notes as $d) {
            $row=array();                
            // <th>Code</th>
            $dtBillDate = Carbon::createFromFormat('Y-m-d',$d->bill_date);
            $dtIssueDate = Carbon::createFromFormat('Y-m-d',$d->due_date);
            $bill='<span class="badge badge-light-secondary">Bill :'.$dtBillDate->format('Y-m-d').'</span>';
            $issue='<span class="badge badge-light-secondary">Issue :'.$dtIssueDate->format('Y-m-d').'</span>';
            $number='<p class="mb-0 text-primary">#'.$d->number.'</p>';
            $row[]=$number.$bill.$issue;
            // <th>Doctor</th>
            $doctor='<span class="badge badge-light-secondary">Dr :'.$d->user->name.'</span>';
            $patient='<span class="badge badge-light-secondary">Pa :'.$d->patient->name.'</span>';
            $row[]=$doctor.$patient;
            // <th>Patient</th>
            //$row[]=$d->patient->name;
            // <th>Total</th>
            $calcul=$DbHelperTools->getAmountsInvoice($d->id);
            $total='<span class="badge badge-light-primary">'.$calcul['total'].'$'.'</span> ';
            $refund=($calcul['total_refund']>0)?'<span class="badge badge-light-danger">Refund : '.$calcul['total_refund'].'$'.'</span>':'';
            $row[]=$total.$refund;
            // <th>Paid</th>
            $row[]='<span class="badge badge-light-info">'.$calcul['total_paid'].'$'.'</span> ';
            // <th>Status</th>
            $tabHelperType=Helper::getcssClassByType($d->status);
            $status='<span class="badge badge-light-'.$tabHelperType[0].'">'.$tabHelperType[1].'</span> ';
            $row[]=$status;
            // <th>Action</th>

            $btn_group_actions='<div class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle hide-arrow mr-1" id="invoiceActions"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        '.Helper::getSvgIconeByAction('MORE-VERTICAL').'
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="invoiceActions">
                        <a class="dropdown-item sort-asc" href="javascript:void(0)" onclick="_formInvoice('.$d->patient_id.','.$d->id.')">'.Helper::getSvgIconeByAction('EDIT').' Edit</a>
                        <a class="dropdown-item sort-asc" href="javascript:void(0)" onclick="_formPayment(0,'.$d->id.')">'.Helper::getSvgIconeByAction('DOLLAR').' Add payment</a>
                        <a class="dropdown-item sort-asc" href="javascript:void(0)" onclick="_formRefund(0,'.$d->id.')">'.Helper::getSvgIconeByAction('TRENDING-DOWN').' Add refund</a>
                    </div>
            </div>';

            //$btn_edit='<button class="btn btn-icon btn-sm btn-outline-primary mr-1" onclick="_formInvoice('.$d->patient_id.','.$d->id.')" title="Edit">'.Helper::getSvgIconeByAction('EDIT').'</button>';
            //$btn_add_payment='<button class="btn btn-icon btn-sm btn-outline-success mr-1" onclick="_formPayment(0,'.$d->id.')" title="Add payment">'.Helper::getSvgIconeByAction('DOLLAR').'</button>';
            //$btn_add_refund='<button class="btn btn-icon btn-sm btn-outline-danger mr-1" onclick="_formRefund(0,'.$d->id.')" title="Add refund">'.Helper::getSvgIconeByAction('TRENDING-DOWN').'</button>';
            //$row[]=$btn_edit.$btn_add_payment.$btn_add_refund;
            $row[]=$btn_group_actions;
            $data[]=$row;
        }    
        $result = [
            'data' => $data,
        ];
        return response()->json($result);
    }
    public function formInvoice($patient_id,$invoice_id){
        $invoice=$patient=null;
        if ($invoice_id > 0) {
                $invoice = Invoice::find ( $invoice_id );
        }
        if($patient_id>0){
            $patient=Patient::find($patient_id);
        }
        return view('profile.form.invoice',compact('invoice','patient','patient_id'));
    }
    public function storeFormInvoice(Request $request) {
		$success = false;
        $msg = 'Oops, something went wrong !';
        $id = 0;
        if ($request->isMethod('post')) {
            $DbHelperTools=new DbHelperTools();
            //dd($request->bill_date);
            $bill_date = Carbon::createFromFormat('Y-m-d',$request->bill_date);
            $due_date = Carbon::createFromFormat('Y-m-d',$request->due_date);
            $data = array(
                'id'=>$request->id,
                'number'=>($request->id==0)?$DbHelperTools->generateInvoiceNumber('INVOICE'):null,
                'doctor_id'=>$request->doctor_id,
                'patient_id'=>$request->patient_id,
                'bill_date'=>$bill_date,
                'due_date'=>$due_date,
                'note'=>$request->note,
                'tax_percentage'=>$request->tax_percentage,
                'discount_amount'=>$request->discount_amount,
                'discount_amount_type'=>'percentage',
                'discount_type'=>'before_tax',
                'status'=>'draft',
                'cancelled_at'=>null,
                'cancelled_by'=>null,
            );
            //dd($data);
            $note_id=$DbHelperTools->manageInvoice($data);
            $success = true;
            $msg = 'Your invoice has been saved successfully';
        }         
        return response ()->json ( [ 
                'success' => $success,
                'msg' => $msg 
        ] );
    }
    public function getInvoiceItems($invoice_id){
        
        //$items=Procedureserviceitem::all();
        $items=[];
        $calcul=[];
        $discount_type = 'before_tax';
        $tax_percentage = 0;
        $discount_amount_type=$discount_amount='';
        if ($invoice_id > 0) {
          $items = Procedureserviceitem::where('invoice_id',$invoice_id)->get();
          $DbHelperTools=new DbHelperTools();
          $calcul=$DbHelperTools->getAmountsInvoice($invoice_id);
          $invoice = Invoice::select('discount_type','discount_amount','discount_amount_type','tax_percentage')->where( 'id',$invoice_id )->first();
          $discount_type = $invoice->discount_type;
          $discount_amount = $invoice->discount_amount;
          $tax_percentage = $invoice->tax_percentage;
          $discount_amount_type = $invoice->discount_amount_type;
        }
        return view('profile.invoice.items',compact('items','invoice_id','calcul','discount_type','tax_percentage','discount_amount_type','discount_amount'));
    }
    public function formDiscount($invoice_id)
    {
        $row = null;
        if ($invoice_id > 0) {
          $row = Invoice::select('id','discount_type','discount_amount','discount_amount_type')->where( 'id',$invoice_id )->first();
        }
        return view('profile.form.discount',compact('row'));
    }
    public function storeFormDiscount(Request $request)
    {
        $success = false;
        $msg = 'Oops !';
        if ($request->isMethod('post')) {
            $DbHelperTools=new DbHelperTools();
            //dd($request->all());
            if($request->id>0){
                $row = Invoice::find ( $request->id );
                $row->discount_type = $request->discount_type;
                $row->discount_amount = $request->discount_amount;
                $row->discount_amount_type = $request->discount_amount_type;
                $row->save ();
                $id = $row->id;
                $success = true;
                $msg = 'Your discount has been saved successfully';
            }    
        }
        return response()->json([
            'success' => $success,
            'msg' => $msg,
        ]);
    }
    public function formInvoiceItems($invoice_id)
    {
        return view('profile.form.invoice-items',compact('invoice_id'));
    }
    //storeInvoiceItems
    public function storeInvoiceItems(Request $request)
    {
        $success = false;
        $msg = 'Oops !';
        if ($request->isMethod('post')) {
            $DbHelperTools=new DbHelperTools();
            //dd($request->all());
            if(count($request->ids_items)>0){
                Procedureserviceitem::whereIn('id', $request->ids_items)->update(['invoice_id' => $request->invoice_id,'type' => 'completed']);
                $success = true;
                $msg = 'Your services has been saved successfully';
            }else{
                $msg = 'Please select one or more services to add to invoice!!';
            }
        }
        return response()->json([
            'success' => $success,
            'msg' => $msg,
        ]);
    }
    //sdtServiceToInvoice
    public function sdtServiceToInvoice(Request $request,$invoice_id)
    {
        $data=$meta=[];
        if($invoice_id>0){
            $invoice = Invoice::select('id','patient_id','doctor_id')->where( 'id',$invoice_id )->first();
            if($invoice){
                $servicesItems = Procedureserviceitem::whereNull('invoice_id')->where([['patient_id',$invoice->patient_id],['doctor_id',$invoice->doctor_id]])->orderByDesc('id')->get();
                foreach ($servicesItems as $d) {
                    $row=array();
                    // <th>select</th>
                    $row[]='<label class="checkbox checkbox-single"><input type="checkbox" name="ids_items[]" value="'.$d->id.'" class="checkable"/><span></span></label>';                
                    // <th>Teeth</th>
                    $row[]=$d->teeth_id;
                    // <th>Service</th>
                    $row[]=$d->service->service_name;
                    // <th>Note</th>
                    $row[]=$d->note;
                    // <th>quantity</th>
                    $row[]=$d->quantity;
                    // <th>rate</th>
                    $row[]=$d->rate.'$';
                    // <th>total</th>
                    $row[]=$d->total.'$';
                    $data[]=$row;
                }
            }
        }    
        $result = [
            'data' => $data,
        ];
        return response()->json($result);
    }
    public function formPayment($payment_id,$invoice_id)
    {
        $payment = null;
        $amount=0;
        if($invoice_id>0){
            $DbHelperTools=new DbHelperTools();
            $calcul=$DbHelperTools->getAmountsInvoice($invoice_id);
            $amount=$calcul['nnf_total'];
            if($calcul['nnf_total_paid']>0){
                $amount= $amount-$calcul['nnf_total_paid'];
            }
        }
        if($payment_id>0){
            $payment = Invoicepayment::find ( $payment_id );
        }
        return view('profile.form.payment',compact('payment','invoice_id','amount'));
    }
    public function storeFormPayment(Request $request)
    {
        $success = false;
        $msg = 'Oops !';
        if ($request->isMethod('post')) {
            $DbHelperTools=new DbHelperTools();
            //dd($request->all());
            $payment_date = Carbon::createFromFormat('Y-m-d',$request->payment_date);
            $data = array(
                'id'=>$request->id,
                'amount'=>$request->amount,
                'payment_date'=>$payment_date,
                'payment_method'=>$request->payment_method,
                'note'=>$request->note,
                'invoice_id'=>$request->invoice_id,
            );
            $payment_id=$DbHelperTools->manageInvoicePayment($data);
            if($payment_id>0){
                $statusInvoice=$DbHelperTools->getStatusInvoice($request->invoice_id);
                Invoice::where('id', $request->invoice_id)->update(['status' => $statusInvoice]);
            }
            $success = true;
            $msg = 'Your payment has been saved successfully';   
        }
        return response()->json([
            'success' => $success,
            'msg' => $msg,
        ]);
    }
    public function getPatientStatsInvoice($patient_id){
        $DbHelperTools=new DbHelperTools();
        $stats=$DbHelperTools->getStatsBillingByPatient($patient_id);
        return response()->json($stats);
    }
    public function formRefund($refund_id,$invoice_id)
    {
        $refund = null;
        $amount=0;
        if($invoice_id>0){
            $invoice = Invoice::select('id','number')->where( 'id',$invoice_id )->first();
        }
        if($refund_id>0){
            $refund = Invoicerefund::find ( $refund_id );
        }
        return view('profile.form.refund',compact('refund','invoice_id','amount'));
    }
    public function storeFormRefund(Request $request)
    {
        $success = false;
        $msg = 'Oops !';
        if ($request->isMethod('post')) {
            $DbHelperTools=new DbHelperTools();
            //dd($request->all());
            $refund_date = Carbon::createFromFormat('Y-m-d',$request->refund_date);
            $data = array(
                'id'=>$request->id,
                'refund_code'=>($request->id==0)?$DbHelperTools->generateCodeForRefund():null,
                'amount'=>$request->amount,
                'reason'=>$request->reason,
                'refund_date'=>$refund_date,
                'invoice_id'=>$request->invoice_id,
            );
            //dd($data);
            $refund_id=$DbHelperTools->manageInvoiceRefund($data);
            $success = true;
            $msg = 'Your refund has been saved successfully';   
        }
        return response()->json([
            'success' => $success,
            'msg' => $msg,
        ]);
    }
}