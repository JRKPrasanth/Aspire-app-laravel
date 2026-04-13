@extends('layouts.header')
@section('content')


<form>

<div class="card">
<div class="card-header">
<h2>Company Document Details</h2>
<span class="ui_close_btn"><a href="../companydocument" class="collapse-close pull-right btn-danger" onclick="../manufacturerpartno"></a></span>
</div>
<div class="card-body card-block normalform">
    <div class="col-md-12">

        <table  class="table table-bordered table-hover ">
            <tbody>
            <thead>
                <th>S.No</th>
                <th>Document Name</th>
            </thead>
                <tr>
                    <td>1</td>
                    <td>{{$values->document }}</td>
                </tr>
            </tbody>
        </table>

    </div>



</div>
</div>

</form>
@endsection
