<?php
class CommonfunctionsComponent extends Component {

	public $components = array('Session');
	

	// public function redirectToMyListing($This=NULL){
	// 	$cur_controller = $This->request->params['controller'];
	// 	$cur_action = $This->request->params['action'];
	// 	$cur_params = $cur_action = $This->request->params;

	// 	prd($cur_params);

	// 	$paginator_data_key = array_keys($cur_params['paging']);

	// 	if($cur_params['named']['page'] > $cur_params['paging'][$paginator_data_key[0]]['pageCount']){
	// 		//redirect to first page
	// 		echo $redirect_url = array('controller'=> $cur_controller, 'action' => $cur_action, 'page:1');
	// 		exit;
	// 		// $this->redirect();

	// 	}
	// 	echo 'im out';
	// 	exit;
	// }

	public function dateTimeDifference($day_1,$day_2){echo "xcvcv";die;
		$diff = strtotime($day_1) - strtotime($day_2);
		$sec   = $diff % 60;
		$diff  = intval($diff / 60);
		$min   = $diff % 60;
		$diff  = intval($diff / 60);
		$hours = $diff % 24;
		$days  = intval($diff / 24);
		return array('seconds'=>$sec,'minutes'=>$min,'hours'=>$hours,'days'=>$days);
	}

	public function getFileExtension($filename=''){
		$exp_data = explode(".",$filename);
		return $exp_data[count($exp_data)-1];
	}

	public function generate_file_name($filename=''){
		$ext = $this->getFileExtension($filename);
		return time()+rand().'.'.$ext;
	}

	public function getHeader($c,$a, $id=false){
		$head	=	'';
		if($c=='users' && $a=='index'){
			$head = 'f_head';
			if($id){
				$head	=	'innerheader';
			}
		}else{
			$temp	=	$c.'/'.$a;
			$arr	=	array('faqs/index', 'pages/view', 'contacts/index', 'profiles/publicproffessionalprofile', 'users/thanks', 'users/profilelisting', 'users/profilelist');
			$head	=	'innerheader';
			if(in_array($temp, $arr)){
				$head	=	'innerheaderother';
				if($id){
					$head	=	'innerheader';
				}

			}
		}

		if(empty($c) && empty($a)){
			$head	=	'innerheaderother';
		}
		//pr($head);

		return $head;
	}

	public function func_crypt($data){
		$data_len = strlen($data);
		$return_data = ($data_len*$data_len).base64_encode($data).'|'.md5($data.rand()).'-'.($data_len);
		return $return_data;
	}

	public function func_decrypt($data){
		$arr_data = split("[|-]",$data);
		$remove_data = $arr_data[2]*$arr_data[2]; // removable data from main data
		$orig_data = preg_replace('/'.$remove_data.'/', '', $arr_data[0], 1);
		return base64_decode($orig_data);
	}

	public function changedatemaster($date,$from='ymd',$to='mdy',$add_time=true,$sep='-'){
		$from = substr(strtolower(str_replace("-","",$from)),0,3);
		$to = substr(strtolower(str_replace("-","",$to)),0,3);
		if($date==''){ 	return $date; }
		$return_string = '';
		$myExp1	=	explode(" ",$date);
		$time = '';
		if(isset($myExp1[1])){
			$time = $myExp1[1];
		}

		list($datelist1, $datelist2, $datelist3) = split("[./-]",$myExp1[0]);
		if($from=='mdy'){
			if($to=='ymd'){
				$return_string = $datelist3.$sep.$datelist1.$sep.$datelist2;
			} else if($to=='dmy'){
				$return_string = $datelist2.$sep.$datelist1.$sep.$datelist3;
			}
		} else if($from=='ymd'){
			if($to=='dmy'){
				$return_string = $datelist3.$sep.$datelist2.$sep.$datelist1;
			} else if($to=='mdy'){
				$return_string = $datelist2.$sep.$datelist3.$sep.$datelist1;
			}
		} else if($from=='dmy'){
			if($to=='ymd'){
				$return_string = $datelist3.$sep.$datelist2.$sep.$datelist1;
			} else if($to=='mdy'){
				$return_string = $datelist2.$sep.$datelist1.$sep.$datelist2;
			}
		}
		if($add_time){
			return 	$return_string ." ". $time;
		} else {
			return 	$return_string;
		}

	}

