<div id="login">

	  <?php echo $this->Form->create('AdminUser',array('url'=>array('admin'=>true,'controller'=>'index','action'=>'admin_login',)
	  ,"class"=>"form-vertical no-padding no-margin profile_form",'id'=>'loginform')); ?>

  <div class="lock"><i class="icon-lock"></i></div>
  <div class="control-wrap">
    <h4>Admin Login</h4>
    
    <div class="control-group  form-group">
      <div class="controls">
        <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
		   <?php echo $this->Form->input('username', array('type'=>'text','placeholder'=>'User Email','label'=>false,'div' => false,
		   'class'=>'form-control required')); ?>
	     </div>
      </div>
    </div>
    <div class="control-group form-group">
      <div class="controls">
        <div class="input-prepend"><span class="add-on"><i class="icon-key"></i></span>
          <?php echo $this->Form->input('password', array('type'=>'password','placeholder'=>'Password','label'=>false,'div' => false,
		   'class'=>'form-control required')); ?>

        </div>
        
        <div class="clearfix space5"></div>
      </div>
    </div>
  </div>
  <?=$this->Form->hidden('login_with_facebook',array("hidden","value"=>Configure::read('facebook_api'),"id"=>'login_with_facebook')); ?>
  <?=$this->Form->hidden('accessToken',array("hidden","value"=>'',"id"=>'accessToken')); ?>
  <?=$this->Form->hidden('accessId',array("hidden","value"=>'',"id"=>'accessId')); ?>
  
 	<input type="submit" id="login-btn" class="btn btn-block login-btn" value="Login" />
    <?php echo $this->Form->end();?>
 	 
  	
</div>