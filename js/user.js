$(document).ready(function(){
	
 $("#user_profile_add").validate({
    //specify the validation rules
    rules: {
		designation: "required",
		employee_id: "required",
   
		user_email: {
			required: true,
			email: true //email is required AND must be in the form of a valid email address
		},
		
		user_phone:{
			required:true,
		},
		
		employee_name:{
			required:true,
		},
		
		
		user_date_of_joining:{
			required:true,
		},
		
		user_live_add:{
			required:true 
		}
    },
     	 
	submitHandler: function(form){
		form.submit();
	}
     
});


 $("#user_profile_salary").validate({
    //specify the validation rules
    rules: {
		company_basic_salary: "required",
		company_lunch: "required",
		company_breakfast: "required",
		company_transport_allowance: "required",
		company_house_allowance: "required",
	},
     	 
	submitHandler: function(form){
		form.submit();
	}
     
}); 

$("#emp_doc_det").validate({
    rules: {
		emp_nationality: {
                required:true,
		},
		
	},
     	 
	submitHandler: function(form){
		form.submit();
	}
     
});
	jQuery.validator.addMethod("lettersonly", function(value, element) {
	  return this.optional(element) || /^[a-z]+$/i.test(value);
	}, "Please Enter Letters Only!"); 

});

function save_user_details(){
	var is_valid	= $('#emp_doc_det').valid();
	if(!is_valid)
	{
		return false;
	}
	var profile_change = $("#Huser_profile_file").val();
	
	var filename = baseUrl+'user/save_user_details';
	var salutation = $('input[name=salutation]:checked').val();
	
	if(salutation=='undefined'){
		salutation = '0';
	}
	var pass_probation = $("#pass_probation").val();
	
	if(pass_probation == 'passed'){
	   var probation_date = $("#probation_date").val();		
		if(probation_date==''){
			alert('Please enter probation pass date');
			return false;
		}
	}
	
	
	var salary_type = $('input[name=salary_show]:checked').val();
	
	var request  = $.ajax({
		url  : filename,
		type : "POST",
		data : {   
				salutation			 	 : salutation,
				salary_type			 	 : salary_type,
				company_basic_salary_sec : $("#company_basic_salary_sec").val(), 
				increament_date 		 : $("#increament_date").val(), 
				add_extra			 	 : $("#add_extra").val(), 
				employee_name    		 : $("#employee_name").val(), 
				user_email    	 		 : $("#user_email").val(), 
				user_date_of_joining   	 : $("#user_date_of_joining").val(), 
				user_date_of_birth   	 : $("#user_date_of_birth").val(), 
				user_gender     		 : $("#user_gender").val(), 
				user_phone     			 : $("#user_phone").val(), 
				user_live_add   		 : $("#user_live_add").val(), 
				user_permanent_add       : $("#user_permanent_add").val(), 
				
				company_basic_salary   	 : $("#company_basic_salary").val(), 
				company_lunch   		 : $("#company_lunch").val(),
				company_breakfast   	 : $("#company_breakfast").val(), 
				company_transport_allowance : $("#transport_allowance").val(), 
				company_house_allowance  : $("#company_house_allowance").val(), 
				company_remarks 		 : $("#company_remarks").val(), 
				pass_probation 		 	 : $("#pass_probation").val(), 
				probation_date 		 	 : $("#probation_date").val(), 
				extend_date 		 	 : $("#extend_dates").val(), 
				
				user_gender    			 : $("#user_gender").val(), 
				designation    			 : $("#designation").val(), 
				user_status    			 : $("#user_status").val(), 
				user_profile_file   	 : $("#Huser_profile_file").val(), 
				user_id   	 			 : $("#employee_id").val(), 
				emp_id   	 			 : $("#emp_id").val(),  
				account_no   	 		 : $("#account_no").val(),  
				transaction_fee   	 	 : $("#transaction_fee").val(),  
				account_bank   	 	 	 : $("#account_bank").val(),  
				
				nationality 			 : $("#emp_nationality").val(), 
				Hdoc_id_card 			 : $("#Hdoc_id_card").val(), 
				id_no   	 			 : $("#id_no").val(), 
				id_exp_date  			 : $("#id_exp_date").val(), 
				Hdoc_passport 			 : $("#Hdoc_passport").val(), 
				passport_no  			 : $("#passport_no").val(), 
				exp_date   	 			 : $("#exp_date").val(),
				emp_office   	 		 : $("#emp_office").val(),
		},
		dataType : "html"
	});
	
	request.done(function(result){
		var output    = jQuery.parseJSON(result);
		if(output.flag == 1){
			swal("Saved!", "Successfully Inserted", "success");
			//location.href = baseUrl+"user/user_list";
		}			
	});
	
	request.fail(function(jqXHR,textStatus){
		alert(textStatus);
	});			
}

