<?php

namespace App\Http\Controllers;
use App\Announcement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use yajra\datatables\datatables;
use DB;
use DateTime;

class AnnouncementController extends Controller
{
       public function __construct()

    {
        $this->data = array();
        $this->table = "hr_announcement_t";
        $this->pageModule = "announcement";
        $this->model = new Announcement;
        $this->data['pageFormtype'] = 'ajax';
        $this->table = "hr_announcement_t";
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'announcement',
            'pageUrl'   =>  url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->data['urlmenu'] = $this->indexs();
    }
    
        public function index(Request $request)
    {
        // restrict illegal menu entry purpose - RATHI R

        $url = $request->path();
        
        $Controller = new Controller();
        $access = $Controller->Accessdined();
        
        $userAccess = json_decode($access, true);
        
        // Flatten the $userAccess array
        $flatUserAccess = [];
        foreach ($userAccess as $accessItem) {
            if (is_array($accessItem)) {
                $flatUserAccess = array_merge($flatUserAccess, $accessItem);
            } else {
                $flatUserAccess[] = $accessItem;
            }
        }
        
        if (!in_array($url, $flatUserAccess)) {
            return view('accessresticted.view');
               
        }

        // END

        $this->data['pageMethod'] = \Request::route()->getName();

        return view('announcement.table', $this->data);
    }
	
	public function getannData(Request $request){
		
		
        $data = DB::table('hr_announcement_t')
            ->select('hr_announcement_t.*')->get();
		
		     return DataTables::of($data)->make(true);
		
	}
	
    
        public function create($id = null)
    {
        if ($id == 0) {

            $this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));
            $table = \DB::table('hr_announcement_t')->get();
			$this->data['row'] = (object)[
				'start_date' => '',
				'ann_name' => '',
				'end_date' => '',
				'active' => '',
				'attachment' => '',
				'id' => ''
			];

          
            $this->data['pageMethod'] = "createannouncement";
         

            return view('announcement.form', $this->data);
            
        } else {

            $table = \DB::table('hr_announcement_t')->where('id', $id)->get();

            $this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));
            $this->data['data'] = $table;
            $this->data['row'] = (object)[];

            $this->data['row']->start_date = $table[0]->start_date;
			$this->data['row']->ann_name = $table[0]->ann_name;
            $this->data['row']->end_date = $table[0]->end_date;
			$this->data['row']->active = $table[0]->active;
			$this->data['row']->attachment = $table[0]->attachment;
            $this->data['row']->id = $table[0]->id;
            $this->data['pageMethod'] = "announcementedit";



        }

        return view('announcement.form', $this->data);
    }
    
    
public function save(Request $request)
{
    $edit_id = $request->input('id'); 
    $isNew = empty($edit_id);
    
    if ($isNew) {
        $announcement = new Announcement();
    } else {
        $announcement = Announcement::find($edit_id);
        if (!$announcement) {
            return response()->json(['status' => 'error', 'message' => 'Invalid Announcement ID']);
        }
    }

    $announcement->ann_name = $request->input('ann_name');
    $announcement->start_date = $request->input('start_date');
    $announcement->end_date = $request->input('end_date');
    $announcement->active = $request->input('active');
    $announcement->created_by = $request->input('created_by');
    $announcement->last_updated_by = \Session::get('id');
    $announcement->organization_id = \Session::get('organization');
    $announcement->location_id = \Session::get('loc_id');
    $announcement->company_id = \Session::get('companyid');
    $announcement->updated_at = now();

    if ($isNew) {
        $announcement->created_at = now();
        $announcement->save(); // Save first to get ID for folder name
        $edit_id = $announcement->id;
    }

    // Handle file upload
    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $folder = public_path('images/announcement/' . $announcement->id);

        // Create directory if it doesn't exist
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        // Move the uploaded file to the target folder
        $file->move($folder, $filename);

        // Save filename to DB
        $announcement->attachment = $filename;
    }

    $announcement->save();

    $action = $isNew ? "Create" : "Edit";
    $this->auditlog($edit_id, "announcement", $action, $request->all(), "hr_announcement_t");

    return response()->json([
        'status' => 'success',
        'message' => $isNew ? 'Announcement Saved Successfully!!' : 'Announcement Updated Successfully!!',
        'id' => $edit_id
    ]);
}


	
    public function delete(Request $request, $id = null)
    {
        if ($id === null) {
            return 2;
        }
        
        $query = \DB::table('hr_announcement_t')->where('id', $id)->delete();


        if ($query) {
            return 1;
        } else {
            return 2;
        }
    }

    
  }