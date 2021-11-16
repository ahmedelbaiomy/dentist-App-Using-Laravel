<?php
require_once 'core/db.php';
require_once 'core/functions.php';
require_once('ripcord/ripcord.php');
require_once 'core/odoo_functions.php';


$invoice_id = (isset($_GET["id"]) && !empty($_GET["id"]))?$_GET["id"]:0;


$datas=get_requests_from_db($invoice_id,$db);


//echo "<pre>";
//print_r($datas);die;


if(count($datas)>0){
        $uid =odoo_authenticate($odoo_url_auth,$odoo_db, $odoo_username, $odoo_password);
       // print("<p>Your current user id is '${uid}'</p>"); exit;
        $models = odoo_logging_in($odoo_url_exec);
	
    foreach($datas as $datas_invoice){
        $invoice_id = $datas_invoice['invoice_number'];
		
        $invoice_items = get_request_items($invoice_id,$db);
  
      //  $resultsArray=get_amounts_invoice($invoice_id,$db);var_dump($datas);exit;odoo_update_invoice
        //var_dump($resultsArray);
		
        $invoice_number=$datas_invoice['invoice_number'];
		
        /* Search if invoice number exist in odoo */
		
		
         $results_odoo_search_invoice=odoo_search_read_invoice($models,$odoo_db, $uid, $odoo_password,$invoice_number);
       // print_r($results_odoo_search_invoice);die;
         
        
        if(count($results_odoo_search_invoice)==0){
            print("<p>Invoice number '$invoice_number' not sync to odoo </p>");
            
            $information = get_information_from_db($datas_invoice['login_id'],$db);
           
            
            //Create invoice in odoo
            $odoo_invoice_id=odoo_create_request_invoice_bill($models,$odoo_db, $uid, $odoo_password,$datas_invoice,$invoice_items,$information);
           // update
            if($odoo_invoice_id>0){
                $rsUpdate=update_invoice_request_odoo_id($invoice_id,$odoo_invoice_id,$db);
                
                // update product quantity
                $reqs = get_request_items_from_db($datas_invoice['request_id'], $db);
                if (count($reqs) > 0) {
                    $odoo_product_id = intval($reqs[0]['odoo_id']);
                    $quantity = intval($reqs[0]['quantity']);
                    odoo_update_product_out_qty($odoo_product_id, $quantity,$models,$odoo_db, $uid, $odoo_password);
                }
            }
    
        }else{
            $found_odoo_id=$results_odoo_search_invoice[0]['id'];
          
       
            print("<p>Invoice number '$invoice_number' already synced to odoo . id founded = $found_odoo_id</p>");
            $odoo_invoice_id=odoo_update_request_invoice($models,$odoo_db, $uid, $odoo_password,$datas_invoice,$invoice_items,$found_odoo_id,'paid',$db);
            
           // update
            if($odoo_invoice_id>0){
                $rsUpdate=update_invoice_request_odoo_id($invoice_id,$odoo_invoice_id,$db);
            }
        }
    }
}

?>