@extends('layouts.header') @section('content')

 <h2 class="heads">Toolsmenu Config</h2>

    <form>
        {{ csrf_field() }}
        

            <div class="card">
                <div class="card-header">
                
                    <span class="ui_close_btn"><a href="../toolsmenuconfig" class="collapse-close pull-right btn-danger" onclick="../toolsmenuconfig"></a></span>
                </div>

                <div class="card-body card-block ">

                   <div class="row">
    <div class="col-md-12">

        <div class="invoice-box" id="section-to-print">

            <table cellpadding="0" cellspacing="0">
                <tbody>

                    <h2 class="heads1">Tools Menu Details</h2>

                    <tr class="information">
                        <td colspan="6">
                            <table>
                                <tbody>

                                    <tr>
                                        <td>
                                            <p><b>Page Module:</b> {{$values['pagemodule'] }}</p>
                                            <br>
                                           

                                        </td>
                                        <td class="text-right">
                                             <p><b>Description:</b> {{$values['description'] }}</p>
                                            <br>
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
                                        <th>Submenu Name</th>
                                        <th>Submenu Label</th>
                                        <th>Submenu URL</th>
                                        <th>Submenu icon</th>
                                        <th>Submenu Region</th>
                                        <th>Submenu Order</th>
                                    </tr>
                                </thead>
                                @foreach($linesvalue as $key=>$value)

                                <tr>
                                    <td>
                                        <?php echo $value->submenu_name ?>
                                    </td>
                                    <td>
                                        <?php echo $value->submenu_label ?>
                                    </td>
                                    <td>
                                        <?php echo $value->submenu_url ?>
                                    </td>
                                    <td>
                                        <?php echo $value->submenu_icon ?>
                                    </td>
                                    <td>
                                        <?php echo $value->submenu_region ?>
                                    </td>
                                    <td>
                                        <?php echo $value->submenu_order ?>
                                    </td>
                                </tr>
                                @endforeach
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


    