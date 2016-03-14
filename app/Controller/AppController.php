<?php
App::uses('Controller', 'Controller');
App::import('Core', 'Helper');
App::uses('Sanitize', 'Utility'); 

class AppController extends Controller
{
	public $helpers = array('Html','Form', 'Js','Session');
	public $components = array('Session','Auth','Cookie','RequestHandler','Email');
	private $statusCode = 200;
	
	function beforeFilter() 
	{ 	
		// allow user actions
		$this->Auth->allow();
	}

	public function generateCrsf($length){
		$token = "";
		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
		$codeAlphabet.= "0123456789";
		for($i=0;$i<$length;$i++){
			$token .= $codeAlphabet[$this->crypto_rand_secure(0,strlen($codeAlphabet))];
		}
		return $token;
	}
	
	public function  check_rest_api()
	{
		if(isset($this->RequestHandler->request->params['ext']))
		{
			$ext = $this->RequestHandler->request->params['ext'];
			if(in_array($ext,array('json','xml')))
			{
				return true;
			}
			else
			return false;
		}
		else
		return false;
	}
	
	public function isSigned()
	{ 
	
		/*--- If REST API Called Then check for access keys ------*/
		if($this->check_rest_api())
		{
					$this->response->statusCode(401);
				
					
					$this->set(array(
					'errors'	=> 1,
					'msg' => "Has key not provided",
					'response'	=> '',
					'_serialize' => array('errors','msg','response')
					)); 
			}
			else{
			return true;
			}
		
		
	}
	
	// To perform basic authentication while login
   protected function process_authentication($req)
   {
	if($this->check_rest_api()){	
		$awsAccessKey = "EzEve";//Configure::read('awsAccessKey');
		$awsSecretKey = "EzEve";//Configure::read('awsSecretKey');

		if(!isset($_REQUEST['auth_username']) || !isset($_REQUEST['auth_password']) || $_REQUEST['auth_username'] != $awsSecretKey && $_REQUEST['auth_password'] == $awsAccessKey)
		{
			
			$this->set(array(
					'errors'	=> 1,
					'msg' => $this->getStatusMessage(96),
					'response'	=> '',
					'_serialize' => array('errors','msg','response')
					)); 
		}
		else return true;
	  }
	}
	
	// To create an access token with respect to the provided user's id
	protected function create_access_token($id)
	{
		$temp = array();
		$temp['User']['access_token'] = bin2hex(openssl_random_pseudo_bytes(16));
		$temp['User']['access_token_expiration'] = date('Y-m-d H:i:s');
		
		$this->loadModel('User');
		$this->User->create();
		$this->User->id = $id;
	
		if(!$this->User->save($temp))
		{
			
			$this->set(array(
					'errors'	=> 1,
					'msg' => $this->getStatusMessage(98),
					'response'	=> '',
					'_serialize' => array('errors','msg','response')
					)); 
		}
		return $temp['User']['access_token'];
	}

	protected function send_response($return)
	{
		if($this->check_rest_api()){		
			if(!isset($return['data']))
				$return['data'] = '';
			$other_data = '';	
			if(isset($return['other_data']))
				$other_data = $return['other_data'];
			$this->set(array(
					'errors'	=>  $return['error'],
					'statusCode'	=> $return['status'],
					'msg' => $this->getStatusMessage($return['status']),
					'response'	=> $return['data'],
					'other_data' => $other_data,
					'_serialize' => array('errors','msg','response','other_data','statusCode')
					)); 
		}
	}

