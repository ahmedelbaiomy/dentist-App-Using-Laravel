<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Models\note;
use App\Models\User;
use App\Models\Xray;
use App\Models\Nurse;
use App\Models\Teeth;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Product;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Schedule;
use Carbon\CarbonPeriod;
use App\Models\Sprequest;
use App\Models\Officetime;
use App\Models\Appointment;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Exports\GlobalExport;
use App\Models\Invoicerefund;
use App\Models\Sprequestitem;
use App\Models\Invoicepayment;
use App\Models\Patientstorage;
use App\Library\Helpers\Helper;
use App\Models\Medicalcondition;
use App\Models\new_invoice_data;
use App\Models\service_category;
use Illuminate\Support\Facades\DB;
use App\Models\Procedureserviceitem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Library\Services\DbHelperTools;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

class AppController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['uploadFormXray']]);
    }

    public function patientProfile($patient_id)
    {
        $DbHelperTools=new DbHelperTools();
        $doctor_user_id=(Auth::user()->user_type=='doctor')?Auth::user()->id:0;
        $autorize=true;
        if($doctor_user_id>0){
            $autorize=$DbHelperTools->checkIfDoctorCanViewPatient($patient_id,$doctor_user_id);
        }
        //dd($autorize);
        if($autorize==false){
            abort(403, 'Unauthorized action.');
        }

        /* $services = DB::select("SELECT patient_notes.id, patient_notes.patient_id,patient_notes.doctor_id, patient_notes.teeth_id,patient_notes.invoiced, patient_notes.note,patient_notes.type, patient_notes.category_id, service_categories.name, services.price
                                FROM patient_notes
                                    LEFT JOIN service_categories on patient_notes.category_id = service_categories.id
                                    left join services on service_categories.id = services.category_id
                                    WHERE patient_notes.patient_id = ?
                                    GROUP BY patient_notes.id", [$patient_id]); */

        $categories =service_category::where('is_active',1)->get();
        $patient_data = DB::select("SELECT * FROM patients WHERE id=?", [$patient_id]);
        $name = explode(" ", $patient_data[0]->name);
        $short_name = "";
        //if(count($name) == 2)
        //   $short_name = $name[0][0].$name[1][0];
        //else if(count($name) == 1)
        //   $short_name = $short_name = $name[0][0].$name[0][1];



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

        $medical_conditions='';
        if(isset($patient_data[0]->medical_conditions)){
            $code_list=explode(';',$patient_data[0]->medical_conditions);
            if(count($code_list)>0){
                $arrayList=[];
                $rsList=Medicalcondition::whereIn('code',$code_list)->get();
                if(count($rsList)>0){
                    foreach($rsList as $m){
                        $arrayList[]=$m->en_name.' '.$m->ar_name;
                    }
                }
                if(count($arrayList)>0){
                    $medical_conditions=implode(" - ",$arrayList);
                }
            }

        }
        //$medical_conditions=$patient_data[0]->medical_conditions;

        return view('profile.patient.profile', compact('patient_id', 'datas','notes','categories', 'invoices', 'patient_data', 'short_name','total','paid','dept','medical_conditions'));
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
                'audio_file'=>(isset($resultPath) && !empty($resultPath))?base64_encode('uploads/'.$resultPath):null,
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
        $canDelete=(Auth::user()->user_type=='admin')?true:false;
        $data=$meta=[];
        $notes = note::where('patient_id',$patient_id)->orderByDesc('id')->get();
        foreach ($notes as $d) {
            $row=array();
                //ID
                $row[]='#NOTE-'.$d->id;
                //By
                $row[]=($d->user)?'<p class="mb-0"><span class="badge badge-light-primary">By : '.$d->user->name.'</span></p>':'';
                //<th>Description</th>
                $row[]=$d->note;
                //attachment
                $audio=(isset($d->audio_file))?'<audio style="width: 100%;" controls><source src="/'.base64_decode($d->audio_file).'" type="audio/wav"></audio>':'';
                $row[]=$audio;
                //<th>Created</th>
                $created='<p class="mb-0"><span class="badge badge-light-primary">Created at : '.$d->created_at->format('Y/m/d h:i:s').'</span></p>';
                $row[]=$created;
                //Actions
                $btn_delete='';
                if($canDelete)
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
                'user_id'=>auth()->user()->id,
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
        $canDelete=(Auth::user()->user_type=='admin')?true:false;
        $files = Patientstorage::where('patient_id',$patient_id)->orderByDesc('id')->get();
        foreach ($files as $d) {
            $row=array();
                //ID
                $row[]='#FILE-'.$d->id;
                //By
                $row[]='<p class="mb-0"><span class="badge badge-light-primary">By : '.$d->user->name.'</span></p>';
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
                $btn_delete='';
                if($canDelete)
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
        $patient=null;
        if ($patient_id > 0) {
           $patient = Patient::find ( $patient_id );
        }
        $categories =service_category::where('is_active',1)->get();
        return view('profile.construct.view',compact('viewtype','patient','patient_id','categories'));
    }
    public function sdtInvoices(Request $request,$patient_id)
    {
        $user_type=Auth::user()->user_type;
        $data=$meta=[];
        $DbHelperTools=new DbHelperTools();
        $notes = Invoice::where('patient_id',$patient_id)->orderByDesc('id')->get();
        foreach ($notes as $d) {
            $row=array();
            $row[]='<label class="checkbox checkbox-single"><input type="checkbox" value="'.$d->id.'" class="checkable"/><span></span></label>';
            // <th>Code</th>
            $dtBillDate = Carbon::createFromFormat('Y-m-d',$d->bill_date);
            $dtIssueDate = Carbon::createFromFormat('Y-m-d',$d->due_date);
            $bill='<span class="badge badge-light-secondary mr-1">Bill :'.$dtBillDate->format('Y-m-d').'</span>';
            $issue='<span class="badge badge-light-secondary">Issue :'.$dtIssueDate->format('Y-m-d').'</span>';
            $number='<p class="mb-0 text-primary">#'.$d->number.'</p>';

            //CHECK_CIRCLE
            $odoo_invoice_id=$d->odoo_id;
            $odoo_span='';
            if($odoo_invoice_id>0){
                //$odoo_span='<span class="badge badge-light-success mr-1">'.Helper::getSvgIconeByAction('CHECK_CIRCLE').' '.__('locale.exist_on_odoo').' - Id='.$odoo_invoice_id.'</span>';
            }

            $row[]=$number.$bill.$issue.$odoo_span;
            // <th>Doctor</th>
            $doctor='<span class="badge badge-light-secondary mr-1">Dr :'.$d->user->name.'</span>';
            $patient='<span class="badge badge-light-secondary">Pa :'.(($d->patient->ar_name)?$d->patient->ar_name:$d->patient->name).'</span>';
            $row[]=$doctor.$patient;
            // <th>Patient</th>
            //$row[]=$d->patient->name;
            // <th>Total</th>
            $calcul=$DbHelperTools->getAmountsInvoice($d->id);
            $total='<span class="badge badge-light-primary">'.$calcul['total'].' '.env('CURRENCY_SYMBOL').'</span> ';
            $refund=($calcul['total_refund']>0)?'<span class="badge badge-light-danger">Refund : '.$calcul['total_refund'].' '.env('CURRENCY_SYMBOL').'</span>':'';
            $row[]=$total.$refund;
            // <th>Paid</th>
            $row[]='<span class="badge badge-light-info">'.$calcul['total_paid'].' '.env('CURRENCY_SYMBOL').'</span> ';
            // <th>Status</th>
            $tabHelperType=Helper::getcssClassByType($d->status);
            $status='<span class="badge badge-light-'.$tabHelperType[0].'">'.$tabHelperType[1].'</span> ';
            $row[]=$status;
            // <th>Action</th>
            $btn_odoo='';
            if($odoo_invoice_id==null && $calcul['total']>0){
                //$btn_odoo='<a class="dropdown-item sort-asc" target="_blank" href="/api-odoo/sync-invoice.php?id='.$d->id.'" title="Synchronize invoice with odoo">'.Helper::getSvgIconeByAction('UPLOAD_CLOUD').' '.__('locale.odoo').'</a>';
            }
            $btn_delete='';
            if($user_type=='admin'){
                $btn_delete='<a class="dropdown-item sort-asc" href="javascript:void(0)" onclick="_deleteInvoice('.$d->id.')" title="Delete">'.Helper::getSvgIconeByAction('DELETE').' '.__('locale.delete').'</a>';
            }
            $btn_group_actions='<div class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle hide-arrow mr-1" id="invoiceActions"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        '.Helper::getSvgIconeByAction('MORE-VERTICAL').'
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="invoiceActions">
                        <a class="dropdown-item sort-asc" href="javascript:void(0)" onclick="_formInvoice('.$d->patient_id.','.$d->id.')">'.Helper::getSvgIconeByAction('EDIT').' '.__('locale.edit').'</a>
                        '.$btn_odoo.'
                        <a class="dropdown-item sort-asc" href="javascript:void(0)" onclick="_formPayment(0,'.$d->id.')">'.Helper::getSvgIconeByAction('DOLLAR').' '.__('locale.add_payment').'</a>
                        <a class="dropdown-item sort-asc" href="javascript:void(0)" onclick="_formRefund(0,'.$d->id.')">'.Helper::getSvgIconeByAction('TRENDING-DOWN').' '.__('locale.add_refund').'</a>
                        <a class="dropdown-item sort-asc" href="/profile/invoice/'.$d->id.'/preview">'.Helper::getSvgIconeByAction('VIEW').' '.__('locale.preview').'</a>
                        <a class="dropdown-item sort-asc" target="_blank" href="/profile/invoice/'.$d->id.'/print">'.Helper::getSvgIconeByAction('PRINT').' '.__('locale.print').'</a>
                        <a class="dropdown-item sort-asc" target="_blank" href="/profile/pdf/invoice/'.$d->id.'/stream">'.Helper::getSvgIconeByAction('FILE').' '.__('locale.pdf').'</a>
                        <a class="dropdown-item sort-asc" target="_blank" href="/profile/pdf/invoice/'.$d->id.'/download">'.Helper::getSvgIconeByAction('DOWNLOAD').' '.__('locale.download').'</a>
                        '.$btn_delete.'
                    </div>
            </div>';
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
        $invoice_id = 0;
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
                'discount_amount_type'=>($request->id==0)?'percentage':null,
                'discount_type'=>($request->id==0)?'before_tax':null,
                'status'=>'draft',
                'cancelled_at'=>null,
                'cancelled_by'=>null,
            );
            //dd($data);
            $invoice_id=$DbHelperTools->manageInvoice($data);
            $success = true;
            $msg = 'Your invoice has been saved successfully';
        }
        return response ()->json ( [
                'success' => $success,
                'msg' => $msg,
                'invoice_id' => $invoice_id
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
            //"discount_amount" => "200"
            //"discount_amount_type" => "percentage"
            if($request->id>0){

                if($request->discount_amount_type=="fixed_amount"){
                    $calcul=$DbHelperTools->getAmountsInvoice($request->id);
                    $amount=$calcul['nnf_total'];
                    if($request->discount_amount>$amount){
                        return response()->json([
                            'success' => $success,
                            //'msg' => 'The discount amount ('.number_format($request->discount_amount,2).' $) is more than invoice amount ('.number_format($amount,2).' $)',
                            'msg' => 'Discount amount cannot not be more than the invoice amount',
                        ]);
                    }
                }

                if($request->discount_amount_type=="percentage" && $request->discount_amount>100){
                    return response()->json([
                        'success' => $success,
                        'msg' => 'The discount percentage is more than 100%',
                    ]);
                }


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
                    $row[]=$d->rate.' '.env('CURRENCY_SYMBOL');
                    // <th>total</th>
                    $row[]=$d->total.' '.env('CURRENCY_SYMBOL');
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
            $arrayAmounts=$DbHelperTools->getAmountsInvoice($request->invoice_id);
            $total_invoice=$arrayAmounts['nnf_total'];
            $nnf_total_paid=$arrayAmounts['nnf_total_paid']+$request->amount;
            if($nnf_total_paid<=$total_invoice){
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
            }else{
                //$msg = 'Error ! Total payments amount ( '.$request->amount.'+'.number_format($arrayAmounts['nnf_total_paid'],2).'='.number_format($nnf_total_paid,2).' '.env('CURRENCY_SYMBOL').') are more than the invoce amount ('.number_format($total_invoice,2).' '.env('CURRENCY_SYMBOL').')';
                $msg = 'Error ! Total payments amount can not be more than the invoice amount';
            }

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

            $DbHelperTools=new DbHelperTools();
            $arrayAmounts=$DbHelperTools->getAmountsInvoice($request->invoice_id);
            $total_invoice=$arrayAmounts['nnf_total'];
            $total_refund=$arrayAmounts['total_refund']+$request->amount;
            if($total_refund<=$total_invoice){
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
            }else{
                $msg = 'Error ! Refund amount cannot be more than the invoice amount';
            }
        }
        return response()->json([
            'success' => $success,
            'msg' => $msg,
        ]);
    }
    public function previewInvoice($invoice_id,$mode)
    {
        $calcul=$items=$refunds=[];
        $invoice=$doctor=null;
        if($invoice_id>0){
            $invoice = Invoice::findOrFail( $invoice_id );
            if($invoice->doctor_id>0){
                $doctor = Doctor::where( 'user_id',$invoice->doctor_id )->first();
            }
            $DbHelperTools=new DbHelperTools();
            $calcul=$DbHelperTools->getAmountsInvoice($invoice_id);
            $items = Procedureserviceitem::where('invoice_id',$invoice_id)->get();
            //Refunds
            $refunds = Invoicerefund::where ( 'invoice_id',$invoice_id )->get();
        }
        return view('profile.invoice.preview',compact('invoice','doctor','calcul','items','refunds','mode'));
    }
    public function generateInvoicePdf($invoice_id,$mode)
    {
        $pdf_name='';
        $calcul=$items=$refunds=[];
        $invoice=$doctor=null;
        $last_payment_method='Cash';
        if($invoice_id>0){
            $invoice = Invoice::findOrFail( $invoice_id );
            $pdf_name = $invoice->number;
            if($invoice->doctor_id>0){
                $doctor = Doctor::where( 'user_id',$invoice->doctor_id )->first();
            }
            $DbHelperTools=new DbHelperTools();
            $calcul=$DbHelperTools->getAmountsInvoice($invoice_id);
            $items = Procedureserviceitem::where('invoice_id',$invoice_id)->get();
            //Refunds
            $refunds = Invoicerefund::where ( 'invoice_id',$invoice_id )->get();
            $rs_payment = Invoicepayment::select('payment_method')->where ( 'invoice_id',$invoice_id )->orderBy('id','DESC')->first();
            if($rs_payment){
                $last_payment_method =$rs_payment->payment_method;
            }
        }
        $configsPdf=[ 'title' => 'Certificate', 'format' => 'A4-L','orientation' => 'L'];
        $pdf=PDF::loadView('profile.invoice.pdf',compact('invoice','doctor','calcul','items','refunds','last_payment_method'),[],$configsPdf);
        if($mode=='stream'){
            return $pdf->stream($pdf_name.time().'.pdf');
        }else{
            return $pdf->download($pdf_name.time().'.pdf');
        }
    }
    public function settingsGeneral()
    {
        $DbHelperTools=new DbHelperTools();
        //app_title
        $app_title=$DbHelperTools->getSetting('app','app_title');
        //site_logo
        $defaultLogos=Helper::getDefaultLogos();
        $default_site_logo=$defaultLogos['logo'];
        $site_logo=$DbHelperTools->getSetting('app','site_logo');
        //favicon
        $default_favicon=$defaultLogos['favicon'];
        $favicon=$DbHelperTools->getSetting('app','favicon');
        //sidebar_logo
        $default_sidebar_logo=$defaultLogos['sidebar_logo'];
        $sidebar_logo=$DbHelperTools->getSetting('app','sidebar_logo');
        //show_logo_in_signin_page
        $show_logo_in_signin_page=$DbHelperTools->getSetting('app','show_logo_in_signin_page');
        //show_logo_in_signup_page
        $show_logo_in_signup_page=$DbHelperTools->getSetting('app','show_logo_in_signup_page');
        return view('admin.settings.general',compact('app_title','site_logo','default_site_logo','favicon','default_favicon','sidebar_logo','default_sidebar_logo','show_logo_in_signin_page','show_logo_in_signup_page'));
    }
    public function storeGeneralSetting(Request $request) {
		$success = false;
        $msg = 'Oops, something went wrong !';
        $id = 0;
        $siteLogoPath=$sideBarLogoPath=$siteFaviconPath='';
        if($request->hasFile('site_logo')){
            $uploadedFile = $request->file ( 'site_logo' );
            $original_name=time().'_logo_'.$uploadedFile->getClientOriginalName();
            //dd($original_name);

            $path = 'uploads/files/settings/';
            $filePath='files/settings/';
            if(!File::exists($path)) {
                File::makeDirectory($path, 0755, true, true);
            }
			$siteLogoPath=Storage::disk('public_uploads')->putFileAs ( $filePath, $uploadedFile, $original_name );
			$exists = Storage::disk ( 'public_uploads' )->exists ( $filePath."{$original_name}" );
			if(!$exists) {
				$siteLogoPath=null;
			}
        }
        if($request->hasFile('favicon')){
            $uploadedFile = $request->file ( 'favicon' );
            $original_name=time().'_favicon_'.$uploadedFile->getClientOriginalName();
            //dd($original_name);

            $path = 'uploads/files/settings/';
            $filePath='files/settings/';
            if(!File::exists($path)) {
                File::makeDirectory($path, 0755, true, true);
            }
			$siteFaviconPath=Storage::disk('public_uploads')->putFileAs ( $filePath, $uploadedFile, $original_name );
			$exists = Storage::disk ( 'public_uploads' )->exists ( $filePath."{$original_name}" );
			if(!$exists) {
				$siteFaviconPath=null;
			}
        }
        //sidebar_logo
        if($request->hasFile('sidebar_logo')){
            $uploadedFile = $request->file ( 'sidebar_logo' );
            $original_name=time().'_sidebar_logo_'.$uploadedFile->getClientOriginalName();
            //dd($original_name);

            $path = 'uploads/files/settings/';
            $filePath='files/settings/';
            if(!File::exists($path)) {
                File::makeDirectory($path, 0755, true, true);
            }
			$sideBarLogoPath=Storage::disk('public_uploads')->putFileAs ( $filePath, $uploadedFile, $original_name );
			$exists = Storage::disk ( 'public_uploads' )->exists ( $filePath."{$original_name}" );
			if(!$exists) {
				$sideBarLogoPath=null;
			}
        }
        if ($request->isMethod('post')) {
            $DbHelperTools=new DbHelperTools();
            $rs=$DbHelperTools->updateSetting('app','app_title',$request->app_title);
            if(isset($siteLogoPath) && !empty($siteLogoPath)){
                $siteLogoPath=(isset($siteLogoPath) && !empty($siteLogoPath))?'uploads/'.$siteLogoPath:$siteLogoPath;
                $rs=$DbHelperTools->updateSetting('app','site_logo',$siteLogoPath);
            }
            if(isset($sideBarLogoPath) && !empty($sideBarLogoPath)){
                $sideBarLogoPath=(isset($sideBarLogoPath) && !empty($sideBarLogoPath))?'uploads/'.$sideBarLogoPath:$sideBarLogoPath;
                $rs=$DbHelperTools->updateSetting('app','sidebar_logo',$sideBarLogoPath);
            }
            if(isset($siteFaviconPath) && !empty($siteFaviconPath)){
                $siteFaviconPath=(isset($siteFaviconPath) && !empty($siteFaviconPath))?'uploads/'.$siteFaviconPath:$siteFaviconPath;
                $rs=$DbHelperTools->updateSetting('app','favicon',$siteFaviconPath);
            }
            $rs=$DbHelperTools->updateSetting('app','show_logo_in_signin_page',$request->show_logo_in_signin_page);
            $rs=$DbHelperTools->updateSetting('app','show_logo_in_signup_page',$request->show_logo_in_signup_page);
            //dd($request->all());
            $success = true;
            $msg = 'You have successfully update settings.';
        }
        return response ()->json ( [
                'success' => $success,
                'msg' => $msg
        ] );
    }


    public function reports()
    {
        return view('admin.reports.index');
    }

    public function reportsStats(Request $request){
        $DbHelperTools=new DbHelperTools();
        $start=$end=null;
        $doctor_id=0;
        if ($request->isMethod('post')) {
            if ($request->has('doctor_id')) {
                $doctor_id=$request->doctor_id;
            }
            if ($request->has('filter_range')) {
                $tab=explode('to',$request->filter_range);
                if(count($tab)>0){
                    if(!empty($tab[0]) && !empty($tab[1])){
                        $start = trim($tab[0]);
                        $end = trim($tab[1]);
                    }
                }
            }
            if ($request->has('quick_type')) {
                $quick_type=$request->quick_type;
                if($quick_type=='today'){
                    $dtNow=Carbon::now();
                    $start=$dtNow->format('Y-m-d');
                    $end=$dtNow->format('Y-m-d');
                }
                if($quick_type=='yesterday'){
                    $yesterday = Carbon::yesterday();
                    $start=$yesterday->format('Y-m-d');
                    $end=$yesterday->format('Y-m-d');
                }
                if($quick_type=='this_month'){
                    $this_month = new Carbon('first day of this month');
                    $start=$this_month->format('Y-m-d');
                    $dtNow=Carbon::now();
                    $end=$dtNow->format('Y-m-d');
                }
                if($quick_type=='this_year'){
                    $dtNowA=Carbon::now();
                    $startOfYear = $dtNowA->copy()->startOfYear();
                    $start=$startOfYear->format('Y-m-d');
                    $dtNow=Carbon::now();
                    $end=$dtNow->format('Y-m-d');
                }
                if($quick_type=='last_7_days'){
                    $date = Carbon::today()->subDays(7);
                    $start=$date->format('Y-m-d');
                    $dtNow=Carbon::now();
                    $end=$dtNow->format('Y-m-d');
                    //dd($start);
                }
                if($quick_type=='last_30_days'){
                    $date = Carbon::today()->subDays(30);
                    $start=$date->format('Y-m-d');
                    $dtNow=Carbon::now();
                    $end=$dtNow->format('Y-m-d');
                    //dd($start);
                }
                if($quick_type=='last_month'){
                    $start_last_month = new Carbon('first day of last month');
                    $end_last_month = new Carbon('last day of last month');
                    $start=$start_last_month->format('Y-m-d');
                    $end=$end_last_month->format('Y-m-d');
                    //dd($end);
                }
            }
        }
        $results=$DbHelperTools->getReportStats($doctor_id,$start,$end);
        return response()->json($results);
    }

    public function getJsonFinancesApexChart(Request $request,$type_data){
        /*
        $type_data==0 ==> all datas
        $type_data==1 ==> show only production
        $type_data==2 ==> show only collection
        */
        $DbHelperTools=new DbHelperTools();
        //dd($request->all());
        $doctor_id=0;
        $start=$end=null;
        if ($request->isMethod('post')) {
            if ($request->has('doctor_id')) {
                $doctor_id=$request->doctor_id;
            }
            if ($request->has('filter_range')) {
                $tab=explode('to',$request->filter_range);
                if(count($tab)>0){
                    if(!empty($tab[0]) && !empty($tab[1])){
                        $start = trim($tab[0]);
                        $end = trim($tab[1]);
                    }
                }
            }
            if ($request->has('quick_type')) {
                $quick_type=$request->quick_type;
                if($quick_type=='today'){
                    $dtNow=Carbon::now();
                    $start=$dtNow->format('Y-m-d');
                    $end=$dtNow->format('Y-m-d');
                }
                if($quick_type=='yesterday'){
                    $yesterday = Carbon::yesterday();
                    $start=$yesterday->format('Y-m-d');
                    $end=$yesterday->format('Y-m-d');
                }
                if($quick_type=='this_month'){
                    $this_month = new Carbon('first day of this month');
                    $start=$this_month->format('Y-m-d');
                    $dtNow=Carbon::now();
                    $end=$dtNow->format('Y-m-d');
                }
                if($quick_type=='this_year'){
                    $dtNowA=Carbon::now();
                    $startOfYear = $dtNowA->copy()->startOfYear();
                    $start=$startOfYear->format('Y-m-d');
                    $dtNow=Carbon::now();
                    $end=$dtNow->format('Y-m-d');
                }
                if($quick_type=='last_7_days'){
                    $date = Carbon::today()->subDays(7);
                    $start=$date->format('Y-m-d');
                    $dtNow=Carbon::now();
                    $end=$dtNow->format('Y-m-d');
                    //dd($start);
                }
                if($quick_type=='last_30_days'){
                    $date = Carbon::today()->subDays(30);
                    $start=$date->format('Y-m-d');
                    $dtNow=Carbon::now();
                    $end=$dtNow->format('Y-m-d');
                    //dd($start);
                }
                if($quick_type=='last_month'){
                    $start_last_month = new Carbon('first day of last month');
                    $end_last_month = new Carbon('last day of last month');
                    $start=$start_last_month->format('Y-m-d');
                    $end=$end_last_month->format('Y-m-d');
                    //dd($end);
                }
            }
        }
        $periods = CarbonPeriod::create($start, $end);
        // Iterate over the period
        $production_datas=$collection_datas=[];
        foreach ($periods as $date) {
            $calcul=$DbHelperTools->getStatsForReports($doctor_id,$date->format('Y-m-d'),$date->format('Y-m-d'));
            if($type_data==0 || $type_data==1){
                $p_row=array();
                $p_row['x']=$date->format('m/d');
                $p_row['y']=$calcul['total_amount_invoices'];
                $production_datas[]=$p_row;
            }
            //
            if($type_data==0 || $type_data==2){
                $c_row=array();
                $c_row['x']=$date->format('m/d');
                $c_row['y']=$calcul['total_amount_payed_invoices'];
                $collection_datas[]=$c_row;
            }
        }
        //global stats
        $calcul = $DbHelperTools->getStatsForReports($doctor_id,$start, $end);
        $stats  = [
            'production'=>number_format($calcul['total_amount_invoices'],2).' '.env('CURRENCY_SYMBOL'),
            'collection'=>number_format($calcul['total_amount_payed_invoices'],2).' '.env('CURRENCY_SYMBOL'),
            'remaining' => number_format(($calcul['total_amount_invoices'] - $calcul['total_amount_payed_invoices']) , 2),
            'discounts' =>number_format($calcul['total_amount_discount'],2).' '.env('CURRENCY_SYMBOL'),
            'taxes'     =>number_format($calcul['total_tax_amount'],2).' '.env('CURRENCY_SYMBOL'),
            'cash'      =>Invoicepayment::where('payment_method','Cash')->sum('amount').' '.env('CURRENCY_SYMBOL'),
            'mada'      =>Invoicepayment::where('payment_method','Mada')->sum('amount').' '.env('CURRENCY_SYMBOL'),
            'credit'    =>Invoicepayment::where('payment_method','Credit card')->sum('amount').' '.env('CURRENCY_SYMBOL'),
        ];

        return response ()->json ([
            'production' => $production_datas,
            'collection' => $collection_datas,
            'stats' => $stats,
        ]);
    }

    public function getJsonAppointmentsApexChart(Request $request,$type_data){
        /*
        $type_data==0 ==> all datas
        $type_data==1 ==> show only booked
        $type_data==2 ==> show only confirmed
        $type_data==3 ==> show only canceled
        $type_data==4 ==> show only attended
        */
        $DbHelperTools=new DbHelperTools();
        //dd($request->all());
        $doctor_id=0;
        $start=$end=null;
        if ($request->isMethod('post')) {
            if ($request->has('doctor_id')) {
                $doctor_id=$request->doctor_id;
            }
            if ($request->has('filter_range')) {
                $tab=explode('to',$request->filter_range);
                if(count($tab)>0){
                    if(!empty($tab[0]) && !empty($tab[1])){
                        $start = trim($tab[0]);
                        $end = trim($tab[1]);
                    }
                }
            }
            if ($request->has('quick_type')) {
                $quick_type=$request->quick_type;
                if($quick_type=='today'){
                    $dtNow=Carbon::now();
                    $start=$dtNow->format('Y-m-d');
                    $end=$dtNow->format('Y-m-d');
                }
                if($quick_type=='yesterday'){
                    $yesterday = Carbon::yesterday();
                    $start=$yesterday->format('Y-m-d');
                    $end=$yesterday->format('Y-m-d');
                }
                if($quick_type=='this_month'){
                    $this_month = new Carbon('first day of this month');
                    $start=$this_month->format('Y-m-d');
                    $dtNow=Carbon::now();
                    $end=$dtNow->format('Y-m-d');
                }
                if($quick_type=='this_year'){
                    $dtNowA=Carbon::now();
                    $startOfYear = $dtNowA->copy()->startOfYear();
                    $start=$startOfYear->format('Y-m-d');
                    $dtNow=Carbon::now();
                    $end=$dtNow->format('Y-m-d');
                }
                if($quick_type=='last_7_days'){
                    $date = Carbon::today()->subDays(7);
                    $start=$date->format('Y-m-d');
                    $dtNow=Carbon::now();
                    $end=$dtNow->format('Y-m-d');
                    //dd($start);
                }
                if($quick_type=='last_30_days'){
                    $date = Carbon::today()->subDays(30);
                    $start=$date->format('Y-m-d');
                    $dtNow=Carbon::now();
                    $end=$dtNow->format('Y-m-d');
                    //dd($start);
                }
                if($quick_type=='last_month'){
                    $start_last_month = new Carbon('first day of last month');
                    $end_last_month = new Carbon('last day of last month');
                    $start=$start_last_month->format('Y-m-d');
                    $end=$end_last_month->format('Y-m-d');
                    //dd($end);
                }
            }
        }
        $periods = CarbonPeriod::create($start, $end);
        // Iterate over the period
        $booked_datas=$confirmed_datas=$canceled_datas=$attended_datas=[];
        foreach ($periods as $date) {
            $calcul=$DbHelperTools->getAppointmentsStatsForReports($doctor_id,$date->format('Y-m-d'),$date->format('Y-m-d'));
            //booked
            if($type_data==0 || $type_data==1){
                $p_row=array();
                $p_row['x']=$date->format('m/d');
                $p_row['y']=$calcul['nb_booked'];
                $booked_datas[]=$p_row;
            }
            //confirmed
            if($type_data==0 || $type_data==2){
                $c_row=array();
                $c_row['x']=$date->format('m/d');
                $c_row['y']=$calcul['nb_confirmed'];
                $confirmed_datas[]=$c_row;
            }
            //canceled
            if($type_data==0 || $type_data==3){
                $c_row=array();
                $c_row['x']=$date->format('m/d');
                $c_row['y']=$calcul['nb_canceled'];
                $canceled_datas[]=$c_row;
            }
            //attended
            if($type_data==0 || $type_data==4){
                $c_row=array();
                $c_row['x']=$date->format('m/d');
                $c_row['y']=$calcul['nb_attended'];
                $attended_datas[]=$c_row;
            }
        }
        //global stats
        $calcul=$DbHelperTools->getAppointmentsStatsForReports($doctor_id,$start,$end);
        $sum=$calcul['nb_booked']+$calcul['nb_confirmed']+$calcul['nb_canceled']+$calcul['nb_attended'];
        $percent=0;
        if($sum>0){
            $percent=($calcul['nb_canceled']/$sum)*100;
        }
        $stats=[
            'nb_booked'=>$calcul['nb_booked'],
            'nb_confirmed'=>$calcul['nb_confirmed'],
            'nb_canceled'=>$calcul['nb_canceled'],
            'percent_canceled'=>number_format($percent,2),
            'nb_attended'=>$calcul['nb_attended'],
        ];
        return response ()->json ([
            'booked' => $booked_datas,
            'confirmed' => $confirmed_datas,
            'canceled' => $canceled_datas,
            'attended' => $attended_datas,
            'stats' => $stats,
        ]);
    }
    public function generatePatiantsReportPdf($doctor_id,$mode)
    {
        $pdf_name='report';
        $bills=[];
        // $Total=[];
        $states_array=[];
        $doctor=null;
        $max_rows=10;
        $nb_empty_rows=0;
        //booked & confirmed
        $today = Carbon::now();

        if($doctor_id>0){
            $DbHelperTools=new DbHelperTools();
            $doctor=User::select('name')->where('id',$doctor_id)->first();


            $bills = DB::select("SELECT inv_invoices.number AS Bill_Number, patients.name AS Patient, services.service_name, inv_invoice_payments.amount AS Paid, pr_procedure_service_items.total AS Amount, inv_invoice_refunds.amount AS refund, inv_invoices.bill_date, inv_invoices.due_date, users.name AS doctor FROM inv_invoices
            LEFT JOIN patients ON inv_invoices.patient_id = patients.id
            LEFT JOIN pr_procedure_service_items ON pr_procedure_service_items.invoice_id = inv_invoices.id
            LEFT JOIN services ON pr_procedure_service_items.service_id = services.id
            LEFT JOIN inv_invoice_payments ON inv_invoices.id = inv_invoice_payments.invoice_id
            LEFT JOIN inv_invoice_refunds ON inv_invoices.id = inv_invoice_refunds.invoice_id
            LEFT JOIN users ON users.id = inv_invoices.doctor_id
            WHERE inv_invoices.doctor_id = $doctor_id;
        ");

            $Total=DB::select("SELECT sum(inv_invoice_payments.amount) AS TOTAL_AMOUNT, sum(pr_procedure_service_items.total) AS TOTAL_PAID, sum(inv_invoice_refunds.amount) AS TOTAL_REFUND FROM inv_invoices

            LEFT JOIN pr_procedure_service_items ON inv_invoices.id = pr_procedure_service_items.invoice_id
            LEFT JOIN inv_invoice_refunds ON inv_invoices.id = inv_invoice_refunds.invoice_id
            LEFT JOIN inv_invoice_payments ON inv_invoices.id = inv_invoice_payments.invoice_id
            WHERE inv_invoices.doctor_id = $doctor_id;
            ");

        }else{
            $DbHelperTools=new DbHelperTools();

            $bills = DB::select("SELECT inv_invoices.number AS Bill_Number, patients.name AS Patient, services.service_name, inv_invoice_payments.amount AS Paid, pr_procedure_service_items.total AS Amount, inv_invoice_refunds.amount AS Refund, inv_invoices.bill_date,inv_invoices.due_date, users.name AS doctor FROM inv_invoices
            LEFT JOIN patients ON inv_invoices.patient_id = patients.id
            LEFT JOIN pr_procedure_service_items ON pr_procedure_service_items.invoice_id = inv_invoices.id
            LEFT JOIN services ON pr_procedure_service_items.service_id = services.id
            LEFT JOIN inv_invoice_payments ON inv_invoices.id = inv_invoice_payments.invoice_id
            LEFT JOIN inv_invoice_refunds ON inv_invoices.id = inv_invoice_refunds.invoice_id
            LEFT JOIN users ON users.id = inv_invoices.doctor_id
        ");

            $Total=DB::select("SELECT sum(inv_invoice_payments.amount) AS TOTAL_AMOUNT, sum(pr_procedure_service_items.total) AS TOTAL_PAID, sum(inv_invoice_refunds.amount) AS TOTAL_REFUND FROM inv_invoice_payments
            LEFT JOIN pr_procedure_service_items ON inv_invoice_payments.invoice_id = pr_procedure_service_items.invoice_id
            LEFT JOIN inv_invoice_refunds ON inv_invoice_refunds.invoice_id = inv_invoice_payments.invoice_id
            ");

            // dd($Total);
            // $appointments = Appointment::whereIn('status',$statusAppointment)->whereBetween('start_time', [$today->format('Y-m-d')." 00:00:00", $today->format('Y-m-d')." 23:59:59"])->get();
            // $appointments = Appointment::whereIn('status',$statusAppointment)->whereBetween('start_time', [$today->format('Y-m-d')." 00:00:00", $today->format('Y-m-d')." 23:59:59"])->get();
        }

        if(count($bills)<=$max_rows){
            $nb_empty_rows=$max_rows-count($bills);
        }

        // if(count($appointments)>0){
        //     foreach($appointments as $apt){
        //         $state=$DbHelperTools->checkPatientIfIsNewOrUsual($apt->patient->id);
        //         $states_array[$apt->patient->id]=$state;
        //     }
        // }


        $pdf=PDF::loadView('pdf.doctor_invoices',compact('bills','doctor','nb_empty_rows','today','Total'),[],
        [
          'title' => 'Certificate',
          'format' => 'A4-L',
          'orientation' => 'L'
        ]);
        if($mode==1){
            return $pdf->stream($pdf_name.time().'.pdf');
        }
        return $pdf->download($pdf_name.time().'.pdf');
    }


    public function generateDailyDoctorsReportPdf($doctor_id,$mode)
    {
        $pdf_name='daily';
        $appointments=[];
        $states_array=[];
        $doctor=null;
        $max_rows=8;
        $nb_empty_rows=0;
        //booked & confirmed
        $statusAppointment=[1,2];
        $today = Carbon::now();
        if($doctor_id>0){
            $DbHelperTools=new DbHelperTools();
            $doctor=User::select('name')->where('id',$doctor_id)->first();
            $appointments = Appointment::where('doctor_id',$doctor_id)->whereIn('status',$statusAppointment)->whereBetween('start_time', [$today->format('Y-m-d')." 00:00:00", $today->format('Y-m-d')." 23:59:59"])->get();
        }else{
            $DbHelperTools=new DbHelperTools();
            $appointments = Appointment::whereIn('status',$statusAppointment)->whereBetween('start_time', [$today->format('Y-m-d')." 00:00:00", $today->format('Y-m-d')." 23:59:59"])->get();
        }


        if(count($appointments)>0){
            foreach($appointments as $apt){
                $state=$DbHelperTools->checkPatientIfIsNewOrUsual($apt->patient->id);
                $states_array[$apt->patient->id]=$state;
            }
        }

        if(count($appointments)<=$max_rows){
            $nb_empty_rows=$max_rows-count($appointments);
        }

        $pdf=PDF::loadView('pdf.report.daily-doctor-report',compact('appointments','doctor','states_array','nb_empty_rows','today'),[],
        [
          'title' => 'Certificate',
          'format' => 'A4-L',
          'orientation' => 'L'
        ]);
        if($mode==1){
            return $pdf->stream($pdf_name.time().'.pdf');
        }
        return $pdf->download($pdf_name.time().'.pdf');
    }

    public function logs()
    {
        return view('activitylog.logs');
    }
    public function sdtLogs(Request $request)
    {
        //$tools=new PublicTools();
        $dtRequests = $request->all();
        $data=$meta=[];
        $cssArray=array(
            'created'=>'success',
            'updated'=>'warning',
            'deleted'=>'danger',
        );
        $datas=Activity::orderByDesc('id')->get();
        foreach ($datas as $d) {
            $row=array();
            $cssClass=$cssArray[$d->description];
            //<th>Date</th>
            $row[]='<span class="badge badge-light-'.$cssClass.'">'.$d->created_at->format('Y/m/d H:i').'</span>';
            //<th>Who</th>
            $row[]=$d->causer->name;
            //<th>Activity</th>
            $row[]='<span class="badge badge-light-'.$cssClass.'">'.$d->log_name.'</span> number <span class="badge badge-light-'.$cssClass.'">'.$d->subject_id.'</span> has been <span class="badge badge-light-'.$cssClass.'">'.$d->description.'</span> by <span class="badge badge-light-'.$cssClass.'">'.$d->causer->name.'</span>';
            //<th>Actions</th>
            $btn_delete='<button class="btn btn-icon btn-outline-danger" onclick="_deleteLog('.$d->id.')" title="Delete">'.Helper::getSvgIconeByAction('DELETE').'</button>';
            $row[]=$btn_delete;
            $data[]=$row;
        }
        $result = [
            'data' => $data,
        ];
        return response()->json($result);
    }
    public function deleteLog($id){
        /**
         * forceDelete
         */
        $success = false;
        $DbHelperTools=new DbHelperTools();
        if($id>0){
            //delete from database
            $deletedRows = $DbHelperTools->massDeletes([$id],'log',0);
            if($deletedRows>0){
              $success = true;
            }
        }else{
            $deletedRows =Activity::truncate();
            $success = true;
        }
        return response()->json(['success'=>$success]);
    }
    public function requests()
    {
        return view('request.requests');
    }


    /* ---------------------------------------------------Changes Made By Abhishek------------------------------------------------------------------------------*/
        public function doc_sdtRequests(Request $request)
    {
        $data=$meta=[];
        	$doctor_user_id=(Auth::user()->user_type=='doctor')?Auth::user()->id:0;
        $requests = DB::select("SELECT sp_request_items.*, sp_requests.user_id,new_invoice_table.status,new_invoice_table.invoice_number, new_invoice_table.id AS invoiceId FROM sp_requests
                                     JOIN sp_request_items on sp_requests.id = sp_request_items.request_id LEFT JOIN new_invoice_table on sp_requests.id=new_invoice_table.request_id WHERE  sp_requests.user_id=$doctor_user_id ORDER BY sp_request_items.id DESC");
                                       //  echo "<pre>";
        // print_r( $services);die;

        // $requests = Sprequestitem::leftjoin('doctors','doctors.id','=','sp_request_items.request_id')
        //  // ->leftjoin('users','users.id','=','doctors.user_id')
        //  //   ->select('sp_request_items.*','users.name')
        //      ->select('sp_request_items.*','doctors.user_id')
        //     // ->DB::select('SELECT users.name from users where doctors.user_id=users.id')
        //     ->orderByDesc('sp_request_items.id')->get();
        //     echo "<pre>";
        // echo "<pre>";
        //  print_r( $requests);die;

        $cssArray=array(
            'draft'=>'warning',
            'sent'=>'success',
        );
        foreach ($requests as $d) {
            $row=array();
                //ID
                $row[]='#REQUEST-'.$d->product_name;
                 $row[]='<p class="mb-0"><span class="badge badge-light-primary">'.$d->invoice_number.'</span></p>';
                //To
                $row[]='<p class="mb-0"><span class="badge badge-light-primary">'.$d->description.'</span></p>';
                //<th>Subject</th>
                $row[]=$d->quantity;
                //<th>Status</th>
                $row[]='<span class="badge badge-light-primary">'.$d->rate.' SAR'.'</span>';
                  $row[]='<span class="badge badge-light-primary">'.$d->total.' SAR'.'</span>';
                   //$row[]='<span class="badge badge-light-primary">'.$this->getDoctorName($d->user_id).'</span>';


                  $row[]='<span class="badge badge-light-primary" id="status_'.$d->invoice_number.'">'.($d->status=='draft' ? 'pending': ($d->status=='' ? '' : 'paid')).'</span>';
                //<th>Sent at</th>
                // $sent='';
                // if($d->sent_at){
                //     $dtSentDate = Carbon::createFromFormat('Y-m-d H:i:s',$d->sent_at);
                //     $sent='<span class="badge badge-light-success">'.$dtSentDate->format('Y-m-d H:i:s').'</span>';
                // }
                // $row[]=$sent;
                // //<th>Created</th>
                // $created='<p class="mb-0"><span class="badge badge-light-primary">'.$d->created_at->format('Y/m/d h:i:s').'</span></p>';
                // $row[]=$created;
                //Actions
               // $btn_delete='<button class="btn btn-icon btn-outline-danger" onclick="_deleteRequest('.$d->id.')" title="Delete">'.Helper::getSvgIconeByAction('DELETE').'</button>';
$btn_delete= $d->status=='' ? '' :  '<label class="switch"><input class="status" type="checkbox" '.($d->status=='draft' ? '': ($d->status=='' ? '' : 'checked disabled')).'><span class="slider" onclick="_changeStatus(this,'.$d->invoice_number.','.$d->invoiceId.')"></span></label>';
                //$row[]=$btn_view.$btn_delete;

            $row[]=$btn_delete;
            $data[]=$row;
        }
       // print_r($data);die;
        $result = [
            'data' => $data,
        ];
        return response()->json($result);
    }
    public function sdtRequests(Request $request)


    {


        $data=$meta=[];
        $requests = DB::select("SELECT sp_request_items.*, sp_requests.user_id,new_invoice_table.status,new_invoice_table.invoice_number, new_invoice_table.id AS invoiceId FROM sp_requests
                                     JOIN sp_request_items on sp_requests.id = sp_request_items.request_id LEFT JOIN new_invoice_table on sp_requests.id=new_invoice_table.request_id  ORDER BY sp_request_items.id DESC");
                                       //  echo "<pre>";
        // print_r( $services);die;

        // $requests = Sprequestitem::leftjoin('doctors','doctors.id','=','sp_request_items.request_id')
        //  // ->leftjoin('users','users.id','=','doctors.user_id')
        //  //   ->select('sp_request_items.*','users.name')
        //      ->select('sp_request_items.*','doctors.user_id')
        //     // ->DB::select('SELECT users.name from users where doctors.user_id=users.id')
        //     ->orderByDesc('sp_request_items.id')->get();
        //     echo "<pre>";
        // echo "<pre>";
        //  print_r( $requests);die;

        $cssArray=array(
            'draft'=>'warning',
            'sent'=>'success',
        );
        foreach ($requests as $d) {
            $row=array();
                //ID
                $row[]='#REQUEST-'.$d->product_name;
                     $row[]='<p class="mb-0"><span class="badge badge-light-primary">'.$d->invoice_number.'</span></p>';
                //To
                $row[]='<p class="mb-0"><span class="badge badge-light-primary">'.$d->description.'</span></p>';
                //<th>Subject</th>
                $row[]=$d->quantity;
                //<th>Status</th>
                $row[]='<span class="badge badge-light-primary">'.$d->rate.' SAR'.'</span>';
                  $row[]='<span class="badge badge-light-primary">'.$d->total.' SAR'.'</span>';
                   $row[]='<span class="badge badge-light-primary">'.$this->getDoctorName($d->user_id).'</span>';

                  $row[]='<span  class="badge badge-light-primary status_'.$d->invoice_number.'">'.($d->status=='draft' ? 'pending': ($d->status=='' ? '' : 'paid')).'</span>';
                //<th>Sent at</th>
                // $sent='';
                // if($d->sent_at){
                //     $dtSentDate = Carbon::createFromFormat('Y-m-d H:i:s',$d->sent_at);
                //     $sent='<span class="badge badge-light-success">'.$dtSentDate->format('Y-m-d H:i:s').'</span>';
                // }
                // $row[]=$sent;
                // //<th>Created</th>
                // $created='<p class="mb-0"><span class="badge badge-light-primary">'.$d->created_at->format('Y/m/d h:i:s').'</span></p>';
                // $row[]=$created;
                //Actions
               // $btn_delete='<button class="btn btn-icon btn-outline-danger" onclick="_deleteRequest('.$d->id.')" title="Delete">'.Helper::getSvgIconeByAction('DELETE').'</button>';
$btn_delete= $d->status=='' ? '' :  '<label class="switch"><input class="status" type="checkbox" '.($d->status=='draft' ? '': ($d->status=='' ? '' : 'checked disabled')).'><span class="slider" onclick="_changeStatus(this,'.$d->invoice_number.','.$d->invoiceId.')"></span></label>';
                //$row[]=$btn_view.$btn_delete;
                 $row[]=$btn_delete;
            $data[]=$row;
        }
       // print_r($data);die;
        $result = [
            'data' => $data,
        ];
        return response()->json($result);
    }


        function cURL($url) {

        // Create a new cURL resource
        $curl = curl_init();

        if (!$curl) {
            die("Couldn't initialize a cURL handle");
        }

        // Set the file URL to fetch through cURL
        curl_setopt($curl, CURLOPT_URL, $url);

        // Set a different user agent string (Googlebot)
        curl_setopt($curl, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)');



        // Return the actual result of the curl result instead of success code
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // Wait for 10 seconds to connect, set 0 to wait indefinitely
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);

        // Execute the cURL request for a maximum of 50 seconds
        curl_setopt($curl, CURLOPT_TIMEOUT, 50);

        // Do not check the SSL certificates
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        // Fetch the URL and save the content in $html variable
        $html = curl_exec($curl);

        // Check if any error has occurred
        if (curl_errno($curl))
        {
          //  echo 'cURL error: ' . curl_error($curl);
        }
        else
        {
            // cURL executed successfully
          //  print_r(curl_getinfo($curl));
        }

        // close cURL resource to free up system resources
        curl_close($curl);
    }

      public function getDoctorName($user_id){
         $doctor = DB::select("SELECT name FROM users WHERE id=?", [$user_id]);

        return $doctor ? $doctor[0]->name : '' ;

    }
       public function storeFormRequestDoctor(Request $request) {
		$success = false;
        $msg = 'Oops, something went wrong !';

        $request_id = 0;
          $DbHelperTools=new DbHelperTools();
           $invoice = ($request->id==0)? rand(1111111111,9999999999):null;
      //  $invoice = ($request->id==0)?$DbHelperTools->generateInvoiceNumber('INVOICE'):null;
        if ($request->isMethod('post')) {

            //dd($request->all());
            $data = array(
                'id'=>$request->id,
                'to'=>$request->to,
                'cc'=>null,
                'bcc'=>null,
                'subject'=>$request->subject,
                'message'=>$request->message,
                'sent_at'=>null,
                'status'=>'draft',
                'user_id'=>auth()->user()->id,
            );
            //dd($data);
            $request_id=$DbHelperTools->manageRequest($data);
            if($request_id>0){
                $items=$request->products;
                if(count($items)>0){
                    foreach($items as $item){
                        $product = Product::find ( $item['product_id']);
                        $rate=$product->price;
                        $data_item=array(
                            'id'=>0,
                            'product_name'=>$product->name,
                            'quantity'=>$item['quantity'],
                            'rate'=>$rate,
                            'total'=>$rate*$item['quantity'],
                            'description'=>$item['description'],
                            'product_id'=>$item['product_id'],
                            'request_id'=>$request_id,
                        );

                         $products_add = array(
                            'note'=>$item['description'],
                            'invoice_number'=>$invoice,
                            'request_id'=>$request->id,
                             'product_id'=>$item['product_id'],
                            'quantity'=>$item['quantity'],
                            'rate'=>$rate,
                            'total'=>$rate*$item['quantity']
                            );
                        //dd($data_item);
                        $item_id=$DbHelperTools->manageRequestItem($data_item);
                      $products_save=$DbHelperTools->manageNewProductsItem($products_add);


                    }
                       $invoice_new = array(

                            'invoice_number'=>$invoice,
                            'login_id'=>Auth()->user()->id,
                            'bill_date'=>date('Y-m-d'),
                            'due_date'=>date('Y-m-d'),
                            //'note'=>$item['description'],
                            'status'=>'draft',
                            //'cancelled_at'=>date('Y-m-d'),
                            //'cancelled_by'=>date('Y-m-d'),
                            //'deleted_at'=>date('Y-m-d'),
                            //'created_at'=>date('Y-m-d'),
                            //'updated_at'=>date('Y-m-d'),
                            'odoo_id'=>123,
                            'request_id'=>$request_id
                            //'quantity'=>$item['quantity'],
                            //'rate'=>$rate,
                            //'total'=>$rate*$item['quantity']
                            );

                         $item_id1=$DbHelperTools->manageNewInvoiceItem($invoice_new);
                        $this->cURL(url('/')."/api-odoo/sync-requests.php?id=". $item_id1);
                }
                // $isSent=$DbHelperTools->sendEmailRequest($request_id);
                // if($isSent){
                //     $request = Sprequest::find ( $request_id );
                //     $request->sent_at = Carbon::now();
                //     $request->status = 'sent';
                //     $request->save ();
                // }
            }
            $success = true;
            $msg = 'Your request has been saved successfully';
        }
        return response ()->json ( [
                'success' => $success,
                'msg' => $msg,
                'request_id' => $request_id
        ] );
    }
    public function storeFormRequest(Request $request) {
        $DbHelperTools=new DbHelperTools();
		$success = false;
		$invoice_new='';
        $msg = 'Oops, something went wrong !';
        $invoice = ($request->id==0)? rand(1111111111,9999999999):null;;
        $request_id = 0;
        if ($request->isMethod('post')) {

            //dd($request->all());
            $data = array(
                'id'=>$request->id,
                'to'=>$request->to,
                'cc'=>null,
                'bcc'=>null,
                'subject'=>$request->subject,
                'message'=>$request->message,
                'sent_at'=>null,
                'status'=>'draft',
                'user_id'=>auth()->user()->id,
            );
            //dd($data);
            $request_id=$DbHelperTools->manageRequest($data);
            if($request_id>0){
                $items=$request->products;
                if(count($items)>0){
                    foreach($items as $item){
                        $product = Product::find ( $item['product_id']);
                        $rate=$product->price;
                        $data_item=array(
                            'id'=>0,
                            'product_name'=>$product->name,
                            'quantity'=>$item['quantity'],
                            'rate'=>$rate,
                            'total'=>$rate*$item['quantity'],
                            'description'=>$item['description'],
                            'product_id'=>$item['product_id'],
                            'request_id'=>$request_id,
                        );

                        $products_add = array(
                            'note'=>$item['description'],
                            'invoice_number'=>$invoice,
                            'request_id'=>$request->id,
                             'product_id'=>$item['product_id'],
                            'quantity'=>$item['quantity'],
                            'rate'=>$rate,
                            'total'=>$rate*$item['quantity']
                            );

                        //dd($data_item);
                        $item_id=$DbHelperTools->manageRequestItem($data_item);


                        $products_save=$DbHelperTools->manageNewProductsItem($products_add);


                    }
                       $invoice_new = array(

                            'invoice_number'=>$invoice,
                            'login_id'=>Auth()->user()->id,
                            'bill_date'=>date('Y-m-d'),
                            'due_date'=>date('Y-m-d'),
                            //'note'=>$item['description'],
                            'status'=>'draft',
                            //'cancelled_at'=>date('Y-m-d'),
                            //'cancelled_by'=>date('Y-m-d'),
                            //'deleted_at'=>date('Y-m-d'),
                            //'created_at'=>date('Y-m-d'),
                            //'updated_at'=>date('Y-m-d'),
                            'odoo_id'=>123,
                            'request_id'=>$request_id
                            //'quantity'=>$item['quantity'],
                            //'rate'=>$rate,
                            //'total'=>$rate*$item['quantity']
                            );

                         $item_id1=$DbHelperTools->manageNewInvoiceItem($invoice_new);

                         if($item_id1){
                          $this->cURL(url('/')."/api-odoo/sync-requests.php?id=". $item_id1);
                         }
                }
                // $isSent=$DbHelperTools->sendEmailRequest($request_id);
                // if($isSent){
                //     $request = Sprequest::find ( $request_id );
                //     $request->sent_at = Carbon::now();
                //     $request->status = 'sent';
                //     $request->save ();
                // }
            }
            $success = true;
            $msg = 'Your request has been saved successfully';
        }
        return response ()->json ( [
                'success' => $success,
                'msg' => $msg,
                'request_id' => $request_id
        ] );
    }

    public function changedStatus(Request $req){

        //print_r($req->all());die;

     $update = new_invoice_data::whereIn('invoice_number', [$req->invoice_number])->update(['status' => $req->status]);

      $this->cURL(url('/')."/api-odoo/sync-requests.php?id=". $req->invoice_id);



         return $update ? 1 : 0;


    }

       /* ---------------------------------------------------End Changes Made By Abhishek------------------------------------------------------------------------------*/
    public function formRequest($request_id){
        $request=$products=null;
        if ($request_id > 0) {
                $request = Sprequest::find ( $request_id );
        }
        $products = Product::all();
        return view('request.form',compact('request','products'));
    }

    public function getPriceProduct($product_id){
        $price=0;
        if($product_id>0){
            $row=Product::select('id','price')->where('id',$product_id)->first();
            $price=$row['price'];
        }
        return response()->json(['price' => $price]);
    }
    public function deleteRequest($id){
        $success = false;
        $DbHelperTools=new DbHelperTools();
        if($id>0){
            $deletedRows = $DbHelperTools->massDeletes([$id],'request',0);
            if($deletedRows>0){
              $success = true;
            }
        }
        return response()->json(['success'=>$success]);
    }
    public function viewRequest($request_id){
        $request=null;
        $items=[];
        $total=0;
        $DbHelperTools=new DbHelperTools();
        if ($request_id > 0) {
                $request = Sprequest::findOrFail ( $request_id );
                $items = Sprequestitem::where('request_id',$request_id)->get();
                $total=$DbHelperTools->getAmountsRequestItems($request_id);
        }
        return view('request.details',compact('request','items','total'));
    }
    public function deleteUser($id){
        $success = false;
        $DbHelperTools=new DbHelperTools();
        if($id>0){
            //sp_request_items
            $idsRequests=Sprequest::select('id')->where('user_id', $id)->pluck('id');
            if(count($idsRequests)>0){
                $idSprequestitem=Sprequestitem::whereIn('request_id', $idsRequests)->pluck('id');
                if(count($idSprequestitem)>0){
                    Sprequestitem::whereIn('id', $idSprequestitem)->forceDelete();
                }
                //sp_requests
                Sprequest::select('id')->where('user_id', $id)->forceDelete();
            }
            //pr_procedure_service_items
            Procedureserviceitem::where('doctor_id', $id)->forceDelete();
            //inv_invoice_payments
            $idsInvoices=Invoice::select('id')->where('doctor_id', $id)->pluck('id');
            //dd($idsInvoices);
            if(count($idsInvoices)>0){
                Invoicepayment::whereIn('invoice_id', $idsInvoices)->forceDelete();
                Invoicerefund::whereIn('invoice_id', $idsInvoices)->forceDelete();
            }
            Invoice::select('id')->where('doctor_id', $id)->forceDelete();
            //patient_storage
            Patientstorage::where('user_id', $id)->forceDelete();
            //officetimes
            Officetime::where('user_id', $id)->forceDelete();
            //nurses
            Nurse::where('user_id', $id)->forceDelete();
            //notes
            note::where('user_id', $id)->forceDelete();
            //doctor_schedules
            Schedule::where('doctor_id', $id)->forceDelete();
            //doctors
            Doctor::where('user_id', $id)->forceDelete();
            //appointments
            Appointment::where('doctor_id', $id)->forceDelete();
            //activity_log
            Activity::where('causer_id', $id)->forceDelete();
            //user
            User::where('id', $id)->forceDelete();
            $success = true;
        }
        return response()->json(['success'=>$success]);
    }
    public function deleteProcedureServiceItem($id){
        $success = false;
        $DbHelperTools=new DbHelperTools();
        if($id>0){
            $deletedRows = $DbHelperTools->massDeletes([$id],'procedureserviceitem',0);
            if($deletedRows>0){
              $success = true;
            }
        }
        return response()->json(['success'=>$success]);
    }
    public function deleteAppointment($id){
        $success = false;
        $DbHelperTools=new DbHelperTools();
        if($id>0){
            $deletedRows = $DbHelperTools->massDeletes([$id],'appointment',0);
            if($deletedRows>0){
              $success = true;
            }
        }
        return response()->json(['success'=>$success]);
    }
    public function deleteOfficetime($id){
        $success = false;
        $DbHelperTools=new DbHelperTools();
        if($id>0){
            $deletedRows = $DbHelperTools->massDeletes([$id],'officetime',0);
            if($deletedRows>0){
              $success = true;
            }
        }
        return response()->json(['success'=>$success]);
    }
    public function formDoctor($doctor_id){
        $doctor=null;
        if ($doctor_id > 0) {
            $doctor = Doctor::find ( $doctor_id );
        }
        return view('admin.form.doctor',compact('doctor'));
    }
    public function storeFormDoctor(Request $request) {
		$success = false;
        $msg = 'Oops, something went wrong !';
        $id = 0;
        $resultPath='';
        if ($request->isMethod('post')) {
            //dd($request->all());
                $DbHelperTools=new DbHelperTools();
                $user_id=$request->user_id;

                $user=User::find($user_id);
                if(!$user){
                    $user = new User();
                }
                $user->name =$request->name;
                $user->username =$request->username;
                $user->state =$request->state;
                $user->save ();
                $user_id = $user->id;
                if($user_id>0){
                    $birthday_date =null;
                    if ($request->has('birthday') && isset($request->birthday)) {
                        $birthday_date = Carbon::createFromFormat('Y-m-d',$request->birthday);
                    }
                    $data = array(
                        'id'=>$request->id,
                        'birthday'=>$birthday_date,
                        'phone'=>$request->phone,
                        'target'=>$request->target,
                        'address'=>$request->address,
                        'user_id'=>$user_id,
                    );
                    $doctor_id=$DbHelperTools->manageDoctor($data);
                }
            $success = true;
            $msg = 'Your service have been saved successfully';
        }
        return response ()->json ( [
                'success' => $success,
                'msg' => $msg
        ] );
    }
    public function generateProcedureServiceItemsPdf($doctor_id,$patient_id,$idsString,$mode)
    {

        $idsProcedureServiceItems = ($idsString)?json_decode(base64_decode($idsString)):null;
        //dd($idsProcedureServiceItems);
        $procedure_service_items=null;
        if(count($idsProcedureServiceItems)>0){
            $procedure_service_items=Procedureserviceitem::whereIn('id',$idsProcedureServiceItems)->get();
        }
        //

        $pdf_name='procedures';
        $max_rows=6;
        $nb_empty_rows=0;
        $today = Carbon::now();

        $doctor=null;
        if($doctor_id>0){
            $doctor=User::select('name')->where('id',$doctor_id)->first();
        }
        $patient=null;
        if($patient_id>0){
            $patient=Patient::select('name','ar_name')->where('id',$patient_id)->first();
        }

        /* if($procedure_service_item_id>0){
            $procedure_service_items=Procedureserviceitem::where('id',$procedure_service_item_id)->get();
        }else{
            $procedure_service_items=Procedureserviceitem::where([['patient_id',$patient_id],['doctor_id',$doctor_id]])->get();
        } */

        if($procedure_service_items && count($procedure_service_items)<=$max_rows){
            $nb_empty_rows=$max_rows-count($procedure_service_items);
        }

        $pdf=PDF::loadView('pdf.procedure.procedure-items',compact('procedure_service_items','doctor','patient','today','nb_empty_rows'),[],
        [
          'title' => 'Certificate',
          'format' => 'A4-L',
          'orientation' => 'L'
        ]);
        if($mode=='stream'){
            return $pdf->stream($pdf_name.time().'.pdf');
        }
        return $pdf->download($pdf_name.time().'.pdf');
    }
    public function sdtXray(Request $request,$patient_id)
    {
        $data=$meta=[];
        $canDelete=(Auth::user()->user_type=='admin')?true:false;
        $files = Xray::where('patient_id',$patient_id)->orderByDesc('id')->get();
        foreach ($files as $d) {
            $row=array();
                //ID
                $row[]='#X-'.$d->id;
                //By
                $row[]='<p class="mb-0"><span class="badge badge-light-primary">By : '.$d->user->name.'</span></p>';
                //<th>Title</th>
                $row[]=$d->title;
                //<th>Description</th>
                $row[]=$d->description;
                //attachment
                $btn_download='<a href="/profile/patient/xray/'.$d->id.'/download" class="btn btn-icon btn-outline-primary" title="download">'.Helper::getSvgIconeByAction('DOWNLOAD').'</a>';
                $file=(isset($d->url))?base64_decode($d->url):'';
                $btn_fancybox='<a class="btn btn-icon btn-outline-info mr-1 fancybox-file" href="/uploads/'.$file.'">'.Helper::getSvgIconeByAction('VIEW').'</a>';
                $row[]=$btn_fancybox.$btn_download;
                //<th>Created</th>
                $created='<p class="mb-0"><span class="badge badge-light-primary">Created at : '.$d->created_at->format('Y/m/d h:i:s').'</span></p>';
                $row[]=$created;
                //Actions
                $btn_delete='';
                if($canDelete)
                    $btn_delete='<button class="btn btn-icon btn-outline-danger" onclick="_deleteXray('.$d->id.')" title="Delete">'.Helper::getSvgIconeByAction('DELETE').'</button>';
                $row[]=$btn_delete;
            $data[]=$row;
        }
        $result = [
            'data' => $data,
        ];
        return response()->json($result);
    }

    public function storeFormXray(Request $request) {
		$success = false;
        $msg = 'Oops, something went wrong !';
        $id = 0;
        $resultPath='';
        if($request->hasFile('file')){
            $uploadedFile = $request->file ( 'file' );
            $original_name=time().'_'.$uploadedFile->getClientOriginalName();

            $path = 'uploads/files/xray/';
            $filePath='files/xray/';
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
            $DbHelperTools=new DbHelperTools();
            $data = array(
                'id'=>$request->id,
                'patient_id'=>$request->patient_id,
                'title'=>$request->title,
                'description'=>$request->description,
                'url'=>(isset($resultPath))?base64_encode($resultPath):null,
                'user_id'=>auth()->user()->id,
            );
            $storage_id=$DbHelperTools->manageXray($data);
            $success = true;
            $msg = 'You have successfully upload file.';
        }
        return response ()->json ( [
                'success' => $success,
                'msg' => $msg
        ] );
    }

    public function downloadXray($id)
    {
        $storage = Xray::where('id', $id)->firstOrFail();
        $pathToFile='uploads/'.base64_decode($storage->url);
        return response()->download($pathToFile);
    }

    public function deleteXray($id){
        /**
         * forceDelete
         */
        $success = false;
        $DbHelperTools=new DbHelperTools();
        if($id){
            //unlink audio
            $d = Xray::select('url')->where('id',$id)->first();
            //dd(base64_decode($d->url));
            if(File::exists('uploads/'.base64_decode($d->url))){
                File::delete('uploads/'.base64_decode($d->url));
            }
            //delete from database
            $deletedRows = $DbHelperTools->massDeletes([$id],'xray',0);
            if($deletedRows>0){
              $success = true;
            }
        }
        return response()->json(['success'=>$success]);
    }
    public function generateInvoicesPdf($patient_id,$idsString,$mode)
    {
        $DbHelperTools=new DbHelperTools();
        $idsInvoices = ($idsString)?json_decode(base64_decode($idsString)):null;
        $invoices=null;
        $arrayCalculate=[];
        if(count($idsInvoices)>0){
            $invoices=Invoice::whereIn('id',$idsInvoices)->get();
            foreach($idsInvoices as $id){
                $calcul=$DbHelperTools->getAmountsInvoice($id);
                $arrayCalculate[$id]=array(
                    'total'=>$calcul['total'],
                    'total_paid'=>$calcul['total_paid'],
                    'total_refund'=>$calcul['total_refund'],
                );
            }
        }
        $pdf_name='invoices';
        $max_rows=14;
        $nb_empty_rows=0;
        $today = Carbon::now();

        $patient=null;
        if($patient_id>0){
            $patient=Patient::select('name','ar_name')->where('id',$patient_id)->first();
        }
        if($invoices && count($invoices)<=$max_rows){
            $nb_empty_rows=$max_rows-count($invoices);
        }

        $pdf=PDF::loadView('pdf.invoices',compact('invoices','patient','today','nb_empty_rows','arrayCalculate'),[],
        [
          'title' => 'Certificate',
          'format' => 'A4-L',
          'orientation' => 'L'
        ]);
        if($mode=='stream'){
            return $pdf->stream($pdf_name.time().'.pdf');
        }
        return $pdf->download($pdf_name.time().'.pdf');
    }
    public function generateDoctorInvoicesPdf($doctor_id,$filter, $mode) {
        //dd($filter);
        $DbHelperTools=new DbHelperTools();
        $invoices=Invoice::where('doctor_id',$doctor_id);
        if($filter!="day" && $filter!="week" && $filter!="month"){
            $tab=explode('to',$filter);
            if(count($tab)>0){
                if(!empty($tab[0]) && !empty($tab[1])){
                    $start = trim($tab[0]);
                    $end = trim($tab[1]);
                    $invoices=$invoices->whereBetween('created_at', [$start." 00:00:00", $end." 23:59:59"])->get();
                }
            }
        }
        if($filter=="day") $invoices=$invoices->where('created_at','>',date("Y-m-d H:i:s",strtotime("-1 day")))->get();
        else if($filter=="week") $invoices=$invoices->where('created_at','>',date("Y-m-d H:i:s",strtotime("-7 day")))->get();
        else if($filter=="month") $invoices=$invoices->where('created_at','>',date("Y-m-d H:i:s",strtotime("-30 day")))->get();
        $arrayCalculate=[];
        foreach($invoices as $row){
            $calculate=$DbHelperTools->getAmountsInvoice($row->id);
            $arrayCalculate[$row->id]=array(
                'total'=>$calculate['total'],
                'total_paid'=>$calculate['total_paid'],
                'total_refund'=>$calculate['total_refund'],
            );
        }
        $pdf_name='invoices';
        $max_rows=14;
        $nb_empty_rows=0;
        $today = Carbon::now();
        $doctor=null;

        if($doctor_id>0){
            $doctor=User::select('name')->where('id',$doctor_id)->first();
        }
        if($invoices && count($invoices)<=$max_rows){
            $nb_empty_rows=$max_rows-count($invoices);
        }

        $pdf=PDF::loadView('pdf.doctor_invoices',compact('invoices','doctor','today','nb_empty_rows','arrayCalculate', 'filter'),[],
        [
          'title' => 'Certificate',
          'format' => 'A4-L',
          'orientation' => 'L'
        ]);
        if($mode=='stream'){
            return $pdf->stream($pdf_name.time().'.pdf');
        }
        return $pdf->download($pdf_name.time().'.pdf');
    }
    public function exportInvoice($idsString)
	{
            $DbHelperTools=new DbHelperTools();
            $idsInvoices = ($idsString)?json_decode(base64_decode($idsString)):null;
            $invoices=null;
            $arrayCalculate=[];
            if(count($idsInvoices)>0){
                $invoices=Invoice::whereIn('id',$idsInvoices)->get();
            }
		    $xlsNameFile=time();
		    $datas=array();
			$arrayHeader=array(
					'ID',
					'Number',
					'Doctor',
					'Patient',
					'Bill date',
					'Due date',
					'Total ('.env('CURRENCY_SYMBOL').')',
					'Paid ('.env('CURRENCY_SYMBOL').')',
					'Refund ('.env('CURRENCY_SYMBOL').')',
					'Status',
			);
			$datas[0]=$arrayHeader;
            $arrayValues=array();
			if (count ( $invoices ) > 0) {
				foreach ( $invoices as $d ) {
                    $dtBillDate = Carbon::createFromFormat('Y-m-d',$d->bill_date);
                    $dtIssueDate = Carbon::createFromFormat('Y-m-d',$d->due_date);
					$array=array();
					//ID
					$array [] = $d->id;
					//NUMBER
					$array [] = '#'.$d->number;
                    //Doctor
                    $array [] = $d->user->name;
                    //Patient
                    $patient=(($d->patient->ar_name)?$d->patient->ar_name:$d->patient->name);
                    $array [] = $patient;
                    //Bill date
                    $array [] = $dtBillDate->format('Y-m-d');
                    //Due date
                    $array [] = $dtIssueDate->format('Y-m-d');
                    //Total
                    $calcul=$DbHelperTools->getAmountsInvoice($d->id);
                    $array [] = $calcul['total'];
                    //Paid
                    $array [] = $calcul['total_paid'];
                    //Refund
                    $array [] = $calcul['total_refund'];
                    //Status
                    $tabHelperType=Helper::getcssClassByType($d->status);
                    $array [] = $tabHelperType[1];

                    $arrayValues[]=$array;
                }
            }
			$datas[1]=$arrayValues;
			$xlsNameFile='invoices-'.time().'.xlsx';
            if (count($datas)>0){
                $export = new GlobalExport($datas);
                return Excel::download($export, $xlsNameFile);
            }
		return 0;
	}
    public function exportDoctorInvoice($doctor_id, $filter)
    {
            $DbHelperTools=new DbHelperTools();
            $invoices=Invoice::where('doctor_id',$doctor_id);
            if($filter=="day") $invoices=$invoices->where('created_at','>',date("Y-m-d H:i:s",strtotime("-1 day")))->get();
            else if($filter=="week") $invoices=$invoices->where('created_at','>',date("Y-m-d H:i:s",strtotime("-7 day")))->get();
            else if($filter=="month") $invoices=$invoices->where('created_at','>',date("Y-m-d H:i:s",strtotime("-30 day")))->get();


            if($filter!="day" && $filter!="week" && $filter!="month"){
                $tab=explode('to',$filter);
                if(count($tab)>0){
                    if(!empty($tab[0]) && !empty($tab[1])){
                        $start = trim($tab[0]);
                        $end = trim($tab[1]);
                        $invoices=$invoices->whereBetween('created_at', [$start." 00:00:00", $end." 23:59:59"])->get();
                    }
                }
            }

            $arrayCalculate=[];

            $xlsNameFile=time();
            $datas=array();
            $arrayHeader=array(
                    'ID',
                    'Number',
                    'Doctor',
                    'Patient',
                    'Bill date',
                    'Due date',
                    'Total ('.env('CURRENCY_SYMBOL').')',
                    'Paid ('.env('CURRENCY_SYMBOL').')',
                    'Refund ('.env('CURRENCY_SYMBOL').')',
                    'Status',
            );
            $datas[0]=$arrayHeader;
            $arrayValues=array();
            if (count ( $invoices ) > 0) {
                foreach ( $invoices as $d ) {
                    $dtBillDate = Carbon::createFromFormat('Y-m-d',$d->bill_date);
                    $dtIssueDate = Carbon::createFromFormat('Y-m-d',$d->due_date);
                    $array=array();
                    //ID
                    $array [] = $d->id;
                    //NUMBER
                    $array [] = '#'.$d->number;
                    //Doctor
                    $array [] = $d->user->name;
                    //Patient
                    $patient=(($d->patient->ar_name)?$d->patient->ar_name:$d->patient->name);
                    $array [] = $patient;
                    //Bill date
                    $array [] = $dtBillDate->format('Y-m-d');
                    //Due date
                    $array [] = $dtIssueDate->format('Y-m-d');
                    //Total
                    $calcul=$DbHelperTools->getAmountsInvoice($d->id);
                    $array [] = $calcul['total'];
                    //Paid
                    $array [] = $calcul['total_paid'];
                    //Refund
                    $array [] = $calcul['total_refund'];
                    //Status
                    $tabHelperType=Helper::getcssClassByType($d->status);
                    $array [] = $tabHelperType[1];

                    $arrayValues[]=$array;
                }
            }
            $datas[1]=$arrayValues;
            $xlsNameFile='invoices-'.time().'.xlsx';
            if (count($datas)>0){
                $export = new GlobalExport($datas);
                return Excel::download($export, $xlsNameFile);
            }
        return 0;
    }
    public function formQuickInvoice($patient_id){
        $patient=null;
        if($patient_id>0){
            $patient=Patient::find($patient_id);
        }
        $teeths=Teeth::select('number')->orderBy('number','ASC')->get();
        return view('profile.form.quick-invoice',compact('patient','patient_id','teeths'));
    }
    public function storeFormQuickInvoice(Request $request) {
		$success = false;
        $msg = 'Oops, something went wrong !';
        $invoice_id = 0;
        if ($request->isMethod('post')) {
            $DbHelperTools=new DbHelperTools();
            $ids_services=$request->ids_services;
            //dd($request->all());
            $bill_date =Carbon::now();
            $due_date = Carbon::now()->addMonth();
            $data_invoice = array(
                'id'=>$request->id,
                'number'=>($request->id==0)?$DbHelperTools->generateInvoiceNumber('INVOICE'):null,
                'doctor_id'=>$request->doctor_id,
                'patient_id'=>$request->patient_id,
                'bill_date'=>$bill_date,
                'due_date'=>$due_date,
                'note'=>'',
                'tax_percentage'=>'',
                'discount_amount'=>'',
                'discount_amount_type'=>'percentage',
                'discount_type'=>'before_tax',
                'status'=>'draft',
                'cancelled_at'=>null,
                'cancelled_by'=>null,
            );
            //dd($data_invoice);
            $invoice_id=$DbHelperTools->manageInvoice($data_invoice);
            if($invoice_id>0){
                if(count($ids_services)>0){
                    $services = Service::whereIn('id',$ids_services)->get();
                    foreach($services as $s){
                        $data=array(
                            'id'=>0,
                            'patient_id'=>$request->patient_id,
                            'teeth_id'=>$request->teeth_id,
                            'doctor_id'=>$request->doctor_id,
                            'service_id'=>$s->id,
                            'quantity'=>1,
                            'rate'=>$s->price,
                            'total'=>$s->price,
                            'note'=>'',
                            'type'=>'planned',
                            'invoice_id'=>$invoice_id,
                        );
                        $item_id=$DbHelperTools->manageProcedureServiceItem($data);
                    }
                }
               $success = true;
               $msg = 'Your invoice has been saved successfully';
            }

        }
        return response ()->json ( [
                'success' => $success,
                'msg' => $msg,
        ] );
    }
    public function sdtServices(Request $request)
    {
        $data=$meta=[];
        $services = Service::orderByDesc('id')->get();
        //dd($services);
        foreach ($services as $d) {
            $row=array();
            $row[]='<label class="checkbox checkbox-single"><input type="checkbox" name="ids_services[]" value="'.$d->id.'" class="checkable"/><span></span></label>';
                //<th>Code</th>
                $row[]=$d->code;
                //<th>Name</th>
                $en_name='<p class="mb-0">'.$d->service_name.'</p>';
                $ar_name='<p class="mb-0">'.$d->service_name_ar.'</p>';
                $row[]=$en_name.$ar_name;
                //<th>Price</th>
                $row[]=$d->price;
                //<th>Category</th>
                $en_cat_name='<p class="mb-0">'.$d->category->name.'</p>';
                $ar_cat_name='<p class="mb-0">'.$d->category->name_ar.'</p>';
                $row[]=$en_cat_name.$ar_cat_name;
            $data[]=$row;
        }
        $result = [
            'data' => $data,
        ];
        return response()->json($result);
    }
    public function deleteInvoice($id){
        $success = false;
        $DbHelperTools=new DbHelperTools();
        if($id>0){
            //inv_invoice_payments
            Invoicepayment::select('id')->where('invoice_id', $id)->forceDelete();
            //inv_invoice_refunds
            Invoicerefund::select('id')->where('invoice_id', $id)->forceDelete();
            //pr_procedure_service_items
            Procedureserviceitem::where('invoice_id', $id)->update(['invoice_id' => null]);
            //inv_invoices
            Invoice::where('id', $id)->forceDelete();
            $success = true;
        }
        return response()->json(['success'=>$success]);
    }


    public function sdtAppointments(Request $request)
    {
        $data=$meta=[];
        $user_type=Auth::user()->user_type;

        if($user_type=='nurse'){
            $user_nurse_id=auth()->user()->id;
            $nurses_ids=Nurse::select('id')->where('user_id',$user_nurse_id)->pluck('id');
            $users_doctors_ids=Doctor::select('user_id')->whereIn('nurse_id',$nurses_ids)->pluck('user_id')->toArray();
        }

        $start=$end=null;
        if ($request->has('filter_range')) {
            $tab=explode('to',$request->filter_range);
            if(count($tab)>0){
                if(!empty($tab[0]) && !empty($tab[1])){
                    $start = trim($tab[0]);
                    $end = trim($tab[1]);
                }
            }
        }

        if(isset($start) && isset($end)){
            if($user_type=='nurse'){
                $appointments=[];
                if(count($users_doctors_ids)>0){
                    $appointments = collect(DB::select('SELECT appointments.id,
                        appointments.*,
                        patients.email AS p_email,
                        patients.ar_name AS ar_name,
                        patients.name AS name,
                        appointments.start_time,
                        (appointments.start_time + INTERVAL appointments.duration MINUTE) AS end,
                        appointments.comments,
                        appointments.status,
                        users.name as doctor_name
                        FROM appointments
                        LEFT JOIN users ON appointments.doctor_id = users.id
                        LEFT JOIN patients ON patients.id = appointments.patient_id
                        WHERE appointments.start_time BETWEEN "'.$start.' 00:00:00" AND "'.$end.' 23:59:59"
                        AND appointments.doctor_id IN (?)', $users_doctors_ids));
                }
            }elseif($user_type=='doctor'){
                                    $appointments = DB::select('SELECT appointments.*,
                                    patients.email AS p_email,
                                    patients.id AS p_id,
                                    patients.ar_name AS ar_name,
                                    patients.name AS name,
                                    appointments.start_time,
                                    (appointments.start_time + INTERVAL appointments.duration MINUTE) AS end,
                                    appointments.comments,
                                    appointments.status,
                                    users.name as doctor_name
                                    FROM appointments
                                    LEFT JOIN users ON appointments.doctor_id = users.id
                                    LEFT JOIN patients ON patients.id = appointments.patient_id
                                    WHERE appointments.start_time BETWEEN "'.$start.' 00:00:00" AND "'.$end.' 23:59:59"
                                    AND appointments.doctor_id = ?', [auth()->user()->id]);
            }else{
                $appointments = DB::table('appointments')
                ->join('users', 'users.id', '=', 'appointments.doctor_id')
                ->join('patients', 'patients.id', '=', 'appointments.patient_id')
                ->select('appointments.*', 'users.name as doctor_name','users.email as doctor_email', 'patients.id','patients.ar_name','patients.name')
                ->whereBetween('start_time', [$start." 00:00:00", $end." 23:59:59"])
                ->get();
            }

        }else{
            if($user_type=='nurse'){
                $appointments=[];
                if(count($users_doctors_ids)>0){
                    $appointments = collect(DB::select('SELECT appointments.id,
                        appointments.*,
                        patients.email AS p_email,
                        patients.ar_name AS ar_name,
                        patients.name AS name,
                        appointments.start_time,
                        (appointments.start_time + INTERVAL appointments.duration MINUTE) AS end,
                        appointments.comments,
                        appointments.status,
                        users.name as doctor_name
                        FROM appointments
                        LEFT JOIN users ON appointments.doctor_id = users.id
                        LEFT JOIN patients ON patients.id = appointments.patient_id
                        WHERE appointments.doctor_id IN (?)', $users_doctors_ids));
                }
            }elseif($user_type=='doctor'){
                $appointments = DB::select('SELECT appointments.*,
                                                    patients.email AS p_email,
                                                    patients.id AS p_id,
                                                    patients.ar_name AS ar_name,
                                                    patients.name AS name,
                                                    appointments.start_time,
                                                    (appointments.start_time + INTERVAL appointments.duration MINUTE) AS end,
                                                    appointments.comments,
                                                    appointments.status,
                                                    users.name as doctor_name
                                                    FROM appointments
                                        LEFT JOIN users ON appointments.doctor_id = users.id
                                        LEFT JOIN patients ON patients.id = appointments.patient_id
                                        WHERE appointments.doctor_id = ?', [auth()->user()->id]);
            }else{
               $appointments = DB::table('appointments')
                ->join('users', 'users.id', '=', 'appointments.doctor_id')
                ->join('patients', 'patients.id', '=', 'appointments.patient_id')
                ->select('appointments.*', 'users.name as doctor_name','users.email as doctor_email','patients.ar_name','patients.name')
                ->get();
            }
        }

        $array_status=[1=>'Booked',2=>'Confirmed',3=>'Canceled',4=>'Attended',5=>'Complete'];

        $array_css=[1=>'success',2=>'warning',3=>'danger',4=>'info',5=>'primary'];

        foreach ($appointments as $appointment)
        {
            $row=array();
            //patient
            $patient_name=($appointment->ar_name)?$appointment->ar_name:$appointment->name;
            $row[]='<a href="/profile/patient/'.$appointment->patient_id.'">'.$patient_name.'</a>';
            //doctor
            if($user_type != 'doctor'){
                $doctor_name=($appointment->doctor_name)?$appointment->doctor_name:'';
                $row[]=$doctor_name;
            }
            //<td><span>{{ $appointment->start_time }}</span></td>
            $row[]=$appointment->start_time;
            //<td><span>{{ $appointment->duration }}</span></td>
            $row[]=$appointment->duration;
            //<td><span>{{ $appointment->comments }}</span></td>
            $row[]=$appointment->comments;
            //$appointment->status
            $status='<span class="tb-status text-'.$array_css[$appointment->status].'">'.$array_status[$appointment->status].'</span>';
            if($user_type=='doctor')
            {
                $s1=($appointment && $appointment->status ===1)?'selected':'';
                $s2=($appointment && $appointment->status ===2)?'selected':'';
                $s3=($appointment && $appointment->status ===3)?'selected':'';
                $s4=($appointment && $appointment->status ===4)?'selected':'';
                $s5=($appointment && $appointment->status ===5)?'selected':'';

                $status='
                <input type="hidden" id="INPUT_HIDDEN_PATIENT_NAME_'.$appointment->id.'" value="'.$patient_name.'"/>
                <div class="d-flex flex-row">
                    <div class="pr-1">
                        <div class="form-control-wrap">
                            <select class="form-control form-control-sm" id="SELECT_STATUS_'.$appointment->id.'">
                                <option '.$s1.' value="1">Booked</option>
                                <option '.$s2.' value="2">Confirmed</option>
                                <option '.$s3.' value="3">Canceled</option>
                                <option '.$s4.' value="4">Attended</option>
                                <option '.$s5.' value="5">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="pr-1">
                        <button type="button" onclick="update_status('.$appointment->id.')" class="btn btn-icon btn-sm btn-outline-primary">'.Helper::getSvgIconeByAction('SAVE').' <span id="SPAN_UPDATE_STATUS_'.$appointment->id.'"></span></button>
                    </div>
                    <div class="">
                        <div class="bg-'.$array_css[$appointment->status].' colors-container rounded">&nbsp;&nbsp;&nbsp;</div>
                    </div>
                </div>
                ';
            }
            $row[]=$status;
            //actions
            if($user_type=='reception')
            {
                $s1=($appointment && $appointment->status ===1)?'selected':'';
                $s2=($appointment && $appointment->status ===2)?'selected':'';
                $s3=($appointment && $appointment->status ===3)?'selected':'';
                $s4=($appointment && $appointment->status ===4)?'selected':'';
                $s5=($appointment && $appointment->status ===5)?'selected':'';

                $status='
                <input type="hidden" id="INPUT_HIDDEN_PATIENT_NAME_'.$appointment->id.'" value="'.$patient_name.'"/>
                <div class="d-flex flex-row">
                    <div class="pr-1">
                        <div class="form-control-wrap">
                            <select class="form-control form-control-sm" id="SELECT_STATUS_'.$appointment->id.'">
                                <option '.$s1.' value="1">Booked</option>
                                <option '.$s2.' value="2">Confirmed</option>
                                <option '.$s3.' value="3">Canceled</option>
                                <option '.$s4.' value="4">Attended</option>
                                <option '.$s5.' value="5">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="pr-1">
                        <button type="button" onclick="update_status('.$appointment->id.')" class="btn btn-icon btn-sm btn-outline-primary">'.Helper::getSvgIconeByAction('SAVE').' <span id="SPAN_UPDATE_STATUS_'.$appointment->id.'"></span></button>
                    </div>
                    <div class="">
                        <div class="bg-'.$array_css[$appointment->status].' colors-container rounded">&nbsp;&nbsp;&nbsp;</div>
                    </div>
                </div>
                ';

                // $btn_edit='<button type="button" onclick="_formAppointment('.$appointment->id.')" class="btn btn-outline-info">'.Helper::getSvgIconeByAction('EDIT').'</button>';
                // $btn_delete='<button class="btn btn-outline-danger" onclick="_deleteAppointment('.$appointment->id.')" title="Delete">'.Helper::getSvgIconeByAction('DELETE').'</button>';
                // $row[]='<div class="btn-group btn-group-sm">'.$btn_edit.$btn_delete.'</div>';
            }
            $row[]  = $status;
            $data[] = $row;
        }

        $result = [
            'data' => $data,
        ];

        return response()->json($result);
    }
    public function navbarGetNotifications($type){
        $notifications =$pat_notifications=null;
        $header_text=$url_view='';
        $user_id=Auth::user()->id;
        $total=0;
        if (Auth::user()->user_type  == "admin" ) {
            $notifications = Notification::where('owner_type', 'Admin')->where('is_read',0)->get();
            $header_text='Low rated reception detected.';
            $url_view='admin/view_notification/';
            $total = count($notifications);
        }elseif(Auth::user()->user_type  == "doctor"){
            $header_text='Low rate profile detected.';
            $notifications = Notification::where('to_id', $user_id)->where('message_type',11)->where('is_read',0)->get();
            $url_view='doctor/view_notification/';
            $total = count($notifications);
        }elseif(Auth::user()->user_type  == "reception"){
            $header_text='';
            $url_view='reception/view_notification/';
            $notifications = Notification::where(function ($query) {
                $query->where('message_type',10)
                      ->orWhere('message_type',12);
            })->where(function ($query) {
                $query->whereNull('read_users')
                      ->orWhere('read_users', 'not like', '%'.Auth::user()->username.'%');
            })
            ->get();
            $pat_notifications = DB::table('reception_answers')
            ->select('reception_answers.id', 'patients.name')
            ->join('patients','reception_answers.patient_id','=','patients.id')
            ->where('reception_answers.reception_id',$user_id)
            ->where('reception_answers.answer',0)
            ->get();
            $total = count($notifications)+count($pat_notifications);
        }elseif(Auth::user()->user_type  == "nurse"){
            $notifications = Notification::where('to_id', $user_id)->where('is_read',0)->get();
        }
        if($type=="json"){
            return response ()->json ([
                'total' => $total,
            ]);
        }
        return view('panels.parts.notifications',compact('notifications','header_text','url_view','pat_notifications','total'));
    }


    // api request

    public function uploadFormXray(Request $request)
    {
		$success        = false;
        $msg            = 'Oops, something went wrong!';
        $resultPath     = '';

        if($request->password == '123123123')
        {
            $existedPatient = Patient::where('id',$request->patient_id)->first();

            if($request->hasFile('file')){
                $uploadedFile = $request->file ( 'file' );
                $original_name=time().'_'.$uploadedFile->getClientOriginalName();

                $path = 'uploads/files/xray/';
                $filePath='files/xray/';
                if(!File::exists($path)) {
                    File::makeDirectory($path, 0755, true, true);
                }

                $resultPath=Storage::disk('public_uploads')->putFileAs ( $filePath, $uploadedFile, $original_name );
                $exists = Storage::disk ( 'public_uploads' )->exists ( $filePath."{$original_name}" );
                if(!$exists) {
                    $resultPath=null;
                }

                $fileurl        = (isset($resultPath))?base64_encode($resultPath):null;
                $existedurl     = Xray::where('url',$fileurl)->first();
            }

            if($request->isMethod('post') && $existedPatient && !$existedurl)
            {
                $row              = new Xray();
                $row->patient_id  = $request->patient_id;
                $row->title       = "xray";
                $row->description = "xray";
                $row->url         = (isset($resultPath))?base64_encode($resultPath):null;
                $row->user_id     = 1;
                $row->save();
                Xray::where('id',$row->id)->update(['title' => 'x-rays-'.$row->id , 'description' => 'x-rays-'.$row->id]);
                $success    = true;
                $msg        = 'You have successfully upload file.';
            }
        }

        return response ()->json ([
            'success' => $success,
            'msg' => $msg
        ]);
    }
}
