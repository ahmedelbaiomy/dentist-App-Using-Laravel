<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Patient;
use App\Models\Patientprofile;


class PatientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $patients = Patient::all();
        return view('admin.patient',compact('patients'));
    }

    public function store(Request $data) 
    {
        Patient::updateOrCreate(
            [
                'id' => $data['id']
            ],
            [
            'name' => $data['name'],
            'email' => $data['email'],
            'birthday' => $data['birthday'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'state' => 0,
        ]);

        return response()->json(['success'=>'Ajax request submitted successfully']);
    }


    public function patient_profile($id)
    {
        $patient_id = $id;

        $services = DB::select("SELECT patient_notes.id, patient_notes.patient_id, patient_notes.teeth_id, patient_notes.note, patient_notes.category_id, patient_notes.type, service_categories.name, services.price FROM patient_notes 
                                    LEFT JOIN service_categories on patient_notes.category_id = service_categories.id
                                    left join services on service_categories.id = services.category_id
                                    WHERE patient_notes.patient_id = ? 
                                    GROUP BY patient_notes.id", [$id]);
       // $services = json_encode($service);


       $patient_data = DB::select("SELECT * FROM patients WHERE id=?", [$id]);
       $name = explode(" ", $patient_data[0]->name);
       if(count($name) == 2)
           $short_name = $name[0][0].$name[1][0];
       else if(count($name) == 1)
           $short_name = $short_name = $name[0][0].$name[0][1];

        $data = DB::select("SELECT * FROM service_categories");
        $cat_arrs = array();
        foreach($data as $row) { 
            $cat_arrs[] = array(
                "id" => $row ->id,
                "parent" => $row->parent_id,
                "text" => $row->name
            );
        }
        $datas = json_encode($cat_arrs);

        $invoices = DB::select("SELECT invoices.id, invoices.code, invoices.from, users.email AS d_email, invoices.to, patients.email AS p_email, t.amount  FROM invoices
            LEFT JOIN users ON invoices.from  = users.id
            LEFT JOIN patients ON invoices.to = patients.id
            LEFT JOIN 
                (SELECT invoice_lists.invoice_code, SUM(invoice_lists.amount) AS amount FROM invoice_lists GROUP BY invoice_lists.invoice_code) AS t ON t.invoice_code = invoices.code
            WHERE invoices.to = ?
        ", [$id]);

        $storages = DB::select("SELECT * FROM patient_storage WHERE patient_id=?", [$id]);

        return view('admin.patient_profile', compact('patient_id', 'datas', 'services', 'invoices', 'storages', 'patient_data', 'short_name'));
    }


    public function profileStore(Request $data)
    {   
        $flag = DB::select("SELECT * FROM patient_notes WHERE category_id = ?", [$data['category']]);

        if ( isset($data['id']) ) {
            $state = Patientprofile::updateOrCreate(
                [
                    'id' => $data['id']
                ],
                [
                'patient_id' => $data['patient_id'],
                'category_id' => $data['category'],
                'teeth_id' => $data['teeth_id'],
                'note' => $data['notes'],
            ]);
            return response()->json(['success'=>'Ajax request submitted successfully', 'state' => $state]);
        } else if (count($flag) == 0 && !isset($data['id']) ) {
            $state = Patientprofile::updateOrCreate(
                [
                    'id' => $data['id']
                ],
                [
                'patient_id' => $data['patient_id'],
                'category_id' => $data['category'],
                'teeth_id' => $data['teeth_id'],
                'note' => $data['notes'],
            ]);
            return response()->json(['success'=>'Ajax request submitted successfully', 'state' => $state]);
        } else if (count($flag) > 0 && !isset($data['id']) ) {
            return response()->json(['success'=>'Ajax request submitted successfully', 'state' => 0]);
        }
        
    }


    public function destroy($id)
    {
            $patient = Patient::findOrFail($id);
            $patient->delete();
            return redirect('/admin/patient')->with('success', 'Patient Data is successfully deleted');
    }

    public function profile_destroy($id)
    {
            $patient = Patientprofile::findOrFail($id);
            $patient->delete();
            return back();
    }

}
