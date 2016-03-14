<?php
/**
 * Application level View Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
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
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Helper', 'View');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class GlobalHelper extends Helper {
	var $helpers = array('html');
	public function get_user_images($image_name="",$array=array())
	{ 
		$class="";
		if(isset($array['class']) && $array['class']!='')
		$class = $array['class'];
		if($image_name=='')
		$image_name =  "profile-pic.jpg";

	   if($image_name!='' && file_exists(WWW_ROOT.'/img/resources/profile_image/'.$image_name))
	   {
	    $image_url =  '/img/resources/profile_image/';
		
		if(isset($array['size']) && $array['size'] && file_exists(WWW_ROOT.'/img/resources/profile_image/'.$array['size'].'/'.$image_name))
		$image_url.=$array['size'].'/';
		$image_name = $image_url.$image_name;
	    }
		$selected_imag = $this->html->image($image_name,array("alt"=>"profile image","border"=>0,"class"=>$class));
		return $selected_imag ;
	}
	public function display_date_time($date,$time='')
	{
		$date = explode(' ',$date);
		$time = $date[1];
		$date = explode('-',$date[0]);
		$date = $date[2].'-'.$date[1].'-'.$date[0];
		if($time=='') 
		return date("jS F, Y", strtotime($date)).' '.$time;
		else return date("jS F, Y", strtotime($date));
	}
	public function getLastQuery()
    {
        $dbo = $this->getDatasource();
        $logs = $dbo->getLog();
        $lastLog = end($logs['log']);
        return $lastLog['query'];
    }

}
