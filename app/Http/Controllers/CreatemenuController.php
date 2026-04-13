<?php
namespace App\Http\Controllers;
use App\Menus;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class CreatemenuController extends Controller
{

    public function __construct()
    {
        $this->data = array();
        $this->model = new Menus();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['urlmenu'] = $this->indexs();

    }


    public function create($id = null)
    {

        $headers = DB::table("tb_menus")->where('parent_id', 0)->orderBy('menus_id')->get();

        $this->data['parent_menu'] = $this->jcustomselect('tb_menus', 'menus_id', 'menus_name', '', 'and parent_id="0"');
        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));

        return view('createmenu.form', $this->data);

    }

    public function getMenuData(Request $request)
    {
        $query = DB::table('tb_menus AS m')
            ->leftJoin('tb_menus AS p', 'p.menus_id', '=', 'm.parent_id')
            ->leftJoin('tb_menus AS s', 's.menus_id', '=', 'p.parent_id')
            ->selectRaw("
                COALESCE(s.menus_name, '-') AS parent_menu,
                COALESCE(p.menus_name, '-') AS sub_menu,
                COALESCE(p.menus_id, '-') AS parent_id,
                COALESCE(s.menus_id, '-') AS sub_id,
                m.menus_name AS menus_name,
                m.controller_name AS url,
                m.active,
                m.menus_id
            ")
            ->having('parent_menu', '!=', '-')
            ->orderBy('menus_id', 'DESC');

        return DataTables::of($query)->make(true);
    }

    // Purpose For Save Function
    public function save(Request $request)
    {
        try {

            $edit_id = $request->edit_id;

            // UPDATE CASE
            if (!empty($edit_id)) {

                $menus = Menus::find($edit_id);

                if (!$menus) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Menu not found'
                    ], 404);
                }

                // Update common fields
                $menus->menus_name = $request->menu;
                $menus->controller_name = $request->url;
                $menus->parent_id = $request->sub_menu ?? 0;
                $menus->last_updated_by = \Session::get('id');
                $menus->updated_at = now();

                $menus->save();

                // AUDIT LOG
                $this->auditlog($edit_id, "menus", "Update", $request->all(), "tb_menus");

                return response()->json([
                    'status' => 'success',
                    'message' => 'Menu Updated Successfully',
                    'id' => $edit_id
                ]);
            }

            $menus = new Menus();
            // CREATE CASE
            if (!empty($request->sub_menu)) {
                // Case 1: Submenu (third level)
                $menus->menus_name = $request->menu;
                $menus->controller_name = $request->url;
                $menus->parent_id = $request->sub_menu;

            } elseif (!empty($request->sub_menu1)) {
                // Case 2: Second-level menu under parent_menu
                $menus->menus_name = $request->sub_menu1;
                $menus->controller_name = $request->url;
                $menus->parent_id = $request->parent_menu;

            } elseif (!empty($request->parent_menu1)) {
                // Case 3: New parent menu
                $menus->menus_name = $request->parent_menu1;
                $menus->controller_name = $request->url;
                $menus->parent_id = 0;

            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid menu input'
                ], 422);
            }

            $menus->created_by = \Session::get('id');
            $menus->created_at = now();
            $menus->last_updated_by = \Session::get('id');
            $menus->updated_at = now();
            $menus->company_id = \Session::get('companyid');

            $menus->save();

            $edit_id = DB::getPdo()->lastInsertId();
            $action = "Create";

            // AUDIT LOG
            $this->auditlog($edit_id, "menus", $action, $request->all(), "tb_menus");

            return response()->json([
                'status' => 'success',
                'message' => 'Menu Saved Successfully',
                'id' => $edit_id
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

            // CHECK IF MENU IS USED ANYWHERE (JSON object key check)
            $menuUsed = \DB::table('a_company_menu_access_t')
                ->whereRaw("JSON_CONTAINS_PATH(menus, 'one', '$.\"$id\"')")
                ->exists();

            if ($menuUsed) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This menu is used somewhere else and cannot be deleted.'
                ], 400);
            }


            // DELETE MENU
            $deleted = \DB::table('tb_menus')->where('menus_id', $id)->delete();

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