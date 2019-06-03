<?php
/*  ----------------------------------------------------------------------------
    Newspaper V6.3+ Child theme - Please do not use this child theme with older versions of Newspaper Theme

    What can be overwritten via the child theme:
     - everything from /parts folder
     - all the loops (loop.php loop-single-1.php) etc
	 - please read the child theme documentation: http://forum.tagdiv.com/the-child-theme-support-tutorial/


     - the rest of the theme has to be modified via the theme api:
       http://forum.tagdiv.com/the-theme-api/

 */




/*  ----------------------------------------------------------------------------
    add the parent style + style.css from this folder
 */
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 1001);
function theme_enqueue_styles() {
    wp_enqueue_style('td-theme', get_template_directory_uri() . '/style.css', '', TD_THEME_VERSION, 'all' );
    wp_enqueue_style('td-theme-child', get_stylesheet_directory_uri() . '/style.css', array('td-theme'), TD_THEME_VERSION . 'c', 'all' );

}

//Enqueue scripts in the footer
function footer_scripts(){

	wp_register_script('main-script', get_stylesheet_directory_uri() . '/scripts/main.js', array(), false, true);
	
	wp_localize_script( 'main-script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))); 

	wp_enqueue_script('main-script');
	  

}

add_action('wp_footer', 'footer_scripts');

//Add new menu for mobiles
function register_my_menu() {
  register_nav_menu('mobile-menu',__( 'Mobile Menu' ));
}
add_action( 'init', 'register_my_menu' );


// This will suppress empty email errors when submitting the user form
add_action('user_profile_update_errors', 'my_user_profile_update_errors', 10, 3 );
function my_user_profile_update_errors($errors, $update, $user) {
    $errors->remove('empty_email');
}

// This will remove javascript required validation for email input
// It will also remove the '(required)' text in the label
// Works for new user, user profile and edit user forms
add_action('user_new_form', 'my_user_new_form', 10, 1);
add_action('show_user_profile', 'my_user_new_form', 10, 1);
add_action('edit_user_profile', 'my_user_new_form', 10, 1);
function my_user_new_form($form_type) {
    ?>
    <script type="text/javascript">
        jQuery('#email').closest('tr').removeClass('form-required').find('.description').remove();
        // Uncheck send new user email option by default
        <?php if (isset($form_type) && $form_type === 'add-new-user') : ?>
            jQuery('#send_user_notification').removeAttr('checked');
        <?php endif; ?>
    </script>
    <?php
}

add_action('wp_footer', 'footer_scripts');

//Support video post format
function add_formats() {
	add_theme_support( 'post-formats', array('video','audio') );
}
add_action( 'after_setup_theme', 'add_formats' );


//Disable Post Formats in posts
add_action( 'init', 'remove_postformat' );
function remove_postformat() {
	remove_post_type_support( 'post', 'post-formats' );
}

/*
 * Posts of post_type_1 will be asides by default, but all other post types
 * will be the default set on the Settings > Writing admin panel
 */
add_filter( 'option_default_post_format', 'custom_default_post_format' );
function custom_default_post_format( $format ) {
    global $post_type;

    if( $post_type == 'video' ) {
        $format = 'video';
    }
	if( $post_type == 'audio' ) {
        $format = 'audio';
    }

    return $format;
}

//Hide metaboxes by default according to custom post type
add_filter( 'hidden_meta_boxes', 'custom_hidden_meta_boxes', 10, 2 );
function custom_hidden_meta_boxes( $hidden, $screen ) {
    $post_type= $screen->id;

    switch ($post_type) {
		case 'video':
			//$hidden[] = 'formatdiv';
			$hidden = array('formatdiv','types-information-table');
			return $hidden;
		break;
		case 'audio':
			$hidden = array('formatdiv','types-information-table');
			return $hidden;
		break;
		case 'post':
			$hidden = array('types-information-table','powerpress-podcast', 'postcustom');
			return $hidden;
		break;
		case 'page':
			$hidden = array('powerpress-podcast');
			return $hidden;
		break;		
		default:
			return $hidden;
		break;		
    }
}

function namespace_add_custom_types( $query ) {
  if( (is_category() || is_tag()) && $query->is_archive() && !is_admin() && empty( $query->query_vars['suppress_filters'] ) ) {
    $query->set( 'post_type', array(
     'post', 'video', 'audio', 'blog'
        ));
    }
    return $query;
}
add_filter( 'pre_get_posts', 'namespace_add_custom_types' );

//Publicize: Add support for custom post types
add_action('init', 'my_custom_init');
function my_custom_init() {
    add_post_type_support( 'blog', 'publicize' );
	add_post_type_support( 'video', 'publicize' );
	add_post_type_support( 'audio', 'publicize' );
}


//Override functions in parent theme to replace with child theme functions
function child_remove_parent_function() {
    remove_action( 'init', 'td_register_post_metaboxes', 9999 );
}
add_action( 'after_setup_theme', 'child_remove_parent_function', 9999 );


add_action('after_setup_theme', 'td_register_post_metaboxes_child'); // we need to be on init because we use get_post_types - we need the high priority to catch retarded plugins that bind late to the hook to register it's CPT
function td_register_post_metaboxes_child() {
    $td_template_settings_path = get_template_directory() . '/includes/wp_booster/wp-admin/content-metaboxes/';
	
	if ( ! class_exists( 'WPAlchemy_MetaBox' ) ){
		include_once get_template_directory()  . '/includes/wp_booster/wp-admin/external/wpalchemy/MetaBox.php';
	}

    if (current_user_can('publish_posts')) {
        // featured video
        new WPAlchemy_MetaBox(array(
            'id' => 'td_post_video',
            'title' => 'Featured Video',
            'types' => array('video'),
            'priority' => 'low',
            'context' => 'side',
            'template' => $td_template_settings_path . 'td_set_video_meta.php',
        ));
    }

}

//Show category when Audio/Video
function custom_category($id, $post_type) {
	
	$html = "";
	$style = "";
	
	switch ($post_type) {
		
		/*case "video":
			$cat = "Video";	
			$html = '<a href="' . get_post_type_archive_link( $post_type ) . '" class="td-post-category">'  . $cat . '</a>';
		break;
		
		case "audio":
			$cat = "Audio";
			$html = '<a href="' . get_post_type_archive_link( $post_type ) . '" class="td-post-category">'  . $cat . '</a>';
		break;*/
		
		default:
			$terms = get_the_terms( $id, 'category' );
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){

					$cat = $terms[0]->name;   
			} else {
				$cat = $post_type; 
				$style = "display: none;";  
			}
			$html = '<a href="' . esc_url( get_category_link( $terms[0]->term_id ) ) . '" class="td-post-category" style="'.$style.'">'  . esc_html( $cat ). '</a>';
		break;
		
	}
		
	return $html;
	
}

