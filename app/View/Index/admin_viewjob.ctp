<div class="span12">
<div class="widget">
<div class="widget-title">
<h4>
<i class="icon-user">
</i>dummy's Profile</h4>
<span class="tools">
<a href="javascript:;" class="icon-chevron-down">
</a>
<a href="javascript:;" class="icon-remove">
</a>
</span>
</div>
<div class="widget-body" style="height:auto; overflow:hidden" >
<div class="span12">
<? if($data){ $job = $data['Job'];$user = $data['rb_users'];?>
<div class="post02_rightcontent job_view">
          <div class="post02_rightcontent_boxfirst">
            <div class="post02_rightcontent_boxfirst_left">
             <div class="post02_rightcontent_boxfirst_left_title"><?=$job['job_title']?></div>
             <div class="post02_rightcontent_boxfirst_left_greentext">
			 	<?=$data['rb_countries']['country_name']?>, <?=$data['rb_states']['state_name']?>
             </div>
             <div class="post02_rightcontent_boxfirst_left_text">
             <?=$job['company_industry']?> - <?=$job['job_payscale_type']?>
             <span style="font-style:italic;"><?=$this->Global->display_date_time($job['job_create_datetime'],1);?></span></div>
            </div>
            
          </div>
          <div class="post02_rightcontent_right_des">Description</div>
           <div class="post02_rightcontent_boxfirst">
          <div class="applyjob05_text"><?=$job['job_desc']?> </div>
    	  <div class="applyjob05_boxdiv">
        <div class="applyjob05_boxdiv_left">
          <div class="applyjob05_boxdiv_left_title">Minimum Requirements</div>
          <div class="applyjob05_boxdiv_left_content">
            <ul>
              <li><?=$job['job_min_qualy']?></li>
              
            </ul>
          </div>
        </div>
        <div class="applyjob05_boxdiv_left">
          <div class="applyjob05_boxdiv_left_title">Preferred Requirements</div>
          <div class="applyjob05_boxdiv_left_content">
            <ul>
              <li><?=$job['job_prefered_qualy']?></li>
            </ul>
          </div>
        </div>
      </div> 
      
     	 <div class="applyjob05_boxdiv_left_title">Responsibilities</div>
           <div class="applyjob05_boxdiv_left_content">
                <ul>
                  <li><?=$job['job_responsibilities']?></li>
                  
                </ul>
              </div>
        </div>
      <div class="post02_rightcontent_boxfirst">
      <br />
       <div class="applyjob05_boxdiv_left_title">Key Skills</div>
       <div class="applyjob05_boxdiv_left_content">
            <ul>
              <li><?=$job['job_keyskills']?></li>
              
            </ul>
          </div>  
       <div class="applyjob05_boxdiv_left_title">Min-Max Experience Required</div>
       <div class="applyjob05_boxdiv_left_content">
            <ul>
              <li><?=$job['job_min_experience'].' - '.$job['job_max_experience']?> Years</li>
              
            </ul>
          </div>      
       <div class="applyjob05_boxdiv_left_title">Pay scale</div>
       <div class="applyjob05_boxdiv_left_content">
            <ul>
              <li><?='$'.$job['job_min_payscale'].' - '.$job['job_max_payscale']?> PA</li>
              
            </ul>
          </div>   
      </div>
      <div class="post02_rightcontent_boxfirst">
      <br />     
       <div class="applyjob05_boxdiv_left_title">Job Location</div>
       <div class="applyjob05_boxdiv_left_content">
       		<?=$job['job_address']?>
            <br />
            <form>
           		 <input type="hidden" id="geocomplete" type="text" placeholder="Type in an address" value="<?=$job['job_address']?>" size="90" />
           		 <input type="hidden" name="lat" id="latitude_val" value="<?=$job['job_latitude']?>"  />
           		 <input type="hidden" name="lng" id="longitude_val" value="<?=$job['job_longitude']?>"  /> 
            </form>
          	      
          <div class="map_canvas" id="map_canvas"></div>
         <br /> 
       </div>             
     </div>   
      <div class="post02_rightcontent_boxfirst">
      <br />   
      	
       <div class="applyjob05_boxdiv_left_title">Job Posted By</div>
       <div class="applyjob05_boxdiv col-lg-12 col-md-12">
         
          <div class="round_img col-lg-2 col-md-2">         	
          <?	echo $this->Global->get_user_images($data['rb_users']['u_photo'],
				array("alt"=>"profile image","class"=>"user_250_profile_image","size"=>"250"));
		  ?>
          </div>
          <div class="user_content  col-lg-10 col-md-10">
          	 
          	 <div class="applyjob05_boxdiv_left_title col-lg-8 col-md-8"><?=$user['u_firstname'].' '.$user['u_lastname']?></div>
             <div class="text-right col-lg-4 col-md-4"><small style="font-style:italic" title="Member Since"> 
             <?=$this->Global->display_date_time($user['u_create_datetime'],'1')?>
             </small>
             </div>
             <div class="clear">
             	<small style="font-style:italic" >Company Number : </small><?=$user['u_company_number']?> , 
                <small style="font-style:italic" >Year trending : </small><?=$user['u_years_trading']?> , 
                <small style="font-style:italic" >No. of Employee : </small><?=$user['u_total_employees']?>
             </div>
              <div class="clear">
             	<small style="font-style:italic" >Address : </small>
				<?=$user['u_address1'].' '.$user['u_address2'].' , '.$user['u_city'].' , '.$user['u_postal_code'].' , '.
				$data['u_states']['name'].' , '.$data['u_countries']['name']?>  
                
             </div>
             
          </div>
          <? if($user['u_aboutme']){?>
          <div class="col-lg-12">
          <small style="font-style:italic" >About Company : </small>	
          <?=$user['u_aboutme']?>
          </div>
          <? } ?>
       </div>      
      </div>  
     <div class="post02_rightcontent_boxfirst">
        <br />
        <div class="applyjob05_boxdiv_left_title"> Applicants</div>
        <div class="applyjob05_boxdiv_left_content"  id="scrolling_content">
        	<?
			 if(!empty($applicants))
			 {  foreach($applicants as $applicants_array)
			 	{ 
					echo $this->Job->applicants_blocks($applicants_array);
				}
				?>
                <div id="more_data" class="text-center" style="display:none">
                <?=$this->html->Image('loading_circle.gif',array("alt"=>"Loading",))?>
                </div>
				<script>
                _scroll_settings.url=baseUrl+'index/applied_user_data/id:<?=$job['job_id']?>';
                _scroll_settings.on='1';
                _scroll_settings.current_offet=2;
                </script>
		<?	 }
			 else if($applicants && isset($applicants[0]['total']))
			 {
					echo $applicants[0]['total'];
			 }
			 ?>
        </div>  
      </div>
    </div>
<? } 
else{?>
<div class="post02_rightcontent">
	<div class="col-lg-12"><h2>No Record Exist</h2></div>
</div>
<? }?>

</div>
</div>
</div>
</div>
<style>
.applyjob05_boxdiv_left_content {
width: 84%;
}
</style>