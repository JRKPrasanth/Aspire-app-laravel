@extends('layouts.header')
@section('content')



<form>

    <div class="card">
        <div class="card-header">
            <span class="ui_close_btn"><a href="../needhelp" class="collapse-close pull-right btn-danger" onclick='location.href="{{ url($pageModule) }}"'></a></span>
        </div>
        <div class="card-body card-block normalform">
            <div class="row">
                <div class="col-md-12">

                    <div class="invoice-box" id="section-to-print">

                        <table cellpadding="0" cellspacing="0">
                            <tbody>

                                <h2 class="heads1">SOP Details</h2>

                                <tr class="information">
                                    <td colspan="6">
                                        <table>
                                            <tbody>

                                                <tr>
                                                    <td>
                                                        <p><b>Primary Menu : </b>{{$headerdata->primary_menu}}</p> <br>
                                                        <p><b>Sub Menu: </b>{{$headerdata->submenu}}</p> <br>
                                                        <p><b>Menu : </b>{{$headerdata->menu}}</p><br>
                                                    </td>
                                                    <td>
                                                        <p><b>Url : </b>{{$headerdata->url}}</p> <br>
                                                        <p><b>Created By :</b>{{$headerdata->created}}</p><br>
                                                        <p><b>Created At :</b>{{$headerdata->created_at}}</p><br>


                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr class="heading">
                                    <table class="table table-bordered table-hover ">
                                        <thead>
                                            <tr>
                                                <th>Line No</th>
                                                <th>Activities</th>
                                                <th>Active</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($linesdata as $key => $value) : ?>
                                                <tr>
                                                    <td>{!! $key+1 !!}</td>
                                                    <td>{!! $value->activities !!}</td>
                                                    <td>{!! $value->active !!}</td>

                                                </tr>
                                            <?php endforeach; ?>

                                        </tbody>
                                    </table>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>
</form>
@endsection