<!DOCTYPE html>

@php if (!isset($pageMethod))
$pageMethod = ''; @endphp

<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/fav.png') }}">

  <!-- Core CSS -->

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <!-- jQuery UI -->
  <link href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" rel="stylesheet">
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css" />

  <!-- DataTables + Extensions -->
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/colreorder/1.7.0/css/colReorder.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/fixedheader/3.4.0/css/fixedHeader.bootstrap5.min.css" rel="stylesheet">

  <!-- jqGrid -->
  <link href="https://cdn.jsdelivr.net/npm/free-jqgrid@4.15.5/css/ui.jqgrid.min.css" rel="stylesheet">

  <!-- Select2 -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

  <!-- Local Styles -->
  <link href="{{ asset('css/daterangepicker.css') }}" rel="stylesheet">
  <link href="{{ asset('css/sweet-alert.css') }}" rel="stylesheet">
  <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
  <link href="{{ asset('css/customize.css') }}" rel="stylesheet">
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">

  <?php include('toolbar.php'); ?>
  @stack('styles')
 </head>
<body class="fix-header fix-sidebar card-no-border" onLoad="noBack();" onpageshow="if (event.persisted) noBack();"
  onUnload="">

  <!-- Session modals, logout modals etc. -->
  <!-- Modal -->
  <!-- Session Timeout Modal -->
  <div class="modal fade" id="logout_popup" tabindex="-1" aria-labelledby="logoutLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-md">
      <div class="modal-content bg-danger bg-gradient text-white border-0 rounded-4">
        <div class="modal-body text-center py-5">
          <i class="fa fa-exclamation-triangle text-warning fa-4x mb-3 animate__animated animate__tada"></i>
          <h4 class="fw-bold text-warning">Session Timeout</h4>
          <p class="text-light mt-2">
            You will be logged out in
            <span id="seconds_time" class="fw-bold text-warning fs-4">60</span> seconds.
          </p>
          <p class="text-white mb-4">Would you like to continue your session?</p>
          <div class="d-flex justify-content-center gap-3">
            <button type="button" class="btn btn-outline-light px-4" id="continue_session">
              <i class="fa fa-sync-alt me-1"></i> Continue
            </button>
            <a href="{{ url('logout') }}" class="btn btn-warning text-dark px-4">
              <i class="fa fa-sign-out-alt me-1"></i> Logout
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- alert message popup -->
<div id="customAlert"
     class="alert alert-warning alert-dismissible fade d-none position-fixed top-0 start-50 translate-middle-x mt-4"
     role="alert"
     style="z-index:1050; min-width:300px;font-weight: 700;">
    <span class="text-center" id="alertMessage"></span>
</div>


  <!-- END -->
  <!-- Delete conform popup -->
  <div class="modal fade" id="globalDeleteModal" tabindex="-1" aria-labelledby="globalDeleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="globalDeleteModalLabel">Confirm Delete</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this record?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="globalConfirmDeleteBtn">Yes, Delete</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Toggle/Close Icons For notification purpose -->
  <?php

if (isset($_GET['ref_id'])) {
  $ref_id = $_GET['ref_id'];
  \DB::table('notifications_t')->where('notification_id', $ref_id)->update(['read/unread' => 'read']);
}

$user = \Session::get('id');
$comp = \Session::get('companyid');
$loc = \Session::get('location');

$count_no = \DB::table('notifications_t')
  ->where('read/unread', 'unread')
  ->where('company_id', $comp)
  ->where('location_id', $loc)
  ->where('user_id', $user)
  ->select('*')
  ->get();

$count_noti = count($count_no);

