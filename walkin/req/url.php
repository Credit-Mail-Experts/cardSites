<?php
// Declare the class
class GoogleUrlApi {

  public static $apiKey;
  private static $url;
  private static $cache = null;
  
  public static function shorten($url) {
    self::$url = $url;

    $response = self::request();

    $json = json_decode($response);

    if(!$json->id) {
      $returnUrl = $url;
    }else{
      $returnUrl = $json->id;
      self::$cache = $response;
    }

    return $returnUrl;

  }

  private static function request() {

    if(self::$cache) {
      return self::$cache;
    }

    $longUrl = self::$url;
    $apiKey = self::$apiKey;

    $postData = array('longUrl' => $longUrl, 'key' => $apiKey);
    $jsonData = json_encode($postData);

    $curlObj = curl_init();

    curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url');
    curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curlObj, CURLOPT_HEADER, 0);
    curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
    curl_setopt($curlObj, CURLOPT_POST, 1);
    curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

    $response = curl_exec($curlObj);

    curl_close($curlObj);

    return $response;
  }
}