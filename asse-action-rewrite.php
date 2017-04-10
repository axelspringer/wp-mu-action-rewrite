<?php

// @codingStandardsIgnoreFile

/**
 * Sets new attachment link.
 *
 * @param $link
 * @param $postId
 *
 * @return string|void
 */
function setAttachmentLink($link, $postId)
{
    $post = get_post($postId);
    return home_url('media/' . $post->post_name);
}
add_action('attachment_link', 'setAttachmentLink', 10, 2);

/**
 * Flushes rewrite rules if custom rule does not exist.
 */
function flushRewriteRules()
{
    $rules = get_option('rewrite_rules');

    if (is_array($rules) && !isset($rules['media/([^/]+)/?$'])) {
        /** @global WP_Rewrite $wp_rewrite */
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }
}
add_action('wp_loaded', 'flushRewriteRules');

/**
 * Generates new rewrite rules.
 *
 * @param WP_Rewrite $wp_rewrite
 */
function generateRewriteRules(WP_Rewrite $wp_rewrite)
{
    $rules = array();
    $rules['media/([^/]+)/?$'] = 'index.php?attachment=$matches[1]';
    $wp_rewrite->rules = $rules + $wp_rewrite->rules;
}
add_action('generate_rewrite_rules', 'generateRewriteRules');
