<?php

require_once('config.php');

abstract class ItemType
{
    const Boards = "boards";
    const Lists = "lists";
    const Cards = "cards";
}

$listIdNameAssocaition = array();
$lists = array();
$cards = array();

function CURL_REQUEST($url){

    $handle = curl_init();

    curl_setopt_array($handle,
    array(
            CURLOPT_URL    => $url,
            CURLOPT_RETURNTRANSFER => true
        )
    );
    $response = array();
    $data = curl_exec($handle);

    $responseCode   = curl_getinfo($handle, CURLINFO_HTTP_CODE);

    if(curl_errno($handle))
    {
        print curl_error($handle);
    }
    else
    {
        if($responseCode == "200") {
            $response = json_decode($data,true);
        }
        curl_close($handle);
        return $response;
    }
    return;
}

function get_Board($board_id,$base_url, $api_key, $api_token){

    $params = array('fields' => 'name', 'lists' => 'open', 'list_fields' => 'all' , 'key' => $api_key, 'token' => $api_token);

    $url = $base_url . ItemType::Boards . '/' . $board_id . '?' . http_build_query($params);

    $curl_response = array();
    $curl_response = CURL_REQUEST($url);
    return $curl_response['lists'];
}

function get_AllCards($list,$base_url, $api_key, $api_token){

    $params = array('fields' => 'all', 'key' => $api_key, 'token' => $api_token);
    $url = $base_url . ItemType::Lists . '/' . $list['id'] . '/' . ItemType::Cards . '?' . http_build_query($params);
    $curl_response = array(); 
    $curl_reponse = CURL_REQUEST($url);
    return $curl_reponse;
}

$lists = get_Board("pMySil9r",$base_url, $API_KEY, $API_TOKEN);
echo "<pre>";
print_r($lists);

foreach($lists as $list){
    $listIdNameAssocaition[$list['id']] = $list['name'];
    array_push($cards, get_AllCards($list,$base_url, $API_KEY, $API_TOKEN));
}

print_r($listIdNameAssocaition);
echo "</pre>";
?>