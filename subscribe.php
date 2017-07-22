<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'GetResponseAPI3.class.php';

//GetResponse auth data
$getresponse = new GetResponse('YOUR-API-KEY-HERE');
$domain = $getresponse->enterprise_domain = 'YOUR GR360/ENTERPRISE DOMAIN HERE';

// Ask your account manager for API URL, or try one of below.

// $getresponse->api_url = 'https://api3.getresponse360.com/v3'; // Uncomment this line if  your account is registred in US / Canada
// $getresponse->api_url = 'https://api3.getresponse360.pl/v3'; // Uncomment this line for all other GR enterprise accounts

$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
//Assign recieved POST
$post = $_POST;

//Assign redirect urls
if (isset($post['alreadyredirect'])){
  $alreadyRedirectTo = $post['alreadyredirect'];
}

if (isset($post['thankyou_url'])){
  $subscribedRedirectTo = $post['thankyou_url'];
}

// Check if campaign_token and email recieved
if (((isset($post['email'])) && !empty($post['email'])) && ((isset($post['campaign_token'])) && !empty($post['campaign_token']))){
  $email = $post['email'];
  $campaignId = $post['campaign_token'];
  $request = createRequest($email, $campaignId);
  $result = $getresponse->getContacts($request);
  $resultArray = (array) $result;

  if (count($resultArray) == 0 ){
    //   TODO: Add contacts via API

    //     Remove redirect urls not to post it to GetResponse
    unset($post['alreadyredirect']);
    unset($post['thankyou_url']);

    //   Set action url
    $url = "http://www.$domain/add_subscriber.html";
    httpPost($url, $post);
    if (isset($subscribedRedirectTo) && !empty($subscribedRedirectTo)) { 
      ob_start();
      header("Location: $subscribedRedirectTo");
      ob_end_flush();
    }


  } else {
    //   Subscriber exists
    if (isset($alreadyRedirectTo) && !empty($alreadyRedirectTo)) { 
      ob_start();
      header("Location: $alreadyRedirectTo");
      ob_end_flush();
    } else { // If redirect is not set, redirect back
      if (isset($_SERVER["HTTP_REFERER"])){
        ob_start();
        header("Location: " . $_SERVER["HTTP_REFERER"], true, 302);
        ob_end_flush();
      }
    }
  }
} else {
  echo "No campaign_token or email";

}

function createRequest($email, $campaignId){
  if (isset($campaignId) && isset($email)){
    $request = (array(
        'query' => array(
          'email' => $email,
          'campaignId' => $campaignId
        ),
        'fields' => 'name,email'
      ));
    return $request;

  }
}

function httpPost($url, $data)
{
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($curl);
  curl_close($curl);
  return $response;
}

?>