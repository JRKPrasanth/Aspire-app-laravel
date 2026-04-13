@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Search Candidate</h3>
    @include('layouts.breadcrumb')

    <div class="card shadow-lg rounded-4 border-0">
        <div class="container mt-4 table-responsive">
            <table id="DescTbl" class="table table-bordered table-striped w-100">
                <thead>
                    <tr class="table-warning">
                        <th>Actions</th>
                        <th>Description</th>
                        <th>Required Skill</th>
                        <th>Month</th>
                        <th>Year</th>
                        <th>Job Title</th>
                        <th>Min Salary</th>
                        <th>Max Salary</th>
                        <th>Min Experience</th>
                        <th>Max Experience</th>

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
                scrollX: true,
                scrollY: "50vh",
                ajax: "{{ route('searchcandidategrid') }}",
                columns: [

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
                            <button class="btn btn-sm btn-primary approve-btn" data-id="${row.description_id}"
                            data-skill="${row.reqired_skills}"
                            data-minexp="${row.min_experience}"
                            data-maxexp="${row.max_experience}">
                              Search
                            </button>`;
                            }
                            return buttons;
                        }
                    },

                    { data: 'description_name', name: 'description_name' },
                    { data: "reqired_skills" },
                    { data: "month_name" },
                    { data: "year_name" },
                    { data: "job_title_name" },
                    { data: "min_salary" },
                    { data: "max_salary" },
                    { data: "min_experience" },
                    { data: "max_experience" },

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
            var reqired_skills = $(this).data('skill');
            var min_experience = $(this).data('minexp');
            var max_experience = $(this).data('maxexp');


            var url = "{{URL::to('resumesearch')}}/" + reqired_skills + '/' + min_experience + '/' + max_experience + '?search=' + description_id;
            window.location.href = url;

        });


    </script>

@endpush