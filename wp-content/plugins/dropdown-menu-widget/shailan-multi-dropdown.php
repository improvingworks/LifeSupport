<?php 

class shailan_MultiDropDown extends WP_Widget {
    /** constructor */
    function shailan_MultiDropDown() {
		$widget_ops = array('classname' => 'shailan-dropdown-menu shailan-multi-dropdown', 'description' => __( 'Dropdown page & category menu', 'shailan-dropdown-menu' ) );
		$this->WP_Widget('multi-dropdown-menu', __('Dropdown Multi', 'shailan-multi-dropdown'), $widget_ops);
		$this->alt_option_name = 'widget_multi_dropdown';	
		
		$this->defaults = array(
			'title' => '',
			'pages' => false,
			'categories' => false,
			'links' => false,
			'archives' => false,
			'exclude' => '',
			'home' => false,
			'login' => false,
			'admin' => false,
			'vertical' => false,
			'align' => 'left'
		);
		
    }
	
    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
	    extract( $args );
		$widget_options = wp_parse_args( $instance, $this->defaults );
		extract( $widget_options, EXTR_SKIP );
		
		$orientation = ($vertical ? 'dropdown-vertical' : 'dropdown-horizontal');
		
		$custom_walkers = false; //(bool) get_option('shailan_dm_customwalkers');
		//$custom_walkers = !$custom_walkers;
		
        ?>
           <?php echo $before_widget; ?>

			<div id="shailan-dropdown-wrapper-<?php echo $this->number; ?>">
			
			<?php do_action('dropdown_before'); 
			echo '<div align="' . $align . '" class="'.$orientation.'-container dm-align-'.$align.'">';	
			?>
				  <table cellpadding="0" cellspacing="0"> 
					<tr><td> 
					<ul class="dropdown <?php echo $orientation; ?>">
					
					<?php do_action('dropdown_list_before'); ?>
					
					<?php if($home){ ?>						
						<li class="page_item cat-item blogtab <?php if ( is_front_page() && !is_paged() ){ ?>current_page_item current-cat<?php } ?>"><a href="<?php echo get_option('home'); ?>/"><span><?php _e('Home', 'shailan-dropdown-menu'); ?></span></a></li>	
					<?php } ?>
					
					<?php if($pages){ ?>
					
						<?php if($custom_walkers){						
							$page_walker = new shailan_PageWalker();
							wp_list_pages(array(
								'walker'=>$page_walker,
								'sort_column'=>'menu_order',
								'depth'=>'4',
								'title_li'=>'',
								'exclude'=>$exclude
								)); 
						} else {
							wp_list_pages(array(
								'sort_column'=>'menu_order',
								'depth'=>'4',
								'title_li'=>'',
								'exclude'=>$exclude
								)); 						
						} ?>
							
					<?php }; if($categories){ ?>
					
						<?php 
						if($custom_walkers){	
							$cat_walker = new shailan_CategoryWalker();
							wp_list_categories(array(
								'walker'=>$cat_walker,
								'order_by'=>'name',
								'depth'=>'4',
								'title_li'=>'',
								'exclude'=>$exclude
								)); 
						} else {
							wp_list_categories(array(
								'order_by'=>'name',
								'depth'=>'4',
								'title_li'=>'',
								'exclude'=>$exclude
								)); 								
						} ?>			
							
					<?php }; ?>
					
					<?php if($links){ ?>
					<li> <a href="#"><span>Links</span></a>
					<ul>
						<?php wp_list_bookmarks('title_li=&category_before=&category_after=&categorize=0'); ?>
					</ul>
					</li>
					<?php } ?>
					
					<?php if($archives){ ?>
					<li> <a href="#"><span>Archives</span></a>
					<ul>
						<?php 
							$args = array();
							wp_get_archives( $args ); ?>
					</ul>
					</li>
					<?php } ?>
					
						<?php do_action('dropdown_list_after'); ?>
					
						<?php if($admin){ wp_register('<li class="admintab">','</li>'); } if($login){ ?><li class="page_item"><?php wp_loginout(); ?><?php } ?>
						
					</ul></td>
				  </tr></table> 
				</div>
				
