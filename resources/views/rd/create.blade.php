<!-- rd/create.blade.php -->
@extends('layouts.header')
@section('content')
<div class="container">
  <h3 class="mb-3 text-danger">Create R&D Entry</h3>

  <form id="rdForm" method="POST" action="{{ route('store') }}" enctype="multipart/form-data">
    @csrf

    <div class="card mb-3">
      <div class="card-header bg-primary text-white">Project Info</div>
      <div class="card-body">
        <input type="text" name="project_name" class="form-control mb-2" placeholder="Project Name" required>
        <input type="text" name="batch_no" class="form-control mb-2" placeholder="Batch No">
        <input type="text" name="product_name" class="form-control mb-2" placeholder="Product Name">
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-header bg-warning">Testing</div>
      <div class="card-body">
        <textarea name="test_parameters" class="form-control mb-2" placeholder="Test Parameters"></textarea>
        <textarea name="test_results" class="form-control mb-2" placeholder="Test Results"></textarea>
        <textarea name="test_observations" class="form-control" placeholder="Observations"></textarea>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-header bg-info">Process</div>
      <div class="card-body">
        <textarea name="process_steps" class="form-control mb-2" placeholder="Process Steps"></textarea>
        <textarea name="process_remarks" class="form-control" placeholder="Remarks"></textarea>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-header bg-success text-white">Output</div>
      <div class="card-body">
        <textarea name="output_summary" class="form-control mb-2" placeholder="Output Summary"></textarea>
        <select name="output_status" class="form-control">
          <option value="">Select Status</option>
          <option value="Success">Success</option>
          <option value="Requires Improvement">Requires Improvement</option>
          <option value="Failed">Failed</option>
        </select>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-header bg-secondary text-white">Attachments</div>
      <div class="card-body">
        <input type="file" name="attachments[]" class="form-control" multiple>
      </div>
    </div>

    <button type="submit" class="btn btn-success">Submit</button>
  </form>

  <div id="msg" class="mt-3"></div>
</div>

<script>
$('#rdForm').on('submit', function(e){
    e.preventDefault();

    let formData = new FormData(this);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(res){
            alert('Saved successfully');
        },
        error: function(xhr){
            console.log(xhr.responseText);
            alert('Error');
        }
    });
});

</script>
@endsection