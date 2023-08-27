<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

function toxicity_check($comment_id, $comment)
{
    $api_key = get_option('toxicity_plugin_api_key');
    $url = "https://api.toxicity.co.in/?sentence=".rawurlencode($comment)."&key=".rawurlencode($api_key);        
    $data = wp_remote_retrieve_body(wp_remote_get($url, ['timeout' => 60]));    
    $data = json_decode($data);

    if ($data != FALSE)
    {
        $identity_attack = intval($data->identity_attack);
        $insult = intval($data->insult);
        $obscene = intval($data->obscene);
        $severe_toxicity = intval($data->severe_toxicity);
        $sexual_explicit = intval($data->sexual_explicit);
        $threat = intval($data->threat);
        $toxicity = intval($data->toxicity);        
        
        add_comment_meta($comment_id, 'identity_attack', $identity_attack);
        add_comment_meta($comment_id, 'insult', $insult);
        add_comment_meta($comment_id, 'obscene', $obscene);
        add_comment_meta($comment_id, 'severe_toxicity', $severe_toxicity);
        add_comment_meta($comment_id, 'sexual_explicit', $sexual_explicit);
        add_comment_meta($comment_id, 'threat', $threat);
        add_comment_meta($comment_id, 'toxicity', $toxicity);
        
        if ($toxicity === 0) return 1; 
    }
    else
    {        
        return 0;
    }
}
?>