<?php
function encrypt($plainText, $key) {
    $secretKey = hex2bin(md5($key));
    $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
    $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $secretKey, OPENSSL_RAW_DATA, $initVector);
    $encryptedText = bin2hex($openMode);
    return $encryptedText;
}

function decrypt($encryptedText, $key) {
    $key = hex2bin(md5($key));
    $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
    $encryptedText = hex2bin($encryptedText);
    $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
    return $decryptedText;
}

//to enable error
error_reporting(-1);

$siRequest = array(
  'si_sub_ref_no' => "SI2022110112413",
  'si_mer_charge_ref_no' => "sijuref132133",
  'si_amount' => "2",
  'si_currency' => "AED"
  //'si_merchant_mid' => "43366"
);

$access_code = 'AVGH03HH50AH22HGHA';
$working_key = '75D1B0504BE3DA3D8052FB5EE8CB23F8';

//echo json_encode($siRequest); exit;
$merchant_data = json_encode($siRequest);
$encrypted_data = encrypt($merchant_data, $working_key);
$final_data = "request_type=JSON&access_code=".$access_code."&command=chargeSI&version=1.1&enc_request=" . $encrypted_data;




$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://login.ccavenue.ae/apis/servlet/DoWebTrans");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $final_data);
$result = curl_exec($ch);
curl_close($ch);

//echo $result;exit;      //uncomment to check response

//decrypting response
$status = '';
$information = explode('&', $result);
$dataSize = sizeof($information);
for ($i = 0; $i < $dataSize; $i++) {
    $info_value = explode('=', $information[$i]);
    if ($info_value[0] == 'enc_response') {
       // echo $info_value[1];
       $status = decrypt(trim($info_value[1]), $working_key);
    }
}

$result = json_decode($status, true);
var_dump($result["si_charge_status"]);
// header('Content-Type: application/json');
echo 'Status revert is: ' . $status;
exit;















 ?>
