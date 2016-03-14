<?php
class UsersController extends AppController 
{	public $name = 'Users';
	public $components = array('RequestHandler','Upload','Commonfunctions');
	
//public $components = array( 'RequestHandler','Auth'=> array( 'authenticate' => array( 'Form' => array( 'fields' => array('u_username' => 'username') ) ) ) );
	//public $components = array('Upload','Commonfunctions');
	public  $logged_user_record;
	public $u_employee_type;
	
	// allow user actions
	public function beforeFilter() 
	{ 
		echo "hello";
		$this->Auth->allow();
	
	}

	public function index(){
		echo "index";
	}
	public function recreate_access_token()
	{	
		$return = array();$requestFields = $this->getRequestFields();
		
		
		if(isset($requestFields['refresh_token']) &&  $requestFields['refresh_token']!="")
			{	
			$this->loadModel('User');
			$result = $this->User->find('first',array('fields'=>array('u_id','access_token','access_token_expiration'),'conditions'=>array('refresh_token'=>$requestFields['refresh_token'])));
			
			if(count($result)>0)
			{
				$access_token = $this->create_access_token($result['User']['u_id']);
				$return['error']  = 0;
				$return['status'] = 99;
				$return['data'] = $access_token;
			}
		else
			{
				$return['error']  = 1;
				$return['status'] = 94;
				$return['data'] = "Invalid refresh token, please try again.";
			}
		}
		else
			{
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = "Please fill all the fields";
			}
			
		if($return)
		$this->send_response($return);
	}
    /*------------------------ Edit Profile  ------------------------------*/
	public function profile() 
	{	
		$this->set("active_tabs","profile");
		if(isset( $_SESSION['email_exist']) &&  $_SESSION['email_exist']!="")
		{	$this->Session->setFlash( $_SESSION['email_exist'],'default',array('class'=>'alert alert-error text-center'));
			unset( $_SESSION['email_exist']);
		}
		$this->set('PageHeading', __("Manage Profile")); 
		$this->loadModel('User');
	 	$requestFields = $this->getRequestFields();
		if($requestFields)
		{
			$save = $requestFields;
			$this->User->set($save);
			$this->User->validator()->remove('username', 'isUnique');
			$this->User->validator()->remove('u_username', 'isUnique');
			if(isset($save['User']['password']) && empty($save['User']['password']) &&
			isset($save['User']['u_confirm_password']) && empty($save['User']['u_confirm_password']))
			{		$this->User->validator()->remove('password', 'notempty');
					$this->User->validator()->remove('u_confirm_password', 'u_confirm_password');
					
					unset($save['User']['password']);	unset($save['User']['u_confirm_password']);
			}
			
			
			if(isset($save['User']) && (!isset($save['User']['u_firstname']) || !isset($save['User']['u_lastname']) )  )
			{
				$errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
			}else
			if($this->User->validates() && isset($save['User']))
			{ 
				$data = $save['User'];
				$error = "";
				
				if(isset($data['password']) && !isset($data['u_confirm_password']) )
				$error = "Confirm password is required";
				else if(isset($data['password']) &&  $data['password']!=$data['u_confirm_password'])
				$error = "Confirm password and password not matched";
				else if(isset($data['password']) &&  empty($data['password']))
				unset($save['User']['password']);
				
				if(!empty($error))
				{
						$return['error']  = 1;
						$return['status'] = 516;
						$return['data'] = $error;
						$this->response($return);
				}
				
				$this->User->create();
				$this->User->id= $data['u_id'];					
					// Check For Image
					if(isset($save['u_photo']['name']) && $save['u_photo']['name']!='')
					{$save['User']['u_photo'] = $this->profile_image($save,$user);
					}
					if(isset($save['u_photo']))
					unset($save['u_photo']);
					// Check For Data URI Image
					if(isset($save['upload_image_datauri']) && $save['upload_image_datauri']!="")
					{$save['User']['u_photo'] = $this->profile_image($save,$user);
					}
				
					if($this->User->save($save)){		
						
						$select_data = array("conditions"=>array("u_id"=>$data['u_id']),"find"=>"");
						$db_record = $this->User->getUserRecords($select_data);	
						if(isset($requestFields['User']['password']) && $requestFields['User']['password']!="")
						$db_record[0]['User']['password'] = $requestFields['User']['password'];
						else
						unset($db_record[0]['User']['password']);
						
						$return['error']  = 0;
						$return['status'] = 523;
						$return['data'] = $db_record[0]['User'];
						
					}
					else
					{
						$this->User->rollback();  
                  		$return['error']  = 1;
						$return['status'] = 516;
                    
					}
			}
			else
			{	
				$this->User->set($save);
				$errors = $this->User->invalidFields();
				$this->User->set('errors',$errors);
				
				if(empty($errors))
				$errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
			}
		}
		else
		{
			    $errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
		}
		$this->response($return);
	}
	