function save_comp_emp_det(){
	var is_valid	= $('#user_profile_salary').valid();
	if(!is_valid)
	{
		return false;
	}
	var filename = baseUrl+'user/save_user_details';
	
	var request  = $.ajax({
		url  : filename,
		type : "POST",
		data : {   
				company_basic_salary   	 : $("#company_basic_salary").val(), 
				company_lunch   		 : $("#company_lunch").val(),
				company_breakfast   	 : $("#company_breakfast").val(), 
				company_transport_allowance : $("#company_transport_allowance").val(), 
				company_house_allowance 	: $("#company_house_allowance").val(), 
				company_remarks 			: $("#company_remarks").val(), 		
		},
		dataType : "html"
	});
	
	request.done(function(result){
		var output    = jQuery.parseJSON(result);
		if(output.flag == 1){
			swal("Saved!", "Successfully Inserted", "success");
			location.href = baseUrl+"user/user_list";
		}			
	});
	
	request.fail(function(jqXHR,textStatus){
		alert(textStatus);
	});			
}

function save_doc_det(){
	var is_valid	= $('#emp_doc_det').valid();
	if(!is_valid)
	{
		return false;
	}
	
	var emp_nationality = $("#emp_nationality").val();
	/* if(emp_nationality=="local"){
		
	}else if(emp_nationality=="foreigners"){
		
	}
	 */
	
	return false;
	var filename = baseUrl+'user/save_user_details';
	
	var request  = $.ajax({
		url  : filename,
		type : "POST",
		data : {   
				Hdoc_id_card : $("#Hdoc_id_card").val(), 
				id_no   	 : $("#id_no").val(), 
				id_exp_date  : $("#id_exp_date").val(), 
				doc_passport : $("#doc_passport").val(), 
				passport_no  : $("#passport_no").val(), 
				exp_date   	 : $("#exp_date").val(), 
		},
		dataType : "html"
	});
	
	request.done(function(result){
		var output    = jQuery.parseJSON(result);
		if(output.flag == 1){
			swal("Saved!", "Successfully Inserted", "success");
			location.href = baseUrl+"user/user_list";
		}			
	});
	
	request.fail(function(jqXHR,textStatus){
		alert(textStatus);
	});			
}

function upate_user_details($id){
	
	var is_valid	= $('#user_profile_edit').valid();
	
	
		if(!is_valid)
		{
			return false;
		}
			 
		var filename = baseUrl+'user/upate_user_details/'+$id;	
		var salutation = $('input[type="radio"]:checked').val();
		
		var request  = $.ajax({
			url  : filename,
			type : "POST",
			data : {
					'salutation'		     : salutation,
					first_name   			 : $("#first_name").val(), 
					last_name    			 : $("#last_name").val(), 
					user_name    			 : $("#user_name").val(), 
					user_email    	 		 : $("#user_email").val(), 
					user_password    	 	 : $("#user_password").val(), 
					user_date_of_joining   	 : $("#user_date_of_joining").val(), 
					user_date_of_birth   	 : $("#user_date_of_birth").val(), 
					user_gender     		 : $("#user_gender").val(), 
					user_phone     			 : $("#user_phone").val(), 
					user_live_add   		 : $("#user_live_add").val(), 
					user_permanent_add       : $("#user_permanent_add").val(), 
					designation    			 : $("#designation").val(), 
					user_status    			 : $("#user_status").val(), 
					user_profile_file   	 : $("#Huser_profile_file").val(), 
					
			
			},
			dataType : "html"
		});
	
	
		
		request.done(function(result){
			var output    = jQuery.parseJSON(result);
			if(output.flag == 1){
				swal("Update!", "Successfully Updated.", "success");
				location.href = baseUrl+"user/user_list";
			}
			
			
		});
		
		request.fail(function(jqXHR,textStatus){
			alert(textStatus);
		});
		
	
}


function deleteUser(id){

swal({   
title: "Are you sure want to delete user",  
type: "warning",   
showCancelButton: true,
confirmButtonColor: "#DD6B55 ",   
confirmButtonText: "Yes",
cancelButtonText:"No", 
closeOnConfirm: false },

function(){

$.ajax({
		type	 : 'POST',
		url: baseUrl+'user/Delete_user_profile/'+id,
		data 	 : {'id':id},
		success  : function(data) {
		
		swal("Delete!", "Deleted Successfully.", "success");
		//setTimeout(function(){location.reload();},1);
		window.location.reload();
		
	}
	});
	});
}
