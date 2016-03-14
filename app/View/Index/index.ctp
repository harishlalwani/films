   <!--- Slider Div ---->
   <div id="myCarousel" class="carousel slide">
     
      <ol class="carousel-indicators" style="display:none">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
         <li data-target="#myCarousel" data-slide-to="1"></li>
          <li data-target="#myCarousel" data-slide-to="2"></li>
        <li data-target="#myCarousel" data-slide-to="3"></li>
        <li data-target="#myCarousel" data-slide-to="4"></li>
      </ol>
      <div class="carousel-inner">
        <div class="item active">
        <img src="<?php echo $this->webroot ?>/img/slider_bg.jpg" alt="First Slide"  />
          <div >
            <div class="carousel-caption">
             <div class="col-sm-9 col-md-6 col-lg-9 ">	
          	  <div class="Heading-slider">Your Dry Cleaning & Laundry Picked-up in 1 Hour </div>
              <div class="clear slider_and green_color_text"> & </div>
              <div class="carousel-caption-text">
               <img src="<?php echo $this->webroot ?>/img/divider.png" alt="block"  />
               <span class="green_color_text">Delivered the Next Day</span>
                <img src="<?php echo $this->webroot ?>/img/divider.png" alt="block"  />
              </div>
              </div>
              <div class="col-sm-9 col-md-3 col-lg-3 ">
              	<div class="offer_div">
                       <div><b> 50% </b></div>
                       <div class="offer_div_sub">
                        off your first
                        delivery  <br />
                        Click Here
               		   </div>	
                </div>
              </div>
               
            </div>
             <p class="button_pick">
                <a class="btn btn-large" href="">Schedule pickup</a>
            </p>
          </div>
        </div>
               
        
      </div>
      
       <div  class="slider-bottom-border"> </div>
      <a class="left carousel-control" href="#myCarousel" data-slide="prev"><span class="icon-prev"></span></a>
      <a class="right carousel-control" href="#myCarousel" data-slide="next"><span class="icon-next"></span></a>
    </div>

   <!-- End Slider Div --->
  <div class="main_page_container_section"> 
   <!--- How it Works Div ---->	
   <div class="container  text-center">
		
        <div class="row text-center"><h2 class="blue_title_head">How it Works</h2>
        	<p> <img src="<?php echo $this->webroot ?>/img/divider.png" alt="block" class="divider_green_line"  /></p>
        </div>
        <div class="col-lg-12">&nbsp;</div>
        <div class="row">
          <div class="col-lg-12 how_it_wrks_box">	
        	<div class="col-sm-4 col-md-4 col-lg-4 "> 
      			   <img src="<?php echo $this->webroot ?>/img/works_1.jpg" alt="Request a pickup"   />  
                   <div class="clear">
                   	 <div class="blue_circle col-lg-2">1</div>
                     <div class=" col-lg-9 text_div"> Request a pickup</div>
                   </div>	 
            </div>
			<div class="col-sm-4 col-md-4 col-lg-4"> 
            	 <img src="<?php echo $this->webroot ?>/img/works_2.jpg" alt="Request a pickup"   />   
                   <div class="clear">
                   	 <div class="blue_circle col-lg-2">2</div>
                     <div class=" col-lg-9 text_div">A delivery expert arrives at your door in 1hr</div>
                   </div>	
            </div>

			<div class="col-sm-4 col-md-4 col-lg-4"> 
            	 <img src="<?php echo $this->webroot ?>/img/works_3.jpg" alt="Request a pickup"   />   
                   <div class="clear">
                   	 <div class="blue_circle col-lg-2">3</div>
                     <div class=" col-lg-9 text_div">  Your clothes are delivered in pristine condition the next day</div>
                   </div>	
            </div>
           </div> 
	   </div>
        
		<!---End How it Works Div ---->	
    </div>
   <!--- End How it Works Div ---->	  
	<!--- Find your nearest store ---->	
   <div class="container">&nbsp;</div> 
   <div class="gray_div"> 
   <div class="container  text-center">
		
        <div class="row text-center"><h2 class="blue_title_head">Find your Nearest Store</h2>
        	<p> <img src="<?php echo $this->webroot ?>/img/divider.png" alt="block" class="divider_green_line"  /></p>
        </div>
        <div class="col-lg-12">&nbsp;</div>
        <div class="row find_ur_store">
          <div class="col-lg-12 find_ur_store">	
        	<div class="col-sm-4 col-md-4 col-lg-4 "> 
      			   <img src="<?php echo $this->webroot ?>/img/find_store_1.png" alt="Request a pickup"   />  
                   	 
            </div>
			<div class="col-sm-4 col-md-4 col-lg-4"> 
            	 <img src="<?php echo $this->webroot ?>/img/find_store_2.png" alt="Request a pickup"   />   
                   	
            </div>

			<div class="col-sm-4 col-md-4 col-lg-4"> 
            	 <img src="<?php echo $this->webroot ?>/img/find_store_3.png" alt="Request a pickup"   />   
                   	
            </div>
           </div>
          
          <div class="col-lg-12 pink_clr_bg">
           <div class="row">
          		<div class="col-sm-9 col-md-9 col-lg-9 text-right search_bar_text"> 
      			   <div class="col-sm-7 col-md-7 col-lg-7">  Enter <b>Zip Code</b> and Search</div>
                  <div class="col-sm-5 col-md-5 col-lg-5 search_form_main"> 
                    <form method="post" name="search" action="">
                    <input type="text" name="search" id="search" placeholder="XXXXXXX"  /> 
                    <button type="submit" id="search_button" class="search_button"></button>
                    
                    </div>	
                </div>
				<div class="col-sm-3 col-md-3 col-lg-3"> 
        	    	
                    <div class="offer_div offer_div_blue">
                       <div><b> 25% </b></div>
                       <div class="offer_div_sub">
                            off your first
                            <br />
                            store visit
               		   </div>	
                </div>
            	</div>
             </div>   

          </div>
           
          <div class="col-lg-12 find_ur_store">	
        	<div class="col-sm-4 col-md-4 col-lg-4 "> 
      			   <img src="<?php echo $this->webroot ?>/img/find_store_4.png" alt="Request a pickup"   />  
                   	 
            </div>
			<div class="col-sm-4 col-md-4 col-lg-4"> 
            	 <img src="<?php echo $this->webroot ?>/img/find_store_5.png" alt="Request a pickup"   />   
                   	
            </div>

			<div class="col-sm-4 col-md-4 col-lg-4"> 
            	 <img src="<?php echo $this->webroot ?>/img/find_store_6.png" alt="Request a pickup"   />   
                   	
            </div>
           </div>  
           
           
	   </div>
        
        
		<!---End How it Works Div ---->	
    </div>
    </div>
   <!--- End  Find your nearest store ---->	  
  	<!--- What our client says ---->	
   <div class="container">&nbsp;</div> 
   <div class="container  text-center">
		
        <div class="row text-center"><h2 class="blue_title_head">What our Clients say</h2>
        	<p> <img src="<?php echo $this->webroot ?>/img/divider.png" alt="block" class="divider_green_line"  /></p>
        </div>
        <div class="col-lg-12">&nbsp;</div>
    </div> 
   
   <div class="what_client_says_main"> 
    
   <div class="what_client_says"> 
   <div class="container  text-center">
	
        <div class="col-lg-12">&nbsp;</div>
        <div class="row ">
          <div class="col-lg-12 what_client_testimonial">	
        	<div class="col-sm-4 col-md-4 col-lg-4 "> 
      			   <div class="position_relative">
                   <img src="<?php echo $this->webroot ?>/img/testimonial_user_1.png" alt="User Icon"   />  
                   <img src="<?php echo $this->webroot ?>/img/comment_quote.png" alt=""  class="comment_icon"  />  
                   </div>	 
                   <div class="clear col-lg-12">
                   	<p>
                        I never realized I could get this excited about dry-cleaning.
                        <br /> <br />
                        Man, I wish more companies had this level of customer service, sophistication, and eco-awareness.  This is how a 
                        company should be run!!

                    </p>
                    <p><b class="what_client_testimonial_uname">Sara. R</b></p>
                   </div>
            </div>
			<div class="col-sm-4 col-md-4 col-lg-4"> 
            	    <div class="position_relative">
                    <img src="<?php echo $this->webroot ?>/img/testimonial_user_2.png" alt="User Icon"   />    
                   	<img src="<?php echo $this->webroot ?>/img/comment_quote.png" alt=""  class="comment_icon"  />  
                    </div>
                    <div class="clear col-lg-12">
                   	<p>
                       Walking into Mulberrys is like a breath of fresh air.  It is well-designed, clean, and naturally lit.  The service team is friendly and helpful and I love, love, love the fact that everything is toxin-free.

                        <br /> <br />
                        I'm telling all my friends about this place ... and now you too

                    </p>
                    <p><b class="what_client_testimonial_uname">Rachael T.</b></p>
                   </div>
            </div>

			<div class="col-sm-4 col-md-4 col-lg-4"> 
            	    <div class="position_relative">
                    <img src="<?php echo $this->webroot ?>/img/testimonial_user_3.png" alt="User Icon"   />  
                   	 <img src="<?php echo $this->webroot ?>/img/comment_quote.png" alt=""  class="comment_icon"  />  
                    </div>
                    <div class="clear col-lg-12">
                   	<p>
                       I believe Mulberrys is the best in the business. 
                        <br /> <br />
                     The shirts come back perfect, with collar stays and wood hangers. The plastic bag can be removed easily by it's perforated lines. Customer service is excellent. 

                    </p>
                    <p><b class="what_client_testimonial_uname">Aaron H. </b></p>
                   </div>
            </div>
           </div>
          <div class="col-lg-12">&nbsp;</div>
          <div class="col-lg-12 what_client_strip text-center">
         		<div class="what_client_strip_head">100% Satisfaction Guarantee</div>
                <p>If you're ever unhappy with the quality of our cleaning or pressing, we'll redo the item for FREE.</p>
          </div> 
	   </div>
       
    </div>
    </div>
   
   </div>
   <!--- End What our client says ---->	   
   	<!--- Toxin Cleaning ---->	
   <div class="toxin_cleaning_main"> 
    
   <div class="toxin_cleaning"> 
   <div class="container  text-center">
	
     <div class="col-lg-12">&nbsp;</div>

     <div class="row text-center"><h2 class="white_head blue_title_head">Toxin-Free Cleaning</h2>
        	<p> <img src="<?php echo $this->webroot ?>/img/white_divider.png" alt="block" class="divider_green_line"  /></p>
        </div>
        <div class="col-lg-12">&nbsp;</div>
        <div class="row ">
          <div class="col-lg-6 pull-right ">
          	
        	<div class="col-sm-6 col-md-6 col-lg-6 toxin_cleaning_blocks "> 
      			   <div class="col-lg-5 position_relative">
                   <img src="<?php echo $this->webroot ?>/img/toxin_icon1.png" alt="User Icon"   />  
                   </div>	 
                   <div class="col-lg-7">
                    <b>Recycled</b><br /> wooden hangers
                   </div>
            </div>
			<div class="col-sm-6 col-md-6 col-lg-6 toxin_cleaning_blocks"> 
      			   <div class="col-lg-5 position_relative">
                   <img src="<?php echo $this->webroot ?>/img/toxin_icon1.png" alt="User Icon"   />  
                   </div>	
                    <div class="col-lg-7">
                    <b>Toxin-Free </b><br />Dry Cleaning
                   </div> 
                  
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 toxin_cleaning_blocks"> 
      			   <div class="col-lg-5 position_relative">
                   <img src="<?php echo $this->webroot ?>/img/toxin_icon1.png" alt="User Icon"   />  
                   </div>	 
                 <div class="col-lg-7">
                    <b>Biodegradable </b><br />Garment Bags
                   </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 toxin_cleaning_blocks"> 
      			   <div class="col-lg-5 position_relative">
                   <img src="<?php echo $this->webroot ?>/img/toxin_icon1.png" alt="User Icon"   />  
                   </div>	 
                   <div class="col-lg-7">
                    <b class="white_head">Environmentally Friendly Laundry </b><br /> Detergent
                   </div>
            </div>
           </div>
          <div class="col-lg-12">&nbsp;</div>
           
	   </div>
       
    </div>
    </div>
   
   </div>
   <!--- End Toxin Cleaning---->	   
   
   <!--- World of services ---->	
   
   <div class="gray_div world_of_services_main"> 
   <div class="container  text-center">
		
        <div class="row text-center"><h2 class="blue_title_head">A World of Services</h2>
        	<p> <img src="<?php echo $this->webroot ?>/img/divider.png" alt="block" class="divider_green_line"  /></p>
        </div>
        <div class="col-lg-12">&nbsp;</div>
        <div class="row">
          <div class="col-lg-12 world_of_services_block">	
        	<div class="col-sm-4 col-md-3 col-lg-3 "> 
      			   <img src="<?php echo $this->webroot ?>/img/world_services_1.jpg" alt="Request a pickup"   />
                   <p>Wedding Gowns Cleaning & Preservation</p>  
                   	 
            </div>
			<div class="col-sm-4 col-md-3 col-lg-3 ">  
            	   <img src="<?php echo $this->webroot ?>/img/world_services_2.jpg" alt="Request a pickup"   />  
                   	<p>Shoe Shine & Repair</p>
            </div>

			<div class="col-sm-4 col-md-3 col-lg-3 ">  
            	   <img src="<?php echo $this->webroot ?>/img/world_services_3.jpg" alt="Request a pickup"   />  
                   <p>Custom Tailoring</p>	
            </div>
            <div class="col-sm-4 col-md-3 col-lg-3 ">  
            	   <img src="<?php echo $this->webroot ?>/img/world_services_4.jpg" alt="Request a pickup"   />  
                   <p>Leather Cleaning </p>	
            </div>
            <div class="col-sm-4 col-md-3 col-lg-3 ">  
            	   <img src="<?php echo $this->webroot ?>/img/world_services_5.jpg" alt="Request a pickup"   />  
                   <p>House Hold Cleaning</p>	
            </div>
           </div>
      </div>
     </div>
    </div>
   <!--- End  World of services  ---->	 
  
   <!--- Competitive Pricing ---->	
     <div class="competitive_pricing world_of_services_main"> 
   <div class="container  text-center">
   		 <div class="col-lg-12">&nbsp;</div>
		<div class="pricing_head position_relative">
        	<div class=" white_head offer_div">
                   <div><b> 50% </b></div>
                   <div class="offer_div_sub">
                    off your first
                    delivery  <br />
                    Click Here
                   </div>	
            </div>
        	<div class="row text-center position-relative">
            
        <h2 class="blue_title_head">Competitive Pricing</h2>
        	<p> <img src="<?php echo $this->webroot ?>/img/divider.png" alt="block" class="divider_green_line"  /></p>
            
        </div>
        </div>
        <div class="col-lg-12">&nbsp;</div>
        <div class="row">
          <div class="col-lg-12 world_of_services_block">	
        	<div class="col-sm-4 col-md-3 col-lg-3 "> 
      			   <img src="<?php echo $this->webroot ?>/img/comp_pricing_1.jpg" alt="Request a pickup"   />
                   <p class="clear"><a href="" class="pricing_button"> $ 2.99</a></p>
                   	 
            </div>
			<div class="col-sm-4 col-md-3 col-lg-3 ">  
            	   <img src="<?php echo $this->webroot ?>/img/comp_pricing_2.jpg" alt="Request a pickup"   />  
                    <p class="clear">	<a href="" class="pricing_button"> $ 7.99</a></p>
            </div>

			<div class="col-sm-4 col-md-3 col-lg-3 ">  
            	   <img src="<?php echo $this->webroot ?>/img/comp_pricing_3.jpg" alt="Request a pickup"   />  
                   <p class="clear"> <a href="" class="pricing_button"> $ 7.99</a>	</p>
            </div>
            <div class="col-sm-4 col-md-3 col-lg-3 ">  
            	   <img src="<?php echo $this->webroot ?>/img/comp_pricing_4.jpg" alt="Request a pickup"   />  
                   <p class="clear"> <a href="" class="pricing_button"> $ 7.99</a>	</p>
            </div>
            <div class="col-sm-4 col-md-3 col-lg-3 ">  
            	   <img src="<?php echo $this->webroot ?>/img/comp_pricing_5.jpg" alt="Request a pickup"   />  
                 <p class="clear">   <a href="" class="pricing_button"> $ 9.99</a></p>
            </div>
           </div>
           <div class="col-lg-12 clear">&nbsp;</div>
          <div class="col-lg-12 full_price_sec">
          	<div class="gray_divider_line"> &nbsp;</div>
            <span class="full_price_button"> Full Price List </span>
            <div class="gray_divider_line"> &nbsp;</div> 
                
          </div>
          <div class="clear col-lg-12 text-center sheduling_buttons_main">
          	  <div class="col-sm-6 col-md-6 col-lg-6 text-right">  
                <p class=" button_quote">
                	<a class="btn btn-large" href="">get price quote</a>
            	</p>
              </div>
              <div class="col-sm-6 col-md-6 col-lg-6 text-left">  
                <p class=" button_pick">
                	<a class="btn btn-large" href="">Schedule pickup</a>
            	</p>
              </div>
              
          </div>    
      </div>
     </div>
    </div>
   <!--- End  Competitive Pricing  ---->	
   <!--- Video Section ---->	
  <div class="video_panel_footer_main"> 
  <div class="video_panel_footer_main_sub"> 
   <div class="container">	
    <div class="col-lg-12 clear video_panel_gap1">&nbsp;</div>
    <div class="row video_panel_footer">
    	<div>
        	<h2>Want to Learn More?</h2>
			Watch the video
        </div>
        <div class="col-lg-12 clear video_panel_gap">&nbsp;</div>
        <div class="col-lg-12"> <img src="<?php echo $this->webroot ?>/img/play.png" alt="Play"   />  </div>
    </div>
   </div>
  </div> 
  </div> 
   <!--- End Video Section ---->	
    
 </div>   