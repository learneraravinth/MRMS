$(document).ready(function(){
	$("#login_form").validate({
			rules: {
				admin_email: {
					 required: true,
					 email:true
				},
				
				admin_password: {
					 required: true,
					 rangelength: [3, 54]
				},
				
			},
			
			submitHandler: function (form) { 
				return false;
			}
	});

});


function login_user(){
		var is_valid	= $('#login_form').valid();
		if(!is_valid)
		{
			return false;
		}
			 
		var filename = baseUrl+"admin/login/login_user";
		
		var request  = $.ajax({
			url  : filename,
			type : "POST",
			data : {								
					admin_email 	: $("#admin_email").val(), 
					admin_password 	: $("#admin_password").val(), 

			},
			dataType : "html"
		});
		
		request.done(function(result){
			var output    = jQuery.parseJSON(result);
			
			if(output.flag==1){
					swal("Login!", "Login Successfully.", "success");
					window.location.href = baseUrl+"admin/dashboard";
			}else{
				swal(
				  'Login Fail',
				  output.msg,
				  'error'
				);
			}
			//alert(output.msg);
			
		});
		
		request.fail(function(jqXHR,textStatus){
			alert(textStatus);
		});		
}