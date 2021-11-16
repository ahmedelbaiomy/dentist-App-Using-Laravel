<?php
require_once 'core/db.php';
require_once 'core/functions.php';
require_once('ripcord/ripcord.php');
require_once 'core/odoo_functions.php';

$product_odoo_id = (isset($_GET["id"]) && !empty($_GET["id"]))?$_GET["id"]:0;
$product_odoo_id=15;

$uid =odoo_authenticate($odoo_url_auth,$odoo_db, $odoo_username, $odoo_password);
//print("<p>Your current user id is '${uid}'</p>");
$models = odoo_logging_in($odoo_url_exec);



/* $results=$models->execute_kw($odoo_db, $uid, $odoo_password,
        'stock.quant', 'search_read',
        array(array(array('product_id', '=', 15))),
        array());

        var_dump($results); */

$odoo_id = $models->execute_kw($odoo_db, $uid, $odoo_password,
        'stock.change.product.qty', 'create',
        array(array(
            'product_id'=>15,
            'product_tmpl_id'=>9,
            'new_quantity'=>240,
        )
));

/* 'stock.change.product.qty', 'create', [{

    'product_id': 1,
    
    'product_tmpl_id': 285,
    
    'new_quantity': 11,
    
    }]) */

var_dump($odoo_id);
exit();





$results_odoo_search_products=odoo_search_read_products($models,$odoo_db, $uid, $odoo_password);
var_dump($results_odoo_search_products);exit();
function odoo_search_read_products($models,$odoo_db, $uid, $odoo_password){
    $results=$models->execute_kw($odoo_db, $uid, $odoo_password,
        'product.template', 'search_read',
        array(),
        array('fields'=>array('id', 'name','list_price','standard_price','description','categ_id','qty_available','virtual_available','incoming_qty','outgoing_qty','location_id','warehouse_id')));
    return $results;
}
/* $results_odoo_search_products=odoo_search_read_products($models,$odoo_db, $uid, $odoo_password);
var_dump($results_odoo_search_products);exit();

function odoo_search_read_products($models,$odoo_db, $uid, $odoo_password){
    $results=$models->execute_kw($odoo_db, $uid, $odoo_password,
        'product.template', 'search_read',
        array(),
        array());
    return $results;
} */

/*
    ["qty_available"]=>
    float(150)
    ["virtual_available"]=>
    float(270)
    ["incoming_qty"]=>
    float(120)
    ["outgoing_qty"]=>
    float(0)
*/
$qty=30;
if($product_odoo_id>0 && $qty>=0){
    $rs=odoo_update_product_qty_available($models,$odoo_db, $uid, $odoo_password,$product_odoo_id,$qty);
}
