<?php
public function getrolsfgreport(){

         
            $wh='';
            if(isset($_GET['pq_filter'])){
        $data=json_decode($_GET['pq_filter']);
        $data=$data->data;
       // $table=array('i_qoh_detail_t','m_products_t','a_m_group_t','m_product_subcategory_t','m_product_category_t');
                $wh.=$this->pqgridsearchsum('g',$data);
        }
        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];
        $sidx='';
        if (!$sidx)
            $sidx = 1;
   $SQL = "SELECT * FROM
    (
    SELECT
        f.product_id,
        f.concatenated_product,
        m_product_subcategory_t.subcategory_name,
        m_product_category_t.category_name,
        f.min_order_qty,
        round(SUM(Stock),2) AS stock,
        round(sum(Stock-min_order_qty),2) as SFG_Req
        
    FROM
        (
        SELECT
            m_products_t.product_id,
            COALESCE((case when SUM(i_qoh_detail_t.qoh_trx_qty)>0 then SUM(i_qoh_detail_t.qoh_trx_qty) else 0 end),0) AS Stock,
            m_products_t.concatenated_product,
            m_products_t.product_subcategory_id,
            m_products_t.product_category_id,
            m_products_t.min_order_qty,
            m_products_t.re_order_level as max_order_qty
        FROM
            m_products_t
        LEFT JOIN i_qoh_detail_t ON i_qoh_detail_t.product_id = m_products_t.product_id AND i_qoh_detail_t.subinventory_id = 5 and i_qoh_detail_t.locator_id = 194 
        WHERE
            m_products_t.product_group_id = 4
        GROUP BY
            m_products_t.product_id  
    ) f left join m_product_subcategory_t on m_product_subcategory_t.product_subcategory_id=f.product_subcategory_id left join m_product_category_t on m_product_category_t.product_category_id=f.product_category_id
GROUP BY
    f.product_id
) as g WHERE 1=1 $wh ORDER BY $sidx";
              $result = \DB::select($SQL);
        $count = count($result);
        if( $count > 0 && $limit > 0)
        {
        $total_pages = ceil($count/$limit);
        } else {
        $total_pages = 0;
        }
        if ($page > $total_pages)
        $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

      $org=\Session::get('organization');
      $loc=\Session::get('location');
      $compy=\Session::get('companyid');

     

  
              
    if(isset($_GET['download']))
    {
          //  $result1 = \DB::select( $download_SQL );
        $result1=collect($result)->map(function($x){ return (array) $x; })->toArray();
        return $result1;
    }

               
           $result = array_slice($result, $start, $limit);

        $responce->rows[]='';
        $responce->data=$result;
        $responce->curPage = $page;
        $responce->total = $total_pages;
        $responce->totalRecords = $count;
        echo json_encode($responce);
    }







