$(document).ready(function(){
	
	$(".button").click(function(){
		var attributeName = $(this).attr('id') + ".jpg";
		
		
		if($(this).attr('id') == "proj")	{
					$("#contentContainer").load("projector/projector_st.html");
			} else if($(this).attr('id') == "lights")	{
					$("#contentContainer").load("lights/lights_st.html");
			}else if($(this).attr('id') == "screens")	{
					$("#contentContainer").load("screens/screens_st.html");
			}else if($(this).attr('id') == "tuner")	{
					$("#contentContainer").load("tuner/tuner_st.html");
			}else if($(this).attr('id') == "audio")	{
					$("#contentContainer").load("audio/audio_st.html");
			}else if($(this).attr('id') == "proj3")	{
					$("#contentContainer").load("projector/proj3_st.html");
			}	
	});
	
	$(".button").mouseenter(function(){
		$(this).css("background-color", "yellow");
		$(this).css('cursor', 'pointer');
	});
	
	$(".button").mouseout(function(){
		$(this).removeAttr("style");
	});	
	
	$("#contentContainer").delegate('.button', 'click', function(){
		var attributeName = $(this).attr('id') + ".jpg";

		
	});

	$("#contentContainer").delegate('.button', 'mouseenter', function(){
		$(this).css("background-color", "yellow");
		$(this).css('cursor', 'pointer');
	});
	
	$("#contentContainer").delegate('.button', 'mouseout', function(){
		$(this).removeAttr("style");
	});
	
})
