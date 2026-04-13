@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Asset Product</h3>
  @include('layouts.breadcrumb')


  <form action="" method="post" id="assetproductform" class="assetproductform" data-parsley-validate>
    <input type="hidden" value="" name="savestatus" id="savestatus" />
    <input type="hidden" value="" name="linescheck" id="linescheck">
    <input type="hidden" name="asset_config_id" value="{{ $asset_config_id }}" id="asset_config_id" />
    {{ csrf_field() }}

    <div class="card shadow-lg rounded-4 border-0">
      <div class="card-header bg-primary text-white fw-semibold">
        Asset Product Configuration
      </div>

      <div class="card-body">
        <!-- Top grid -->
        <div class="row g-4">

          <!-- Column 1 -->
          <div class="col-md-4">
            <div class="form-section">
              <div class="row mb-3">
                <label class="col-form-label col-md-4">
                  <span class="required-star">*</span> Product Name
                </label>
                <div class="col-md-8">
                  <input type="hidden" name="subassemblydata" class="subassemblydata" id="subassemblydata">
                  <select style="width:100%" name="product_id" tabindex="1" rows="5"
                    class="form-control product_id select2" required>
                    {!! $product_id !!}
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-form-label col-md-4">
                  <span class="required-star">*</span> Asset Number
                </label>
                <div class="col-md-8">
                  <input type="text" id="asset_number" name="asset_number" class="form-control asset_number"
                    value="{{$assetproductdata['asset_number'] }}" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-form-label col-md-4">Brand Name</label>
                <div class="col-md-8">
                  <select style="width:100%" name="brand_name" rows="5" class="form-control brand_name select2" required>
                    {!! $brand_name !!}
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-form-label col-md-4">
                  <span class="required-star remove">*</span> Quantity
                </label>
                <div class="col-md-8">
                  <input type="text" id="qty" name="qty" class="form-control qty" value="{{$assetproductdata['qty'] }}"
                    required>
                </div>
              </div>

              <div class="fdivold">
                <div class="row mb-3 raw pc">
                  <label class="col-form-label col-md-4">
                    <span class="required-star remove">*</span> UOM
                  </label>
                  <div class="col-md-8">
                    <select style="width:100%" name="uom" rows="5" class="form-control uom select2"
                      data-show-subtext="true" data-live-search="true" required>
                      {!! $uom !!}
                    </select>
                  </div>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-form-label col-md-4">
                  <span class="required-star remove">*</span> Life Period (months)
                </label>
                <div class="col-md-8">
                  <input type="text" id="life_period" name="life_period" class="form-control life_period"
                    value="{{$assetproductdata['life_period'] }}" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-form-label col-md-4">
                  <span class="required-star remove">*</span> PO Number
                </label>
                <div class="col-md-8">
                  <input type="text" id="po_number" name="po_number" class="form-control po_number"
                    value="{{$po_number }}" required>
                </div>
              </div>

              <div class="row mb-1">
                <label class="col-form-label col-md-4">
                  <span class="required-star remove">*</span> Capacity/Range
                </label>
                <div class="col-md-8">
                  <input type="text" id="capacity" name="capacity" class="form-control capacity" value="{{$capacity }}"
                    required>
                </div>
              </div>
            </div>
          </div>

          <!-- Column 2 -->
          <div class="col-md-4">
            <div class="form-section stdivold">

              <div class="row mb-3 stdiv altname">
                <label class="col-form-label col-md-4">Serial Number</label>
                <div class="col-md-8">
                  <input type="text" id="serial_number" tabindex="2" name="serial_number"
                    class="form-control serial_number" value="{{$assetproductdata['serial_number'] }}">
                </div>
              </div>

              <div class="row mb-3 stdiv">
                <label class="col-form-label col-md-4">Warranty From</label>
                <div class="col-md-8">
                  <div class="input-group form_date col-md-12" data-date="" data-date-format="dd MM yyyy"
                    data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input class="form-control start_date warrenty_from" id="warrenty_from" name="warrenty_from"
                      type="text" value="{{ $warrenty_from }}" readonly>
                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                  </div>
                </div>
              </div>

              <div class="row mb-3 stdiv">
                <label class="col-form-label col-md-4">
                  <span class="required-star">*</span> Warranty (months)
                </label>
                <div class="col-md-8">
                  <input type="text" id="warrenty" tabindex="2" name="warrenty" class="form-control warrenty"
                    value="{{$assetproductdata['warrenty'] }}">
                </div>
              </div>

              <div class="row mb-3 stdiv semiedit">
                <label class="col-form-label col-md-4">
                  <span class="required-star sfgreq">*</span> Asset Type
                </label>
                <div class="col-md-8">
                  <select style="width:100%" name="asset_type" rows="5" class="form-control asset_type select2"
                    data-show-subtext="true" data-live-search="true" required>
                    {!! $asset_type !!}
                  </select>
                </div>
              </div>

              <div class="row mb-3 stdiv semiedit">
                <label class="col-form-label col-md-4">
                  <span class="required-star sfgreq">*</span> Asset Category
                </label>
                <div class="col-md-8">
                  <select style="width:100%" name="asset_category" rows="5" class="form-control asset_category select2"
                    id="asset_category" data-show-subtext="true" data-live-search="true" required>
                    {!! $asset_category !!}
                  </select>
                </div>
              </div>

              <div class="row mb-3 stdiv semiedit">
                <label class="col-form-label col-md-4">
                  <span class="required-star sfgreq">*</span> Asset Status
                </label>
                <div class="col-md-8">
                  <select type="text" name="asset_status" id="asset_status" class="form-control asset_status select2"
                    readonly>
                    <option value="">-- Please Select --</option>
                    <option <?php if ($asset_status == "ACTIVE")
    echo "selected"; ?> value="ACTIVE">ACTIVE</option>
                    <option <?php if ($asset_status == "INACTIVE")
    echo "selected"; ?> value="INACTIVE">INACTIVE</option>
                  </select>
                </div>
              </div>

              <div class="row mb-3 stdiv semiedit">
                <label class="col-form-label col-md-4">
                  <span class="required-star sfgreq">*</span> Location
                </label>
                <div class="col-md-8">
                  <select type="text" name="location" id="location" class="form-control location select2" readonly>
                    <option value="">-- Please Select --</option>
                    <option <?php if ($location == "HO")
    echo "selected"; ?> value="HO">HO</option>
                    <option <?php if ($location == "CO")
    echo "selected"; ?> value="CO">CO</option>
                    <option <?php if ($location == "MR")
    echo "selected"; ?> value="MR">MR</option>
                    <option <?php if ($location == "HO Ground Floor")
    echo "selected"; ?> value="HO Ground Floor">HO Ground
                      Floor</option>
                    <option <?php if ($location == "HO First Floor")
    echo "selected"; ?> value="HO First Floor">HO First
                      Floor</option>
                    <option <?php if ($location == "HO Second Floor")
    echo "selected"; ?> value="HO Second Floor">HO Second
                      Floor</option>
                    <option <?php if ($location == "HO Third Floor")
    echo "selected"; ?> value="HO Third Floor">HO Third
                      Floor</option>
                    <option <?php if ($location == "Factory OB Ground Floor")
    echo "selected"; ?>
                      value="Factory OB Ground Floor">Factory OB Ground Floor</option>
                    <option <?php if ($location == "Factory OB First Floor")
    echo "selected"; ?>
                      value="Factory OB First Floor">Factory OB First Floor</option>
                    <option <?php if ($location == "Factory OB Second Floor")
    echo "selected"; ?>
                      value="Factory OB Second Floor">Factory OB Second Floor</option>
                    <option <?php if ($location == "Factory OB Third Floor")
    echo "selected"; ?>
                      value="Factory OB Third Floor">Factory OB Third Floor</option>
                    <option <?php if ($location == "Factory RB Ground Floor")
    echo "selected"; ?>
                      value="Factory RB Ground Floor">Factory RB Ground Floor</option>
                    <option <?php if ($location == "Factory RB First Floor")
    echo "selected"; ?>
                      value="Factory RB First Floor">Factory RB First Floor</option>
                    <option <?php if ($location == "Factory RB Second Floor")
    echo "selected"; ?>
                      value="Factory RB Second Floor">Factory RB Second Floor</option>
                    <option <?php if ($location == "Factory RB Third Floor")
    echo "selected"; ?>
                      value="Factory RB Third Floor">Factory RB Third Floor</option>
                  </select>
                </div>
              </div>

              <div class="row mb-1 stdiv altname">
                <label class="col-form-label col-md-4">Remarks</label>
                <div class="col-md-8">
                  <input type="text" id="remarks" tabindex="2" name="remarks" class="form-control remarks"
                    value="{{$remarks }}">
                </div>
              </div>
            </div>
          </div>

          <!-- Column 3 -->
          <div class="col-md-4">
            <div class="form-section">

              <div class="row mb-3 stdiv semiedit">
                <label class="col-form-label col-md-4">Department</label>
                <div class="col-md-8">
                  <select style="width:100%" name="department" rows="5" class="form-control department select2"
                    data-show-subtext="true" data-live-search="true" required>
                    {!! $department !!}
                  </select>
                </div>
              </div>

              <div class="row mb-3 stdiv semiedit">
                <label class="col-form-label col-md-4">Area</label>
                <div class="col-md-8">
                  <select style="width:100%" name="area" rows="5" class="form-control area select2"
                    data-show-subtext="true" data-live-search="true" required>
                    {!! $area !!}
                  </select>
                </div>
              </div>

              <div class="row mb-3 stdiv semiedit">
                <label class="col-form-label col-md-4">Purchase Date</label>
                <div class="col-md-8">
                  <div class="input-group form_date col-md-12" data-date="" data-date-format="dd MM yyyy"
                    data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input class="form-control start_date purchase_date" id="purchase_date" name="purchase_date"
                      type="text" value="{{ $purchase_date }}" readonly>
                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                  </div>
                </div>
              </div>

              <div class="row mb-3 stdiv semiedit">
                <label class="col-form-label col-md-4">Supplier Name</label>
                <div class="col-md-8">
                  <select style="width:100%" name="supplier_id" rows="5" class="form-control supplier_id select2"
                    data-show-subtext="true" data-live-search="true" required>
                    {!! $supplier_id !!}
                  </select>
                </div>
              </div>

              <div class="row mb-3 stdiv semiedit">
                <label class="col-form-label col-md-4">Installed ON</label>
                <div class="col-md-8">
                  <div class="input-group form_date col-md-12" data-date="" data-date-format="dd MM yyyy"
                    data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input class="form-control start_date replace_date" id="replace_date" name="replace_date" type="text"
                      value="{{ $replace_date }}" readonly>
                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                  </div>
                </div>
              </div>

              <div class="row mb-3 stdiv semiedit">
                <label class="col-form-label col-md-4">Assigned to</label>
                <div class="col-md-8">
                  <select style="width:100%" name="assigned_to" rows="5" class="form-control assigned_to select2"
                    data-show-subtext="true" data-live-search="true" required>
                    {!! $assigned_to !!}
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-form-label col-md-4">
                  <span class="required-star remove">*</span> PO Invoice Number
                </label>
                <div class="col-md-8">
                  <input type="text" id="po_invoice_number" name="po_invoice_number"
                    class="form-control po_invoice_number" value="{{$po_invoice_number }}" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-form-label col-md-4">File Upload</label>
                <div class="col-md-8">
                  <?php 
                    if ($return_url == "assetproductconfig") {
    $link = "download";
  } else {
    $link = '';
  }
  if ($asset_config_id == '') { ?>
                  <input id="" class="GetFileSizeNameAndType" name="choosefile[]" type="file" onchange="example()"
                    multiple />
                  <table id="file_choosen" class="table table-bordered table-sm mb-0">
                    <tbody id="fp"></tbody>
                  </table>
                  <?php } else { ?>
                  <input id="" class="GetFileSizeNameAndType" name="choosefile[]" type="file" onchange="example()"
                    multiple />
                  <table id="file_choosen" class="table table-bordered table-sm mb-2">
                    <tbody id="fp">
                      <?php  if ($attachfile_name != "" || $attachfile_name != NULL) {
      $dataupload = json_decode($attachfile_name); ?>
                      <input type="hidden" value="{{implode(",", $dataupload)}}" name="existing_file" id="existing_file" />
                      <?php  } ?>
                    </tbody>
                  </table>
                  <table id="file_choosen" class="table table-bordered table-sm">
                    <tbody id="fp">
                      <?php  $dataupload = json_decode($attachfile_name);
    if ($dataupload != "" || $dataupload != NULL) { ?>
                      <input type="hidden" value="{{implode(",", $dataupload)}}" name="existing_file" id="existing_file" />
                      <?php    foreach ($dataupload as $k => $v) { ?>
                      <tr>
                        <td>
                          <span class="note">
                            File: <span class="files">
                              <a {{$link}} href="{{URL::to('')}}/Uploads/assets/{{$v}}">{{$v}}</a>
                            </span>
                            &nbsp;<img src="{{URL::to('')}}/images/cancel.png" data-value="{{$v}}" class="delete_user"
                              alt="delete">
                          </span>
                        </td>
                      </tr>
                      <?php    } ?>
                      <?php  } ?>
                    </tbody>
                  </table>
                  <?php } ?>
                </div>
              </div>

            </div>
          </div>

        </div><!-- /row -->

        <!-- IT/Asset Category Blocks -->
        <div class="section-title">System / IT Details</div>
        <div class="row g-4 ast_cat">
          <div class="col-md-4">
            <div class="form-section">
              <div class="row mb-3 stdiv semiedit">
                <label class="col-form-label col-md-4">Workgroup</label>
                <div class="col-md-8">
                  <select type="text" name="work_group" id="work_group" class="form-control work_group select2" readonly>
                    <option value="">-- Please Select --</option>
                    <option <?php if ($work_group == "WORKGROUP")
    echo "selected"; ?> value="WORKGROUP">WORKGROUP</option>
                    <option <?php if ($work_group == "DOMAIN")
    echo "selected"; ?> value="DOMAIN">DOMAIN</option>
                  </select>
                </div>
              </div>
              <div class="row mb-3 stdiv">
                <label class="col-form-label col-md-4">Windows Key (OEM Licence)</label>
                <div class="col-md-8">
                  <input type="text" id="windows_key" name="windows_key" class="form-control windows_key"
                    value="{{$assetproductdata['windows_key'] }}">
                </div>
              </div>
              <div class="row mb-3 stdiv">
                <label class="col-form-label col-md-4">RAM Size</label>
                <div class="col-md-8">
                  <input type="text" id="ram_size" name="ram_size" class="form-control ram_size"
                    value="{{$assetproductdata['ram_size'] }}">
                </div>
              </div>
              <div class="row mb-3 stdiv">
                <label class="col-form-label col-md-4">Anti-Virus</label>
                <div class="col-md-8">
                  <input type="text" id="anti_virus" name="anti_virus" class="form-control anti_virus"
                    value="{{$assetproductdata['anti_virus'] }}">
                </div>
              </div>
              <div class="row mb-1 stdiv">
                <label class="col-form-label col-md-4">MS Office Key</label>
                <div class="col-md-8">
                  <input type="text" id="ms_office_key" name="ms_office_key" class="form-control ms_office_key"
                    value="{{$assetproductdata['ms_office_key'] }}">
                </div>
              </div>
              <div class="row mb-1 stdiv">
                <label class="col-form-label col-md-4">Key Board</label>
                <div class="col-md-8">
                  <input type="text" id="keyboard" name="keyboard" class="form-control keyboard"
                    value="{{$assetproductdata['keyboard'] }}">
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-section">
              <div class="row mb-3 stdiv">
                <label class="col-form-label col-md-4">System Name</label>
                <div class="col-md-8">
                  <input type="text" id="system_name" name="system_name" class="form-control system_name"
                    value="{{$assetproductdata['system_name'] }}">
                </div>
              </div>
              <div class="row mb-3 stdiv">
                <label class="col-form-label col-md-4">Processor Name</label>
                <div class="col-md-8">
                  <input type="text" id="processor_name" name="processor_name" class="form-control processor_name"
                    value="{{$assetproductdata['processor_name'] }}">
                </div>
              </div>
              <div class="row mb-3 stdiv">
                <label class="col-form-label col-md-4">Printer Name</label>
                <div class="col-md-8">
                  <input type="text" id="printer_name" name="printer_name" class="form-control printer_name"
                    value="{{$assetproductdata['printer_name'] }}">
                </div>
              </div>
              <div class="row mb-3 stdiv">
                <label class="col-form-label col-md-4">IP Address</label>
                <div class="col-md-8">
                  <input type="text" id="ip_address" name="ip_address" class="form-control ip_address"
                    value="{{$assetproductdata['ip_address'] }}">
                </div>
              </div>
              <div class="row mb-3 stdiv">
                <label class="col-form-label col-md-4">Additional Software</label>
                <div class="col-md-8">
                  <textarea id="add_software" rows="5" name="add_software" class="form-control add_software"
                    style="height:50px !important;">{{$assetproductdata['add_software'] }}</textarea>
                </div>
              </div>
              <div class="row mb-1 stdiv">
                <label class="col-form-label col-md-4">Network Type</label>
                <div class="col-md-8">
                  <input type="text" id="network_type" name="network_type" class="form-control network_type"
                    value="{{$assetproductdata['network_type'] }}">
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-section">
              <div class="row mb-3 stdiv">
                <label class="col-form-label col-md-4">Operating System</label>
                <div class="col-md-8">
                  <input type="text" id="operating_system" name="operating_system" class="form-control operating_system"
                    value="{{$assetproductdata['operating_system'] }}">
                </div>
              </div>
              <div class="row mb-3 stdiv">
                <label class="col-form-label col-md-4">HDD Size</label>
                <div class="col-md-8">
                  <input type="text" id="hdd_size" name="hdd_size" class="form-control hdd_size"
                    value="{{$assetproductdata['hdd_size'] }}">
                </div>
              </div>
              <div class="row mb-3 stdiv">
                <label class="col-form-label col-md-4">Monitor</label>
                <div class="col-md-8">
                  <input type="text" id="monitor" name="monitor" class="form-control monitor"
                    value="{{$assetproductdata['monitor'] }}">
                </div>
              </div>
              <div class="row mb-3 stdiv">
                <label class="col-form-label col-md-4">MS Office</label>
                <div class="col-md-8">
                  <input type="text" id="ms_office" name="ms_office" class="form-control ms_office"
                    value="{{$assetproductdata['ms_office'] }}">
                </div>
              </div>
              <div class="row mb-3 stdiv">
                <label class="col-form-label col-md-4">Mouse</label>
                <div class="col-md-8">
                  <input type="text" id="mouse" name="mouse" class="form-control mouse"
                    value="{{$assetproductdata['mouse'] }}">
                </div>
              </div>
              <div class="row mb-3 stdiv">
                <label class="col-form-label col-md-4">Anydesk Number</label>
                <div class="col-md-8">
                  <input type="text" id="anydesk_number" name="anydesk_number" class="form-control anydesk_number"
                    value="{{$assetproductdata['anydesk_number'] }}">
                </div>
              </div>
              <div class="row mb-3 stdiv">
                <label class="col-form-label col-md-4">Anydesk Password</label>
                <div class="col-md-8">
                  <input type="text" id="anydesk_pw" name="anydesk_pw" class="form-control anydesk_pw"
                    value="{{$assetproductdata['anydesk_pw'] }}">
                </div>
              </div>
              <div class="row mb-3 stdiv semiedit">
                <label class="col-form-label col-md-4">CD/DVD Drive</label>
                <div class="col-md-8">
                  <select type="text" name="cd_dvd_drive" id="cd_dvd_drive" class="form-control cd_dvd_drive select2"
                    readonly>
                    <option value="">-- Please Select --</option>
                    <option <?php if ($cd_dvd_drive == "YES")
    echo "selected"; ?> value="YES">YES</option>
                    <option <?php if ($cd_dvd_drive == "NO")
    echo "selected"; ?> value="NO">NO</option>
                  </select>
                </div>
              </div>
              <div class="row mb-1 stdiv semiedit">
                <label class="col-form-label col-md-4">Login Type</label>
                <div class="col-md-8">
                  <select type="text" name="login_type" id="login_type" class="form-control login_type select2">
                    <option value="">-- Please Select --</option>
                    <option <?php if ($login_type == "DOMAIN")
    echo "selected"; ?> value="DOMAIN">DOMAIN</option>
                    <option <?php if ($login_type == "LOCAL")
    echo "selected"; ?> value="LOCAL">LOCAL</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

        </div><!-- /row IT -->

        <!-- Domain dates -->
        <div class="section-title">Domain / Subscription</div>
        <div class="row g-3 ast_domain">
          <div class="col-md-6">
            <div class="row mb-3">
              <label class="col-form-label col-md-2">Renewal Date</label>
              <div class="col-md-10">
                <div class="input-group form_date col-md-12" data-date="" data-date-format="dd MM yyyy"
                  data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                  <input class="form-control datepicker renewal_date" id="renewal_date" name="renewal_date" type="text"
                    value="{{ $renewal_date }}" readonly>
                  <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="row mb-3">
              <label class="col-form-label col-md-2">Expiry On</label>
              <div class="col-md-10">
                <div class="input-group form_date col-md-12" data-date="" data-date-format="dd MM yyyy"
                  data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                  <input class="form-control datepicker expiry_on" id="expiry_on" name="expiry_on" type="text"
                    value="{{ $expiry_on }}" readonly>
                  <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="row mt-4 butt">
          <div class="col-12">
            <div class="form-group text-center">
              <input type="hidden" name="submit_type" class="submit_type" id="submit_type">
              <?php if ($return_url == "assetproductconfigcreate") { ?>
              <button type="button" id="save" class="btn btn-success px-4 me-2 saveform" value="SAVE">Save</button>
              <a class="btn btn-danger px-4 me-2" onclick="location.href = '{{URL::to('assetproductconfig')}}'">Cancel</a>
              <?php } else { ?>
              <button type="button" name="submit" class="btn btn-success px-4 me-2 saveform"
                value="APPROVED">Approve</button>
              <button type="button" name="submit" class="btn btn-danger saveform px-4 me-2" value="REJECT">Reject</button>
              <a class="btn btn-outline-danger" onclick="location.href = '{{URL::to('assetproductconfig')}}'">Cancel</a>
              <?php } ?>
            </div>
          </div>
        </div>

      </div>
    </div>
  </form>