public function getrolsfgreport(){

            $wh='';
            if(isset($_GET['pq_filter'])){
        $data=json_decode($_GET['pq_filter']);
        $data=$data->data;
       // $table=array('i_qoh_detail_t','m_products_t','a_m_group_t','m_product_subcategory_t','m_product_category_t');
                $wh.=$this->pqgridsearchsum('a',$data);
        }
        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];
        $sidx='';
        if (!$sidx)
            $sidx = 1;

              $result = \DB::select("select a.concatenated_product,a.qty,a.qoh,sum(a.qoh-a.qty)as pro_reqf,a.re_order_level from( select m_product_type_t.product_type,m_product_variants_t.product_variant_name,m_products_t.concatenated_product,k.component_product_id,k.component_qty,k.product_type_id,m_products_t.re_order_level, k.product_variant_id,k.qty,round(sum(i_qoh_detail_t.qoh_trx_qty),2)as qoh from(select h.product_id,h.concatenated_product,(h.due*-1) as due,m_material_bom_lines_t.component_product_id,m_material_bom_lines_t.component_qty,h.product_type_id, h.product_variant_id,round(sum(h.due*m_material_bom_lines_t.component_qty*-1),2) as qty from(SELECT
    *
FROM
    (
    SELECT
        f.product_id,
        f.concatenated_product,
        f.product_subcategory_id,
        m_product_subcategory_t.subcategory_name,
        f.product_type_id,
            f.product_variant_id,
        SUM(f.so_qty) AS so_qty,
        SUM(Stock) AS stock,
        SUM(wip) AS wip,
        SUM(Stock + wip - so_qty - min_order_qty) AS due
    FROM
        (
        SELECT
            m_products_t.product_id,
            COALESCE(
                SUM(i_qoh_detail_t.qoh_trx_qty),
                0
            ) AS Stock,
            m_products_t.concatenated_product,
            0 AS wip,
            0 AS so_qty,
            m_products_t.product_subcategory_id,
            m_products_t.min_order_qty,
            m_products_t.product_type_id,
            m_products_t.product_variant_id
        FROM
            m_products_t
        LEFT JOIN i_qoh_detail_t ON i_qoh_detail_t.product_id = m_products_t.product_id AND i_qoh_detail_t.qoh_source != 'WIP Store Move' AND i_qoh_detail_t.qoh_source != 'MATERIAL RECEIVE'
        WHERE
            m_products_t.product_group_id = 1
        GROUP BY
            m_products_t.product_id
        UNION ALL
    SELECT
        m_products_t.product_id,
        0 AS Stock,
        m_products_t.concatenated_product,
        COALESCE(
            SUM(i_qoh_detail_t.qoh_trx_qty),
            0
        ) AS wip,
        0 AS so_qty,
            m_products_t.product_subcategory_id,
            0 as min_order_qty,
            m_products_t.product_type_id,
            m_products_t.product_variant_id
    FROM
        m_products_t
    LEFT JOIN i_qoh_detail_t ON i_qoh_detail_t.product_id = m_products_t.product_id AND(
            i_qoh_detail_t.qoh_source = 'WIP Store Move' OR i_qoh_detail_t.qoh_source = 'MATERIAL RECEIVE' OR i_qoh_detail_t.qoh_source = 'OPENSTOCK'
        ) AND i_qoh_detail_t.subinventory_id = 5
    WHERE
        m_products_t.product_group_id = 1
    GROUP BY
        m_products_t.product_id
    UNION ALL
SELECT
    m_products_t.product_id,
    0 AS Stock,
    m_products_t.concatenated_product,
    0 AS wip,
    COALESCE(
        SUM(
            s_salesorder_lines_t.qty - s_salesorder_lines_t.dispatched_qty
        ),
        0
    ) AS so_qty,m_products_t.product_subcategory_id,
    0 as min_order_qty,
            m_products_t.product_type_id,
            m_products_t.product_variant_id
FROM
    m_products_t
LEFT JOIN s_salesorder_lines_t ON s_salesorder_lines_t.product_id = m_products_t.product_id
LEFT JOIN s_salesorder_hdr_t ON s_salesorder_hdr_t.sales_hdr_id = s_salesorder_lines_t.sales_hdr_id AND s_salesorder_hdr_t.order_status_id = 'APPROVED'
WHERE
    m_products_t.product_group_id = 1
GROUP BY
    m_products_t.product_id
union all 
    select m_products_t.product_id, COALESCE(sum(w_jobcard_hdr_t.job_qty),0) as Stock ,m_products_t.concatenated_product, 0 AS wip, 0 as so_qty, m_products_t.product_subcategory_id,0 as min_order_qty,m_products_t.product_type_id,
            m_products_t.product_variant_id FROM m_products_t left JOIN w_jobcard_hdr_t ON w_jobcard_hdr_t.product_id=m_products_t.product_id and w_jobcard_hdr_t.job_status='QA SUBMITTED' and w_jobcard_hdr_t.bom_process='FINALPROCESS' WHERE m_products_t.product_group_id=1 group by m_products_t.product_id    ) f left join m_product_subcategory_t on m_product_subcategory_t.product_subcategory_id=f.product_subcategory_id
GROUP BY
    f.product_id
) g where g.due <0)h left join m_material_bom_hdr_t ON m_material_bom_hdr_t.assembly_product_id=h.product_id left join m_material_bom_lines_t ON m_material_bom_lines_t.material_bom_hdr_id=m_material_bom_hdr_t.material_bom_hdr_id and (m_material_bom_lines_t.component_uom_code_id=2 or m_material_bom_lines_t.component_uom_code_id=3) where m_material_bom_lines_t.component_product_id is not null group by h.product_type_id, h.product_variant_id,m_material_bom_lines_t.component_product_id)k left join i_qoh_detail_t ON i_qoh_detail_t.product_id=k.component_product_id left join m_product_type_t ON m_product_type_t.product_type_id=k.product_type_id left JOIN m_product_variants_t ON m_product_variants_t.product_variant_id=k.product_variant_id left join m_products_t on m_products_t.product_id=k.component_product_id group by k.product_type_id, k.product_variant_id,k.component_product_id) as a  WHERE 1=1 $wh group by a.product_type_id, a.product_variant_id,a.component_product_id");
        //     dd("gghgh");
        $count = count($result);
        if( $count > 0 && $limit > 0)
        {
        $total_pages = ceil($count/$limit);
        } else {
        $total_pages = 0;
        }
        if ($page > $total_pages)
        $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

      $org=\Session::get('organization');
      $loc=\Session::get('location');
      $compy=\Session::get('companyid');

        $SQL = "select b.concatenated_product,b.qty,b.qoh,round(sum(b.qoh+COALESCE(w_qa_submitstage_trx_t.production_qty,0)-b.qty),2) as pro_reqf,IF(round(sum(b.qoh+COALESCE(w_qa_submitstage_trx_t.production_qty,0)-b.qty),2)>0,0,b.max_order_qty) as max_order_qty,round(COALESCE(sum(w_qa_submitstage_trx_t.production_qty),0),2) as po_qty from (select a.concatenated_product,a.qty,a.qoh,round(sum(a.qoh-a.qty),2)as pro_reqf,a.max_order_qty,a.component_product_id from( select m_product_type_t.product_type,m_product_variants_t.product_variant_name,m_products_t.concatenated_product,k.component_product_id,k.component_qty,k.product_type_id,m_products_t.max_order_qty, k.product_variant_id,k.qty,round(COALESCE(sum(i_qoh_detail_t.qoh_trx_qty),0),2)as qoh from(select h.product_id,h.concatenated_product,(h.due*-1) as due,m_material_bom_lines_t.component_product_id,m_material_bom_lines_t.component_qty,h.product_type_id, h.product_variant_id,round(sum(h.due*m_material_bom_lines_t.component_qty*-1),2) as qty from(SELECT
    *
