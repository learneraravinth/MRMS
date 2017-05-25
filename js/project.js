$(document).ready(function(){
	/* $("#add_project_form").validate({
			rules: {
				client_name: {
					 required: true,
					 rangelength: [3, 54]
				},
				project_name: {
					 required: true,					
				},
			},
			
			submitHandler: function (form) { 
				return false;
			}
	});
	
	$("#add_project_activity_form").validate({
			rules: {
				client_name: {
					 required: true,
					 rangelength: [3, 54]
				},
				project_name: {
					 required: true,					
				},
			},
			
			submitHandler: function (form) { 
				return false;
			}
	}); */
	
	
	

});

function save_project_details(){
	
		var is_valid	= $('#add_project_form').valid();
		if(!is_valid)
		{
			return false;
		}
		var filename = baseUrl+'project/save_project_deatils';
		/* if(type==1){
			 filename = baseUrl+'project/save_project_deatils';
		}else{
			 filename = baseUrl+'project/update_project_deatils';
		} */
		
		var request  = $.ajax({
			url  : filename,
			type : "POST",
			data : {								
					project_name   		 : $("#project_name").val(), 
					project_desc    	 : $("#project_desc").val(), 
					project_status    	 : $("#project_status").val(), 
					},
				dataType : "html"
		});
		
		request.done(function(result){
			var output    = jQuery.parseJSON(result);
			if(output.flag == 1){
				swal("Saved!", "Successfully Inserted", "success");
				window.location.href = baseUrl+'project';
			}
					
		});
		
		request.fail(function(jqXHR,textStatus){
			alert(textStatus);
		});			
}

function update_project_details($id){
	
	var is_valid	= $('#edit_project_form').valid();
	//var file_name = document.getElementById('user_profile_file').val();
	
	//$('#Huser_profile_file').attr('value', file_name);
	
		if(!is_valid)
		{
			return false;
		}
			 
		var filename = baseUrl+'project/update_project_details/'+$id;
		
		var request  = $.ajax({
			url  : filename,
			type : "POST",
			data : {								
					project_name   		 : $("#project_name").val(), 
					project_desc    	 : $("#project_desc").val(), 
					project_status    	 : $("#project_status").val(), 
					},
			dataType : "html"
		});
		
		request.done(function(result){
			var output    = jQuery.parseJSON(result);
			if(output.flag == 1){
				swal("Updated!", "Successfully Updated","success");
				location.href = baseUrl+"project";
			}
			
		});
		
		request.fail(function(jqXHR,textStatus){
			alert(textStatus);
		});		
	
	
}
function save_project_activity(){
		var is_valid	= $('#add_project_activity_form').valid();
		if(!is_valid)
		{
			return false;
		}
		var salutation=$('input[type="radio"]:checked').val();	 
		var filename = baseUrl+'project/save_project_activity';
		var request  = $.ajax({
			url  : filename,
			type : "POST",
			data : {								
					'salutation'   		 	: salutation, 
					client_name   		 	: $("#client_name").val(), 
					id_label_multiple   	: $("#id_label_multiple").val(), 
					id_label_multiple_project : $("#id_label_multiple_project").val(), 
					id_label_multiple_dev   : $("#id_label_multiple_dev").val(), 
					project_demo   		 	: $("#project_demo").val(), 
					project_requirement   	: $("#project_requirement").val(), 
					initialization_process  : $("#initialization_process").val(), 
					project_estimation  	: $("#project_estimation").val(), 
					project_documentation  	: $("#project_documentation").val(), 
					project_module  		: $("#project_module").val(), 
					project_start_date  	: $("#project_start_date").val(), 
					project_end_date  		: $("#project_end_date").val(), 
					project_development_members  : $("#project_development_members").val(), 
					meeting_number  		: $("#meeting_number").val(), 
					meeting_date  			: $("#meeting_date").val(), 
					Hproject_profile_file  	: $("#Hproject_profile_file").val(), 
					meeting_description     : $("#meeting_description").val(), 
					requirement_date    	: $("#requirement_date").val(), 
					req_description     	: $("#req_description").val(), 
					Hrequirement_file       : $("#Hrequirement_file").val()
			
			},
				dataType : "html"
		});
		request.done(function(result){
			var output    = jQuery.parseJSON(result);
			//alert(output.msg);
			if(output.flag == 1){
				swal("Saved!", "Successfully Inserted", "success");
				location.href = baseUrl+"project/project_activity_list";
			}

		});
		request.fail(function(jqXHR,textStatus){
			alert(textStatus);
		});			
}
function deleteProject(id){

swal({   
title: "Are you sure want to delete project",  
type: "warning",   
showCancelButton: true,
confirmButtonColor: "#DD6B55 ",   
confirmButtonText: "Yes",
cancelButtonText:"No", 
closeOnConfirm: false },

function(){

$.ajax({
		type	 : 'POST',
		url		 : baseUrl+'project/delete_project/'+id,
		data 	 : {'id':id},
		success  : function(data) {
		
		swal("Deleted!", "Deleted Successfully.", "success");
		//setTimeout(function(){location.reload();},1);
		window.location.reload();
		
	}
	});
	});
}
function project_activity_file(project_file){
	
	
	//e.preventDefault();
    $.ajaxFileUpload
	(
			{
			     url           : baseUrl+'project/project_activity_document',
				 secureuri      : false,
				 fileElementId  : 'project_file',
				 dataType    	: 'text',
				 data           : {
									'filename' : 'project_file'
								},
				success        : function (data)
				{
					
					 var result = jQuery.parseJSON(data);
					 $('#Hproject_profile_file').val(result.store_filename);
					
				},
				
				failure : function (data)
				{
					alert('Pls try again Later');
				}
			}
	  );
      return true;
		
}


function project_activity_req_file(requirement_file){
	
	
	  $.ajaxFileUpload
	(
			{
			     url           : baseUrl+'project/project_activity_req_document',
				 secureuri      : false,
				 fileElementId  : 'requirement_file',
				 dataType    	: 'text',
				 data           : {
									'filename' : 'requirement_file'
								},
				success        : function (data)
				{
					
					 var result = jQuery.parseJSON(data);
					 $('#Hrequirement_file').val(result.store_filename);
					
				},
				
				failure : function (data)
				{
					alert('Pls try again Later');
				}
			}
	  );
      return true;
}




function cancel(){
	window.location.href = 'http://localhost:81/pms/project';
}