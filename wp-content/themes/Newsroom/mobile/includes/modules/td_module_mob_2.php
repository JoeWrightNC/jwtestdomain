<?php

class td_module_mob_2 extends td_module {

    function __construct($post) {
        //run the parrent constructor
        parent::__construct($post);
    }

    function render() {
        ob_start();
        ?>

        <div class="<?php echo $this->get_module_classes(); ?>">
            <?php
            echo $this->get_image('td_741x486');
            ?>
            <div class="td-meta-info-container">
                <div class="td-meta-align">
                    <div class="td-big-grid-meta">
                        <?php echo $this->get_category(); ?>
                        <?php echo $this->get_title();?>
                    </div>
                    <div class="td-module-meta-info">
                        <!-- Add support for co-authors plus plugin -->
                        
						<?php $co_authors = show_authors_module($this->post->ID);
						if  (!$co_authors) {
							
							echo $this->get_author();
							
						} else {
							
							echo $co_authors;
						} ?>
                        <?php echo $this->get_date();?>
                    </div>
                </div>
            </div>

        </div>

        <?php return ob_get_clean();
    }
}