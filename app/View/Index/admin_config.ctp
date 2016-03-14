<div class="row-fluid">
        <div class="span12 sortable">
          <div class="widget">
            <div class="widget-title">
              <h4><i class="icon-reorder"></i>Manage Settings</h4>
              <span class="tools"><a href="javascript:;" class="icon-chevron-down"></a><a href="javascript:;" class="icon-remove"></a></span></div>
            <div class="widget-body">
                    <div class="row-fluid">

                  	  <?php echo $this->Form->create('Config',array('url'=>array('admin'=>true,'controller'=>'index','action'=>'config')
	  ,"class"=>"form-horizontal no-padding no-margin profile_form")); ?>
                         
                     <?php foreach($site_data as $key=>$value){
					  $value = $value['Config'];
					 ?>
                        <div class="control-group">
                        <label class="control-label"><?php=$value['config_title']?></label>
                        <div class="controls form-group">
                       	   <?php echo $this->Form->input($value['config_id'],
							    array('type'=>'text','placeholder'=>$value['config_title'],'label'=>false,'value'=>$value['config_value'],
                               'class'=>'span10 form-control required','id'=>$value['config_key'],"div"=>false)); ?>
                        </div>
                        </div>						                        
                      <?php } ?>
                        
                        <div class="form-actions">
                			<button type="submit" class="btn btn-success">Submit</button>
            		    
              			</div>
                    <?php echo $this->Form->end();?>
                  </div> 
            </div>
          </div>
        </div>
      </div>