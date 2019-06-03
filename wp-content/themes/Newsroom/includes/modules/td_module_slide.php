<?php

class td_module_slide extends td_module {


    function __construct($post, $module_atts = array()) {
        //run the parrent constructor
        parent::__construct($post, $module_atts);
    }

    function get_title_main() {
        $buffy = '';

        $buffy .= '<div class="td-sbig-title-wrap">';
        $buffy .='<a class="noSwipe" href="' . $this->href . '" rel="bookmark" title="' . $this->title_attribute . '">';
        $buffy .= $this->get_title();
        $buffy .='</a>';
        $buffy .= '</div>';

        return $buffy;
    }
	
	function get_image_custom($thumbType, $css_image = false) {

        $buffy = ''; //the output buffer
        $tds_hide_featured_image_placeholder = td_util::get_option('tds_hide_featured_image_placeholder');
        //retina image
        $srcset_sizes = '';

        // do we have a post thumb or a placeholder?
        if (!is_null($this->post_thumb_id) or ($tds_hide_featured_image_placeholder != 'hide_placeholder')) {

            if (!is_null($this->post_thumb_id)) {
                //if we have a thumb
                // check to see if the thumb size is enabled in the panel, we don't have to check for the default wordpress
                // thumbs (the default ones are already cut and we don't have  a panel setting for them)
                if (td_util::get_option('tds_thumb_' . $thumbType) != 'yes' and $thumbType != 'thumbnail') {
                    //the thumb is disabled, show a placeholder thumb from the theme with the "thumb disabled" message
                    global $_wp_additional_image_sizes;

                    if (empty($_wp_additional_image_sizes[$thumbType]['width'])) {
                        $td_temp_image_url[1] = '';
                    } else {
                        $td_temp_image_url[1] = $_wp_additional_image_sizes[$thumbType]['width'];
                    }

                    if (empty($_wp_additional_image_sizes[$thumbType]['height'])) {
                        $td_temp_image_url[2] = '';
                    } else {
                        $td_temp_image_url[2] = $_wp_additional_image_sizes[$thumbType]['height'];
                    }

					// For custom wordpress sizes (not 'thumbnail', 'medium', 'medium_large' or 'large'), get the image path using the api (no_image_path)
	                $thumb_disabled_path = td_global::$get_template_directory_uri;
	                if (strpos($thumbType, 'td_') === 0) {
			            $thumb_disabled_path = td_api_thumb::get_key($thumbType, 'no_image_path');
		            }
			        $td_temp_image_url[0] = $thumb_disabled_path . '/images/thumb-disabled/' . $thumbType . '.png';

                    $attachment_alt = 'alt=""';
                    $attachment_title = '';

                } else {
                    // the thumb is enabled from the panel, it's time to show the real thumb
                    $td_temp_image_url = wp_get_attachment_image_src($this->post_thumb_id, $thumbType);
                    $attachment_alt = get_post_meta($this->post_thumb_id, '_wp_attachment_image_alt', true );
                    $attachment_alt = 'alt="' . esc_attr(strip_tags($attachment_alt)) . '"';
                    $attachment_title = ' title="' . esc_attr(strip_tags($this->title)) . '"';

                    if (empty($td_temp_image_url[0])) {
                        $td_temp_image_url[0] = '';
                    }

                    if (empty($td_temp_image_url[1])) {
                        $td_temp_image_url[1] = '';
                    }

                    if (empty($td_temp_image_url[2])) {
                        $td_temp_image_url[2] = '';
                    }

                    //retina image
                    //don't display srcset_sizes on DEMO - it messes up Pagespeed score (8 March 2017)
                    if (TD_DEPLOY_MODE != 'demo') {
                        $srcset_sizes = td_util::get_srcset_sizes($this->post_thumb_id, $thumbType, $td_temp_image_url[1], $td_temp_image_url[0]);
                    }

                } // end panel thumb enabled check



            } else {
                //we have no thumb but the placeholder one is activated
                global $_wp_additional_image_sizes;

                if (empty($_wp_additional_image_sizes[$thumbType]['width'])) {
                    $td_temp_image_url[1] = '';
                } else {
                    $td_temp_image_url[1] = $_wp_additional_image_sizes[$thumbType]['width'];
                }

                if (empty($_wp_additional_image_sizes[$thumbType]['height'])) {
                    $td_temp_image_url[2] = '';
                } else {
                    $td_temp_image_url[2] = $_wp_additional_image_sizes[$thumbType]['height'];
                }

                /**
                 * get thumb height and width via api
                 * first we check the global in case a custom thumb is used
                 *
                 * The api thumb is checked only for additional sizes registered and if at least one of the settings (width or height) is empty.
                 * This should be enough to avoid getting a non existing id using api thumb.
                 */
	            if (!empty($_wp_additional_image_sizes) && array_key_exists($thumbType, $_wp_additional_image_sizes) && ($td_temp_image_url[1] == '' || $td_temp_image_url[2] == '')) {
                    $td_thumb_parameters = td_api_thumb::get_by_id($thumbType);
	                $td_temp_image_url[1] = $td_thumb_parameters['width'];
                    $td_temp_image_url[2] = $td_thumb_parameters['height'];
                }

	            // For custom wordpress sizes (not 'thumbnail', 'medium', 'medium_large' or 'large'), get the image path using the api (no_image_path)
	            //$no_thumb_path = td_global::$get_template_directory_uri;
				$no_thumb_path = get_stylesheet_directory_uri();
	            if (strpos($thumbType, 'td_') === 0) {
		            $no_thumb_path = rtrim(td_api_thumb::get_key($thumbType, 'no_image_path'), '/');
	            }
		        $td_temp_image_url[0] = $no_thumb_path . '/images/no-thumb/' . $thumbType . '.png';

                $attachment_alt = 'alt=""';
                $attachment_title = '';
            } //end    if ($this->post_has_thumb) {



            $buffy .= '<div class="td-module-thumb">';
                if (current_user_can('edit_published_posts')) {
                    $buffy .= '<a class="td-admin-edit" href="' . get_edit_post_link($this->post->ID) . '">edit</a>';
                }


                $buffy .= '<a href="' . $this->href . '" rel="bookmark" class="td-image-wrap" title="' . $this->title_attribute . '">';

                    // css image
                    if ($css_image === true) {
                        // retina image
                        if (td_util::get_option('tds_thumb_' . $thumbType . '_retina') == 'yes' && !empty($td_temp_image_url[1])) {
                            $retina_url = wp_get_attachment_image_src($this->post_thumb_id, $thumbType . '_retina');
                            if (!empty($retina_url[0])) {
                                $td_temp_image_url[0] = $retina_url[0];
                            }
                        }
                        $buffy .= '<span class="entry-thumb td-thumb-css" style="background-image: url(' . $td_temp_image_url[0] . ')"></span>';

                    // normal image
                    } else {
                        $buffy .= '<img width="' . $td_temp_image_url[1] . '" height="' . $td_temp_image_url[2] . '" class="entry-thumb" src="' . $td_temp_image_url[0] . '"' . $srcset_sizes . ' ' . $attachment_alt . $attachment_title . '/>';
                    }

                    // on videos add the play icon
                    if (get_post_format($this->post->ID) == 'video' || get_post_format($this->post->ID) == 'audio') {

                        $use_small_post_format_icon_size = false;
                        // search in all the thumbs for the one that we are currently using here and see if it has post_format_icon_size = small
                        foreach (td_api_thumb::get_all() as $thumb_from_thumb_list) {
                            if ($thumb_from_thumb_list['name'] == $thumbType and $thumb_from_thumb_list['post_format_icon_size'] == 'small') {
                                $use_small_post_format_icon_size = true;
                                break;
                            }
                        }

                        // load the small or medium play icon
                        if ($use_small_post_format_icon_size === true) {
                          $buffy .= '<span class="td-video-play-ico td-video-small"><img width="20" height="20" class="td-retina" src="' . get_stylesheet_directory_uri() . '/images/icons/'.get_post_format($this->post->ID).'-small.png' . '" alt="'.get_post_format($this->post->ID).'"/></span>';
                        } else {
                           $buffy .= '<span class="td-video-play-ico"><img width="40" height="40" class="td-retina" src="' . get_stylesheet_directory_uri() . '/images/icons/ico-'.get_post_format($this->post->ID).'-large.png' . '" alt="'.get_post_format($this->post->ID).'"/></span>';
                        }
                    } // end on video if

                $buffy .= '</a>';
            $buffy .= '</div>'; //end wrapper

            return $buffy;
        }
}


