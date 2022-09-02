<h1><?php _e('Popup Settings', 'popup-creator'); ?></h1>
<div class="arpc_tabbed_wrapper">
    <ul class="nav nav-tabs">
        <li class="active"><a href="choose_template"><?php _e('Choose template', 'arpc-popup-creator'); ?></a></li>
        <li><a href="#overlay"><?php _e('Overlay', 'arpc-popup-creator'); ?></a></li>
        <li><a href="#container"><?php _e('Container', 'arpc-popup-creator'); ?></a></li>
        <li><a href="#title"><?php _e('Title', 'arpc-popup-creator'); ?></a></li>
        <li><a href="#content"><?php _e('Content', 'arpc-popup-creator'); ?></a></li>
        <li><a href="#close"><?php _e('Close Icon', 'arpc-popup-creator'); ?></a></li>
    </ul>

    <div class="tab-content">
        <div id="choose_template" class="tab-pane active">
            <div>
                <form action="">
                    <div class="choose-template-wrapper">
                        <label>
                            <div>
                                <input type="radio" name="template" value="0" <?php checked(0, get_option('template'), 0); ?> id="template1" />
                                Template 1
                            </div>
                            <img src="<?php echo esc_url(ARPC_ASSETS . '/images/template1.png') ?>" alt="Template 1" />
                        </label>

                        <label>
                            <div>
                                <input type="radio" name="template" value="1" <?php checked(1, get_option('template'), 1); ?> id="template2" />
                                Template 2
                            </div>
                            <img src="<?php echo esc_url(ARPC_ASSETS . '/images/template2.png') ?>" alt="Template 2" />
                        </label>
                    </div>
                </form>
            </div>
        </div>
        <div id="overlay" class="tab-pane">
            <div>
                <p>Some sort of content</p>
            </div>
        </div>
        <div id="container" class="tab-pane">
            <p>Hello world</p>
        </div>
        <div id="title" class="tab-pane">
            <p>Some sort of content</p>
        </div>
        <div id="content" class="tab-pane">
            <p>Some sort of content</p>
        </div>
        <div id="close" class="tab-pane">
            <p>Some sort of content</p>
        </div>
    </div>
</div>