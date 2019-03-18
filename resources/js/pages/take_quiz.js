$( function() {
	startTimer();

	// window.onbeforeunload = function() {
 //        return "Dude, are you sure you want to leave? Think of the kittens!";
 //    }
});

function startTimer() {
	var presentTime = $('#timer').val();
	var timeArray = presentTime.split(/[:]+/);
	var m = timeArray[0];
	var s = checkSecond((timeArray[1] - 1));
	if(s==59){m=m-1}
	//if(m<0){alert('timer completed')}

	$('#timer').val(m + ":" + s);
	setTimeout(startTimer, 1000);
}

function checkSecond(sec) {
	if (sec < 10 && sec >= 0) {sec = "0" + sec}; // add zero in front of numbers < 10
	if (sec < 0) {sec = "59"};
	return sec;
}