	// To compare access token and signature provided
	protected function compare_token($req='')
	{	if(empty($req))
		$req = $this->getRequestFields();
		$res = array();
		if($this->check_rest_api()){
			if(isset( $req['u_id']) &&  $req['u_id']!="")
			{	
			$this->loadModel('User');
			$result = $this->User->find('first',array('fields'=>array('u_id','access_token','access_token_expiration'),'conditions'=>array('u_id'=>$req['u_id'])));
			if(count($result)>0)
			{	$dateTime1 = strtotime(date('Y-m-d H:i:s'));
				$dateTime2 = strtotime($result['User']['access_token_expiration'].' +5 min'); //+1 years, +5 min,+1 hour
				if($dateTime2 < $dateTime1)
				{
							$res['error']   = 1;
							$res['status'] = 511;
							$res['data'] = '';
				}
				else
				{
					$now = date('Y-m-d H:i:s');
					$nowPlus15  = date('Y-m-d H:i:s', strtotime($now.' +15 min'));
					$nowMinus15 = date('Y-m-d H:i:s', strtotime($now.' -15 min'));
					$urlGenerationTime = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
						
					if($urlGenerationTime < $nowMinus15 || $urlGenerationTime > $nowPlus15)
					{
							$res['error']   = 1;
							$res['status'] = 527;
							$res['data'] = '';
							
					}
					else
					{
						$concateUsing = '!@!';
						
						$stringToSign  = '';
						$stringToSign .= $_SERVER['REQUEST_METHOD'].$concateUsing;
						//if(isset($_SERVER['HTTP_CONTENT_MD5']))
						//$stringToSign .= $_SERVER['HTTP_CONTENT_MD5'].$concateUsing;
						//if(isset($_SERVER['HTTP_HTTP_CONTENT_TYPE']))
						//$stringToSign .= $_SERVER['HTTP_HTTP_CONTENT_TYPE'].$concateUsing;
						
						$stringToSign .= date('Y-m-d', $_SERVER['REQUEST_TIME']).$concateUsing;
						$stringToSign .= '/'.$this->request->url.$concateUsing;						
						$stringToSign .= $this->arrayString($req, $concateUsing);	
						/*echo "hello==================>".$stringToSign;
						pr($_SERVER);*/
											
					 $computedSignature = hash_hmac('sha256', $stringToSign, $result['User']['access_token'], FALSE);
						
		
						//	prd($_SERVER);	echo $computedSignature;die;
					if(TEST_MODE){	
					$error = 0;
						if(!isset($_SERVER['HTTP_SIGNATURE']))
						$error = 1;
						else if(isset($_SERVER['HTTP_SIGNATURE']) && $computedSignature != $_SERVER['HTTP_SIGNATURE'])
						$error = 1;
						
						
						if($error==1)
						{
							$res['error']   = 1;
							$res['status'] = 526;
							$res['data'] = $stringToSign;
						
						}
						else return true;
					 }
					else return true;
					}
				} 
			}
			}
			else
			{
				$res['error']  = 1;
				$res['status'] = 515;
				$res['data'] = "Please fill all the fields";
			}
			//prd($res);
			if(!empty($res))
			{
			
				$this->send_response($res);
			}
		}
	}
	
		
	// To convert an array to string
	private function arrayString($array, $concateUsing)
	{
		$data = '';
		if(isset($array) && !empty($array))
		{
			$var = 1;
			foreach($array as $key => $val)
			{
				
				$data .= $key.':'.$val;
				
				if($var != count($array))
				{
					$data .= $concateUsing;
				}
				$var++;
			}
		}
		return $data;
	}
	
	public function androidPushNotification($gcmId, $msg, $params)
	{
		$url = 'https://android.googleapis.com/gcm/send';
		$key = "AIzaSyBPzfG1cn7f5NxgIv6KMS894naPXWazOyw";
	
		$data = array();
		$data['alert'] = $msg;
		$data['data']  = $params;
		
		$fields  = array('registration_ids'=>$gcmId,'data'=>$data);
		$headers = array('Authorization: key='.$key.'','Content-Type: application/json');
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		 
		$result=curl_exec($ch);
	
		$res = json_decode($result,true);
	
		if($res['success'])
		{
			curl_close($ch);
			return 1;
		}else{
			curl_close($ch);
			return 0;
		}
	}
	
