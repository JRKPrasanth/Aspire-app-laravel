<?php
namespace App\Http\Controllers;

        // restrict illegal menu entry purpose
        $url = $request->path();
        //dd($url);
        $Controller = new Controller();
        $access = $Controller->Accessdined();
        
        $userAccess = json_encode($access, true);
        dd($userAccess);
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
        
        ?>