{{-- Include Bootstrap 5 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    /* Custom styles for menu */
    .offcanvas-body ul ul {
        padding-left: 1rem;
        border-left: 2px solid #f1f1f1;
    }
    .offcanvas-body .btn {
        border-radius: 0;
        width: 100%;
        text-align: left;
    }
    .offcanvas-body a {
        text-decoration: none;
        display: block;
        padding: 8px 12px;
        color: #333;
    }
    .offcanvas-body a:hover {
        background: #f8f9fa;
        color: #000;
    }
</style>

{{-- Mobile Menu Button --}}
<button class="btn btn-outline-secondary d-lg-none" type="button"
        data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
    <i class="bi bi-list"></i>
</button>

{{-- Offcanvas Mobile Menu --}}
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="mobileMenuLabel">Menu</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        <ul class="list-group list-group-flush">
            <?php
            $headers = DB::table("tb_menus")->select('*')->where('parent_id', '=', 0)->orderBy('menus_id', 'asc')->get();
            $access = DB::table('a_user_access_t')->where('user_id', \Session::get('id'))->first();
            $access = json_decode($access->menus);

            foreach ($headers as $header) {
                if (!isset($access->{$header->menus_id})) continue;
                $submenus = DB::table("tb_menus")->where('parent_id', '=', $header->menus_id)->orderBy('menus_id', 'asc')->get();
                ?>
                <li class="list-group-item">
                    @if(count($submenus) > 0)
                        <button class="btn d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#menu-{{ $header->menus_id }}">
                            {{ $header->menus_name }}
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div class="collapse" id="menu-{{ $header->menus_id }}">
                            <ul class="list-group list-group-flush ms-3">
                                <?php foreach ($submenus as $submenu) {
                                    if (!isset($access->{$submenu->menus_id})) continue;
                                    $childmenus = DB::table("tb_menus")->where('parent_id', '=', $submenu->menus_id)->orderBy('menus_id', 'asc')->get(); ?>
                                    <li class="list-group-item">
                                        @if(count($childmenus) > 0)
                                            <button class="btn d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#submenu-{{ $submenu->menus_id }}">
                                                {{ $submenu->menus_name }}
                                                <i class="bi bi-chevron-down"></i>
                                            </button>
                                            <div class="collapse" id="submenu-{{ $submenu->menus_id }}">
                                                <ul class="list-group list-group-flush ms-3">
                                                    <?php foreach ($childmenus as $child) {
                                                        if (!isset($access->{$child->menus_id})) continue; ?>
                                                        <li class="list-group-item">
															<a href="{{ URL::to($child->controller_name) }}">{{ $child->menus_name }}</a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        @else
										<a href="{{ URL::to($submenu->controller_name) }}">{{ $submenu->menus_name }}</a>
                                        @endif
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    @else
					<a href="{{ URL::to($header->controller_name) }}">{{ $header->menus_name }}</a>
                    @endif
                </li>
            <?php } ?>
        </ul>
    </div>
</div>
