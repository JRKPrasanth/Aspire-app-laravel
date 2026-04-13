@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Job Report settings</h3>
    @include('layouts.breadcrumb')


    <form method="post" action="{{ url('jobreportsave') }}" id="form-ui">
        {{ csrf_field() }}


        <div class="card shadow-lg rounded-4 border-0">
            <div class="card-header bg-success bg-gradient text-white fw-semibold"></div>

            <div class="card-body card-block ">
                <?php error_reporting(0);?>

                <div class="row">

                    <div class="col-md-10 col-md-offset-2">


                        <div class="form-group col-md-3 row " style="display:none;">
                            <div class="hide">
                                <label class="field col-md-6">jobreport_settings_id:</label>
                                <div class="section col-md-6">
                                    <input type="text" class="form-control jobreport_settings_id"
                                        name="jobreport_settings_id" value="{{$jobreport_settings_id}}">
                                </div>
                            </div>
                        </div>


                        <div class="form-group col-md-6 row">

                            <label class="field col-md-5"><span style="color:red;">*</span>Pricelist:</label>
                            <div class="section col-md-7">
                                <select type="text" name="pricelist_id" id="pricelist_id" class="select2 pricelist_id"
                                    value="" style="width: 100%;">
                                    {!! $pricelist_id !!}
                                </select>
                            </div>

                        </div>


                    </div>
                    <div class="form-group text-center">

                        <div class="col-md-12">
                            <input type="hidden" name="submit_type" class="submit_type">
                            <button type="submit" class="btn btn-success px-4">Save </button>
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
            $(".decimal_points").keypress(function (e) {
                if (String.fromCharCode(e.keyCode).match(/[^0-9]/g)) return false;
            });
        });

    </script>

@endpush