    function render($td_column_number, $td_post_count, $td_unique_id_slide) {
        $buffy = '';

        $buffy .= '<div id="' . $td_unique_id_slide . '_item_' . $td_post_count . '" class = "' . $this->get_module_classes(array("td-image-gradient")) . '">';
        switch ($td_column_number) {
            case '1': //one column layout
                $buffy .= $this->get_image_custom('td_324x400');
                break;
            case '2': //two column layout
                $buffy .= $this->get_image_custom('td_696x385');
                break;
            case '3': //three column layout
                $buffy .= $this->get_image_custom('td_1068x580');
                break;
        }

            $buffy .= '<div class="td-slide-meta">';
                if (td_util::get_option('tds_category_module_slide') == 'yes') {
                    $buffy .= '<span class="slide-meta-cat">';
                    $buffy .= $this->get_category();
                    $buffy .= '</span>';
                }
                $buffy .=  $this->get_title();//$this->get_title_main();
                $buffy .= '<div class="td-module-meta-info">';
                    $buffy .= $this->get_author();
                    $buffy .= $this->get_date();
                    $buffy .= $this->get_comments();
                $buffy .= '</div>';
            $buffy .= '</div>';

        $buffy .= '</div>';

        return $buffy;
    }

    function get_category() {
        $buffy = '';

        //read the post meta to get the custom primary category
        $td_post_theme_settings = td_util::get_post_meta_array($this->post->ID, 'td_post_theme_settings');
        if (!empty($td_post_theme_settings['td_primary_cat'])) {
            //we have a custom category selected
            $selected_category_obj = get_category($td_post_theme_settings['td_primary_cat']);
        } else {
            //get one auto
            $categories = get_the_category($this->post->ID);
            if (!empty($categories[0])) {
                if ($categories[0]->name === TD_FEATURED_CAT and !empty($categories[1])) {
                    $selected_category_obj = $categories[1];
                } else {
                    $selected_category_obj = $categories[0];
                }
            }
        }


        if (!empty($selected_category_obj)) { //@todo catch error here
            $buffy .= '<a href="' . get_category_link($selected_category_obj->cat_ID) . '">'  . $selected_category_obj->name . '</a>' ;
        }

        //return print_r($post, true);
        return $buffy;
    }


    //overwrite the default function from td_module.php
    function get_comments() {
        $buffy = '';
        if (td_util::get_option('tds_m_show_comments') != 'hide') {
            $buffy .= '<div class="td-post-comments"><i class="td-icon-comments"></i>';
            $buffy .= '<a href="' . get_comments_link($this->post->ID) . '">';
            $buffy .= get_comments_number($this->post->ID);
            $buffy .= '</a>';
            $buffy .= '</div>';
        }

        return $buffy;
    }
}
//td-icon-views