  	/*------------------------ User Register  ------------------------------*/
 	public function user_register() 
    {	$return = array();
		
		if($this->process_authentication($this->request->data))
		{
		// echo exec('whoami'); die;
		
		$this->loadModel('User');
		$this->set('PageHeading', __('Register'));
		$return = array();
		$save = $this->request->data;	
		$requestFields = $this->getRequestFields();
		if($requestFields)
		{
			$save = $requestFields;	
			if(!isset($save['User']))
			$save['User'] = $requestFields;			
			
			$this->User->set($save);
			if(isset($save['User']) && (!isset($save['User']['u_username']) || !isset($save['User']['username']) 
			|| !isset($save['User']['password']) 
			) )
			{
				$errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
			}else
			if($this->User->validates() && isset($save['User']))
			{  $save['User']['u_type'] = 0;
			   $save['User']['u_status'] = 2;
		
				// Check For Image
				if(isset($_FILES['u_photo']['name']) && $_FILES['u_photo']['name']!='')
				$save['User']['u_photo'] = $this->profile_image($_FILES);
				if(isset($save['u_photo']))
				unset($save['u_photo']);
				// Check For Data URI Image
					if(isset($save['upload_image_datauri']) && $save['upload_image_datauri']!="")
					{$save['User']['u_photo'] = $this->profile_image($save,'','');
					}
				$uploadlocation = "../webroot/img/resources/profile_image/";
				$error=0;		
				if(isset($save['User']['u_photo']) && $save['User']['u_photo']!="" && file_exists($uploadlocation.$save['User']['u_photo']))	
					$error=0;
				else if(isset($save['User']['u_photo']) && $save['User']['u_photo']!="")
					$error=1;	
					
				if($error==0){	
					$this->User->begin();
					if($this->User->save($save))
					{	
						
						$this->User->commit();
						$id=$this->User->getLastInsertId();
						
						$access_token = $this->create_access_token($id);
						$update_login_records['User']['u_last_login'] = date("Y-m-d H:i:s");
						$update_login_records['User']['refresh_token'] =  bin2hex(openssl_random_pseudo_bytes(16));
						$this->User->create();
						$this->User->id= $id;
						$this->User->save($update_login_records);
						//mail("kusumratawa9@gmail.com", "test email", "test email message"); 
						$this->User->send_email($save,'send_reg_mail');
						$user_data = $this->User->find('first',array("conditions"=>array('u_id'=>$id)));
						$this->Session->setFlash("Registration completed successfully please login","default",
						array('class'=>'alert alert-success text-center'));
						
						$return['error']  = 0;
						$return['status'] = 517;
						$return['data'] = $user_data['User'];
						$return['other_data'] = array("u_photo_url"=>Router::url('/', true).'img/resources/profile_image/');
						
					}
					else
					{
						$this->User->rollback();  
						$return['error']  = 1;
						$return['status'] = 516;
						$return['data'] = "Problem in saving data";

                    
					}
				}
				else
				{		$return['error']  = 1;
						$return['status'] = 516;
						$return['data'] = "Problem in saving image";
				}
			
			}
			else
			{
				if(empty($save))
				{
					$return['error']  = 1;
					$return['status'] = 515;
					$return['data'] = 'Empty form submitted';
				}
				else
				{
				$this->User->set($save);
				$errors = $this->User->invalidFields();
				if(empty($errors))
				$errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
				
				$this->User->set('errors',$errors);
				}
			}
		}
		else
		{
			    $errors = "Please fill all the fields";
			
				$this->set(array(
				'errors'	=> 1,
				'msg' => $errors,
				'response'	=> $errors,
				'_serialize' => array('errors','msg','response')
				)); 
		}
		
		}
		
		if($return){	$this->send_response($return);}
	}
   
