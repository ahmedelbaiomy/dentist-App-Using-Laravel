<?php
require_once '/var/www/appointment20/public/api-odoo/core/functions.php';


function odoo_authenticate($odoo_url_auth,$odoo_db, $odoo_username, $odoo_password){
    $common = ripcord::client($odoo_url_auth);
    $uid = $common->authenticate($odoo_db, $odoo_username, $odoo_password, array());
    return $uid;
}
function odoo_logging_in($odoo_url_exec){
    $models = ripcord::client($odoo_url_exec);
    return $models;
}


function odoo_get_all_quotations($models,$odoo_db, $uid, $odoo_password){
    $results=$models->execute_kw($odoo_db, $uid, $odoo_password,
        'sale.order', 'search_read',
        array(),
        //array('fields'=>array('id', 'name','list_price','standard_price','description','categ_id')));
        array());
    return $results;
}




function odoo_search_read_invoice($models,$odoo_db, $uid, $odoo_password,$invoice_number){
    $results=$models->execute_kw($odoo_db, $uid, $odoo_password,
        'account.move', 'search_read',
        array(array(array('name', '=', $invoice_number))),
        array('fields'=>array('id', 'name','amount_untaxed_signed', 'amount_total_signed','state'), 'limit'=>1));
    return $results;
}
function odoo_search_read_partner($models,$odoo_db, $uid, $odoo_password,$partner_name){
    $results=$models->execute_kw($odoo_db, $uid, $odoo_password,
        'res.partner', 'search_read',
        array(array(array('name', '=', $partner_name))),
        array('fields'=>array('id', 'name'), 'limit'=>1));
    return $results;
}
function odoo_create_partner($models,$odoo_db, $uid, $odoo_password,$datas_invoice){
    $patient_name=($datas_invoice['patient_ar_name'])?$datas_invoice['patient_ar_name']:$datas_invoice['patient_name'];
    $results_odoo_search_partner=odoo_search_read_partner($models,$odoo_db, $uid, $odoo_password,$patient_name);
    if(count($results_odoo_search_partner)==0){
        $partner_id = $models->execute_kw($odoo_db, $uid, $odoo_password,
        'res.partner', 'create',
        array(array(
            'name'=>$patient_name,
            'is_company'=>0,
            'active'=>1,
            'company_id'=>1,
            'type'=>'contact',
            'street'=>$datas_invoice['patient_address'],
            'phone'=>$datas_invoice['patient_phone'],
            'email'=>$datas_invoice['patient_email'],
        )));
        print('********created patient : '.$partner_id);
        return $partner_id;
    }else{
        $partner_id=$results_odoo_search_partner[0]['id'];
        print('********found patient in odoo: '.$partner_id);
        return $partner_id;
    }
}

function odoo_create_request_partner($models,$odoo_db, $uid, $odoo_password,$datas_invoice,$info){
    
        $patient_name=($info[0]['name'])?$info[0]['name']:$info[0]['username'];
    $results_odoo_search_partner=odoo_search_read_partner($models,$odoo_db, $uid, $odoo_password,$patient_name);
    if(count($results_odoo_search_partner)==0){
        $partner_id = $models->execute_kw($odoo_db, $uid, $odoo_password,
        'res.partner', 'create',
        array(array(
            'name'=>$patient_name,
            'is_company'=>0,
            'active'=>1,
            'company_id'=>1,
            'type'=>'contact',
            'street'=>$info[0]['state'],
            'phone'=>$info[0]['phone'],
            'email'=>$info[0]['email'],
        )));
        print('********created patient : '.$partner_id);
        return $partner_id;
    }else{
        $partner_id=$results_odoo_search_partner[0]['id'];
        print('********found patient in odoo: '.$partner_id);
        return $partner_id;
    }
    
}
function odoo_create_partner_doctor($models,$odoo_db, $uid, $odoo_password,$datas_invoice){
    $doctor_name=$datas_invoice['doctor_name'];
    $results_odoo_search_partner=odoo_search_read_partner($models,$odoo_db, $uid, $odoo_password,$doctor_name);
    //var_dump($results_odoo_search_partner);
    if(count($results_odoo_search_partner)==0){
    $partner_doctor_id = $models->execute_kw($odoo_db, $uid, $odoo_password,
    'res.partner', 'create',
    array(array(
        'name'=>$doctor_name,
        'is_company'=>0,
        'function'=>'doctor',
        'type'=>'contact',
        'company_id'=>1,
        'active'=>1,
    )));
    print('********created patient : '.$partner_doctor_id);
    return $partner_doctor_id;
    }else{
        $partner_doctor_id=$results_odoo_search_partner[0]['id'];
        print('********found doctor in odoo: '.$partner_doctor_id);
        return $partner_doctor_id;
    }
}

