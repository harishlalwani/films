<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
		
	function __construct($id = false, $table = null, $ds = null) {
            parent::__construct($id, $table, $ds);
            $dbo = $this->getDataSource();
            $dbo->cacheMethods = false;
        }
	public function sanitize_data($data)
	{
		if(is_array($data))
		{	
			foreach($data as $key=>$value)
			{
				$data[$key] = Sanitize::html($value,array("remove"=>true,"quotes"=>ENT_NOQUOTES));
			}
			
		}
		else
		{
			$data = Sanitize::html($data,array("remove"=>true,"quotes"=>ENT_NOQUOTES));
		}
	 	return $data;
	
	}
	
	public function send_email($data,$type)
	{		  
				App::import('Model','Email');
				$Email = new Email();
			    $email_content = $Email->find("first",array("conditions"=>array("email_key"=>$type)));	
				$email_content = $email_content['eve_emailtemplates'];
				// config data
				App::import('Model','AdminUser');
				$AdminUser = new AdminUser();
			    $AdminUser = $AdminUser->find("first",array("conditions"=>array("id"=>1)));	
				
				// Config details
				App::import('Model','Config');
				$Config = new Config();
				$site_config = $Config->find("all",array("fields"=>array('*')));
				$config = array();
				foreach($site_config as $data_array)
				{ $data_array = $data_array['eve_configs'];
				  $config[$data_array['config_key']] = $data_array['config_value'];	
				}
				$site_name = 'EzEve';
				
				if(isset($config) && $config['site_title']!="")
				$site_name = $config['site_title'];
				
				$Email = new CakeEmail();
				$Email->from(array($AdminUser['admin_users']['email'] => $site_name));
				$Email->emailFormat('html');
				$Email->subject($email_content['email_subject']);	
				
				if($type=='send_reg_mail') // user
				{   $data = $data['User'];
					$Email->to($data['username']);	
					$message = str_ireplace(array("{user_name}","{site_name}"),
					array($data['u_username'],$site_name),
					$email_content['email_body']);
				
				}				
				else if($type=="forgotpassword_mail")
				{	$data = $data['User'];
					$Email->to($data['username']);
					// set activation link 
				    $activation_link="<a href='".Router::url('/', true)."resetpassword?code=".$data['u_activation_key']."' 
					 style='width: 47%;text-align: center;background: #ff0009;border: none;font-size: 15px;
color: #fff;font-weight: 600;padding: 7px 15px;clear:both;text-decoration:none;'>Click Here</a>";
				  	$message = $activation_link; 
							
					$message = str_ireplace(array("{user_name}","{activation_link}","{site_name}"),
					array($data['u_firstname'].' '.$data['u_lastname'],$activation_link,$site_name),
					$email_content['email_body']);
				}
				else if($type=="sch_forgotpassword_mail")
				{
					$Email->to($data['email_address']);
					// set activation link 
				/*	$activation_link=' <a href="http://cgtechnosoft.net/EzEve/resetpassword?key='.$data['key'].'" 
					style="pointer-events: all; text-decoration:none; cursor: pointer;box-shadow: 0 5px #a73b82;
					font-size: 16px;text-transform: uppercase;color: #fff;background-color: #c855a1;border: 1px solid #c855a1;
					border-radius: 29px;padding: 9px 20px;"> Reset Password </a>';*/
					
				  //	$message = $activation_link; 
							
					$message = str_ireplace(array("{user_name}","{password}","{site_name}"),
					array($data['user_name'],$data['password'],$site_name),
					$email_content['email_body']);				
					
				}
				else if($type=="passwordreset_mail")
				{	$data = $data['User'];
					$Email->to($data['username']);
					// set activation link 
				    $activation_link="<a href='".Router::url('/', true)."forgotpassword' 
					 style='width: 47%;text-align: center;background: #ff0009;border: none;font-size: 15px;
color: #fff;font-weight: 600;padding: 7px 15px;clear:both; text-decoration:none;'>Reset Password</a>";
				  	$message = $activation_link; 							
					$message = str_ireplace(array("{user_name}","{password_link}","{site_name}"),
					array($data['u_firstname'].' '.$data['u_lastname'],$activation_link,$site_name),
					$email_content['email_body']);
				}
				else if($type=="friend_request_sent")
				{	$data = $data;
					$Email->to($data['recvr_email']);
				
					$message = str_ireplace(array("{user_name}","{sender_user}","{friend_code}","{access_functionality}","{message}","{site_name}"),
					array($data['user_name'],$data['sender_user'],$data['friend_code'],$data['access_functionality'],$data['message'],$site_name),
					$email_content['email_body']);
				}
				else if($type=="friend_request_accept")
				{	$data = $data;
					$Email->to($data['recvr_email']);
				
					$message = str_ireplace(array("{user_name}","{sender_user}","{access_functionality}","{message}","{site_name}"),
					array($data['user_name'],$data['sender_user'],$data['access_functionality'],$data['message'],$site_name),
					$email_content['email_body']);
				}
					else if($type=="invitation_added_mail_to_friends")
				{	$data = $data;
					$Email->to($data['recvr_email']);
				
					$message = str_ireplace(array("{user_name}","{sender_user}","{site_name}"),
					array($data['user_name'],$data['sender_user'],$site_name),
					$email_content['email_body']);
				}
			
				//prd($Email->send($message));
				if(TEST_MODE){
					if($Email->send($message))
						return array("success"=>true,"error"=>false,"message"=>'Mail Sent');
					else
						return array("success"=>false,"error"=>true,"message"=>'Mail not sent');	
					
				}
				return array("success"=>false,"error"=>true,"message"=>'Local host');
	
	}
	public function job_payscale_type()
	{ return array("0"=>"full time","1"=>"part time","2"=>"casual");
	}
	public function u_industry_sector()
	{
	return	array(
										
"0"=>"Accident  Health Insurance",

"1"=>"Advertising Agencies",

"2"=>"Air Delivery &amp; Freight Services",

"3"=>"Airlines",

"4"=>"Apparel Stores",

"5"=>"Appliances",

"6"=>"Application Software",

"7"=>"Asset Management",

"8"=>"Auto Dealerships",

"9"=>"Auto Manufacturers - Major",

"10"=>"Auto Parts",

"11"=>"Beverages - Brewers",

"12"=>"Beverages - Soft Drinks",

"13"=>"Beverages - Wineries &amp; Distillers",

"14"=>"Biotechnology",

"15"=>"Broadcasting - Radio",

"16"=>"Broadcasting - TV",

"17"=>"Business Equipment",

"18"=>"Business Services",

"19"=>"Business Software &amp; Services",

"20"=>"Chemicals - Major Diversified",

"21"=>"Cigarettes",

"22"=>"Cleaning Products",

"23"=>"Communication Equipment",

"24"=>"Computer Based Systems",

"25"=>"Computers Wholesale",

"26"=>"Conglomerates",

"27"=>"Consumer Services",

"28"=>"Credit Services",

"29"=>"Dairy Products",

"30"=>"Department Stores",

"31"=>"Drug Delivery",

"32"=>"Drug Manufacturers - Major",

"33"=>"Drug Stores",

"34"=>"Education &amp; Training Services",

"35"=>"Electric Utilities",

"36"=>"Electronic Equipment",

"37"=>"Electronics Stores",

"38"=>"Electronics Wholesale",

"39"=>"Entertainment - Diversified",

"40"=>"Food - Major Diversified",

"41"=>"Food Wholesale",

"42"=>"Gaming Activities",

"43"=>"Gas Utilities",

"44"=>"General Building Materials",

"45"=>"General Contractors",

"46"=>"General Entertainment",

"47"=>"Grocery Stores",

"48"=>"Health Care Plans",

"49"=>"Home Furnishing Stores",

"50"=>"Home Furnishings &amp; Fixtures",

"51"=>"Home Improvement Stores",

"52"=>"Housewares &amp; Accessories",

"53"=>"Information &amp; Delivery Services",

"54"=>"Information Technology Services",

"55"=>"Insurance Brokers",

"56"=>"Internet Information Providers",

"57"=>"Internet Service Providers",

"58"=>"Internet Software &amp; Services",

"59"=>"Investment Brokerage",

"60"=>"Jewelry Stores",

"61"=>"Life Insurance",

"62"=>"Lodging",

"63"=>"Machine Tools &amp; Accessories",

"64"=>"Major Integrated Oil &amp; Gas",

"65"=>"Marketing Services",

"66"=>"Medical Instruments &amp; Supplies",

"67"=>"Medical Laboratories &amp; Research",

"68"=>"Medical Practitioners",

"69"=>"Mortgage Investment",

"70"=>"Movie Production, Theaters",

"71"=>"Multimedia &amp; Graphics Software",

"72"=>"Music &amp; Video Stores",

"73"=>"Networking &amp; Communication Devices",

"74"=>"Nonmetallic Mineral Mining",

"75"=>"Office Supplies",

"76"=>"Oil &amp; Gas",

"77"=>"Oil &amp; Gas Drilling &amp; Exploration",

"78"=>"Oil &amp; Gas Equipment &amp; Services",

"79"=>"Packaging &amp; Containers",

"80"=>"Paper &amp; Paper Products",

"81"=>"Personal Computers",

"82"=>"Personal Products",

"83"=>"Personal Services",

"84"=>"Photographic Equipment &amp; Supplies",

"85"=>"Processed &amp; Packaged Goods",

"86"=>"Property &amp; Casualty Insurance",

"87"=>"Property Management",

"88"=>"Publishing - Books",

"89"=>"Publishing - Newspapers",

"90"=>"Publishing - Periodicals",

"91"=>"Real Estate Development",

"92"=>"Recreational Goods, Other",

"93"=>"Recreational Vehicles",

"94"=>"Rental &amp; Leasing Services",

"95"=>"Residential Construction",

"96"=>"Resorts &amp; Casinos",

"97"=>"Restaurants",

"98"=>"Scientific &amp; Technical Instruments",

"99"=>"Security &amp; Protection Services",

"100"=>"Security Software &amp; Services",

"101"=>"Shipping",

"102"=>"Small Tools &amp; Accessories",

"103"=>"Specialty Eateries",

"104"=>"Specialty Retail, Other",

"105"=>"Sporting Activities",

"106"=>"Sporting Goods",

"107"=>"Staffing &amp; Outsourcing Services",

"108"=>"Technical &amp; System Software",

"109"=>"Telecom Services - Domestic",

"110"=>"Textile - Apparel Clothing",

"111"=>"Textile - Apparel Footwear &amp; Accessories",

"112"=>"Textile Industrial",

"113"=>"Tobacco Products, Other",

"114"=>"Toy &amp; Hobby Stores",

"115"=>"Toys &amp; Games",

"116"=>"Trucking",

"117"=>"Trucks &amp; Other Vehicles",

"118"=>"Waste Management",

"119"=>"Water Utilities",

"120"=>"Wholesale, Other",

"121"=>"Wireless Communications",

	
								  );
	}
	public  function yearDropdown($title=''){ 
        //echo each year as an option
		if($title=='')
		$title = 'Select Year';
		$year = '<option value="">'.$title.'</option>';     
        for ($i=date('Y');$i>=date('Y')-100;$i--){ 
        $year.="<option value=".$i.">".$i."</option>n";     
        } 
		return $year;
	 } 
}
