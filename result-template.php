<?php
/* Template Name: Results Template */

get_header();

$site_url = isset($_GET['site']) ? urldecode($_GET['site']) : '';

if ($site_url) {
    // Perform your logic here based on $site_url
    echo '<h2>Results for: ' . esc_html($site_url) . '</h2>';
    // Display the results as needed
} else {
    echo '<p>No site URL provided.</p>';
}

get_footer();
?>
