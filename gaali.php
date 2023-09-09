<?php
/*
Plugin Name: Gaali
Version: 1.0
Plugin URI: https://gaali.in
Description: Toxicity detect for Comments
Author: Anjanesh Lekshminarayanan
Author URI: https://anjanesh.consulting
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

const BOOLEANS = ['false', 'true'];
const BOOLEANS_YN = ['No', 'Yes'];

// Include your custom function file
require_once plugin_dir_path(__FILE__) . 'includes/functions.php';

function toxicity_plugin_register_settings()
{
    // Register a new setting for the plugin
    register_setting('toxicity_plugin_options', 'toxicity_plugin_api_key', [
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'show_in_rest' => true,
    ]);
}
add_action('admin_init', 'toxicity_plugin_register_settings');

function toxicity_plugin_settings_page()
{
    // Render the settings page HTML
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Gaali Settings', 'my-toxicity'); ?></h1>
        <div style="display:flex; margin-top:20px">
            <div>
                In a future upate you can get your API Keys at <a href="https://gaali.in" target="_blank">gaali.in</a>.<br/>
                For now API Key is not required as it's in intial stages and only if there are many users or toxicity calls will I require you to use API key.<br/>
                This is going to be a free plugin forever anyway.
            </div>
        </div>
        <form method="post" action="options.php">
            <?php settings_fields('toxicity_plugin_options'); ?>
            <?php do_settings_sections('toxicity_plugin_options'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('API Key', 'my-plugin'); ?></th>
                    <td><input type="text" name="toxicity_plugin_api_key" value="<?php echo esc_attr(get_option('toxicity_plugin_api_key')); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function toxicity_plugin_add_settings_page()
{
    // Add a new settings page for the plugin
    add_options_page(__('My Gaali Settings', 'my-toxicity'), __('Gaali', 'my-toxicity'), 'manage_options', 'toxicity_plugin_options', 'toxicity_plugin_settings_page');
}
add_action('admin_menu', 'toxicity_plugin_add_settings_page');

# Comment Post
add_action('comment_post', 'toxicity_schedule_check', 10, 2);
function toxicity_schedule_check($comment_id, $comment_approved)
{    
    # error_log('toxicity_approve_function called with comment ID ' . $comment_id);
    
    # Run scheduled event in 5 seconds to check for toxicity
    wp_schedule_single_event(time() + 5, 'toxicity_comment_approve_event', ['comment_id' => $comment_id]);
    
    # error_log('Comment with ID ' . $comment_id . ' was posted with status ' . $comment_approved);    
}

add_action('add_meta_boxes_comment', 'toxicity_extend_comment_add_meta_box');
function toxicity_extend_comment_add_meta_box()
{
    add_meta_box('title', __( 'Comment Metadata - Toxicity' ), 'toxicity_extend_comment_meta_box', 'comment', 'normal', 'high');
}

function toxicity_extend_comment_meta_box($comment)
{
    $identity_attack = get_comment_meta($comment->comment_ID, 'identity_attack', true);
    $insult = get_comment_meta($comment->comment_ID, 'insult', true);
    $obscene = get_comment_meta($comment->comment_ID, 'obscene', true);
    $severe_toxicity = get_comment_meta($comment->comment_ID, 'severe_toxicity', true);
    $sexual_explicit = get_comment_meta($comment->comment_ID, 'sexual_explicit', true);
    $threat = get_comment_meta($comment->comment_ID, 'threat', true);
    $toxicity = get_comment_meta($comment->comment_ID, 'toxicity', true);

    wp_nonce_field( 'extend_comment_update', 'extend_comment_update', false );
    ?>    
    <table cellspacing="5">
        <tbody>
        <tr>
            <th style="text-align:left"><?php esc_html_e('Identity Attack'); ?></th>
            <td><?php echo esc_attr($identity_attack); ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php esc_html_e('Insult'); ?></th>
            <td><?php echo esc_attr($insult); ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php esc_html_e('Obscene'); ?></th>
            <td><?php echo esc_attr($obscene); ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php esc_html_e('Severe Toxicity'); ?></th>
            <td><?php echo esc_attr($severe_toxicity); ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php esc_html_e('Sexual Explicit'); ?></th>
            <td><?php echo esc_attr($sexual_explicit); ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php esc_html_e('Threat'); ?></th>
            <td><?php echo esc_attr($threat); ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php esc_html_e('Toxicity'); ?></th>
            <td><?php echo esc_attr($toxicity); ?></td>
        </tr>
        </tbody>
    </table>
    <?php
}

add_filter('comment_text', 'toxicity_data');
function toxicity_data($text)
{    
    if (is_admin())
    {        
        if (get_comment_meta(get_comment_ID(), 'toxicity'))
        {            
            $identity_attack = intval(get_comment_meta(get_comment_ID(), 'identity_attack', true));    
            $insult = intval(get_comment_meta(get_comment_ID(), 'insult', true));
            $obscene = intval(get_comment_meta(get_comment_ID(), 'obscene', true));
            $severe_toxicity = intval(get_comment_meta( get_comment_ID(), 'severe_toxicity', true));
            $sexual_explicit = intval(get_comment_meta(get_comment_ID(), 'sexual_explicit', true));
            $threat = intval(get_comment_meta(get_comment_ID(), 'threat', true));
            $toxicity = intval(get_comment_meta(get_comment_ID(), 'toxicity', true));            
            
            $text .= '<table class="tbl-toxicity">';        
            $text .= '<tr><th class='.(BOOLEANS[(bool)$toxicity]).'>Toxicity : </th><td colspan="3">'.BOOLEANS_YN[(bool)$toxicity].'</td></tr>';
            $text .= '<tr><th class='.(BOOLEANS[(bool)$identity_attack]).'>Identity Attack : </th><td>'.BOOLEANS_YN[(bool)$identity_attack].'</td>';
            $text .= '<th class='.(BOOLEANS[(bool)$insult]).'>Insult : </th><td>'.BOOLEANS_YN[(bool)$insult].'</td></tr>';
            $text .= '<tr><th class='.(BOOLEANS[(bool)$obscene]).'>Obscene : </th><td>'.BOOLEANS_YN[(bool)$obscene].'</td>';
            $text .= '<th class='.(BOOLEANS[(bool)$severe_toxicity]).'>Severe Toxicity : </th><td>'.BOOLEANS_YN[(bool)$severe_toxicity].'</td></tr>';
            $text .= '<tr><th class='.(BOOLEANS[(bool)$sexual_explicit]).'>Sexual Explicit : </th><td>'.BOOLEANS_YN[(bool)$sexual_explicit].'</td>';
            $text .= '<th class='.(BOOLEANS[(bool)$threat]).'>Threat : </th><td>'.BOOLEANS_YN[(bool)$threat].'</td></tr>';        
            $text .= '</table>';      
        }
    }

    return $text;
}

function toxicity_styles()
{
    wp_enqueue_style('toxicity',  plugin_dir_url( __FILE__ ) . 'assets/css/styles.css');
}
add_action('admin_enqueue_scripts', 'toxicity_styles');

add_action('toxicity_comment_approve_event', 'toxicity_approve_function', 10, 1);

// Schedule the event to run your custom function with a specific comment ID
function toxicity_approve_function($comment_id)
{
    $comment = get_comment($comment_id);
    
    # error_log('toxicity_approve_function called with comment ID ' . $comment_id);
    
    // Run your machine learning code to check the validity of the comment
    $is_valid = toxicity_check($comment_id, $comment->comment_content);
    
    // If the comment is valid, approve it and add meta data
    if ($is_valid)
    {
        // Approve the comment
        wp_set_comment_status($comment_id, 'approve');
    }
}
?>