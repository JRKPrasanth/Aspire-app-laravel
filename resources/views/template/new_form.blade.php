@extends('layouts.header')
@section('content')

<body>
<span class="ui_close_btn"></span>
<div class="container" style="height:1000px;">
<div class="row">
	<div class="col-lg-1">
	</div>
<div class="col-lg-10">
<div class="card">
<div class="card-header">
<strong>Sales Enquiry</strong> 
</div>
<div class="card-body card-block">

          <div class="form-group">
              <label for="idname" class=" form-control-label">Text Field</label>
              <input type="text" id="inputIsValid" class="is-valid form-control-success form-control">
          </div>

          <div class="form-group">
              <label for="idname" class=" form-control-label">Radio Button Field</label>
              <div class="l-radio" style="display: flex;">
                   <div class="c-radio">
                       <input type="radio" name="radio" checked>
                       <span class="check_mark"></span>
                       <label for="">Radio</label>
                   </div>
                   <div class="c-radio">
                       <input type="radio" name="radio">
                       <span class="check_mark"></span>
                       <label for="">Radio</label>
                   </div>
               </div>
          </div>

          <div class="form-group">
              <label for="idname" class=" form-control-label">Check Box Field</label>
              <div class="l-checkbox">
            <div class="c-checkbox">
                <input type="checkbox" name="checkbox" checked="">
                <span class="check_mark"></span>
                <label for="">Checkbox</label>
            </div>
            <div class="c-checkbox">
                <input type="checkbox" name="checkbox">
                <span class="check_mark"></span>
                <label for="">Checkbox</label>
            </div>
        </div>
          </div>

          <div class="form-group">
            <label for="idname" class="form-control-label">Select Box Field</label>
            <select class="simpsons">
              <option value="none">Select</option>
              <option value="lotus">Lotus</option>
              <option value="jasmine">Jasmine</option>
              <option value="sunflower">Sunflower</option>
              <option value="daisy">Daisy</option>
            </select>
          </div>

          <div class="form-group">
              <label for="idname" class="form-control-label">Select Box Live Search</label>
              <select class="selectpicker" data-show-subtext="true" data-live-search="true">
                <option data-subtext="Rep California">Tom Foolery</option>
                <option data-subtext="Sen California">Bill Gordon</option>
                <option data-subtext="Sen Massacusetts">Elizabeth Warren</option>
                <option data-subtext="Rep Alabama">Mario Flores</option>
                <option data-subtext="Rep Alaska">Don Young</option>
                <option data-subtext="Rep California" disabled="disabled">Marvin Martinez</option>
              </select>
          </div>

          <div class="form-group">
                <label for="idname" class="form-control-label">Textarea</label>
                <textarea name="textarea-input" id="textarea-input" rows="9" placeholder="Content..." class="form-control"></textarea>
                          </div>

          </div>
</div>
</div>
	<div class="col-lg-1">
	</div>
</div>
@extends('layouts.footer')
</div>
</body>
	<script>
	$(document).ready(function(){
	$('.group_name').on('keyup',function(){
	this.value= this.value.toUpperCase();
	});

	});
	</script>

@endsection
