$(function() {
	$('#form_logo').submit(function(e) {
		e.preventDefault();
		$.ajaxFileUpload
		(
			{
				 url         	: baseUrl+'admin/company_profile/company_logo_upload',
				 secureuri      : false,
				 fileElementId  : 'company_logo',
				 dataType    	: 'text',
				 data           : {
									'filename' : "company_logo"
								},
				success        : function (data)
				{
					 var result = jQuery.parseJSON(data);
					 var imagePath = baseUrl+'uploads/company_logo_upload/';
					 
					 $('#imgage_name').val(result.store_filename);
					 $('#show_img').html('');
					 $('#show_img').prepend('<img height="89" width="90" id="theImg" src="'+imagePath+result.store_filename+'" />');
				},
				
				failure : function (data)
				{
					alert('Pls try again Later');
				}
			}
		);
		  return true;
	   });
	   
	   $('#admin_photofrm').submit(function(e) {
		e.preventDefault();
		$.ajaxFileUpload
		(
			{
				 url         	: baseUrl+'admin/company_profile/admin_upload',
				 secureuri      : false,
				 fileElementId  : 'user_photo',
				 dataType    	: 'text',
				 data           : {
									'filename' : "user_photo"
								},
				success        : function (data)
				{
					 var result = jQuery.parseJSON(data);
					 var imagePath = baseUrl+'uploads/admin_upload/';
					 
					 $('#user_photo_name').val(result.store_filename);
					 $('#show_img_user').html('');
					 $('#show_img_user').prepend('<img height="89" width="90" id="theImg" src="'+imagePath+result.store_filename+'" />');
				},
				
				failure : function (data)
				{
					alert('Pls try again Later');
				}
			}
		);
		  return true;
	   });
	   
	   $('#admin_photofrm1').submit(function(e) {
		e.preventDefault();
		$.ajaxFileUpload
		(
			{
				 url         	: baseUrl+'dashboard_user/admin_upload',
				 secureuri      : false,
				 fileElementId  : 'user_photo1',
				 dataType    	: 'text',
				 data           : {
									'filename' : "user_photo1"
								},
				success        : function (data)
				{
					 var result = jQuery.parseJSON(data);
					 var imagePath = baseUrl+'uploads/admin_upload/';
					 
					 $('#user_photo_name').val(result.store_filename);
					 $('#show_img_user').html('');
					 $('#show_img_user').prepend('<img height="89" width="90" id="theImg" src="'+imagePath+result.store_filename+'" />');
				},
				
				failure : function (data)
				{
					alert('Pls try again Later');
				}
			}
		);
		  return true;
	   });
});