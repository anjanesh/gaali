<?php
function toxicity_check($comment_id, $comment)
{
    $api_key = get_option('toxicity_plugin_api_key');
    $url = "https://api.toxicity.co.in/?sentence=".rawurlencode($comment)."&key=".rawurlencode($api_key);
    $data = get_file_contents($url, 3);    

    if ($data !== FALSE)
    {
        $data = json_decode($data);        

        $identity_attack = intval((bool)$data->identity_attack);
        $insult = intval((bool)$data->insult);
        $obscene = intval((bool)$data->obscene);
        $severe_toxicity = intval((bool)$data->severe_toxicity);
        $sexual_explicit = intval((bool)$data->sexual_explicit);
        $threat = intval((bool)$data->threat);
        $toxicity = intval((bool)$data->toxicity);
        
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

function get_file_contents($url, $totalTries = 5)
{
    $Tries = 0;
    do
        {
            if ($Tries > 0) sleep(1); # Wait for a sec before retrieving again
            $contents = @file_get_contents($url);
            $Tries++;
        } while ($Tries <= $totalTries && $contents === FALSE);
        if ($contents == "") $contents = FALSE;
        return $contents;
}
?>