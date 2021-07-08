<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Traits\NotificationTraits;
use App\Traits\PaymentTraits;
use Helper;
class TestController extends Controller
{
    use NotificationTraits, PaymentTraits;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id= !empty($request->id) ? $request->id : '65cb8c70-d17e-11ea-8795-e121237be871';

        $user = \App\User::find($id);

        $type = config('globalconstants.NOTIFICATION_MESSAGE_TYPE.ORDER_PLACED');

        $data['orderId'] =23;
        $data['amount'] =400;

        $this->createOrderPush($user, $type, $data);

    }

    public function charge(){
        $this->chargeCustomer('SI2022110112413', 'Chref'.time(), 2);
    }
    public function testCCavan(Request $request){
        
	$merchant_data='';
	$working_key='3B4980BF140C5A2461129264885172CC';//Shared by CCAVENUES
	$access_code='AVDR03IF82CE26RDEC';//Shared by CCAVENUES
	
	foreach ($_POST as $key => $value){
		$merchant_data.=$key.'='.$value.'&';
	}

	$encrypted_data=Helper::encrypt($merchant_data,$working_key); // Method for encrypting the data.

?>
<form method="post" name="redirect" action="https://secure.ccavenue.ae/transaction/transaction.do?command=initiateTransaction"> 
<?php
echo "<input type=hidden name=encRequest value=$encrypted_data>";
echo "<input type=hidden name=access_code value=$access_code>";
?>
</form>
</center>
<script language='javascript'>document.redirect.submit();</script>
</body>
</html>
<?php
    }
    
    public function cchandle(Request $request)
    {

        $workingKey='';		//Working Key should be provided here.
        $encResponse=$_POST["encResp"];			//This is the response sent by the CCAvenue Server
        $rcvdString=Helper::decrypt($encResponse,$workingKey);		//Crypto Decryption used as per the specified working key.
        $order_status="";
        $decryptValues=explode('&', $rcvdString);
        $dataSize=sizeof($decryptValues);
        echo "<center>";
    
        for($i = 0; $i < $dataSize; $i++) 
        {
            $information=explode('=',$decryptValues[$i]);
            if($i==3)	$order_status=$information[1];
        }
    
        if($order_status==="Success")
        {
            echo "<br>Thank you for shopping with us. Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.";
            
        }
        else if($order_status==="Aborted")
        {
            echo "<br>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail";
        
        }
        else if($order_status==="Failure")
        {
            echo "<br>Thank you for shopping with us.However,the transaction has been declined.";
        }
        else
        {
            echo "<br>Security Error. Illegal access detected";
        
        }
    
        echo "<br><br>";
    
        echo "<table cellspacing=4 cellpadding=4>";
        for($i = 0; $i < $dataSize; $i++) 
        {
            $information=explode('=',$decryptValues[$i]);
                echo '<tr><td>'.$information[0].'</td><td>'.$information[1].'</td></tr>';
        }
    
        echo "</table><br>";
        echo "</center>";
        }
}