    /*------------------------ Login  ------------------------------*/
	
	public function login() 
	{ $return = array();
	
		if($this->process_authentication($this->request->data))
		{
		  $this->set('PageHeading', __('Login'));
	  $this->loadModel('User');
	  Configure::write('defalut_layout','defalut');
	
		$save = $this->request->data;	
		$requestFields = $this->getRequestFields();
		if($requestFields)
		{ 
		$save=$requestFields;
		
		if(!isset($save['User']))
		{	$save['User'] = $requestFields;	
		}
		$this->User->set($save);
		$this->User->validator()->remove('username', 'isUnique');
		$this->User->validator()->remove('u_username', 'isUnique');
		$this->User->validator()->remove('u_photo', 'rule1');
		$this->User->validator()->remove('u_phone', 'isUnique');
		if(isset($save['User']) && (!isset($save['User']['u_username']) || !isset($save['User']['password'])  ) )
			{
				$errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
			}else
        if($this->User->validates())
		 { 	
				$user = $this->User->loginUser(
				$save['User']['u_username'],
				AuthComponent::password($save['User']['password'])
				);
				
				 if(AuthComponent::user())
				$this->Auth->logout();
				//prd($user);
				if($user && $this->Auth->login($user)) 
				{ 	// update Last Login	
					//$user = AuthComponent::user();
					$user = $user['User'];
					
					if($user['u_status']==2)
					{
						$return['error']  = 1;
						$return['status'] = 521;
						$return['data'] = 'We will mail you once your acount is approved by admin';
						$this->Auth->logout();
					}
					else if($user['u_status']==0)
					{
						$return['error']  = 1;
						$return['status'] = 519;
						$return['data'] = 'Status is deactivated by admin';
						$this->Auth->logout();
					}
					else {
					$this->User->create();
					 $this->User->id= $user['u_id'];
					
					if(isset($save['User']['u_device_id']))
					$update_login_records['User']['u_device_id'] = $save['User']['u_device_id'];
					
					if(isset($save['User']['u_device_type']))
					$update_login_records['User']['u_device_type'] = $save['User']['u_device_type'];
					
					$update_login_records['User']['u_last_login'] = date("Y-m-d H:i:s");
					$update_login_records['User']['refresh_token'] =  bin2hex(openssl_random_pseudo_bytes(16));
					
					unset($requestFields['User']['password']);
					unset($user['password']);
				
					$this->User->save($update_login_records);
					/*----------- Remove Device Id For other users -----------*/
					
					if(isset($save['User']['u_device_id']) && $save['User']['u_device_id']!="")
					{
						$exist_data = $this->User->find('all',
						array("conditions"=>array('u_device_id="'.$save['User']['u_device_id'].'" and u_id!="'.$user['u_id'].'"'),
						"fields"=>array('u_id','u_device_id')
						));
						
						if($exist_data)
						{//prd($exist_data);
							foreach($exist_data as $exist_data_arrray)
							{
								$logged_out_users = array();
								$this->User->create();
								$this->User->id= $exist_data_arrray['User']['u_id'];
								$logged_out_users['User']['u_device_id'] = '';
								$logged_out_users['User']['access_token'] = '';
								$logged_out_users['User']['access_token_expiration'] = '0000-00-00 00:00:00';
							
								$this->User->save($logged_out_users);
							}
						}
						
					}
					
					$access_token = $this->create_access_token($user['u_id']);
					
					$user['access_token'] = $access_token;
					$user['refresh_token'] = $update_login_records['User']['refresh_token'];
					$this->Session->setFlash('You have logged in successfully','default',
					array('class' => 'alert alert-success text-center'));
						$return['error']  = 0;
						$return['status'] = 518;
						$return['data'] = $user;
						$return['other_data'] = array("u_photo_url"=>Router::url('/', true).'img/resources/profile_image/');
					}
				
				} 
				else 
				{//echo "cfcfgh";die;
					$this->Session->setFlash('Invalid u_email or password, try again.','default',
					array('class' => 'alert alert-error text-center'));
					$return['error']  = 1;
					$return['status'] = 519;
				}
        } 
		else 
		{
           		$this->User->set($save);
				$errors = $this->User->invalidFields();

				if(empty($errors))
				$errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
				
				$this->User->set('errors',$errors);
      }
	}
	else
	{
				$errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
			}
		}
		
		if($return){
			if(!isset($return['data']))
			$return['data'] = '';
			$this->set(array(
				'errors'	=>  $return['error'],
				'msg' => $this->getStatusMessage($return['status']),
				'response'	=> $return['data'],
				'_serialize' => array('errors','msg','response')
				)); 
		}
	
  }
   /*----------------- Login End ------------------------------*/
   /*------------------------ Country List  ------------------------------*/
	