	public function create_thumb($image_path,$save_path,$width_height=100, $exheight=0){
		$s_image = $image_path; // Image url set in the URL. ex: thumbit.php?image=URL
		$e_image = "error.jpg"; // If there is a problem using the file extension then load an error JPG.
		if($exheight > 0){
			//case when we want to create desired width/height image
			$max_width = $width_height; // Max thumbnail width.
			$max_height = $exheight; // Max thumbnail height.
		} else {
			//crop image by fix width/height
			$max_width = $width_height; // Max thumbnail width.
			$max_height =$width_height; // Max thumbnail height.
		}
		$quality = 100; // Do not change this if you plan on using PNG images.

		if (preg_match("/.jpg/i","$s_image") or preg_match("/.jpeg/i","$s_image")) {
			list($width, $height) = getimagesize($s_image);
			$ratiow = $width/$max_width ;
			$ratioh = $height/$max_height;
			$ratio = ($ratiow > $ratioh) ? $max_width/$width : $max_height/$height;
			if($width > $max_width || $height > $max_height) {
			$new_width = $width * $ratio;
			$new_height = $height * $ratio;
			} else {
			$new_width = $width;
			$new_height = $height;
			}
			$image_p = imagecreatetruecolor($new_width, $new_height);
			$image = imagecreatefromjpeg($s_image);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			imagejpeg($image_p, $save_path, $quality);
			imagedestroy($image_p);

		} elseif (preg_match("/.png/i", "$s_image")) {
			list($width, $height) = getimagesize($s_image);
			$ratiow = $width/$max_width ;
			$ratioh = $height/$max_height;
			$ratio = ($ratiow > $ratioh) ? $max_width/$width : $max_height/$height;
			if($width > $max_width || $height > $max_height) {
			$new_width = $width * $ratio;
			$new_height = $height * $ratio;
			} else {
			$new_width = $width;
			$new_height = $height;
			}
			$image_p = imagecreatetruecolor($new_width, $new_height);
			$background = imagecolorallocate($image_p, 0, 0, 0);

			$image = imagecreatefrompng($s_image);
			if(!@$_GET['flag']==1){
			imagecolortransparent($image_p,$background) ;
			}
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			imagepng($image_p,$save_path);
			imagedestroy($image_p);

		} elseif (preg_match("/.gif/i", "$s_image")) {
			list($width, $height) = getimagesize($s_image);
			$ratiow = $width/$max_width ;
			$ratioh = $height/$max_height;
			$ratio = ($ratiow > $ratioh) ? $max_width/$width : $max_height/$height;
			if($width > $max_width || $height > $max_height) {
			$new_width = $width * $ratio;
			$new_height = $height * $ratio;
			} else {
			$new_width = $width;
			$new_height = $height;
			}
			$image_p = imagecreatetruecolor($new_width, $new_height);
			$image = imagecreatefromgif($s_image);
			$bgc = imagecolorallocate ($image_p, 255, 255, 255);
			imagefilledrectangle ($image_p, 0, 0, $new_width, $new_height, $bgc);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			imagegif($image_p, $save_path, $quality);
			imagedestroy($image_p);

		} else {
			header('Content-type: image/jpeg');
			imagejpeg($e_image, $save_path, $quality);
			imagedestroy($e_image);
		}
	}

  public function getExpiryDate($expiry_date){
    $expiry_date = strtotime($expiry_date);
    $new_time = strtotime('+23 hours', $expiry_date);
    $new_time = strtotime('+59 minutes', $new_time);
    $return_time = date("Y-m-d H:i:s", strtotime('+59 seconds', $new_time));
    return $return_time;
  }

  public function getExpiryDateAdv($expiry_date){
		$expiry_date = strtotime($expiry_date);
    $return_time = date("Y-m-d H:i:s", strtotime('-1 seconds', $expiry_date));
    return $return_time;
  }

  protected function convertFormat($date='',$onlydate=true){
    if($date==''){ $date = date("Y-m-d"); }
    $new_val = $date;

    if($onlydate){
      $old_val = explode("-",$date);
      $y = $old_val[2];
      $m = $old_val[0];
      $d = $old_val[1];
      $new_val = $y.'-'.$m.'-'.$d;
    }else{
      $old_val1 = explode(" ",$date);
      $old_val = explode("-",$old_val1[0]);
      $y = $old_val[2];
      $m = $old_val[0];
      $d = $old_val[1];
      $new_val = $y.'-'.$m.'-'.$d.' '.$old_val1[1];
    }
    return $new_val;
  }