function odoo_create_partner_request_doctor($models,$odoo_db, $uid, $odoo_password,$info){
    $doctor_name=$info[0]['name'] ? $info[0]['name'] : $info[0]['username'];
    $results_odoo_search_partner=odoo_search_read_partner($models,$odoo_db, $uid, $odoo_password,$doctor_name);
    //var_dump($results_odoo_search_partner);
    if(count($results_odoo_search_partner)==0){
    $partner_doctor_id = $models->execute_kw($odoo_db, $uid, $odoo_password,
    'res.partner', 'create',
    array(array(
        'name'=>$doctor_name,
        'is_company'=>0,
        'function'=>'doctor',
        'type'=>'contact',
        'company_id'=>1,
        'active'=>1,
    )));
    print('********created patient : '.$partner_doctor_id);
    return $partner_doctor_id;
    }else{
        $partner_doctor_id=$results_odoo_search_partner[0]['id'];
        print('********found doctor in odoo: '.$partner_doctor_id);
        return $partner_doctor_id;
    }
}
function odoo_update_invoice($models,$odoo_db, $uid, $odoo_password,$datas_invoice,$invoice_items,$found_odoo_id){
    print("********start update invoice: $found_odoo_id");
    //echo "<br>";
   // echo $datas_invoice['amount'];
   // echo "<br>";
    // echo $datas_invoice['amount_with_tax'];
     
    //'draft','not_paid','partial_paid','paid','cancelled'
    $stateArray=['draft'=>'draft','not_paid'=>'draft','partial_paid'=>'draft','paid'=>'posted','cancelled'=>'cancelled'];
    
    // if($datas_invoice['number']==2021070041){
    //      $rsUpdate =$models->execute_kw($odoo_db, $uid, $odoo_password, 'account.move', 'write',
    // array(array($found_odoo_id), array( 
    //     'state'=>$stateArray[$datas_invoice['status']],
    //   )));
        
    //       $rsUpdate =$models->execute_kw($odoo_db, $uid, $odoo_password, 'account.move', 'write',
    // array(array($found_odoo_id), array( 
       
    //     'amount_untaxed_signed'=>$datas_invoice['amount'],
    //     'amount_total_signed'=>$datas_invoice['amount_with_tax'])));
    // }
  //  else{
        $rsUpdate =$models->execute_kw($odoo_db, $uid, $odoo_password, 'account.move', 'write',
    array(array($found_odoo_id), array( 
        'state'=>$stateArray[$datas_invoice['status']],
       )));
       
      $rsUpdate =$models->execute_kw($odoo_db, $uid, $odoo_password, 'account.move', 'write',
    array(array($found_odoo_id), array( 
       
        'amount_untaxed_signed'=>$datas_invoice['amount'],
        'amount_total_signed'=>$datas_invoice['amount_with_tax'])));
  //  }
   // print_r($rsUpdate);
									//	echo "here".$rsUpdate;
    if($rsUpdate){
        if($stateArray[$datas_invoice['status']]!='draft'){
            //delete first
           // odoo_delete_invoice_lines($found_odoo_id,$models,$odoo_db, $uid, $odoo_password);
            //create lines
          //  odoo_create_lines($found_odoo_id,$models,$odoo_db, $uid, $odoo_password,$invoice_items);
        }
        
    }
    print("********Updated invoice: $rsUpdate");
    print("********end update invoice: $found_odoo_id");
    return $odoo_invoice_id;
}