	public function countries() 
	{ $return = array();
	
		
		  $this->set('PageHeading', __('Login'));
	 	  $this->loadModel('Country');
	  	  Configure::write('defalut_layout','defalut');
		  $this->loadModel('AllApi');
		  $data = $this->Country->find('all',array('fields'=>array('*')));
	/*	$new_value = $data['Country'][];
		unset($arr[n]);
		array_unshift($arr, $new_value);*/
		  
			$return['error']  = 0;
			$return['status'] = 529;
			$return['data'] = $data;
		
		if($return)
		$this->send_response($return);
  }
   /*----------------- Login End ------------------------------*/
   
  /*------------------------ Logout  ------------------------------*/
 	public function logout() 
  	{
	  	$return = array();
	  $save = $requestFields = $this->getRequestFields();
		$return = array();
		if($this->compare_token($requestFields))
		{	
			$this->set('PageHeading', __('Logout'));
			$this->loadModel('User');
			Configure::write('defalut_layout','defalut');
				$this->User->create();	
				$this->User->id= $save['u_id'];
				
				$update_login_records['User']['u_device_id'] = '';
				$update_login_records['User']['u_device_type'] = '';
				$update_login_records['User']['access_token'] = '';
				$update_login_records['User']['refresh_token'] = '';
				$update_login_records['User']['access_token_expiration'] = '';
				$this->User->save($update_login_records);
				
				$this->Session->setFlash('You have logged out successfully','default',
				array('class' => 'alert alert-success text-center'));
				$return['error']  = 0;
				$return['status'] = 532;
				$return['data'] = 'You have logged out successfully';

		 
		
			
		}
		if($return)
		$this->send_response($return);
  	}
  /*------------------------ END  Logout  ------------------------------*/
   /*------------------------ Profile Image  ------------------------------*/
   public function profile_image($save,$user='',$create_thumbs='')
   {	$img = '';	
		if((isset($save['u_photo']['name']) && $save['u_photo']['name']!='') 
		|| (isset($save['upload_image_datauri']) && $save['upload_image_datauri']!="")){
			
			$uploadlocation = "../webroot/img/resources/profile_image/";
			$name = $img = '';
			$ext = array('image/jpg'=>'.jpg','image/jpeg'=>'.jpeg','image/gif'=>'.gif','image/png'=>'.png');
			
			if(isset($save['upload_image_datauri']) && $save['upload_image_datauri']!="")
			{   $image_array =  explode(';base64,',$save['upload_image_datauri']);
				$image_ext =  explode("data:",$image_array[0]);
				if(isset($image_ext[1]))
				$image_ext = $image_ext[1];
				$image_ext = $ext[$image_ext];
				$uri = $image_array[1];
				$img = $name = $this->getToken(8) . $image_ext;
				file_put_contents($uploadlocation.$name, base64_decode($uri));
			
			}
			else{
			$name = $save['u_photo']['name'];
				$img = $this->Commonfunctions->generate_file_name($name);	
				
				$result = $this->Upload->upload($save['u_photo'],$uploadlocation,$img);
			if($create_thumbs=='')
			 return $img;//die;
			}
			
			if($create_thumbs=='create_thumb'){
			// create thumbnil
			 // $uploadlocation = "../webroot/images/banner/";
			 $image_destination = $uploadlocation.$img;
			 $size = getimagesize($image_destination);
			 	$height = '';
				if($size[1] > 60 ) {
				if($size[0] > 60 )   
				$height = 60; 	
				$image_parts = explode(".", $img);
				$thumbnail_destination = $uploadlocation.'60/'.$img;
				$this->Commonfunctions->create_thumb($image_destination,$thumbnail_destination,60,$height);
				}
			 	
			    if($size[1] > 250 ) {
				if($size[0] > 250 )   
				$height = 250;   
				$image_parts = explode(".", $img);
				$thumbnail_destination = $uploadlocation.'250/'.$img;
				$this->Commonfunctions->create_thumb($image_destination,$thumbnail_destination,250,$height);
				}
				// unlink prev image
				if(!empty($user) && $user['u_photo'])
				{	$uploadlocation = WWW_ROOT.'/img/resources/profile_image/';
				    if(file_exists($uploadlocation.$user['u_photo']))
					unlink($uploadlocation.$user['u_photo']);
					if(file_exists($uploadlocation.'60/'.$user['u_photo']))
					unlink($uploadlocation.'60/'.$user['u_photo']);
					if(file_exists($uploadlocation.'250/'.$user['u_photo']))
					unlink($uploadlocation.'250/'.$user['u_photo']);
				}
			}
		}
		return $img;
							
  }
   /*------------------------ END Profile Image  ------------------------------*/
 
  
   /*------------------------ Get Sch User Register  ------------------------------*/	
 