@endsection
@push('scripts')

  <script>

    $(document).ready(function () {


      $(document).on('keypress', '#qty,#warrenty,#life_period', function (ev) {
        var regex = new RegExp("^[0-9.]+$");
        var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
        if (regex.test(str)) {
          return true;
        }
        ev.preventDefault();
        return false;
      });

      <?php  if ($return_url != "assetproductconfigcreate") {?>
      $('.row').css('pointer-events', 'none');
      $('.butt').css('pointer-events', 'auto');


      <?php }?>


      $('.asset_number').on('keyup', function () {
        this.value = this.value.toUpperCase();
      });


      var organization = '<?php echo Session::get('organization'); ?>';
      $('.organization_id').val(organization).change();



      $(document).on('click', '.saveform', function () {

        var btnval = $(this).val();
        if (btnval == 'APPLYCHANGES') {
          var savestatus = 'APPLY CHANGES';


        }

        else if (btnval == 'REJECT') {
          var savestatus = 'REJECTED';


        } else if (btnval == 'APPROVED') {
          var savestatus = 'APPROVED';

        } else {

          var savestatus = 'SAVE';

        }

        $('#savestatus').val(savestatus);
        $('.submit_type').val("save");

        var url = "{{ URL::to('assetproductconfigsave') }}";
        var formdata = $('#assetproductform').serialize();
        var form = $('#assetproductform');
        var red_url = "{{ URL::to('assetproductconfig') }}";

        var create_url = "{{ URL::to('assetproductconfig') }}";
        form.parsley().validate();
        var form = $('#assetproductform');
        form.parsley().validate();

        var edit_url = '<?php echo $edit_url; ?>';


        var url = "{{ URL::to('assetproductconfigsave') }}";
        var red_url = "{{ URL::to('assetproductconfig') }}";
        if (btnval != 'APPLYCHANGES') {
          form.parsley().validate();
          var form = $('#assetproductform');

          form.parsley().validate();

          if (form.parsley().isValid()) {

            var $btn = $(this);
            $btn.prop('disabled', true);
            var formdata = $('#assetproductform').serialize();
            var form_data = new FormData(document.getElementById('assetproductform'));
            $.ajax({
              url: url,
              type: "POST",
              data: form_data,
              enctype: 'multipart/form-data',
              processData: false,  // tell jQuery not to process the data
              contentType: false,   // tell jQuery not to set contentType
              async: true,
              xhr: function () {
                var xhr = $.ajaxSettings.xhr();
                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function (event) {
                    var percent = 0;
                    var position = event.loaded || event.position;
                    var total = event.total;
                    if (event.lengthComputable) {
                      percent = Math.ceil(position / total * 100);
                    }
                    //update progressbar

                  }, true);
                }
                return xhr;

              }
            }).done(function (data) {
              var status = data.status;
              var msg = data.message;
              var id = data.id;
              var auto_no = data.auto_no;
              if (btnval != 'SAVE' && btnval != 'APPROVED' && btnval != 'REJECT' && btnval != 'Canceled') {
                showCustomAlert(msg, status);
                window.location.href = create_url;
              } else if (btnval == "APPROVED" || btnval == "REJECT") {
                showCustomAlert(msg, status);

                window.location.href = "{{URL::to('assetproductconfig')}}";

              }
              else {
                showCustomAlert(msg, status);
                window.location.href = red_url;

              }
            });
          }
        }
        else {

        }
      });


      /**********Up/down/left/right arrow navigation start*******/
      $('input,select').keyup(function (e) {
        if (e.which == 39) { // right arrow
          $(this).closest('td').next().find('input,select').focus();

        } else if (e.which == 37) { // left arrow
          $(this).closest('td').prev().find('input,select').focus();

        } else if (e.which == 40) { // down arrow
          $(this).closest('tr').next().find('td:eq(' + $(this).closest('td').index() + ')').find('input,select').focus();

        } else if (e.which == 38) { // up arrow
          $(this).closest('tr').prev().find('td:eq(' + $(this).closest('td').index() + ')').find('input,select').focus();
        }
      });
      /**********Up/down/left/right arrow navigation end *******/


    });

    /*end*/
    var c_set = $('.asset_category option:selected').val();
    //console.log(c_set);
    if (c_set != 16) {
      $('.ast_cat').hide();
    }
    /*** based on leave mode date change start **/
    $(document).on('change', '#asset_category', function () {

      var ast_cat = $('#asset_category').select2('val');

      if (ast_cat == 16 && ast_cat != '') {
        $('.ast_cat').show();

      } else {
        $('.ast_cat').hide();
      }
    });


    var s_set = $('.asset_category option:selected').val();

    if (s_set != 12) {
      $('.ast_domain').hide();
    }

    $(document).on('change', '#asset_category', function () {

      var ast_cat = $('#asset_category').select2('val');

      if (ast_cat == 12 && ast_cat != '') {
        $('.ast_domain').show();

      } else {
        $('.ast_domain').hide();
      }
    });


    function example() {
      $("#file_choosen").css({
        "border-color": "rgb(20, 46, 120)",
        "border-width": "1px",
        "border-style": "solid"
      });
    }

    $(document).on('change', '.GetFileSizeNameAndType', function () {
      var fi = document.getElementById('choosefile'); // GET THE FILE INPUT AS VARIABLE.
      var totalFileSize = 0;
      // VALIDATE OR CHECK IF ANY FILE IS SELECTED.
      if (fi.files.length > 0) {
        // RUN A LOOP TO CHECK EACH SELECTED FILE.
        for (var i = 0; i <= fi.files.length - 1; i++) {
          //ACCESS THE SIZE PROPERTY OF THE ITEM OBJECT IN FILES COLLECTION. IN THIS WAY ALSO GET OTHER PROPERTIES LIKE FILENAME AND FILETYPE
          var fsize = fi.files.item(i).size;
          totalFileSize = totalFileSize + fsize;
          document.getElementById('fp').innerHTML =
            document.getElementById('fp').innerHTML
            +
            '<tr><td><span class="note" ><br /> File:<span class="files">' + fi.files.item(i).name + '</span>&nbsp;<img src="{{URL::to('')}}/images/cancel.png" class="delete_user"></span></td></tr>';
        }
      }
      //document.getElementById('divTotalSize').innerHTML = "Total File(s) Size is <b>" + Math.round(totalFileSize / 1024) + "</b> KB";
      /*file upload validation*/
      $('#choosefile').change(function () {

        var fp = $("#choosefile");

        var lg = fp[0].files.length; // get length

        var items = fp[0].files;

        var fileSize = 0;



        if (lg > 0) {

          for (var i = 0; i < lg; i++) {

            fileSize = fileSize + items[i].size; // get file size

          }

          if (fileSize > 10485760) {

            showCustomAlert('File size must not be more than 10MB', 'warning');

            $('#choosefile').val('');

          }

        }

      });
      /*file upload validation*/
    });


    $(document).on('click', '.delete_user', function () {
      var asset_config_id = '{{$asset_config_id}}';
      if (asset_config_id != '') {
        var existing_value = $('#existing_file').val();
        var delete_value = $(this).attr('data-value');
        removeValue(existing_value, delete_value);
      }
      $(this).parent().parent().remove();
    });

    function removeValue(existing_value, delete_value) {
      list = existing_value.split(',');
      list.splice(list.indexOf(delete_value), 1);
      var values = list.join(',');
      if (values != '')
        $('#existing_file').val(values);
      else
        $('#existing_file').val('');
    }


  </script>

@endpush