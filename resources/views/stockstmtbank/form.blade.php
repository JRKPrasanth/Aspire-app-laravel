@extends('layouts.header')
@section('content')

<style>
    .fonts {
        font-size: 14px;
    }

    .header {
        padding: 10px 16px;
        background: #f1f4ff;
        color: #000;
    }

    .content {
        padding: 16px;
    }

    .sticky {
        position: fixed;
        top: 0;
        width: 100%;
    }

    .sticky+.content {
        padding-top: 102px;
    }
    
</style>


<!--Need help menu popup-->

<div class="modal fade" id="confirm_modal">
    <div class="modal-dialog" style="width:65%;">
        <div class="modal-content">
            <!--Moda Header-->
            <div class="modal-header">
                <h4 class="modal-title-sop"> conformation </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body-sop configdetail">

                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="form-group text-center">
                            <h4>Are You Sure ? It Should Be the Final Save...</h4>

                            <button name="finalsave" type="button" class="btn btn-success finalsave">Save</button>
                            <a type="button" class='btn cancel ' id="closeButton" data-dismiss="modal">Cancel</a>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>

<!--end-->




<h3 class="heads">Stock statement generate</h3>

<div class="ajaxLoading"></div>

<div class="card">
    <div class="card-body card-block ">
        <div class="row">
            <div class="col-md-12">

                <div class="col-lg-12">

                    <form action="{{ url('stockstatementgenerate') }}" method="get" id="searchForm">

                        <div class="col-md-4">
                            <div class="form-group row">
                                <label for="inputIsValid" class="form-control-label col-md-4"
                                    style="text-align: end;">Month</label>
                                <div class="col-md-6">
                                    <div class="input-group form_date " data-date="" data-link-format="yyyy-mm">
                                        <input class="form-control select_month  " id="select_month" name="select_month"
                                            type="month" value="" style="border-radius: 5px;" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2" style="margin-top: -9px;">
                            <a>
                                <button type="submit" class="btn search search" id="searchButton">search</button>
                            </a>
                        </div>
                    </form>
                </div>

                <form method="POST" action="" id="stock_form" class="stock_form" data-parsley-validate>

                    <div class="col-md-12 header" id="myHeader">
                        <div class="col-md-2">
                            <label for="inputIsValid" class="form-control-label fonts"><b>Particulars</b></label>
                        </div>

                        <div class="col-md-2">
                            <label for="inputIsValid" class="form-control-label fonts"><b>Stk On</b></label>
                        </div>


                        <div class="col-md-2">
                            <label for="inputIsValid" class="form-control-label fonts"><b>Stk On For Bank</b></label>
                            <input class="form-control" type="hidden" id="hidden_select_month" name="select_date"
                                value="{{ request('select_month') }}">
                        </div>


                        <div class="col-md-1">
                            <label for="inputIsValid" class="form-control-label fonts"><b>Unit weight</b></label>
                        </div>

                        <div class="col-md-1">
                            <label for="inputIsValid" class="form-control-label fonts"><b>Market /Purc Rate</b></label>
                        </div>

                        <div class="col-md-2">
                            <label for="inputIsValid" class="form-control-label fonts"><b>Total Value</b></label>
                        </div>

                        <div class="col-md-2">
                            <label for="inputIsValid" class="form-control-label fonts"><b>Total Value For
                                    Bank</b></label>
                        </div>

                    </div>

                    <!-- FINISHED GOODS -->


                    <div class="col-md-12">
                        <h6><b>FINISHED GOODS</b></h6>
                        <div class="col-md-2">
                            <label for="inputIsValid" class="form-control-label">Total</label>
                        </div>

                        <div class="col-md-2">
                            <h6 id="total_qoh_fg" style="text-align: center;"><b>{{ $fg_cosmetic_qoh[0]->qoh +
                                    $fg_sasayur_qoh[0]->qoh + $fg_sassid_qoh[0]->qoh + $fg_sidha_qoh[0]->qoh}}</b></h6>
                        </div>


                        <div class="col-md-2">
                            <h6 style="text-align: center;" id="total_fg_qty"><b>0</b></h6>
                        </div>


                        <div class="col-md-1">
                            <label for="inputIsValid" class="form-control-label"></label>
                        </div>

                        <div class="col-md-1">
                            <label for="inputIsValid" class="form-control-label"></label>
                        </div>

                        <div class="col-md-2">
                            <h6 id="fg_total" style="text-align: center;"><b>{{ $fg_cosmetic_total[0]->total +
                                    $fg_sasayur_total[0]->total + $fg_sassid_total[0]->total + $fg_sidha_total[0]->total
                                    }}</b></h6>
                        </div>

                        <div class="col-md-2">
                            <h6 style="text-align: center;" id="total_fg_value"><b>0</b></h6>
                        </div>

                    </div>



                    <div class="col-md-12">
                        <div class="col-md-2">
                            <label for="inputIsValid" class="form-control-label">COSMETICS</label>
                            <input type="hidden" name="cosmetics" type="hidden" class="form-control" id="cosmetics"
                                value="COSMETICS">
                        </div>

                        <div class="col-md-2">
                            <input name="stk_cos" type="text" class="form-control" id="stk_cos"
                                value="{{$fg_cosmetic_qoh[0]->qoh}}" readonly>
                        </div>


                        <div class="col-md-2">
                            <input name="stkbank_cos" type="text" class="form-control" id="stkbank_cos"
                                value="{{$fg_cosmetic_stk1}}">
                        </div>


                        <div class="col-md-1">
                            <input name="unit_cos" type="text" class="form-control" id="unit_cos" value="Nos" readonly>
                        </div>

                        <div class="col-md-1">
                            <input name="marate_cos" type="text" class="form-control" id="marate_cos" value="" readonly>
                        </div>

                        <div class="col-md-2">
                            <input name="total_cos" type="text" class="form-control" id="total_cos"
                                value="{{$fg_cosmetic_total[0]->total}}" readonly>
                        </div>

                        <div class="col-md-2">
                            <input name="totalbank_cos" type="text" class="form-control" id="totalbank_cos"
                                value="{{$fg_cosmetic_gt1}}">
                        </div>

                    </div>

                    <div class="col-md-12" style="margin-top:15px;">

                        <div class="col-md-2">
                            <label for="inputIsValid" class="form-control-label">SASTRIC AYURVEDA</label>
                            <input type="hidden" name="sastric_ayurvedha" type="hidden" class="form-control"
                                id="sastric_ayurvedha" value="SASTRIC AYURVEDA">
                        </div>

                        <div class="col-md-2">
                            <input name="stk_sacayv" type="text" class="form-control" id="stk_sacayv"
                                value="{{$fg_sasayur_qoh[0]->qoh}}" readonly>
                        </div>


                        <div class="col-md-2">
                            <input name="stkbank_sacayv" type="text" class="form-control" id="stkbank_sacayv"
                                value="{{$fg_sas_stk1}}">
                        </div>


                        <div class="col-md-1">
                            <input name="unit_sacayv" type="text" class="form-control" id="unit_sacayv" value="Nos"
                                readonly>
                        </div>

                        <div class="col-md-1">
                            <input name="marate_sacayv" type="text" class="form-control" id="marate_sacayv" value=""
                                readonly>
                        </div>

                        <div class="col-md-2">
                            <input name="total_sacayv" type="text" class="form-control" id="total_sacayv"
                                value="{{$fg_sasayur_total[0]->total}}" readonly>
                        </div>

                        <div class="col-md-2">
                            <input name="totalbank_sacayv" type="text" class="form-control" id="totalbank_sacayv"
                                value="{{$fg_sas_gt1}}">
                        </div>

                    </div>


                    <div class="col-md-12" style="margin-top:15px;">

                        <div class="col-md-2">
                            <label for="inputIsValid" class="form-control-label">SASTRIC SIDDHA</label>
                            <input type="hidden" name="sastric_siddha" type="hidden" class="form-control"
                                id="sastric_siddha" value="SASTRIC SIDDHA">
                        </div>

                        <div class="col-md-2">
                            <input name="stk_sacsid" type="text" class="form-control" id="stk_sacsid"
                                value="{{$fg_sassid_qoh[0]->qoh}}" readonly>
                        </div>


                        <div class="col-md-2">
                            <input name="stkbank_sacsid" type="text" class="form-control" id="stkbank_sacsid"
                                value="{{$fg_sasid_stk1}}">
                        </div>


                        <div class="col-md-1">
                            <input name="unit_sacsid" type="text" class="form-control" id="unit_sacsid" value="Nos"
                                readonly>
                        </div>

                        <div class="col-md-1">
                            <input name="marate_sacsid" type="text" class="form-control" id="marate_sacsid" value=""
                                readonly>
                        </div>

                        <div class="col-md-2">
                            <input name="total_sacsid" type="text" class="form-control" id="total_sacsid"
                                value="{{$fg_sassid_total[0]->total}}" readonly>
                        </div>

                        <div class="col-md-2">
                            <input name="totalbank_sacsid" type="text" class="form-control" id="totalbank_sacsid"
                                value="{{$fg_sasid_gt1}}">
                        </div>

                    </div>


                    <div class="col-md-12" style="margin-top:15px;">

                        <div class="col-md-2">
                            <label for="inputIsValid" class="form-control-label">SIDDHA</label>
                            <input type="hidden" name="siddha" type="hidden" class="form-control" id="siddha"
                                value="SIDDHA">
                        </div>

                        <div class="col-md-2">
                            <input name="stk_sida" type="text" class="form-control" id="stk_sida"
                                value="{{$fg_sidha_qoh[0]->qoh}}" readonly>
                        </div>


                        <div class="col-md-2">
                            <input name="stkbank_sida" type="text" class="form-control" id="stkbank_sida"
                                value="{{$fg_sidha_stk1}}">
                        </div>


                        <div class="col-md-1">
                            <input name="unit_sida" type="text" class="form-control" id="unit_sida" value="Nos"
                                readonly>
                        </div>

                        <div class="col-md-1">
                            <input name="marate_sida" type="text" class="form-control" id="marate_sida" value=""
                                readonly>
                        </div>

                        <div class="col-md-2">
                            <input name="total_sida" type="text" class="form-control" id="total_sida"
                                value="{{$fg_sidha_total[0]->total}}">
                        </div>

                        <div class="col-md-2">
                            <input name="totalbank_sida" type="text" class="form-control" id="totalbank_sida"
                                value="{{$fg_sidha_gt1}}">
                        </div>

                    </div>

                    <!-- finished goods end -->


                    <!-- PACKING MATERIALS -->


                    <div class="col-md-12" style="margin-top:15px;">
                        <h6><b>PACKING MATERIALS</b></h6>
                        <div class="col-md-2">
                            <label for="inputIsValid" class="form-control-label">Total</label>
                        </div>

                        <div class="col-md-2">
                            <h6 id="total_qoh_pm" style="text-align: center;"><b>{{ $pm_carton_qoh[0]->qoh +
                                    $pm_container_qoh[0]->qoh+ $pm_insert_qoh[0]->qoh + $pm_label_qoh[0]->qoh +
                                    $pm_otheritem_qoh[0]->qoh + $pm_sticker_qoh[0]->qoh}}</b></h6>
                        </div>

                        <div class="col-md-2">
                            <h6 style="text-align: center;" id="total_pm_qty"><b>0</b></h6>
                        </div>


                        <div class="col-md-1">
                            <label for="inputIsValid" class="form-control-label"></label>
                        </div>

                        <div class="col-md-1">
                            <label for="inputIsValid" class="form-control-label"></label>
                        </div>

                        <div class="col-md-2">
                            <h6 id="pm_total" style="text-align: center;"><b>{{ $pm_sticker_total[0]->total +
                                    $pm_otheritem_total[0]->total + $pm_label_total[0]->total +
                                    $pm_insert_total[0]->total + $pm_container_total[0]->total +
                                    $pm_carton_total[0]->total}}</b></h6>
                        </div>

                        <div class="col-md-2">
                            <h6 style="text-align: center;" id="total_pm_value"><b>0</b></h6>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <label for="inputIsValid" class="form-control-label">CARTON</label>
                        <input type="hidden" name="carton" type="hidden" class="form-control" id="carton"
                            value="CARTON">
                    </div>

                    <div class="col-md-2">
                        <input name="stk_carton" type="text" class="form-control" id="stk_carton"
                            value="{{$pm_carton_qoh[0]->qoh}}" readonly>
                    </div>


                    <div class="col-md-2">
                        <input name="stkbank_carton" type="text" class="form-control" id="stkbank_carton"
                            value="{{$pm_carton_stk1}}">
                    </div>


                    <div class="col-md-1">
                        <input name="unit_carton" type="text" class="form-control" id="unit_carton" value="Nos"
                            readonly>
                    </div>

                    <div class="col-md-1">
                        <input name="marate_carton" type="text" class="form-control" id="marate_carton" value=""
                            readonly>
                    </div>

                    <div class="col-md-2">
                        <input name="total_carton" type="text" class="form-control" id="total_carton"
                            value="{{$pm_carton_total[0]->total}}" readonly>
                    </div>

                    <div class="col-md-2">
                        <input name="totalbank_carton" type="text" class="form-control" id="totalbank_carton"
                            value="{{$pm_carton_gt1}}">
                    </div>

            </div>


            <div class="col-md-12" style="margin-top:15px;">

                <div class="col-md-2">
                    <label for="inputIsValid" class="form-control-label">CONTAINERS</label>
                    <input type="hidden" name="containers" type="hidden" class="form-control" id="containers"
                        value="CONTAINERS">
                </div>

                <div class="col-md-2">
                    <input name="stk_container" type="text" class="form-control" id="stk_container"
                        value="{{$pm_container_qoh[0]->qoh}}" readonly>
                </div>


                <div class="col-md-2">
                    <input name="stkbank_container" type="text" class="form-control" id="stkbank_container"
                        value="{{$pm_container_stk1}}">
                </div>


                <div class="col-md-1">
                    <input name="unit_container" type="text" class="form-control" id="unit_container" value="Nos">
                </div>

                <div class="col-md-1">
                    <input name="marate_container" type="text" class="form-control" id="marate_container" value=""
                        readonly>
                </div>

                <div class="col-md-2">
                    <input name="total_container" type="text" class="form-control" id="total_container"
                        value="{{$pm_container_total[0]->total}}">
                </div>

                <div class="col-md-2">
                    <input name="totalbank_container" type="text" class="form-control" id="totalbank_container"
                        value="{{$pm_container_gt1}}">
                </div>

            </div>

            <div class="col-md-12" style="margin-top:15px;">

                <div class="col-md-2">
                    <label for="inputIsValid" class="form-control-label">INSERT</label>
                    <input type="hidden" name="insert" type="hidden" class="form-control" id="insert" value="INSERT">
                </div>

                <div class="col-md-2">
                    <input name="stk_insert" type="text" class="form-control" id="stk_insert"
                        value="{{$pm_insert_qoh[0]->qoh}}" readonly>
                </div>


                <div class="col-md-2">
                    <input name="stkbank_insert" type="text" class="form-control" id="stkbank_insert"
                        value="{{$pm_insert_stk1}}">
                </div>


                <div class="col-md-1">
                    <input name="unit_insert" type="text" class="form-control" id="unit_insert" value="Nos" readonly>
                </div>

                <div class="col-md-1">
                    <input name="marate_insert" type="text" class="form-control" id="marate_insert" value="" readonly>
                </div>

                <div class="col-md-2">
                    <input name="total_insert" type="text" class="form-control" id="total_insert"
                        value="{{$pm_insert_total[0]->total}}" readonly>
                </div>

                <div class="col-md-2">
                    <input name="totalbank_insert" type="text" class="form-control" id="totalbank_insert"
                        value="{{$pm_insert_gt1}}">
                </div>

            </div>

            <div class="col-md-12" style="margin-top:15px;">

                <div class="col-md-2">
                    <label for="inputIsValid" class="form-control-label">LABELS</label>
                    <input type="hidden" name="labels" type="hidden" class="form-control" id="labels" value="LABELS">
                </div>

                <div class="col-md-2">
                    <input name="stk_label" type="text" class="form-control" id="stk_label"
                        value="{{$pm_label_qoh[0]->qoh}}" readonly>
                </div>


                <div class="col-md-2">
                    <input name="stkbank_label" type="text" class="form-control" id="stkbank_label"
                        value="{{$pm_label_stk1}}">
                </div>


                <div class="col-md-1">
                    <input name="unit_label" type="text" class="form-control" id="unit_label" value="Nos" readonly>
                </div>

                <div class="col-md-1">
                    <input name="marate_label" type="text" class="form-control" id="marate_label" value="" readonly>
                </div>

                <div class="col-md-2">
                    <input name="total_label" type="text" class="form-control" id="total_label"
                        value="{{$pm_label_total[0]->total}}" readonly>
                </div>

                <div class="col-md-2">
                    <input name="totalbank_label" type="text" class="form-control" id="totalbank_label"
                        value="{{$pm_label_gt1}}">
                </div>

            </div>

            <div class="col-md-12" style="margin-top:15px;">

                <div class="col-md-2">
                    <label for="inputIsValid" class="form-control-oitem">Other Items</label>
                    <input type="hidden" name="oitem" type="hidden" class="form-control" id="lebel" value="Other Items">
                </div>

                <div class="col-md-2">
                    <input name="stk_oitem" type="text" class="form-control" id="stk_oitem"
                        value="{{$pm_otheritem_qoh[0]->qoh}}" readonly>
                </div>


                <div class="col-md-2">
                    <input name="stkbank_oitem" type="text" class="form-control" id="stkbank_oitem"
                        value="{{$pm_oitem_stk1}}">
                </div>


                <div class="col-md-1">
                    <input name="unit_oitem" type="text" class="form-control" id="unit_oitem" value="Nos" readonly>
                </div>

                <div class="col-md-1">
                    <input name="marate_oitem" type="text" class="form-control" id="marate_oitem" value="" readonly>
                </div>

                <div class="col-md-2">
                    <input name="total_oitem" type="text" class="form-control" id="total_oitem"
                        value="{{$pm_otheritem_total[0]->total}}" readonly>
                </div>

                <div class="col-md-2">
                    <input name="totalbank_oitem" type="text" class="form-control" id="totalbank_oitem"
                        value="{{$pm_oitem_gt1}}">
                </div>

            </div>

            <div class="col-md-12" style="margin-top:15px;">

                <div class="col-md-2">
                    <label for="inputIsValid" class="form-control-sticker">STICKER</label>
                    <input type="hidden" name="sticker" type="hidden" class="form-control" id="lebel" value="STICKER">
                </div>

                <div class="col-md-2">
                    <input name="stk_sticker" type="text" class="form-control" id="stk_sticker"
                        value="{{$pm_sticker_qoh[0]->qoh}}" readonly>
                </div>


                <div class="col-md-2">
                    <input name="stkbank_sticker" type="text" class="form-control" id="stkbank_sticker"
                        value="{{$pm_sticker_stk1}}">
                </div>


                <div class="col-md-1">
                    <input name="unit_sticker" type="text" class="form-control" id="unit_sticker" value="Nos" readonly>
                </div>

                <div class="col-md-1">
                    <input name="marate_sticker" type="text" class="form-control" id="marate_sticker" value="" readonly>
                </div>

                <div class="col-md-2">
                    <input name="total_sticker" type="text" class="form-control" id="total_sticker"
                        value="{{$pm_sticker_total[0]->total}}" readonly>
                </div>

                <div class="col-md-2">
                    <input name="totalbank_sticker" type="text" class="form-control" id="totalbank_sticker"
                        value="{{$pm_sticker_gt1}}">
                </div>
            </div>

            <!-- PACKING MATERIALS END  -->


            <!-- RAW MATERIALS -->
            <div class="col-md-12" style="margin-top:15px;">
                <h6><b>RAW MATERIALS</b></h6>
                <div class="col-md-2">
                    <label for="inputIsValid" class="form-control-label">Total</label>
                </div>

                <div class="col-md-2">
                    <h6 id="total_qoh_rm" style="text-align: center;"><b>{{ $rm_chemical_qoh[0]->qoh +
                            $rm_lab_qoh[0]->qoh + $rm_leaf_qoh[0]->qoh + $rm_oil_qoh[0]->qoh + $rm_raw_qoh[0]->qoh
                            }}</b></h6>
                </div>


                <div class="col-md-2">
                    <h6 style="text-align: center;" id="total_rm_qty"><b>0</b></h6>
                </div>


                <div class="col-md-1">
                    <label for="inputIsValid" class="form-control-label"></label>
                </div>

                <div class="col-md-1">
                    <label for="inputIsValid" class="form-control-label"></label>
                </div>

                <div class="col-md-2">
                    <h6 id="rm_total" style="text-align: center;"><b>{{ $rm_raw_total[0]->total +
                            $rm_oil_total[0]->total + $rm_leaf_total[0]->total + $rm_lab_total[0]->total +
                            $rm_chemical_total[0]->total}}</b></h6>
                </div>

                <div class="col-md-2">
                    <h6 style="text-align: center;" id="total_rm_value"><b>0</b></h6>
                </div>
            </div>
            <div class="col-md-2">
                <label for="inputIsValid" class="form-control-label">CHEMICALS</label>
                <input type="hidden" name="chemicals" type="hidden" class="form-control" id="chemicals"
                    value="CHEMICALS">
            </div>

            <div class="col-md-2">
                <input name="stk_chemical" type="text" class="form-control" id="stk_chemical"
                    value="{{$rm_chemical_qoh[0]->qoh}}" readonly>
            </div>


            <div class="col-md-2">
                <input name="stkbank_chemical" type="text" class="form-control" id="stkbank_chemical"
                    value="{{$rm_chemical_stk1}}">
            </div>


            <div class="col-md-1">
                <input name="unit_chemical" type="text" class="form-control" id="unit_chemical" value="Kg/Ltr" readonly>
            </div>

            <div class="col-md-1">
                <input name="marate_chemical" type="text" class="form-control" id="marate_chemical" value="" readonly>
            </div>

            <div class="col-md-2">
                <input name="total_chemical" type="text" class="form-control" id="total_chemical"
                    value="{{$rm_chemical_total[0]->total}}" readonly>
            </div>

            <div class="col-md-2">
                <input name="totalbank_chemical" type="text" class="form-control" id="totalbank_chemical"
                    value="{{$rm_chemical_gt1}}">
            </div>

        </div>


        <div class="col-md-12" style="margin-top:15px;">
            <div class="col-md-2">
                <label for="inputIsValid" class="form-control-label">LAB</label>
                <input type="hidden" name="lab" type="hidden" class="form-control" id="lab" value="LAB">
            </div>

            <div class="col-md-2">
                <input name="stk_lab" type="text" class="form-control" id="stk_lab" value="{{$rm_lab_qoh[0]->qoh}}"
                    readonly>
            </div>


            <div class="col-md-2">
                <input name="stkbank_lab" type="text" class="form-control" id="stkbank_lab" value="{{$rm_lab_stk1}}">
            </div>


            <div class="col-md-1">
                <input name="unit_lab" type="text" class="form-control" id="unit_lab" value="Kg/Ltr" readonly>
            </div>

            <div class="col-md-1">
                <input name="marate_lab" type="text" class="form-control" id="marate_lab" value="" readonly>
            </div>

            <div class="col-md-2">
                <input name="total_lab" type="text" class="form-control" id="total_lab"
                    value="{{$rm_lab_total[0]->total}}" readonly>
            </div>

            <div class="col-md-2">
                <input name="totalbank_lab" type="text" class="form-control" id="totalbank_lab" value="{{$rm_lab_gt1}}">
            </div>

        </div>


        <div class="col-md-12" style="margin-top:15px;">
            <div class="col-md-2">
                <label for="inputIsValid" class="form-control-label">LEAF</label>
                <input type="hidden" name="leaf" type="hidden" class="form-control" id="leaf" value="LEAF">
            </div>

            <div class="col-md-2">
                <input name="stk_leaf" type="text" class="form-control" id="stk_leaf" value="{{$rm_leaf_qoh[0]->qoh}}"
                    readonly>
            </div>


            <div class="col-md-2">
                <input name="stkbank_leaf" type="text" class="form-control" id="stkbank_leaf" value="{{$rm_leaf_stk1}}">
            </div>


            <div class="col-md-1">
                <input name="unit_leaf" type="text" class="form-control" id="unit_leaf" value="Kg/Ltr" readonly>
            </div>

            <div class="col-md-1">
                <input name="marate_leaf" type="text" class="form-control" id="marate_leaf" value="" readonly>
            </div>

            <div class="col-md-2">
                <input name="total_leaf" type="text" class="form-control" id="total_leaf"
                    value="{{$rm_leaf_total[0]->total}}" readonly>
            </div>

            <div class="col-md-2">
                <input name="totalbank_leaf" type="text" class="form-control" id="totalbank_leaf"
                    value="{{$rm_leaf_gt1}}">
            </div>

        </div>

        <div class="col-md-12" style="margin-top:15px;">
            <div class="col-md-2">
                <label for="inputIsValid" class="form-control-label">OIL</label>
                <input type="hidden" name="oil" type="hidden" class="form-control" id="oil" value="OIL">
            </div>

            <div class="col-md-2">
                <input name="stk_oil" type="text" class="form-control" id="stk_oil" value="{{$rm_oil_qoh[0]->qoh}}"
                    readonly>
            </div>


            <div class="col-md-2">
                <input name="stkbank_oil" type="text" class="form-control" id="stkbank_oil" value="{{$rm_oil_stk1}}">
            </div>


            <div class="col-md-1">
                <input name="unit_oil" type="text" class="form-control" id="unit_oil" value="Kg/Ltr" readonly>
            </div>

            <div class="col-md-1">
                <input name="marate_oil" type="text" class="form-control" id="marate_oil" value="" readonly>
            </div>

            <div class="col-md-2">
                <input name="total_oil" type="text" class="form-control" id="total_oil"
                    value="{{$rm_oil_total[0]->total}}" readonly>
            </div>

            <div class="col-md-2">
                <input name="totalbank_oil" type="text" class="form-control" id="totalbank_oil" value="{{$rm_oil_gt1}}">
            </div>

        </div>

        <div class="col-md-12" style="margin-top:15px;">
            <div class="col-md-2">
                <label for="inputIsValid" class="form-control-label">RAW</label>
                <input type="hidden" name="raw" type="hidden" class="form-control" id="raw" value="RAW">
            </div>

            <div class="col-md-2">
                <input name="stk_raw" type="text" class="form-control" id="stk_raw" value="{{$rm_raw_qoh[0]->qoh}}"
                    readonly>
            </div>


            <div class="col-md-2">
                <input name="stkbank_raw" type="text" class="form-control" id="stkbank_raw" value="{{$rm_raw_stk1}}">
            </div>


            <div class="col-md-1">
                <input name="unit_raw" type="text" class="form-control" id="unit_raw" value="Kg/Ltr" readonly>
            </div>

            <div class="col-md-1">
                <input name="marate_raw" type="text" class="form-control" id="marate_raw" value="" readonly>
            </div>

            <div class="col-md-2">
                <input name="total_raw" type="text" class="form-control" id="total_raw"
                    value="{{$rm_raw_total[0]->total}}" readonly>
            </div>

            <div class="col-md-2">
                <input name="totalbank_raw" type="text" class="form-control" id="totalbank_raw" value="{{$rm_raw_gt1}}">
            </div>

        </div>
        <!-- RAW MATERIALS END-->


        <!-- SEMI FINISHED GOODS-->

        <div class="col-md-12" style="margin-top:15px;">
            <div class="col-md-12">
                <h6><b>SEMI FINISHED GOODS</b></h6>
                <div class="col-md-2">
                    <label for="inputIsValid" class="form-control-label">Total</label>
                </div>

                <div class="col-md-2">
                    <h6 id="total_qoh_sfg" style="text-align: center;"><b>{{ $sfg_cos_qoh[0]->qoh +
                            $sfg_sasauy_qoh[0]->qoh + $sfg_sasida_qoh[0]->qoh + $sfg_sidha_qoh[0]->qoh +
                            $access_item_qoh[0]->qoh + $pro_godown_qoh[0]->qoh}}</b></h6>
                </div>


                <div class="col-md-2">
                    <h6 style="text-align: center;" id="total_sfg_qty"><b>0</b></h6>
                </div>


                <div class="col-md-1">
                    <label for="inputIsValid" class="form-control-label"></label>
                </div>

                <div class="col-md-1">
                    <label for="inputIsValid" class="form-control-label"></label>
                </div>

                <div class="col-md-2">
                    <h6 id="sfg_total" style="text-align: center;"><b>{{ $sfg_sidha_total[0]->total +
                            $sfg_sasida_total[0]->total + $sfg_sasauy_total[0]->total + $sfg_cos_total[0]->total +
                            $access_item_total[0]->total + $pro_godown_total[0]->total}}</b></h6>
                </div>

                <div class="col-md-2">
                    <h6 style="text-align: center;" id="total_sfg_value"><b>0</b></h6>
                </div>

            </div>
            <div class="col-md-2">
                <label for="inputIsValid" class="form-control-label">COSMETICS</label>
                <input type="hidden" name="semi_cosmetics" type="hidden" class="form-control" id="semi_cosmetics"
                    value="SEMI-COSMETICS">
            </div>

            <div class="col-md-2">
                <input name="stk_sfg_cos" type="text" class="form-control" id="stk_sfg_cos"
                    value="{{$sfg_cos_qoh[0]->qoh}}" readonly>
            </div>


            <div class="col-md-2">
                <input name="stkbank_sfg_cos" type="text" class="form-control" id="stkbank_sfg_cos"
                    value="{{$sfg_cos_stk1}}">
            </div>


            <div class="col-md-1">
                <input name="unit_sfg_cos" type="text" class="form-control" id="unit_sfg_cos" value="Nos" readonly>
            </div>

            <div class="col-md-1">
                <input name="marate_sfg_cos" type="text" class="form-control" id="marate_sfg_cos" value="" readonly>
            </div>

            <div class="col-md-2">
                <input name="total_sfg_cos" type="text" class="form-control" id="total_sfg_cos"
                    value="{{$sfg_cos_total[0]->total}}" readonly>
            </div>

            <div class="col-md-2">
                <input name="totalbank_sfg_cos" type="text" class="form-control" id="totalbank_sfg_cos"
                    value="{{$sfg_cos_gt1}}">
            </div>

        </div>

        <div class="col-md-12" style="margin-top:15px;">
            <div class="col-md-2">
                <label for="inputIsValid" class="form-control-label">SASTRIC AYURVEDA</label>
                <input type="hidden" name="semi_sasay" type="hidden" class="form-control" id="semi_sasay"
                    value="SEMI-SASTRIC AYURVEDA">
            </div>

            <div class="col-md-2">
                <input name="stk_sfg_sacayu" type="text" class="form-control" id="stk_sfg_sacayu"
                    value="{{$sfg_sasauy_qoh[0]->qoh}}" readonly>
            </div>


            <div class="col-md-2">
                <input name="stkbank_sfg_sacayu" type="text" class="form-control" id="stkbank_sfg_sacayu"
                    value="{{$sfg_sas_stk1}}">
            </div>


            <div class="col-md-1">
                <input name="unit_sfg_sacayu" type="text" class="form-control" id="unit_sfg_sacayu" value="Nos"
                    readonly>
            </div>

            <div class="col-md-1">
                <input name="marate_sfg_sacayu" type="text" class="form-control" id="marate_sfg_sacayu" value=""
                    readonly>
            </div>

            <div class="col-md-2">
                <input name="total_sfg_sacayu" type="text" class="form-control" id="total_sfg_sacayu"
                    value="{{$sfg_sasauy_total[0]->total}}" readonly>
            </div>

            <div class="col-md-2">
                <input name="totalbank_sfg_sacayu" type="text" class="form-control" id="totalbank_sfg_sacayu"
                    value="{{$sfg_sas_gt1}}">
            </div>

        </div>

        <div class="col-md-12" style="margin-top:15px;">
            <div class="col-md-2">
                <label for="inputIsValid" class="form-control-label">SASTRIC SIDDHA</label>
                <input type="hidden" name="semi_sassidha" type="hidden" class="form-control" id="semi_sassidha"
                    value="SEMI-SASTRIC SIDDHA">
            </div>

            <div class="col-md-2">
                <input name="stk_sfg_sacsid" type="text" class="form-control" id="stk_sfg_sacsid"
                    value="{{$sfg_sasida_qoh[0]->qoh}}" readonly>
            </div>


            <div class="col-md-2">
                <input name="stkbank_sfg_sacsid" type="text" class="form-control" id="stkbank_sfg_sacsid"
                    value="{{$sfg_sasid_stk1}}">
            </div>


            <div class="col-md-1">
                <input name="unit_sfg_sacsid" type="text" class="form-control" id="unit_sfg_sacsid" value="Nos"
                    readonly>
            </div>

            <div class="col-md-1">
                <input name="marate_sfg_sacsid" type="text" class="form-control" id="marate_sfg_sacsid" value=""
                    readonly>
            </div>

            <div class="col-md-2">
                <input name="total_sfg_sacsid" type="text" class="form-control" id="total_sfg_sacsid"
                    value="{{$sfg_sasida_total[0]->total}}" readonly>
            </div>

            <div class="col-md-2">
                <input name="totalbank_sfg_sacsid" type="text" class="form-control" id="totalbank_sfg_sacsid"
                    value="{{$sfg_sasid_gt1}}">
            </div>

        </div>

        <div class="col-md-12" style="margin-top:15px;">
            <div class="col-md-2">
                <label for="inputIsValid" class="form-control-label">SIDDHA</label>
                <input type="hidden" name="semi_sidha" type="hidden" class="form-control" id="semi_sidha"
                    value="SEMI-SIDDHA">
            </div>

            <div class="col-md-2">
                <input name="stk_sfg_sidha" type="text" class="form-control" id="stk_sfg_sidha"
                    value="{{$sfg_sidha_qoh[0]->qoh}}" readonly>
            </div>


            <div class="col-md-2">
                <input name="stkbank_sfg_sidha" type="text" class="form-control" id="stkbank_sfg_sidha"
                    value="{{$sfg_sidha_stk1}}">
            </div>


            <div class="col-md-1">
                <input name="unit_sfg_sidha" type="text" class="form-control" id="unit_sfg_sidha" value="Nos" readonly>
            </div>

            <div class="col-md-1">
                <input name="marate_sfg_sidha" type="text" class="form-control" id="marate_sfg_sidha" value="" readonly>
            </div>

            <div class="col-md-2">
                <input name="total_sfg_sidha" type="text" class="form-control" id="total_sfg_sidha"
                    value="{{$sfg_sidha_total[0]->total}}" readonly>
            </div>

            <div class="col-md-2">
                <input name="totalbank_sfg_sidha" type="text" class="form-control" id="totalbank_sfg_sidha"
                    value="{{$sfg_sidha_gt1}}">
            </div>

        </div>

        <div class="col-md-12" style="margin-top:15px;">

            <div class="col-md-2">
                <label for="inputIsValid" class="form-control-label">PROCESSING GODOWN</label>
                <input type="hidden" name="pro_godown" type="hidden" class="form-control" id="pro_godown"
                    value="PROCESSING GODOWN">
            </div>

            <div class="col-md-2">
                <input name="pro_godown_qoh" type="text" class="form-control" id="pro_godown_qoh"
                    value="{{$pro_godown_qoh[0]->qoh}}" readonly>
            </div>


            <div class="col-md-2">
                <input name="stkbank_pro_godown" type="text" class="form-control" id="stkbank_pro_godown"
                    value="{{$pro_godown_stk1}}">
            </div>


            <div class="col-md-1">
                <input name="unit_pro_godown" type="text" class="form-control" id="unit_pro_godown" value="Kg/Ltr"
                    readonly>
            </div>

            <div class="col-md-1">
                <input name="marate_pro_godown" type="text" class="form-control" id="marate_pro_godown" value=""
                    readonly>
            </div>

            <div class="col-md-2">
                <input name="total_pro_godown" type="text" class="form-control" id="total_pro_godown"
                    value="{{$pro_godown_total[0]->total}}" readonly>
            </div>

            <div class="col-md-2">
                <input name="totalbank_pro_godown" type="text" class="form-control" id="totalbank_pro_godown"
                    value="{{$pro_godown_gt1}}">
            </div>

        </div>

        <div class="col-md-12" style="margin-top:15px;">

            <div class="col-md-2">
                <label for="inputIsValid" class="form-control-label">OTHER PROMOTIONAL AND ACCESSARY ITEMS</label>
                <input type="hidden" name="access_item" type="hidden" class="form-control" id="access_item"
                    value="OTHER PROMOTIONAL AND ACCESSARY ITEMS">
            </div>

            <div class="col-md-2">
                <input name="access_item_qoh" type="text" class="form-control" id="access_item_qoh"
                    value="{{$access_item_qoh[0]->qoh}}" readonly>
            </div>


            <div class="col-md-2">
                <input name="stkbank_access_item" type="text" class="form-control" id="stkbank_access_item"
                    value="{{$access_item_stk1}}">
            </div>


            <div class="col-md-1">
                <input name="unit_access_item" type="text" class="form-control" id="unit_access_item" value="Nos"
                    readonly>
            </div>

            <div class="col-md-1">
                <input name="marate_access_item" type="text" class="form-control" id="marate_access_item" value=""
                    readonly>
            </div>

            <div class="col-md-2">
                <input name="total_access_item" type="text" class="form-control" id="total_access_item"
                    value="{{$access_item_total[0]->total}}" readonly>
            </div>

            <div class="col-md-2">
                <input name="totalbank_access_item" type="text" class="form-control" id="totalbank_access_item"
                    value="{{$access_item_gt1}}">
            </div>

        </div>
        <!-- SEMI FINISHED GOODS END-->

        <div class="col-md-12" style="margin-top:15px;">
            <div class="col-md-2">
                <label for="inputIsValid" class="form-control-label fonts" style=""><b>Total Value</b></label>
            </div>

            <div class="col-md-2">
                <input name="ovrall_total_qoh" type="text" class="form-control" id="ovrall_total_qoh" value="" readonly>
            </div>

            <div class="col-md-2">
                <input name="ovrall_total_qoh_bank" type="text" class="form-control" id="ovrall_total_qoh_bank"
                    value="{{$grand_total_qoh_bank[0]->stock}}" readonly>
            </div>
            <div class="col-md-1">
                <input name="" type="text" class="form-control" id="" value="-" readonly>
            </div>
            <div class="col-md-1">
                <input name="" type="text" class="form-control" id="" value="-" readonly>
            </div>
            <div class="col-md-2">
                <input name="total_category" type="text" class="form-control" id="total_category" value="" readonly>
            </div>

            <div class="col-md-2">
                <input name="ovrall_total" type="text" class="form-control" id="ovrall_total"
                    value="{{$grand_total[0]->total}}" readonly>
            </div>
        </div>
    </div>
    <!-- over end -->

    <!-- summary start -->

    <div class="col-md-12" style="margin-top:15px;">
        <h4 style="text-align: center;background:#4e4646;color:#fff;"><b>Summary</b></h4>

        <div class="col-md-6" style="margin-top:15px;">
            <div class="col-md-8">
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-4">1. Total Value</label>
                    <div class="col-md-8">
                        <input type="text" name="sum_tl_val" class="form-control" id="ovrall_total1"
                            value="{{$grand_total[0]->total}}" readonly>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-4">2. Less:Trade Creditors</label>
                    <div class="col-md-8">
                        <input type="text" name="trade_sum" type="hidden" class="form-control" id="trade_sum"
                            value="{{$trade_credit[0]->balance}}">
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-4">3. Net Value (1-2)</label>
                    <div class="col-md-8">
                        <input type="text" name="net_sum" type="hidden" class="form-control" id="net_sum"
                            value="{{$net_value}}" readonly>
                    </div>
                </div>
            </div>


            <div class="col-md-8">
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-4">4. Less: Margin as applicable</label>
                    <div class="col-md-8">
                        <div class="col-md-8">
                            <input type="text" name="result" class="form-control" id="result" value="{{$net_value}}"
                                readonly>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="multiplier" class="form-control" id="multiplier"
                                placeholder="Enter multiplier">
                        </div>
                        <div class="col-md-12">
                            <input type="text" name="margin_sum" class="form-control" id="margin_sum" readonly>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-8">
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-4">5. Total Value A (3-4)</label>
                    <div class="col-md-8">
                        <input type="text" name="totala_sum" class="form-control" id="total_a" value="{{$total_a}}"
                            readonly>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-6" style="margin-top:15px;">
            <h4><b>Goods procured under Guarantee</b></h4>

            <div class="col-md-8">
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-4">6. Sundry Debtor</label>
                    <div class="col-md-8">
                        <input type="text" name="sun_debtor" type="hidden" class="form-control" id="sun_debtor"
                            value="{{$sundry_debtor[0]->cus_balance}}">
                    </div>
                </div>
            </div>


            <div class="col-md-8" style="margin-top:70px;">
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-4">7. Less:Margin as applicable</label>
                    <div class="col-md-8">
                        <div class="col-md-8">
                            <input type="text" name="result1" class="form-control" id="result1"
                                value="{{$sundry_debtor[0]->cus_balance}}" readonly>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="multiplier1" class="form-control" id="multiplier1"
                                placeholder="Enter multiplier">
                        </div>
                        <div class="col-md-12">
                            <input type="text" name="margin2_sum" class="form-control" id="margin2_sum" readonly>
                        </div>
                    </div>
                </div>
            </div>




            <div class="col-md-8">
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-4">8. Total Value B (6-7)</label>
                    <div class="col-md-8">
                        <input type="text" name="totalb_sum" type="hidden" class="form-control" id="total_b"
                            value="{{$total_b}}" readonly>
                    </div>
                </div>
            </div>


            <div class="col-md-8">
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-4">9. Drawing Power (5+8)</label>
                    <div class="col-md-8">
                        <input type="text" name="draw_power" class="form-control" id="drawing_power"
                            value="{{$total_a + $total_b}}" readonly>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="form-group text-center">

                    <button name="save" type="button" class="btn save draftsave">Draft Save</button>

                    <button name="" type="button" id="viewButton" class="btn btn-success">Final Save</button>
                </div>
            </div>
        </div>
        </form>


        <script>
            $(document).ready(function () {

                var selectDate = "{{ request('select_month') }}";

                $('#select_month').val(selectDate);

            });


            document.addEventListener('DOMContentLoaded', function () {

                var today = new Date();
                var currentYear = today.getFullYear();
                var currentMonth = today.getMonth() + 1; // getMonth() returns 0-11
                var previousMonth = currentMonth - 1;
                var previousYear = currentYear;

                if (previousMonth === 0) { // If current month is January, previous month should be December of the previous year
                    previousMonth = 12;
                    previousYear -= 1;
                }

                var formattedCurrentMonth = ('0' + currentMonth).slice(-2);
                var formattedPreviousMonth = ('0' + previousMonth).slice(-2);

                var startMonthYear = previousYear + '-' + formattedPreviousMonth;
                var endMonthYear = currentYear + '-' + formattedCurrentMonth;

                document.getElementById('select_month').setAttribute('min', startMonthYear);
                document.getElementById('select_month').setAttribute('max', endMonthYear);
            });


            // save function
            $(document).on('click', '.save', function (e) {
                e.preventDefault();

                var selectDate = "{{ request('select_month') }}";
                var btnval = $(this).val();
                $('#savestatus').val(btnval);
                var url = "{{ URL::to('stockstatementgeneratesave') }}";
                // validationrule('stock_form');
                var form = $('#stock_form');
                form.parsley().validate();
                var form = $('#stock_form');
                form.parsley().validate();

                if (form.parsley().isValid()) {
                    var saveurl = "{{ url('stockstatementgeneratesave') }}";
                    var red_url = "{{ url('stockstatementgenerate') }}";
                    red_url = red_url + "?select_month=" + encodeURIComponent(selectDate);
                    
                     $('.ajaxLoading').show();
                    //     validationrule('save');
                    var form = $("#stock_form");
                    var formData = form.serialize();

                    $.post(saveurl, formData, function (data) {
                        var status = data.status;
                        var msg = data.message;
                        notyMsg(status, msg);
                         $('.ajaxLoading').hide();
                        setTimeout(function () {
                            window.location.href = red_url;

                        }, 1500);

                    });
                }
            });
            // ---END---

            // Save function
            $(document).on('click', '.finalsave', function (e) {

                e.preventDefault();

                var btnval = $(this).val();
                $('#savestatus').val(btnval);

                var form = $('#stock_form');
                form.parsley().validate();

                if (form.parsley().isValid()) {
                    var saveurl = "{{ url('stockstatementgeneratefinalsave') }}";
                    var red_url = "{{ url('stockstatementforbank') }}";

                    // Serialize form data and append date
                    var formData = form.serialize();

                    $.post(saveurl, formData, function (data) {
                        var status = data.status;
                        var msg = data.message;
                        notyMsg(status, msg);

                        setTimeout(function () {
                            window.location.href = red_url;
                        }, 1500);
                    });
                }
            });

            document.getElementById("viewButton").addEventListener("click", function () {
                $('#confirm_modal').modal('show');
            });

            // close modal button to close the sidebar
            document.getElementById("closeButton").addEventListener("click", function () {
                $('#confirm_modal').modal('hide');

            });


            // net value calculate
            $(document).ready(function () {
                // Function to calculate the net value
                function calculateNetValue() {
                    let totalValue = 0;
                    const influencingInputs = ['#totalbank_cos', '#totalbank_sacayv', '#totalbank_sacsid', '#totalbank_sida', '#totalbank_carton', '#totalbank_container', '#totalbank_insert', '#totalbank_label', '#totalbank_oitem', '#totalbank_sticker', '#totalbank_raw', '#totalbank_oil', '#totalbank_leaf', '#totalbank_lab', '#totalbank_chemical', '#totalbank_sfg_cos', '#totalbank_sfg_sacayu', '#totalbank_sfg_sacsid', '#totalbank_sfg_sidha', '#totalbank_pro_godown', '#totalbank_access_item'];

                    influencingInputs.forEach(function (inputId) {
                        totalValue += parseFloat($(inputId).val()) || 0;
                    });

                    let tradeCreditors = parseFloat($('#trade_sum').val()) || 0;
                    let netValue = totalValue - tradeCreditors;

                    $('#net_sum').val(netValue.toFixed(2));
                }
                const influencingInputs = ['#totalbank_cos', '#totalbank_sacayv', '#totalbank_sacsid', '#totalbank_sida', '#totalbank_carton', '#totalbank_container', '#totalbank_insert', '#totalbank_label', '#totalbank_oitem', '#totalbank_sticker', '#totalbank_raw', '#totalbank_oil', '#totalbank_leaf', '#totalbank_lab', '#totalbank_chemical', '#totalbank_sfg_cos', '#totalbank_sfg_sacayu', '#totalbank_sfg_sacsid', '#totalbank_sfg_sidha', '#totalbank_pro_godown', '#totalbank_access_item'];


                influencingInputs.forEach(function (inputId) {
                    $(inputId).on('input', function () {
                        calculateNetValue();
                    });
                });

                // Initialize net value on page load
                calculateNetValue();
            });


            // fg total qty for bank purpose
            $(document).ready(function () {
                function calculateTotal() {
                    let total = 0;
                    $('#stkbank_cos, #stkbank_sacayv, #stkbank_sacsid, #stkbank_sida').each(function () {
                        let value = parseFloat($(this).val()) || 0;
                        total += value;
                    });
                    $('#total_fg_qty b').text(total);
                }

                $('#stkbank_cos, #stkbank_sacayv, #stkbank_sacsid, #stkbank_sida').on('input', function () {
                    calculateTotal();
                });

                // Initialize total on page load
                calculateTotal();
            });

            // fg total value for bank purpose

            $(document).ready(function () {
                function calculateTotal() {
                    let total = 0;
                    $('#totalbank_cos, #totalbank_sacayv, #totalbank_sacsid, #totalbank_sida').each(function () {
                        let value = parseFloat($(this).val()) || 0;
                        total += value;
                    });
                    $('#total_fg_value b').text(total);
                }

                $('#totalbank_cos, #totalbank_sacayv, #totalbank_sacsid, #totalbank_sida').on('input', function () {
                    calculateTotal();
                });

                // Initialize total on page load
                calculateTotal();
            });

            // pm total qty for bank purpose
            $(document).ready(function () {
                function calculateTotal() {
                    let total = 0;
                    $('#stkbank_carton, #stkbank_container, #stkbank_insert, #stkbank_label,#stkbank_oitem,#stkbank_sticker').each(function () {
                        let value = parseFloat($(this).val()) || 0;
                        total += value;
                    });
                    $('#total_pm_qty b').text(total);
                }

                $('#stkbank_carton, #stkbank_container, #stkbank_insert, #stkbank_label,#stkbank_oitem,#stkbank_sticker').on('input', function () {
                    calculateTotal();
                });

                // Initialize total on page load
                calculateTotal();
            });

            // pm total value for bank purpose

            $(document).ready(function () {
                function calculateTotal() {
                    let total = 0;
                    $('#totalbank_carton, #totalbank_container, #totalbank_insert, #totalbank_label,#totalbank_oitem,#totalbank_sticker').each(function () {
                        let value = parseFloat($(this).val()) || 0;
                        total += value;
                    });
                    $('#total_pm_value b').text(total);
                }

                $('#totalbank_carton, #totalbank_container, #totalbank_insert, #totalbank_label,#totalbank_oitem,#totalbank_sticker').on('input', function () {
                    calculateTotal();
                });

                // Initialize total on page load
                calculateTotal();
            });

            // rm total qty for bank purpose
            $(document).ready(function () {
                function calculateTotal() {
                    let total = 0;
                    $('#stkbank_chemical, #stkbank_lab, #stkbank_leaf, #stkbank_oil,#stkbank_raw').each(function () {
                        let value = parseFloat($(this).val()) || 0;
                        total += value;
                    });
                    $('#total_rm_qty b').text(total);
                }

                $('#stkbank_chemical, #stkbank_lab, #stkbank_leaf, #stkbank_oil,#stkbank_raw').on('input', function () {
                    calculateTotal();
                });

                // Initialize total on page load
                calculateTotal();
            });

            // rm total value for bank purpose

            $(document).ready(function () {
                function calculateTotal() {
                    let total = 0;
                    $('#totalbank_raw, #totalbank_oil, #totalbank_leaf, #totalbank_lab,#totalbank_chemical').each(function () {
                        let value = parseFloat($(this).val()) || 0;
                        total += value;
                    });
                    $('#total_rm_value b').text(total);
                }

                $('#totalbank_raw, #totalbank_oil, #totalbank_leaf, #totalbank_lab,#totalbank_chemical').on('input', function () {
                    calculateTotal();
                });

                // Initialize total on page load
                calculateTotal();
            });

            // sfg total qty for bank purpose
            $(document).ready(function () {
                function calculateTotal() {
                    let total = 0;
                    $('#stkbank_sfg_cos, #stkbank_sfg_sacayu, #stkbank_sfg_sacsid, #stkbank_sfg_sidha,#stkbank_pro_godown,#stkbank_access_item').each(function () {
                        let value = parseFloat($(this).val()) || 0;
                        total += value;
                    });
                    $('#total_sfg_qty b').text(total);
                }

                $('#stkbank_sfg_cos, #stkbank_sfg_sacayu, #stkbank_sfg_sacsid, #stkbank_sfg_sidha,#stkbank_pro_godown,#stkbank_access_item').on('input', function () {
                    calculateTotal();
                });

                // Initialize total on page load
                calculateTotal();
            });

            // sfg total value for bank purpose

            $(document).ready(function () {
                function calculateTotal() {
                    let total = 0;
                    $('#totalbank_sfg_cos, #totalbank_sfg_sacayu, #totalbank_sfg_sacsid, #totalbank_sfg_sidha,#totalbank_access_item,#totalbank_pro_godown').each(function () {
                        let value = parseFloat($(this).val()) || 0;
                        total += value;
                    });
                    $('#total_sfg_value b').text(total);
                }

                $('#totalbank_sfg_cos, #totalbank_sfg_sacayu, #totalbank_sfg_sacsid, #totalbank_sfg_sidha,#totalbank_pro_godown,#totalbank_access_item').on('input', function () {
                    calculateTotal();
                });

                // Initialize total on page load
                calculateTotal();
            });

            //  total value calculate purpose

            $(document).ready(function () {
                function calculateTotal() {
                    let total = 0;
                    $('#totalbank_cos, #totalbank_sacayv, #totalbank_sacsid, #totalbank_sida,#totalbank_carton, #totalbank_container, #totalbank_insert, #totalbank_label,#totalbank_oitem,#totalbank_sticker,#totalbank_raw, #totalbank_oil, #totalbank_leaf, #totalbank_lab,#totalbank_chemical,#totalbank_sfg_cos, #totalbank_sfg_sacayu, #totalbank_sfg_sacsid, #totalbank_sfg_sidha,#totalbank_pro_godown,#totalbank_access_item').each(function () {
                        let value = parseFloat($(this).val()) || 0;
                        total += value;
                    });
                    $('#ovrall_total').val(total);
                }

                $('#totalbank_cos, #totalbank_sacayv, #totalbank_sacsid, #totalbank_sida,#totalbank_carton, #totalbank_container, #totalbank_insert, #totalbank_label,#totalbank_oitem,#totalbank_sticker,#totalbank_raw, #totalbank_oil, #totalbank_leaf, #totalbank_lab,#totalbank_chemical,#totalbank_sfg_cos, #totalbank_sfg_sacayu, #totalbank_sfg_sacsid, #totalbank_sfg_sidha,#totalbank_pro_godown,#totalbank_access_item').on('input', function () {
                    calculateTotal();
                });

                // Initialize total on page load
                calculateTotal();
            });



            //  total value calculate purpose

            $(document).ready(function () {
                function calculateTotal() {
                    let total = 0;
                    $('#totalbank_cos, #totalbank_sacayv, #totalbank_sacsid, #totalbank_sida,#totalbank_carton, #totalbank_container, #totalbank_insert, #totalbank_label,#totalbank_oitem,#totalbank_sticker,#totalbank_raw, #totalbank_oil, #totalbank_leaf, #totalbank_lab,#totalbank_chemical,#totalbank_sfg_cos, #totalbank_sfg_sacayu, #totalbank_sfg_sacsid, #totalbank_sfg_sidha,#totalbank_pro_godown,#totalbank_access_item').each(function () {
                        let value = parseFloat($(this).val()) || 0;
                        total += value;
                    });
                    $('#ovrall_total1').val(total);
                }

                $('#totalbank_cos, #totalbank_sacayv, #totalbank_sacsid, #totalbank_sida,#totalbank_carton, #totalbank_container, #totalbank_insert, #totalbank_label,#totalbank_oitem,#totalbank_sticker,#totalbank_raw, #totalbank_oil, #totalbank_leaf, #totalbank_lab,#totalbank_chemical,#totalbank_sfg_cos, #totalbank_sfg_sacayu, #totalbank_sfg_sacsid, #totalbank_sfg_sidha,#totalbank_pro_godown,#totalbank_access_item').on('input', function () {
                    calculateTotal();
                });

                // Initialize total on page load
                calculateTotal();
            });




            // overall total qoh
            $(document).ready(function () {
                function calculateTotal() {
                    let totalA = parseFloat($('#total_qoh_fg').text()) || 0;
                    let totalB = parseFloat($('#total_qoh_pm').text()) || 0;
                    let totalC = parseFloat($('#total_qoh_rm').text()) || 0;
                    let totalD = parseFloat($('#total_qoh_sfg').text()) || 0;

                    let total = totalA + totalB + totalC + totalD;
                    $('#ovrall_total_qoh').val(total.toFixed(2));
                }

                // Initialize total on page load
                calculateTotal();
            });

            // overall total value

            $(document).ready(function () {
                function calculateTotal() {
                    let totalA = parseFloat($('#fg_total').text()) || 0;
                    let totalB = parseFloat($('#rm_total').text()) || 0;
                    let totalC = parseFloat($('#pm_total').text()) || 0;
                    let totalD = parseFloat($('#sfg_total').text()) || 0;

                    let total = totalA + totalB + totalC + totalD;
                    $('#total_category').val(total.toFixed(2));
                }

                // Initialize total on page load
                calculateTotal();
            });

            //  total qty calculate purpose

            $(document).ready(function () {
                function calculateTotal() {
                    let total = 0;
                    $('#stkbank_cos, #stkbank_sacayv, #stkbank_sacsid, #stkbank_sida,#stkbank_carton, #stkbank_container, #stkbank_insert, #stkbank_label,#stkbank_oitem,#stkbank_sticker,#stkbank_raw, #stkbank_oil, #stkbank_leaf, #stkbank_lab,#stkbank_chemical,#stkbank_sfg_cos, #stkbank_sfg_sacayu, #stkbank_sfg_sacsid, #stkbank_sfg_sidha, #stkbank_pro_godown, #stkbank_access_item').each(function () {
                        let value = parseFloat($(this).val()) || 0;
                        total += value;
                    });
                    $('#ovrall_total_qoh_bank').val(total);
                }

                $('#stkbank_cos, #stkbank_sacayv, #stkbank_sacsid, #stkbank_sida,#stkbank_carton, #stkbank_container, #stkbank_insert, #stkbank_label,#stkbank_oitem,#stkbank_sticker,#stkbank_raw, #stkbank_oil, #stkbank_leaf, #stkbank_lab,#stkbank_chemical,#stkbank_sfg_cos, #stkbank_sfg_sacayu, #stkbank_sfg_sacsid, #stkbank_sfg_sidha,#stkbank_pro_godown, #stkbank_access_item').on('input', function () {
                    calculateTotal();
                });

                // Initialize total on page load
                calculateTotal();
            });




            // total calculation purpose


            $(document).ready(function () {
                // Function to calculate net value
                function calculateNetValue() {
                    let totalValue = 0;
                    const influencingInputs = ['#totalbank_cos', '#totalbank_sacayv', '#totalbank_sacsid', '#totalbank_sida', '#totalbank_carton', '#totalbank_container', '#totalbank_insert', '#totalbank_label', '#totalbank_oitem', '#totalbank_sticker', '#totalbank_raw', '#totalbank_oil', '#totalbank_leaf', '#totalbank_lab', '#totalbank_chemical', '#totalbank_sfg_cos', '#totalbank_sfg_sacayu', '#totalbank_sfg_sacsid', '#totalbank_sfg_sidha', '#totalbank_pro_godown', '#totalbank_access_item'];

                    influencingInputs.forEach(function (inputId) {
                        totalValue += parseFloat($(inputId).val()) || 0;
                    });

                    let tradeCreditors = parseFloat($('#trade_sum').val()) || 0;
                    let netValue = totalValue - tradeCreditors;
                    $('#result').val(netValue.toFixed(2));

                    // Call margin sum calculation
                    calculateMarginSum();
                }

                // Function to calculate margin sum
                function calculateMarginSum() {
                    let netValue = parseFloat($('#result').val()) || 0;
                    let multiplier = parseFloat($('#multiplier').val()) || 0;
                    let marginSum = netValue * multiplier;
                    $('#margin_sum').val(marginSum.toFixed(2));

                    // Call total A calculation
                    calculateTotalA();
                }

                // Function to calculate total A
                function calculateTotalA() {
                    let netValue = parseFloat($('#result').val()) || 0;
                    let marginSum = parseFloat($('#margin_sum').val()) || 0;
                    let totalA = netValue - marginSum;
                    $('#total_a').val(totalA.toFixed(2));

                    // Recalculate drawing power
                    calculateDrawPower();
                }

                // Function to calculate margin2 sum
                function calculateMargin2Sum() {
                    let netValue = parseFloat($('#result1').val()) || 0;
                    let multiplier1 = parseFloat($('#multiplier1').val()) || 0;
                    let margin2Sum = netValue * multiplier1;
                    $('#margin2_sum').val(margin2Sum.toFixed(2));

                    // Call total B calculation
                    calculateTotalB();
                }

                // Function to calculate total B
                function calculateTotalB() {
                    let sundryDebtor = parseFloat($('#sun_debtor').val()) || 0;
                    let margin2Sum = parseFloat($('#margin2_sum').val()) || 0;
                    let totalB = sundryDebtor - margin2Sum;
                    $('#total_b').val(totalB.toFixed(2));

                    // Recalculate drawing power
                    calculateDrawPower();
                }

                // Function to calculate drawing power
                function calculateDrawPower() {
                    let totalA = parseFloat($('#total_a').val()) || 0;
                    let totalB = parseFloat($('#total_b').val()) || 0;
                    let drawPower = totalA + totalB;
                    $('#drawing_power').val(drawPower.toFixed(2));
                }

                // Event listeners for influencing inputs
                const influencingInputs = ['#totalbank_cos', '#totalbank_sacayv', '#totalbank_sacsid', '#totalbank_sida', '#totalbank_carton', '#totalbank_container', '#totalbank_insert', '#totalbank_label', '#totalbank_oitem', '#totalbank_sticker', '#totalbank_raw', '#totalbank_oil', '#totalbank_leaf', '#totalbank_lab', '#totalbank_chemical', '#totalbank_sfg_cos', '#totalbank_sfg_sacayu', '#totalbank_sfg_sacsid', '#totalbank_sfg_sidha', '#totalbank_pro_godown', '#totalbank_access_item'];

                influencingInputs.forEach(function (inputId) {
                    $(inputId).on('input', function () {
                        calculateNetValue();
                    });
                });

                // Event listener for trade_sum
                $('#trade_sum').on('input', function () {
                    calculateNetValue();
                });

                // Event listener for sun_debtor
                $('#sun_debtor').on('input', function () {
                    calculateTotalB();
                });

                // Event listener for multiplier
                $('#multiplier').on('input', function () {
                    calculateMarginSum();
                });

                // Event listener for multiplier1
                $('#multiplier1').on('input', function () {
                    calculateMargin2Sum();
                });

                // Initialize calculations on page load
                calculateNetValue();
                calculateMargin2Sum();
                calculateTotalB();
                calculateDrawPower();
            });


            window.onscroll = function () { myFunction() };

            var header = document.getElementById("myHeader");
            var sticky = header.offsetTop;

            function myFunction() {
                if (window.pageYOffset > sticky) {
                    header.classList.add("sticky");
                } else {
                    header.classList.remove("sticky");
                }
            }
        </script>


        @endsection