@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Job Description HR Approval</h3>
    @include('layouts.breadcrumb')

    <div class="card shadow-lg rounded-4 border-0">
        <div class="container mt-4">
            <table id="DescTbl" class="table table-bordered table-striped w-100">
                <thead>
                    <tr class="table-warning">
                        <th>Description</th>
                        <th>Department</th>
                        <th>Tob Title</th>
                        <th>Req Skill</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                    <tr class="table-info">
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

@endsection
@push('scripts')

<script>


        // data table funcrion	
        $(document).ready(function () {
            var table = $('#DescTbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('jobdescriptiongriddata') }}?status=1",
                columns: [

                    { data: 'description_name', name: 'description_name' },
                    { data: 'sub_department_name', name: 'sub_department_name' },
                    { data: 'job_title_name', name: 'job_title_name' },
                    { data: 'reqired_skills', name: 'reqired_skills' },
                    { data: 'active', name: 'active' },
                    {
                        data: 'description_id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '140px',
                        render: function (data, type, row) {
                            let buttons = '';
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
                                buttons += `
                        <button class="btn btn-sm btn-success approve-btn" data-id="${row.description_id}">
                          Approve
                        </button>`;
                            }
                            return buttons;
                        }
                    }
                ]
            });

            // Individual column search
            $('#DescTbl thead').on('keyup change', ".column-search", function () {
                var colIndex = $(this).parent().index();
                table.column(colIndex).search(this.value).draw();
            });
        });


            $(document).on('click', '.approve-btn', function () {

                var description_id = $(this).data('id'); 


                    var url = "{{ URL::to('createjobdescriptionhr') }}/" + description_id;
                        window.location.href = url;


            });


            $(document).on('click', '.reject-btn', function () {


                var description_id = $(this).data('id'); 


                    var url = "{{ URL::to('approvedescription') }}/reject/" + description_id;
                    $.get(url, function (data) {
                        if (data == 2) {
                            url = "{{ URL::to('jobdescriptionapproval')}}";

                            shwCustomAlert('Job Description Rejected Successfully','success');
                            setTimeout(function () {
                                window.location.href = url;
                            }, 2000);
                        }
                    });

            });


            $(document).on('click', '.view-btn', function () {

                var sales_hdr_id = $(this).data('id'); 
      
                    window.location.replace('soorderview/' + sales_hdr_id);

            });

           

    </script>

@endpush