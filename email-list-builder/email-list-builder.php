<?php
/** 
 * Plugin Name: Email List Builder
 * Description: A shortcode form that will build an email list
 * Author: Eric Ranner
 * Version: 1.0
 * **/
// Create table on plugin activation
function my_plugin_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'email_list';
    
    // Check if the table exists
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        // Table does not exist, create it
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(50) NOT NULL,
            email varchar(100) NOT NULL,
            address varchar(255) NOT NULL,
            city varchar(50) NOT NULL,
            state varchar(50) NOT NULL,
            zip varchar(50) NOT NULL,
            phone varchar(50) NOT NULL,
            contacted tinyint(1) NOT NULL DEFAULT 0,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
register_activation_hook(__FILE__, 'my_plugin_create_table');

function my_plugin_deactivate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'email_list';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}
register_deactivation_hook(__FILE__, 'my_plugin_deactivate');
 function email_list_builder_shortcode(){
    $data = '';
    $data .= '  
    <div class="container" style="padding:40px 20px 40px 20px; background-color:white; width: 80%; border-radius: 5px;">
        <h2 class="edgeHeader" style="text-align:center;" id="email_builder_header">Not sure if hosting Corporate Retreats is right for you?</h2>
        <h3 class="clientTagline" style="text-align:center; padding-bottom: 20px;" id="email_builder_tagline">Provide your name and email address in the form below and we will send you a free assessment test!</h3>
        <form id="email-form" style="display:flex; justify-content: center; max-width: 800px; margin:auto;" id="email_list_form">
            <input class="form-control me-1"type="text" name="name" placeholder="Your Name" id="first_input">
            <input class="form-control me-1"type="email" name="email" placeholder="Your Email" id="second_input">
            <input type="submit" value="Subscribe" class="btn btn-warning" id="email_list_builder_button">
        </form>
        <div style="color: red; opacity:0; text-align: center; padding-top:10px;" id="email_list_error_message">Please enter your name and email address</div>
    </div>';
    return $data;
 }

 function load_email_list_builder_scripts() {
    $plugin_path = plugins_url('js/email_builder_script.js', __FILE__);
    $php_path = plugins_url('email-list-builder/handle_email_builder_data.php');
    wp_enqueue_script('email_builder_js', $plugin_path, array('jquery'), '1.0', true);

    wp_localize_script('email_builder_js', 'emailBuilderData', array(
        'plugin_path' => $php_path
    ));
}
add_action('wp_enqueue_scripts', 'load_email_list_builder_scripts');

 add_shortcode('email_list_builder', 'email_list_builder_shortcode');
 add_action('init', 'email_list_builder_shortcode');
 add_action('wp_enqueue_scripts', 'load_email_list_builder_scripts');

 function my_plugin_menu() {
    add_menu_page(
        'Email List Data',    // Page title
        'Email List Data',    // Menu title
        'manage_options',     // Capability required to access the page
        'email-list-data',    // Menu slug (should be unique)
        'my_plugin_data_page',
        'dashicons-email-alt' // Callback function to display the page content
    );
}
add_action('admin_menu', 'my_plugin_menu');

// Display email list data on admin page
function my_plugin_data_page() {
    global $wpdb;

    // Retrieve data from the wp_email_list table
    $table_name = $wpdb->prefix . 'email_list';
    $query = "SELECT * FROM $table_name";
    $results = $wpdb->get_results($query);

    // Display the retrieved data
    echo '<div>';
    echo '<h1>Email List Data</h1>';
    
    if ($results) {
        echo '<div style="display:flex; justify-content:center;">';
        echo '<table style="width: 80%;">';
        echo '<tr style="font-size: 1.5rem;"><th>Name</th><th>Email</th><th>Address</th><th>City</th><th>State</th><th>Zip Code</th><th>Phone #</th><th>Contacted</th></tr>';
        
        foreach ($results as $key => $row) {
            echo '<tr style="font-size: 1.3rem;';
            if ($key % 2 == 0) {
                echo 'background: #D7D7D7 ;">';
            } else {
                echo '">';
            }

            if($row->contacted == 0){
                echo '<td style="padding: 5px">' . esc_html($row->name) . '</td>';
                echo '<td><a href="mailto:'.$row->email.'">' . esc_html($row->email) . '</td>';
                echo '<td>'. esc_html($row->address) . '</td>';
                echo '<td>'. esc_html($row->city) . '</td>';
                echo '<td>'. esc_html($row->state) . '</td>';
                echo '<td>'. esc_html($row->zip) . '</td>';
                echo '<td>'. esc_html($row->phone) . '</td>';
                // Display link to update 'contacted' status
                echo '<td><form method="POST" action="'.esc_url( plugins_url( "handle_email_builder_data.php", __FILE__ ) ).'"><input type="submit" value="Contacted"> <input type="hidden" name="id" value="'. $row->id.'"></form></td>';
                echo '</tr>'; 
            } else {
                echo '';
            }

        }
        
        echo '</table>';
    } else {
        echo '<p>No data found.</p>';
    }
    echo '</div>';
    echo '</div>';
}
