<?php
    $this->Paginator->options(array(
        'update' => '#content',
        'evalScripts' => true,
		'url' => array_merge(array('settings' => false,'order'=>false,'sort'=>false,'direction'=>false), $this->passedArgs)
    ));
    ?>

<div class="span12">

  <div class="widget">
	<div class="widget-title">
        <h4>
        <i class="icon-reorder">
        </i>Managed Users</h4>
        <span class="tools">
        <a href="javascript:;" class="icon-chevron-down">
        </a>
        <a href="javascript:;" class="icon-remove">
        </a>
        </span>
	</div>
    <div>&nbsp;</div>
    <div class="text-right pull-right dataTables_filter search_bar">
    	
          <form name="search" id="search" method="get"
           action="<?=$this->html->url(array("admin"=>true,"controller"=>"index","action"=>"managejobs","prefix"=>true))?>">
        	<label>Search: <input type="text" aria-controls="sample_1" name="search" class="input-medium"></label>
           </form> 
       
    </div>
    <? echo $this->Form->Create("User",array("url"=>array("admin"=>true,"controller"=>"index","action"=>"removejob","prefix"=>true),
"onsubmit"=>" return check_before_delete()"))?>
	<div class="span12">
        <p>
    	  <button id="delete_records" type="submit" class="btn btn-danger"><i class="icon-remove icon-white"></i> Delete</button>
    	</p>
        
    </div>
	<div class="widget-body">
		<div id="sample_1_wrapper" class="dataTables_wrapper form-inline" role="grid">
				    
			<table class="table table-striped table-bordered dataTable" id="sample_1" aria-describedby="sample_1_info">
            <thead>
            <tr role="row">
            <th style="width: 24px;" class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="">
            <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" />
            </th>
            <th class="sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" style="width: 158px;">
            	Title
            </th>
            <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" style="width: 158px;">
            	Salary
            </th>
            
            <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" 
            colspan="1" aria-label="Email: activate to sort column ascending" style="width: 253px;">
            	Address
            </th>
             <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" 
            colspan="1" aria-label="Email: activate to sort column ascending" style="width: 253px;">
            	Added By
            </th>
             <th class="sorting" role="columnheader" tabindex="0" aria-controls="sample_1" 
            rowspan="1" colspan="1" aria-label="Points: activate to sort column ascending" style="width: 200px;">
           		Applicants
            </th>
            <th class="sorting" role="columnheader" tabindex="0" aria-controls="sample_1" 
            rowspan="1" colspan="1" aria-label="Points: activate to sort column ascending" style="width: 200px;">
           		 Status
            </th>
            <th class="sorting" role="columnheader" tabindex="0" aria-controls="sample_1" 
            rowspan="1" colspan="1" aria-label="Points: activate to sort column ascending" style="width:  100px;">
           		 View
            </th>
             <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" 
            colspan="1" aria-label="Email: activate to sort column ascending" style="width: 100px;">
            	Posted On
            </th>
            </tr>
            </thead>
            <tbody role="alert" aria-live="polite" aria-relevant="all">
                <? $data = $this->get('data');
                 if($data){
				 $i = 0 ;
                 foreach ($data as $item): $class="odd"; if($i%2==0) $class = "even";
			
				$applicant = $item[0]['total_applicants'];
                 $countries = $item['rb_countries'];
				 $state = $item['rb_states'];
				 $user = $item['rb_users'];
				 $item = $item['Job'];
				
				 
                 ?>
                        <tr class="gradeX <?=$class?>">
                            <td class=" sorting_1">
                            <input type="checkbox" class="sub_check_element checkboxes" 
                            value="<?=$item['job_id']?>" name="job_id[<?=$item['job_id']?>]"  />
						
                            </span></div>
							</td>
                            <td class=" "><?=$item['job_title']?></td>
                            <td class="hidden-phone"><?='$'.$item['job_min_payscale'].' - $'.$item['job_max_payscale']?></td>
                            <td class="hidden-phone "><?=$item['job_address']?></td>
                            <td class="hidden-phone ">
							
                            <i class="icon-eye-open"> &nbsp;</i> 
                                <span>
                                 <?php  echo $this->Html->link($user['u_firstname'].' '.$user['u_lastname'],
								  array("admin"=>true,'controller'=>'index',
                                'action'=>'viewuser','u_id'=>$user['u_id']));?>
                                </span>
                            </td>
                            <td class="hidden-phone "><?=$applicant?></td>
                            <td class="">
                            	<? 
								$status = array();
								if($item['job_status']==0){
									 $status['class'] = 'btn btn-success change_status';
									 $status['change_to'] = 1;
									 $status['text'] = 'Active';
								}
								 else{
									 $status['class'] = 'btn btn-gray change_status';
									 $status['change_to'] = 0;
									 $status['text'] = 'Inactive';
								 }
								?>
                                <button type="button" class="<?=$status['class']?>" id="status_id-<?=$item['job_id']?>"
                                 data-id="<?=$item['job_id']?>" 
                                data-status="<?=$status['change_to']?>" data-controller="index" data-action="job_status"   >
									<?=$status['text']?>
                                </button>
                             
                            </td>
                            <td class="">
                      
                            <i class="icon-eye-open"> &nbsp;</i> 
                                <span>
                                 <?php  echo $this->Html->link("View", array("admin"=>true,'controller'=>'index',
                                'action'=>'viewjob','job_id'=>$item['job_id']));?>
                                </span>
                            </td>
                            <td>
								<? echo $this->Global->display_date_time($item['job_create_datetime']);	?>
                            </td>
                        </tr>
                 <? $i++;endforeach;
				 } else {
				 ?>
                 	  <tr><td colspan="8"> No Record Exist </td> </tr>
                 <? } ?>
            </tbody>

</table>
		<? if($data){ ?>
           <div class="row-fluid">
                <div class="span6">
                <div class="dataTables_info" id="sample_1_info">
                 <?php
            echo $this->Paginator->counter(
            'Page {:page} of {:pages}, showing {:current} records out of
            {:count} total, starting on record {:start}, ending on {:end}'
            );
            ?>
               </div>
                </div>
                <div class="span6">
                <div class="dataTables_paginate paging_bootstrap pagination">
                <ul>
                <li class="prev disabled">
              		 
               		<?
			    	echo $this->Paginator->prev(__('← Prev'), array('tag' => 'li'), null, 
					array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
			   		?>
               
                	</li>
           		 <?php
            		echo $this->Paginator->numbers(array('separator' => '', 'currentTag' => 'a',
				 	'currentClass' => 'active', 'tag' => 'li', 'first' => 1));
            		?>
                	<li class="next">
                	<?  echo $this->Paginator->next(__(' Next →'), array('tag' => 'li', 'currentClass' => 'disabled'),
					 null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));?>  
               	  </li>
                </ul>
                </div>
                </div>
                </div>
          
        <? } ?>        

               
		</div>
     <?php echo $this->Form->end();?> 
	</div>
  </div>  

</div>
 <?php echo $this->Js->writeBuffer(); ?>