  public function convertDate($dateval='',$front_end=true,$include_time=false,$format=false){
    $time_included = true;
    $return_val = '';
    if($dateval==''){
      $time_included = false;
      $dateval = ($front_end)? date("Y-m-d"): date("m-d-Y");
    }

    if($front_end){
      if($include_time){
        //$show_format = ($format) ? $format : "m-d-Y H:i:s";
        $show_format = ($format) ? $format : "m-d-Y H:i";
        if($time_included){
          $return_val = date($show_format,strtotime($dateval));
        }else{
          //$return_val = date($show_format,strtotime($dateval." ".date("H:i:s")));
          $return_val = date($show_format,strtotime($dateval." ".date("H:i")));
        }
      }else{
        $show_format = ($format) ? $format : "m-d-Y";
        $return_val = date($show_format,strtotime($dateval));
      }
    }else{
      if($include_time){
        $return_val = date("Y-m-d H:i",strtotime($this->convertFormat($dateval." ".date("H:i"),false)));
      }else{
        $return_val = date("Y-m-d",strtotime($this->convertFormat($dateval,true)));
      }
    }

    return $return_val;
  }

  public function center_text($string){
    // $font = $this->webroot.'font/arial.ttf';
    $font = WWW_ROOT.'font/arial.ttf';
    $font_size = 11;
    $image_width = 800;
    $dimensions = imagettfbbox($font_size, 0, $font, $string);
    return ceil(($image_width - $dimensions[4]) / 2);
  }

  public function generateSecurityCodeImage($security_code) {
    $quality = 85;
    $i = 30;
    $font = 'font/arial.ttf';
    // $font = WWW_ROOT.'font/arial.ttf';
    $font_size = 30;
    $angle = 0;
    $height = 30;
    $original_file = 'images/banner/watermark_new.jpg';
    // $original_file = WWW_ROOT.'img/watermark.png';
    $image = imagecreatefromjpeg($original_file);
    $new_file_path = 'images/banner/watermark_modified.jpg';
    // $new_file_path = WWW_ROOT.'img/watermark_modified.jpg';
    $text_color = imagecolorallocate($image, 0, 0, 0);
    $grey = imagecolorallocate($image, 102, 102, 102);

    /*$y = imagesy($image) - $height - 365;
    imagettftext($image, $font_size, $angle, $x, $y+$i, $text_color, $font, $security_code);*/

    // $font = 'fonts/captcha_font.ttf';
    $x = $this->center_text($security_code, $font_size);
    imagettftext($image, $font_size, 0,45, 115, $text_color, $font, $security_code);

    // create the image
    imagejpeg($image, $new_file_path);

    return $new_file_path;
  }

  public function generatePlaceholderImage($security_code) {
    $quality = 85;
    $i = 30;
    $font = 'font/arial.ttf';
    // $font = WWW_ROOT.'font/arial.ttf';
    $font_size = 22;
    $angle = 0;
    $height = 30;
    $original_file = 'images/banner/watermark_new.jpg';
    // $original_file = WWW_ROOT.'img/watermark.png';
    $image = imagecreatefromjpeg($original_file);
    $new_file_path = 'images/banner/watermark_modified.jpg';
    // $new_file_path = WWW_ROOT.'img/watermark_modified.jpg';
    $text_color = imagecolorallocate($image, 0, 0, 0);
    $grey = imagecolorallocate($image, 102, 102, 102);

    /*$y = imagesy($image) - $height - 365;
    imagettftext($image, $font_size, $angle, $x, $y+$i, $text_color, $font, $security_code);*/

    // $font = 'fonts/captcha_font.ttf';
    $x = $this->center_text($security_code, $font_size);
    imagettftext($image, $font_size, 0,10,115, $text_color, $font, $security_code);

    // create the image
    imagejpeg($image, $new_file_path);

    return $new_file_path;
  }

