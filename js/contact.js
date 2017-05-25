$(document).ready(function(){
	$("#user_document_file").validate({
			rules: {
				first_name: {
					 required: true,
					 rangelength: [3, 54]
				},
				
			},
			
			submitHandler: function (form) { 
				return false;
			}
	});

});


function save_contact_details(){
	
	
		var is_valid	= $('#user_document_file').valid();
		if(!is_valid)
		{
			return false;
		}
		//var file_name = document.getElementById('user_profile_file').val();
		//$('#Huser_profile_file').attr('value',file_name);
			 
		var filename = baseUrl+'contactus/save_contact_details';
		var request  = $.ajax({
			url  : filename,
			type : "POST",
			data : {								
					
					first_name   			 : $("#first_name").val(), 
					last_name    			 : $("#last_name").val(), 
					email_id    	 		 : $("#email_id").val(), 
					mobile_number_1    	 	 : $("#mobile_number_1").val(), 
					designation   	         : $("#designation").val(), 
					date_of_birth     		 : $("#date_of_birth").val(), 
					address_1     			 : $("#address_1").val(), 
					country                  : $("#country").val(), 
					company_name   	         : $("#company_name").val(), 
					company_emailid   	     : $("#company_emailid").val(), 
					company_phone   	     : $("#company_phone").val(), 
					Huser_document_file   	 : $("#Huser_document_file").val(), 
			
			},
			dataType : "html"
		});
		
		request.done(function(result){
			var output    = jQuery.parseJSON(result);
			alert(output.msg);
			location.href = baseUrl+"contactus/contact_list";
		});
		
		request.fail(function(jqXHR,textStatus){
			alert(textStatus);
		});		
	
}

function update_contact_details($id){
	
	
		var is_valid	= $('#user_document_file').valid();
		if(!is_valid)
		{
			return false;
		}
		//var file_name = document.getElementById('user_profile_file').val();
		//$('#Huser_profile_file').attr('value',file_name);
			 
		var filename = baseUrl+'contactus/update_contact_details/'+$id;
		
		var request  = $.ajax({
			url  : filename,
			type : "POST",
			data : {								
					
					first_name   			 : $("#first_name").val(), 
					last_name    			 : $("#last_name").val(), 
					email_id    	 		 : $("#email_id").val(), 
					mobile_number_1    	 	 : $("#mobile_number_1").val(), 
					designation   	         : $("#designation").val(), 
					date_of_birth     		 : $("#date_of_birth").val(), 
					address_1     			 : $("#address_1").val(), 
					country                  : $("#country").val(), 
					company_name   	         : $("#company_name").val(), 
					company_emailid   	     : $("#company_emailid").val(), 
					company_phone   	     : $("#company_phone").val(), 
					Huser_document_file   	 : $("#Huser_document_file").val(), 
			
			},
			dataType : "html"
		});
		
		request.done(function(result){
			var output    = jQuery.parseJSON(result);
			alert(output.msg);
			
			location.href = baseUrl+"contactus/contact_list";
		});
		
		request.fail(function(jqXHR,textStatus){
			alert(textStatus);
		});	
	
	
}




