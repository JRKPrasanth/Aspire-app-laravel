
$job=\DB::select('select * from w_jobcard_hdr_t where w_jobs_hdr_id='.$_POST['job_no']);
$jobprice=\DB::select('select * from jobreport_settings_t where jobreport_settings_id="1"');
if(count($jobprice)>0){
	$jobprice=$jobprice[0]->pricelist_id;
}else{
	$jobprice=0;
}
	$jobassign=$job[0]->job_assigned_to;
	$jobhr=$job[0]->hour;
	$jobemp=explode(",",$jobassign);
	$cost=array();
	$totcost=0;
	$line=0;
	$totcost1=0;
	$totcost2=0;
	$totcost3=0;
	$totqty=0;
	foreach($jobemp as $k =>$val){
		$cost[$line]['type']=1;
		$cost[$line]['costtype']=1;
		$cost[$line]['typeid']=$val;
		$cost[$line]['hour']=$jobhr;
		
		$cost[$line]['rate']=$jobhr*100;
		$totcost +=$jobhr*100;
		$line++;
	}
	
	$jobassign1=$_POST['job_assigned_to'];
	foreach($jobassign1 as $k =>$val){
		$cost[$line]['type']=1;
		$cost[$line]['costtype']=2;
		$cost[$line]['typeid']=$val;
		$cost[$line]['hour']=$_POST['working_hrs'][$k];
		$cost[$line]['rate']=$_POST['working_hrs'][$k]*100;
		$totcost1 +=$_POST['working_hrs'][$k]*100;
		$totcost1 =$_POST['working_hrs'][$k];
		$line++;
	}
	foreach($_POST['bulk_product_id'] as $k1 =>$val1){
		$cost[$line]['type']=2;
		$cost[$line]['costtype']=1;
		$cost[$line]['typeid']=$val1;
		$sql=\DB::select('select m_products_t.product_id,m_products_t.product_group_id,m_product_groups_t.group_name from m_products_t left join m_product_groups_t on(m_products_t.product_group_id=m_product_groups_t.product_group_id) where m_products_t.product_id='.$val1);
		$prdqty=$_POST['bulk_production_qty'][$k1];
		if($sql[0]->group_name!="SEMI FINISHED GOODS"){
			$price=\DB::select('select unit_price from i_pricelist_lines_t where product_id='.$val1.' and pricelist_hdr_id='.$jobprice);
                        if(count($price)>0){
                            $prdprice=$price[0]->unit_price;
                        }else{
                            $prdprice=0;
                        }
		$cost[$line]['hour']=$prdqty;
                
		$cost[$line]['rate']=round($prdqty*$prdprice/$_POST['production_qty'],2);
		$totcost2 +=round($prdqty*$prdprice/$_POST['production_qty'],2);
			$line++;
		}else{
			$price=\DB::select('select actual_cost from job_card_report_hdr where product_id='.$val1);
			if(count($price)>0){
			$prdprice=$price[0]->actual_cost;
			}else{
			$prdprice=0;	
			}
		$cost[$line]['hour']=$totqty;
		$cost[$line]['rate']=round($totqty*$prdprice/$_POST['production_qty'],2);
			$totcost3 +=round($totqty*$prdprice/$_POST['production_qty'],2);
			$line++;
		}
		
		
	}
	foreach($_POST['bulk_product_id'] as $k1 =>$val1){
		$cost[$line]['type']=2;
		$cost[$line]['costtype']=2;
		$cost[$line]['typeid']=$val1;
		$sql=\DB::select('select m_products_t.product_id,m_products_t.product_group_id,m_product_groups_t.group_name from m_products_t left join m_product_groups_t on(m_products_t.product_group_id=m_product_groups_t.product_group_id) where m_products_t.product_id='.$val1);
		$prdqty=$_POST['bulk_production_qty'][$k1];
		$exceedqty=$_POST['bulk_exceed_qty'][$k1];
		$totqty=$prdqty+$exceedqty;
		if($sql[0]->group_name!="SEMI FINISHED GOODS"){
			$price=\DB::select('select unit_price from i_pricelist_lines_t where product_id='.$val1.' and pricelist_hdr_id='.$jobprice);
                        if(count($price)>0){
                            $prdprice=$price[0]->unit_price;
                        }else{
                            $prdprice=0;
                        }
			
		$cost[$line]['hour']=$totqty;
		$cost[$line]['rate']=round($totqty*$prdprice/$_POST['production_qty'],2);
			$totcost3 +=round($totqty*$prdprice/$_POST['production_qty'],2);
			$line++;
		}else{
			$price=\DB::select('select actual_cost from job_card_report_hdr where product_id='.$val1);
			if(count($price)>0){
			$prdprice=$price[0]->actual_cost;
			}else{
			$prdprice=0;	
			}
		$cost[$line]['hour']=$totqty;
		$cost[$line]['rate']=round($totqty*$prdprice/$_POST['production_qty'],2);
			$totcost3 +=round($totqty*$prdprice/$_POST['production_qty'],2);
			$line++;
		}
		
		
	}
	
	$data1['actual_cost']=$totcost3+$totcost1;
	$data1['actual_cost']=$totcost3;
	$data1['palnning_cost']=$totcost+$totcost2;
	$data1['product_id']=$_POST['product_id'];
	$data1['job_card_no']=$_POST['job_no'];
	$data1['date']=date('Y-m-d');
	
	$jobrid=DB::table('job_card_report_hdr')->insertGetId($data1);
	 /*deepika purpose:audit log*/
	 $this->auditlog($jobrid,"qasubmitstage-jobreport",'create',$data1,"job_card_report_hdr");
	/*end*/
	foreach($cost as $k2 => $val2){
		$line_data[$k2]=$val2;
		$line_data[$k2]['job_card_id']=$jobrid;
			
	}
		DB::table('job_card_report_lines')->insert($line_data);