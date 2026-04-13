<!-- FOR LINKING - PARSLEY VALIDATION ALL PAGES  -->

<?php 

$form_type=@$pageFormtype;

//dd($form_type);

?>

<link href="<?php echo asset('js/plugins/parsley/src/parsley.css') ?>" rel="stylesheet">
<script src="<?php echo asset('js/plugins/parsley/dist/parsley.js')?>"></script>

<script>
	
/*
function loader(mode)
{
	
	if(mode==1)
	{
	$('.loader').show();
	}
	else
	{
	$('.loader').hide();	
	}
}
*/
	/*sudhagar purpose:to deactivate input fields for post*/
function modal_field_active_decative(model_class,flag)
{
	if(flag==1)
	{
		$("."+model_class).find("input,textarea,select").attr("disabled","disabled");
	}
	else
	{
		$("."+model_class).find("input,textarea,select").removeAttr("disabled");
	}
}
/*end*/	
function draft_save(form_id,save_route,red_url,create_url,edit_url)
{
var bsf=before_submit_form(form_id);	
if(bsf==true)
{	
	var frm_data=$('#'+form_id).serializeArray();
	$.post(save_route,frm_data,function(data)
	{			
	var status=data.status;
	var msg=data.message;
	var id= data.id;		
	if(id!='')
	{
	edit_url=edit_url+"/"+id;
	}
	else
	{
	edit_url=create_url;	
	}	
	notyMessage(status,msg,red_url,create_url,edit_url);			
	});		
}
}		
	
		
function before_submit_form(form_id)
{	
	var id="#"+form_id;
	var id_each=id+' input,'+id+' select,'+id+' textarea';
	
	
	var form_data = {};
	var form_fields_data = {};
	var form_fields = {};
	var header = {};
	var lines = {};	
	var removed_line_id = {};	
	var before_submit_form_status =false;	

	$(id_each).each(function(index)
	{

		var input = $(this);

		var name=input.attr('name');		
		var required=input.prop('required');		
		var data_required=input.attr('data-required');	
		var view=1;
		
		if(required==true)
		{			
			required='';	
			
			if(typeof data_required!="undefined")
			{
				required=data_required;
			}			
		}
		else
		{
			required='0';			
		}
				
		var value=input.val();

		var type='';
		var key_name='';

		var nodeName=$(this)[0].nodeName;			
		nodeName=nodeName.toLowerCase();

		if(nodeName == "input") 
		{
			type = $(this).attr("type");  
			
			if(type=='hidden')
		    {
		   // view=0;
		    }
		} 
		else 
		{
			type = nodeName;   
		}		

		//console.log(name+" : "+required+" : "+view);
		
		if( typeof name !== "undefined")
		{ 		
			if(!/[\[\]']+/g.test(name))
			{
				key_name=name;		
				$current_row={'field':name,'type':type,'required':required,'value':value,'view':view};			
				form_fields[key_name] =$current_row;
				header[key_name]= form_fields[key_name];
						
				if(key_name=='removed_line_id' && form_fields[key_name]!='')
				{					
					removed_line_id=form_fields[key_name];									
				}
				
			}
			else
			{			
				key_name=name.replace(/[\[\]']+/g,'');			
				$current_row={'field':name,'type':type,'required':required,'value':value,'view':view};				
				form_fields[key_name] =$current_row;
				lines[key_name]= form_fields[key_name] ;				
			}
		}
	});
	
	var frm_data_serialized=$(id).serializeArray();
	
	form_data['header']=header;
	form_data['lines']=lines;
	form_data['removed_line_id']=removed_line_id;
	
	//console.log(form_data);
	
	//form_data['form_fields']=frm_data_serialized;
	//form_data['form_fields_data']=form_fields;
	var form_data_json=encodeURIComponent(JSON.stringify(form_data));
	var form_data_json_id='form_data_json_'+form_id;
	var form_token_id='token_'+form_id;
	
	//var tok=$("meta[name=csrf-token]").attr("content");
	var tok="<?php echo csrf_token();?>";	
	
	$('#'+form_data_json_id+',#'+form_token_id).remove();	
	$(id).append('<textarea name="form_data_json" id='+form_data_json_id+' class="hide form_data_json '+form_data_json_id+'" readonly>'+form_data_json+'</textarea>');	
	$(id).append('<input type="hidden" id='+form_token_id+' class="form_token_id '+form_token_id+'" name="_token" value="'+tok+'">');		
	$form_data_json=$('.'+form_data_json_id).text();
	
	if($form_data_json!='')
	{
	before_submit_form_status =true;	
	}
	//alert();
	
	return before_submit_form_status;
	
}	
function validationrule(form_id)
{
	var form_id=form_id;		
	var bsf=before_submit_form(form_id);	
	var form=[];
	form['form_id']=form_id;
	form['form_validate_status']=bsf;
	//afterSubmit(form);		
	return form;
	
}
	


	
</script>
	
<!-- FOR LINKING - PARSLEY VALIDATION ALL PAGES  -->

<script>
	
// FOR DOCUMENT READY FUNTIONS

	
$(document).ready(function() 
{		

//loader(0);	

$('form').append('<input type="hidden" id="removed_line_id" class="removed_line_id" name="removed_line_id">');		

	

$(document).on('click' ,'[name=apply],[name=submit]',function()
{	
	var submit_type=$(this).attr('name');	
	$(this).parents('form').find('.submit_type').val(submit_type);
});	
	
$(document).on('click' ,'.remove',function()
{
	var index = $('.clone').closest('tr').index();
	var line_id=$(this).closest('tr').find('td:first input').val();

	if(line_id!='')
	{	

		var removed_line_ids=[];	
		var removed_line_id=$('.removed_line_id').val();

		if(removed_line_id=='')
		{			
			$('.removed_line_id').val(line_id);	
		}
		else
		{				
			removed_line_ids=removed_line_id.split(',');				
			removed_line_ids.push(line_id);
			$('.removed_line_id').val(removed_line_ids);		
		}

	}	
	
});	
	
		
	
});
	
// FOR DOCUMENT READY FUNTIONS
	
	
</script>

