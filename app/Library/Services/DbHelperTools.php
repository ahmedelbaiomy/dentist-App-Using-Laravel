<?php
namespace App\Library\Services;
use Carbon\Carbon;
use App\Models\note;
use App\Models\Storage;
use App\Models\Schedule;
use App\Models\Appointment;
use App\Models\Patientstorage;
use Illuminate\Support\Facades\DB;
  
class DbHelperTools
{
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
}