?>

  <header class="m-header">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light px-4"
    style="  position: fixed; 
    top: 0; 
    width: 100%; 
    z-index: 1030;     
    background-image: linear-gradient(-225deg, #69EACB 0%, #EACCF8 48%, #6654F1 100%);">
      <div class="container-fluid">

        <!-- MOBILE hamburger (left) -->
        <button class="navbar-toggler d-inline-flex d-lg-none me-2" type="button" data-bs-toggle="offcanvas"
          data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-label="Open menu">
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Logo (HIDE on mobile, SHOW on desktop) -->
        <div class="d-flex align-items-center">
          <a class="navbar-brand d-none d-lg-block" href="{{ URL::to('home') }}">
            <img src="{{ asset('images/profile_images/logo1.png') }}" alt="Logo" class="img-fluid"
              style="max-height: 65px;margin-top:-5px;">
          </a>
        </div>
        <h2 class="f_year">FY 2024–26</h2>
        <a class="homei" href="{{ URL::to('home') }}" style="display:none;"><i class="bi bi-house-check-fill"
            style="color: white;"></i></a>
        <!-- ===== MOBILE RIGHT: bell + avatar on same line ===== -->
        <div class="d-flex align-items-center ms-auto gap-1 d-lg-none">

          <!-- Notification (mobile) -->
          <div class="dropdown">
            <a class="position-relative text-dark" href="#" id="notifDropdownSm" role="button" data-bs-toggle="dropdown"
              aria-expanded="false" title="Notifications">
              <i class="bi bi-bell-fill fs-4" style="color:#ffff;"></i>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                style="min-width:1.25rem;">
                <?= $count_noti > 0 ? $count_noti : 0 ?>
              </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end p-2 shadow" aria-labelledby="notifDropdownSm"
              style="min-width: 150px; max-height: 60vh; overflow-y: auto;">
              <?php if ($count_noti > 0): ?>
              <?php  foreach ($count_no as $value): ?>
              <li>
                <a href="{{ URL::to($value->reference_url . '?ref_id=' . $value->notification_id) }}"
                  class="dropdown-item small py-2">
                  <?= $value->description ?>
                </a>
              </li>
              <?php  endforeach; ?>
              <?php else: ?>
              <li class="text-center text-muted small py-2">No New Notifications</li>
              <?php endif; ?>
            </ul>
          </div>

          <!-- User dropdown (mobile avatar only) -->
          <div class="dropdown">
            <a class="d-flex align-items-center border rounded px-2 py-1 bg-white text-dark dropdown-toggle" href="#"
              role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="{{ asset('images/profile_images/' . (Session::get('img') ?: 'profile.png')) }}" width="36"
                height="36" class="rounded-circle">
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item"
                  href="{{ URL::to('userprofile') }}/{{ \Session::get('id') }}?pagemethod=myprofile">
                  <i class="bi bi-person-circle me-2"></i> My Profile
                </a>
              </li>
              <li>
                <a class="dropdown-item text-danger" href="{{ URL::to('logout') }}">
                  <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
              </li>
            </ul>
          </div>
        </div>


        
        <div class="d-flex ms-auto align-items-center d-none d-lg-flex">

         <div class="col-3 d-flex justify-content-end align-items-center">
    <div class="dropdown me-3">
        <a class="position-relative text-dark"
           href="#"
           id="notifDropdown"
           role="button"
           data-bs-toggle="dropdown"
           aria-expanded="false"
           title="Notifications">

            <i class="bi bi-bell-fill fs-4 
                <?= $count_noti > 0 ? 'bell-animate bell-glow' : '' ?>"
                style="color:#fff;">
            </i>

            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?= $count_noti > 0 ? $count_noti : 0 ?>
            </span>
        </a>

        <ul class="dropdown-menu dropdown-menu-end p-2 shadow"
            aria-labelledby="notifDropdown"
            style="min-width:350px; max-height:400px; overflow-y:auto;">

            <?php if ($count_noti > 0): ?>
                <?php foreach ($count_no as $value): ?>
                    <li>
                        <div class="alert alert-primary mb-2 p-2">
                            <a href="{{ URL::to($value->reference_url . '?ref_id=' . $value->notification_id) }}"
                               class="text-success text-decoration-none">
                                <?= $value->description ?>
                            </a>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="text-center text-muted">No New Notifications</li>
            <?php endif; ?>
        </ul>
    </div>
</div>


          <!-- User Dropdown with greeting -->
          <div class="dropdown">
            <a class="d-flex align-items-center border rounded px-3 py-2 bg-white text-dark dropdown-toggle" href="#"
              role="button" data-bs-toggle="dropdown">
              <img src="{{ asset('images/profile_images/' . (Session::get('img') ?: 'profile.png')) }}" width="40"
                height="40" class="rounded-circle me-2">
              <strong>Hi, {{ \Session::get('first_name') }}!</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item"
                  href="{{ URL::to('userprofile') }}/{{ \Session::get('id') }}?pagemethod=myprofile">
                  <i class="bi bi-person-circle me-2"></i> My Profile</a></li>
              <li><a class="dropdown-item text-danger" href="{{ URL::to('logout') }}">
                  <i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
            </ul>
          </div>

        </div>

      </div>
    </nav>


    <!-- Page Layout -->
    <div class="container-fluid">
      <div class="row">
        <!-- Sidebar -->
        <div id="sidebara" class="col-md-2 col-md-1 p-0 bg-light">
          @include('layouts.menu')
        </div>
        <div class="mobile-Menu" style="display: none;">
          @include('layouts.mobilemenu')
        </div>
        <!-- Main Content -->
        <div id="mainContent" class="col-md-10 col-11 py-4"
          style="padding-left: 70px;font-size: 14px;padding-top: 100px !important;">
          @yield('content')
        </div>
      </div>
    </div>

    @include('layouts.footer')
  </header>


  <!-- Core Dependencies -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- DataTables Core + Bootstrap 5 -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  <!-- DataTables Extensions -->
  <script src="https://cdn.datatables.net/colreorder/1.7.0/js/dataTables.colReorder.min.js"></script>
  <script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
  <!-- Buttons Extension (Excel + Column Visibility) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

  <!-- Other Plugins -->
  <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/parsley.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script>

  <!-- Charts + Misc -->
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/funnel.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>

  <!-- Your Local Scripts -->
  <script src="{{ asset('js/moment.js') }}"></script>
  <script src="{{ asset('js/daterangepicker.js') }}"></script>
  <script src="{{ asset('js/sweet-alert.min.js') }}"></script>
  <script src="{{ asset('js/scroll.js') }}"></script>



  @stack('scripts')
  @yield('scripts')

  <script>

    // functions	
    $(document).ready(function () {
      if ($.fn.select2) {
        $('.select2').select2({ width: '100%' });
      }
      if ($.fn.datepicker) {
        $('.datepicker').datepicker({ dateFormat: 'dd-mm-yy' });
      }
      if ($.fn.DataTable) {
        $('.datatable').DataTable();
      }

      // focus search input whenever any Select2 opens
      $(document).on('select2:open', function () {
        // small timeout lets Select2 finish rendering
        setTimeout(function () {
          const input = document.querySelector('.select2-container--open .select2-search__field');
          if (input) input.focus();
        }, 0);
      });

      // Highlight active menu and build breadcrumb
      var currentPath = window.location.pathname.replace(/^\/+|\/+$/g, '');
      var $activeLink = $('.sidebara a').filter(function () {
        return this.pathname.replace(/^\/+|\/+$/g, '') === currentPath;
      });

      if ($activeLink.length > 0) {
        $activeLink.addClass('active2');
        var $li = $activeLink.closest('li');
        var $menuList = $li.closest('.menu-list');
        var $submenu = $menuList.prev('.submenu');
        var $submenuContainer = $menuList.closest('.submenu-container');
        var $parentMenu = $submenuContainer.prev('.parent-menu');

        $parentMenu.find('.menu-label').addClass('active-label');
        $parentMenu.find('.menu_icon').addClass('active-icon');

        $li.addClass('active2');
        $submenu.addClass('active2');
        $parentMenu.addClass('active-parent');
        $submenuContainer.show();
        $menuList.show();

        var menuText = $activeLink.text().trim();
        $('head title').text("Dr JRK's ERP - " + menuText);

        var breadcrumb = '<li><a href="{{ url('home') }}">Home</a></li>';
        breadcrumb += '<li>' + $parentMenu.text().trim() + '</li>';
        breadcrumb += '<li>' + $submenu.text().trim() + '</li>';
        breadcrumb += '<li>' + menuText + '</li>';

        @if (!empty($urlmenu['check']))
          breadcrumb += "<li><a href='#' onclick='return false;'>{{ $urlmenu['label'] }}</a></li>";
        @endif

        $('.breadcrumb').html(breadcrumb);
      }
    });

    // Turn off autocomplete globally
    $('form').attr('autocomplete', 'off');

    // AJAX content load for links with .contentonly class
    $(document).on('click', '.contentonly', function () {
      const content = $(this).attr('href');
      $.get(content, function (data) {
        $('.contentdata').html(data);
      });
      return false;
    });

    // Notification functions
    function notyMsg(status, msg) {
      notif({ type: status, msg: msg, position: "right" });
    }
    function notyMsgs(status, msg) {
      notif({ type: status, msg: msg, position: "right", timeout: 6500 });
    }

    // Cloning table rows
    function cloneRow(cloneClass, cloneBody) {
      const cloned = $('.' + cloneClass).find('tr:eq(1)').clone();
      cloned.find("input, textarea, select").val("");
      cloned.appendTo('.' + cloneBody);
    }

    // SweetAlert message
    function notyMessage(status, message, red_url, create_url, edit_url) {
      swal({
        title: status,
        text: message,
        type: status,
        showCancelButton: true,
        confirmButtonText: "Ok",
        cancelButtonText: "Create",
        closeOnConfirm: false,
        closeOnCancel: false
      });

      $(document).on('click', '.confirm', () => window.location.href = red_url);
      $(document).on('click', '.cancel', () => window.location.href = create_url);
      $(document).on('click', '.apply', () => window.location.href = edit_url);
    }

    // Column chooser for jqGrid
    function showcolumn(tableid) {
      $(document).on('click', ".showcolumn", function () {
        $('#' + tableid).jqGrid('columnChooser');
      });
    }

    // Duplicate entry checkers (for product, invoice, batch, etc.)
    function checkDuplicate(className, valueToCheck, index) {
      let count = 0;
      $('.clone').each(function (ind) {
        const val = $("." + className + ind).val();
        if (val && index !== ind && val === valueToCheck) count++;
      });
      return count;
    }

    // Scroll to top
    $(document).ready(function () {
      $(window).scroll(function () {
        $('#scroll').toggle($(this).scrollTop() > 80);
      });
      $('#scroll').click(function () {
        $('html, body').animate({ scrollTop: 0 }, 600);
        return false;
      });
    });

    // Parsley init globally
    $(document).ready(function () {
      if ($.fn.parsley) {
        $('form[data-parsley-validate]').each(function () {
          $(this).parsley();
        });
      }
    });

    // Select2 required field validation on close
    $(document).on("select2:close", "select", function () {
      if ($.fn.parsley) $(this).parsley().validate();
    });


    // popup for success and error
    function showCustomAlert(message, type = 'success') {
      const alertBox = $('#customAlert');
      alertBox.removeClass('alert-success alert-danger alert-warning alert-primary d-none');

      // Choose alert type
      if (type === 'success') {
        alertBox.addClass('alert-success-custom');
      } else if (type === 'error') {
        alertBox.addClass('alert-danger-custom');
      } else if (type === 'info') {
        alertBox.addClass('alert-info-custom');
      } else {
        alertBox.addClass('alert-warning-custom');
      }

      $('#alertMessage').text(message);
      alertBox.addClass('show');

      // Auto hide after 5 seconds
      setTimeout(() => {
        alertBox.removeClass('show').addClass('d-none');
      }, 5000);
    }



    // Generic click handler for any delete button
    $(document).on('click', '.delete-btn', function () {
      deleteId = $(this).data('id');
      deleteUrl = $(this).data('url'); // pass full route dynamically
      $('#globalDeleteModal').modal('show');
    });

    // Handle confirmation
    $('#globalConfirmDeleteBtn').on('click', function () {
      if (deleteId && deleteUrl) {
        $.ajax({
          url: deleteUrl + '/' + deleteId,
          type: "GET",
          success: function (response) {
            $('#globalDeleteModal').modal('hide');
            showCustomAlert(response.message, 'success');
            // Optional: reload a DataTable if present
            if ($('#organizationTable').length) {
              $('#organizationTable').DataTable().ajax.reload();
            }
          },
          error: function (xhr) {
            $('#globalDeleteModal').modal('hide');
            const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
            showCustomAlert(errorMsg, 'error');
          }
        });
      }
    });

    $('.select2').select2({
      width: '100%'
    });

    // Datepicker global use config
    $(document).ready(function () {
      const dateFormat = "{{ \Session('j_date_format') ?? 'yy-mm-dd' }}";

      // Initially disable end_date
      $(".end_date").prop("disabled", true).val("");

      $(document).on("focus", ".start_date", function () {
        $(this).datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: dateFormat,
          minDate: new Date(2024, 3, 1),
          maxDate: 0,
          showAnim: "slideDown",
          yearRange: "-25:+0",
          onClose: function (selectedDate) {
            if (selectedDate) {
              const startDate = $(this).datetimepicker("getDate");

              // Enable end_date
              $(".end_date").prop("disabled", false);

              // Reinitialize end_date with updated minDate
              $(".end_date").datepicker("destroy").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: dateFormat,
                minDate: startDate, // Disallow dates before start time
                maxDate: 0,
                showAnim: "slideDown",
                yearRange: "-25:+0"
              });
            }
          }
        });
      });
    });
    // global use for datepicker time 
    $(document).ready(function () {
      const dateFormat = "{{ \Session('j_date_format') ?? 'yy-mm-dd' }}";
      const timeFormat = "HH:mm:ss"; // 24-hour format with seconds

      // Initially disable end_date_time
      $(".end_date_time").prop("disabled", true).val("");

      $(document).on("focus", ".start_date_time", function () {
        $(this).datetimepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: dateFormat,
          timeFormat: timeFormat,
          controlType: 'select',
          oneLine: true,
          showSecond: true,
          minDate: new Date(2024, 3, 1), // April is month 3 (0-indexed)
          maxDate: 0,
          showAnim: "slideDown",
          yearRange: "-25:+0",
          onClose: function (selectedDateTime) {
            if (selectedDateTime) {
              const startDate = $(this).datetimepicker("getDate");

              // Enable end_date_time
              $(".end_date_time").prop("disabled", false);

              // Reinitialize end_date_time with updated minDate
              $(".end_date_time").datetimepicker("destroy").datetimepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: dateFormat,
                timeFormat: timeFormat,
                controlType: 'select',
                oneLine: true,
                showSecond: true,
                minDate: startDate, // Disallow dates before start time
                maxDate: 0,
                showAnim: "slideDown",
                yearRange: "-25:+0"
              });
            }
          }
        });
      });
    });

    // global use for datepicker time - without endtime disabled
    $(document).ready(function () {

      const dateFormat = "{{ \Session('j_date_format') ?? 'yy-mm-dd' }}";
      const timeFormat = "HH:mm:ss";


      $(document).on("focus", ".start_date_time1", function () {
        $(this).datetimepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: dateFormat,
          timeFormat: timeFormat,
          controlType: 'select',
          oneLine: true,
          showSecond: true,
          minDate: new Date(2024, 3, 1),
          maxDate: 0,
          showAnim: "slideDown",
          yearRange: "-25:+0",
          onClose: function (selectedDateTime) {
            if (selectedDateTime) {
              const startDate = $(this).datetimepicker("getDate");

              // Reinitialize end_date_time with updated minDate
              $(".end_date_time1").datetimepicker("destroy").datetimepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: dateFormat,
                timeFormat: timeFormat,
                controlType: 'select',
                oneLine: true,
                showSecond: true,
                minDate: startDate, // Disallow dates before start time
                maxDate: 0,
                showAnim: "slideDown",
                yearRange: "-25:+0"
              });
            }
          }
        });
      });
    });

    // date picker without disabled end date	
    $(document).ready(function () {

      const dateFormat = "{{ \Session('j_date_format') ?? 'yy-mm-dd' }}";

      $(document).on("focus", ".start_date1", function () {
        $(this).datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: dateFormat,
          minDate: new Date(2024, 3, 1),
          maxDate: 0,
          showAnim: "slideDown",
          yearRange: "-25:+0",
          onClose: function (selectedDate) {
            if (selectedDate) {
              const startDate = $(this).datetimepicker("getDate");

              // Reinitialize end_date1 with updated minDate
              $(".end_date1").datepicker("destroy").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: dateFormat,
                minDate: startDate, // Disallow dates before start time
                maxDate: 0,
                showAnim: "slideDown",
                yearRange: "-25:+0"
              });
            }
          }
        });
      });
    });

    // Session Expire
    let inactivityTime = 20 * 60 * 1000; // 20 minutes
    let logoutCountdown = 60;
    let inactivityTimer, countdownTimer;
    let modalShown = false;

    const modalElement = document.getElementById('logout_popup');
    const secondsSpan = document.getElementById('seconds_time');
    const continueBtn = document.getElementById('continue_session');

    // Bootstrap modal instance
    const logoutModal = new bootstrap.Modal(modalElement);

    function resetInactivityTimer() {
      if (modalShown) return;

      clearTimeout(inactivityTimer);
      inactivityTimer = setTimeout(showLogoutPopup, inactivityTime);
    }

    function showLogoutPopup() {
      modalShown = true;
      logoutCountdown = 60;
      secondsSpan.textContent = logoutCountdown;

      logoutModal.show();

      countdownTimer = setInterval(() => {
        logoutCountdown--;
        secondsSpan.textContent = logoutCountdown;

        if (logoutCountdown <= 0) {
          clearInterval(countdownTimer);
          window.location.href = "{{ url('logout') }}";
        }
      }, 1000);
    }

    // Continue Session Button
    continueBtn.addEventListener('click', () => {
      clearInterval(countdownTimer);
      modalShown = false;
      logoutModal.hide();
      resetInactivityTimer();
    });

    // Detect user activity
    ['mousemove', 'mousedown', 'keypress', 'scroll', 'touchstart'].forEach(event => {
      document.addEventListener(event, resetInactivityTimer, true);
    });

    // Start timer on page load
    resetInactivityTimer();


    // no back
    document.addEventListener("DOMContentLoaded", function () {
      // push current state into history 
      history.pushState(null, null, location.href);
      window.onpopstate = function (event) {
        history.go(1);
      };
    });
    
    window.history.forward();
    function noBack() {
      window.history.forward();
    }

    // for product check	
    function pdtcheck(product_id, index) {
      var pdtcount = 0;
      $('.clone').each(function (ind, v) {
        var val = $(".bulk_product_id" + ind).val();
        if ($.trim(val) != "" && val != null) {
          if (index != ind) {
            if (val == product_id) {
              pdtcount++;
            }
          }
        }
      });
      return pdtcount;
    }


    function before_submit_form(form_id) {
      var id = "#" + form_id;
      var id_each = id + ' input,' + id + ' select,' + id + ' textarea';


      var form_data = {};
      var form_fields_data = {};
      var form_fields = {};
      var header = {};
      var lines = {};
      var removed_line_id = {};
      var before_submit_form_status = false;

      $(id_each).each(function (index) {

        var input = $(this);

        var name = input.attr('name');
        var required = input.prop('required');
        var data_required = input.attr('data-required');
        var view = 1;

        if (required == true) {
          required = '';

          if (typeof data_required != "undefined") {
            required = data_required;
          }
        }
        else {
          required = '0';
        }

        var value = input.val();

        var type = '';
        var key_name = '';

        var nodeName = $(this)[0].nodeName;
        nodeName = nodeName.toLowerCase();

        if (nodeName == "input") {
          type = $(this).attr("type");

          if (type == 'hidden') {
            // view=0;
          }
        }
        else {
          type = nodeName;
        }

        //console.log(name+" : "+required+" : "+view);

        if (typeof name !== "undefined") {
          if (!/[\[\]']+/g.test(name)) {
            key_name = name;
            $current_row = { 'field': name, 'type': type, 'required': required, 'value': value, 'view': view };
            form_fields[key_name] = $current_row;
            header[key_name] = form_fields[key_name];

            if (key_name == 'removed_line_id' && form_fields[key_name] != '') {
              removed_line_id = form_fields[key_name];
            }

          }
          else {
            key_name = name.replace(/[\[\]']+/g, '');
            $current_row = { 'field': name, 'type': type, 'required': required, 'value': value, 'view': view };
            form_fields[key_name] = $current_row;
            lines[key_name] = form_fields[key_name];
          }
        }
      });

      var frm_data_serialized = $(id).serializeArray();

      form_data['header'] = header;
      form_data['lines'] = lines;
      form_data['removed_line_id'] = removed_line_id;

      var form_data_json = encodeURIComponent(JSON.stringify(form_data));
      var form_data_json_id = 'form_data_json_' + form_id;
      var form_token_id = 'token_' + form_id;

      //var tok=$("meta[name=csrf-token]").attr("content");
      var tok = "<?php echo csrf_token();?>";

      $('#' + form_data_json_id + ',#' + form_token_id).remove();
      $(id).append('<textarea name="form_data_json" id=' + form_data_json_id + ' class="hide form_data_json ' + form_data_json_id + '" readonly>' + form_data_json + '</textarea>');
      $(id).append('<input type="hidden" id=' + form_token_id + ' class="form_token_id ' + form_token_id + '" name="_token" value="' + tok + '">');
      $form_data_json = $('.' + form_data_json_id).text();

      if ($form_data_json != '') {
        before_submit_form_status = true;
      }
      //alert();

      return before_submit_form_status;

    }

    function validationrule(form_id) {
      var form_id = form_id;
      var bsf = before_submit_form(form_id);
      var form = [];
      form['form_id'] = form_id;
      form['form_validate_status'] = bsf;
      return form;

    }

    function prdtypecheck(product_type_id, index) {
      var pdtcount = 0;
      $('.bulk_product_type_id').each(function (ind, v) {
        var val = $(".bulk_product_type_id" + ind).val();

        if ($.trim(val) != "" && val != null) {

          if (index != ind) {
            if (val == product_type_id) {
              pdtcount++;
            }
          }
        }
      });
      return pdtcount;
    }

    var currentPath = window.location.pathname.replace(/^\/+|\/+$/g, '');
    var $activeLink = $('.sidebara a').filter(function () {
      return this.pathname.replace(/^\/+|\/+$/g, '') === currentPath;
    });
    var menuText = $activeLink.text().trim();

    // datatable column Reorder - yajra
    $.extend(true, $.fn.dataTable.defaults, {

      rowCallback: function (row, data) {
        var activeVal = (data.active || '').toString().trim().toLowerCase();
        if (activeVal === 'no' || activeVal === '0' || activeVal === 'false') {
          $(row).addClass('table-danger');
        }
      },
      pageLength: 10,
      lengthMenu: [[10, 25, 50, 100, 500, 1000, 10000], [10, 25, 50, 100, 500, 1000, 10000]],
      autoWidth: false,
      colReorder: {
        realtime: true
      },
      //  Keep column alignment when multi-row headers exist
      orderCellsTop: true,
      //  Fixed Header (freeze top header on scroll)
      fixedHeader: {
        header: true,
        headerOffset: 85  // Adjust if you have a sticky navbar (~60px height)
      },
      dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
      buttons: [
        {
          extend: 'colvis',
          text: '<i class="bi bi-layout-three-columns"></i> Columns',
          className: 'btn bg-primary btn-sm',
          postfixButtons: ['colvisRestore'] // adds "Restore Columns" button
        },
        { extend: 'excelHtml5', title: menuText, exportOptions: { columns: ':visible' } }
      ]
    });

    // Optional visual cue for drag-enabled headers
    $(document).on('init.dt', function (e, settings) {
      const tableId = settings.nTable.id;
      const $table = $('#' + tableId);

      // Allow dragging only on the first header row (column names)
      $table.find('thead tr:eq(1) th').off('.ColReorder');
      $table.find('thead tr:first-child th').css('cursor', 'move');

      // Keep search input functionality
      $table.find('thead tr:eq(1) th').each(function (i) {
        $('input', this).off().on('keyup change', function () {
          const dt = $table.DataTable();
          if (dt.column(i).search() !== this.value) {
            dt.column(i).search(this.value).draw();
          }
        });
      });
    });

    // form validation
    function highlightInvalidFields(form) {

      // Remove previous invalid marks
      form.find(".is-invalid").removeClass("is-invalid");
      form.find(".select2-container").removeClass("is-invalid");

      // Loop all parsley error fields
      form.find('.parsley-error').each(function () {

        // Highlight normal input
        $(this).addClass('is-invalid');

        // Special condition for Select2
        if ($(this).hasClass("select2-hidden-accessible")) {

          // Select2 wrapper
          let s2 = $(this).next(".select2-container");

          s2.addClass("is-invalid");
          s2.find(".select2-selection").addClass("is-invalid");
        }
      });
    }


    $(document).on('click', '.saveform', function () {
      let form = $(this).closest("form");
      form.parsley().validate();

      if (!form.parsley().isValid()) {
        highlightInvalidFields(form);
        return false;
      }
    });


  </script>

</body>

</html>