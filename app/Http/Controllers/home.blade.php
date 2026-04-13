@extends('layouts.header')

@section('content')


<!----------------------------------->
	<div class="row page-titles">

									 <div class="col-md-12">
											 <ol class="breadcrumb">
													 <li class="breadcrumb-item"><a href="#">Home</a></li>
													 <li class="breadcrumb-item active">Welcome</li>
											 </ol>
									 </div>

							 </div>
	<!----------------------------------->
<div class="container">

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <!--<div class="panel-heading"></div>-->

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <strong >  Welcome to Ifive </strong>
                </div>
            </div>
        </div>
    </div>
<!-------------------------------------------->
<style>
.header_call>.panel-heading {
  background: #6c46a7;
  color: #fff;
	display: flex;
    border-color: #ddd;
}
.header_call.panel-body {
    padding: 15px;
    border: 1px dashed #9c9b9b;
}
</style>

<div class="row">
	<div class="col-md-4">
			<h2>Fullscreen toggle</h2>
			<div class="header_call">
					<div class="panel-heading">
							<h3 class="panel-title">Panel title</h3>
							<ul class="list-inline panel-actions">
									<li><a href="#" id="drag-fullscreen" role="button" title="Toggle fullscreen"><i class="glyphicon glyphicon-resize-full"></i></a></li>
							</ul>
					</div>
					<div class="panel-body">
							<h3>Panel body</h3>
							<p>Click the resize icon in the top right to make this fullscreen.</p>
					</div>
			</div>
	</div>




</div>
<script type="text/javascript">
$(document).ready(function () {

   $(".header_call").draggable();
    //Toggle fullscreen
    $("#drag-fullscreen").click(function (e) {
        e.preventDefault();
        var $this = $(this);
        if ($this.children('i').hasClass('glyphicon-resize-full')){
            $this.children('i').removeClass('glyphicon-resize-full');
            $this.children('i').addClass('glyphicon-resize-small');
            $(".panel").css('position','');
        }else if ($this.children('i').hasClass('glyphicon-resize-small')){
            $this.children('i').removeClass('glyphicon-resize-small');
            $this.children('i').addClass('glyphicon-resize-full');
            $(".panel").css('position','relative');
        }
        $(this).closest('.panel').toggleClass('drag-fullscreen');
          $(".header_call").draggable();
    });
});
</script>
</div>
@endsection
