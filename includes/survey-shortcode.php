<?php

add_shortcode('survey', 'display_custom_post_type');

function display_custom_post_type($atts)
{

    $attributes = shortcode_atts(array(
        'id' => null,
        // 'class' => 'fas fa-pen',
    ), $atts);

    // $args = array(
    //     'post_type' => 'survey',
    //     'posts_per_page' => '1',
    //     'post_status' => 'publish',
    //     'post_id' => $attributes['id'],
    // );

    $string = '';
    // $query = new WP_Query($args);
    // if ($query->have_posts()) {
        $cmb2_TEST = get_post_meta($attributes['id'], 'survey_demo_textsmall', true);
        // echo $cmb2_TEST;
        $string .= '<div>';
        // while ($query->have_posts()) {
            // $query->the_post();
            // $string .= '<div>' . get_the_title($attributes['id']) . '</div>';
            $string .= '<div>' . get_post($attributes['id'])->post_content . '</div>';
            $string .= '<div>' . $cmb2_TEST . '</div>';
            $meta = get_post_meta(get_the_id(), '');
        // }
        $string .= '</div>';
        // }
        // wp_reset_postdata();
        return $string;
    // }
}
