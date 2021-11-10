<?php

add_shortcode('wadi-poll', 'display_poll_post_type');

function display_poll_post_type($atts)
{
    ob_start();
    ?>
    <h1>TEST Shortcode Poll</h1>
    <?php

    return ob_get_clean();
}