  public function user_detail() 
   {		
		$this->loadModel('SchUser');
		$this->set('PageHeading', __('User Register'));
		$return = array();
		$save = $this->request->data;	
		$requestFields = $this->getRequestFields();
		if($requestFields)
		{
			$save = $requestFields;		
			
			if(!isset($save['id']) )
			{	
				$errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
			}else
			if($save['access_token']== Configure::read('profileID'))
			{
				$user_data = $this->SchUser->find('first',array("conditions"=>array('sch_u_id'=>$save['id'])));
				if(empty($user_data))
				{
					$return['error']  = 1;
					$return['status'] = 515;
					$return['data'] = 'User not found';
				}
				else
				{
					$return['error']  = 0;
					$return['status'] = 524;
					$return['data'] = $user_data['SchUser'];
				}
			
			}
			else
			{
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = 'Access token missing';
				
			}
		}
		else
		{
			    $errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
		}
		
		
		$this->response($return);
	
   }
  /*--------------------  END Get Sch User Register  -----*/
  
  /*------------------------ Forgot password Sch User  ------------------------------*/	
 
  public function forgotpassword() 
   {	$return = array();
	
		if($this->process_authentication($this->request->data))//$this->isSigned())
		{	
	
		$this->set('PageHeading', __('User ForgotPassword'));
		$return = array();
		$save = $this->request->data;			
		$requestFields = $this->getRequestFields();
		if($requestFields)
		{
			$save = $requestFields;		
			
			if(!isset($save['email_address'])  )
			{	
				$errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
			}else
			
			{
			/*	$user_data = $this->User->find('first',array("conditions"=>array(
																'OR' => array(
																'u_username' => $save['email_address'],
																'username' => $save['email_address'],
																'u_phone' => $save['email_address'],
																),
															)
														));*/
				$user_data = $this->User->find_user($save['email_address']);										
				
				if(empty($user_data))
				{
						$errors = "Email/Username/Phone does not exist.";
						$return['error']  = 1;
						$return['status'] = 513;
						$return['data'] = $errors;
				}
				else{
					$save['email_address'] =   $user_data['User']['username'];
				$save['user_name'] = $save['email_address'];
				if($user_data)
				$save['user_name'] = $user_data['User']['u_username'];//.' '.$user_data['SchUser']['LastName']; 
				$password = $this->getToken(8);
				
				 $temp['User']['password'] =$save['password'] = $password;
				$this->loadModel('User');
				$this->User->create();
				$this->User->id = $user_data['User']['u_id'];
				$this->User->save($temp);
				
					//$save['email_address'] = 	'kusumratawa9@gmail.com';
					$this->User->send_email($save,'sch_forgotpassword_mail');
					$return['data'] = "Email sent successfully";
					$return['status'] = 524;
					$return['error']  = 0;
				
				}
			
			}
			
		}
		else
		{
			    $errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
		}
		}
		if($return){
			$this->set(array(
				'errors'	=>  $return['error'],
				'msg' => $this->getStatusMessage($return['status']),
				'response'	=> $return['data'],
				'_serialize' => array('errors','msg','response')
				)); 
		}
		
		
	
   }

