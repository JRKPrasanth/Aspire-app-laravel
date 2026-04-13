<?php

namespace App\Http\Controllers;
use App\Buttons;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class CreatebuttonController extends Controller
{

    public function __construct()
    {
        $this->data = array();
        $this->model = new Buttons();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['urlmenu'] = $this->indexs();

    }


    public function create($id = null)
    {

        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));

        return view('createbutton.form', $this->data);

    }

    public function getbuttonData(Request $request)
    {
        $query = DB::table('button_names_tbl')
            ->selectRaw("module_name,button_name,button_id_name,button_id")
            ->orderBy('button_id', 'DESC');

        return DataTables::of($query)->make(true);
    }

    // Purpose For Save Function
   public function save(Request $request)
{
    try {

        if (empty($request->url)) {
            return response()->json([
                'status' => 'error',
                'message' => 'URL is required'
            ], 422);
        }

        // These arrays come from the cloned table rows
        $button_names = $request->button_name;  
        $button_ids   = $request->button_id;     


        if (empty($button_names) || empty($button_ids)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Add at least one row'
            ], 422);
        }

        $inserted_ids = []; // To return all created row IDs

        foreach ($button_names as $index => $btn_name) {

            // Skip any empty lines the user may have added
            if (empty($btn_name) || empty($button_ids[$index])) {
                continue;
            }

            $buttons = new Buttons();
            $buttons->module_name     = $request->url;     
            $buttons->button_name     = $btn_name;          
            $buttons->button_id_name  = $button_ids[$index]; 
            $buttons->company_id      = \Session::get('companyid');
            $buttons->location_id      = \Session::get('location');
            $buttons->organization_id      = \Session::get('organization');
            $buttons->save();

            $row_id = DB::getPdo()->lastInsertId();
            $inserted_ids[] = $row_id;

            // Log EACH line separately
            $this->auditlog($row_id, "buttons", "Create", [
                'button_name' => $btn_name,
                'button_id'   => $button_ids[$index],
                'module_name' => $request->url
            ], "button_names_tbl");
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Buttons saved successfully',
            'ids' => $inserted_ids
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'status' => 'error',
            'message' => 'Error: ' . $e->getMessage()
        ], 500);

    }
}



public function delete(Request $request, $id = null)
{

    try {

        // Try to delete
        $deleted = \DB::table('button_names_tbl')->where('button_id', $id)->delete();

        if ($deleted) {
            return response()->json([
                'status' => 'success',
                'message' => 'Deleted successfully.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Delete failed. Please try again.'
            ], 500);
        }

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong: ' . $e->getMessage()
        ], 500);
    }
}

}