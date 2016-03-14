<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

?>
<!DOCTYPE html>
<html>
<head>
<title> Films </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->meta('title',  "Films");
		echo $this->Html->meta('description',  "Films");
		echo $this->Html->css(
								array(								
									'bootstrap3/css/bootstrap',	
							 		'main.css',
								)
							 );

    echo $this->Html->script(array( 
            'jquery-1.11.0.min.js',
            '../css/bootstrap3/js/bootstrap.js',
            'bootstrap-carousel.js'           
            ));           	


		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/t/dt/dt-1.10.11/datatables.min.css"/>
<script src="http://maps.googleapis.com/maps/api/js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.1/js/dataTables.fixedHeader.min.js"></script>

<script> 
  var baseUrl = '<?php echo $this->webroot?>'; var WindowScrollEn = 1;
</script>

</head>
<body>
  <!--- Header --->
      <!-- Static navbar -->
      <nav class="navbar navbar-default top-header" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar" aria-expanded="true" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            
          </div>
          <!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
   <!-- End  Header --->

	<?php echo $this->fetch("content"); ?>

  <!--- Footer Section --->
  <div class="footer_main">
  	<div class="footer_top_bg">&nbsp;</div>
    <div class="footer_content">
       <div class="container">	
    		<div class="col-lg-12">&nbsp;</div>
        	<div class="row">
            	<div class="col-lg-12">
                	<p class="text-center"><i> 2015 All rights reserved</i></p>
                </div>	
        		
        	</div>
       </div>     
    </div>
  </div>
  <!--- End Footer Section ---> 

<?php	echo $this->Html->script(array(	
						'jquery-1.11.0.min.js',
						'../css/bootstrap3/js/bootstrap.js',
						'bootstrap-carousel.js'						
						));

?>
<script>
!function ($) {
$(function(){
$('#myCarousel').carousel()
})
}(window.jQuery)
</script>
</body>
</html>