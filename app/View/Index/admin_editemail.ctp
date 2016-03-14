<div class="row-fluid">
        <div class="span12 sortable">
          <div class="widget">
            <div class="widget-title">
              <h4><i class="icon-reorder"></i>Form Layouts</h4>
              <span class="tools"><a href="javascript:;" class="icon-chevron-down"></a><a href="javascript:;" class="icon-remove"></a></span></div>
            <div class="widget-body">
                    <div class="row-fluid">

                    	  <?php echo $this->Form->create('Email',array('url'=>array('admin'=>true,'controller'=>'index','action'=>'editemail',
						  'email_id'=>$this->get('email_id'))
	  ,"class"=>"form-horizontal no-padding no-margin profile_form")); ?>
      
                        <div class="control-group">
                        <label class="control-label">Subject</label>
                        <div class="controls form-group">
                       	   <?php echo $this->Form->input('email_subject',
							    array('type'=>'text','placeholder'=>'Subject','label'=>false,
                               'class'=>'span5 form-control required','id'=>'email_subject',"div"=>false)); ?>
                        </div>
                        </div>						                        
                        <div class="control-group">
                            <label class="control-label">Mail Content</label>
                            <div class="controls">
                             <?php echo $this->Form->textarea('email_body',
							    array('placeholder'=>'Subject','label'=>false,
                               'class'=>'span12 ckeditor form-control required','id'=>'editor1',"div"=>false,"rows"=>6)); ?>
                            
                            </div>
                        </div>
                        
                        <div class="form-actions">
                			<button type="submit" class="btn btn-success">Submit</button>
            		    
              			</div>
                    <?php echo $this->Form->end();?>
                  </div> 
            </div>
          </div>
        </div>
      </div>