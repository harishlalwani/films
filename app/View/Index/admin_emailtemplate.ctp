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
        </i>Managed Table</h4>
        <span class="tools">
        <a href="javascript:;" class="icon-chevron-down">
        </a>
        <a href="javascript:;" class="icon-remove">
        </a>
        </span>
	</div>
	<div class="widget-body">
		<div id="sample_1_wrapper" class="dataTables_wrapper form-inline" role="grid">
				    
			<table class="table table-striped table-bordered dataTable" id="sample_1" aria-describedby="sample_1_info">
            <thead>
            <tr role="row">
            <th style="width: 24px;" class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="">
            	Sno:
            </th>
            <th class="sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" style="width: 158px;"
            >Title</th>
            <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 253px;">Subject</th>
            <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Points: activate to sort column ascending" style="width:  400px;">Edit</th>
            </th>
            </tr>
            </thead>
<tbody role="alert" aria-live="polite" aria-relevant="all">
	<?php $data = $this->get('data');
	  $i = 0 ;
	 foreach ($data as $item): $class="odd"; if($i%2==0) $class = "even";
	 $item = $item['eve_emailtemplate'];
	 ?>
            <tr class="gradeX <?=$class?>">
            <td class=" sorting_1">
            	<?=$item['email_id']?>
            </td>
            <td class=" ">
<?=$item['email_title']?>
</td>
           
            <td class="">
<?=$item['email_subject']?>
</td>
            <td class="">

               <i class="icon-edit"> &nbsp;</i> 
               <span>
      <?php  echo $this->Html->link("Edit", array("admin"=>true,'controller'=>'index','action'=>'editemail','email_id'=>$item['email_id']));?>
               </span>
            </td>
            </tr>
	 <?php $i++;endforeach;?>
</tbody>

</table>

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
              		 
               		<?php
			    	echo $this->Paginator->prev(__('←'), array('tag' => 'li'), null, 
					array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
			   		?>
               
                	</li>
           		 <?php
            		echo $this->Paginator->numbers(array('separator' => '', 'currentTag' => 'a',
				 	'currentClass' => 'active', 'tag' => 'li', 'first' => 1));
            		?>
                	<li class="next">
                	<?php  echo $this->Paginator->next(__('→'), array('tag' => 'li', 'currentClass' => 'disabled'),
					 null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));?>  
               	  </li>
                </ul>
                </div>
                </div>
                </div>

               
		</div>
	</div>
  </div>  
</div>
 <?php echo $this->Js->writeBuffer(); ?>