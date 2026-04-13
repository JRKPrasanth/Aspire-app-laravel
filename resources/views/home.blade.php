@extends('layouts.header')
@section('content')

    <style>
        .holo-card {
            position: relative;
            padding: 20px;
            border-radius: 12px;
            color: cyan;
            font-weight: bold;
            overflow: hidden;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }


        /* Hover animation */
        .holo-card:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px #000;
        }

        .holo-card:hover::before {
            opacity: 1;
            transform: rotate(-45deg) translateY(100%);
        }


        .dashboard-card {
            border-radius: 20px;
            padding: 25px;
            height: 180px;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.12);
            transition: 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        /* Gradient colors */
        .card-pink {
            background: linear-gradient(to right, #ffbf96, #fe7096);
        }

        .card-blue {
            background: linear-gradient(to right, #90caf9, #047edf 99%);
        }

        .card-green {
            background: linear-gradient(to right, #84d9d2, #07cdae);
        }

        .card-orange {
            background: linear-gradient(135deg, #ffb974, #ff8a3d);
        }

        .card-violet {
            background: linear-gradient(to right, #da8cff, #9a55ff);
        }

        .card-account {
           background-image: linear-gradient(to top, #6a85b6 0%, #bac8e0 100%);
        }
        /* Circle background shapes */
        .dashboard-card::before,
        .dashboard-card::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.20);
        }

        .dashboard-card::before {
            width: 140px;
            height: 140px;
            top: -40px;
            right: -30px;
        }

        .dashboard-card::after {
            width: 100px;
            height: 100px;
            bottom: -30px;
            right: 10px;
        }

        .dashboard-title {
            font-size: 20px;
            font-weight: 700;
        }

        .dashboard-value {
            font-size: 32px;
            font-weight: 900;
            margin-top: 10px;
        }

        /* Modal backdrop like screenshot */
        .announcement-modal .modal-backdrop.show,
        .modal-backdrop.show {
            opacity: .65;
        }

        /* Popup size similar to screenshot */
        .announcement-modal .modal-dialog {
            max-width: 760px;
            /* adjust if you want wider/narrower */
            width: 92%;
        }

        /* White rounded card */
        .announcement-card {
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 15px 45px rgba(0, 0, 0, .25);
            background: #fff;
            position: relative;
        }

        /* Close button: black circle with white X */
        .announcement-close {
            position: absolute;
            top: 2px;
            right: 10px;
            width: 18px;
            height: -49px;
            border: 0;
            background: rgba(0, 0, 0, .075);
            color: #0d0d0d;
            font-size: 30px;
            line-height: 16px;
            z-index: 10;
            cursor: pointer;
        }

        /* Image fills the popup */
        .announcement-frame {
            position: relative;
            width: 100%;
        }

        .announcement-img {
            display: block;
            width: 100%;
            object-fit: cover;

        }

        /* Popper overlay on top of image */
        .popper-img {
            position: absolute;
            width: 80%;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2;
            pointer-events: none;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: #b5b5b5;
            border-radius: 50%;
            padding: 15px;
        }


    </style>

    <!-- Announcement popup -->
    @if(session('show_announcement') && $announcements->count() > 0)
        <div class="modal fade announcement-modal" id="announcementModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content announcement-card border-0">

                    {{-- Close button (black circle like screenshot) --}}
                    <button type="button" class="announcement-close" data-bs-dismiss="modal" aria-label="Close">×</button>

                    <div class="modal-body p-0">
                        @if($announcements->count() == 1)
                            @php $ann = $announcements->first(); @endphp

                            <div class="announcement-frame">
                                <img src="{{ asset('images/announcement/' . $ann->id . '/' . $ann->attachment) }}"
                                    class="announcement-img" alt="Announcement">

                                {{-- popper overlay --}}
                                <img src="{{ asset('images/popper.gif') }}" class="popper-img" alt="Celebration">
                            </div>

                        @else
                            <div id="announcementCarousel" 
                                    class="carousel slide" 
                                    data-bs-ride="carousel" 
                                    data-bs-interval="2000"> 
                                <div class="carousel-inner">
                                    @foreach($announcements as $key => $ann)
                                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                            <div class="announcement-frame">
                                                <img src="{{ asset('images/announcement/' . $ann->id . '/' . $ann->attachment) }}"
                                                    class="announcement-img" alt="Announcement">

                                                <img src="{{ asset('images/popper.gif') }}" class="popper-img" alt="Celebration">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button class="carousel-control-prev" type="button" data-bs-target="#announcementCarousel"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>

                                <button class="carousel-control-next" type="button" data-bs-target="#announcementCarousel"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        @php session()->forget('show_announcement'); @endphp
    @endif


    <!-- popup end -->

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-danger">Dashboard</h2>
            </div>
        </div>

        <div class="row g-4">

            @foreach ($das_access as $val)

                @if ($val == 'HRMSDashboard')
                    <div class="col-12 col-md-4 col-lg-4">
                        <a href="hrmshome" class="text-decoration-none">
                            <div class="dashboard-card card-pink">
                                <div class="dashboard-title">HRMS Dashboard <i class="bi bi-people-fill fs-2 float-end"></i></div>
                                <div class="dashboard-value">→</div>
                            </div>
                        </a>
                    </div>

                @elseif ($val == 'PurchaseDashboard')
                    <div class="col-12 col-md-4 col-lg-4">
                        <a href="purchasedashboard" class="text-decoration-none">
                            <div class="dashboard-card card-blue">
                                <div class="dashboard-title">Purchase Dashboard <i class="bi bi-bag-check-fill fs-2 float-end"></i>
                                </div>
                                <div class="dashboard-value">→</div>
                            </div>
                        </a>
                    </div>

                @elseif ($val == 'OperationDashboard')
                    <div class="col-12 col-md-4 col-lg-4">
                        <a href="operationanalyserpt" class="text-decoration-none">
                            <div class="dashboard-card card-green">
                                <div class="dashboard-title">Operation Dashboard <i
                                        class="bi bi-gear-fill fs-2 float-end"></i></div>
                                <div class="dashboard-value">→</div>
                            </div>
                        </a>
                    </div>

                @elseif ($val == 'MaintenanceDashboard')
                    <div class="col-12 col-md-4 col-lg-4">
                        <a href="machinemintenancelogreport" class="text-decoration-none">
                            <div class="dashboard-card card-orange">
                                <div class="dashboard-title">Maintenance Dashboard <i class="bi bi-tools fs-2 float-end"></i></div>
                                <div class="dashboard-value">→</div>
                            </div>
                        </a>
                    </div>

                @elseif ($val == 'ProductionDashboard')
                    <div class="col-12 col-md-4 col-lg-4">
                        <a href="productiondashboard" class="text-decoration-none">
                            <div class="dashboard-card card-violet">
                                <div class="dashboard-title">Production Dashboard <i class="bi bi-box-seam-fill fs-2 float-end"></i>
                                </div>
                                <div class="dashboard-value">→</div>
                            </div>
                        </a>
                    </div>

                     @elseif ($val == 'AccountsDashboard')
                    <div class="col-12 col-md-4 col-lg-4">
                        <a href="accountsdashboard" class="text-decoration-none">
                            <div class="dashboard-card card-account">
                                <div class="dashboard-title">Accounts Dashboard <i class="bi bi-calculator fs-2 float-end"></i>
                                </div>
                                <div class="dashboard-value">→</div>
                            </div>
                        </a>
                    </div>

                @endif

            @endforeach

        </div>
    </div>


    <!-- For more Dashboards End -->
    <div class="row row-cols-1 row-cols-md-4 g-4 cardtop mt-4">
        <?php error_reporting(0);
    foreach ($das_access as $val) {
        if ($val == 'PODraft') { ?>
        <div class="col">
            <a href="purchaseorder" class="text-decoration-none">
                <div class="card border-primary shadow-lg h-100">
                    <div class="card-body holo-card  text-primary">
                        <h5 class="card-subtitle mb-2"><i class="bi bi-file-earmark-text"></i> PO Draft</h5>
                        <h3 class="fw-bold">{{$po_draft_count[0]->count}}</h3>
                    </div>
                </div>
            </a>
        </div>
        <?php    } else if ($val == 'GRNPending') { ?>
        <div class="col-md-3">
            <a href="Gintable" class="text-decoration-none">
                <div class="card border-danger shadow-lg h-100">
                    <div class="card-body holo-card text-danger">
                        <h5 class="card-title"><i class="bi bi-box"></i> GRN Pending</h5>
                        <h3 class="fw-bold">{{$grn_count[0]->count}}</h3>
                    </div>
                </div>
            </a>
        </div>
        <?php        } else if ($val == 'InvoicePending') { ?>
        <div class="col-md-3">
            <a href="purchaseinvoice" class="text-decoration-none">
                <div class="card border-danger shadow-lg h-100">
                    <div class="card-body holo-card text-danger">
                        <h5 class="card-title"><i class="bi bi-file-earmark-excel"></i> PO Invoice Pending</h5>
                        <h3 class="fw-bold">{{$invoice_count[0]->count}}</h3>
                    </div>
                </div>
            </a>
        </div>
        <?php        } else if ($val == 'InvoiceDraft') { ?>
        <div class="col-md-3">
            <a href="purchaseinvoice" class="text-decoration-none">
                <div class="card border-primary shadow-lg h-100">
                    <div class="card-body holo-card text-primary">
                        <h5 class="card-title"><i class="bi bi-pencil-square"></i> PO Invoice Draft</h5>
                        <h3 class="fw-bold">{{$invoice_draft_count[0]->count}}</h3>
                    </div>
                </div>
            </a>
        </div>
        <?php        } else if ($val == 'POApproval') { ?>
        <div class="col-md-3">
            <a href="purchaseorder" class="text-decoration-none">
                <div class="card border-success shadow-lg h-100">
                    <div class="card-body holo-card text-success">
                        <h5 class="card-title"><i class="bi bi-check2-circle"></i> PO Approval</h5>
                        <h3 class="fw-bold">{{$po_initiated_count[0]->count}}</h3>
                    </div>
                </div>
            </a>
        </div>
        <?php        } else if ($val == 'POInvoiceApproval') { ?>
        <div class="col-md-3">
            <a href="poinvoiceapproval" class="text-decoration-none">
                <div class="card border-success shadow-lg h-100">
                    <div class="card-body holo-card text-success">
                        <h5 class="card-title"><i class="bi bi-journal-check"></i> PO Invoice Approval</h5>
                        <h3 class="fw-bold">{{$invoice_initiated_count[0]->count}}</h3>
                    </div>
                </div>
            </a>
        </div>
        <?php        } else if ($val == 'ProductApproval') { ?>
        <div class="col-md-3">
            <a href="product" class="text-decoration-none">
                <div class="card border-success shadow-lg h-100">
                    <div class="card-body holo-card text-success">
                        <h5 class="card-title"><i class="bi bi-cube"></i> Product Approval</h5>
                        <h3 class="fw-bold">{{$product_initiated_count[0]->count}}</h3>
                    </div>
                </div>
            </a>
        </div>
        <?php        } else if ($val == 'BOMApproval') { ?>
        <div class="col-md-3">
            <a href="materialbom" class="text-decoration-none">
                <div class="card border-success shadow-lg h-100">
                    <div class="card-body holo-card text-success">
                        <h5 class="card-title"><i class="bi bi-clipboard-check"></i> BOM Approval</h5>
                        <h3 class="fw-bold">{{$bom_initiated_count[0]->count}}</h3>
                    </div>
                </div>
            </a>
        </div>
        <?php        } else if ($val == 'SupplierApproval') { ?>
        <div class="col-md-3">
            <a href="supplier" class="text-decoration-none">
                <div class="card border-success shadow-lg h-100">
                    <div class="card-body holo-card text-success">
                        <h5 class="card-title"><i class="bi bi-people"></i> Supplier Approval</h5>
                        <h3 class="fw-bold">{{$supplier_initiated_count[0]->count}}</h3>
                    </div>
                </div>
            </a>
        </div>
        <?php        } else if ($val == 'PurchasePricelistApproval') { ?>
        <div class="col-md-3">
            <a href="purchasepricelist" class="text-decoration-none">
                <div class="card border-success shadow-lg h-100">
                    <div class="card-body holo-card text-success">
                        <h5 class="card-title"><i class="bi bi-tags"></i> Pricelist (Purchase) Approval</h5>
                        <h3 class="fw-bold">{{$purpricelist_initiated_count[0]->count}}</h3>
                    </div>
                </div>
            </a>
        </div>
        <?php        } else if ($val == 'POQualityPending') { ?>
        <div class="col-md-3">
            <a href="qcgrntable" class="text-decoration-none">
                <div class="card border-danger shadow-lg h-100">
                    <div class="card-body holo-card text-danger">
                        <h5 class="card-title"><i class="bi bi-shield-exclamation"></i> PO Quality Pending</h5>
                        <h3 class="fw-bold">{{$quality_count[0]->count}}</h3>
                    </div>
                </div>
            </a>
        </div>
        <?php        }
    } ?>
    </div>

    <div class="row row-cols-1 row-cols-md-4 g-4 cardtop mt-4">
        <?php error_reporting(0);
    foreach ($das_access as $val) { ?>

        <?php    if ($val == 'SODraft') { ?>
        <div class="col">
            <a href="soorder" class="text-decoration-none">
                <div class="card h-100 border-primary shadow-lg">
                    <div class="card-body holo-card text-primary">
                        <h6 class="card-subtitle mb-2"><i class="bi bi-file-earmark-text"></i> SO Draft</h6>
                        <h4 class="fw-bold">{{$so_draft_count[0]->count}}</h4>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == 'DispatchDraft') { ?>
        <div class="col">
            <a href="dispatchfrmso" class="text-decoration-none">
                <div class="card h-100 border-info shadow-lg">
                    <div class="card-body holo-card text-info">
                        <h6 class="card-subtitle mb-2"><i class="bi bi-truck"></i> Dispatch Draft</h6>
                        <h4 class="fw-bold">{{$dispatch_count[0]->count}}</h4>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == 'SInvoicePending') { ?>
        <div class="col">
            <a href="salesinvoice" class="text-decoration-none">
                <div class="card h-100 border-danger shadow-lg">
                    <div class="card-body holo-card text-danger">
                        <h6 class="card-subtitle mb-2"><i class="bi bi-journal-x"></i> SO Invoice Pending</h6>
                        <h4 class="fw-bold">{{$so_invoice_count[0]->count}}</h4>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == 'SOInvoiceDraft') { ?>
        <div class="col">
            <a href="salesinvoice" class="text-decoration-none">
                <div class="card h-100 border-primary shadow-lg">
                    <div class="card-body holo-card text-primary">
                        <h6 class="card-subtitle mb-2"><i class="bi bi-pencil-square"></i> SO Invoice Draft</h6>
                        <h4 class="fw-bold">{{$so_invoice_draft_count[0]->count}}</h4>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == 'SOApproval') { ?>
        <div class="col">
            <a href="salesorderapproval" class="text-decoration-none">
                <div class="card h-100 border-success shadow-lg">
                    <div class="card-body holo-card text-success">
                        <h6 class="card-subtitle mb-2"><i class="bi bi-check-circle"></i> SO Approval</h6>
                        <h4 class="fw-bold">{{$so_initiated_count[0]->count}}</h4>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == 'SOInvoiceApproval') { ?>
        <div class="col">
            <a href="salesinvoiceapproval" class="text-decoration-none">
                <div class="card h-100 border-success shadow-lg">
                    <div class="card-body holo-card text-success">
                        <h6 class="card-subtitle mb-2"><i class="bi bi-clipboard-check"></i> SO Invoice Approval</h6>
                        <h4 class="fw-bold">{{$so_invoice_initiated_count[0]->count}}</h4>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == 'CustomerApproval') { ?>
        <div class="col">
            <a href="customers" class="text-decoration-none">
                <div class="card h-100 border-success shadow-lg">
                    <div class="card-body holo-card text-success">
                        <h6 class="card-subtitle mb-2"><i class="bi bi-person-check"></i> Customer Approval</h6>
                        <h4 class="fw-bold">{{$customer_initiated_count[0]->count}}</h4>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == 'SalesReturnDirect') { ?>
        <div class="col">
            <a href="salesreturn" class="text-decoration-none">
                <div class="card h-100 border-warning shadow-lg">
                    <div class="card-body holo-card text-warning">
                        <h6 class="card-subtitle mb-2"><i class="bi bi-arrow-left-circle"></i> SO Return Direct</h6>
                        <h4 class="fw-bold">{{$salesr_direct_count[0]->count}}</h4>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == 'SalesReturnInvoice') { ?>
        <div class="col">
            <a href="salesreturn" class="text-decoration-none">
                <div class="card h-100 border-danger shadow-lg">
                    <div class="card-body holo-card text-danger">
                        <h6 class="card-subtitle mb-2"><i class="bi bi-arrow-return-left"></i> SO Return Invoice</h6>
                        <h4 class="fw-bold">{{$salesr_invoice_count[0]->count}}</h4>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == 'SalesReturnFromInvoice') { ?>
        <div class="col">
            <a href="salesreturnfrominvoice" class="text-decoration-none">
                <div class="card h-100 border-primary shadow-lg">
                    <div class="card-body holo-card text-primary">
                        <h6 class="card-subtitle mb-2"><i class="bi bi-arrow-left-right"></i> SO Return From Invoice</h6>
                        <h4 class="fw-bold">{{$sales_returninvoice_count[0]->count}}</h4>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == 'SalesPricelistApproval') { ?>
        <div class="col">
            <a href="salespricelist" class="text-decoration-none">
                <div class="card h-100 border-success shadow-lg">
                    <div class="card-body holo-card text-success">
                        <h6 class="card-subtitle mb-2"><i class="bi bi-tag"></i> Sales Pricelist Approval</h6>
                        <h4 class="fw-bold">{{$salpricelist_initiated_count[0]->count}}</h4>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == 'SOReturnApproval') { ?>
        <div class="col">
            <a href="salesreturn" class="text-decoration-none">
                <div class="card h-100 border-success shadow-lg">
                    <div class="card-body holo-card text-success">
                        <h6 class="card-subtitle mb-2"><i class="bi bi-check2-square"></i> SO Return Approval</h6>
                        <h4 class="fw-bold">{{$soreturn_initiated_count[0]->count}}</h4>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == 'ShipConfirmPending') { ?>
        <div class="col">
            <a href="salesdispatchshipconfirm" class="text-decoration-none">
                <div class="card h-100 border-danger shadow-lg">
                    <div class="card-body holo-card text-danger">
                        <h6 class="card-subtitle mb-2"><i class="bi bi-box-seam"></i> Ship Confirm Pending</h6>
                        <h4 class="fw-bold">{{$shipconfirmpen_count[0]->count}}</h4>
                    </div>
                </div>
            </a>
        </div>
        <?php    } ?>
        <?php } ?>
    </div>

    <!-- LEAVE SECTION -->
    <hr class="my-4">
    <div class="row row-cols-1 row-cols-md-4 g-4 cardtop mt-4">
        <?php
    foreach ($das_access as $val) {
        if ($val == 'CLBalance') { ?>
        <div class="col">
            <a href="leave" class="text-decoration-none">
                <div class="card h-100 border-info shadow-lg">
                    <div class="card-body holo-card text-info">
                        <h6 class="card-subtitle mb-2"><i class="bi bi-calendar-day"></i> Casual Leave Balance</h6>
                        <h4 class="fw-bold">{{$cl_balance_count[0]->causal_leave}}</h4>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == 'ELBalance') { ?>
        <div class="col">
            <a href="leave" class="text-decoration-none">
                <div class="card h-100 border-success shadow-lg">
                    <div class="card-body holo-card text-success">
                        <h6 class="card-subtitle mb-2"><i class="bi bi-calendar-check"></i> Earn Leave Balance</h6>
                        <h4 class="fw-bold">{{$el_balance_count[0]->earn_leave}}</h4>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == 'Comp-OffBalance') { ?>
        <div class="col">
            <a href="leave" class="text-decoration-none">
                <div class="card h-100 border-warning shadow-lg">
                    <div class="card-body holo-card text-warning">
                        <h6 class="card-subtitle mb-2"><i class="bi bi-hourglass-split"></i> Comp-Off Leave Balance</h6>
                        <h4 class="fw-bold">{{$compoff_balance_count[0]->comp_off_leave}}</h4>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == 'LeaveApproval') { ?>
        <div class="col">
            <a href="leave" class="text-decoration-none">
                <div class="card h-100 border-info shadow-lg">
                    <div class="card-body holo-card text-info">
                        <h6 class="card-subtitle mb-2"><i class="bi bi-file-earmark-plus"></i> Perm./Leave/OD Initiated</h6>
                        <h4 class="fw-bold">{{$leave_initiated[0]->count}}</h4>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == 'LeaveApprovePending') { ?>
        <div class="col">
            <a href="leaveapproval" class="text-decoration-none">
                <div class="card h-100 border-danger shadow-lg">
                    <div class="card-body holo-card text-danger">
                        <h6 class="card-subtitle mb-2"><i class="bi bi-hourglass-top"></i> Leave Approve Pending</h6>
                        <h4 class="fw-bold">{{$report_approve_pending[0]->count}}</h4>
                    </div>
                </div>
            </a>
        </div>
        <?php    }
    } ?>
    </div>


    <div class="container-fluid">
        <div class="row row-cols-1 row-cols-md-5 g-3" style="margin-top:12px;">
            <?php error_reporting(0);
    foreach ($das_access as $val) { ?>

            <?php    if ($val == "ProductionMaterialIssuePending") { ?>
            <div class="col">
                <a href="materialissues" class="text-decoration-none">
                    <div class="card border-danger h-100">
                        <div class="card-body holo-card text-center text-danger">
                            <i class="bi bi-box-seam fs-3"></i>
                            <h6 class="mt-2">Production Mat. Issue Pending</h6>
                            <p class="fw-bold mb-0">{{$production_material_issue_pending[0]->count}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <?php    } elseif ($val == "ProductionMaterialAcknowledgementPending") { ?>
            <div class="col">
                <a href="materialreceive" class="text-decoration-none">
                    <div class="card border-warning h-100">
                        <div class="card-body holo-card text-center text-warning">
                            <i class="bi bi-clipboard-check fs-3"></i>
                            <h6 class="mt-2">Production Mat. Ack. Pending</h6>
                            <p class="fw-bold mb-0">{{$production_material_ack_pending[0]->count}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <?php    } elseif ($val == "ProductionCompletionPending") { ?>
            <div class="col">
                <a href="qasubmitstage" class="text-decoration-none">
                    <div class="card border-primary h-100">
                        <div class="card-body holo-card text-center text-primary">
                            <i class="bi bi-check2-square fs-3"></i>
                            <h6 class="mt-2">Production Completion Pending</h6>
                            <p class="fw-bold mb-0">{{$production_comp_pending[0]->count}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <?php    } elseif ($val == "ProductionQualityPending") { ?>
            <div class="col">
                <a href="qualitycheck" class="text-decoration-none">
                    <div class="card border-info h-100">
                        <div class="card-body holo-card text-center text-info">
                            <i class="bi bi-shield-exclamation fs-3"></i>
                            <h6 class="mt-2">Production Quality Pending</h6>
                            <p class="fw-bold mb-0">{{$production_quality_pending[0]->count}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <?php    } elseif ($val == "ProductionQualityApprovePending") { ?>
            <div class="col">
                <a href="qaapproval" class="text-decoration-none">
                    <div class="card border-success h-100">
                        <div class="card-body holo-card text-center text-success">
                            <i class="bi bi-check2-circle fs-3"></i>
                            <h6 class="mt-2">Production Quality App. Pending</h6>
                            <p class="fw-bold mb-0">{{$production_quality_app_pending[0]->count}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <?php    } elseif ($val == "OperationMaterialIssuePending") { ?>
            <div class="col">
                <a href="materialissues" class="text-decoration-none">
                    <div class="card border-danger h-100">
                        <div class="card-body holo-card text-center text-danger">
                            <i class="bi bi-box fs-3"></i>
                            <h6 class="mt-2">Operation Mat. Issue Pending</h6>
                            <p class="fw-bold mb-0">{{$operation_material_issue_pending[0]->count}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <?php    } elseif ($val == "OperationMaterialAcknowledgementPending") { ?>
            <div class="col">
                <a href="materialreceive" class="text-decoration-none">
                    <div class="card border-warning h-100">
                        <div class="card-body holo-card text-center text-warning">
                            <i class="bi bi-receipt-cutoff fs-3"></i>
                            <h6 class="mt-2">Operation Mat. Ack. Pending</h6>
                            <p class="fw-bold mb-0">{{$operation_material_ack_pending[0]->count}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <?php    } elseif ($val == "OperationCompletionPending") { ?>
            <div class="col">
                <a href="qasubmitstage" class="text-decoration-none">
                    <div class="card border-primary h-100">
                        <div class="card-body holo-card text-center text-primary">
                            <i class="bi bi-check-circle fs-3"></i>
                            <h6 class="mt-2">Operation Completion Pending</h6>
                            <p class="fw-bold mb-0">{{$operation_comp_pending[0]->count}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <?php    } elseif ($val == "OperationQualityPending") { ?>
            <div class="col">
                <a href="qualitycheck" class="text-decoration-none">
                    <div class="card border-info h-100">
                        <div class="card-body holo-card text-center text-info">
                            <i class="bi bi-bug fs-3"></i>
                            <h6 class="mt-2">Operation Quality Pending</h6>
                            <p class="fw-bold mb-0">{{$operation_quality_pending[0]->count}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <?php    } elseif ($val == "OperationQualityApprovePending") { ?>
            <div class="col">
                <a href="qaapproval" class="text-decoration-none">
                    <div class="card border-success h-100">
                        <div class="card-body holo-card text-center text-success">
                            <i class="bi bi-check-lg fs-3"></i>
                            <h6 class="mt-2">Operation Quality App. Pending</h6>
                            <p class="fw-bold mb-0">{{$operation_quality_app_pending[0]->count}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <?php    } elseif ($val == "ExpenseDraft") { ?>
            <div class="col">
                <a href="expenses" class="text-decoration-none">
                    <div class="card border-primary h-100">
                        <div class="card-body holo-card text-center text-primary">
                            <i class="bi bi-pencil-square fs-3"></i>
                            <h6 class="mt-2">Expense Draft</h6>
                            <p class="fw-bold mb-0">{{$exp_draft[0]->count}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <?php    } elseif ($val == "ExpenseApproval") { ?>
            <div class="col">
                <a href="expenseapproval" class="text-decoration-none">
                    <div class="card border-success h-100">
                        <div class="card-body holo-card text-center text-success">
                            <i class="bi bi-person-check fs-3"></i>
                            <h6 class="mt-2">Expense Approval</h6>
                            <p class="fw-bold mb-0">{{$exp_initiate[0]->count}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <?php    } elseif ($val == "PaymentBatchPending") { ?>
            <div class="col">
                <a href="paymentsindex" class="text-decoration-none">
                    <div class="card border-danger h-100">
                        <div class="card-body holo-card text-center text-danger">
                            <i class="bi bi-cash-stack fs-3"></i>
                            <h6 class="mt-2">Payment Batch Pending</h6>
                            <p class="fw-bold mb-0">{{$payment_batch[0]->count}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <?php    } elseif ($val == "PaymentOverduePending") { ?>
            <div class="col">
                <a href="paymentrequest" class="text-decoration-none">
                    <div class="card border-danger h-100">
                        <div class="card-body holo-card text-center text-danger">
                            <i class="bi bi-alarm fs-3"></i>
                            <h6 class="mt-2">Payment Overdue</h6>
                            <p class="fw-bold mb-0">{{$payment_due[0]->count}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <?php    } elseif ($val == "PaymentBRSPending") { ?>
            <div class="col">
                <a href="viewstatementdetails" class="text-decoration-none">
                    <div class="card border-secondary h-100">
                        <div class="card-body holo-card text-center text-secondary">
                            <i class="bi bi-bank fs-3"></i>
                            <h6 class="mt-2">Payment BRS Pending</h6>
                            <p class="fw-bold mb-0">{{$payment_brs[0]->count}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <?php    } elseif ($val == "ReceiptBRSPending") { ?>
            <div class="col">
                <a href="viewstatementdetails" class="text-decoration-none">
                    <div class="card border-secondary h-100">
                        <div class="card-body holo-card text-center text-secondary">
                            <i class="bi bi-wallet fs-3"></i>
                            <h6 class="mt-2">Receipt BRS Pending</h6>
                            <p class="fw-bold mb-0">{{$receipt_brs[0]->count}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <?php    } elseif ($val == "Consumables") { ?>
            <div class="col">
                <a href="consumable" class="text-decoration-none">
                    <div class="card border-info h-100">
                        <div class="card-body holo-card text-center text-info">
                            <i class="bi bi-droplet fs-3"></i>
                            <h6 class="mt-2">Consumables</h6>
                            <p class="fw-bold mb-0">{{$consumables[0]->count}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <?php    } elseif ($val == "ConsumablesApprovalPending") { ?>
            <div class="col">
                <a href="consumableapproval" class="text-decoration-none">
                    <div class="card border-warning h-100">
                        <div class="card-body holo-card text-center text-warning">
                            <i class="bi bi-clipboard-data fs-3"></i>
                            <h6 class="mt-2">Consumables Approval Pending</h6>
                            <p class="fw-bold mb-0">{{$consumables_pending[0]->count}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <?php    } elseif ($val == "AdvanceSet-OffPO") { ?>
            <div class="col">
                <a href="advancestatusview" class="text-decoration-none">
                    <div class="card border-primary h-100">
                        <div class="card-body holo-card text-center text-primary">
                            <i class="bi bi-arrow-left-right fs-3"></i>
                            <h6 class="mt-2">Advance Set-Off (PO)</h6>
                            <p class="fw-bold mb-0">{{$advancesetoff_po[0]->count}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <?php    } ?>

            <?php } ?>
        </div>
    </div>

    <!-- Subinventory Transfer Receive Section -->
    <div class="row row-cols-1 row-cols-md-5 g-3 cardtop mt-4">
        <?php error_reporting(0);
    foreach ($das_access as $val) { ?>
        <?php    if ($val == "SubinventoryTransferReceive-FinishedGoods") { ?>
        <div class="col">
            <a href="subinventorytransferreceive" class="text-decoration-none">
                <div class="card border-primary h-100">
                    <div class="card-body holo-card text-center text-primary">
                        <i class="bi bi-box-seam fs-3"></i>
                        <h6 class="mt-2">Transfer Receive - Finished Goods</h6>
                        <p class="fw-bold mb-0">{{$si_finishgoods[0]->count}}</p>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == "SubinventoryTransferReceive-SampleGoods") { ?>
        <div class="col">
            <a href="subinventorytransferreceive" class="text-decoration-none">
                <div class="card border-success h-100">
                    <div class="card-body holo-card text-center text-success">
                        <i class="bi bi-bezier2 fs-3"></i>
                        <h6 class="mt-2">Transfer Receive - Sample Goods</h6>
                        <p class="fw-bold mb-0">{{$si_samplegoods[0]->count}}</p>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == "SubinventoryTransferReceive-SemiGoods") { ?>
        <div class="col">
            <a href="subinventorytransferreceive" class="text-decoration-none">
                <div class="card border-danger h-100">
                    <div class="card-body holo-card text-center text-danger">
                        <i class="bi bi-layers fs-3"></i>
                        <h6 class="mt-2">Transfer Receive - Semi Goods</h6>
                        <p class="fw-bold mb-0">{{$si_semigoods[0]->count}}</p>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == "SubinventoryTransferReceive-Store") { ?>
        <div class="col">
            <a href="subinventorytransferreceive" class="text-decoration-none">
                <div class="card border-info h-100">
                    <div class="card-body holo-card text-center text-info">
                        <i class="bi bi-shop-window fs-3"></i>
                        <h6 class="mt-2">Transfer Receive - Purchase Store</h6>
                        <p class="fw-bold mb-0">{{$si_store[0]->count}}</p>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == "SubinventoryTransferReceive-accessories") { ?>
        <div class="col">
            <a href="subinventorytransferreceive" class="text-decoration-none">
                <div class="card border-success h-100">
                    <div class="card-body holo-card text-center text-success">
                        <i class="bi bi-puzzle fs-3"></i>
                        <h6 class="mt-2">Transfer Receive - Accessories</h6>
                        <p class="fw-bold mb-0">{{$si_acc[0]->count}}</p>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == "SubinventoryTransferReceive-lab") { ?>
        <div class="col">
            <a href="subinventorytransferreceive" class="text-decoration-none">
                <div class="card border-primary h-100">
                    <div class="card-body holo-card text-center text-primary">
                        <i class="bi bi-eyedropper fs-3"></i>
                        <h6 class="mt-2">Transfer Receive - Laboratory</h6>
                        <p class="fw-bold mb-0">{{$si_lab[0]->count}}</p>
                    </div>
                </div>
            </a>
        </div>
        <?php    } ?>
        <?php } ?>
    </div>

    <!-- Inventory Section -->
    <div class="row row-cols-1 row-cols-md-5 g-3 mt-4 cardtop">
        <?php error_reporting(0);
    foreach ($das_access as $val) { ?>
        <?php    if ($val == "FinishedGoods") { ?>
        <div class="col">
            <a href="product" class="text-decoration-none">
                <div class="card border-primary h-100">
                    <div class="card-body holo-card text-center text-primary">
                        <i class="bi bi-box2 fs-3"></i>
                        <h6 class="mt-2">Finished Goods</h6>
                        <p class="fw-bold mb-0">{{$fg_product_count[0]->count}}</p>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == "RawMaterials") { ?>
        <div class="col">
            <a href="product" class="text-decoration-none">
                <div class="card border-success h-100">
                    <div class="card-body holo-card text-center text-success">
                        <i class="bi bi-boxes fs-3"></i>
                        <h6 class="mt-2">Raw Materials</h6>
                        <p class="fw-bold mb-0">{{$raw_product_count[0]->count}}</p>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == "PackingMaterials") { ?>
        <div class="col">
            <a href="product" class="text-decoration-none">
                <div class="card border-info h-100">
                    <div class="card-body holo-card text-center text-info">
                        <i class="bi bi-bag-check fs-3"></i>
                        <h6 class="mt-2">Packing Materials</h6>
                        <p class="fw-bold mb-0">{{$pack_product_count[0]->count}}</p>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == "SemiFinishedGoods") { ?>
        <div class="col">
            <a href="product" class="text-decoration-none">
                <div class="card border-danger h-100">
                    <div class="card-body holo-card text-center text-danger">
                        <i class="bi bi-diagram-3 fs-3"></i>
                        <h6 class="mt-2">Semi Finished Goods</h6>
                        <p class="fw-bold mb-0">{{$sfg_product_count[0]->count}}</p>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == "SampleProducts") { ?>
        <div class="col">
            <a href="product" class="text-decoration-none">
                <div class="card border-success h-100">
                    <div class="card-body holo-card text-center text-success">
                        <i class="bi bi-box fs-3"></i>
                        <h6 class="mt-2">Sample Products</h6>
                        <p class="fw-bold mb-0">{{$sp_product_count[0]->count}}</p>
                    </div>
                </div>
            </a>
        </div>
        <?php    } elseif ($val == "PromotionalItems") { ?>
        <div class="col">
            <a href="product" class="text-decoration-none">
                <div class="card border-primary h-100">
                    <div class="card-body holo-card text-center text-primary">
                        <i class="bi bi-gift fs-3"></i>
                        <h6 class="mt-2">Promotional Items</h6>
                        <p class="fw-bold mb-0">{{$pi_product_count[0]->count}}</p>
                    </div>
                </div>
            </a>
        </div>
        <?php    } ?>
        <?php } ?>
    </div>
    <!-- card design end -->
    <div class="row g-4 mb-4 mt-4">
        @foreach ($das_access as $val)
            @if ($val === 'PurchaseBySupplier')
                <div class="col-lg-12">
                    <div class="card shadow-lg rounded-4 border-0">
                        <div class="card-body ">
                            <label class="form-label fw-bold">Supplier</label>
                            <select class="form-select select2 supplier_id">
                                {!! $supplier !!}
                            </select>
                            <div id="po_chart1" class="mt-3"></div>
                        </div>
                    </div>
                </div>

            @elseif ($val === 'PurchaseByProducts')
                <div class="col-lg-12">
                    <div class="card shadow-lg rounded-4 border-0">
                        <div class="card-body ">
                            <label class="form-label fw-bold">Product</label>
                            <select class="form-select select2 raw_product">
                                {!! $raw_product !!}
                            </select>
                            <div id="po_chart2" class="mt-3"></div>
                        </div>
                    </div>
                </div>

            @elseif ($val === 'SalesByCustomer')
                <div class="col-lg-12">
                    <div class="card shadow-lg rounded-4 border-0">
                        <div class="card-body ">
                            <label class="form-label fw-bold">Customer</label>
                            <select class="form-select select2 customer_id">
                                {!! $customer !!}
                            </select>
                            <div id="so_chart1" class="mt-3"></div>
                        </div>
                    </div>
                </div>

            @elseif ($val === 'SalesByProducts')
                <div class="col-lg-12">
                    <div class="card shadow-lg rounded-4 border-0">
                        <div class="card-body ">
                            <label class="form-label fw-bold">Product</label>
                            <select class="form-select select2 fg_product">
                                {!! $fg_product !!}
                            </select>
                            <div id="so_chart2" class="mt-3"></div>
                        </div>
                    </div>
                </div>

            @elseif ($val === 'PurchaseInvoicePiechart')
                <div class="col-lg-12">
                    <div class="card shadow-lg rounded-4 border-0">
                        <div class="card-body ">
                            <div id="po_chart3"></div>
                        </div>
                    </div>
                </div>

            @elseif ($val === 'PurchaseOrderPiechart')
                <div class="col-lg-12">
                    <div class="card shadow-lg rounded-4 border-0">
                        <div class="card-body ">
                            <div id="po_chart4"></div>
                        </div>
                    </div>
                </div>

            @elseif ($val === 'PurchaseByProductsQty')
                <div class="col-lg-12">
                    <div class="card shadow-lg rounded-4 border-0">
                        <div class="card-body ">
                            <label class="form-label fw-bold">Product</label>
                            <select class="form-select select2 raw_product_qty">
                                {!! $raw_product !!}
                            </select>
                            <div id="po_chart5" class="mt-3"></div>
                        </div>
                    </div>
                </div>

            @elseif ($val === 'ProductionProductQty')
                <div class="col-lg-12">
                    <div class="card shadow-lg rounded-4 border-0">
                        <div class="card-body ">
                            <label class="form-label fw-bold">Product</label>
                            <select class="form-select select2 sfg_qty">
                                {!! $sfg_product !!}
                            </select>
                            <div id="prod_chart1" class="mt-3"></div>
                        </div>
                    </div>
                </div>

            @elseif ($val === 'OperationProductQty')
                <div class="col-lg-12">
                    <div class="card shadow-lg rounded-4 border-0">
                        <div class="card-body ">
                            <label class="form-label fw-bold">Product</label>
                            <select class="form-select select2 fg_op_qty">
                                {!! $fg_product !!}
                            </select>
                            <div id="op_chart2" class="mt-3"></div>
                        </div>
                    </div>
                </div>

            @elseif ($val === 'OperationOpenJobcard')
                <div class="col-lg-12">
                    <div class="card shadow-lg rounded-4 border-0">
                        <div class="card-body ">
                            <div id="op_chart1"></div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    <!--chart design end-->
    <div class='row'>
        @foreach ($das_access as $val)
            @if ($val == 'GRNOverdue')
                <div class='col-12 tabletop'>
                    <div class="card shadow-lg rounded-4 border-0">
                        <div class="card-body ">
                            <h4>GRN Overdue</h4>
                            <table id="grnTable" class="table table-striped table-hover table-bordered" style="width:100%">
                                <thead class="table-danger">
                                    <tr>
                                        <th>PO Number</th>
                                        <th>Supplier</th>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Promise date</th>
                                        <th>Overdue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pending_qty as $value)
                                        <tr>
                                            <td>{{ $value->po_number }}</td>
                                            <td>{{ $value->supplier_name }}</td>
                                            <td>{{ $value->product_name }}</td>
                                            <td>{{ round($value->pending_qty, 2) }}</td>
                                            <td>{{ $value->promised_date }}</td>
                                            <td>{{ $value->due }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @elseif ($val == 'PurchaseInvoiceOverdue')
                <div class='col-12 tabletop'>
                    <div class="card shadow-lg rounded-4 border-0">
                        <div class="card-body ">
                            <h4>Purchase Invoice Overdue</h4>
                            <table id="invoiceTable" class="table table-striped table-bordered" style="width:100%">
                                <thead class="table-success">
                                    <tr>
                                        <th>Bill Number</th>
                                        <th>Invoice Date</th>
                                        <th>Supplier</th>
                                        <th>Balance</th>
                                        <th>Due Date</th>
                                        <th>Overdue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payment_pending as $value)
                                        <tr>
                                            <td>{{ $value->bill_number }}</td>
                                            <td>{{ $value->invoice_date }}</td>
                                            <td>{{ $value->supplier_name }}</td>
                                            <td class="text-end">{{ round($value->balance_amount, 2) }}</td>
                                            <td>{{ $value->due_date }}</td>
                                            <td>{{ $value->due }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @elseif ($val == 'DispatchOverdue')
                <div class='col-md-12 tabletop'>
                    <div class="card shadow-lg rounded-4 border-0">
                        <div class="card-body ">
                            <h4>Dispatch Overdue</h4>
                            <table id="DispatchOverdueTbl" class="table table-striped table-bordered" style="width:100%">
                                <thead class="table-primary">
                                    <tr>
                                        <th>SO Number</th>
                                        <th>Customer</th>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Delivery date</th>
                                        <th>Overdue</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php        foreach ($so_pending_qty as $value) {

                        setlocale(LC_MONETARY, 'en_IN');
                                                                                                      ?>
                                    <tr>
                                        <td>{{$value->so_number}}</td>
                                        <td>{{$value->customer_name}}</td>
                                        <td>{{$value->product_name}}</td>
                                        <td>{{round($value->pending_qty, 2)}}</td>
                                        <td>{{$value->delivery_date}}</td>
                                        <td>{{$value->due}}</td>
                                    </tr>
                                    <?php        } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @elseif ($val == 'SalesInvoiceOverdue')
                <div class='col-md-12 tabletop'>
                    <div class="card shadow-lg rounded-4 border-0">
                        <div class="card-body ">
                            <h4>Sales Invoice Overdue</h4>
                            <table id="SalesInvoiceOverdueTbl" class="table table-striped table-bordered" style="width:100%">
                                <thead class="table-warning">
                                    <tr>
                                        <th>Invoice Number</th>
                                        <th>Invoice Date</th>
                                        <th>Customer</th>
                                        <th>Balance</th>
                                        <th>Due Date</th>
                                        <th>Overdue</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php        foreach ($so_payment_pending as $value) { ?>
                                    <tr>
                                        <td>{{$value->invoice_number}}</td>
                                        <td>{{$value->invoice_date}}</td>
                                        <td>{{$value->customer_name}}</td>
                                        <td style='text-align: right;'>{{round($value->balance_amount, 2)}}</td>
                                        <td>{{$value->due_date}}</td>
                                        <td>{{$value->due}}</td>
                                    </tr>
                                    <?php        } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- new sales return overall -->
            @elseif ($val == 'SalesReturnOverall')
                <div class='col-md-12 tabletop'>
                    <div class="card shadow-lg rounded-4 border-0">
                        <div class="card-body ">
                            <h4>Sales Return</h4>
                            <table id="SalesReturnOverallTbl" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr class="table-success">
                                        <th>Rma Reference Number</th>
                                        <th>Return Date</th>
                                        <th>Return Status</th>
                                        <th>Reference Number</th>
                                        <th>Return Source</th>
                                        <th>Total Amount</th>
                                        <th>Customer Name</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php        foreach ($sales_return_overall as $value) { ?>
                                    <tr>
                                        <td>{{$value->rma_ref_no}}</td>
                                        <td>{{$value->return_date}}</td>
                                        <td>{{$value->return_status}}</td>
                                        <td>{{$value->reference_no}}</td>
                                        <td>{{$value->return_source}}</td>
                                        <td>{{$value->total_amount}}</td>
                                        <td>{{$value->customer_name}}</td>
                                    </tr>
                                    <?php        } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End -->

            @elseif ($val == 'OperationJobCardDetails')
                <div class='col-md-12 tabletop'>
                    <div class="card shadow-lg rounded-4 border-0">
                        <div class="card-body ">
                            <h4>Operation Job Card Details</h4>
                            <table id="OperationJobCardDetailsTbl" class="table table-bordered" style="width:100%">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Job Number</th>
                                        <th>Job Date</th>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Process</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php        foreach ($operation_job_data as $value) {

                        setlocale(LC_MONETARY, 'en_IN');
                                                                                                      ?>
                                    <tr>
                                        <td>{{$value->job_no}}</td>
                                        <td>{{$value->job_date}}</td>
                                        <td>{{$value->product_name}}</td>
                                        <td>{{$value->job_adjusted_qty}}</td>
                                        <td>{{$value->bom_process}}</td>
                                        <td>{{$value->job_status}}</td>
                                    </tr>
                                    <?php        } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @elseif ($val == 'ProductionJobCardDetails')
                <div class='col-md-12 tabletop'>
                    <div class="card shadow-lg rounded-4 border-0">
                        <div class="card-body ">
                            <h4>Production Job Card Details</h4>
                            <table id="ProductionJobCardDetailsTbl" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr class="table-danger">
                                        <th>Job Number</th>
                                        <th>Job Date</th>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php        foreach ($production_job_data as $value) {

                        setlocale(LC_MONETARY, 'en_IN');
                                                                                                      ?>
                                    <tr>
                                        <td>{{$value->job_no}}</td>
                                        <td>{{$value->job_date}}</td>
                                        <td>{{$value->product_name}}</td>
                                        <td>{{$value->job_adjusted_qty}}</td>
                                        <td>{{$value->job_status}}</td>
                                    </tr>
                                    <?php        } ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>

            @elseif ($val == 'ROLFGDetails')
                <div class='col-md-12 tabletop'>
                    <div class="card shadow-lg rounded-4 border-0">
                        <div class="card-body ">
                            <h4>ROL FG Details</h4>
                            <table id="ROLFGDetailsTbl" class="table table-sm table-striped table-hover table-bordered"
                                style="width:100%">
                                <thead class="table-warning">
                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Min Stock in FG</th>
                                        <th>Sales Order Qty</th>
                                        <th>Stock</th>
                                        <th>FG Req</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php        foreach ($rol_fg as $value) {

                        setlocale(LC_MONETARY, 'en_IN');
                                                                                                      ?>
                                    <tr>
                                        <td>{{$value->concatenated_product}}</td>
                                        <td>{{$value->category_name}}</td>
                                        <td style='text-align: right;'>{{$value->min_order_qty}}</td>
                                        <td style='text-align: right;'>{{$value->so_qty}}</td>
                                        <td style='text-align: right;'>{{$value->stock}}</td>
                                        <td style='text-align: right;'>{{$value->FG_Req}}</td>
                                    </tr>
                                    <?php        } ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            @elseif ($val == 'ROLRMDetails')
                <div class='col-md-12 tabletop'>
                    <div class="card shadow-lg rounded-4 border-0">
                        <div class="card-body ">
                            <h4>ROL RM Details</h4>
                            <table id="ROLRMDetailsTbl" class="table table-sm table-striped table-hover table-bordered"
                                style="width:100%">
                                <thead>
                                    <tr class="table-primary">
                                        <th>Product</th>
                                        <th>Min. Stock Qty</th>
                                        <th>Stock</th>
                                        <th>Reorder</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php        foreach ($rol_rm as $value) {

                        setlocale(LC_MONETARY, 'en_IN');
                                                                                                      ?>
                                    <tr>
                                        <td>{{$value->concatenated_product}}</td>
                                        <td style='text-align: right;'>{{$value->min_stock_level3}}</td>
                                        <td style='text-align: right;'>{{$value->stock}}</td>
                                        <td style='text-align: right;'>{{$value->order_qty}}</td>
                                    </tr>
                                    <?php        } ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            @elseif ($val == 'ROLPMDetails')
                <div class='col-md-12 tabletop'>
                    <div class="card shadow-lg rounded-4 border-0">
                        <div class="card-body ">
                            <h4>ROL PM Details</h4>
                            <table id="ROLPMDetailsTbl" class="table table-sm table-striped table-hover table-bordered"
                                style="width:100%">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Product</th>
                                        <th>Min. Stock Qty</th>
                                        <th>Stock</th>
                                        <th>Reorder</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php        foreach ($rol_pm as $value) {

                        setlocale(LC_MONETARY, 'en_IN');
                                                                                                      ?>
                                    <tr>
                                        <td>{{$value->concatenated_product}}</td>
                                        <td style='text-align: right;'>{{$value->min_stock_level3}}</td>
                                        <td style='text-align: right;'>{{$value->stock}}</td>
                                        <td style='text-align: right;'>{{$value->order_qty}}</td>
                                    </tr>
                                    <?php        } ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    @push('scripts')

        <script>

            //announcement popup
            $(document).ready(function () {
                $('#announcementModal').modal('show');
            });


            $(document).ready(function () {
                $('#grnTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthChange: true,
                    searching: true,
                    ordering: true,
                    paging: true,
                    info: true,
                    language: {
                        search: "🔍 Search:",
                    }
                });

                $('#invoiceTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthChange: true,
                    searching: true,
                    ordering: true,
                    paging: true,
                    info: true,
                    language: {
                        search: "🔍 Search:",
                    }
                });

                $('#DispatchOverdueTbl').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthChange: true,
                    searching: true,
                    ordering: true,
                    paging: true,
                    info: true,
                    language: {
                        search: "🔍 Search:",
                    }
                });

                $('#SalesInvoiceOverdueTbl').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthChange: true,
                    searching: true,
                    ordering: true,
                    paging: true,
                    info: true,
                    language: {
                        search: "🔍 Search:",
                    }
                });


                $('#SalesReturnOverallTbl').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthChange: true,
                    searching: true,
                    ordering: true,
                    paging: true,
                    info: true,
                    language: {
                        search: "🔍 Search:",
                    }
                });


                $('#OperationJobCardDetailsTbl').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthChange: true,
                    searching: true,
                    ordering: true,
                    paging: true,
                    info: true,
                    language: {
                        search: "🔍 Search:",
                    }
                });


                $('#ProductionJobCardDetailsTbl').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthChange: true,
                    searching: true,
                    ordering: true,
                    paging: true,
                    info: true,
                    language: {
                        search: "🔍 Search:",
                    }
                });

                $('#ROLFGDetailsTbl').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthChange: true,
                    searching: true,
                    ordering: true,
                    paging: true,
                    info: true,
                    language: {
                        search: "🔍 Search:",
                    }
                });


                $('#ROLRMDetailsTbl').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthChange: true,
                    searching: true,
                    ordering: true,
                    paging: true,
                    info: true,
                    language: {
                        search: "🔍 Search:",
                    }
                });


                $('#ROLPMDetailsTbl').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthChange: true,
                    searching: true,
                    ordering: true,
                    paging: true,
                    info: true,
                    language: {
                        search: "🔍 Search:",
                    }
                });


            });

            var column = "{{$column}}";

            // Column Chart for Sales by Customer
            $(".customer_id").change(function () {
                const id = $(this).val();
                if (id) {
                    $.get("{{URL::to('dashboard_data')}}?type=customer&customer_id=" + id, function (data) {
                        Highcharts.chart("so_chart1", {
                            chart: { type: "column" },
                            title: { text: "Sales by Customer" },
                            xAxis: { categories: JSON.parse(column.replace(/&quot;/g, '"')) },
                            yAxis: { title: { text: "Values" } },
                            series: [{
                                name: "Customer",
                                data: data,
                                colorByPoint: true
                            }]
                        });
                    });
                }
            });

            // Donut Chart for Sales by Product
            $(".fg_product").change(function () {
                const id = $(this).val();
                if (id) {
                    $.get("{{URL::to('dashboard_data')}}?type=fg_product&product_id=" + id, function (data) {
                        Highcharts.chart("so_chart2", {
                            chart: { type: "column" },
                            title: { text: "Sales by Product" },
                            plotOptions: {
                                pie: {
                                    innerSize: '50%',
                                    dataLabels: { enabled: true, format: '<b>{point.name}</b>: {point.y}' }
                                }
                            },
                            series: [{
                                name: "Sales",
                                colorByPoint: true,
                                data: data
                            }]
                        });
                    });
                }
            });

            // Bar Chart for Purchase by Product
            $(".raw_product").change(function () {
                const id = $(this).val();
                if (id) {
                    $.get("{{URL::to('dashboard_data')}}?type=raw_product&product_id=" + id, function (data) {
                        Highcharts.chart("po_chart2", {
                            chart: { type: "column" },
                            title: { text: "Purchase by Product" },
                            xAxis: { categories: JSON.parse(column.replace(/&quot;/g, '"')) },
                            yAxis: { title: { text: "Values" } },
                            series: [{
                                name: "Product",
                                data: data,
                                colorByPoint: true
                            }]
                        });
                    });
                }
            });

            // Column Chart for Purchase by Product Quantity
            $(".raw_product_qty").change(function () {
                const id = $(this).val();
                if (id) {
                    $.get("{{URL::to('dashboard_data')}}?type=raw_product_qty&product_id=" + id, function (data) {
                        Highcharts.chart("po_chart5", {
                            chart: { type: "column" },
                            title: { text: "Purchase by Product (Qty)" },
                            xAxis: { categories: JSON.parse(column.replace(/&quot;/g, '"')) },
                            yAxis: { title: { text: `Values in (${data.uom})` } },
                            series: [{
                                name: "Qty",
                                data: data.chart,
                                colorByPoint: true
                            }]
                        });
                    });
                }
            });

            // Stacked Column for Operation Product Qty
            $(".fg_op_qty").change(function () {
                const id = $(this).val();
                if (id) {
                    $.get("{{URL::to('dashboard_data')}}?type=fg_op_qty&product_id=" + id, function (data) {
                        Highcharts.chart("op_chart2", {
                            chart: { type: "column" },
                            title: { text: "Operation Product Qty" },
                            xAxis: { categories: JSON.parse(column.replace(/&quot;/g, '"')) },
                            yAxis: { title: { text: `Values in (${data.uom})` }, stackLabels: { enabled: true } },
                            plotOptions: {
                                column: { stacking: 'normal' }
                            },
                            series: [{
                                name: "Qty",
                                data: data.chart,
                                colorByPoint: true
                            }]
                        });
                    });
                }
            });

            // Area Chart for Production Product Qty
            $(".sfg_qty").change(function () {
                const id = $(this).val();
                if (id) {
                    $.get("{{URL::to('dashboard_data')}}?type=sfg_qty&product_id=" + id, function (data) {
                        Highcharts.chart("prod_chart1", {
                            chart: { type: "area" },
                            title: { text: "Production Product Qty" },
                            xAxis: { categories: JSON.parse(column.replace(/&quot;/g, '"')) },
                            yAxis: { title: { text: `Values in (${data.uom})` } },
                            series: [{
                                name: "Qty",
                                data: data.chart,
                                colorByPoint: true
                            }]
                        });
                    });
                }
            });

            // Donut Chart for Purchase by Supplier
            $(".supplier_id").change(function () {
                const id = $(this).val();
                if (id) {
                    $.get("{{URL::to('dashboard_data')}}?type=supplier&supplier_id=" + id, function (data) {
                        Highcharts.chart("po_chart1", {
                            chart: { type: "column" },
                            title: { text: "Purchase by Supplier" },
                            plotOptions: {
                                pie: {
                                    innerSize: '50%',
                                    dataLabels: { enabled: true, format: '<b>{point.name}</b>: {point.y}' }
                                }
                            },
                            series: [{
                                name: "Value",
                                colorByPoint: true,
                                data: data
                            }]
                        });
                    });
                }
            });
            var inv_data = "{{$overall_invoice_data}}";
            var pro_data = "{{$overall_product_data}}";
            if (pro_data != '') {
                var chart = Highcharts.chart('po_chart2', {

                    title: {
                        text: 'Purchase By Product'
                    },

                    subtitle: {
                        text: ''
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y:.2f}'
                            }
                        }
                    },
                    yAxis: {

                        title: {
                            text: 'Values (in Lakhs)'
                        }
                    },

                    xAxis: {
                        categories: JSON.parse(column.replace(/&quot;/g, '"'))
                    },

                    series: [{
                        type: 'column',
                        colorByPoint: true,
                        data: JSON.parse(pro_data.replace(/&quot;/g, '"')),
                        showInLegend: false
                    }]

                });
            }

            var pro_qty_data = "{{$overall_product_qty_data}}";
            if (pro_qty_data != '') {
                var chart = Highcharts.chart('po_chart5', {

                    title: {
                        text: 'Purchase By Product (QTY)'
                    },

                    subtitle: {
                        text: ''
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y:.2f}'
                            }
                        }
                    },
                    yAxis: {

                        title: {
                            text: 'Values ()'
                        }
                    },

                    xAxis: {
                        categories: JSON.parse(column.replace(/&quot;/g, '"'))
                    },

                    series: [{
                        type: 'column',
                        colorByPoint: true,
                        data: JSON.parse(pro_qty_data.replace(/&quot;/g, '"')),
                        showInLegend: false
                    }]

                });
            }

            if (inv_data != '') {
                var chart = Highcharts.chart('po_chart1', {

                    title: {
                        text: 'Purchase By Supplier'
                    },

                    subtitle: {
                        text: ''
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y:.2f}'
                            }
                        }
                    },

                    yAxis: {

                        title: {
                            text: 'Values (in Lakhs)'
                        }
                    },
                    xAxis: {
                        categories: JSON.parse(column.replace(/&quot;/g, '"'))
                    },

                    series: [{
                        type: 'column',
                        colorByPoint: true,
                        data: JSON.parse(inv_data.replace(/&quot;/g, '"')),
                        showInLegend: false
                    }]

                });

            }

            var inv_data = "{{$so_overall_invoice_data}}";
            var pro_data = "{{$so_overall_product_data}}";
            if (inv_data != '') {
                var chart = Highcharts.chart('so_chart2', {

                    title: {
                        text: 'Sales By Product'
                    },

                    subtitle: {
                        text: ''
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y:.2f}'
                            }
                        }
                    },
                    yAxis: {

                        title: {
                            text: 'Values (in Lakhs)'
                        }
                    },

                    xAxis: {
                        categories: JSON.parse(column.replace(/&quot;/g, '"'))
                    },

                    series: [{
                        type: 'column',
                        colorByPoint: true,
                        data: JSON.parse(pro_data.replace(/&quot;/g, '"')),
                        showInLegend: false
                    }]

                });
            }

            if (pro_data != '') {
                var chart = Highcharts.chart('so_chart1', {
                    title: {
                        text: 'Sales By Customer'
                    },

                    subtitle: {
                        text: ''
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y:.2f}'
                            }
                        }
                    },

                    yAxis: {

                        title: {
                            text: 'Values (in Lakhs)'
                        }
                    },
                    xAxis: {
                        categories: JSON.parse(column.replace(/&quot;/g, '"'))
                    },

                    series: [{
                        type: 'column',
                        colorByPoint: true,
                        data: JSON.parse(inv_data.replace(/&quot;/g, '"')),
                        showInLegend: false
                    }]

                });
            }

            var status_data = "{{$po_invoice_status}}";
            if (status_data != '') {
                Highcharts.chart('po_chart3', {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'column'
                    },
                    title: {
                        text: 'Purchase Invoice For ' + "{{date('M-y')}}"
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.y}</b>'
                    },
                    accessibility: {
                        point: {
                            valueSuffix: '%'
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.y}'
                            }
                        }
                    },
                    series: [{
                        name: 'Status',
                        colorByPoint: true,
                        data: JSON.parse(status_data.replace(/&quot;/g, '"'))
                    }]
                });
            }

            var po_status_data = "{{$po_status}}";
            if (po_status_data != '') {
                Highcharts.chart('po_chart4', {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                    },
                    title: {
                        text: 'Purchase Order For ' + "{{date('M-y')}}"
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.y}</b>'
                    },
                    accessibility: {
                        point: {
                            valueSuffix: '%'
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.y}'
                            }
                        }
                    },
                    series: [{
                        name: 'Status',
                        colorByPoint: true,
                        data: JSON.parse(po_status_data.replace(/&quot;/g, '"'))
                    }]
                });
            }

            var op_data = "{{$operation_process_data}}";
            if (op_data != '') {

                Highcharts.chart('op_chart1', {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                    },
                    title: {
                        text: 'Open Operation Job Card '
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.y}</b>'
                    },
                    accessibility: {
                        point: {
                            valueSuffix: '%'
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.y}'
                            }
                        }
                    },
                    series: [{
                        name: 'Status',
                        colorByPoint: true,
                        data: JSON.parse(op_data.replace(/&quot;/g, '"'))
                    }]
                });
            }

            var overall_operation_qty = "{{$overall_operation_qty}}";
            if (overall_operation_qty != '') {
                var chart = Highcharts.chart('op_chart2', {
                    title: {
                        text: 'Operation Product Qty'
                    },

                    subtitle: {
                        text: ''
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y}'
                            }
                        }
                    },
                    yAxis: {

                        title: {
                            text: 'Values'
                        }
                    },

                    xAxis: {
                        categories: JSON.parse(column.replace(/&quot;/g, '"'))
                    },

                    series: [{
                        type: 'column',
                        colorByPoint: true,
                        data: JSON.parse(overall_operation_qty.replace(/&quot;/g, '"')),
                        showInLegend: false
                    }]

                });
            }


            var overall_production_qty = "{{$overall_production_qty}}";
            if (overall_production_qty != '') {
                var chart = Highcharts.chart('prod_chart1', {

                    title: {
                        text: 'Production Product Qty'
                    },

                    subtitle: {
                        text: ''
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y}'
                            }
                        }
                    },
                    yAxis: {

                        title: {
                            text: 'Values'
                        }
                    },

                    xAxis: {
                        categories: JSON.parse(column.replace(/&quot;/g, '"'))
                    },

                    series: [{
                        type: 'column',
                        colorByPoint: true,
                        data: JSON.parse(overall_production_qty.replace(/&quot;/g, '"')),
                        showInLegend: false
                    }]

                });
            }

            $(document).ready(function () {
                $('.select2').select2({
                    width: '100%' // Ensures dropdown is 
                });
            });

        </script>

    @endpush
@endsection