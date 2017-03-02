<script src="//code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>

<script src='//cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
<script src='//cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/locale/en-gb.js'></script>

<script src='//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
<link rel='stylesheet' href='//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css'>
<link media='print' rel='stylesheet' href='//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.print.css'>

<?php
$url = get_option('room_bookings_api_url');
?>

<script type='text/javascript'>
$(document).ready(function() {
	$('#calendar').fullCalendar({
		header: {
			left: 'title',
			center: '',
			right: 'today agendaWeek,month prev,next'
		},
		events: {
			url: '<?php echo $url?>/wp_calbookings.php',
			type: 'GET' 
		},
		eventMouseover: function (calEvent, jsEvent) {
			var start = moment(calEvent.start);
			var end = moment(calEvent.end);
			var msg = start.format('DD MMM YYYY HA') + '-' + end.format('HA') + '<br/>' + calEvent.title;
			if (calEvent.provisional) msg += "<br/>(Provisional)";
			var tooltip = '<div class="tooltipevent" style="background:#ccc;position:absolute;z-index:10001;">' + msg + '</div>';
			$("body").append(tooltip);
    		$(this).mouseover(function(e) {
		        $(this).css('z-index', 10000);
		        $('.tooltipevent').fadeIn('500');
		        $('.tooltipevent').fadeTo('10', 1.9);
		   }).mousemove(function(e) {
		        $('.tooltipevent').css('top', e.pageY + 10);
		        $('.tooltipevent').css('left', e.pageX + 20);
		   });
		},
		eventMouseout: function(calEvent, jsEvent) {
		     $(this).css('z-index', 8);
		     $('.tooltipevent').remove();
		},
		/*dayClick: function (date, jsEvent, view) {
			var d = date.format('DD-MM-YYYY');
			var t = date.get('hour');
			var msg = "Create a new booking for "+d;
			if (t > 0) msg += " at " + date.format("kk:mm:ss");
			if (confirm(msg+"?")) {
				var url = "booking.php?date="+date.format('YYYY-MM-DD');
				if (t > 0) url += "&time="+t;
				location.href = url;
			}
		},*/
		minTime: "09:00:00",
		allDaySlot: false,
		slotDuration: "01:00:00"
	});
});
</script>


<div class="container-fluid">
<div id="calendar"></div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.1/angular.min.js"></script>
<script src='//cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
<script src='//cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/locale/en-gb.js'></script>

<div ng-cloak class="container-fluid" ng-app="bookings">

<script>
var app = angular.module("bookings", ['moment-picker']);
</script>

<?php include('booking_form.php'); ?>

</div>