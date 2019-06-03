<?php

class td_module_9 extends td_module {

        function __construct($post, $module_atts = array()) {
        //run the parrent constructor
        parent::__construct($post, $module_atts);
    }
	
    function render() {
        ob_start();
        $title_length = $this->get_shortcode_att('m9_tl');
        ?>

        <div class="<?php echo $this->get_module_classes();?>">

            <div class="item-details">
                <?php echo $this->get_comments();?>
                <?php echo $this->get_title($title_length);?>

                <?php if (td_util::get_option('tds_category_module_9') == 'yes') { if ($this->post->post_type != "post") {
					
					echo custom_category($this->post->ID, $this->post->post_type);
					
				} else {
					
					echo $this->get_category();
				} }?>

                <div class="td-module-meta-info">
                    <!-- Add support for co-authors plus plugin -->
<?php $co_authors = show_authors_module($this->post->ID);
						if  (!$co_authors) {
							
							$guest_author = guest_author($this->post->ID);
					
						if ($guest_author) {
							
							echo $guest_author;
							
						} else {
							
							echo $this->get_author();
							
						}
							
						} else {
							
							echo $co_authors;
						} ?>
                    <?php echo $this->get_date();?>
                </div>

            </div>

	        <?php echo $this->get_quotes_on_blocks();?>

        </div>

        <?php return ob_get_clean();
    }
}