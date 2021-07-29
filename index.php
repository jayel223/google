<?php

//index.php

//Include Configuration File
include('config.php');
header('Content-Type: application/json');
error_reporting(0);
$login_button = '';
$msg = "";

if(isset($_GET["code"]))
{

 $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);


 if(!isset($token['error']))
 {
 
  $google_client->setAccessToken($token['access_token']);

 
  $_SESSION['access_token'] = $token['access_token'];


  $google_service = new Google_Service_Oauth2($google_client);

 
  $data = $google_service->userinfo->get();

 
  if(!empty($data['given_name']))
  {
   $_SESSION['user_first_name'] = $data['given_name'];
  }

  if(!empty($data['family_name']))
  {
    $_SESSION['user_last_name'] = $data['family_name'];
  }else{
    $_SESSION['user_last_name'] = "";
  }

  if(!empty($data['email']))
  {
   $_SESSION['user_email_address'] = $data['email'];
  }

  if(!empty($data['gender']))
  {
   $_SESSION['user_gender'] = $data['gender'];
  }

  if(!empty($data['picture']))
  {
   $_SESSION['user_image'] = $data['picture'];
  }
  header('location:index.php');
 }
}


if(!isset($_SESSION['access_token']))
{

 $login_button = '<a href="'.$google_client->createAuthUrl().'">Login With Google</a>';
}

if($login_button == '')
{
    

 $json[] = array(
    'firstname' => $_SESSION['user_first_name'],
    'lastname' => $_SESSION['user_last_name'],
    'email' => $_SESSION['user_email_address'],
    'picture' => $_SESSION["user_image"]
);

echo json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
else
{
    $json[] = array(
        'msg' => "You are not login.",
        'url' => $google_client->createAuthUrl()
    );
    
    echo json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
?>
  
