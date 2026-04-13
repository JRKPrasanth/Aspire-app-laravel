@extends('layouts.header')
@section('content')
<h3 class="text-danger">Employee Document Upload</h3>
@include('layouts.breadcrumb')

    

<div class="card shadow-lg border-0 rounded-4">
       <div class="card-header d-flex justify-content-between align-items-center bg-primary">
      <h4 class="mb-0 text-white">Employee Details</h4>
      <a href="{{ url('empuploaddoc') }}" class="btn btn-sm btn-danger">Close</a>
    </div> 
    <form action="" id="save" enctype="multipart/form-data">
        {{ csrf_field() }}

        <input type="hidden" name="edit_id" value="{{ $id }}" id="edit_id" />
        <input type="hidden" name="employee_id" value="{{ $employee_id }}" id="employee_id"/>

        <div class="card-body">

            <!-- ===================== BASIC INFO ===================== -->

            <div class="row g-4">

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Employee Name</label>
                    <input type="text" id="employee_name"
                           class="form-control"
                           value="{{ $employee_name }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Mobile</label>
                    <input type="text" id="mobile"
                           class="form-control"
                           value="{{ $mobile_number }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="text" id="mail"
                           class="form-control"
                           value="{{ $email }}">
                </div>

            </div>

            <hr class="my-4">

            <!-- ===================== COMPANY PROVISION ===================== -->
            <fieldset class="border p-4 rounded-4 shadow-sm bg-light">
                <legend class="float-none w-auto px-3 fs-6 text-primary fw-bold">
                    Company's Provision
                </legend>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th width="40%">Document Name</th>
                                <th width="20%">Date of Provision</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>

                        <tbody>
                        @php
                            $emp_remark = isset($company_remarks) ? json_decode($company_remarks) : [];
                            $count  = isset($company_provision_data) ? json_decode($company_provision_data) : [];
                        @endphp

                        @foreach ($company_provision as $key => $value)
                            @php
                                $checked = '';
                                $date = '';
                                $remarks = '';

                                if(count($count) > 0){
                                    foreach($count as $k => $val){
                                        if($value->id == $val[0]){
                                            $checked = 'checked';
                                            $date = $val[1];
                                            $remarks = $emp_remark[$k][1] ?? '';
                                            break;
                                        }
                                    }
                                }
                            @endphp

                            <tr>
                                <td class="bg-white">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="company_doc{{ $key }}"
                                               {{ $checked }}
                                               value="{{ $value->id }}">
                                        <label class="form-check-label fw-semibold">
                                            {{ $value->document }}
                                        </label>
                                    </div>
                                </td>

                                <td>
                                    <input type="text"
                                           class="form-control datepicker"
                                           name="provision_date[]"
                                           value="{{ $date }}">
                                </td>

                                <td>
                                    <textarea name="company_remarks[]"
                                              class="form-control"
                                              rows="3">{{ $remarks }}</textarea>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </fieldset>

            <hr class="my-4">

            <!-- ===================== EMPLOYEE PROVISION ===================== -->
            <fieldset class="border p-4 rounded-4 shadow-sm bg-light">
                <legend class="float-none w-auto px-3 fs-6 text-success fw-bold">
                    Employee's Provision
                </legend>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-success">
                            <tr>
                                <th width="40%">Document Name</th>
                                <th width="25%">File Upload</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>

                        <tbody>
                        @php 
                            $employee_remarks = isset($employee_remarks) ? json_decode($employee_remarks) : [];
                            $count1 = isset($employee_provision_data) ? json_decode($employee_provision_data) : [];
                        @endphp

                        @foreach($count1 as $key => $value)
                            <tr>
                                <td>
                                    <select class="form-select select2"
                                            name="document[]">
{!! app(config('global.CONT'))->jCombo('m_employee_doc_check_list','id','emp_document',$count1[$key][0]) !!}

                                    </select>
                                </td>

                                <td>
                                    <div class="mt-2">
                                        @if($count1[$key][1] != '')
                                            <a href="{{ '../documentupload/'.$employee_id.'/'.$count1[$key][1] }}"
                                               download class="text-decoration-none fw-semibold">
                                                {{ $count1[$key][1] }}
                                                <img src="{{ URL::to('') }}/images/download.png"
                                                     width="20">
                                            </a>
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    <textarea class="form-control"
                                              name="employee_remarks[]"
                                              rows="3">{{ $employee_remarks[$key][1] ?? '' }}</textarea>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </fieldset>
        </div>
    </form>
</div>


@endsection
@push('scripts')

<script>

    $(document).ready(function(){
$('#save input').attr('readonly', 'readonly');
$('#save textarea').attr('readonly', 'readonly');
    });

</script>

 @endpush