	function format_phone_us($phone){
		$OriginalPhone = preg_replace('/[^a-zA-Z0-9]/s', '', $phone);
		$length = strlen($OriginalPhone);
		// Perform phone number formatting here
		switch ($length) {
			case 7:
				// Format: xxx-xxxx
				return preg_replace("/([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/", "$1-$2", $phone);
			case 8:
				return preg_replace("/([0-9a-zA-Z]{1})([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/", "($1) $2-$3", $OriginalPhone);
			case 9:
				return preg_replace("/([0-9a-zA-Z]{2})([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/", "($1) $2-$3", $OriginalPhone);
			case 10:
				// Format: (xxx) xxx-xxxx
				return preg_replace("/([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/", "($1) $2-$3", $OriginalPhone);
			case 11:
				// Format: x(xxx) xxx-xxxx
				return preg_replace("/([0-9a-zA-Z]{1})([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/", "$1 ($2) $3-$4", $OriginalPhone);
			default:
				// Return original phone if not 7, 10 or 11 digits long
					$last_digits = substr($OriginalPhone,-10,10);
					$remaining_digits = substr($OriginalPhone,0,-10);
					$phno_format =$remaining_digits.' '.preg_replace("/([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/", "($1) $2-$3", $last_digits);
					return $phno_format;
				}

		}
		/** DoDirectPayment NVP example; last modified 08MAY23.
		 *
		 *  Process a credit card payment. 
		*/
		
		//public $environment = 'sandbox';	// or 'beta-sandbox' or 'live'
		
		/**
		 * Send HTTP POST Request
		 *
		 * @param	string	The API method name
		 * @param	string	The POST Message fields in &name=value pair format
		 * @return	array	Parsed HTTP Response body
		 */
		//paypal 
		function PPHttpPost($methodName_, $nvpStr_) {
			//global $environment;
			 $environment = 'live'; //'sandbox';	// or 'beta-sandbox' or 'live'
			
			// Set up your API credentials, PayPal end point, and API version.
			//$API_UserName = urlencode('sdk-three_api1.sdk.com');
			//$API_Password = urlencode('QFZCWN5HZM8VBG7Q');
			//$API_Signature = urlencode('A.d9eRKfd1yVkRrtmMfCFLTqa6M9AyodL0SJkhYztxUi8W9pCXF6.4NI');
			
			//live
			$API_UserName = urlencode('ehab_api1.eccentricbi.com');
			$API_Password = urlencode('4886PNYBCY2BLSWR');
			$API_Signature = urlencode('ArR7zp1s5B1koVNQjYtnF1.UrO6OAFLOpJD8.YPazWuUdBRcDVWjbv5F');
			
			//$API_UserName = urlencode('paypal_api1.babysittermatch.co.nz');
			//$API_Password = urlencode('SLR4CGBRPPR44BUD');
			//$API_Signature = urlencode('ASjzbU4u134rJvr0yOkyhapZRnmgAKn8LE6nhp1qL8iNcV9lJR0EJ6pG');
			
			//new test account
			//$API_UserName = urlencode('kamlesh-facilitator_api1.cgt.co.in');
			//$API_Password = urlencode('1363412524');
			//$API_Signature = urlencode('AF3Fl5mw-rqE6mZrDFEa.EdKfGdbAerl9p.m65TgmjdX8XE53srJ-wiY');
			
			/*$API_UserName = urlencode('kk_1259124926_biz_api1.gmail.com');
			$API_Password = urlencode('1259124935');
			$API_Signature = urlencode('AnlqNYrwhcNNDeYxIFy0s3tOFx2FAuqp6QmB0MPEN9anXKaeZ.3GJs4-');*/
			
			
			$API_Endpoint = "https://api-3t.paypal.com/nvp";
			if("sandbox" === $environment || "beta-sandbox" === $environment) {
				$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
			}
			$version = urlencode('51.0');
		
			// Set the curl parameters.
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
		
			// Turn off the server and peer verification (TrustManager Concept).
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
		
			// Set the API operation, version, and API signature in the request.
			$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
		
			// Set the request as a POST FIELD for curl.
			curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
		
			// Get response from the server.
			$httpResponse = curl_exec($ch);
		
		
		/*$arraynew=deformatNVP($httpResponse);
		
		echo '<pre>';echo print_r($arraynew);  echo '</pre>';*/
		
		
			if(!$httpResponse) {
				exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
			}
		
			// Extract the response details.
			$httpResponseAr = explode("&", $httpResponse);
		
			$httpParsedResponseAr = array();
			foreach ($httpResponseAr as $i => $value) {
				$tmpAr = explode("=", $value);
				if(sizeof($tmpAr) > 1) {
					$httpParsedResponseAr[$tmpAr[0]] = urldecode($tmpAr[1]);
				}
			}
		
			if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
				exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
			}
		
			return $httpParsedResponseAr;
		}
}