function odoo_update_request_invoice($models,$odoo_db, $uid, $odoo_password,$datas_invoice,$invoice_items,$found_odoo_id,$status,$db){
   // print_r($datas_invoice);die;
    print("********start update invoice: $found_odoo_id");
    //echo "<br>";
   // echo $datas_invoice['amount'];
   // echo "<br>";
    // echo $datas_invoice['amount_with_tax'];
     
    //'draft','not_paid','partial_paid','paid','cancelled'
    $stateArray=['draft'=>'draft','not_paid'=>'draft','partial_paid'=>'draft','paid'=>'posted','cancelled'=>'cancelled'];
      
       $return_price = get_request_price($invoice_items);
  
  
   if($status!=$datas_invoice['status'] && $status!='' && $status!='draft'){
       
       change_status_new($status,$db,$datas_invoice['invoice_number']);
       
       
        $rsUpdate =$models->execute_kw($odoo_db, $uid, $odoo_password, 'account.move', 'write',
    array(array($found_odoo_id), array( 
        'state'=>$stateArray[$status],
       )));
       
      $rsUpdate =$models->execute_kw($odoo_db, $uid, $odoo_password, 'account.move', 'write',
    array(array($found_odoo_id), array( 
       
        'amount_untaxed_signed'=>$return_price['tax'],
        'amount_total_signed'=>$return_price['total'] )));
   }
   // print_r($rsUpdate);
									//	echo "here".$rsUpdate;
    if($rsUpdate){
        if($stateArray[$datas_invoice['status']]!='posted'){
            //delete first
          //  odoo_delete_invoice_lines($found_odoo_id,$models,$odoo_db, $uid, $odoo_password);
            //create lines
            //odoo_create_request_lines($found_odoo_id,$models,$odoo_db, $uid, $odoo_password,$invoice_items);
        }
        
    }
    print("********Updated invoice: $rsUpdate");
    print("********end update invoice: $found_odoo_id");
    return $odoo_invoice_id;
}

function get_request_price($data){
    for($i=0; $i<count($data); $i++){
                $tax +=  $data[$i]['rate'];
                $total +=  $data[$i]['total'];
    }
    
    return array('tax'=>$tax,'total'=>$total);
}


function odoo_create_invoice($models,$odoo_db, $uid, $odoo_password,$datas_invoice,$invoice_items){
    //create partner
    $partner_id=odoo_create_partner($models,$odoo_db, $uid, $odoo_password,$datas_invoice);
    //create doctor
    $partner_doctor_id=odoo_create_partner_doctor($models,$odoo_db, $uid, $odoo_password,$datas_invoice);
    print("<p>Partner patient created with id '{$partner_id}'</p>");
    print("<p>Partner doctor created with id '{$partner_doctor_id}'</p>");
  
    $invoice_model='account.move';
    $odoo_invoice_id = $models->execute_kw($odoo_db, $uid, $odoo_password,
        $invoice_model, 'create',
        array(array(
            'name'=>$datas_invoice['number'],
            'date'=>$datas_invoice['created_at'],
            'invoice_date'=>$datas_invoice['bill_date'],
            'invoice_date_due'=>$datas_invoice['due_date'],
            'partner_id'=>$partner_id,//patient id
            'ref' => $partner_doctor_id,
            'narration' => $datas_invoice['note'],
            'state' => 'draft',
            'type' => 'out_invoice',
            'type_name' => 'Invoice',
            
            //'invoice_user_id' => $partner_doctor_id,//doctor id,
            'company_id' => 1,//company drtooth
			// 'amount_untaxed_signed' => $datas_invoice['amount'],
			// 'amount_total_signed' => $datas_invoice['amount_with_tax'],
		
			//'check_move_validity'=>false
        )
    ));
    if(is_int($odoo_invoice_id)){
        print("<p>Invoice created with id '{$odoo_invoice_id}'</p>");
        // Create invoice line(s)
        odoo_create_lines($odoo_invoice_id,$models,$odoo_db, $uid, $odoo_password,$invoice_items);
        
        //odoo_update_request_invoice($models,$odoo_db, $uid, $odoo_password,$datas_invoice,$invoice_items,$odoo_invoice_id,'paid');
       // odoo_update_request_invoice($models,$odoo_db, $uid, $odoo_password,$datas_invoice,$invoice_items,$odoo_invoice_id,'draft');
    }
    else{
        print("<p>Error: ");
        print($invoice_id['faultString']);
        print("</p>");
    }
    return $odoo_invoice_id;
}
/*---------------------------------------------------------------------Get Request Odoo------------------------------------------------------------------------------------------------------------*/