  /*--------------------  END Forgot password Sch User  -----*/
  
  /*------------------------ Create Data URI From Image  ------------------------------*/ 
  public function datauri()
  {  
	  if(isset($_FILES['product_image']['name']) &&  $_FILES['product_image']['name']!="")
	  {
			$name = $_FILES['product_image']['name'];
			$img = $this->Commonfunctions->generate_file_name($name);
			$uploadlocation = "../webroot/img/resources/profile_image/";
			$this->Upload->upload($_FILES['product_image'],$uploadlocation,$img);
			
			$size = getimagesize($uploadlocation.$img);
			if($size[0]<100 && $size[1]<100)
			{	echo 'less_size';exit();
	  		}
			$image = $img;
			$type = pathinfo($image, PATHINFO_EXTENSION);
			$data = file_get_contents($uploadlocation.$image);
			$dataUri = 'data:image/' . $type . ';base64,' . base64_encode($data);
			// unlink image
			$uploadlocation = WWW_ROOT.'/img/resources/profile_image/';
			unlink($uploadlocation.$img);
			echo $dataUri; exit();
	  }
	  exit();
  }
   /*------------------------ END Create Data URI From Image  ------------------------------*/ 
   /*----------------------- Get Friends List ------------------------*/
    public function friendlist()
	{	$save = $requestFields = $this->getRequestFields();
		$return = array();
		
		if($this->compare_token($requestFields))
		{	
			if(!isset($save['u_id']))
			{
				$errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
			}
		else{
			$this->loadModel('User');
				try{
				$u_id = $requestFields['u_id'];
				$fields = array('u_username','u_id','u_photo');
				$joins = array();
				$conditions = array();		
				/*-------- User History ---------*/		
				
				
			  $message_data['pageCount'] = '';
			  if(isset($this->params['paging']) && $this->params['paging']!='')
			  {	
				$this->paginate = array(
						'joins' => $joins,
						'fields'=>$fields,
						'limit' => 30,
						'order' => array(
							'u_id' => 'desc'
						)
					);
								
				$data = $this->paginate($this->User,$conditions);				
				$paging_array = $this->params['paging'];
				
				$message_data['pageCount'] = $paging_array['User']['pageCount'];
			  }
			  else
			  {
				  	$data = $this->User->find("all",array('joins' => $joins,'fields'=>$fields, 'conditions'=>$conditions));
					
			  }
				if(isset($this->params['paging']) && isset($this->request->named['page']) && $this->request->named['page']>$paging_array['User']['pageCount'])
				{
				 $return['error']  = 1;
			 	 $return['status'] = 528;
			 	 $return['data'] = '';
				}
				else
				{	if(empty($data))
					{
				 $return['error']  = 1;
				 $return['status'] = 528;
				 $return['data'] = '';
				}
					else
					{
					  $return['error']  = 0;
					  $return['status'] = 512;
					  $return['data'] = $data;
					  $return['other_data'] = array("u_photo_url"=>Router::url('/', true).'img/resources/profile_image/');
					}
				}
			}
			catch (NotFoundException $e) {
				  $return['error']  = 1;
			  	  $return['status'] = 528;
			 	  $return['data'] = "Record Not Found";
				  return $return;
			  }
			}
		}
		if($return)
		$this->send_response($return);
		
	}
   /*------------------------ END Get Friends List -------------------*/
  public function allapi()
	{
		$this->loadModel('AllApi');
		$allApis = $this->AllApi->find('all',array('fields'=>array('*')));
		prd($allApis);
		
		echo date('Y-m-d H:i:s'); exit;
	}
}

?>