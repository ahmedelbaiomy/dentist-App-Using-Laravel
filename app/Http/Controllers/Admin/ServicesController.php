<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\models\service_category;
use App\Models\Scategory;

class ServicesController extends Controller
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
        $services = DB::select("SELECT services.id, services.service_name, services.price, services.note, services.category_id, service_categories.name AS s_name FROM services
        LEFT JOIN service_categories ON service_categories.id = services.category_id");

        $data = DB::select("SELECT * FROM service_categories");
        $cat_arrs = array();
        foreach($data as $row) { 
            $cat_arrs[] = array(
               "id"     => $row->id,
               "parent" => $row->parent_id,
               "text"   => $row->name
            );
        }

        $datas = json_encode($cat_arrs);
        return view('admin.services', compact('services', 'datas'));
    }


    public function store(Request $data) 
    {
        
        Service::updateOrCreate(
            [
                'id' => $data['id']
            ],
            [
            'service_name' => $data['service_name'],
            'price' => $data['price'],
            'note' => $data['note'],
            'category_id' => $data['category']
        ]);

        service_category::updateOrCreate(
            [
                'id' => $data['id']
            ],
            [
            'name'      => $data['service_name'],
            'parent_id' => $data['category']
        ]);

        return response()->json(['success'=>'Ajax request submitted successfully']);
    }


    public function root_category_store(Request $data) 
    {   
        $id = DB::table('service_categories')->insertGetId(
            ['parent_id' => '#', 'name' => $data['name']]
        );

        return response()->json(['success'=> true, 'id' => $id, 'name' => $data['name']]);
    }

    public function category_store(Request $data) 
    {
        // DB::table('service_categories')->insert([
        //     'parent_id'        => $data['parent_id'],
        //     'name'          => $data['name']
        // ]);
        // return response()->json(['success'=>'Ajax request submitted successfully']);

        $id = DB::table('service_categories')->insertGetId([
            'parent_id' => $data['parent_id'], 
            'name' => $data['name']
            ]);

        return response()->json(['success'=> true, 'id' => $id ]);
    }

    public function category_update(Request $data) 
    {
        DB::table('service_categories')
            ->where('id', $data['id'])
            ->update(['name' => $data['name']]);

        return response()->json(['success'=>'Ajax request submitted successfully']);
    }


    

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect('/admin/services')->with('success', 'Staff Data is successfully deleted');
    }

    public function category_delete(Request $data)
    {
        DB::table('service_categories')->delete($data['id']);

        return redirect('/admin/services')->with('success', 'Staff Data is successfully deleted');
    }

}