function odoo_create_request_invoice($models,$odoo_db, $uid, $odoo_password,$datas_invoice,$invoice_items,$info){
    //create partner
    $partner_id=odoo_create_request_partner($models,$odoo_db, $uid, $odoo_password,$datas_invoice,$info);
    //create doctor
   // $partner_doctor_id=odoo_create_partner_request_doctor($models,$odoo_db, $uid, $odoo_password,$info);
    print("<p>Partner patient created with id '{$partner_id}'</p>");
    print("<p>Partner doctor created with id '{$partner_doctor_id}'</p>");
    
      $amount = get_request_price($invoice_items);
     
    $invoice_model='account.move';
    $odoo_invoice_id = $models->execute_kw($odoo_db, $uid, $odoo_password,
        $invoice_model, 'create',
        array(array(
            'name'=>$datas_invoice['invoice_number'],
            'date'=>$datas_invoice['created_at'],
            'invoice_date'=>$datas_invoice['bill_date'],
            'invoice_date_due'=>$datas_invoice['due_date'],
            'partner_id'=>$partner_id,//patient id
            'ref' => '',
            'narration' => $info[0]['description'],
            'state' => 'draft',
            'type' => 'out_invoice',
            'type_name' => 'Invoice',
            //'invoice_user_id' => $partner_doctor_id,//doctor id
            'company_id' => 1,//company drtooth
			//'amount_untaxed_signed' => $amount['tax'],
			//'amount_total_signed' => $amount['total'],
		//	'check_move_validity'=>false,
		 //   'amount_residual'=>$amount['total'],
        )
    ));
    if(is_int($odoo_invoice_id)){
        print("<p>Invoice created with id '{$odoo_invoice_id}'</p>");
        // Create invoice line(s)
        odoo_create_request_lines($odoo_invoice_id,$models,$odoo_db, $uid, $odoo_password,$invoice_items);
    }
    else{
        print("<p>Error: ");
        print($invoice_id['faultString']);
        print("</p>");
    }
    return $odoo_invoice_id;
}
function odoo_create_request_invoice_bill($models,$odoo_db, $uid, $odoo_password,$datas_invoice,$invoice_items,$info){
    //create partner
    $partner_id=odoo_create_request_partner($models,$odoo_db, $uid, $odoo_password,$datas_invoice,$info);
    //create doctor
   // $partner_doctor_id=odoo_create_partner_request_doctor($models,$odoo_db, $uid, $odoo_password,$info);
    print("<p>Partner patient created with id '{$partner_id}'</p>");
    print("<p>Partner doctor created with id '{$partner_doctor_id}'</p>");
    
      $amount = get_request_price($invoice_items);
     
    $invoice_model='account.move';
    $odoo_invoice_id = $models->execute_kw($odoo_db, $uid, $odoo_password,
        $invoice_model, 'create',
        array(array(
            'name'=>$datas_invoice['invoice_number'],
            'date'=>$datas_invoice['created_at'],
            'invoice_date'=>$datas_invoice['bill_date'],
            'invoice_date_due'=>$datas_invoice['due_date'],
            'partner_id'=>$partner_id,//patient id
            'ref' => '',
            'narration' => $info[0]['description'],
            'state' => 'draft',
            'type' => 'in_invoice',
            'type_name' => 'Invoice',
            'company_id' => 1,
        )
    ));
    if(is_int($odoo_invoice_id)){
        print("<p>Invoice created with id '{$odoo_invoice_id}'</p>");
        // Create invoice line(s)
        odoo_create_request_lines($odoo_invoice_id,$models,$odoo_db, $uid, $odoo_password,$invoice_items);
    }
    else{
        print("<p>Error: ");
        print($invoice_id['faultString']);
        print("</p>");
    }
    return $odoo_invoice_id;
}
function odoo_create_lines($odoo_invoice_id,$models,$odoo_db, $uid, $odoo_password,$invoice_items){
    print("<p>---- START CREATION LINES  </p>");
    print("<p>---- TOTAL LINES :".count($invoice_items)." </p>");
   // print_r($invoice_items);
    if(count($invoice_items)>0){
        foreach($invoice_items as $row){
            $invoice_line_id = $models->execute_kw($odoo_db, $uid, $odoo_password,
            'account.move',
            'write',
                array(
                    array($odoo_invoice_id), // Reference to the invoice itself
                    array(
                        'invoice_line_ids' => array(
                            array(0, 0, array(
                                'name'=>$row['code']." ".$row['service_name'], // Invoice line description
                                'price_unit'=>floatval($row['quantity'])*floatval($row['rate']),
                                'quantity'=>floatval($row['quantity']),
                                //'product_id'=>37, // Products can be found from product_product 
                                // 'account_id'=>17, // Accounting accounts can be found from account_account            
                            ))
                        )
                    )
                )
                // ['context'=>['check_move_validity'=>False]]
            );


            if(is_int($invoice_line_id)){
                print("<p>Invoice line created with id '{$invoice_line_id}'</p>");
            }
            else{
                print("<p>Error: ");
                print($invoice_line_id['faultString']);
                print("</p>");
            }
        }
    }
    print("<p>----END CREATION LINES </p>");
}



