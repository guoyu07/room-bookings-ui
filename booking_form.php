<script src="//cdn.rawgit.com/indrimuska/angular-moment-picker/master/dist/angular-moment-picker.min.js"></script>
<link href="//cdn.rawgit.com/indrimuska/angular-moment-picker/master/dist/angular-moment-picker.min.css" rel="stylesheet">

<?php
$blogurl = get_bloginfo('url');
?>

<link rel="stylesheet" href="<?php echo $blogurl;?>/wp-content/plugins/room_bookings_ui/bookingform.css"> 

<div class="container-fluid" ng-controller="createbooking">

<button ng-show="showButton" ng-click="showBookingForm()">Book a room</button>

<form class="room-booking" ng-submit="save()" ng-show="showForm">

	<div class="form-group">
		<label for="booking.BookerName" class="control-label">Your Name</label>
		<div>
			<input class="form-control" type="text" ng-model="booking.BookerName" required>
		</div>
	</div>
	<div class="form-group">
		<label for="booking.BookerEmail" class="control-label">Your Email Address</label>
		<div>
			<input class="form-control" type="email" ng-model="booking.BookerEmail" required>
		</div>
	</div>
	<div class="form-group">
		<label for="booking.BookerPhone" class="control-label">Your Phone Number</label>
		<div>
			<input class="form-control" type="text" ng-model="booking.BookerPhone" required>
		</div>
	</div>
	<div class="form-group">
		<label for="booking.Title" class="control-label">Event Name</label>
		<div>
			<input class="form-control" type="text" ng-model="booking.Title" required>
		</div>
	</div>
	<div class="form-group">
		<label for="booking.Id_Room" class="control-label">Room</label>
		<div>
			<select class="form-control" ng-model="booking.Id_Room" ng-change="newRoom(booking.Id_Room)" required>
				<option></option>
				<option ng_repeat="room in rooms" value="{{room.Id_Room}}">{{room.Name}}</option>
			</select>
			<span ng-repeat="fac in facilities">
				<br/><input type="checkbox" ng-change="facChanged(fac.Id_Facility, fac.checked)" ng-model="fac.checked">{{fac.Name}}
			</span>
		</div>
	</div>
	<div class="form-group">
		<label for="Date" class="control-label">Date</label>
		<div>
			<input moment-picker="booking.Date"
				class="form-control"
				locale="en-gb"
		     	format="LL"
		     	autoclose="true"
		     	today="true"
		     	start-view="month"
		     	ng-model="booking.Date"
		     	required>
		</div>
	</div>
	<div class="form-group">
		<label for="time-band" class="control-label"></label>
		<div>
			<input type="radio" ng-model="timeband" value="0" ng-change="setTime()">Morning 
		</div>
		<div>
			<input type="radio" ng-model="timeband" value="1" ng-change="setTime()">Afternoon 
		</div>
		<div>
			<input type="radio" ng-model="timeband" value="2" ng-change="setTime()">Evening
		</div>
	</div>
	<div class="form-group">
		<label for="booking.Start" class="control-label">Start Time</label>
		<div>
			<select class="form-control" ng-model="booking.Start" ng-options="x.hour as x.display for x in StartTimes" ng-change="checkDuration()" required>
				<option></option>
			</select>
		</div>
		<label for="booking.Duration" class="control-label">Duration (Hours)</label>
		<div>
			<select class="form-control" ng-model="booking.Duration" ng-options="x for x in Durations" required>
				<option></option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="booking.Notes" class="col-sm-2 control-label">Notes</label>
		<div class="col-sm-10">
			<textarea class="form-control vresize" style="resize:vertical" rows="8" ng-model="booking.Notes"></textarea>
		</div>
	</div>
	<div class="form-group">
		<div>
			<input class="btn btn-primary" type="submit" value="Send Booking">
		</div>
	</div>

</form>

</div>

<script>
app.controller("createbooking", function ($scope, $http, $window) {
	$scope.newRoom = function (roomId) {
		var room = null;
		for (var i = 0; i < $scope.rooms.length; i++) {
			if ($scope.rooms[i].Id_Room == roomId) {
				room = $scope.rooms[i];
			}
		};
		if (room == null) return new Array();
		var facilities = new Array();
		for (var i = 0; i < room.facilities.length; i++) {
			var f = room.facilities[i];
			if (f.Used == 1) {
				var fac = new Object();
				fac.Id_Facility = f.Id_Facility;
				fac.Name = f.Name;
				fac.checked = jQuery.inArray(f.Id_Facility, $scope.booking.facilities) >= 0; 
				facilities.push(fac);
			}
		}
		$scope.booking.facilities = [];
		$scope.facilities = facilities;
	};
	
	$scope.facChanged = function (facId, sel) {
		var currsel = jQuery.inArray(facId, $scope.booking.facilities) >= 0; 
		if (!currsel && sel) {
			facilities = $scope.booking.facilities.push(facId);
		} else if (currsel && !sel) {
			$scope.booking.facilities = jQuery.grep($scope.booking.facilities, function(value) {
				return value != facId;
			});
		}
	}

	$scope.showBookingForm = function () {
		$scope.showButton = false;
		$scope.showForm = true;
	};	
	
	$scope.facilities = [];

	$scope.StartTimes = [];
	for (var i = 9; i < 24; i++) {
		var t = { hour: i, display: moment({hour: i}).format('h A') };
		$scope.StartTimes.push(t);
	}
	$scope.Durations = [1, 2, 3, 4, 5, 6];
	
	$scope.booking = {};	
	
	$http.get("<?php echo $url?>/wp_timebands.php")
		.then(function(response) {
			$scope.timebands = response.data;
			$scope.timeband = "2";
			$scope.setTime();
		});
			
	$scope.showButton = true;
	$scope.showForm = false;	
		
	$http.get("<?php echo $url?>/wp_room_data.php")
		.then(function(response) {
			$scope.rooms = response.data;
		});
		
	$scope.setTime = function () {
		$scope.booking.Start = $scope.timebands[$scope.timeband][0];
		$scope.booking.Duration = $scope.timebands[$scope.timeband][1];
	};
	
	$scope.checkDuration = function () {
		if ($scope.booking.Start + $scope.booking.Duration > 24) {
			$scope.booking.Duration = 24 - $scope.booking.Start;
		}
	};

	$scope.save = function () {
		$http.post("<?php echo $url?>/wp_add_booking.php", $scope.booking)
			.then(function (response) {
				$scope.postresult = response.data;
				if ($scope.postresult.success) {
					alert("Your booking has been recorded. You should receive an email about it soon.");
					$window.location.reload();
				} else {
					alert("There was a problem recording your booking");
				}
			}, function (response) {
				alert("There was a problem sending your booking");
				$scope.postresult = response;
			});
	};
		
});

</script>