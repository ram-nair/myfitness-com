<?php

namespace App\Traits;

use Config;

trait PaymentTraits
{

    public function chargeCustomer($siReference, $chargeReferenceId, $amount)
    {
        try {
            $siRequest = array(
                'si_sub_ref_no' => $siReference,
                'si_mer_charge_ref_no' => $chargeReferenceId,
                'si_amount' => $amount,
                'si_currency' => config('ccavenue.currency'),
                //'si_merchant_mid' => "43366"
            );
            $access_code = config('ccavenue.accessCode');
            $working_key = config('ccavenue.accessKey');

            //echo json_encode($siRequest); exit;
            $merchant_data = json_encode($siRequest);
            $encrypted_data = $this->encrypt($merchant_data, $working_key);
            $final_data = "request_type=JSON&access_code=" . $access_code . "&command=chargeSI&version=1.1&enc_request=" . $encrypted_data;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, config('ccavenue.paymentUrl'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $final_data);
            $result = curl_exec($ch);
            curl_close($ch);

            $status = '';
            $information = explode('&', $result);
            $dataSize = sizeof($information);
            for ($i = 0; $i < $dataSize; $i++) {
                $info_value = explode('=', $information[$i]);
                if ($info_value[0] == 'enc_response') {
                    // echo $info_value[1];
                    $status = $this->decrypt(trim($info_value[1]), $working_key);
                }
            }

            $result = json_decode($status, true);
            // dd($result);
            return $result;
            //{"si_charge_status":"0","si_mer_charge_ref_no":"sijuref132133","si_charge_txn_status":"0","si_sub_ref_no":"SI2022110112413",
            //"reference_no":"109014587416","bank_ref_no":"420478","receipt_no":"022323420478","bank_mid":"TEST800454","si_error_desc":"","error_code":""}

        } catch (\Exception $e) {
            return false;
            //dd('Add : ' . $e->getMessage());
            //Log::error($e->getMessage());
        }
    }

    public function encrypt($plainText, $key)
    {
        $secretKey = hex2bin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $secretKey, OPENSSL_RAW_DATA, $initVector);
        $encryptedText = bin2hex($openMode);
        return $encryptedText;
    }

    public function decrypt($encryptedText, $key)
    {
        $key = hex2bin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText = hex2bin($encryptedText);
        $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        return $decryptedText;
    }
}
