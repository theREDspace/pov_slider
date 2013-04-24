<?php
function pov_slider_homepage_slider_page() {	
	if ( isset($_POST['pov_slider_featured_posts']) ) {
		$error = false;
		$nonce=$_REQUEST['pov_homepage_slider'];
		if ( !wp_verify_nonce($nonce, 'pov_homepage_slider') ) {
			$error = true;
		} else {
			update_option('pov_slider_featured_posts', $_POST['pov_slider_featured_posts']);
		}
	} else if ( isset($_POST['pov_homepage_slider']) && empty($_POST['pov_slider_featured_posts']) ) {
		update_option('pov_slider_featured_posts', array());
	}
?>
  	<div class="wrap">
		<?php screen_icon(); ?>
    <h2>POV Slider Setup</h2>
    
    <?php if ( $error ) : ?>
    	<div id="message" class="error">
    		<p>There was an error saving the featured posts, please try again later.</p>
    	</div>
	<?php endif; ?>

    <p>Drag 'n drop the posts you want to feature in the slider on the Home page.</p>
    
    <form id="slider" action="" method="post">
	  <?php wp_nonce_field('pov_homepage_slider', 'pov_homepage_slider'); ?>
      <?php if ( $wp_http_referer ) : ?>
        <input type="hidden" name="wp_http_referer" value="<?php echo esc_url($wp_http_referer); ?>" />
      <?php endif; ?>
	    <input type="hidden" name="action" value="pov_homepage_slider_update" />
    
    	<div id="search-panel">
		<div class="link-search-wrapper">
			<label>
				<span><?php _e( 'Search' ); ?></span>
				<input type="text" id="search-field" class="link-search-field" tabindex="60" autocomplete="off" />
				<a class="clear-btn" title="Clear Search" href="#"></a>
				<img class="waiting" src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" alt="" />
			</label>
		</div>
		
	    <div id="search-query-notice" class="query-notice"><em>No results matched your search.</em></div>
	    <ul id="sortable-search" class="connectedSortable"></ul>
			<div id="recent-query-notice" class="query-notice"><em>No search term specified. Showing recent items.</em></div>
			<ul id="recent-sortable" class="connectedSortable">
	      	
	      <?php 						
				$args = array ( 'posts_per_page' => 50,
								'post_type' => 'any',
								'post_status' => 'publish' );
				$posts_query = new WP_Query($args);
				?>
	      
	      <?php while ( $posts_query->have_posts() ) : $posts_query->the_post(); ?>
	      
	    		<?php $p = get_post_type_object(get_post_type()); ?>
	        <li class="alternate" id="<?php the_ID(); ?>"><span class="item-title"><?php echo get_the_title(); ?></span><span class="item-info"><?php echo $p->labels->singular_name; ?></span></li>
	        
	       <?php 
	          endwhile; 
	          wp_reset_postdata();
	          wp_reset_query();
	        ?>
	      </ul>
		</div>
    
      <div id="slider_posts">      
        
        <div id="featured" class="left clearfix">
          <div class="query-notice"><em>Homepage Featured Posts</em></div>
          
		<?php
			$featured_home = pov_slider_get_featured_posts();
		?>
     	  	
         	<ul id="featured-sortable" class="connectedSortable query-results">
            <?php 
            	if (!empty($featured_home)) : foreach($featured_home as $pid) :
              		$slide = get_post($pid); 
					$p = get_post_type_object($slide->post_type); 
			?>
          		
          		<li class="alternate" id="<?php echo $slide->ID; ?>"><span class="item-title"><?php echo $slide->post_title; ?></span><span class="item-info"><?php echo $p->labels->singular_name; ?></span></li>
          
             <?php 
				endforeach; endif;
                wp_reset_postdata();
                wp_reset_query();
              ?>
          	</ul>

          	<div class="right">
          		<?php submit_button(__('Save List', 'pov')); ?>
          	</div>
        </div>
      </div>
    
    </form>
</div>
<?php
}