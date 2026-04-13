@extends('layouts.header')
@section('content')
<style>
    input[type="file"] {
    display: none;
}
.custom-file-upload 
{
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
}
.btnic 
{
    text-align: center;
    background-color: DodgerBlue;
    border: none;
    color: white;
    padding: 12px 30px;
    cursor: pointer;
    font-size: 12px;
}
</style>


<span class="ui_close_btn"></span>
<h2 class="heads">attendance chart</h2>
            <div class="card">
                            <div class="card-body card-block">
                              <div class="row">
                          <div class="col-md-12" style="margin-bottom: 0px">

              

                    <div class="col-md-3">
                        <div class="form-group  " > 
                            <label for="Employee Id" class=" control-label col-md-4 text-left"> 
                                Employee Name
                            </label>
                            <div class="col-md-6    employee_event">
                                <select name='employee_id' rows='7' id='employee_id' class='select2 ' required  >
                                    <?php echo $emp_id; ?>
                                </select> 
                            </div> 
                            <div class="col-md-2">

                            </div>
                        </div> 

                    </div>
               


                <div class="col-md-3">
                    <div class="form-group  " > 
                        <label for="Employee Id" class=" control-label col-md-4 text-left"> 
                            Month
                        </label>
                        <div class="col-md-6">
                            <select name='month' rows='5' id='month' class='select2 ' required  >
                              
                            </select> 
                        </div> 
                        <div class="col-md-2">

                        </div>
                    </div> 

                </div>
                               <div class="col-md-3">
                    <div class="form-group  " > 
                        <label for="Employee Id" class=" control-label col-md-4 text-left"> 
                            Year
                        </label>
                        <div class="col-md-6">
                            <select name='year' rows='5' id='year' class='select2 ' required  >
                              
                            </select> 
                        </div> 
                        <div class="col-md-2">

                        </div>
                    </div> 

                </div>
                              <div class="col-md-3"><button type="button" class="btn  search">Search</button></div>

              <div id="work_hours" style="min-width: 310px;  margin-top: 20px;"></div> 

            </div> 
                </div> 
             
                       
            
            </div>  
                      
                    </div>
                    </div>
    
  
	<script>
	$(document).ready(function(){
			// for year load and selected
        var min = 1900,
					max = new Date().getFullYear(),
					select = document.getElementById('year');

				for (var i = max; i>=min; i--){
					var opt = document.createElement('option');
					opt.value = i;
					opt.innerHTML = i;
					select.appendChild(opt);
					
				}
 
	// month jcombo and select current month
    var condition1 ='1=1';
		$("#month").jCombo("{{ URL::to('jcomboformlogin?table=month:id:description') }}&order_by=id asc"+'&parent='+condition1,
                {selected_value:'<?php echo date("m"); ?>'});
		
		
			
	});
          $(function () 
    {
    $('.ajaxLoading').hide();
    
    $('#work_hours').highcharts({
    chart: {
    type: 'column'
    },
            colors: ['#28B463', '#0066FF', '#00CCFF'],
            title: {
            text: 'Working Hours'
            },
            subtitle: {
            text: '{{$emp_name}}',
            },
            xAxis: {
            type: 'category',
                    labels: {
                    rotation: - 45,
                            style: {
                            fontSize: '11px',
                                    fontFamily: 'Verdana, sans-serif'
                            }
                    }
            },
            yAxis: {
            min: 0,
                    title: {
                    text: 'Time(hours)'
                    }
            },
            legend: {
            enabled: false
            },
            tooltip: {
            pointFormat: ''
            },
            series: [{
            name: 'Population',
                    //   colorByPoint: true,
                   
                    data: <?php echo html_entity_decode($work_hours); ?>,
                    dataLabels: {
                    enabled: true,
                            rotation: - 90,
                            color: '#FFFFFF',
                            align: 'right',
                            format: '{point.y:.1f}', // one decimal
                            y: 10, // 10 pixels down from the top
                            style: {
                                fontSize: '13px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                    }
            }]
    });
    $('#check_in').highcharts({
    title: {
    text: "CHECK-IN",
            x: - 20 //center
    },
            colors: ['#1A5276', '#0066FF', '#00CCFF'],
            subtitle: {
            text: '{{$emp_name}}',
                    x: - 20
            },
            xAxis: {
            categories: <?php echo html_entity_decode($categories); ?>
            },
            yAxis: {
            title: {
            text: 'Points'
            },
                    plotLines: [{
                    value: 5,
                            width: 1,
                            color: '#808080'
                    }],
            },
            tooltip: {
            valueSuffix: ''
            },
            legend: {
            layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    borderWidth: 0
            },
            series: [{
            name: 'Check-In',
                    data: {{$check_in}}
            }]
    });
    $('#check_out').highcharts({

    title: {
    text: "CHECK-OUT",
            x: - 20 //center
    },
            colors: ['#E74C3C', '#0066FF', '#00CCFF'],
            subtitle: {
            text: '{{$emp_name}}',
                    x: - 20
            },
            xAxis: {
            categories: <?php echo html_entity_decode($categories); ?>
            },
            yAxis: {
            title: {
            text: 'Points'
            },
            plotLines: [{
            value: 5,
                    width: 1,
                    color: '#606060'
            }],
            },
            tooltip: {
            valueSuffix: ''
            },
            legend: {
            layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    borderWidth: 0
            },
            series: [{
                    name: 'Check-Out',
                    data: {{$check_out}}
            }]
    });
    
    
     $('#container').highcharts({

        chart: {
            type: 'columnrange',
            inverted: true,
            height: 800,
        },

        title: {
            text: 'Attendance In-Out Time'
        },

        subtitle: {
            text: '{{$emp_name}}',
        },

        xAxis: {
            categories: <?php echo html_entity_decode($categories); ?>
        },

        yAxis: {
            title: {
                text: 'Time(hours)'
            },
            lineHeight:10
        },

        tooltip: {
            valueSuffix: ''
        },

        plotOptions: {
            columnrange: {
                dataLabels: {
                    enabled: true,
                    formatter: function () {
                        return this.y + '';
                    }
                }
            }
        },

        legend: {
            enabled: false
        },

        series: [{
            name: 'In-Out',
            data: <?php echo html_entity_decode($in_out); ?>,
        }]

    });
    
    $('.search').click(function () {
        var month_id = $('#month').val();
        var year = $('#year').val();
            var emp_code = $('#employee_id').val();
            if(emp_code!='' && year!='' && month_id!=''){
        var APP_URL = {!! json_encode(url('/')) !!};
        var url = "";
        
            
                url = APP_URL + "/attendancechartsreport/" + month_id + "/" + emp_code+"/"+year;
     

        if (month_id != null && month_id != "")
        {
            window.location.replace(url);
        }
    }else{
        notyMsg("info","Please Select row");
    }
    });
    });
	</script>

@endsection