FROM
    (
    SELECT
        f.product_id,
        f.concatenated_product,
        f.product_subcategory_id,
        m_product_subcategory_t.subcategory_name,
        f.product_type_id,
            f.product_variant_id,
        SUM(f.so_qty) AS so_qty,
        SUM(Stock) AS stock,
        SUM(wip) AS wip,
        SUM(Stock + wip - so_qty - min_order_qty) AS due
    FROM
        (
        SELECT
            m_products_t.product_id,
            COALESCE(
                SUM(i_qoh_detail_t.qoh_trx_qty),
                0
            ) AS Stock,
            m_products_t.concatenated_product,
            0 AS wip,
            0 AS so_qty,
            m_products_t.product_subcategory_id,
            m_products_t.min_order_qty,
            m_products_t.product_type_id,
            m_products_t.product_variant_id
        FROM
            m_products_t
        LEFT JOIN i_qoh_detail_t ON i_qoh_detail_t.product_id = m_products_t.product_id AND i_qoh_detail_t.qoh_source != 'WIP Store Move' AND i_qoh_detail_t.qoh_source != 'MATERIAL RECEIVE'
        WHERE
            m_products_t.product_group_id = 1
        GROUP BY
            m_products_t.product_id
        UNION ALL
    SELECT
        m_products_t.product_id,
        0 AS Stock,
        m_products_t.concatenated_product,
        COALESCE(
            SUM(i_qoh_detail_t.qoh_trx_qty),
            0
        ) AS wip,
        0 AS so_qty,
            m_products_t.product_subcategory_id,
            0 as min_order_qty,
            m_products_t.product_type_id,
            m_products_t.product_variant_id
    FROM
        m_products_t
    LEFT JOIN i_qoh_detail_t ON i_qoh_detail_t.product_id = m_products_t.product_id AND(
            i_qoh_detail_t.qoh_source = 'WIP Store Move' OR i_qoh_detail_t.qoh_source = 'MATERIAL RECEIVE' OR i_qoh_detail_t.qoh_source = 'OPENSTOCK'
        ) AND i_qoh_detail_t.subinventory_id = 5
    WHERE
        m_products_t.product_group_id = 1
    GROUP BY
        m_products_t.product_id
    UNION ALL
SELECT
    m_products_t.product_id,
    0 AS Stock,
    m_products_t.concatenated_product,
    0 AS wip,
    COALESCE(
        SUM(
            s_salesorder_lines_t.qty - s_salesorder_lines_t.dispatched_qty
        ),
        0
    ) AS so_qty,m_products_t.product_subcategory_id,
     0 as min_order_qty,
            m_products_t.product_type_id,
            m_products_t.product_variant_id
FROM
    m_products_t
LEFT JOIN s_salesorder_lines_t ON s_salesorder_lines_t.product_id = m_products_t.product_id
LEFT JOIN s_salesorder_hdr_t ON s_salesorder_hdr_t.sales_hdr_id = s_salesorder_lines_t.sales_hdr_id AND s_salesorder_hdr_t.order_status_id = 'APPROVED'
WHERE
    m_products_t.product_group_id = 1
GROUP BY
    m_products_t.product_id 

   union all 
    select m_products_t.product_id, COALESCE(sum(w_jobcard_hdr_t.job_qty),0) as Stock ,m_products_t.concatenated_product, 0 AS wip, 0 as so_qty, m_products_t.product_subcategory_id, 0 as min_order_qty,m_products_t.product_type_id,
            m_products_t.product_variant_id FROM m_products_t left JOIN w_jobcard_hdr_t ON w_jobcard_hdr_t.product_id=m_products_t.product_id and w_jobcard_hdr_t.job_status='QA SUBMITTED' and w_jobcard_hdr_t.bom_process='FINALPROCESS' WHERE m_products_t.product_group_id=1 group by m_products_t.product_id 
    ) f left join m_product_subcategory_t on m_product_subcategory_t.product_subcategory_id=f.product_subcategory_id
GROUP BY
    f.product_id
) g where g.due <0)h left join m_material_bom_hdr_t ON m_material_bom_hdr_t.assembly_product_id=h.product_id left join m_material_bom_lines_t ON m_material_bom_lines_t.material_bom_hdr_id=m_material_bom_hdr_t.material_bom_hdr_id and (m_material_bom_lines_t.component_uom_code_id=2 or m_material_bom_lines_t.component_uom_code_id=3) where m_material_bom_lines_t.component_product_id is not null group by h.product_type_id, h.product_variant_id,m_material_bom_lines_t.component_product_id)k left join i_qoh_detail_t ON i_qoh_detail_t.product_id=k.component_product_id left join m_product_type_t ON m_product_type_t.product_type_id=k.product_type_id left JOIN m_product_variants_t ON m_product_variants_t.product_variant_id=k.product_variant_id left join m_products_t on m_products_t.product_id=k.component_product_id group by k.product_type_id, k.product_variant_id,k.component_product_id) as a  WHERE 1=1  $wh group by a.product_type_id, a.product_variant_id,a.component_product_id)b  left join w_jobcard_hdr_t on w_jobcard_hdr_t.product_id=b.component_product_id and w_jobcard_hdr_t.job_status='QA SUBMITTED' left join w_qa_submitstage_trx_t on w_qa_submitstage_trx_t.job_no=w_jobcard_hdr_t.w_jobs_hdr_id  where 1=1 group by b.concatenated_product ORDER BY $sidx  LIMIT $start , $limit";

    $download_SQL = "select b.concatenated_product,b.qty,b.qoh,round(sum(b.qoh+COALESCE(w_qa_submitstage_trx_t.production_qty,0)-b.qty),2) as pro_reqf,IF(round(sum(b.qoh+COALESCE(w_qa_submitstage_trx_t.production_qty,0)-b.qty),2)>0,0,b.max_order_qty) as max_order_qty,round(COALESCE(sum(w_qa_submitstage_trx_t.production_qty),0),2) as po_qty from (select a.concatenated_product,a.qty,a.qoh,round(sum(a.qoh-a.qty),2)as pro_reqf,a.max_order_qty,a.component_product_id from( select m_product_type_t.product_type,m_product_variants_t.product_variant_name,m_products_t.concatenated_product,k.component_product_id,k.component_qty,k.product_type_id,m_products_t.max_order_qty, k.product_variant_id,k.qty,round(COALESCE(sum(i_qoh_detail_t.qoh_trx_qty),0),2)as qoh from(select h.product_id,h.concatenated_product,(h.due*-1) as due,m_material_bom_lines_t.component_product_id,m_material_bom_lines_t.component_qty,h.product_type_id, h.product_variant_id,round(sum(h.due*m_material_bom_lines_t.component_qty*-1),2) as qty from(SELECT
    *
