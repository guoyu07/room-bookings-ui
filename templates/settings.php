<div class="wrap">
	<h2>Room Bookings Setup</h2>
	<form method="post" action="options.php"> 
        <?php @settings_fields('room_bookings_ui-group'); ?>

        <table class="form-table">  
            <tr valign="top">
                <th scope="row"><label for="room_bookings_api_url">Bookings API url</label></th>
                <td><input type="text" name="room_bookings_api_url" id="room_bookings_api_url" value="<?php echo get_option('room_bookings_api_url'); ?>" /></td>
            </tr>
        </table> 

        <?php @submit_button(); ?>
    </form>
</div>