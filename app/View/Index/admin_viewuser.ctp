<? 
$user = $data['User'];

?>
<div class="row-fluid">
<div class="span12">
<div class="widget">
<div class="widget-title">
<h4>
<i class="icon-user">
</i><?=$user['u_username']?>'s Profile</h4>
<span class="tools">
<a href="javascript:;" class="icon-chevron-down">
</a>
<a href="javascript:;" class="icon-remove">
</a>
</span>
</div>
<div class="widget-body">
<div class="span3">
<div class="text-center profile-pic">
<?     
$u_photo = $user['u_photo'];
echo $this->Global->get_user_images($u_photo,array("alt"=>"profile image","class"=>"user_250_profile_image","size"=>"250"));
?>
</div>
<ul class="nav nav-tabs nav-stacked">
<li>
<a href="javascript:void(0)">
<i class="icon-coffee">
</i> Profile </a>
</li>
<? if($user['u_type']==0){?>

<li>
<a href="javascript:void(0)">
<i class="icon-paper-clip">
</i> Resume </a>
</li>
<li>
<a href="javascript:void(0)">
<i class="icon-picture">
</i> 
Applied Jobs
</a>

</li>
<? } 
else {?>
<li>
<a href="javascript:void(0)">
<i class="icon-picture">
</i> 
 Jobs
</a>

</li>
<? } ?>
</ul>
<? if($user['u_login_type']!='' && $user['u_login_token']!=""){ ?>
<ul class="nav nav-tabs nav-stacked">

<li> Connected Via</li>
<? if($user['u_login_type']=='0'){ ?>
<li>
<a href="javascript:void(0)">
<i class="icon-facebook">
</i> Facebook</a>
</li>
<? } if($user['u_login_type']=='1'){ ?>
<li>
<a href="javascript:void(0)">
<i class="icon-twitter">
</i> Twitter</a>
</li>
<? } if($user['u_login_type']=='2'){ ?>
<li>
<a href="javascript:void(0)">
<i class="icon-linkedin">
</i> LinkedIn</a>
</li>
<? } ?>

</ul>
<? } ?>
</div>
<div class="span6">
<h4><?=$user['u_username']?><br />
<small><? if($user['u_type']==0) echo 'Individual user'; else echo 'Company';?></small>
</h4>
<table class="table table-borderless">
<tbody>
<tr>
<td class="span2">First Name :</td>
<td> <?=$user['u_firstname']?> </td>
</tr>
<tr>
<td class="span2">Last Name :</td>
<td> <?=$user['u_lastname']?> </td>
</tr>
<? if(isset($data['rb_countries']['name'])) {?>
<tr>
<td class="span2">Country :</td>
<td> <?=$data['rb_countries']['name']?> </td>
</tr>
<? } ?>
<tr>
<td class="span2"> Email :</td>
<td> <?=$user['username']?> </td>
</tr>
<tr>
<td class="span2">City :</td>
<td> <? echo $user['u_city']?> </td>
</tr>
<? if($user['u_type']==1){ ?>
<tr>
<td class="span2">Address :</td>
<td> <? echo $user['u_address1'].' <br />'.$user['u_address2']?> </td>
</tr>
<tr>
<td class="span2">Employment Type:</td>
<td> <? 
$u_employee_type = array("0"=>"full time","1"=>"part time","2"=>"casual");
echo $u_employee_type[$user['u_employee_type']];?> </td>
</tr>

<? } ?>



</tbody>
</table>
<h4>About</h4>
<p class="push"><?=$user['u_aboutme']?></p>
<h4 class="hide">Skills</h4>
<table class="table table-borderless hide">
<tbody>
<tr>
<td class="span1">
<span class="label label-inverse">HTML</span>
</td>
<td>
<div class="progress progress-success progress-striped">
<div style="width: 90%" class="bar">
</div>
</div>
</td>
</tr>
<tr>
<td class="span1">
<span class="label label-inverse">CSS</span>
</td>
<td>
<div class="progress progress-warning progress-striped">
<div style="width: 85%" class="bar">
</div>
</div>
</td>
</tr>
<tr>
<td class="span1">
<span class="label label-inverse">Javascript</span>
</td>
<td>
<div class="progress progress-success progress-striped">
<div style="width: 60%" class="bar">
</div>
</div>
</td>
</tr>
<tr>
<td class="span1">
<span class="label label-inverse">PHP</span>
</td>
<td>
<div class="progress progress-success progress-striped">
<div style="width: 40%" class="bar">
</div>
</div>
</td>
</tr>
<tr>
<td class="span1">
<span class="label label-inverse">Photoshop</span>
</td>
<td>
<div class="progress progress-warning progress-striped">
<div style="width: 80%" class="bar">
</div>
</div>
</td>
</tr>
<tr>
<td class="span1">
<span class="label label-inverse">Node.js</span>
</td>
<td>
<div class="progress progress-danger progress-striped">
<div style="width: 45%" class="bar">
</div>
</div>
</td>
</tr>
<tr>
<td class="span1">
<span class="label label-inverse">Java</span>
</td>
<td>
<div class="progress progress-danger progress-striped">
<div style="width: 10%" class="bar">
</div>
</div>
</td>
</tr>
</tbody>
</table>

</div>
<div class="span3">
<h4> Others</h4>
<ul class="icons push">
<li>
<i class="icon-hand-right">
</i>
<strong> Member Since</strong>
<br />
<em><? echo $this->Global->display_date_time($user['u_create_datetime']);	?></em>
<br />

</li>
<li>
<i class="icon-hand-right">
</i>
<strong>Last Login</strong>
<br />
<em><? echo $this->Global->display_date_time($user['u_last_login']);	?></em>

</li>
<li>
<i class="icon-hand-right">
</i>
<strong>Last Profile Update</strong>
<br />
<? echo $this->Global->display_date_time($user['u_update_datetime']);	?></li>
</ul>
<h4>Current Status</h4>
<? if($user['u_status']==0){?>
<div class="alert alert-success">
Active
 </div>
 <? } 
 else { ?>
<div class="alert alert-error">
Inactive
 </div> 
 <? } ?>

</div>
<div class="space5">
</div>
</div>
</div>
</div>
</div>