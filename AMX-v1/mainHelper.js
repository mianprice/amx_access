var state = {
	"audio.jpg":false,
	"lights.jpg":false,
	"allOn.jpg":false,
	"nightLight.jpg":false,
	"allOff.jpg":false,
	"brightRoom.jpg":false,
	"mediumRoom.jpg":false,
	"projectionPreset.jpg":false,
	"wallCamsOn.jpg":false,
	"boardFluoroOn.jpg":false,
	"roomFluoroOn.jpg":false,
	"podiumOn.jpg":false,
	"walCamsOff.jpg":false,
	"boardFluoroOff.jpg":false,
	"roomFluoroOff.jpg":false,
	"podiumOff.jpg":false,
	"proj.jpg":false,
	"powerOff.jpg":false,
	"powerOn.jpg":false,
	"imageBlank.jpg":false,
	"PC1VGA1400x1050.jpg":false,
	"docCam.jpg":false,
	"laptopVGA1400x1050.jpg":false,
	"video.jpg":false,
	"laptopDVI.jpg":false,
	"up.jpg":false,
	"stop.jpg":false,
	"down.jpg":false,
	"systemReset.jpg":false
}

var clicksTracked = [];
var tracking = false;
var clickString;
var userSessionID;

function getCookie(name) {
	var value = "; " + document.cookie;
	var parts = value.split("; " + name + "=");
	if (parts.length == 2) return parts.pop().split(";").shift();
}

//Functions for users
function createUser() {
	var uname = prompt("Onyen: ");
	var pword = prompt("Password: ");
	var pwordCheck = prompt("Re-enter password: ");
	if (pword===pwordCheck) {
		var jqxhr = jQuery.ajax({
			type: "POST",
		   	url: "AJAXhandler.php",
		   	data:{username: uname, password: pword, tag: "create_user"}
		});
		
		jqxhr.done(function(data) {
			userSessionID = data;
			toggleUserButtons();
			toggleTrackButtons();
		});
	}
}

function login() {
	var uname = prompt("Onyen: ");
	var pword = prompt("Password: ");
	var jqxhr = jQuery.ajax({
		type: "POST",
	   	url: "AJAXhandler.php",
	  	data:{username: uname, password: pword, tag: "login"},
	});
	jqxhr.done(function(data) {
			userSessionID = data;
			toggleUserButtons();
			toggleTrackButtons();
			updatePresets();
		});
}

function logout() {
	var jqxhr = jQuery.ajax({
		type: "POST",
	   	url: "AJAXhandler.php",
	  	data:{tag: "logout"}
	});
	jqxhr.done(function(data) {
			if (data = "y") {
				location.reload();
			}	
	});
}

function pushClick(event) {
	clicksTracked.push(event.target.id);
}

function trackStart() {
	if (!tracking) {
		tracking = true;
		$('.trackable').on( 
			"click", function(event) {
				pushClick(event);
		});
	}
}

function trackEnd() {
	if (tracking) {
		$('.trackable').off("click");
		tracking = false;
		var name = prompt("Name of preset: ");
		if (name) {
			finishPreset(name);
		} 
	}
}

function finishPreset(name) {
	var n = name;
	clickString = clicksTracked.join("_");
	console.log(clickString);
	var id = getCookie("amxCookie");
	var jqxhr = jQuery.ajax({
		type: "POST",
	   	url: "AJAXhandler.php",
		data:{uid: userSessionID, name: n, p_arr_str: clickString, tag: "finish_preset"},
	});
	jqxhr.done(function(data) {
			updatePresets();
	});
}

function updatePresets() {
	if (userSessionID) { 
	var jqxhr = jQuery.ajax({
		type: "POST",
	   	url: "AJAXhandler.php",
		data: {uid: getCookie("amxCookie"), tag: "update_preset"},
		dataType: "json"
	});
	jqxhr.done(function(data) {
		var i = data;
		$('#presetList').empty();		
		$.each(i, function(index, value) {
			checkPreset(value);
		});
	});
	}
}

function checkValues(array) {
	var i = array.length;
	for (j=0;j<i;j++) {
		updatePresetList(data[j]);
	}
}

function updatePresetList(index, name) {
	$('#presetList').append("<li>Preset: " + name + "</dt><dd><button id='p_" + index + "' onclick='choosePreset(" + index + ")'>Choose this preset</button></div></dd>");
}

function checkPreset(j) {
	findPreset(j);
}

function findPreset(d) {
	//POST using preset_id to call 	
	var jqxhr = jQuery.ajax({
		type: "POST",
	   	url: "AJAXhandler.php",
		data:{p_id: d, tag: "find_preset"},
	});
	jqxhr.done(function(data) {
		var i = $.parseJSON(data);
		updatePresetList(i[0], i[1]);
	});
	
}

