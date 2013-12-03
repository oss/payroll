$(document).ready(function(){
	$("#ws").change(function()
		{	
		var startDate = $('#startdate').val();	
		$.get("view.php", { startdate: startDate }, 
				  function(data, textStatus){
		    alert("Data Loaded: " + textStatus);
		  });
	});			   
});