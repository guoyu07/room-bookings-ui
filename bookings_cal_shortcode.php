<script src="//code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>

<script src='//cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
<script src='//cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/locale/en-gb.js'></script>

<script src='//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
<link rel='stylesheet' href='//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css'>
<link media='print' rel='stylesheet' href='//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.print.css'>

<?php
$url = get_option('api_url');
$username = get_option('username');
$password = get_option('password');
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