function choosePreset(i) {
	//POST using event to call 
	var d = i;
	var jqxhr = jQuery.ajax({
		type: "POST",
	   	url: "AJAXhandler.php",
		data:{p_id: d, tag: "use"},
	});
	jqxhr.done(function(data) {
		var str = $.parseJSON(data);
		parsePreset(str);
	});
}

function parsePreset(str) {
	var idArray = str.split("_");
	var x = idArray.length;
	idArray.forEach(function(element, index, array) {
		setTimeout(function(element) {
			var jqxhr = jQuery.ajax({
				type: "POST",
		   		url: "sikuliInterface.php",
		   		data:{data: element}
		});
		}, 2000);
	});
}

function trackerSet(attributeName) {
	clicksTracked.push(attributeName);
	var jqxhr = jQuery.ajax({
		type: "POST",
	   	url: "sikuliInterface.php",
	   	data:{data: attributeName}
	});


	jqxhr.success(function(){
		setButtonClickReturn(true);
	});

	jqxhr.fail(function(){
		setButtonClickReturn(false);
	});
}

//Button Management
function initButtons() {
	$('#logout').hide();
	$('#trackButtons').hide();
	$('#eventTrackers').hide();
}

function toggleUserButtons() {
	$('#createUser').toggle();
	$('#login').toggle();
	$('#logout').toggle();
}

function toggleTrackButtons() {
	$('#trackButtons').toggle();
	$('#eventTrackers').hide();
}

function toggleEventTrackers() {
	$('trackStart').toggle();
	$('trackEnd').toggle();
}

//example of a callback function that might be passed into a test of one of the functions
function successCallBack(responseObj){
	if(responseObj != undefined && responseObj != null){
		return true;}
	else{
		return false;
}}

//variables for unit tests initialized on doc ready
var buttonClickReturn;
var buttonClickInContainerReturn;

//setters and getters for global test variables
function setButtonClickReturn(set){
	buttonClickReturn = set;
}
function getButtonClickReturn(){
	return buttonClickReturn;
}

function setButtonClickInContainerReturn(set){
	buttonClickInContainerReturn = set;
}
function getButtonClickInContainerReturn(){
	return buttonClickInContainerReturn;
}

//corresponding variable is buttonClickReturn
function buttonClick() {

	$(".button").click(function()
		{


			var attributeName = $(this).attr('id');
			state[attributeName] = !state[attributeName];
			//alert("click event on button " + attributeName + " state: " + state[attributeName]);


			if($(this).attr('id') == "proj")	{
					$("#contentContainer").load("projector/projector.html .container");
					$("#containerStyle").attr("href","projector/projector.css");
			} else if($(this).attr('id') == "lights")	{
					$("#contentContainer").load("lights/lights.html .container");
					$("#containerStyle").attr("href","lights/lights.css");
			}else if($(this).attr('id') == "screens")	{
					$("#contentContainer").load("screens/screens.html .container");
					$("#containerStyle").attr("href","screens/screens.css");
			}else if($(this).attr('id') == "tuner")	{
					$("#contentContainer").load("tuner/tuner.html .container");
					$("#containerStyle").attr("href","tuner/tuner.css");
			}else if($(this).attr('id') == "audio")	{
					$("#contentContainer").load("audio/audio.html .container");
					$("#containerStyle").attr("href","audio/audio.css");
			}else if($(this).attr('id') == "proj3")	{
					$("#contentContainer").load("projector/proj3.html .container");
					$("#containerStyle").attr("href","projector/projector.css");
			}
						
			trackerSet(attributeName);

		});


}


function dynamicButtonColor(){
	$(".button").mouseenter(function(){
		$(this).css("background-color", "yellow");
		$(this).css('cursor', 'pointer');
	});
}

function removeDynamicButtonColor(){
	$(".button").mouseout(function(){
		$(this).removeAttr("style");
	});
}




function buttonClickInContainer(){

	$("#contentContainer").delegate('.button', 'click', function(){
		var attributeName = $(this).attr('id');
		state[attributeName] = !state[attributeName];
		//alert("click event on button " + attributeName + " state: " + state[attributeName]);
		
		trackerSet(attributeName);
	});

}


function dynamicButtonColorInContainer(){
	$("#contentContainer").delegate('.button', 'mouseenter', function(){
		$(this).css("background-color", "yellow");
		$(this).css('cursor', 'pointer');
	});

}

function removeDynamicButtonColorInContainer(){
	$("#contentContainer").delegate('.button', 'mouseout', function(){
		$(this).removeAttr("style");
	});
}

$(document).ready(function(){	
	setButtonClickReturn("NOT EXPECTED: var 1");
	
	setButtonClickInContainerReturn("NOT EXPECTED: var 2");
	
	initButtons();

	buttonClick();

	dynamicButtonColor();

	removeDynamicButtonColor();

	buttonClickInContainer();

	dynamicButtonColorInContainer();

	removeDynamicButtonColorInContainer();
})
