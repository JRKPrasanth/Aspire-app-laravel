 <nav>




                            <ul class="main-menu">

                                <!-------------------------------first list------------>
                               <?php 
                             
                       $headers=DB::table("tb_menus")->select('*')->where('parent_id', '=', 0)->orderBy('ordering', 'asc')->get();
$access=\DB::table('a_group_menu_access_t')->select('*')->where('group_id',\Session::get('groupid'))->get();
$access=   json_decode($access[0]->menus);                     $subsubmenu=array();
                                $controllers=array();

                                foreach($headers as $key=>$value){
                                    
                                    
$subsubmenu[$value->menus_name]=array();
$controllers[$value->menus_name]=array();
$createurl[$value->menus_name]=array();

                                $subhead=DB::table("tb_menus")->select('*')->where('parent_id', '=', $value->menus_id)->orderBy('ordering', 'asc')->get();

                                foreach($subhead as $k=>$v){

                                $subname=DB::table("tb_menus")->select('*')->where('parent_id', '=', $v->menus_id)->orderBy('ordering', 'asc')->get();
$subsubmenu[$value->menus_name][$v->menus_name]=array();


                                foreach($subname as $k1=>$v1){
                                $subsubmenu[$value->menus_name][$v->menus_name][$k1]=$v1->menus_name;
                                $controllers[$value->menus_name][$v->menus_name][$k1]=$v1->controller_name;
                                $createurl[$value->menus_name][$v->menus_name][$k1]=$v1->createurl;

                                }

                                }


                              }  ?>

                                  <?php foreach($subsubmenu as $key=>$value)   {    if(isset($access->$key)) { ?>


<!-- <li><a href="/deals" class="three-d">
            Deals
            <span class="three-d-box"><span class="front">Deals</span><span class="back">Deals</span></span>
        </a></li> -->


                                <li class="mega-parent">
                                    <a href="#" class="menuhover three-d"><?php echo $key;   ?> <i class="zmdi zmdi-chevron-down"></i>

<span class="three-d-box"><span class="front"><?php echo $key;   ?></span><span class="back"><?php echo $key;   ?></span></span>

                                    </a>
                                    <div class="mega-menu-area hp2style2 mega-menu-area2">
 <?php foreach($value as $k=>$v) {  if(isset($access->$k)) {  ?>
                                        <ul class="single-mega-item single-mega-item2">

                                            <li class="mega-title"><a href="#">{{$k}}</a>
                                            </li>
                                        <?php foreach($v as $k1=>$v1) {  if(isset($access->$v1)) {  ?>
                                            <li><a href="<?php echo URL::to($controllers[$key][$k][$k1]); ?>">{{$v1}}</a><?php if(isset($createurl[$key][$k][$k1])&&$createurl[$key][$k][$k1]!='') { ?>
                                            <a href="<?php echo URL::to($createurl[$key][$k][$k1]); ?>"><i class="fa fa-plus-circle"></i></a><?php } ?>
                                            </li>
                                        <?php }  }  ?>

                                        </ul>
 <?php }  }?>



                                    </div>
                                </li>
                                  <?php } }?>
							</ul>
                    </nav>	
