<?php
namespace App\Library\Services;
use Carbon\Carbon;
use App\Models\note;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\Storage;
use App\Models\Schedule;
use App\Models\Helpindex;
use App\Models\Appointment;
use App\Models\Invoiceitem;
use App\Models\Invoicerefund;
use App\Models\Invoicepayment;
use App\Models\Patientstorage;
use App\Models\service_category;
use Illuminate\Support\Facades\DB;
use App\Models\Procedureserviceitem;
  
class DbHelperTools
{
    public function getDatasFromTableToArray($tableName){
        $result = DB::select('SELECT * FROM `'.$tableName.'`');
        $result = array_map(function ($value) {return (array)$value;}, $result);
        return $result;
    }
    public function manageSchedule($data){
        $id=0;
        if (count($data)>0){
            $row = new Schedule();
            $id=(isset($data['id']))?$data['id']:0;
            if ($id > 0) {
                $row = Schedule::find ( $id );
            }
            $row->day = (isset($data['day']))?$data['day']:null;
            $row->slot = (isset($data['slot']))?$data['slot']:null;
            $row->doctor_id = (isset($data['doctor_id']))?$data['doctor_id']:null;
            $row->save ();
            $id = $row->id;
        }
        return $id;
    }
    public function manageAppointment($data){
        $id=0;
        if (count($data)>0){
            $row = new Appointment();
            $id=(isset($data['id']))?$data['id']:0;
            if ($id > 0) {
                $row = Appointment::find ( $id );
            }
            $row->patient_id = (isset($data['patient_id']))?$data['patient_id']:null;
            $row->doctor_id = (isset($data['doctor_id']))?$data['doctor_id']:null;
            $row->start_time = (isset($data['start_time']))?$data['start_time']:null;
            $row->duration = (isset($data['duration']))?$data['duration']:null;
            $row->comments = (isset($data['comments']))?$data['comments']:null;
            $row->status = (isset($data['status']))?$data['status']:null;
            $row->save ();
            $id = $row->id;
        }
        return $id;
    }
    public function manageNote($data){
        $id=0;
        if (count($data)>0){
            $row = new note();
            $id=(isset($data['id']))?$data['id']:0;
            if ($id > 0) {
                $row = note::find ( $id );
            }
            $row->patient_id = (isset($data['patient_id']))?$data['patient_id']:null;
            $row->user_id = (isset($data['user_id']))?$data['user_id']:null;
            $row->note = (isset($data['note']))?$data['note']:null;
            if(isset($data['audio_file'])){
                $row->audio_file = (isset($data['audio_file']))?$data['audio_file']:null;
            }
            $row->save ();
            $id = $row->id;
        }
        return $id;
    }
    public function generateDateRange($started_at,$ended_at,$slot_duration = 15)
    {
        $start_date = Carbon::createFromFormat('Y-m-d H:i',$started_at);
        $end_date = Carbon::createFromFormat('Y-m-d H:i',$ended_at);

        $dates = [];
        $slots = $start_date->diffInMinutes($end_date)/$slot_duration;

        //first unchanged time
        $dates[$start_date->toDateString()][]=$start_date->toTimeString();

        for($s = 1;$s <=$slots;$s++){

            $dates[$start_date->toDateString()][]=$start_date->addMinute($slot_duration)->toTimeString();

        }

        return $dates;
    }
    public function getTimeSlotsByDoctorDay($doctor_id,$day){
        $times = [];
        if($doctor_id>0){
            $rSlots = Schedule::where([['doctor_id',$doctor_id],['day',$day]])->pluck('slot')->toArray();
            if(count($rSlots)>0){
                foreach($rSlots as $slot){
                    $dt = Carbon::createFromFormat('Y-m-d H:i:s',$slot);
                    $times[]=$dt->format('H:i:s');
                }
            }
        }
        return $times;
    }
    public function massDeletes($ids,$type,$force_delete){
        $deletedRows = 0;
        if($type=='schedule'){
          if($force_delete==1){
            $deletedRows = Schedule::whereIn('id', $ids)->forceDelete();
          }else{
            $deletedRows = Schedule::whereIn('id', $ids)->delete();
          }
        }elseif($type=='note'){
            if($force_delete==1){
                $deletedRows = note::whereIn('id', $ids)->forceDelete();
              }else{
                $deletedRows = note::whereIn('id', $ids)->delete();
            }
        }elseif($type=='patientstorage'){
            if($force_delete==1){
                $deletedRows = Patientstorage::whereIn('id', $ids)->forceDelete();
              }else{
                $deletedRows = Patientstorage::whereIn('id', $ids)->delete();
            }
        }elseif($type=='service'){
            if($force_delete==1){
                $deletedRows = Service::whereIn('id', $ids)->forceDelete();
              }else{
                $deletedRows = Service::whereIn('id', $ids)->delete();
            }
        }elseif($type=='category'){
            if($force_delete==1){
                $deletedRows = service_category::whereIn('id', $ids)->forceDelete();
              }else{
                $deletedRows = service_category::whereIn('id', $ids)->delete();
            }
        }
        return $deletedRows;
    }
    public function checkNearstAvalabilityTime($doctor_id,$start_date,$iCount){
        $tentativeMaxLimit = 10;
        $day = strtoupper(Carbon::createFromFormat('Y-m-d',$start_date)->format('l'));
        $slots = Schedule::where([['doctor_id',$doctor_id],['day',$day]])->orderBy('slot')->get();
        if(count($slots)>0 || $iCount>=$tentativeMaxLimit){
            return ['doctor_id'=>$doctor_id,'start_date'=>$start_date];
        }
        $iCount++;
        $dt=Carbon::createFromFormat('Y-m-d',$start_date);
        $date = $dt->addDays(1);
        $start_date=$date->format('Y-m-d');
        return $this->checkNearstAvalabilityTime($doctor_id,$start_date,$iCount);
    }
    public function managePatientStorage($data){
        $id=0;
        if (count($data)>0){
            $row = new Patientstorage();
            $id=(isset($data['id']))?$data['id']:0;
            if ($id > 0) {
                $row = Patientstorage::find ( $id );
            }
            if(isset($data['patient_id'])){
                $row->patient_id = (isset($data['patient_id']))?$data['patient_id']:null;
            }
            if(isset($data['title'])){
                $row->title = (isset($data['title']))?$data['title']:null;
            }
            if(isset($data['description'])){
                $row->description = (isset($data['description']))?$data['description']:null;
            }
            if(isset($data['url'])){
                $row->url = (isset($data['url']))?$data['url']:null;
            }
            $row->save ();
            $id = $row->id;
        }
        return $id;
    }
    public function getStatsByDoctors($doctor_user_id,$start_date,$end_date){
        $incomes=$refunds=0;
        if($doctor_user_id>0){
                if($start_date && $end_date){
                    //$start = Carbon::createFromFormat('Y-m-d',$start_date);
                    //$end = Carbon::createFromFormat('Y-m-d',$end_date);
                    $ids = Invoice::select('id')->where('doctor_id',$doctor_user_id)->whereBetween('created_at', [$start_date." 00:00:00", $end_date." 23:59:59"])->pluck('id');
                }else{
                    $ids = Invoice::select('id')->where('doctor_id',$doctor_user_id)->pluck('id');
                }
                if(count($ids)>0){
                    foreach($ids as $invoice_id){
                        $calc=$this->getAmountsInvoice($invoice_id);
                        $incomes=$incomes+$calc['nnf_total'];
                        $refunds=$refunds+$calc['total_refund'];
                    }
                }
            
        }
        return array(
            'incomes'=>$incomes,
            'refunds'=>$refunds,
        );
    }
    public function manageServiceCategorie($data){
        $id=0;
        if (count($data)>0){
            $row = new service_category();
            $id=(isset($data['id']))?$data['id']:0;
            if ($id > 0) {
                $row = service_category::find ( $id );
                if(!$row){
                    $row = new service_category();
                  }        
            }
            
            if(isset($data['name'])){
                $row->name = (isset($data['name']))?$data['name']:null;
            }
            if(isset($data['name_ar'])){
                $row->name_ar = (isset($data['name_ar']))?$data['name_ar']:null;
            }
            if(isset($data['path_icon'])){
                $row->path_icon = (isset($data['path_icon']))?$data['path_icon']:null;
            }
            $row->order_show = (isset($data['order_show']))?$data['order_show']:1;
            $row->is_active = (isset($data['is_active']))?$data['is_active']:0;
            $row->parent_id = (isset($data['parent_id']))?$data['parent_id']:null;
            $row->save ();
            $id = $row->id;
        }
        return $id;
    }      
    public function manageService($data){
        $id=0;
        if (count($data)>0){
            $row = new Service();
            $id=(isset($data['id']))?$data['id']:0;
            if ($id > 0) {
                $row = Service::find ( $id );
                if(!$row){
                    $row = new Service();
                  }        
            }            
            if(isset($data['code'])){
                $row->code = (isset($data['code']))?$data['code']:null;
            }
            if(isset($data['service_name'])){
                $row->service_name = (isset($data['service_name']))?$data['service_name']:null;
            }
            if(isset($data['service_name_ar'])){
                $row->service_name_ar = (isset($data['service_name_ar']))?$data['service_name_ar']:null;
            }
            if(isset($data['price'])){
                $row->price = (isset($data['price']))?$data['price']:null;
            }
            if(isset($data['note'])){
                $row->note = (isset($data['note']))?$data['note']:null;
            }
            if(isset($data['category_id'])){
                $row->category_id = (isset($data['category_id']))?$data['category_id']:null;
            }
            $row->save ();
            $id = $row->id;
        }
        return $id;
    }      
    public function manageProcedureServiceItem($data){
        $id=0;
        if (count($data)>0){
            $row = new Procedureserviceitem();
            $id=(isset($data['id']))?$data['id']:0;
            if ($id > 0) {
                $row = Procedureserviceitem::find ( $id );
                if(!$row){
                    $row = new Procedureserviceitem();
                  }        
            }            
            $row->quantity = (isset($data['quantity']))?$data['quantity']:1;
            $row->rate = (isset($data['rate']))?$data['rate']:null;
            $row->total = (isset($data['total']))?$data['total']:null;
            $row->note = (isset($data['note']))?$data['note']:null;
            if(isset($data['type'])){
                $row->type = (isset($data['type']))?$data['type']:null;
            }
            if(isset($data['invoice_id'])){
                $row->invoice_id = (isset($data['invoice_id']))?$data['invoice_id']:null;
            }
            $row->service_id = (isset($data['service_id']))?$data['service_id']:null;
            $row->teeth_id = (isset($data['teeth_id']))?$data['teeth_id']:null;
            $row->doctor_id = (isset($data['doctor_id']))?$data['doctor_id']:null;
            $row->patient_id = (isset($data['patient_id']))?$data['patient_id']:null;
            $row->save ();
            $id = $row->id;
        }
        return $id;
    }      
    public function manageInvoice($data){
        $id=0;
        if (count($data)>0){
            $row = new Invoice();
            $id=(isset($data['id']))?$data['id']:0;
            if ($id > 0) {
                $row = Invoice::find ( $id );
                if(!$row){
                    $row = new Invoice();
                  }        
            }
            if(isset($data['number'])){
                $row->number = (isset($data['number']))?$data['number']:null;
            }
            $row->doctor_id = (isset($data['doctor_id']))?$data['doctor_id']:null;
            $row->patient_id = (isset($data['patient_id']))?$data['patient_id']:null;
            $row->bill_date = (isset($data['bill_date']))?$data['bill_date']:null;
            $row->due_date = (isset($data['due_date']))?$data['due_date']:null;
            $row->note = (isset($data['note']))?$data['note']:null;
            if(isset($data['tax_percentage'])){
                $row->tax_percentage = (isset($data['tax_percentage']))?$data['tax_percentage']:null;
            }
            if(isset($data['discount_amount'])){
                $row->discount_amount = (isset($data['discount_amount']))?$data['discount_amount']:null;
            }
            if(isset($data['discount_amount_type'])){
                $row->discount_amount_type = (isset($data['discount_amount_type']))?$data['discount_amount_type']:null;
            }
            if(isset($data['discount_type'])){
                $row->discount_type = (isset($data['discount_type']))?$data['discount_type']:null;
            }
            if(isset($data['status'])){
                $row->status = (isset($data['status']))?$data['status']:null;
            }
            if(isset($data['cancelled_at'])){
                $row->cancelled_at = (isset($data['cancelled_at']))?$data['cancelled_at']:null;
            }
            if(isset($data['cancelled_by'])){
                $row->cancelled_by = (isset($data['cancelled_by']))?$data['cancelled_by']:null;
            }
            $row->save ();
            $id = $row->id;
        }
        return $id;
    }      
    public function manageInvoiceItem($data){
        $id=0;
        if (count($data)>0){
            $row = new Invoiceitem();
            $id=(isset($data['id']))?$data['id']:0;
            if ($id > 0) {
                $row = Invoiceitem::find ( $id );
                if(!$row){
                    $row = new Invoiceitem();
                  }        
            }            
            $row->quantity = (isset($data['quantity']))?$data['quantity']:1;
            $row->rate = (isset($data['rate']))?$data['rate']:null;
            $row->total = (isset($data['total']))?$data['total']:null;
            $row->sort = (isset($data['sort']))?$data['sort']:1;
            $row->procedure_service_item_id = (isset($data['procedure_service_item_id']))?$data['procedure_service_item_id']:null;
            $row->invoice_id = (isset($data['invoice_id']))?$data['invoice_id']:null;
            $row->save ();
            $id = $row->id;
        }
        return $id;
    }      
    public function manageInvoicePayment($data){
        $id=0;
        if (count($data)>0){
            $row = new Invoicepayment();
            $id=(isset($data['id']))?$data['id']:0;
            if ($id > 0) {
                $row = Invoicepayment::find ( $id );
                if(!$row){
                    $row = new Invoicepayment();
                  }        
            }            
            $row->amount = (isset($data['amount']))?$data['amount']:null;
            $row->payment_date = (isset($data['payment_date']))?$data['payment_date']:null;
            $row->payment_method = (isset($data['payment_method']))?$data['payment_method']:null;
            $row->note = (isset($data['note']))?$data['note']:null;
            $row->invoice_id = (isset($data['invoice_id']))?$data['invoice_id']:null;
            $row->save ();
            $id = $row->id;
        }
        return $id;
    }      
    public function manageInvoiceRefund($data){
        $id=0;
        if (count($data)>0){
            $row = new Invoicerefund();
            $id=(isset($data['id']))?$data['id']:0;
            if ($id > 0) {
                $row = Invoicerefund::find ( $id );
                if(!$row){
                    $row = new Invoicerefund();
                  }        
            }            
            if(isset($data['refund_code'])){
                $row->refund_code = (isset($data['refund_code']))?$data['refund_code']:null;
            }
            $row->amount = (isset($data['amount']))?$data['amount']:null;
            $row->reason = (isset($data['reason']))?$data['reason']:null;
            $row->refund_date = (isset($data['refund_date']))?$data['refund_date']:null;
            $row->invoice_id = (isset($data['invoice_id']))?$data['invoice_id']:null;
            $row->save ();
            $id = $row->id;
        }
        return $id;
    }
    public function getIndexNumber($type) {
		$Number = 0;
		$indice = 0;
        $now = Carbon::now();
            $m = $now->format ( 'm' );
        $index = Helpindex::where ('type',$type)->first();
            if ($index) {
        $index_date = Carbon::createFromFormat('Y-m-d H:i:s',$index->index_date);
                $mIndex = $index_date->format ( 'm' );
                $indice = $index->index + 1;
                if ($m != $mIndex) {
                    $indice = 1;
                }
            }
            if ($indice > 0) {
                $ym = $now->format ( 'Y' ) . $now->format ( 'm' );
                $num_format = $this->formatNombreChiffre ( array (
                        'ID' => $indice,
                        'NOMBRE_CHIFFRE' => 4 
                ) );
                $Number = $ym . $num_format;
            }
            return array (
                    'indice' => $indice,
                    'number' => $Number 
            );
	}
  public function formatNombreChiffre(array $data) {
		$maxRang = ($data ['ID']) . "";
		$left = $data ['NOMBRE_CHIFFRE'] - strlen ( $maxRang );
		if ($left > 0) {
			$maxRang = str_repeat ( "0", $left ) . $maxRang;
		}
		return $maxRang;
	}
  public function generateInvoiceNumber($type){
        $tab = $this->getIndexNumber ( $type );
        $indice = $tab ['indice'];
        $invoiceNumber = $tab ['number'];
        $this->setIndexNumber($indice, $type);
        return $invoiceNumber;
  }
  public function setIndexNumber($indice, $type) {
    Helpindex::where('type',$type)->update(['index' => $indice]);
    return true;
  }
  public function getAmountsInvoice($invoice_id){
    $total =$subtotal = $total_paid=$total_refund=0;
    $discount_amount=0;
    if($invoice_id>0){
      $invoice = Invoice::find ( $invoice_id );
      $subtotal = Procedureserviceitem::where ('invoice_id',$invoice_id)->sum('total');
      //Réduction : //'percentage', 'fixed_amount'
      //'before_tax', 'after_tax'
      $tax_amount = 0;
      $amout_to_discount=$subtotal;
      if($invoice->discount_type=='after_tax'){
        if($invoice->tax_percentage>0){
          $tax_amount=$this->calculateTaxAmount($subtotal,$invoice->tax_percentage);
        }
        $amout_to_discount=$subtotal+$tax_amount;
        $discount_amount=$this->calculateDiscount($amout_to_discount,$invoice->discount_amount_type,$invoice->discount_amount);
      }elseif($invoice->discount_type=='before_tax'){
        $amout_to_discount=$subtotal;
        $discount_amount=$this->calculateDiscount($amout_to_discount,$invoice->discount_amount_type,$invoice->discount_amount);
        if($invoice->tax_percentage>0){
          $tax_amount=$this->calculateTaxAmount($subtotal-$discount_amount,$invoice->tax_percentage);
        }
      }else{
        if($invoice->tax_percentage>0){
          $tax_amount=$this->calculateTaxAmount($subtotal,$invoice->tax_percentage);
        }
      }

      $total = $subtotal-$discount_amount+$tax_amount;
      //total payments
      $total_paid = Invoicepayment::where ('invoice_id',$invoice_id)->sum('amount');
      $total_refund = Invoicerefund::where ('invoice_id',$invoice_id)->sum('amount');
    }
    return array(
        'total'=>number_format($total,2),
        'nnf_total'=>$total,
        'subtotal'=>number_format($subtotal,2),
        'discount_amount'=>number_format($discount_amount,2),
        'nnf_discount_amount'=>number_format($discount_amount,2),
        'tax_amount'=>number_format($tax_amount,2),
        'total_paid'=>number_format($total_paid,2),
        'nnf_total_paid'=>$total_paid,
        'total_refund'=>$total_refund,
    );
  }
  public function calculateDiscount($amout_to_discount,$discount_amount_type,$invoice_discount_amount){
    $discount_amount=0;
    if($discount_amount_type=='percentage'){
      $discount_amount=($amout_to_discount*$invoice_discount_amount)/100;
    }elseif($discount_amount_type=='fixed_amount'){
      $discount_amount=$invoice_discount_amount;
    }
    return $discount_amount;
  }
  public function calculateTaxAmount($amount,$tax_percentage){
    $tax_amount = 0;
    if($tax_percentage>0){
      $tax_amount =($amount*$tax_percentage)/100;
    }
    return $tax_amount;
  }
  public function getStatusInvoice($invoice_id){
    $status='not_paid';
    //enum('draft', 'not_paid','partial_paid', 'paid','cancelled')
    if($invoice_id>0){
        $totalPayments = Invoicepayment::where ('invoice_id',$invoice_id)->sum('amount');
        $calcul=$this->getAmountsInvoice($invoice_id);
        $totalInvoice=$calcul['nnf_total'];
        if($totalPayments==$totalInvoice){
            $status='paid';
        }
        if($totalPayments>0 && $totalPayments<$totalInvoice){
            $status='partial_paid';
        }
    }
    return $status;
  }
  public function getStatsBillingByPatient($patient_id){
    $nb_invoices=$total_invoices=$total_paid_invoices=$total_discount=0;
    if($patient_id>0){
        $nb_invoices = Invoice::select('id')->where ('patient_id',$patient_id)->count();
        $invoices=Invoice::select('id')->where ('patient_id',$patient_id)->get();
        if(count($invoices)>0){
            foreach($invoices as $invoice){
                $calcul=$this->getAmountsInvoice($invoice['id']);
                $total_invoices=$total_invoices+$calcul['nnf_total'];
                $total_paid_invoices=$total_paid_invoices+$calcul['nnf_total_paid'];
                $total_discount=$total_discount+$calcul['nnf_discount_amount'];
            }
        }
    }
    return [
        'nb_invoices'=>$nb_invoices,
        'total_invoices'=>$total_invoices,
        'total_paid_invoices'=>$total_paid_invoices,
        'total_discount'=>$total_discount,
    ];
  }
  public function generateCodeForRefund(){
      $code=0;
      $rs = Invoicerefund::select('id')->latest('id')->first();
      $id=($rs)?$rs->id:0;
      $code=$id+1;
      $code = 'RF'.sprintf('%04d', $code);  
      return $code;
  } 
}