				<?php do_action('dropdown_after'); ?>
				
			</div> 				
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {			
	
		extract( $instance );
		$widget_options = wp_parse_args( $instance, $this->defaults );
		extract( $widget_options, EXTR_SKIP );
		
        ?>		
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (won\'t be shown):', 'shailan-dropdown-menu'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
			
		<p> Includes: <br/>
		
		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('home'); ?>" name="<?php echo $this->get_field_name('home'); ?>"<?php checked( (bool) $home ); ?> />
		<label for="<?php echo $this->get_field_id('home'); ?>"><?php _e( 'Homepage link' , 'shailan-dropdown-menu' ); ?></label><br />
		
		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('pages'); ?>" name="<?php echo $this->get_field_name('pages'); ?>"<?php checked( (bool) $pages ); ?> />
		<label for="<?php echo $this->get_field_id('pages'); ?>"><?php _e( 'Pages' , 'shailan-dropdown-menu' ); ?></label><br />
		
		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>"<?php checked( (bool) $categories ); ?> />
		<label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e( 'Categories' , 'shailan-dropdown-menu' ); ?></label><br />
		
		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('links'); ?>" name="<?php echo $this->get_field_name('links'); ?>"<?php checked( (bool) $links ); ?> />
		<label for="<?php echo $this->get_field_id('links'); ?>"><?php _e( 'Links' , 'shailan-dropdown-menu' ); ?></label><br />		
		
		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('archives'); ?>" name="<?php echo $this->get_field_name('archives'); ?>"<?php checked( (bool) $archives ); ?> />
		<label for="<?php echo $this->get_field_id('archives'); ?>"><?php _e( 'Archives' , 'shailan-dropdown-menu' ); ?></label><br />		
		

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('login'); ?>" name="<?php echo $this->get_field_name('login'); ?>"<?php checked( (bool) $login ); ?> />
		<label for="<?php echo $this->get_field_id('login'); ?>"><?php _e( 'Login/logout' , 'shailan-dropdown-menu' ); ?></label><br />
		
		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('admin'); ?>" name="<?php echo $this->get_field_name('admin'); ?>"<?php checked( (bool) $admin ); ?> />
		<label for="<?php echo $this->get_field_id('admin'); ?>"><?php _e( 'Register/Site Admin' , 'shailan-dropdown-menu' ); ?></label>
		</p>
		
		<p>
		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('vertical'); ?>" name="<?php echo $this->get_field_name('vertical'); ?>"<?php checked( (bool) $vertical ); ?> />
		<label for="<?php echo $this->get_field_id('vertical'); ?>"><?php _e( 'Vertical menu' , 'shailan-dropdown-menu' ); ?></label>
		</p>
		
		<p><?php _e('Align:', 'shailan-dropdown-menu'); ?> <label for="left"><input type="radio" id="left" name="<?php echo $this->get_field_name('align'); ?>" value="left" <?php if($align=='left'){ echo 'checked="checked"'; } ?> /> <?php _e('Left', 'shailan-dropdown-menu'); ?></label> <label for="center"><input type="radio" id="center" name="<?php echo $this->get_field_name('align'); ?>" value="center" <?php if($align=='center'){ echo 'checked="checked"'; } ?>/> <?php _e('Center', 'shailan-dropdown-menu'); ?></label> <label for="right"><input type="radio" id="right" name="<?php echo $this->get_field_name('align'); ?>" value="right" <?php if($align=='right'){ echo 'checked="checked"'; } ?>/> <?php _e('Right', 'shailan-dropdown-menu'); ?></label></p>
			
<div class="widget-control-actions alignright">
<p><small><a href="options-general.php?page=dropdown-menu"><?php esc_attr_e('Menu Style', 'shailan-dropdown-menu'); ?></a> | <a href="http://shailan.com/wordpress/plugins/dropdown-menu"><?php esc_attr_e('Visit plugin site', 'shailan-dropdown-menu'); ?></a></small></p>
</div>
			
        <?php 
	}

} // class shailan_MultiDropDown

// register widget
add_action('widgets_init', create_function('', 'return register_widget("shailan_MultiDropDown");'));