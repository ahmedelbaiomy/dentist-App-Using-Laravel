<?php
require_once 'core/db.php';
require_once 'core/functions.php';
require_once('ripcord/ripcord.php');
require_once 'core/odoo_functions.php';

$invoice_id = (isset($_GET["id"]) && !empty($_GET["id"]))?$_GET["id"]:0;
//echo $invoice_id;die;
$datas=get_invoices_from_db($invoice_id,$db);//var_dump($datas);exit;
//echo "here";die;
if(count($datas)>0){
        $uid =odoo_authenticate($odoo_url_auth,$odoo_db, $odoo_username, $odoo_password);
       // print("<p>Your current user id is '${uid}'</p>"); exit;
        $models = odoo_logging_in($odoo_url_exec);
	
    foreach($datas as $datas_invoice){
        $invoice_id = $datas_invoice['id'];
		if($datas_invoice['tax_percentage']){
		   
			$datas_invoice['amount_with_tax'] = $datas_invoice['amount']+($datas_invoice['amount']*($datas_invoice['tax_percentage']/100));
			$datas_invoice['amount_with_tax'] = number_format($datas_invoice['amount_with_tax'], 2, '.', '');
			
		}
		else{
		 $datas_invoice['amount_with_tax'] = $datas_invoice['amount'];
	
		}
	//	echo $datas_invoice['amount_with_tax'];
	//	echo $datas_invoice['amount'];
		
	
        //var_dump($datas_invoice);
		
        $invoice_items=get_invoice_items($invoice_id,$db);//var_dump($datas);exit;
        
	//	print_r($invoice_items);die;
        //var_dump($datas_invoice);exit();
      //  $resultsArray=get_amounts_invoice($invoice_id,$db);var_dump($datas);exit;odoo_update_invoice
        //var_dump($resultsArray);
		
        $invoice_number=$datas_invoice['number'];
		
        /* Search if invoice number exist in odoo */
		
		
         $results_odoo_search_invoice=odoo_search_read_invoice($models,$odoo_db, $uid, $odoo_password,$invoice_number);
         
         if($datas_invoice['number']==2021070043){
           //  echo "<pre>";
           //  echo "invoice id:" . $invoice_id;
           //  print_r($invoice_items);
           // echo "here";
             //$datas_invoice['status'] = 'paid';
          //   $datas_invoice['amount'] = 228.00;
          //   $datas_invoice['amount_with_tax'] = 259.00;
            //print_r($results_odoo_search_invoice);die;
        }
        if(count($results_odoo_search_invoice)==0){
            print("<p>Invoice number '$invoice_number' not sync to odoo </p>");
            
            //Create invoice in odoo
            $odoo_invoice_id=odoo_create_invoice($models,$odoo_db, $uid, $odoo_password,$datas_invoice,$invoice_items);
           // update
            if($odoo_invoice_id>0){
                $rsUpdate=update_invoice_odoo_id($invoice_id,$odoo_invoice_id,$db);
            }
    
        }else{
            $found_odoo_id=$results_odoo_search_invoice[0]['id'];
    
            print("<p>Invoice number '$invoice_number' already synced to odoo . id founded = $found_odoo_id</p>");
            $odoo_invoice_id=odoo_update_invoice($models,$odoo_db, $uid, $odoo_password,$datas_invoice,$invoice_items,$found_odoo_id);
            
           // update
            if($odoo_invoice_id>0){
                $rsUpdate=update_invoice_odoo_id($invoice_id,$odoo_invoice_id,$db);
            }
        } 
    }
}