//Support for co-authors in single templates
function show_authors_single() {
	
	if(function_exists('get_coauthors')) {
	
	    if (td_util::get_option('tds_p_show_author_name') != 'hide') {
            
            $coauths = get_coauthors();
            $string = "";
            $count = count($coauths);
            if ($count > 1) {
                
                $string.= '<div class="td-post-author-name"><div class="td-author-by">By&nbsp;</div>';
            
                foreach ($coauths as $key => $coauth) {
                   $string.= '<a href="'.get_site_url().'/author/' . $coauth->user_nicename . '">' . $coauth->display_name . '</a>';
                 	// add "and"

                    if ($key < ($count - 1)) {
                        $string.= '| ';
                        //$coauths = substr($coauthsHTML, 0, -4);
                    }
                }
                
               $string.= '<div class="td-author-line"> - </div></div>';
			   return $string;
                
            }   else {
                
               return false;
            }
			
			

    	}
	}  else {
                
       return false;
    }
   

	
}

//Support for co-authors in modules
function show_authors_module($postID) {
	
	if(function_exists('get_coauthors')) {

			$coauths = get_coauthors($postID);
			$string = "";
			$count = count($coauths);
            
            if ($count > 1) {
                
                $string.= '<span class="td-post-author-name">';
            
                foreach ($coauths as $key => $coauth) {
                    $string.= '<a href="'.get_site_url().'/author/' . $coauth->user_nicename . '">' . $coauth->display_name . '</a>';
                 // add "and"
                    if ($key < ($count - 1)) {
                         $string.= '<span style="display: inline;"> | </span>';
                         //$coauths = substr($coauthsHTML, 0, -4);
                    }
                }
                
                $string.= '<span> - </span></span>';
				return $string;
                
            } else {
				
				return false;
				
			}
	} else {
				
		return false;
				
	}

}


//Support for co-authors in author boxes
function show_coauthors_box() {
	
	if(function_exists('get_coauthors')) {
	
		$coauths = get_coauthors();
	
		if (count($coauths) > 1) {
		
			$string = '<div class="author-box-list">';
		
			foreach ($coauths as $key => $coauth) {
				
				$string.= '<div class="author-box-wrap"><a href="'.get_site_url().'/author/'.$coauth->user_nicename.'">';
				$string.= get_avatar( $coauth->user_email, '96' );
				$string.= '</a>';
				$string.= '<div class="desc"><div class="td-author-name vcard author"><span class="fn"><a href="/author/'.$coauth->user_nicename.'">' . $coauth->display_name . '</a></span></div>';
				$string.= '<div class="td-author-description">' . $coauth->description . '</div>';
				$string.= '<div class="td-author-social">';
				
				foreach (td_social_icons::$td_social_icons_array as $td_social_id => $td_social_name) {
					//echo get_the_author_meta($td_social_id) . '<br>';
					$authorMeta = get_the_author_meta($td_social_id, $coauth->ID);
					
					if (!empty($authorMeta)) {
						
						$string.= td_social_icons::get_icon($authorMeta, $td_social_id, true);
					}
				}
				
				$string.= '</div>';
				$string.= '</div><div class="clearfix"></div></div>';
			}
			
			$string.= '</div>';
			
			return $string;
			
		} else {
			
			return false;
		}
	} else {
		
		return false;
		
	}
	
}

//Support for guest authors
function guest_author($postID) {
	
	$is_submission = get_post_meta($postID, "is_submission", true);
	$string = "";
	
	if (!$is_submission) {
		
		return false;
		
	} else {
	
		$author_name = get_post_meta($postID, "user_submit_name", true);
		$author_email = get_post_meta($postID, "user_submit_email", true);
		
		if ($author_name) {
		
			$string.= '<span class="td-post-author-name">';
			$string.= '<a href="mailto:' . $author_email . '">' . $author_name . '</a>';
			$string.= '<span> - </span></span>';
		
			return $string;
		
		} else {
			return false;
		}
	}
	
}

//Add guest post category to body for styling purposes
add_filter('body_class','add_category_to_single');
function add_category_to_single($classes) {
  if (is_single() ) {
	global $post;
	
	if (has_category( "guest-posts", $post )) {
	  	$classes[] = "guest-post";
	}
  }
  // return the $classes array
  return $classes;
}
