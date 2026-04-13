<style>
    .sidebara {

        padding: 10px 15px;
        position: fixed;
        overflow-y: auto;
        top: 82px;
        bottom: 0;
        background-color: #ffffff;
        border-right: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }


    .sidebar-expanded {
        width: 275px;
    }


    .sidebar-collapsed {
        width: 70px !important;
        padding: 10px 5px !important;
    }

    .sidebar-collapsed .menu-label {
        display: none !important;
    }

    .sidebar-collapsed .parent-menu {
        justify-content: center;
    }

    .sidebar-collapsed .submenu-container,
    .sidebar-collapsed .menu-list {
        display: none !important;
    }

    /* === Collapsed Style === */
    .sidebar-collapsed {
        width: 70px !important;
        padding: 10px 5px !important;
        transition: all 0.3s ease;
    }

    .sidebar-collapsed .menu-label {
        display: none !important;
    }

    .sidebar-collapsed .parent-menu {
        justify-content: center;
    }

    .sidebar-collapsed .submenu-container,
    .sidebar-collapsed .menu-list {
        display: none !important;
    }

    /* Parent menu */
    .parent-menu {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        margin: 5px 0;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s ease;
        font-weight: 700;
        color: #5d3434;
    }

    .parent-menu:hover {
        background: #ef353c;
        color: #fff;
    }

    .menu_icon {
        margin-right: 2px;
        border-radius: 8px;
        height: 35px;
        width: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        vertical-align: middle;
        background-color: #fff;
        font-size: 20px;
        color: #dc3545;
        box-shadow: -3px 4px 23px rgb(29 11 11 / 10%);
    }

    .parent-menu:hover .menu_icon {
        background-color: #ffffff;
        color: #0d6efd;
    }

    .menu-label {
        font-weight: 600;
        font-size: 14px;
        color: #212529;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .parent-menu:hover .menu-label {
        color: #fff;
        font-weight: 600;
    }

    /* Submenu container */
    .submenu-container {
        display: none;
        padding-left: 20px;
        margin-top: -5px;
    }

    /* Submenu */
    .submenu {
        padding: 7px 25px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        color: #444;
        transition: color 0.3s;
    }

    .submenu:hover {
        color: #ef353c;
    }

    /* Child menu list */
    .menu-list {
        display: none;
        padding-left: 25px;
        list-style-type: disc;
        width: 200px;
        transition: all 0.3s ease;
    }

    .menu-list li {
        margin: 8px 30px;
        font-size: 14px;
        color: #333;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .menu-list li a {
        color: inherit;
        text-decoration: none;
    }

    .menu-list li:hover a {
        color: #ef353c !important;
        font-weight: 600;
    }

    #sidebara,
    #mainContent {
        transition: all 0.3s ease;
    }
</style>

<!-- Sidebar content -->
<div class="sidebara" id="sidebar">
    <!-- In header.blade.php or above sidebar -->
    <button id="toggleSidebarBtn" class="btn btn-outline-secondary m-2">
        ☰
    </button>
    <?php
$headers = DB::table("tb_menus")->where('parent_id', 0)->orderBy('menus_id')->get();
$access = json_decode(DB::table('a_user_access_t')->where('user_id', \Session::get('id'))->value('menus'));
    ?>

    @foreach ($headers as $header)
        @if (isset($access->{$header->menus_id}))
            <div class="parent-menu" onclick="toggleParent(this)">
                <i class="{{ $header->icon_class }} menu_icon sidebar-toggle-icon"></i>
                <span class="menu-label">{{ $header->menus_name }}</span>
            </div>

            <div class="submenu-container">
                <?php
                $submenus = DB::table("tb_menus")->where('parent_id', $header->menus_id)->orderBy('menus_id')->get();
                        ?>

                @foreach ($submenus as $submenu)
                    @if (isset($access->{$submenu->menus_id}))
                        <div class="submenu" onclick="toggleSubmenu(this)">
                            {{ $submenu->menus_name }}
                        </div>

                        <?php
                                $childMenus = DB::table("tb_menus")->where('parent_id', $submenu->menus_id)->orderBy('menus_id')->get();
                                        ?>

                        @if (count($childMenus) > 0)
                            <ul class="menu-list">
                                @foreach ($childMenus as $child)
                                    @if (isset($access->{$child->menus_id}))
                                        <li>
                                            <a href="{{ URL::to($child->controller_name) }}">
                                                {{ $child->menus_name }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    @endif
                @endforeach
            </div>
        @endif
    @endforeach
</div>

<!-- Sidebar Toggle Script -->
<script>
    function toggleParent(element) {
        const allContainers = document.querySelectorAll('.submenu-container');
        const currentContainer = element.nextElementSibling;

        allContainers.forEach(container => {
            if (container !== currentContainer) {
                container.style.display = 'none';
                closeAllSubmenus(container);
            }
        });

        currentContainer.style.display =
            currentContainer.style.display === 'block' ? 'none' : 'block';
    }

    function toggleSubmenu(element) {
        const currentMenuList = element.nextElementSibling;

        // Close all other .menu-list in the same container
        const allMenuLists = element.parentElement.querySelectorAll('.menu-list');
        allMenuLists.forEach(menu => {
            if (menu !== currentMenuList) {
                menu.style.display = 'none';
            }
        });

        // Toggle the current submenu
        currentMenuList.style.display =
            currentMenuList.style.display === 'block' ? 'none' : 'block';
    }

    function closeAllSubmenus(container) {
        const allMenus = container.querySelectorAll('.menu-list');
        allMenus.forEach(menu => {
            menu.style.display = 'none';
        });
    }

    document.addEventListener('DOMContentLoaded', function () {

        const toggleBtn = document.getElementById('toggleSidebarBtn');
        const sidebar = document.getElementById('sidebara');
        const mainContent = document.getElementById('mainContent');

        function toggleSidebar() {
            sidebar.classList.toggle('sidebar-collapsed');

            if (sidebar.classList.contains('sidebar-collapsed')) {
                sidebar.classList.remove('col-md-2');
                sidebar.classList.add('col-md-1');
                mainContent.classList.remove('col-md-10');
                mainContent.classList.add('col-md-11');
            } else {
                sidebar.classList.remove('col-md-1');
                sidebar.classList.add('col-md-2');
                mainContent.classList.remove('col-md-11');
                mainContent.classList.add('col-md-10');
            }
        }

        // Toggle button click
        toggleBtn.addEventListener('click', toggleSidebar);

        // Menu icon click (ALL icons)
        document.querySelectorAll('.sidebar-toggle-icon').forEach(icon => {
            icon.addEventListener('click', function (e) {
                e.stopPropagation(); // prevents parent menu toggle
                toggleSidebar();
            });
        });

    });

</script>