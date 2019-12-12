<?php

  $phone="237698762982";
  $amount ="1000";
  $billno = "REF_20190301095351";
  $currency ="XAF";
  $date = "2017-09-08 15:53:32";
  $duedate = "2017-09-10 15:53:32";
  $name = "USER";
  $custid = "1234567";
  $label = "PAYMENT";
  $url = "http://213.251.146.170/eumobile_api/v2.1/sendPaymentRequest";
  $id = "25";
  $password = "mav150518";
  $key = 'SaH:jtx945AFJOd5b11UdIB2@7sHJCYiCneMIDEuq3YZA2OCMxcP5EAYtSn1b:@Ei!!QAC@QmSyMD*$$s5MfD@4WLaGN:y2ew$VWxOvKpUeRObO5ah*DaYhMYNgT*fZ';
  $hash = md5($id.$password.$billno.$amount.$currency.$date.$duedate.$name.$phone.$custid.$label.$key);

  $data = "id=".$id."&pwd=".$password."&billno=".$billno."&amount=".$amount."&currency=".$currency."&date=".$date."&duedate=".$duedate."&name=".$name."&phone=".$phone."&custid=".$custid."&label=".$label."&";
  $data_string = $data."hash=".$hash;

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
  curl_close($ch);

echo $result ;