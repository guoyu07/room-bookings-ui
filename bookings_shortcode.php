<script src="//code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>

<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.1/angular.min.js"></script>
<script src='//cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
<script src='//cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/locale/en-gb.js'></script>

<link rel="stylesheet" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
<script type="text/javascript" src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.10.13/sorting/datetime-moment.js"></script>
	
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular-datatables/0.6.0/angular-datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/angular-datatables/0.6.0/css/angular-datatables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/angular-datatables/0.6.0/plugins/bootstrap/angular-datatables.bootstrap.min.js">



<div ng-cloak class="container-fluid" ng-app="bookings">
<div ng-controller="bookingsctl">

<b>{{failure}}</b>

<table id="table" class="table table-hover" datatable="ng" dt-options="dtOptions" dt-column-defs="dtColumnDefs">
	<thead>
	<tr>
		<th>Date</th>
		<th>Time</th>
		<th></th>
		<th>Room</th>
	</tr>
	</thead>
	
	<tbody>
	
	<tr ng-repeat="booking in bookings">
		<td>{{displayDate(booking)}}</td>
		<td>{{displayTime(booking)}}</td>
		<td>{{booking.Date}}</td>
		<td>{{booking.RoomName}}<span ng-show="booking.Provisional==1"><br>(Provisional)</span></td>
	</tr>
	</tbody>
</table>
</div>

<?php
$url = get_option('room_bookings_api_url');
?>

<script>
var app = angular.module("bookings", ['datatables', 'moment-picker']);
app.controller("bookingsctl", function($scope, $http) {

	$scope.dtOptions = {};
	$scope.dtColumnDefs = [
		{ 'orderData': [2], 'targets': [0]},
		{ 'sortable': false, 'targets': [1]},
		{ 'visible': false, 'targets': [2]}
	];
	
	
	$http.get("<?php echo $url?>/wp_bookings.php")
		.then(function(response) {
			$scope.bookings = response.data;
		}, function(response) {
			$scope.failure = "The bookings plugin settings don't appear to be correct";
		});
	
	$scope.displayDate = function(booking) {
		return moment(booking.Date, 'YYYY-MM-DD').format('ddd D MMM YYYY');
	};
		
	$scope.displayTime = function(booking) {
		var start = moment({hour: booking.Start});
		var end = moment({hour: booking.Start + booking.Duration}); 
		return start.format('h A') + '-' + end.format('h A'); 
	};
});
</script>

<?php include('booking_form.php'); ?>

</div>
