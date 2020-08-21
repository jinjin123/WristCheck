<?php

namespace Drupal\wristcheck_basic\Service;

use Drupal\Core\Site\Settings;

class MailChampSubscript  {

  protected  $url;
  protected  $key;

  function __construct($url,$key) {
    $this->$url = Settings::get('mailchamp_api', '');
    $this->$key = Settings::get('mailchamp_key', '');
  }
  public function MailChampSubscript ($email,$first_name,$last_name){
    $postData = array(
      "email_address" => "$email",
      "status" => "subscribed",
      "merge_fields" => array(
        "FNAME" => "$first_name",
        "LNAME" => "$last_name"
      )
    );
    $ch = curl_init($url);
    curl_setopt_array($ch, array(
      CURLOPT_POST => TRUE,
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_HTTPHEADER => array(
        'Authorization: apikey '.$key,
        'Content-Type: application/json'
      ),
      CURLOPT_POSTFIELDS => json_encode($postData)
    ));

    $response = curl_exec($ch);
    $readable_response = json_decode($response);
    if(!$readable_response) {
      \Drupal::logger('Mailchimp_subscriber')->error($readable_response->title.': '.$readable_response->detail .'. Raw values:'.print_r($values));
      \Drupal::messenger()->addError('Something went wrong. Please contact your webmaster.');
    }
    if($readable_response->status == 403) {
      \Drupal::logger('Mailchimp_subscriber')->error($readable_response->title.': '.$readable_response->detail .'. Raw values:'.print_r($values));
      \Drupal::messenger()->addError('Something went wrong. Please contact your webmaster.');
    }
    if($readable_response->status == 'subscribed') {
      \Drupal::messenger()->addStatus('You are now successfully subscribed.');
    }
    if($readable_response->status == 400) {
      if($readable_response->title == 'Member Exists') {
        \Drupal::messenger()->addWarning('You are already subscribed to this mailing list.');
      }
    }
    return true;
  }
}
