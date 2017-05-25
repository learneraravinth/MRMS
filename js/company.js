$(document).ready(function(){
	/* $("#company_profile").validate({
			rules: {
				first_name: {
					 required: true,
					 rangelength: [3, 54]
				},
				
			},
			
			submitHandler: function (form) { 
				return false;
			}
	}); */
	$("#company_profile_form").validate({
    //specify the validation rules
    rules: {
    client_name: "required",
    company_name: "required",
    c_address: "required",
    reference_name: "required",
   
    company_email_id: {
    required: true,
    email: true //email is required AND must be in the form of a valid email address
    },
   
	mobile_number:{
		required:true,
		minlength:8		
	 },
	 
    },
     
    //specify validation error messages
    messages: {
    client_name: "Client Name field cannot be blank!",
    company_name: "Company Name field cannot be blank!",
    c_address: "Address field cannot be blank!",
    company_email_id: "Email Id field cannot be blank!",
    mobile_number: {
		required:"Phone number cannot be blank",
		minlength:"Your Phone number must be at least 10 numbers ",
	},
	
	reference_name:"Reference Name cannot be blank!",
    
    
    },
     
    submitHandler: function(form){
    form.submit();
    }
     
    });
	$("#company_profile_form_edit").validate({
    //specify the validation rules
    rules: {
    client_name: "required",
    company_name: "required",
    c_address: "required",
    reference_name: "required",
   
    company_email_id: {
    required: true,
    email: true //email is required AND must be in the form of a valid email address
    },
   
	mobile_number:{
		required:true,
		minlength:8		
	 },
	 
    },
     
    //specify validation error messages
    messages: {
    client_name: "Client Name field cannot be blank!",
    company_name: "Company Name field cannot be blank!",
    c_address: "Address field cannot be blank!",
    company_email_id: "Email Id field cannot be blank!",
    mobile_number: {
		required:"Phone number cannot be blank",
		minlength:"Your Phone number must be at least 10 numbers ",
	},
	
	reference_name:"Reference Name cannot be blank!",
    
    
    },
     
    submitHandler: function(form){
    form.submit();
    }
     
    });


});


function save_company_details(){
	
	
		var is_valid	= $('#company_profile_form').valid();
		if(!is_valid)
		{
			return false;
		}
		//var file_name = document.getElementById('user_profile_file').val();
		//$('#Huser_profile_file').attr('value',file_name);
			 
		var filename = baseUrl+'company/save_company_details';
		var salutation=$('input[type="radio"]:checked').val();
		var request  = $.ajax({
			url  : filename,
			type : "POST",
			data : {
					'salutation'			 : salutation,
					client_name   			 : $("#client_name").val(), 
					company_name    		 : $("#company_name").val(), 
					company_address    		 : $("#c_address").val(), 
					company_email_id    	 : $("#company_email_id").val(), 
					designation    	 	     : $("#designation").val(), 
					mobile_number     		 : $("#mobile_number").val(), 
					company_desc   		     : $("#company_desc").val(), 
					reference_name           : $("#reference_name").val(), 
					client_status            : $("#client_status").val(), 
					company_profile_file   	 : $("#Hcompany_profile").val(), 
					company_watermark_file   : $("#Hcompany_water").val(), 
					company_logo_file   	 : $("#Hcompany_logo").val(), 
			
			},
			dataType : "html"
		});
		
		request.done(function(result){
			var output    = jQuery.parseJSON(result);
			if(output.flag == 1){
				swal("Saved!", "Successfully Inserted", "success");
				location.href = baseUrl+"company";
			}
			});
		
		request.fail(function(jqXHR,textStatus){
			alert(textStatus);
		});		
	
}

function update_company_details($id){
	
	var is_valid	= $('#company_profile_form_edit').valid();
	//var file_name = document.getElementById('user_profile_file').val();
	//alert(file_name);
	//$('#Huser_profile_file').attr('value', file_name);
	
		if(!is_valid)
		{
			return false;
		}
			 
		var filename = baseUrl+'company/update_company_details/'+$id;
		var salutation=$('input[type="radio"]:checked').val();
		var request  = $.ajax({
			url  : filename,
			type : "POST",
			data : {
					'salutation'			 : salutation,
					client_name   			 : $("#client_name").val(), 
					company_name    		 : $("#company_name").val(), 
					company_address    		 : $("#c_address").val(), 
					company_email_id    	 : $("#company_email_id").val(), 
					designation    	 	     : $("#designation").val(), 
					mobile_number     		 : $("#mobile_number").val(), 
					company_desc   		     : $("#company_desc").val(), 
					reference_name           : $("#reference_name").val(), 
					client_status            : $("#client_status").val(), 
					company_watermark_file   : $("#Hcompany_water").val(), 
					company_logo_file   	 : $("#Hcompany_logo").val(), 
			
			},
			dataType : "html"
		});
		
		request.done(function(result){
			var output    = jQuery.parseJSON(result);
			if(output.flag == 1){
				swal("Updated!", "Successfully Updated", "success");
				location.href = baseUrl+"company";
			}
		});
		
		request.fail(function(jqXHR,textStatus){
			alert(textStatus);
		});		
	
	
}
function deleteCompany(id){

swal({   
title: "Are you sure want to delete company",  
type: "warning",   
showCancelButton: true,
confirmButtonColor: "#DD6B55 ",   
confirmButtonText: "Yes",
cancelButtonText:"No", 
closeOnConfirm: false },

function(){

$.ajax({
		type	 : 'POST',
		url: baseUrl+'company/delete_company/'+id,
		data 	 : {'id':id},
		success  : function(data) {
		
		swal("Deleted!", "Deleted Successfully.", "success");
		//setTimeout(function(){location.reload();},1);
		window.location.reload();
		
	}
	});
	});
}



