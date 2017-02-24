<script src="//cdn.rawgit.com/indrimuska/angular-moment-picker/master/dist/angular-moment-picker.min.js"></script>
<link href="//cdn.rawgit.com/indrimuska/angular-moment-picker/master/dist/angular-moment-picker.min.css" rel="stylesheet">

<div class="container-fluid" ng-controller="createbooking">

<button ng-show="showButton" ng-click="showBookingForm()">Book a room</button>

<form class="form-horizontal" ng-submit="save()" ng-show="showForm">

	<div class="form-group">
		<label for="booking.BookerName" class="col-sm-2 control-label">Your Name</label>
		<div class="col-sm-10">
			<input class="form-control" type="text" ng-model="booking.BookerName" required>
		</div>
	</div>
	<div class="form-group">
		<label for="booking.BookerEmail" class="col-sm-2 control-label">Your Email Address</label>
		<div class="col-sm-10">
			<input class="form-control" type="email" ng-model="booking.BookerEmail" required>
		</div>
	</div>
	<div class="form-group">
		<label for="booking.BookerPhone" class="col-sm-2 control-label">Your Phone Number</label>
		<div class="col-sm-10">
			<input class="form-control" type="text" ng-model="booking.BookerPhone" required>
		</div>
	</div>
	<div class="form-group">
		<label for="booking.Title" class="col-sm-2 control-label">Event Name</label>
		<div class="col-sm-10">
			<input class="form-control" type="text" ng-model="booking.Title" required>
		</div>
	</div>
	<div class="form-group">
		<label for="booking.Id_Room" class="col-sm-2 control-label">Room</label>
		<div class="col-sm-10">
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
		<label for="Date" class="col-sm-2 control-label">Date</label>
		<div class="col-sm-10">
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
		<label for="booking.Start" class="col-sm-2 control-label">Start Time</label>
		<div class="col-sm-2">
			<select class="form-control" ng-model="booking.Start" ng-options="x for x in StartTimes" required>
				<option></option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="booking.Duration" class="col-sm-2 control-label">Duration (Hours)</label>
		<div class="col-sm-2">
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
		<div class="col-sm-10 col-sm-offset-2">
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
				fac.checked = $.inArray(f.Id_Facility, $scope.booking.facilities) >= 0; 
				facilities.push(fac);
			}
		}
		$scope.booking.facilities = [];
		$scope.facilities = facilities;
	};
	
	$scope.facChanged = function (facId, sel) {
		var currsel = $.inArray(facId, $scope.booking.facilities) >= 0; 
		if (!currsel && sel) {
			facilities = $scope.booking.facilities.push(facId);
		} else if (currsel && !sel) {
			$scope.booking.facilities = $.grep($scope.booking.facilities, function(value) {
				return value != facId;
			});
		}
	}

	$scope.showBookingForm = function () {
		$scope.showButton = false;
		$scope.showForm = true;
	};	
	
	$scope.facilities = [];
	
	$scope.StartTimes = [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23];
	$scope.Durations = [1, 2, 3, 4, 5, 6];
	
	$scope.showButton = true;
	$scope.showForm = false;	
		
	$http.get("<?php echo $url?>/wp_room_data.php")
		.then(function(response) {
			$scope.rooms = response.data;
			$scope.booking.Date = moment();
		});
		
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