function odoo_create_request_lines($odoo_invoice_id,$models,$odoo_db, $uid, $odoo_password,$invoice_items){
    print("<p>---- START CREATION LINES  </p>");
    print("<p>---- TOTAL LINES :".count($invoice_items)." </p>");
   // print_r($invoice_items);
    if(count($invoice_items)>0){
        foreach($invoice_items as $row){
            
            $invoice_line_id = $models->execute_kw($odoo_db, $uid, $odoo_password,
            'account.move',
            'write',
                array(
                    array($odoo_invoice_id), // Reference to the invoice itself
                    array(
                        'invoice_line_ids' => array(
                            array(0, 0, array(
                                'name'=>$row['name'], // Invoice line description
                                'price_unit'=>floatval($row['rate']),
                                'quantity'=>floatval($row['quantity']),
                                //'product_id'=>37, // Products can be found from product_product 
                                // 'account_id'=>17, // Accounting accounts can be found from account_account            
                            ))
                        )
                    )
                )
                /*array(
                    array(
                        'move_id'=>$odoo_invoice_id, // Reference to the invoice itself
                        'name'=>$row['name'], // Invoice line description
                        'price_unit'=>floatval($row['rate']),
                       // 'credit'=>0,
                        
                       // 'debit'=>floatva($row['quantity'])*floatva($row['rate']),
                       //'credit'=>floatva($row['quantity'])*floatva($row['rate']),
                        //'debit'=>floatva($row['quantity'])*floatva($row['rate']),
                        
                        //'debit'=>-floatva($row['quantity'])*floatva($row['rate']),
                        'quantity'=>floatva($row['quantity']),
                        //'product_id'=>41, // Products can be found from product_product 
                        'account_id'=>17, // Accounting accounts can be found from account_account            
                        
                    )
                ),
                ['context'=>['check_move_validity'=>False]]*/
            );
            
           
            
           
            
            


            if(is_int($invoice_line_id)){
                print("<p>Invoice line created with id '{$invoice_line_id}'</p>");
            }
            else{
                print("<p>Error: ");
                print($invoice_line_id['faultString']);
                print("</p>");
            }
        }
    }
    print("<p>----END CREATION LINES </p>");
}
function odoo_delete_invoice($odoo_invoice_id,$models,$odoo_db, $uid, $odoo_password){
    $rs=$models->execute_kw($odoo_db, $uid, $odoo_password,
    'account.move', 'unlink',
    array(array($odoo_invoice_id)));
    var_dump($rs);
    // check if the deleted record is still in the database
    $rs2=$models->execute_kw($odoo_db, $uid, $odoo_password,
        'account.move', 'search',
    array(array(array('id', '=', $odoo_invoice_id))));
    var_dump($rs2);
}
function odoo_search($odoo_invoice_id,$models,$odoo_db, $uid, $odoo_password){
    $records = $models->execute_kw($odoo_db, $uid, $odoo_password,
    'account.move', 'read', array([$odoo_invoice_id]));
    var_dump($records);
}
function odoo_delete_invoice_lines($odoo_invoice_id,$models,$odoo_db, $uid, $odoo_password){
    print("<p>----START DELETE LINES </p>");
    $rsLines=odoo_search_read_lines_invoice($models,$odoo_db, $uid, $odoo_password,$odoo_invoice_id);
   // var_dump($rsLines);die;
    if(count($rsLines)){
        foreach($rsLines as $row){
            //var_dump($row["id"]);
            $results=$models->execute_kw($odoo_db, $uid, $odoo_password,
            'account.move.line', 'unlink',
            array(array($row["id"])));
            var_dump($results);
        }
    }
    //exit(); 
    print("<p>----END DELETE LINES </p>");
}
function odoo_search_read_lines_invoice($models,$odoo_db, $uid, $odoo_password,$odoo_invoice_id){
    $results=$models->execute_kw($odoo_db, $uid, $odoo_password,
        'account.move.line', 'search_read',
        array(array(array('move_id', '=', $odoo_invoice_id))),
        array('fields'=>array('id')));
    return $results;
}
function odoo_update_product_out_qty($odoo_product_id,$qty,$models,$odoo_db, $uid, $odoo_password){
    print("<p>---- START UPDATE PRODCUT OUT QTY  </p>");
    print("<p>---- PRODUCT_ID :".$odoo_product_id ." QUANTY :". $qty ." </p>");

    $models->execute_kw($odoo_db, $uid, $odoo_password,
        'product.template',
        'do_out_qty',
            array(
                array($odoo_product_id), // Reference to the invoice itself
                $qty
            ),
            ['context'=>['inventory_mode'=>True]]
    );

    print("<p>----END UPDATE PRODUCT OUT QTY </p>");
}
