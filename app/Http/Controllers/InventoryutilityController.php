<?php

namespace App\Http\Controllers;

use App\Inventoryutility;
use Illuminate\Http\Request;
use App\Subinventorytransfer;
use App\Product;
use DB;
class InventoryutilityController extends Controller
{
	
	//SUB INVENTORY MTL TRANSACTIONS

public  function Mtlsubtransaction($request)
{
 //dd($request);
//***************************************** REQUIRED DATA SOURCES*****************************************************//
$req_cnt=count($request);

//dd($req_cnt);
$from_data_mtl_insert_id_array=$to_data_mtl_insert_id_array=array();
	
// foreach($request as $key=>$value)
// { 

	$product= Product::find($request['product_id']); 
	$product= json_decode( json_encode($product),true);

	$trsnsType=\DB::table('m_transaction_types_t')->where('transaction_type_code','SUB INVENTORY TRANSFER')->get(); //dd($trsnsType);
	$trsnsType= json_decode( json_encode($trsnsType),true);

	$trx_source_type_id=$trsnsType[0]['transaction_source_id'];
	$trx_action_id=$trsnsType[0]['transaction_action_id'];
	$trx_type_id=$trsnsType[0]['transaction_type_id'];
	$trx_source_hdr_id='';
	$trx_source_line_id='';

	$trx_uom=$product['trx_uom_id'];
	$line_number='';
	$trx_date=date('Y-m-d');
	$trx_cost='';
	$period_id='';
	$trx_reference='';
	$gl_codecombination_id='';
	//$crncy=\DB::table('account_currency_t')->where('currency_code','INR')->get();
	//$currency_code=$crncy[0]->currecncy_id;
	$currency_code='';
	$project_id='';
	//$created_by=\Session::get('uid');;
	$created_by=\Session::get('id');
	$created_date=date('Y-m-d');


		// FROM SAVE ON MTL TRANSACTION
		$from_data_mtl_and_qoh=array(

		//FROM 	MTL SAVE ARRAY  
		"trx_source_type_id" =>$trx_source_type_id,
		"trx_source_hdr_id" => $trx_source_type_id,//
		"trx_source_line_id" =>$trx_source_line_id,//
		"trx_type_id" => $trx_type_id,
		"trx_action_id" =>$trx_action_id,
		"line_number" => $line_number,
		"product_id" =>$request['product_id'],  
		"subinventory_id" =>$request['frm_subinv_id'],  
		"locator_id" =>$request['frm_loc_id'], 
		"trx_qty" => "-".$request['receive_qty'], 
		"trx_uom" =>$trx_uom,  
		"trx_date" =>$trx_date,  //cur date
		"period_id" =>$period_id, //
		"trx_reference" =>$trx_reference,
		"gl_codecombination_id" =>$gl_codecombination_id, // 
		"trx_cost" =>$trx_cost,  //
		"currency_code" =>$currency_code, //currency code table
		"project_id" =>$project_id,//
		"created_by" =>$created_by,//
		"created_at" =>$created_date,//
		"company_id" => $request['frm_cmp_id']

		//FROM 	MTL SAVE ARRAY  

		);


	$from_data_mtl_insert_id = DB::table('m_material_trx_t')->insertGetId($from_data_mtl_and_qoh);
     array_push($from_data_mtl_insert_id_array,$from_data_mtl_insert_id);


		$data_from_qoh=array(
        "qoh_source" => "SUB INVENTORY TRANSFER",  
		"product_id" => $request['product_id'],  
		"subinventory_id" => $request['frm_subinv_id'],  
		"locator_id" =>$request['frm_loc_id'], 
		"qoh_trx_qty" => "-".$request['receive_qty'],   
		"qoh_uom_code_id" => $trx_uom, 
		"company_id" => $request['frm_cmp_id'] ,   
		"create_trx_id" => $from_data_mtl_insert_id,  
		"update_trx_id" =>'',
		"created_by" =>$created_by,//
		"created_at" =>$created_date//
		);
		//FROM 	QOH SAVE ARRAY  



		$data_qoh_insert_id=\DB::table('i_qoh_detail_t')->insert($data_from_qoh);


// FROM SAVE ON MTL TRANSACTION

//*************************************************************FROM MTL TRANSACTION & QOH AREA*****************************************************



//*************************************************************TO MTL TRANSACTION & QOH AREA*****************************************************

		// TO SAVE ON MTL TRANSACTION
		$to_data_mtl_and_qoh=array(  
		//TO MTL SAVE ARRAY  
		"trx_source_type_id" =>$trx_source_type_id,
		"trx_source_hdr_id" => $trx_source_type_id,//
		"trx_source_line_id" =>$trx_source_line_id,//
		"trx_type_id" => $trx_type_id,
		"trx_action_id" =>$trx_action_id,
		"line_number" => $line_number,
		"product_id" => $request['product_id'],  
		"subinventory_id" => $request['to_subinv_id'],  
		"locator_id" =>$request['to_loc_id'], 
		"trx_qty" => $request['receive_qty'], 
		"trx_uom" =>$trx_uom,  
		"trx_date" =>$trx_date,  //cur date
		"period_id" =>$period_id, //
		"trx_reference" =>$trx_reference,
		"gl_codecombination_id" =>$gl_codecombination_id, // 
		"trx_cost" =>$trx_cost,  //
		"currency_code" =>$currency_code, //currency code table
		"project_id" =>$project_id,//
		"created_by" =>$created_by,//
		"created_at" =>$created_date,//
		"company_id" => $request['to_cmp_id']
		//TO MTL SAVE ARRAY  


		);

		$to_data_mtl_insert_id = DB::table('m_material_trx_t')->insertGetId($to_data_mtl_and_qoh);
		array_push($to_data_mtl_insert_id_array,$to_data_mtl_insert_id);
		//TO QOH SAVE ARRAY  

		$data_to_qoh=array(
        "qoh_source" => "SUB INVENTORY TRANSFER",  
		"product_id" => $request['product_id'],  
		"subinventory_id" => $request['to_subinv_id'],  
		"locator_id" =>$request['to_loc_id'], 
		"qoh_trx_qty" => $request['receive_qty'],   
		"qoh_uom_code_id" => $trx_uom, 
		"company_id" => $request['to_cmp_id'],   
		"create_trx_id" =>$to_data_mtl_insert_id,  
		"update_trx_id" =>'',
		"created_by" =>$created_by,//
		"created_at" =>$created_date//
		);


//TO QOH SAVE ARRAY 



//*************************************************************TO MTL TRANSACTION & QOH AREA*****************************************************


$data_qoh_insert_id=\DB::table('i_qoh_detail_t')->insert($data_to_qoh);	
// }

$fcnt=count($from_data_mtl_insert_id_array);
$tcnt=count($to_data_mtl_insert_id_array);

if($req_cnt==$fcnt && $req_cnt==$tcnt){ return 1; }else{ return 0; }


}

}
