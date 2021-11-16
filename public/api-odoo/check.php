<?php
require_once 'core/db.php';
require_once 'core/functions.php';
require_once('ripcord/ripcord.php');
require_once 'core/odoo_functions.php';

$uid =odoo_authenticate($odoo_url_auth,$odoo_db, $odoo_username, $odoo_password);
//print("<p>Your current user id is '${uid}'</p>");
$models = odoo_logging_in($odoo_url_exec);

$rs=$models->execute_kw($odoo_db, $uid, $odoo_password,
    'product.template', 'fields_get',
    array(), array('attributes' => array('string', 'help', 'type','required')));

var_dump($rs);exit();

//$results_odoo_search_invoice=odoo_search_read_invoice($models,$odoo_db, $uid, $odoo_password,$invoice_number);
$rs=$models->execute_kw($odoo_db, $uid, $odoo_password,
        'account.move.line', 'search_read',
        array(array(array('move_id', '=', 2))),
        array());
var_dump($rs);exit();


$invoice_model='account.move';
$odoo_invoice_id = $models->execute_kw($odoo_db, $uid, $odoo_password,
        $invoice_model, 'create',
        array(array(
            'name'=>'MDEVHD0027',
            'date'=>'2021-07-28',
            'invoice_date'=>'2021-07-28',
            'invoice_date_due'=>'2021-07-30',
            'partner_id'=>45,//patient id
            'ref' => 'REF0027',
            'narration' => '',
            'state' => 'draft',
            'type' => 'out_invoice',
            'type_name' => 'Invoice',
            'to_check' => false,
            'journal_id' => 1,
            'company_currency_id' => 153,
            'currency_id' => 153,
            'company_id' => 1,//company drtooth
            'amount_untaxed'=> 450,
            'amount_tax'=> 0,
            'amount_total'=> 450,
            'amount_residual'=> 450,
			'amount_untaxed_signed' => 450,
			'amount_tax_signed' => 0,
			'amount_total_signed' => 450,
			'amount_residual_signed' => 450,
        )
    ));
    if(is_int($odoo_invoice_id)){
        print("<p>Invoice created with id '{$odoo_invoice_id}'</p>");
        // Create invoice line(s)
        //odoo_create_lines($odoo_invoice_id,$models,$odoo_db, $uid, $odoo_password,$invoice_items);

        /* $invoice_line_id = $models->execute_kw($odoo_db, $uid, $odoo_password,
            'account.move.line',
            'create',
                array(
                    array(
                        'move_id'=>$odoo_invoice_id, // Reference to the invoice itself
                        'name'=>'THIS IS A TEST HIDAOUI MOUHSSIN FROM API', // Invoice line description
                        'quantity'=>1,
                        'price_unit'=>450,
                        'discount'=>0,
                        'debit'=>0,
                        'credit'=>450,
                        'balance'=>-450,
                        'price_subtotal'=>450,
                        'price_total'=>450,
                        'account_id'=>48,
                    )
                ),
                ['context'=>['check_move_validity'=>False]]
            ); */
        /* $invoice_line_id = $models->execute_kw($odoo_db, $uid, $odoo_password,
            'account.move.line',
            'create',
                array(
                    array(
                        'move_id'=>$odoo_invoice_id, // Reference to the invoice itself
                        'name'=>false, // Invoice line description
                        'quantity'=>1,
                        'price_unit'=>-450,
                        //'discount'=>0,
                        'debit'=>450,
                        'credit'=>0,
                        //'balance'=>-450,
                        //'price_subtotal'=>-450,
                        //'price_total'=>-450,
                        'account_id'=>2,
                    )
                ),
                ['context'=>['check_move_validity'=>False]]
            );  */
        $invoice_line_id = $models->execute_kw($odoo_db, $uid, $odoo_password,
            'account.move.line',
            'create',
                array(
                    array(
                        'move_id'=>$odoo_invoice_id, // Reference to the invoice itself
                        'name'=>'THIS IS A TEST HIDAOUI MOUHSSIN FROM API', // Invoice line description
                        //'quantity'=>1,
                        //'price_unit'=>450,
                        //'discount'=>0,
                        //'debit'=>0,
                        'credit'=>450,
                        //'balance'=>-450,
                        //'price_subtotal'=>450,
                        //'price_total'=>450,
                        'account_id'=>1,
                    )
                ),
                ['context'=>['check_move_validity'=>False]]
            );
            if(is_int($invoice_line_id)){
                print("<p>Invoice line created with id '{$invoice_line_id}'</p>");
            }
            else{
                print("<p>Error: ");
                print($invoice_line_id['faultString']);
                print("</p>");
            }

            /* array(2) {
                [0]=>
                array(64) {

                  ["price_unit"]=>
                  float(-350)
                  ["discount"]=>
                  float(0)
                  ["debit"]=>
                  float(350)
                  ["credit"]=>
                  float(0)
                  ["balance"]=>
                  float(350)
                  ["amount_currency"]=>
                  float(0)
                  ["price_subtotal"]=>
                  float(-350)
                  ["price_total"]=>
                  float(-350)

                [1]=>
                array(64) {
                    ["account_id"]=>48
                  ["name"]=>
                  string(21) "TEST LINE BY MOUHSSIN"
                  ["quantity"]=>
                  float(1)
                  ["price_unit"]=>
                  float(350)
                  ["discount"]=>
                  float(0)
                  ["debit"]=>
                  float(0)
                  ["credit"]=>
                  float(350)
                  ["balance"]=>
                  float(-350)
                  ["amount_currency"]=>
                  float(0)
                  ["price_subtotal"]=>
                  float(350)
                  ["price_total"]=>
                  float(350)

                }
              } */

    }
    else{
        print("<p>Error: ");
        print($invoice_id['faultString']);
        print("</p>");
    }
