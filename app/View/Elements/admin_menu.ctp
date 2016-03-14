<div id="sidebar" class="nav-collapse collapse">
<div class="sidebar-toggler hidden-phone">
</div>
<div class="navbar-inverse"><form class="navbar-search visible-phone" />
<input type="text" class="search-query" placeholder="Search" />
</form>
</div>
<?php
$menu = array(
			 "dashboard"=>array(
			 					"title"=>"Dashboard",
								"url"=>$this->html->url(array("admin"=>true,"controller"=>"index","action"=>"index","prefix"=>true)),
								"icon"=>"icon-dashboard",			 				   
							   ),
			
			 "settings"=>array(
								"title"=>"Settings",
								"url"=>"javascript:;",
								"icon"=>"icon-cogs",
								"submenu"=>
								    array(
										"general_setting"=> array(
										"title"=>"General Settings",
										"url"=>$this->html->url(array("admin"=>true,"controller"=>"index",
										"action"=>"config","prefix"=>true)),										
										),
										"static_pages"=> array(
										"title"=>"Static Pages",
										"url"=>$this->html->url(array("admin"=>true,"controller"=>"index",
										"action"=>"staticpages","prefix"=>true)),										
										),
										"email_templates"=> array(
										"title"=>"Email Templates",
										"url"=>$this->html->url(array("admin"=>true,"controller"=>"index",
										"action"=>"emailtemplate","prefix"=>true)),										
										),
									)
																																																														 				   
							   ),
		
			 "logout"=>array(
							    "title"=>"Logout",
								"url"=>$this->html->url(array("admin"=>true,"controller"=>"index","action"=>"logout","prefix"=>true)),
								"icon"=>"icon-key",			 				   
							   ),
							   				    
			 );
		
?>

<ul class="sidebar-menu">
	<?php
		foreach($menu as $key=>$value){
		
		 ?>
         
                <li class="<?php if(isset($value['submenu'])) echo 'has-sub';?>">
                <a href="<?php echo $value['url']?>" class="<? if($this->get('active_tabs')==$key) echo 'active_tab'?>"><span class="icon-box">
                <i class="<? if(isset($value['icon'])) echo $value['icon']; else echo 'iicon-cogs'; ?>">
                </i>
                </span> <?php echo $value['title']?>
                <?php if(isset($value['submenu'])){ ?>
                <span class="arrow"> </span>
                <?php } ?>
                </a>
                <?php if(isset($value['submenu'])){ ?>
                <ul class="sub " style=" <? if($this->get('active_tabs')==$key) echo 'display:block';?>">
                <?php foreach($value['submenu'] as $sub_key=>$sub_value){ ?>
                	<li>
                		<a class="" href="<?php echo $sub_value['url']?>"><?php echo $sub_value['title']?></a>
              		</li>
                <?php } ?>
                </ul>
                <?php } ?>
                </li>
	<?php } ?>
</ul>
</div>
