
$(".submit_btn").click(function(e){

	if(validate_password()==false){
		e.preventDefault();
	}else{
		$("#common_user_redirect_login_form").submit();
	}

});



 return_error_msg = $("#return_error_msg").val();    
  if(return_error_msg != ''){
      $.alert(return_error_msg);
      $('#common_user_redirect_login_form').trigger("reset");
  }


function validate_password(){

    var password=$("#passwordValidation").val();

    if(password==""){

        $("#error_password").show().text("Please enter your password.");
        $("#passwordValidation").addClass("is-invalid");
        $("#passwordValidation").click(function(){$("#error_password").hide().text;$("#passwordValidation").removeClass("is-invalid");});
        return false;
    }


    var PasswordValue = document.getElementById('passwordValidation').value;
    var SaltValue = document.getElementById('hiddenSaltvalue').value;
    var EncryptPass = sha512(PasswordValue);
    var SaltedPass = SaltValue.concat(EncryptPass);
    var Saltedsha512pass = sha512(SaltedPass);
    document.getElementById('passwordValidation').value = Saltedsha512pass;
    exit();
}
