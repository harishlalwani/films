<?php
class EventController extends AppController 
{	public $name = 'Event';
	public $components = array('RequestHandler', 'Commonfunctions');
	// allow user actions
	public function beforeFilter() 
	{ 
		$this->Auth->allow();
		//$date = date("Y-m-d",strtotime(date("Y-m-d", strtotime(date('Y-m-d'))) . " +40 day"));
		
	}
  	public function encript()
	{
						/*$return['error']  = 0;
						$return['status'] = 517;
						$return['data'] = "cv";
						$return['other_data'] = array("u_photo_url"=>Router::url('/', true).'img/resources/profile_image/');
						$this->send_response($return);*/
	//prd($_SERVER);
	// add friends
	 prd($computedSignature = hash_hmac('sha256', 'POST!@!2015-02-27!@!/availablestatus.json!@!u_id:27!@!driver_avl_status:1','91f48647a429f0db33c59b1a221fc59f' , FALSE));

	//prd($computedSignature = hash_hmac('sha256', "POST!@!2015-02-03!@!/addevent.json!@!u_id:1!@!event_name:Event10!@!event_type:3!@!alarm_type:1!@!alarm_note:lets attend Event9!@!member_per_event:10!@!total_event_perday:4!@!first_event:2015-02-03 14:00:00!@!last_event:2015-03-03 05:00:00!@!location:1!@!orgraniser:1!@!last_attend_date:2014-03-03 05:00:00!@!attended_number:20!@!next_plan_date:2015-04-06  06:00:00!@!friend_user_id:2!@!monthly_date:10!@!date:2015-02-14",'f0c299f2bc13d8aa627d51be4b7f9037' , FALSE));
	 prd($computedSignature = hash_hmac('sha256', 'POST!@!2015-02-02!@!/eventslist.json!@!u_id:1','ec12ad8a01b84d814ab7c3847ae37294' , FALSE));
	
	//$this->compare_token($requestFields);
	}
	/*---------- Author@Kusum : Add Event -------*/
	public function addevent()
	{
		$this->writeLog();
		$return = array();
		$requestFields = $this->getRequestFields();
		if(1||$this->compare_token($requestFields))	
		if($requestFields)
		{	$this->loadModel('Event');
			if(!isset($requestFields['Event']))
			$requestFields['Event'] = $requestFields;
			$save = $requestFields;
				$this->Event->set($save);
			if(isset($save['Event']) && (!isset($save['Event']['event_name']) || !isset($save['Event']['event_type']) 
			|| !isset($save['Event']['member_per_event']) || !isset($save['Event']['total_event_perday']) 
			|| !isset($save['Event']['first_event']) || !isset($save['Event']['last_event']) 
			|| !isset($save['Event']['alarm_type'])  
			) )
			{	$errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
			}
		 	else {
			
			$requestFields['Event']['first_event'] = date('Y-m-d H:i:s', strtotime($requestFields['Event']['first_event']));
			$requestFields['Event']['last_event'] = date('Y-m-d H:i:s', strtotime($requestFields['Event']['last_event']));
			$error = 0;	
			if($requestFields['Event']['event_type'] == 2)
			{	if(!isset($requestFields['Event']['event_weekly_days']) && empty($requestFields['Event']['event_weekly_days']))
					$error = 1;
				else
					{
						//$requestFields['Event']['event_weekly_days'] = json_decode($requestFields['Event']['event_weekly_days']);
					}
			}
			else if($requestFields['Event']['event_type'] == 3)
			{
				if(!isset($requestFields['Event']['monthly_date']) && empty($requestFields['Event']['monthly_date']))
					$error = 1;
				/*else
					$requestFields['Event']['monthly_date'] = date('Y-m-d H:i:s', strtotime($requestFields['Event']['monthly_date']));*/
			}
			
			$requestFields['Event']['added_by_user'] = $requestFields['u_id'];
			$requestFields['Event']['event_status'] = '1';
			if(isset($save['Event']['event_id']) && $save['Event']['event_id']!="")
			     {
			      // Please run search query that id exist in db or not
			     	$this->Event->create();
			     	$this->Event->id = $save['Event']['event_id'];
			     }
			    else			     
					$this->Event->begin();
		
			//$this->Event->create();
			if($this->Event->save($requestFields) && $error==0)
			{	
				if(isset($save['Event']['event_id']) && $save['Event']['event_id']!="")
			     {
			      $lastInsertID = $save['Event']['event_id'];
			     }
			    else			     
					$lastInsertID = $this->Event->getLastInsertId();

				

				/*-------- Save Time Slote in different table -------*/
				if(0 && $requestFields['Event']['event_type'] == 1)
				{
					$dayHours = 24;
					$slotHours = $dayHours / $requestFields['Event']['event_type'];
					$eveTiming = array();
					$limit = 0;
					for($i = 1; $limit < $dayHours; $i++)
					{
						$limit = $i * $slotHours;
						
						$eveTiming[$i]['EventTiming']['et_eve_id'] = $lastInsertID;
						$eveTiming[$i]['EventTiming']['et_eve_time'] = date('H:i:s', strtotime("$limit:00:00"));
					}
				
					$this->loadModel('EventTiming');
					$this->EventTiming->saveAll($eveTiming);
				}
				$this->Event->commit();
				$data = $this->Event->find("first",array("conditions"=>array("event_id"=>$lastInsertID)));
				$return['error']  = 0;
				$return['status'] = 531;
				$return['data'] = $data['Event'] ;
			}
			else
			{	
				$return['error']  = 1;
				if($error==1){
					$return['status'] = 515;
					$return['data'] = "Please fill all the fields";;
				}
				else
				{	$return['status'] = 527;
					$return['data'] = '';
				}
			}
		   }
		}
		else
		{
			$return['error']  = 1;
			$return['status'] = 528;
			$return['data'] = '';
		}
		
		if($return)
		$this->send_response($return);
		
	}
	/*---------- END Add Event -------*/
	/*---------- Author@Kusum : Event List -------*/
    public function eventslist()
	{	
		$this->Event->Behaviors->load('Containable');
		$this->writeLog();
		$save = $requestFields = $this->getRequestFields();
		$return = array();
		if(1|| $this->compare_token($requestFields))
		{	
			if(!isset($save['u_id']))
			{
				$errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
			}
		else{
			$this->loadModel('Event');
			$this->Event->bindModel(
						array(
							'hasMany' => array(
				                'EveAttendedStatus' => array(
				                    'className' => 'EveAttendedStatus',
				                    'foreignKey' => 'eve_id'
				                )
				             ),
							
							));
				try{
				$added_by_user = $u_id = $requestFields['u_id'];
				$friend_id = 0;
				if(isset($requestFields['friend_id']))
				$added_by_user = $friend_id = $requestFields['friend_id'];
				$date = "'".date('Y-m-d')."'";	
				$date_element = date('Y-m-d');
				if(isset($requestFields['date']))
				{
					$date = "'".date('Y-m-d', strtotime($requestFields['date']))."'";	
					$date_element = date('Y-m-d', strtotime($requestFields['date']));	
				}
				
				$fields = array('Event.*','eve_users.u_id','eve_users.u_username','eve_locations.loca_id','eve_locations.loca_name'
				,'eve_organisers.org_id','eve_organisers.org_name','Photo.ph_path');
				$joins = array(
							array(
								'table' => 'eve_users',
								'type' => 'INNER ',
								'conditions' => '`eve_users`.`u_id` = `Event`.`added_by_user`',
							),
							array(
								'table' => 'eve_locations',
								'type' => 'LEFT ',
								'conditions' => '`loca_id` = `Event`.`location`',
							),
							array(
								'table' => 'eve_organisers',
								'type' => 'LEFT ',
								'conditions' => '`org_id` = `Event`.`orgraniser`',
							),
							
							array(
								'table' => 'eve_photos',
								'alias' => 'Photo',
								'type' => 'LEFT ',
								'conditions' => '`ph_event_id` = `Event`.`event_id`',
							),
								
						);
						
				$match_condition = array(' ( 
									 (added_by_user = '.$added_by_user.' and friend_user_id = 0) 
									 or (added_by_user != 0 and friend_user_id = '.$added_by_user.') 
									 )');
				if($friend_id!=0)
				{
							$match_condition = array(' ( 
									 (added_by_user = '.$added_by_user.' and friend_user_id = 0) 
									 or (added_by_user != 0 and friend_user_id = '.$added_by_user.') 
									  or (added_by_user = '.$u_id.' and friend_user_id = '.$added_by_user.') 
									 )');
				}
				$conditions = array(' `'.$date.'` between DATE(`first_event`) and DATE(`last_event`) ',
							  'OR'=> array(
							  		'event_type'=>1, 
							  		array(
									'event_type'=>2,
									'FIND_IN_SET("'.strtolower(date('D',strtotime($date_element))).'",`LOWER(event_weekly_days)`)'
									),
									array(
									'event_type'=>3,
									'FIND_IN_SET("'.strtolower(date('d',strtotime($date_element))).'",`LOWER(monthly_date)`)'
									),
									
								  ),	
 							  'AND' => array(
									$match_condition,
									)
				);				
			
			  /*-------- Event History ---------*/		
			  $message_data['pageCount'] = '';
			  $contain = array('EveAttendedStatus.date_slot =  '.$date);	
			  if(isset($this->params['paging']) && $this->params['paging']!='')
			  {	
				$this->paginate = array(
						'joins' => $joins,
						'fields'=>$fields,
						'limit' => 30,
						'order' => array(
							'event_posted' => 'desc'
						)
					);
				
						
				$data = $this->paginate($this->Event,$conditions);				
				$paging_array = $this->params['paging'];
				
				$message_data['pageCount'] = $paging_array['Event']['pageCount'];
			  }
			  else
			  {
			  	//$this->Event->contain(array('EveAttendedStatus.date_slot <=  "'.$date.'"'));	
			  	
				  	$data = $this->Event->find("all",array('joins' => $joins,'fields'=>$fields, 'conditions'=>$conditions , 'contain' => $contain));
				}
			  if(isset($this->params['paging']) && isset($this->request->named['page']) && $this->request->named['page']>$paging_array['Event']['pageCount'])
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
					  $this->writeResponseLog($data);
					  $return['error']  = 0;
					  $return['status'] = 512;
					  $return['data'] = $data;
					  $return['other_data'] = array('event_image_url'=>Router::url('/', true).Configure::read('photoPath'));
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
	/*---------- END Event List -------*/
	/*---------- Author@Kusum : Add Location -------*/
	public function addlocation()
	{
		$save = $requestFields = $this->getRequestFields();
		$return = array();
		if($this->compare_token($requestFields))
		{
		$this->loadModel('Location');
		$this->set('PageHeading', __('Add Location'));
		$return = array();
		$save = $this->request->data;	
		$requestFields = $this->getRequestFields();
		if($requestFields)
		{
			$save = $requestFields;	
			if(!isset($save['Location']))
			$save['Location'] = $requestFields;			
			
			$this->Location->set($save);
			if(isset($save['Location']) && (!isset($save['Location']['loca_name']) ) )
			{
				$errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
			}else
			if($this->Location->validates() && isset($save['Location']))
			{ 
			$save['Location']['loca_added_by'] = $save['Location']['u_id'];
				if(1){	

				 if(isset($save['Location']['loca_id']) && $save['Location']['loca_id']!="")
			     {
			      // Please run search query that id exist in db or not
			     	$this->Location->create();
			     	$this->Location->id = $save['Location']['loca_id'];
			     }
			     else			     
					$this->Location->begin();
					if($this->Location->save($save))
					{	
						$this->Location->commit();
						

						if(isset($save['Location']['loca_id']) && $save['Location']['loca_id']!="")
			     {
			      $id = $save['Location']['loca_id'];
			     }
			     else
			  		$id=$this->Location->getLastInsertId();
						
						$Location_data = $this->Location->find('first',array("conditions"=>array('loca_id'=>$id)));
						$this->Session->setFlash("Location added successfully.","default",
						array('class'=>'alert alert-success text-center'));						
						$return['error']  = 0;
						$return['status'] = 530;
						$return['data'] = $Location_data['Location'];
					}
					else
					{
						$this->Location->rollback();  
						$return['error']  = 1;
						$return['status'] = 516;

                    
					}
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
				$this->Location->set($save);
				$errors = $this->Location->invalidFields();
				if(empty($errors))
				$errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
				
				$this->Location->set('errors',$errors);
				}
			}
		}
		else
		{
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = "Please fill all the fields";
		}
		
		}
		if($return)
		$this->send_response($return);
		
	
	}
	/*---------- END Add Location -------*/
	
	/*---------- Author@Kusum : List Of Location -------*/
	public function locationlist()
	{	$save = $requestFields = $this->getRequestFields();
		$return = array();
		if(1||$this->compare_token($requestFields))
		{	
			if(!isset($save['u_id']))
			{
				$errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
			}
			else
			{
				$this->loadModel('Location');
				try{
				
				$added_by_user = $u_id = $requestFields['u_id'];
				$friend_id = 0;
				if(isset($requestFields['friend_id']))
				$added_by_user = $friend_id = $requestFields['friend_id'];
				
				$fields = array('Location.*','eve_users.u_id','eve_users.u_username');
				$joins = array(
							array(
								'table' => 'eve_users',
								'type' => 'INNER ',
								'conditions' => '`eve_users`.`u_id` = `Location`.`loca_added_by`',
							),
						
						);
				$match_condition = array(' ( 
									 (loca_added_by = '.$added_by_user.' and loca_friend_id = 0) 
									 or (loca_added_by != 0 and loca_friend_id = '.$added_by_user.') 
									 )');
				if($friend_id!=0)
				{
							$match_condition = array(' ( 
									 (loca_added_by = '.$added_by_user.' and loca_friend_id = 0) 
									 or (loca_added_by != 0 and loca_friend_id = '.$added_by_user.') 
									  or (loca_added_by = '.$u_id.' and loca_friend_id = '.$added_by_user.') 
									 )');
				}
				$conditions = array($match_condition);				
				
				if(isset($requestFields['loca_id']) && $requestFields['loca_id']!="")
					array_push($conditions,array("loca_id"=>$requestFields['loca_id']));
				
			  	
				$data = $this->Location->find("all",array('joins' => $joins,'fields'=>$fields, 'conditions'=>$conditions));
				
				if(!empty($data) && isset($requestFields['loca_id']) && $requestFields['loca_id']!="")
				  $data = $data['Location'];	
			  
			  	if(empty($data))
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
	/*---------- END List Of Location -------*/
	
	/*---------- Author@Kusum : Add Organiser -------*/
	public function addorganiser()
	{
		$save = $requestFields = $this->getRequestFields();
		$return = array();
		if($this->compare_token($requestFields))
		{
		$this->loadModel('Organiser');
		$this->set('PageHeading', __('Add Organiser'));
		$return = array();
		$save = $this->request->data;	
		$requestFields = $this->getRequestFields();
		if($requestFields)
		{
			$save = $requestFields;	
			if(!isset($save['Organiser']))
			$save['Organiser'] = $requestFields;			
			
			$this->Organiser->set($save);
			
			if(isset($save['Organiser']) && (!isset($save['Organiser']['org_name']) ))
			{
				$errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
			}else
			if($this->Organiser->validates() && isset($save['Organiser']))
			{ 
			$save['Organiser']['org_added_by'] = $save['Organiser']['u_id'];
				if(1){
					 if(isset($save['Organiser']['org_id']) && $save['Organiser']['org_id']!="")
			     {
			      // Please run search query that id exist in db or not
			     $this->Organiser->create();
			     $this->Organiser->id = $save['Organiser']['org_id'];
			     }
			     else
			     $this->Organiser->begin();
					
					if($this->Organiser->save($save))
					{	
						$this->Organiser->commit();
						if(isset($save['Organiser']['org_id']) && $save['Organiser']['org_id']!="")
			      $id=$save['Organiser']['org_id'];
			      else
			      $id=$this->Organiser->getLastInsertId();
						$Organiser_data = $this->Organiser->find('first',array("conditions"=>array('org_id'=>$id)));
						$this->Session->setFlash("Organiser added successfully.","default",
						array('class'=>'alert alert-success text-center'));						
						$return['error']  = 0;
						$return['status'] = 530;
						$return['data'] = $Organiser_data['Organiser'];
					}
					else
					{
						$this->Organiser->rollback();  
						$return['error']  = 1;
						$return['status'] = 516;

                    
					}
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
				$this->Organiser->set($save);
				$errors = $this->Organiser->invalidFields();
				if(empty($errors))
				$errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
				
				$this->Organiser->set('errors',$errors);
				}
			}
		}
		else
		{
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = "Please fill all the fields";
		}
		
		}
		if($return)
		$this->send_response($return);
		
	
	}
	/*---------- END Add Organiser -------*/
	
	/*---------- Author@Kusum : List Of Organiser -------*/
	public function orgraniserlist()
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
			else
			{
				$this->loadModel('Organiser');
				try{
				
				$added_by_user = $u_id = $requestFields['u_id'];
				$friend_id = 0;
				if(isset($requestFields['friend_id']))
				$added_by_user = $friend_id = $requestFields['friend_id'];
				
				$fields = array('Organiser.*','eve_users.u_id','eve_users.u_username');
				$joins = array(
							array(
								'table' => 'eve_users',
								'type' => 'INNER ',
								'conditions' => '`eve_users`.`u_id` = `Organiser`.`org_added_by`',
							),
						
						);
				$match_condition = array(' ( 
									 (org_added_by = '.$added_by_user.' and org_friend_id = 0) 
									 or (org_added_by != 0 and org_friend_id = '.$added_by_user.') 
									 )');
				if($friend_id!=0)
				{
							$match_condition = array(' ( 
									 (org_added_by = '.$added_by_user.' and org_friend_id = 0) 
									 or (org_added_by != 0 and org_friend_id = '.$added_by_user.') 
									  or (org_added_by = '.$u_id.' and org_friend_id = '.$added_by_user.') 
									 )');
				}
				$conditions = array($match_condition);				
				
				if(isset($requestFields['org_id']) && $requestFields['org_id']!="")
					array_push($conditions,array("org_id"=>$requestFields['org_id']));
				
			  	
				$data = $this->Organiser->find("all",array('joins' => $joins,'fields'=>$fields, 'conditions'=>$conditions));
				
				if(!empty($data) && isset($requestFields['org_id']) && $requestFields['org_id']!="")
				  $data = $data['Organiser'];	
			  
			  	if(empty($data))
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
	/*---------- END List Of Organiser -------*/

/*---------- Author@Harish : Update attend status -------*/
	public function attendstatus()
	{	$save = $requestFields = $this->getRequestFields();
		$return = array();
		if(1||$this->compare_token($requestFields))
		{	
			if(!isset($save['u_id']))
			{
				$errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] 	= $errors;
			}
			else
			{
				$this->loadModel('EveAttendedStatus');
				$save['EveAttendedStatus'] 	= $save;
				$save['EveAttendedStatus']['eve_friend_id'] =	$save['EveAttendedStatus']['u_id'];
				$save['EveAttendedStatus']['status_update_time'] =	date("Y-m-d H:i:s");
				
				$this->EveAttendedStatus->save($save);
				$msg = "Event status updated";
				$return['error']  = 0;
				$return['status'] = 539;
				$return['data'] 	= $msg;
			}
		}
		if($return)
		$this->send_response($return);
	}
	/*---------- END List Of Organiser -------*/
	/*---------- Author@Harish : Event Time Line List -------*/
    public function getTimeLine()
	{	$save = $requestFields = $this->getRequestFields();
		$return = array();
		$this->loadModel('Event');
		$this->writeLog();
		if(1 || $this->compare_token($requestFields))
		{	
			if(!isset($save['u_id']))
			{
				$errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data']   = $errors;
			}
		else{
			
			try{
				/*$u_id = $requestFields['u_id'];
				$friend_id = 0;
				if(isset($requestFields['friend_id']))
				$u_id = $requestFields['friend_id'];*/
			
				$added_by_user = $u_id = $requestFields['u_id'];
				$friend_id = 0;
				if(isset($requestFields['friend_id']))
				$added_by_user = $friend_id = $requestFields['friend_id'];
			
				$this->Event->recursive = 0;
				if(isset($requestFields['page']) && $requestFields['page']!="")
				$page = $requestFields['page'];
				else
				$page = 1;
				
				$match_condition = ' ( 
									 (added_by_user = '.$added_by_user.' and friend_user_id = 0) 
									 or (added_by_user != 0 and friend_user_id = '.$added_by_user.') 
									 )';
				$match_condition2 = ' ( 
								 (invi_added_by = '.$added_by_user.' and invi_friend_id = 0) 
								 or (invi_added_by != 0 and invi_friend_id = '.$added_by_user.') 
								 )';					 
									 
				if($friend_id!=0)
				{
							$match_condition = ' ( 
									 (added_by_user = '.$added_by_user.' and friend_user_id = 0) 
									 or (added_by_user != 0 and friend_user_id = '.$added_by_user.') 
									  or (added_by_user = '.$u_id.' and friend_user_id = '.$added_by_user.') 
									 )';
							$match_condition2 = ' ( 
							 (invi_added_by = '.$added_by_user.' and invi_friend_id = 0) 
							 or (invi_added_by != 0 and invi_friend_id = '.$added_by_user.') 
							  or (invi_added_by = '.$u_id.' and invi_friend_id = '.$added_by_user.') 
							 )'; 
				}
				
				
				$this->paginate = array('Event'=>array('limit'=>10,"user_id"=>$u_id,"page"=>$page,
				"eve_condition"=>$match_condition,"invi_condition"=>$match_condition2));
				$data = $this->paginate('Event');
				//print_r($data);
				$paging_array = $this->params['paging'];
		
			  if(isset($this->params['paging']) && isset($this->request->named['page']) && $this->request->named['page']>$paging_array['event_time_line']['pageCount'])
		   {
				 $return['error']  = 1;
			 	 $return['status'] = 528;
			 	 $return['data']   = '';
		   }
				else
				{	if(empty($data))
					{
				 $return['error']  = 1;
				 $return['status'] = 528;
				 $return['data']   = '';
				}
				else
				{
				  $return['error']  = 0;
				  $return['status'] = 512;
				  $return['data']   = $data;
				  $return['other_data'] = array('pageCount'=>$paging_array['Event']['pageCount']);
				  
				}
				}
			}
				catch (NotFoundException $e) {
				  $return['error']  = 1;
			  	$return['status'] = 528;
			 	  $return['data'] = "Record Not Found";
				}
			}
		}
		
		if($return)
		$this->send_response($return);
		
	}
	/*---------- END TimeLine List -------*/

	/*---------- Author@Harish : Event Status List -------*/
	public function getEventStatus()
	{ $this->writeLog();

		$save = $requestFields = $this->getRequestFields();
		$range = (isset($requestFields['range']) && $requestFields['range'] != '')? $requestFields['range'] :
		'all'  ;
			$return = array();
			$this->loadModel('Event');

			if(1 || $this->compare_token($requestFields))
			{	
				if(!isset($save['u_id']))
				{
					$errors = "Please fill all the fields";
					$return['error']  = 1;
					$return['status'] = 515;
					$return['data'] = $errors;
				}
			else{
				if(isset($save['friend_id']) && $save['friend_id'] !== '')
				$save['u_id'] = $save['friend_id'];
						
						$this->Event->bindModel(
						array(
							'hasMany' => array(
				                'EveAttendedStatus' => array(
				                    'className' => 'EveAttendedStatus',
				                    'foreignKey' => 'eve_id',
				                     'order' => 'status_update_time Desc'
				                )
				            ),
							/*'belongsTo' => array(
				                'EveLocation' => array(
				                    'className' => 'Location',
				                    'foreignKey' => 'location'
				                ),
															
				                'Organiser' => array(
				                    'className' => 'Organiser',
				                    'foreignKey' => 'orgraniser'
				                ),
															
				                'User' => array(
				                    'className' => 'User',
				                    'foreignKey' => 'added_by_user'
				                ),
				        )*/));
						$datetime = $this->getdatetime();
						if($range == "all")
						  $cond = 'EveAttendedStatus.status_update_time < "'.$datetime.'"';
						  if($range == "week")
						  $cond = 'EveAttendedStatus.status_update_time BETWEEN "'. $datetime.'" - INTERVAL 7 DAY AND "'. $datetime.'"';
						  if($range == "month")
						  $cond = 'EveAttendedStatus.status_update_time BETWEEN "'. $datetime.'" - INTERVAL 30 DAY AND "'. $datetime.'"';
						$this->Event->Behaviors->load('Containable');
						$options['recursive'] = 1;
					/*	$temp_u_id = (isset($save['friend_id']))? $save['friend_id'] : $save['u_id']; 
						$options['conditions'] = array('added_by_user' => $temp_u_id );*/
						$added_by_user = $u_id = $requestFields['u_id'];
						$friend_id = 0;
						if(isset($requestFields['friend_id']))
						$added_by_user = $friend_id = $requestFields['friend_id'];
						$match_condition = array(' ( 
							 (added_by_user = '.$added_by_user.' and friend_user_id = 0) 
							 or (added_by_user != 0 and friend_user_id = '.$added_by_user.') 
							 )');
						if($friend_id!=0)
						{
						$match_condition = array(' ( 
							 (added_by_user = '.$added_by_user.' and friend_user_id = 0) 
							 or (added_by_user != 0 and friend_user_id = '.$added_by_user.') 
							  or (added_by_user = '.$u_id.' and friend_user_id = '.$added_by_user.') 
							 )');
						}
						$options['conditions'] = $match_condition;
						$options['contain'] = $cond;
						//$options['order'] = array('EveAttendedStatus.status_update_time desc');
						$res = $this->Event->find('all',$options);


						$view = new View;
						//$this->writeLog($view->element('sql_dump'));
						//print_r($view->element('sql_dump'));
						//Csv file download condition
						
            	if(isset($requestFields['download']) && $requestFields['download'])
							{
								if (!file_exists(WWW_ROOT.'files/csv')) {
								    mkdir(WWW_ROOT.'files/csv', 0744, true);
								}
								$name = 'export'.strtotime(date('Y-m-d H:i:s')).'.csv';
								$path = WWW_ROOT.'files/csv/'.$name;
								touch($path);
					$name = 'export'.strtotime(date('Y-m-d H:i:s')).'.csv';	
					//$path = Configure::read('S3FolderPath').Configure::read('csvPath').'/'.$name;
						 $headers = array( 
	                'Event'=>array( 
	                    'event_name' => 'Event Name', 
	                    'date_slot' => 'Date Slot', 
	                    'time_slot' => 'Time Slot', 
	                    'status' => 'Status' 
	                  ) 
	            		); 
	            	// Add headers to start of file 
	            	$file = fopen($path , 'w');
								fputcsv($file, $headers['Event']);
								foreach ($res as $row):
									$r = array();
									$r['Event']['event_name'] = $row['Event']['event_name'];
									foreach ($row['EveAttendedStatus'] as $key=>$cell):
										foreach ($cell as $k => $value) {
											if($k == 'time_slot' || $k == 'date_slot' || $k == 'status' )
											$r['Event'][$k] = $value;
										}
										fputcsv($file, $r['Event']);	
									endforeach;
								endforeach;
								fclose($file);
								$res = "http://".$_SERVER['SERVER_NAME']."/"."files/csv/".$name;
							}
					if(empty($res))
					{
						$return['error']  = 1;
				  	$return['status'] = 528;
				 	  $return['data'] = "";
          }
          else
          {			
						$return['error']  = 0;
						$return['status'] = 545;
						$return['data'] = $res;			
					}
				}
			}
			
			if($return)
			$this->send_response($return);
	}
	/*---------- END TimeLine List -------*/

	/*---------- Author@Harish : upload Photo -------*/
	public function uploadPhoto()
	{ 
/*$result = $this->s3DeleteObject(Configure::read('photoPath').'/EzEveUser1_1_2015-03-02_13:05:08.jpg');
		prd($result);*/
		$save = $requestFields = $this->getRequestFields();
		$this->loadModel('Photo');

		if(1 || $this->compare_token($requestFields))
		{	
			if($requestFields && !empty($_FILES))
			{
				$name    				= $_FILES['photo']['name'];
				$this->loadModel('User');
				$user_data = $this->User->find('first',array('fields'=>array('u_username'),'conditions'=>array('u_id'=>$requestFields['u_id'])));
				$ext = explode(".",$name);
				$imgname = $user_data['User']['u_username'].'_'.$requestFields['u_id'].'_'.date('Y-m-d').'_'.date('H:i:s');//.'.'.$ext[1];
			 	
				//$imgname 				= $this->Commonfunctions->generate_file_name($name); 
				
			    $uploadlocation = Configure::read('photoPath');	
  			   // $result = $this->uploadFile($uploadlocation,$_FILES['photo']);
  				$result = $this->s3upload($_FILES['photo'], $imgname,$uploadlocation);
			
				if($result['error']==0)
    			{
    				$save['Photo']['ph_user_id']	=	$save['u_id'];
    				$save['Photo']['ph_name']    	= $name;//(isset($save['name']) && $save['name'] != '')? $save['name'] : '' ;
    				$save['Photo']['ph_path'] 		=	$imgname.'.'.$ext[1];
					if(isset($save['friend_id']) && $save['friend_id'] != '')
    				$save['Photo']['ph_friend_id']	=	$save['friend_id'];
				
					$this->Photo->begin();
    				if($this->Photo->save($save))
    				{$lastInsertID = $this->Photo->getLastInsertId();	$this->Photo->commit();
    						$return['error']  = 0;
							$return['status'] = 545;
							$return['data'] = 'Photo saved successfully';
    				}
    				else
    				{
    					$return['error']  = 1;
							$return['status'] = 515;
							$return['data'] 	= 'Photo upload failed';
    				}	
    			}	
    			else
    			{
    				$return['error']  = 1;
						$return['status'] = 515;
						$return['data'] 	= 'File data corrupted';
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
			
		if($return)
		$this->send_response($return);
	}
	/*---------- END Photo Upload -------*/

		/*---------- Author@Harish : Link event Picture -------*/
	public function linkEventPicture()
	{   $this->writeLog();

		$save = $requestFields = $this->getRequestFields();
		$this->loadModel('Photo');

		if(1 || $this->compare_token($requestFields))
		{	
			if($requestFields)
			{
				if(!isset($save['u_id']) || !isset($save['ph_event_id']) || !isset($save['ph_event_id']))
				{
					$errors = "Please fill all the fields";
					$return['error']  = 1;
					$return['status'] = 515;
					$return['data'] = $errors;
				}
			else{
				
				$event_pic = $this->Photo->find("first",array("conditions"=>array("ph_event_id"=>$save['ph_event_id'])));
				if(empty($event_pic))
				$event_pic = $this->Photo->find("first",array("conditions"=>array("ph_id"=>$save['ph_id'])));
				if($event_pic)
				{
					/*------------ Remove attached image form event -----*/						
						if(isset($save['remove_element']))
					    {	$this->Photo->deleteAll(array('ph_id IN ('.$event_pic['Photo']['ph_id'].')'));
							
							$uploadlocation = WWW_ROOT.'/img/resources/gallery/';
							if(file_exists($uploadlocation.$event_pic['Photo']['ph_path']))
							unlink($uploadlocation.$event_pic['Photo']['ph_path']);
							$return['error']  = 0;
							$return['status'] = 548;
							$return['data'] 	= 'Photo deleted from event successfully';
						}	
						else
						{
								$update_save['Photo']['ph_event_id']	=	"";
								$this->Photo->create();
								$this->Photo->id=$event_pic['Photo']['ph_id'];
								$this->Photo->save($update_save);	
						}
										
				}
				if(!isset($save['remove_element']))
				{
					$save['Photo'] = $save;
					$save['Photo']['friend_id']	=	$save['Photo']['u_id'];
					$this->Photo->create();
					$this->Photo->id=$save['Photo']['ph_id'];
					if($this->Photo->save($save))
					{
						$return['error']  = 0;
						$return['status'] = 546;
						$return['data'] 	= 'Photo linked successfully';
					}
					else
					{
						$return['error']  = 1;
						$return['status'] = 515;
						$return['data'] 	= 'Photo Linking failed';
					}	
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
			
		if($return)
		$this->send_response($return);
	}
	/*---------- END Link Event Picture -------*/
	/*---------- Author@Kusum : Photo Galary List -------*/
    public function galarylist()
	{	
		$this->writeLog();
		$save = $requestFields = $this->getRequestFields();
		$return = array();
		if(1|| $this->compare_token($requestFields))
		{	
			if(!isset($save['u_id']))
			{
				$errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
			}
		else{
			$this->loadModel('Photo');
				try{
				$added_by_user = $u_id = $requestFields['u_id'];
				$friend_id = 0;
				if(isset($requestFields['friend_id']))
				$added_by_user = $friend_id = $requestFields['friend_id'];
				
				$fields = array('Photo.*','Event.event_id','Event.event_name','Event.first_event');
				$joins = array(
							array(
								'table' => 'eve_events',
								'alias' => 'Event',
								'type' => 'LEFT ',
								'conditions' => '`Photo`.`ph_event_id` = `Event`.`event_id`',
							  )								
						);
					$galry_cond = 	' ( 
									 (ph_user_id = '.$added_by_user.' and ph_friend_id = 0) 
									 or (ph_user_id != 0 and ph_friend_id = '.$added_by_user.') 
									 )';
				$match_condition = array($galry_cond);
				if($friend_id!=0)
				{			$galry_cond = ' ( 
									 (ph_user_id = '.$added_by_user.' and ph_friend_id = 0) 
									 or (ph_user_id != 0 and ph_friend_id = '.$added_by_user.') 
									  or (ph_user_id = '.$u_id.' and ph_friend_id = '.$added_by_user.') 
									 )';
							$match_condition = array($galry_cond );
				}
				 
				
			 $conditions = $match_condition;				
			
			  /*-------- Event History ---------*/		
			  $message_data['pageCount'] = '';
			 	$this->paginate = array(
						'joins' => $joins,
						'fields'=>$fields,
						'limit' => 20,
						'order' => array(
							'ph_posted' => 'desc'
						),
						'conditions' => $conditions
					);			
						
				$data = $this->paginate($this->Photo,$conditions);	
				
				$paging_array = $this->params['paging']; 
				$message_data['pageCount'] = $paging_array['Photo']['pageCount'];			 
				$message_data['image_url'] = Configure::read('S3FolderPath').Configure::read('photoPath').'/';//Router::url('/', true).Configure::read('photoPath');
				
				// get all currecnt events which is not linking with image
				$this->loadModel("Event");
				$date = "'".date('Y-m-d')."'";	
				$date_element = date('Y-m-d');
				$match_condition = '';
				 $match_condition = array(' ( 
									 (added_by_user = '.$added_by_user.' and friend_user_id = 0) 
									 or (added_by_user != 0 and friend_user_id = '.$added_by_user.') 
									 )');
				if($friend_id!=0)
				{
							$match_condition = array(' ( 
									 (added_by_user = '.$added_by_user.' and friend_user_id = 0) 
									 or (added_by_user != 0 and friend_user_id = '.$added_by_user.') 
									  or (added_by_user = '.$u_id.' and friend_user_id = '.$added_by_user.') 
									 )');
				}
				$eve_conditions = array(' `'.$date.'`<=DATE(`last_event`) ',
 							  'AND' => array(
									$match_condition,
									)
				);
				
			/*	'CASE (select count(ph_event_id) as ph_event from eve_photos where ph_event_id!="" and '.$galry_cond .')  WHEN  NOT 0 
							THEN 
							event_id NOT IN (select GROUP_CONCAT(ph_event_id) from eve_photos where ph_event_id!="" and '.$galry_cond .')
							ELSE
							1
							END',*/
							
				$message_data['eventlist'] = $this->Event->find("all",array('fields'=>array("event_id","event_name",'Event.first_event'), 'conditions'=>$eve_conditions ));
				
			  if(isset($this->params['paging']) && isset($this->request->named['page']) && $this->request->named['page']>$paging_array['Photo']['pageCount'])
			   { $return['error']  = 1;
			 	 $return['status'] = 528;
			 	 $return['data'] = '';
			   }
			  else
			   {	
				   if(empty($data))
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
					  $return['other_data'] = $message_data;
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
	/*---------- END Photo Galary List -------*/
	/*---------- Author@Kusum : Get Event Data -------*/
    public function eventimage()
	{	
		$this->writeLog();
		$save = $requestFields = $this->getRequestFields();
		$return = array();
		if(1|| $this->compare_token($requestFields))
		{	
			if(!isset($save['u_id']) || !isset($save['event_id']))
			{
				$errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
			}
		else{
			$this->loadModel('Photo');
				try{
				
				$fields = array('Photo.*','Event.event_id','Event.event_name','Event.first_event');
				$joins = array(
							array(
								'table' => 'eve_events',
								'alias' => 'Event',
								'type' => 'INNER ',
								'conditions' => '`Photo`.`ph_event_id` = `Event`.`event_id`',
							  )								
						);
			 $conditions = 	'ph_event_id = '.$save['event_id'].' ';
			 $img_data =  $this->Photo->find("first",array("fields"=>$fields,"conditions"=>$conditions,"joins"=>$joins));
			
			  /*-------- Event History ---------*/				 
			 $message_data['image_url'] = Router::url('/', true).Configure::read('photoPath');
				
				// get all currecnt events which is not linking with image
				
			  if(empty($img_data))
			   { $return['error']  = 1;
			 	 $return['status'] = 528;
			 	 $return['data'] = '';
			   }
			  else
			   {	  $return['error']  = 0;
					  $return['status'] = 512;
					  $return['data'] = $img_data;
					  $return['other_data'] = $message_data;
					
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
	/*---------- END Get Event Data -------*/
	
	/*---------- Author@Kusum : Snooze Event -------*/
	public function eventsnooze()
	{
		$return = array();
		$requestFields = $this->getRequestFields();
		if(1||$this->compare_token($requestFields))	
		if($requestFields)
		{	$this->loadModel('Event');
			$this->loadModel('Snooze');
			if(!isset($requestFields['Snooze']))
			$requestFields['Snooze'] = $requestFields;
			$save = $requestFields;
			$this->Snooze->set($save);
			if(isset($save['Snooze']) && (!isset($save['Snooze']['snz_event_id']) || !isset($save['Snooze']['snz_event_slot']) 
			|| !isset($save['Snooze']['snz_next_snooze']) || !isset($save['Snooze']['snz_time_slots']) 
			) )
			{	$errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
			}
		 	else { 
			// check event exist or not
			$save['Snooze']['snz_user_id'] = $save['Snooze']['u_id'];
			$event_data =  $this->Event->find("first",array("conditions"=>array("event_id"=>$save['snz_event_id'])));
			if(empty($event_data))
			{
				$return['error']  = 1;
				$return['status'] = 549;
				$return['data'] = "Please fill all the fields";;
			}
			else
			{
			// Check if snooze already done the update entry :
			$snooze_event =  $this->Snooze->find("first",array("conditions"=>
												array("snz_event_id"=>$save['Snooze']['snz_event_id'],
												"snz_event_slot"=>$save['Snooze']['snz_event_slot'])
								 ));	
			if($snooze_event)
			{
				$this->Snooze->create();	
				$this->Snooze->id = $snooze_event['Snooze']['snz_id'];	
			}
			else
			$this->Snooze->begin();	
			$requestFields['Snooze']['snz_last_updated'] = date('Y-m-d H:i:s');
			
			if($this->Snooze->save($requestFields))
			{			if(empty($snooze_event))     
						{
							$lastInsertID = $this->Snooze->getLastInsertId();
							$this->Snooze->commit();
						}else
						$lastInsertID = $snooze_event['Snooze']['snz_id'];
						
							/*-------- Save Time Slote in different table -------*/
					
						$data = $this->Snooze->find("first",array("conditions"=>array("snz_id"=>$lastInsertID)));
						$return['error']  = 0;
						$return['status'] = 550;
						$return['data'] = $data['Snooze'] ;
			}
			else
			{	
				$return['error']  = 1;
				if($error==1){
					$return['status'] = 515;
					$return['data'] = "Please fill all the fields";;
				}
				else
				{	$return['status'] = 527;
					$return['data'] = '';
				}
			}			
			}
			
		   }
		}
		else
		{
			$return['error']  = 1;
			$return['status'] = 528;
			$return['data'] = '';
		}
		
		if($return)
		$this->send_response($return);
		
	}
	/*---------- END Snooze Event -------*/
	
	/*---------- Author@Kusum : List Of Snoozed Event -------*/
	public function snoozelist()
	{
		$save = $requestFields = $this->getRequestFields();
		$return = array();
		if(1|| $this->compare_token($requestFields))
		{	
			if(!isset($save['u_id']))
			{
				$errors = "Please fill all the fields";
				$return['error']  = 1;
				$return['status'] = 515;
				$return['data'] = $errors;
			}
		else{
			$this->loadModel('Event');
			
				try{
				$added_by_user = $u_id = $requestFields['u_id'];
				$friend_id = 0;
				if(isset($requestFields['friend_id']))
				$added_by_user = $friend_id = $requestFields['friend_id'];
				$date = date("Y-m-d",strtotime(date("Y-m-d", strtotime(date('Y-m-d'))) . " +10 day"));
				$date = "'".$date."'";	
				$current_date = "'".date("Y-m-d")."'";	
				$date_element = $date;
			
				$fields = array('Event.*','eve_users.u_id','eve_users.u_username','eve_locations.loca_id','eve_locations.loca_name'
				,'eve_organisers.org_id','eve_organisers.org_name','Photo.ph_path','Snooze.snz_id','Snooze.snz_event_slot'
				,'Snooze.snz_next_snooze','Snooze.snz_time_slots','Snooze.snz_last_updated');
				$joins = array(
							array(
								'table' => 'eve_users',
								'type' => 'INNER ',
								'conditions' => '`eve_users`.`u_id` = `Event`.`added_by_user`',
							),
							array(
								'table' => 'eve_locations',
								'type' => 'LEFT ',
								'conditions' => '`loca_id` = `Event`.`location`',
							),
							array(
								'table' => 'eve_organisers',
								'type' => 'LEFT ',
								'conditions' => '`org_id` = `Event`.`orgraniser`',
							),
							
							array(
								'table' => 'eve_photos',
								'alias' => 'Photo',
								'type' => 'LEFT ',
								'conditions' => '`ph_event_id` = `Event`.`event_id`',
							),
							array(
								'table' => 'eve_snoozes',
								'alias' => 'Snooze',
								'type' => 'LEFT ',
								'conditions' => '`snz_event_id` = `Event`.`event_id`',
							),
								
						);
						
				$match_condition = array(' ( 
									 (added_by_user = '.$added_by_user.' and friend_user_id = 0) 
									 or (added_by_user != 0 and friend_user_id = '.$added_by_user.') 
									 )');
			if($friend_id!=0)
			 {
							$match_condition = array(' ( 
									 (added_by_user = '.$added_by_user.' and friend_user_id = 0) 
									 or (added_by_user != 0 and friend_user_id = '.$added_by_user.') 
									  or (added_by_user = '.$u_id.' and friend_user_id = '.$added_by_user.') 
									 )');
				}		
				
			 $conditions = array(' ( ( DATE(`first_event`) between  `'.$current_date.'` and  `'.$date.'`  ) or ( DATE(`last_event`) between  `'.$current_date.'` and  `'.$date.'` ) )',
							 	 'AND' => array(
									$match_condition,
									)
				);	
			 /*-------- Event History ---------*/		
			 $data = $this->Event->find("all",array('joins' => $joins,'fields'=>$fields, 'conditions'=>$conditions ));
			  if(empty($data))
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
					  $return['other_data'] = array('event_image_url'=>Router::url('/', true).Configure::read('photoPath'));
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
	/*---------- END List Of Snoozed Event  -------*/
}

?>