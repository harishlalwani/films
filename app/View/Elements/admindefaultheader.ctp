<?php 
$user = '';
if(AuthComponent::user())
$user = AuthComponent::user();
?>
<div id="header" class="navbar navbar-inverse navbar-fixed-top">
<div class="navbar-inner"><div class="container-fluid">
<a class="brand" href="<?=$this->html->url(array("controller"=>"users","action"=>"login"))?>">
    <?php echo $this->Html->image("page9_titleimg.png", array('class'=>'center', 'border' =>'0','alt'=>'logo'));?>
</a>

<div class="top-nav ">
<ul class="nav pull-right top-menu">
<?php if($user) { ?>
<li class="dropdown">

<a href="#" class="dropdown-toggle" data-toggle="dropdown">
	
	<?php
	$photo = "";
	if(isset($user['photo']))
	$photo = $user['photo'];
    echo $this->Global->get_user_images($photo,array("class"=>"small_profile","size"=>"60"));
	?>
<span class="username">

<?php echo 'welcome ' ; echo ucfirst($user['username']); ?>
</span><b class="caret">
</b>
</a>
<ul class="dropdown-menu"><li>
<a href="<?=$this->html->url(array("controller"=>"users","action"=>"profile"))?>"><i class="icon-user">
</i> My Profile</a>
</li>
<li>
<a href="<?=$this->html->url(array("controller"=>"users","action"=>"logout"))?>">
<i class="icon-key"></i>
 Log Out</a>
</li>
</ul>
</li>
<?php } 
else { ?>
<li><a href="<?=$this->html->url(array("controller"=>"users","action"=>"login"))?>"> Login </a></li>
<?php } ?>
</ul>
</div>
</div>
</div>
</div>
