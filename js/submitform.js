$(document).ready(function(){
	var netidInput = $("#netid");
	var netidInfo = $("#netidInfo");
	var netid = "";
	
	var emailInput = $("#email");
	var emailInfo = $("#emailInfo");
	var email = "";
	
	var typeInput = $("#type");
	var typeInfo = $("#typeInfo");
	var type = "";
	
	var titleInput = $("#title");
	var titleInfo = $("#titleInfo");
	var title = "";
	
	var wageInput = $("#wage");
	var wageInfo = $("#wageInfo");
	var wage = "";
	
	var acctInput = $("#acct");
	var acctInfo = $("#acctInfo");
	var acct = "";

	$("#netid").blur(function()
		{
		netid = "";
		if($("#netid").val() != "")
			{
			netidInput.removeClass("error");
			netidInfo.removeClass("errortext");
			netidInput.addClass("success");
			netidInfo.addClass("successtext");
			netidInfo.text("");
			netid = $("#netid").val();
			}
		//if it's NOT valid
		else
			{
			netidInput.removeClass("success");
			netidInfo.removeClass("successtext");
			netidInput.addClass("error");
			netidInfo.addClass("errortext");
			netidInfo.text("Type in a valid netid please.");
			return false;
			}
		});
	$("#email").blur(function()
		{
		email = "";
		if($("#email").val() != "")
			{
			emailInput.removeClass("error");
			emailInfo.removeClass("errortext");
			emailInput.addClass("success");
			emailInfo.addClass("successtext");
			emailInfo.text("");
			email = $("#email").val();
			}
		//if it's NOT valid
		else
			{
			emailInput.removeClass("success");
			emailInfo.removeClass("successtext");
			emailInput.addClass("error");
			emailInfo.addClass("errortext");
			emailInfo.text("Type in a valid email please.");
			return false;
			}
		});
// the fuck is this? -->	{
	// 	ssn = "";
	// 	var pattern = /^([0-6]\d{2}|7[0-6]\d|77[0-2])([ \-]?)(\d{2})\2(\d{4})$/;
	// 	if(pattern.test($("#ssn").val()))
	// 		{
	// 		ssnInput.removeClass("error");
	// 		ssnInfo.removeClass("errortext");
	// 		ssnInput.addClass("success");
	// 		ssnInfo.addClass("successtext");
	// 		ssnInfo.text("");
	// 		ssn = $("#ssn").val();
	// 		}
	// 	//if it's NOT valid
	// 	else
	// 		{
	// 		ssnInput.removeClass("success");
	// 		ssnInfo.removeClass("successtext");
	// 		ssnInput.addClass("error");
	// 		ssnInfo.addClass("errortext");
	// 		ssnInfo.text("Type in a valid SSN please.");
	// 		return false;
	// 		}
	// 	});
	$("#type").blur(function()
		{
		type = "";
		var pattern = /^\d{1}$/;
		if(pattern.test($("#type").val()))
			{
			typeInput.removeClass("error");
			typeInfo.removeClass("errortext");
			typeInput.addClass("success");
			typeInfo.addClass("successtext");
			typeInfo.text("");
			type = $("#type").val();
			}
		//if it's NOT valid
		else
			{
			typeInput.removeClass("success");
			typeInfo.removeClass("successtext");
			typeInput.addClass("error");
			typeInfo.addClass("errortext");
			typeInfo.text("Type in a valid type number please.");
			return false;
			}
		});
	$("#title").blur(function()
		{
		title = "";
		if($("#title").val() != "")
			{
			titleInput.removeClass("error");
			titleInfo.removeClass("errortext");
			titleInput.addClass("success");
			titleInfo.addClass("successtext");
			titleInfo.text("");
			title = $("#title").val();
			}
		//if it's NOT valid
		else
			{
			titleInput.removeClass("success");
			titleInfo.removeClass("successtext");
			titleInput.addClass("error");
			titleInfo.addClass("errortext");
			titleInfo.text("Type in a valid title please.");
			return false;
			}
		});
	$("#wage").blur(function()
		{
		wage = "";
		if((isNaN($("#wage").val()) == false) && $("#wage").val() != "")
			{
			wageInput.removeClass("error");
			wageInfo.removeClass("errortext");
			wageInput.addClass("success");
			wageInfo.addClass("successtext");
			wageInfo.text("");
			wage = $("#wage").val();
			}
		//if it's NOT valid
		else
			{
			wageInput.removeClass("success");
			wageInfo.removeClass("successtext");
			wageInput.addClass("error");
			wageInfo.addClass("errortext");
			wageInfo.text("Type in a valid wage rate please.");
			return false;
			}
		});
	$("#submit").click(function()
		{	
		netid = $("#netid").val();
		email = $("#email").val();
		type = $("#type").val();
		title = $("#title").val();
		wage = $("#wage").val();
		acct = $("#acct").val();
		var error = false;				   				   	
		if(netid == "")
			{
			netidInput.removeClass("success");
			netidInfo.removeClass("successtext");
			netidInput.addClass("error");
			netidInfo.addClass("errortext");
			netidInfo.text("Type in a valid netid please.");
			error = true;
			}
		if (email == "") 
		 	{
		 	emailInput.removeClass("success");
		 	emailInfo.removeClass("successtext");
		 	emailInput.addClass("error");
		 	emailInfo.addClass("errortext");
		 	emailInfo.text("Type in a valid email please.");
		 	error = true;
		 	}	
		if (type == "") 
			{
			typeInput.removeClass("success");
			typeInfo.removeClass("successtext");
			typeInput.addClass("error");
			typeInfo.addClass("errortext");
			typeInfo.text("Type in a valid type number please.");
			error = true;
			}
		if (title == "") 
			{
			titleInput.removeClass("success");
			titleInfo.removeClass("successtext");
			titleInput.addClass("error");
			titleInfo.addClass("errortext");
			titleInfo.text("Type in a valid title please.");
			error = true;
			}
		if (wage == "")
			{
			wageInput.removeClass("success");
			wageInfo.removeClass("successtext");
			wageInput.addClass("error");
			wageInfo.addClass("errortext");
			wageInfo.text("Type in a valid wage rate please.");
			error = true;
			}
		if (acct == "")
			{
			acctInput.removeClass("success");
			acctInfo.removeClass("successtext");
			acctInput.addClass("error");
			acctInfo.addClass("errortext");
			acctInfo.text("Type in a valid acct number please.");
			error = true;
			}
		if(error == false)
			{
			$(this).hide();
			$("#loading").append('<img src="images/loading.gif" alt="Loading" id="loading" />');
			
			$.post("data.php", { netid: netid, type: type, title: title, wage: wage, acct: acct, email: email}, function(data, textStatus)
				{
				$("#sendEmail").slideUp("normal", function() {				   
					$("#sendEmail").before('<h2>'+ textStatus +', refresh to edit again.</h2>');	
					$('#dialog').dialog("close");										
					});
   				});
			}
		return false;
		});			   
});
