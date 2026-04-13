<!-- rd/edit.blade.php -->
@extends('layouts.header')
@section('content')
<div class="container">
  <h3 class="mb-3">Edit R&D Entry</h3>

  <form id="editForm" method="POST" action="{{ route('update', $entry->id) }}" enctype="multipart/form-data">
    @csrf

    <input type="hidden" id="entryId" value="{{ $entry->id }}">
    <input type="hidden" id="approvalStatus" value="{{ $entry->approval_status }}">


    <div class="card mb-3">
      <div class="card-header bg-primary text-white">Project Info</div>
      <div class="card-body">
        <input type="text" name="project_name" class="form-control mb-2" value="{{ $entry->project_name }}" required>
        <input type="text" name="batch_no" class="form-control mb-2" value="{{ $entry->batch_no }}">
        <input type="text" name="product_name" class="form-control mb-2" value="{{ $entry->product_name }}">
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-header bg-warning">Testing</div>
      <div class="card-body">
        <textarea name="test_parameters" class="form-control mb-2">{{ $entry->test_parameters }}</textarea>
        <textarea name="test_results" class="form-control mb-2">{{ $entry->test_results }}</textarea>
        <textarea name="test_observations" class="form-control">{{ $entry->test_observations }}</textarea>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-header bg-info">Process</div>
      <div class="card-body">
        <textarea name="process_steps" class="form-control mb-2">{{ $entry->process_steps }}</textarea>
        <textarea name="process_remarks" class="form-control">{{ $entry->process_remarks }}</textarea>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-header bg-success text-white">Output</div>
      <div class="card-body">
        <textarea name="output_summary" class="form-control mb-2">{{ $entry->output_summary }}</textarea>
        <select name="output_status" class="form-control">
          <option value="Success" {{ $entry->output_status=='Success'?'selected':'' }}>Success</option>
          <option value="Requires Improvement" {{ $entry->output_status=='Requires Improvement'?'selected':'' }}>Requires Improvement</option>
          <option value="Failed" {{ $entry->output_status=='Failed'?'selected':'' }}>Failed</option>
        </select>
      </div>
    </div>


    <div class="card mb-3">
      <div class="card-header bg-secondary text-white">Add Attachments</div>
      <div class="card-body">
        <input type="file" name="attachments[]" class="form-control" multiple>
      </div>
  </div>

    <button class="btn btn-primary">Update</button>
    <a href="{{ route('rd') }}" class="btn btn-secondary ms-2">Cancel</a>

  </form>

  <div id="msg" class="mt-3"></div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>

$(document).ready(function () {
    let status = $('#approvalStatus').val();

    if (status === 'Approved') {
        alert('Approved entries cannot be edited.');
        window.location.href = "{{ route('rd') }}";
    }
});
  
$('#editForm').on('submit', function(e){
  e.preventDefault();

  let id = $('#entryId').val();
  let fd = new FormData(this);

  $.ajax({
    url:$(this).attr('action'),
    type:"POST",
    data:fd,
    contentType:false,
    processData:false,
    success:function(res){
      alert('Updated successfully');
      window.location.href = "{{ route('rd') }}";
    },
    error: function (xhr) {
    if (xhr.status === 422) {
        let errors = xhr.responseJSON.errors;
        let msg = Object.values(errors).map(e => e[0]).join("\n");
        alert(msg);
    } else {
        alert('Unexpected error');
    }
  }
  });
});
</script>
@endsection