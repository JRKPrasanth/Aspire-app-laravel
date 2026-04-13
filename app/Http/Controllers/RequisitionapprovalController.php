 <?php

namespace App\Http\Controllers;

use App\Requisitionapproval;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RequisitionapprovalController extends Controller
{
    public $module="approvelrequestion";
	
	public function __construct()
	{
		$this->data=array(
                    'pageModule'=> 'approvalrequestion',
                    'pageUrl'	=>  url('requestionapproval')
                  );
                $this->data['urlmenu']=$this->indexs(); 
		$this->table="p_po_hdr_t";
		$this->subtable="p_po_lines_t";
		$this->pageModule="Approvalrequestion";
	
		$this->data['pageModule']=$this->pageModule;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
	}
	
	
	
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Requisitionapproval  $requisitionapproval
     * @return \Illuminate\Http\Response
     */
    public function show(Requisitionapproval $requisitionapproval)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Requisitionapproval  $requisitionapproval
     * @return \Illuminate\Http\Response
     */
    public function edit(Requisitionapproval $requisitionapproval)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Requisitionapproval  $requisitionapproval
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Requisitionapproval $requisitionapproval)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Requisitionapproval  $requisitionapproval
     * @return \Illuminate\Http\Response
     */
    public function destroy(Requisitionapproval $requisitionapproval)
    {
        //
    }
}