FROM
    (
    SELECT
        f.product_id,
        f.concatenated_product,
        f.product_subcategory_id,
        m_product_subcategory_t.subcategory_name,
        f.product_type_id,
            f.product_variant_id,
        SUM(f.so_qty) AS so_qty,
        SUM(Stock) AS stock,
        SUM(wip) AS wip,
        SUM(Stock + wip - so_qty - min_order_qty) AS due
    FROM
        (
        SELECT
            m_products_t.product_id,
            COALESCE(
                SUM(i_qoh_detail_t.qoh_trx_qty),
                0
            ) AS Stock,
            m_products_t.concatenated_product,
            0 AS wip,
            0 AS so_qty,
            m_products_t.product_subcategory_id,
            m_products_t.min_order_qty,
            m_products_t.product_type_id,
            m_products_t.product_variant_id
        FROM
            m_products_t
        LEFT JOIN i_qoh_detail_t ON i_qoh_detail_t.product_id = m_products_t.product_id AND i_qoh_detail_t.qoh_source != 'WIP Store Move' AND i_qoh_detail_t.qoh_source != 'MATERIAL RECEIVE'
        WHERE
            m_products_t.product_group_id = 1
        GROUP BY
            m_products_t.product_id
        UNION ALL
    SELECT
        m_products_t.product_id,
        0 AS Stock,
        m_products_t.concatenated_product,
        COALESCE(
            SUM(i_qoh_detail_t.qoh_trx_qty),
            0
        ) AS wip,
        0 AS so_qty,
            m_products_t.product_subcategory_id,
             0 as min_order_qty,
            m_products_t.product_type_id,
            m_products_t.product_variant_id
    FROM
        m_products_t
    LEFT JOIN i_qoh_detail_t ON i_qoh_detail_t.product_id = m_products_t.product_id AND(
            i_qoh_detail_t.qoh_source = 'WIP Store Move' OR i_qoh_detail_t.qoh_source = 'MATERIAL RECEIVE' OR i_qoh_detail_t.qoh_source = 'OPENSTOCK'
        ) AND i_qoh_detail_t.subinventory_id = 5
    WHERE
        m_products_t.product_group_id = 1
    GROUP BY
        m_products_t.product_id
    UNION ALL
SELECT
    m_products_t.product_id,
    0 AS Stock,
    m_products_t.concatenated_product,
    0 AS wip,
    COALESCE(
        SUM(
            s_salesorder_lines_t.qty - s_salesorder_lines_t.dispatched_qty
        ),
        0
    ) AS so_qty,m_products_t.product_subcategory_id,
     0 as min_order_qty,
            m_products_t.product_type_id,
            m_products_t.product_variant_id
FROM
    m_products_t
LEFT JOIN s_salesorder_lines_t ON s_salesorder_lines_t.product_id = m_products_t.product_id
LEFT JOIN s_salesorder_hdr_t ON s_salesorder_hdr_t.sales_hdr_id = s_salesorder_lines_t.sales_hdr_id AND s_salesorder_hdr_t.order_status_id = 'APPROVED'
WHERE
    m_products_t.product_group_id = 1
GROUP BY
    m_products_t.product_id 

   union all 
    select m_products_t.product_id, COALESCE(sum(w_jobcard_hdr_t.job_qty),0) as Stock ,m_products_t.concatenated_product, 0 AS wip, 0 as so_qty, m_products_t.product_subcategory_id, 0 as min_order_qty,m_products_t.product_type_id,
            m_products_t.product_variant_id FROM m_products_t left JOIN w_jobcard_hdr_t ON w_jobcard_hdr_t.product_id=m_products_t.product_id and w_jobcard_hdr_t.job_status='QA SUBMITTED' and w_jobcard_hdr_t.bom_process='FINALPROCESS' WHERE m_products_t.product_group_id=1 group by m_products_t.product_id 
    ) f left join m_product_subcategory_t on m_product_subcategory_t.product_subcategory_id=f.product_subcategory_id
GROUP BY
    f.product_id
) g where g.due <0)h left join m_material_bom_hdr_t ON m_material_bom_hdr_t.assembly_product_id=h.product_id left join m_material_bom_lines_t ON m_material_bom_lines_t.material_bom_hdr_id=m_material_bom_hdr_t.material_bom_hdr_id and (m_material_bom_lines_t.component_uom_code_id=2 or m_material_bom_lines_t.component_uom_code_id=3) where m_material_bom_lines_t.component_product_id is not null group by h.product_type_id, h.product_variant_id,m_material_bom_lines_t.component_product_id)k left join i_qoh_detail_t ON i_qoh_detail_t.product_id=k.component_product_id left join m_product_type_t ON m_product_type_t.product_type_id=k.product_type_id left JOIN m_product_variants_t ON m_product_variants_t.product_variant_id=k.product_variant_id left join m_products_t on m_products_t.product_id=k.component_product_id group by k.product_type_id, k.product_variant_id,k.component_product_id) as a  WHERE 1=1  $wh group by a.product_type_id, a.product_variant_id,a.component_product_id)b  left join w_jobcard_hdr_t on w_jobcard_hdr_t.product_id=b.component_product_id and w_jobcard_hdr_t.job_status='QA SUBMITTED' left join w_qa_submitstage_trx_t on w_qa_submitstage_trx_t.job_no=w_jobcard_hdr_t.w_jobs_hdr_id  where 1=1 group by b.concatenated_product ORDER BY $sidx";
                  $result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }
     
                $result = \DB::select( $SQL );

//dd($result);

        $responce->rows[]='';
        $responce->data=$result;
        $responce->curPage = $page;
        $responce->total = $total_pages;
        $responce->totalRecords = $count;
        echo json_encode($responce);
    }