	public function iPhonePushNotification($devideId, $msg, $params)
	{ if(0){
		// Put your private key's passphrase here:
		$passphrase = 'techno';
	
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert','../../ck.pem');
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
	
		// Open a connection to the APNS server
		$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
	
		if(!$fp)
		{
			exit("Failed to connect: $err $errstr" . PHP_EOL);
		}
	
		// Create the payload body
		$body = array();
		$body['aps']['alert'] = $msg;
		$body['aps']['sound'] = 'default'; //'futuresound.wav'
		$body['aps']['data']  = $params;
		
		// Encode the payload as JSON
		$payload = json_encode($body);
	
		// Build the binary notification
		if($devideId != NULL)
		{
			$msg = chr(0) . pack('n', 32) . pack('H*', $devideId) . pack('n', strlen($payload)) . $payload;
				
			// Send it to the server
			$result = fwrite($fp, $msg, strlen($msg));
			if($result)
				return 1;
			else
				return 0;
		}
		else
		{
			return 0;
		}
		// Close the connection to the server
		fclose($fp);
	}
		else
		{
			  require_once '../webroot/ApnsPHP/ApnsPHP/Autoload.php';
			   $push = new ApnsPHP_Push(
				 ApnsPHP_Abstract::ENVIRONMENT_PRODUCTION,
				'../webroot/ck_driverapp.pem'
			   );
			   $push->setProviderCertificatePassphrase("techno");
			   // Connect to the Apple Push Notification Service
			   $push->connect();
			   $invalid_token = array();
			   $invalid_token_string = '';
			   $comma = '';
			   $DeviceIds =  $devideId;
			
			   /*echo "<pre/>";
			   print_r($DeviceIds);
			   exit;*/
			   for($j=0; $j<count($DeviceIds); $j++){
				   $deviceToken[$j] = $DeviceIds[$j];
				if($deviceToken[$j] != '' && preg_match('~^[a-f0-9]{64}$~i', $deviceToken[$j]) ){
				 // Instantiate a new Message with a single recipient
				 $messageObj = new ApnsPHP_Message($deviceToken[$j]);
				 // Set a simple welcome text
				 $messageObj->setText($msg);
					   // Play the default sound
				 $messageObj->setSound();
				 if($params)
				 {	foreach($params as $key_par=>$value_par)
				 	$messageObj->setCustomProperty($key_par, $value_par);
				 }
			    // Add the message to the message queue
				 $push->add($messageObj);
				}else{
				 $invalid_token[] = $deviceToken[$j];
				 $invalid_token_string .= $comma."'".$deviceToken[$j]."'";
				 $comma = ',';
				}
			   }
			
			   //send notification
			   $push->send();
			   // Disconnect from the Apple Push Notification Service
			   $push->disconnect();
		}
	}
	
	public function response($return)
	{/*	$data['status_message']  = "Something went wrong";
		if(isset($data['status']))
		$data['status_message']  = $this->getStatusMessage($data['status']);	
		
		$this->statusCode = ($data['error']) ? 400 : 200;
		$this->statusCode = 200;

		$data['message']  = $this->getStatusMessage();
		$this->setHeaders();
		
		echo json_encode($data);
		exit;*/
			if(!isset($return['data']))
			$return['data'] = '';
			$this->set(array(
				'errors'	=>  $return['error'],
				'msg' => $this->getStatusMessage($return['status']),
				'response'	=> $return['data'],
				'_serialize' => array('errors','msg','response')
				)); 
		
	}
	
