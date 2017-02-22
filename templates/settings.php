<div class="wrap">
	<h2>Room Bookings Setup</h2>
	<form method="post" action="options.php"> 
        <?php @settings_fields('room_bookings_ui-group'); ?>
        <?php @do_settings_fields('room_bookings_ui'); ?>

        <table class="form-table">  
            <tr valign="top">
                <th scope="row"><label for="api_url">Bookings API url</label></th>
                <td><input type="text" name="api_url" id="api_url" value="<?php echo get_option('api_url'); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="username">API Username</label></th>
                <td><input type="text" name="username" id="username" value="<?php echo get_option('username'); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="password">API Password</label></th>
                <td><input type="text" name="password" id="password" value="<?php echo get_option('password'); ?>" /></td>
            </tr>
        </table> 

        <?php @submit_button(); ?>
    </form>
</div>