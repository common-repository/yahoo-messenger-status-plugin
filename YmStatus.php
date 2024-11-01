<?php
abstract class YmStatus {
  public static function IsOnline($username) {
      if ($username == '') return false;

      $yahoo_url = "http://opi.yahoo.com/online?u=$username&m=a&t=1";

      $conn = curl_init();
      curl_setopt($conn, CURLOPT_URL, $yahoo_url);
      curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
      $status = curl_exec($conn);
      curl_close($conn);

      return ($status == '01');
  }
}
?>