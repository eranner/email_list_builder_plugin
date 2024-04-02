<?php
// Load WordPress
define('WP_USE_THEMES', false);
require_once('../../../wp-load.php');

if(isset($_POST['id']) ) {
    // Validate and sanitize the email ID
    $emailId = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if($emailId > 0) {
        // Update 'contacted' field to true for the specified email ID
        global $wpdb;
        $table_name = $wpdb->prefix . 'email_list';
        $wpdb->update(
            $table_name,
            array('contacted' => 1),
            array('id' => $emailId),
            array('%d'),
            array('%d')
        );

        // Redirect back to email list page
        wp_redirect(admin_url('admin.php?page=email-list-data'));
        exit;
    }
}

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize the form data
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $address = isset($_POST['address']) ? sanitize_text_field($_POST['address']) : '';
    $city = isset($_POST['city']) ? sanitize_text_field($_POST['city']) : '';
    $state = isset($_POST['state']) ? sanitize_text_field($_POST['state']) : '';
    $zip = isset($_POST['zip']) ? sanitize_text_field($_POST['zip']) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';


    if (!empty($name) && !empty($email)) {
        // Insert data into the table
        global $wpdb;
        $table_name = $wpdb->prefix . 'email_list';
        $wpdb->insert(
            $table_name,
            array(
                'name' => $name,
                'email' => $email,
                'address'=>$address,
                'city' => $city,
                'state' => $state,
                'zip' => $zip,
                'phone' => $phone
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            )
        );
        // Return response
        echo json_encode(array('success' => true, 'message' => 'Data saved successfully!'));
    } else {
        // Return error response if required fields are empty
        // echo json_encode(array('success' => false, 'message' => 'Name and email are required fields.'));
        echo json_encode(['name' => $name, 'email' => $email]);
    }
} else {
    // Return error response for non-POST requests
    echo json_encode(array('success' => false, 'message' => 'Invalid request method.'));
}
?>