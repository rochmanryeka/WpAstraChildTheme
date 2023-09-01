<?php

// add custom rss feed template
function custom_rss_feed() {
    add_feed('custom-rss', 'custom_rss_template');
}
add_action('init', 'custom_rss_feed');

function custom_rss_template() {
    include(get_stylesheet_directory() . '/custom-rss-feed.php');
}
