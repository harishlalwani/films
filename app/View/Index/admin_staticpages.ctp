<?php
    $this->Paginator->options(array(
        'update' => '#content',
        'evalScripts' => true,
		'url' => array_merge(array('settings' => false,'order'=>false,'sort'=>false,'direction'=>false), $this->passedArgs)
    ));
    ?>
    
<div class="span12">
<?php echo $this->Form->Create("Page",array("url"=>array("admin"=>true,"controller"=>"index","action"=>"removepage","prefix"=>true),
"onsubmit"=>" return check_before_delete()"))?>
  <div class="widget">
	<div class="widget-title">
        <h4>
        <i class="icon-reorder">
        </i>Managed Pages</h4>
        <span class="tools">
        <a href="javascript:;" class="icon-chevron-down">
        </a>
        <a href="javascript:;" class="icon-remove">
        </a>
        </span>
	</div>
    <div>&nbsp;</div>
    <div class="span12">
    	<p>
    	<a href="<?=$this->html->url(array("admin"=>true,"controller"=>"index","action"=>"addpage","prefix"=>true));?>">
        <button  type="button" class="btn btn-success"><i class="icon-plus icon-white"></i> Add Page</button>
        </a>
        <button type="submit" class="btn btn-danger"><i class="icon-remove icon-white"></i> Delete</button>
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
            <th class="sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" style="width: 158px;"
            >Page name</th>
            <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 253px;">Page Title</th>
     <?php /*?>    <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Points: activate to sort column ascending" style="width:  400px;">Status</th><?php */?>
            <th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label="Points: activate to sort column ascending" style="width:  400px;">Edit</th>
            </th>
            </tr>
            </thead>
            <tbody role="alert" aria-live="polite" aria-relevant="all">
                <?php $data = $this->get('data');
                  $i = 0 ;
                 foreach ($data as $item): $class="odd"; if($i%2==0) $class = "even";
                 $item = $item['rb_page'];
                 ?>
                        <tr class="gradeX <?=$class?>">
                            <td class=" sorting_1">
                            <input type="checkbox" class="sub_check_element checkboxes" 
                            value="<?=$item['page_id']?>" name="page_id[<?=$item['page_id']?>]"  />
						
                            </span></div>
							</td>
                            <td class=" "><?=$item['page_key']?></td>
                            <td class=""><?=$item['page_title']?></td>
                           <?php /*?> <td class="">
                            	<?php if($item['page_status']==1){?>
                                <button type="button" class="label label-success">Approved</button>
                              <?php } else {?>
                              	  <button type="button" class="btn btn-inverse">Inverse</button>
                                <?php } ?>
                            </td><?php */?>
                            <td class="">
                      
                            <i class="icon-edit"> &nbsp;</i> 
                                <span>
                                 <?php  echo $this->Html->link("Edit", array("admin"=>true,'controller'=>'index',
                                'action'=>'editpage','page_id'=>$item['page_id']));?>
                                </span>
                            </td>
                        </tr>
                 <?php $i++;endforeach;?>
            </tbody>

</table>
			<?php if($data){ ?>
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
                	<?php  echo $this->Paginator->next(__(' Next →'), array('tag' => 'li', 'currentClass' => 'disabled'),
					 null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));?>  
               	  </li>
                </ul>
                </div>
                </div>
                </div>
			<?php } ?>
               
		</div>
	</div>
  </div>  
   <?php echo $this->Form->end();?>
</div>
 <?php echo $this->Js->writeBuffer(); ?>