	public function getStatusMessage($status_val='')
	{
		$status = array(
				94 => 'Invalid refresh token',
			    95 => 'Invalid app key',
				96 => 'Authentication failed',
				97 => 'Invaild access token',
				98 => 'Failed to create access token',
				99 => 'Access token re-created successfully',
				100 => 'Continue',
				101 => 'Switching Protocols',
				200 => 'OK',
				201 => 'Created',
				202 => 'Accepted',
				203 => 'Non-Authoritative Information',
				204 => 'No Content',
				205 => 'Reset Content',
				206 => 'Partial Content',
				300 => 'Multiple Choices',
				301 => 'Moved Permanently',
				302 => 'Found',
				303 => 'See Other',
				304 => 'Not Modified',
				305 => 'Use Proxy',
				306 => '(Unused)',
				307 => 'Temporary Redirect',
				400 => 'Bad Request',
				401 => 'Unauthorized',
				402 => 'Payment Required',
				403 => 'Forbidden',
				404 => 'Not Found',
				405 => 'Method Not Allowed',
				406 => 'Not Acceptable',
				407 => 'Proxy Authentication Required',
				408 => 'Request Timeout',
				409 => 'Conflict',
				410 => 'Gone',
				411 => 'Length Required',
				412 => 'Precondition Failed',
				413 => 'Request Entity Too Large',
				414 => 'Request-URI Too Long',
				415 => 'Unsupported Media Type',
				416 => 'Requested Range Not Satisfiable',
				417 => 'Expectation Failed',
				500 => 'Internal Server Error',
				501 => 'Not Implemented',
				502 => 'Bad Gateway',
				503 => 'Service Unavailable',
				504 => 'Gateway Timeout',
				505 => 'HTTP Version Not Supported',
				506 => 'Categories has been successfully fetched',
				507 => 'Categories not found',
				508 => 'Invaild access token',
				509 => 'Failed to create access token',
				510 => 'Access token re-created successfully',
				511 => 'Access token exipred',
				512 => 'Result fetched successfully',
				513 => 'No record found',
				514 => 'Invalid provisioning profile',
				515 => 'Please fill all required fields',
				516 => 'Something went wrong',
				517 => 'You have registered successfully ',
				518 => 'Logged in successfully',
				519 => 'Invalid username or password, try again.',
				520 => 'Seems that you have already logged in.',
				521 => 'We will mail you once your acount is approved by admin',
				522 => 'Registration Completed Successfully',
				523 => 'Invalid signature',
				524 => 'Your new password sent to your email address successfully',
				525 => 'Password sent on your phone number ',
				526 => 'Invalid signature',
				527 => 'URL has been expired',
				528 => 'Record Not Exist',
				529 => 'Countries list fetched successfully',
				530 => 'Record added successfully',
				531 => 'Event added successfully',
				532 => 'You have logged out successfully',
				533 => 'Friend request sent successfully',
				534 => 'You are already friend with this user',
				535 => 'User not accepted your request yet.',
				536 => 'There is no friend matched',
				537 => 'You are already friend with this user',
				538 => 'Friend request accepted successfully',
				539 => 'Event status updated',
				540 => 'Invite card sending failed',
				541 => 'Invite card sent',
				542 => 'Event status list fetched successfully',
				543 => 'Friend deleted successfully',
				544 => 'Friend Request is pending',
				545 => 'Report exported successfully',
				545 => 'Photo upload successfully',
				546 => 'Photo linked successfully',
				547 => 'Reports fetched successfully',
				
				);
		if(!empty($status_val))	
		return 	$status[$status_val];
		return ($status[$this->statusCode]) ? $status[$this->statusCode] : $status[500];
	}
	
	
	public function getRequestFields()
	{
		if($this->request->ispost('post'))
		{
		    if(!empty($this->request->data))
			return $this->request->data;
			else
			return $this->request->query;
		}
		else if($this->request->named)
		{return $this->request->named;
		}
		else
		{
			return $this->request->query;
		}
	}
	private function setHeaders()
	{
		header('HTTP/1.1 '.$this->statusCode.' '.$this->getStatusMessage());
		header('Content-Type:application/json');
	}
	
	public   function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 0) return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
	}

	public function getToken($length){
		$token = "";
		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
		$codeAlphabet.= "0123456789";
		for($i=0;$i<$length;$i++){
			$token .= $codeAlphabet[$this->crypto_rand_secure(0,strlen($codeAlphabet))];
		}
		return $token;
	}

	protected function createDir($path)
	{
		if (!file_exists($path)) {
    mkdir($path, 0777, true);
		}
	}

	public function uploadFile($path,$file)
	{
		$RealPath = realpath(dirname(dirname(dirname(__FILE__))));
		$uploadPath = $RealPath.'/app/webroot/'.$path;
		move_uploaded_file($file['tmp_name'], $uploadPath);	
		return file_exists($uploadPath);
	}

	public function writeLog(){
		if(!(file_exists('files/log.txt')))
		{
			touch(WWW_ROOT.'files/log.txt');
		}
		
			
			$file = fopen(WWW_ROOT."files/log.txt","a");
				fwrite($file,"\n". date('Y-m-d H:i:s A')."\n Called from api :- ".$this->here);
				
				
				   fwrite($file,"\n ". print_r($_POST, true));
				
				
				if(!empty($_FILES))
				{
					
					   fwrite($file,"\n ".print_r($_FILES, true));
					
				}
				fclose($file);
		
		}

	public function writeResponseLog($response){
		//if(!(file_exists('files/log.txt')))
		//{
			
		//}
			
				$file = fopen("files/log.txt","a");
				fwrite($file, print_r($response, true));
				
				
				fclose($file);
		
		}	

	public function getdatetime()
	{
		return date('Y-m-d H:i:s'); 
	}	

}