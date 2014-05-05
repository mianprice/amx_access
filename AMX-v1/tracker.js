function trackerSet() {
	$(".button").on("click", function() {
		var attributeName = $(this).attr('id');
		clicksTracked.push(attributeName);
	});
}

$(document).ready(function(){
	$(".button").attr("onclick",trackerSet());
})
