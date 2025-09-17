<?php // Admin Page
class Admin {

    public function __construct() {

        // Remove all third party notices and enqueue style and script
        add_action('admin_enqueue_scripts', [$this, 'admin_script_n_style']);

        // Add admin page
        add_action('admin_menu', [$this, 'consult_admin_page']);

        add_action('wp_ajax_admin_install_plug', array($this, 'install_plug_ajax'));
        // add_action('wp_ajax_nopriv_admin_install_plug', array($this, 'install_plug_ajax'));

    }

    public function admin_script_n_style() {
        $screen = get_current_screen();
        if (isset($screen->base) && $screen->base == 'toplevel_page_consult-admin') {
            remove_all_actions('admin_notices');

            wp_enqueue_script('consult-element-admin', CONSULT_ELEMENT_URI . 'js/admin.js', array('jquery'), CONSULT_ELEMENT_VERSION, array());

            wp_localize_script(
                'consult-element-admin',
                'admin_ajax_obj', 
                array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('esh_admin_nonce_check'),
                    'import_url' => admin_url('themes.php?page=ananta-demo-import')
                )
            );

            wp_enqueue_style('admin-notice-styles', CONSULT_ELEMENT_URI . 'css/admin.css', array(), CONSULT_ELEMENT_VERSION);

            add_filter('admin_footer_text', [$this, 'esh_remove_admin_footer_text']);
        }
    }

    public function consult_admin_page() {
        // Add top-level menu page
        $menuImg = CONSULT_ELEMENT_URI . 'images/menu-logo.png';
        add_menu_page(
            'Consult Element',  // Page title
            'Consult',            // Menu title
            'manage_options',   // Capability required to access the page
            'consult-admin',      // Menu slug
            array($this, 'consult_admin_page_content'), // Callback function
            $menuImg, // Icon
            20                  // Position
        );
    }

    public function esh_remove_admin_footer_text() {
        return 'Enjoyed <span class="consult-el-footer-thankyou"><strong>Consult</strong>? Please leave us a <a href="https://wordpress.org/support/theme/consult/reviews/?rate=5#new-post" target="_blank">★★★★★</a></span> rating. We really appreciate your support!';
    }

    public function consult_free_features() {
        return array(
     
            array( 'type' => 'lite' , 'name' => 'Creative Button', 'demo' => 'https://anantaddons.com/creative-button/' ),

            array( 'type' => 'lite' , 'name' => 'Dual Color Heading', 'demo' => 'https://anantaddons.com/dual-color-heading/' ),
        
            array( 'type' => 'lite' , 'name' => 'Service Box', 'demo' => 'https://anantaddons.com/service/' ),
            
            array( 'type' => 'lite' , 'name' => 'Portfolios', 'demo' => 'https://anantaddons.com/portfolio/' ),

            array( 'type' => 'lite' , 'name' => 'Testimonials', 'demo' => 'https://anantaddons.com/testimonial/' ),

            array( 'type' => 'lite' , 'name' => 'Team Members', 'demo' => 'https://anantaddons.com/team/' ),
    
            array( 'type' => 'lite' , 'name' => 'Blog Post', 'demo' => 'https://anantaddons.com/blog-post/' ),
            
            array( 'type' => 'lite' , 'name' => 'Progress Bar', 'demo' => 'https://anantaddons.com/progress-bar/' ),
            
            array( 'type' => 'lite' , 'name' => 'Menus', 'demo' => '#' ),
           
        );
    }

    public function consult_premium_features(){
        return array(
     
            array( 'type' => 'pro' , 'name' => 'Advance Creative Button', 'demo' => 'https://anantaddons.com/creative-button/' ),

            array( 'type' => 'pro' , 'name' => 'Advance Dual Color Heading', 'demo' => 'https://anantaddons.com/dual-color-heading/' ),
        
            array( 'type' => 'pro' , 'name' => 'Advance Service Box', 'demo' => 'https://anantaddons.com/service/' ),
            
            array( 'type' => 'pro' , 'name' => 'Advance Portfolios', 'demo' => 'https://anantaddons.com/portfolio/' ),

            array( 'type' => 'pro' , 'name' => 'Advance Testimonials', 'demo' => 'https://anantaddons.com/testimonial/' ),

            array( 'type' => 'pro' , 'name' => 'Advance Team Members', 'demo' => 'https://anantaddons.com/team/' ),
    
            array( 'type' => 'pro' , 'name' => 'Advance Blog Post', 'demo' => 'https://anantaddons.com/blog-post/' ),
            
            array( 'type' => 'pro' , 'name' => 'Advance Progress Bar', 'demo' => 'https://anantaddons.com/progress-bar/' ),
            
            array( 'type' => 'pro' , 'name' => 'Advance Menus', 'demo' => '#' ),
            
            array( 'type' => 'pro' , 'name' => 'Advance Portfolio Carousel', 'demo' => 'https://anantaddons.com/portfolio-carousel/' ),
            
            array( 'type' => 'pro' , 'name' => 'Advance Testimonials Carousel', 'demo' => 'https://anantaddons.com/testimonial-carousel/' ),
            
            array( 'type' => 'pro' , 'name' => 'Advance Team Members Carousel', 'demo' => 'https://anantaddons.com/team-carousel/' ),
        );
    }

    public function consult_admin_page_content() { 
        $consult_free_features = $this->consult_free_features();
        $consult_premium_features = $this->consult_premium_features(); ?>

        <div class="page-content">
            <div class="tabbed">
                <input type="radio" id="tab1" name="css-tabs" <?php if( !isset($_GET['tab']) || isset($_GET['tab']) && $_GET['tab'] == 'welcome' ){ echo 'checked'; } ?> >
                <input type="radio" id="tab2" name="css-tabs" <?php if( isset($_GET['tab']) && $_GET['tab'] == 'starter-sites' ){ echo 'checked'; } ?> >
                <input type="radio" id="tab3" name="css-tabs" <?php if( isset($_GET['tab']) && $_GET['tab'] == 'plugins' ){ echo 'checked'; } ?> >
                <div class="head-top-items">
                    <div class="head-item">
                        <a href="#" class="site-icon"><img src="<?php echo CONSULT_ELEMENT_URI . 'images/site-logo.png' ?>" alt=""></a>
                        <ul class="tabs">
                            <li class="tab">
                                <label for="tab1" tab="welcome">
                                <a  href="<?php echo esc_url( add_query_arg( [ 'tab'   => 'welcome'] ) ); ?>">
                                    <?php esc_html_e( 'Welcome', 'consult-element' ); ?>
                                </a>
                                </label>
                            </li>
                            <li class="tab">
                                <label for="tab2" tab="starter-sites">
                                <a  href="<?php echo esc_url( add_query_arg( [ 'tab'   => 'starter-sites'] ) ); ?>">
                                    <?php esc_html_e( 'Starter Sites', 'consult-element' ); ?>
                                </a>
                                </label>
                            </li>
                            <li class="tab">
                                <label for="tab3" tab="plugins">
                                <a  href="<?php echo esc_url( add_query_arg( [ 'tab'   => 'plugins'] ) ); ?>">
                                    <?php esc_html_e( 'Plugins', 'consult-element' ); ?>
                                </a>
                                </label>
                            </li>
                        </ul>
                    </div>
                    <div class="right-top-area">
                        <div class="version">
                            <span><?php echo CONSULT_ELEMENT_VERSION; ?></span>
                        </div>
                        <div class="feature_pro">
                            <a href="https://anantaddons.com/pricing/" target="_blank" title="Upgrade to Pro">
                                <span class="head-icon"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 24 24"
                                        fill="none" style="fill: #fff;">
                                        <path
                                            d="M19.6872 14.0931L19.8706 12.3884C19.9684 11.4789 20.033 10.8783 19.9823 10.4999L20 10.5C20.8284 10.5 21.5 9.82843 21.5 9C21.5 8.17157 20.8284 7.5 20 7.5C19.1716 7.5 18.5 8.17157 18.5 9C18.5 9.37466 18.6374 9.71724 18.8645 9.98013C18.5384 10.1814 18.1122 10.606 17.4705 11.2451L17.4705 11.2451C16.9762 11.7375 16.729 11.9837 16.4533 12.0219C16.3005 12.043 16.1449 12.0213 16.0038 11.9592C15.7492 11.847 15.5794 11.5427 15.2399 10.934L13.4505 7.7254C13.241 7.34987 13.0657 7.03557 12.9077 6.78265C13.556 6.45187 14 5.77778 14 5C14 3.89543 13.1046 3 12 3C10.8954 3 10 3.89543 10 5C10 5.77778 10.444 6.45187 11.0923 6.78265C10.9343 7.03559 10.759 7.34984 10.5495 7.7254L8.76006 10.934C8.42056 11.5427 8.25081 11.847 7.99621 11.9592C7.85514 12.0213 7.69947 12.043 7.5467 12.0219C7.27097 11.9837 7.02381 11.7375 6.5295 11.2451C5.88787 10.606 5.46156 10.1814 5.13553 9.98012C5.36264 9.71724 5.5 9.37466 5.5 9C5.5 8.17157 4.82843 7.5 4 7.5C3.17157 7.5 2.5 8.17157 2.5 9C2.5 9.82843 3.17157 10.5 4 10.5L4.01771 10.4999C3.96702 10.8783 4.03162 11.4789 4.12945 12.3884L4.3128 14.0931C4.41458 15.0393 4.49921 15.9396 4.60287 16.75H19.3971C19.5008 15.9396 19.5854 15.0393 19.6872 14.0931Z"
                                            fill="#1C274C" style="&#10;    fill: #fff;&#10;" />
                                        <path
                                            d="M10.9121 21H13.0879C15.9239 21 17.3418 21 18.2879 20.1532C18.7009 19.7835 18.9623 19.1172 19.151 18.25H4.84896C5.03765 19.1172 5.29913 19.7835 5.71208 20.1532C6.65817 21 8.07613 21 10.9121 21Z"
                                            fill="#1C274C" style="&#10;    fill: #fff;&#10;" />
                                    </svg>
                                </span>
                                <span class="head-title"><?php esc_html_e( 'Upgrade to Pro', 'consult-element' ); ?></span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="consult-el-main-area">
                    <div class="tab-contents">
                        <div class="tab-content welcome">
                            <div class="item-content flex align-center gap-30">
                                <div class="text-content">
                                    <h2 class="heading-content"> 
                                        <?php esc_html_e( 'Welcome to ', 'consult-element' ); 
                                        $current_theme = wp_get_theme();
                                        echo esc_html( $current_theme->get( 'Name' ) );                                       
                                        ?>
                                    </h2>
                                    <p><?php esc_html_e( 'Consult Elements is a sleek, fast, and highly customizable WordPress theme crafted specifically for consulting firms and business professionals. Perfect for building modern, client-focused websites, it’s lightweight, performance-optimized, and designed to showcase services, build credibility, and drive inquiries.', 'consult-element' );?></p>
                                    <div class="buttons flex gap-15">
                                        <a href="<?php echo esc_url( add_query_arg( [ 'tab'   => 'starter-sites'] ) ); ?>" class="btn-default"><?php esc_html_e( 'Get Starter Sites', 'consult-element' );?></a>
                                        <a href="#" class="btn-default">Watch and Launch Quickly!</a>
                                    </div>
                                </div>
                                <!-- media -->
                                <div class="consult-media">
                                    <img src="<?php echo esc_url( CONSULT_ELEMENT_URI . 'images/admin-image.jpg' ); ?>" alt="Notice Image">
                                </div>
                            </div>
                            
                            <div class="grid mt-30 gap-30 column-4">
                                <div class="consult-el-key-features col-span-3">
                                    <div class="consult-el-key-features-free">
                                        <h2 class="consult-el-key-feature-title">Elevate Your Business with Consult Features</h2>
                                        <div class="consult-el-key-features_content">
                                            <?php foreach ($consult_free_features as $features) { ?>
                                                <div class="consult-el-key-feature-box">
                                                    <div class="consult-el-key-features-title-area">
                                                        <h5 class="consult-el-key-features-title"><a href="<?php echo esc_url($features['demo']); ?>" target="_blank"><?php echo esc_html($features['name']); ?></a></h5>
                                                    </div>
                                                    <div class="consult-el-key-features-btn-area anant-admin-f-center">
                                                        <a href="<?php echo esc_url($features['demo']); ?>" target="_blank" class="edit"><i class="dashicons dashicons-external"></i></a> 
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="consult-el-key-feature-premium">
                                        <h2 class="consult-el-key-feature-title">Unlock Your Business's Full Potential with Pro Features.</h2>
                                        <div class="consult-el-key-features_content">
                                            <?php foreach ($consult_premium_features as $features) { ?>
                                                <div class="consult-el-key-feature-box">
                                                    <div class="consult-el-key-features-title-area">
                                                        <h5 class="consult-el-key-features-title"><a href="<?php echo esc_url($features['demo']); ?>" target="_blank"><?php echo esc_html($features['name']); ?></a></h5>
                                                        <?php if($features['type'] == 'pro'){ ?>
                                                            <span class="consult-el-pro-feature">Pro</span>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="consult-el-key-features-btn-area anant-admin-f-center">
                                                        <a href="<?php echo esc_url($features['demo']); ?>" target="_blank" class="edit"><i class="dashicons dashicons-external"></i></a> 
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <!-- <div class="consult-el-key-feature-box">
                                                <div class="consult-el-key-features-title-area">
                                                    <h5 class="consult-el-key-features-title"><a href="" target="_blank">Product Slider</a></h5>
                                                </div>
                                                <div class="consult-el-key-features-btn-area anant-admin-f-center">
                                                    <a href="" target="_blank" class="edit"><i class="dashicons dashicons-external"></i></a> 
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="consult-el-quick-links">
                                    <div class="consult-el-quick-link-box">
                                        <div class="consult-el-item-icon-title">
                                            <h2 class="consult-el-heading">Upgrade to Pro</h2>
                                            <span class="consult-el-item-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" style="fill: white; width: 25px; height: 25px;" viewBox="0 0 640 512"><path d="M528 448H112c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h416c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm64-320c-26.5 0-48 21.5-48 48 0 7.1 1.6 13.7 4.4 19.8L476 239.2c-15.4 9.2-35.3 4-44.2-11.6L350.3 85C361 76.2 368 63 368 48c0-26.5-21.5-48-48-48s-48 21.5-48 48c0 15 7 28.2 17.7 37l-81.5 142.6c-8.9 15.6-28.9 20.8-44.2 11.6l-72.3-43.4c2.7-6 4.4-12.7 4.4-19.8 0-26.5-21.5-48-48-48S0 149.5 0 176s21.5 48 48 48c2.6 0 5.2-.4 7.7-.8L128 416h384l72.3-192.8c2.5 .4 5.1 .8 7.7 .8 26.5 0 48-21.5 48-48s-21.5-48-48-48z"/></svg>
                                            </span>
                                        </div>
                                        <a href="https://anantaddons.com/pricing/" target="_blank"></a>
                                        <p class="consult-el-paragraph">Unlock advanced customization and enjoy premium support from our team of WordPress wizards.</p>   
                                        <a href="https://anantaddons.com/pricing/" target="_blank" class="consult-el-sm-link">Buy Now!</a>                             
                                    </div>

                                    <div class="consult-el-quick-link-box">
                                        <div class="consult-el-item-icon-title">
                                            <h2 class="consult-el-heading">Explore the Guide</h2>
                                            <span class="consult-el-item-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" style="fill: white; width: 25px; height: 25px;" viewBox="0 0 384 512"><path d="M288 248v28c0 6.6-5.4 12-12 12H108c-6.6 0-12-5.4-12-12v-28c0-6.6 5.4-12 12-12h168c6.6 0 12 5.4 12 12zm-12 72H108c-6.6 0-12 5.4-12 12v28c0 6.6 5.4 12 12 12h168c6.6 0 12-5.4 12-12v-28c0-6.6-5.4-12-12-12zm108-188.1V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V48C0 21.5 21.5 0 48 0h204.1C264.8 0 277 5.1 286 14.1L369.9 98c9 8.9 14.1 21.2 14.1 33.9zm-128-80V128h76.1L256 51.9zM336 464V176H232c-13.3 0-24-10.7-24-24V48H48v416h288z"/></svg>
                                            </span>
                                        </div>
                                        <a href="https://anantaddons.com/docs/" target="_blank"></a>
                                        <p class="consult-el-paragraph">Struggling to figure it out? Let our detailed guides be your ultimate problem-solver!</p>   
                                        <a href="https://anantaddons.com/docs/" target="_blank" class="consult-el-sm-link">Explore Now</a>                             
                                    </div>

                                    <div class="consult-el-quick-link-box">
                                        <div class="consult-el-item-icon-title">
                                            <h2 class="consult-el-heading">Rate Us</h2>
                                            <span class="consult-el-item-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" style="fill: white; width: 25px; height: 25px;" viewBox="0 0 576 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M528.1 171.5L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6zM388.6 312.3l23.7 138.4L288 385.4l-124.3 65.3 23.7-138.4-100.6-98 139-20.2 62.2-126 62.2 126 139 20.2-100.6 98z"/></svg>
                                            </span>
                                        </div>
                                        <a href="https://wordpress.org/support/theme/consult-element/reviews/#new-post" target="_blank"></a>
                                        <p class="consult-el-paragraph">Share your thoughts! Please leave a review and help us improve your experience.</p>   
                                        <a href="https://wordpress.org/support/theme/consult-element/reviews/#new-post" target="_blank" class="consult-el-sm-link">Submit a Review</a>                             
                                    </div>

                                    <div class="consult-el-quick-link-box">
                                        <div class="consult-el-item-icon-title">
                                            <h2 class="consult-el-heading">Our support</h2>
                                            <span class="consult-el-item-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" style="fill: white; width: 25px; height: 25px;" viewBox="0 0 512 512"><path d="M192 208c0-17.7-14.3-32-32-32h-16c-35.4 0-64 28.7-64 64v48c0 35.4 28.7 64 64 64h16c17.7 0 32-14.3 32-32V208zm176 144c35.4 0 64-28.7 64-64v-48c0-35.4-28.7-64-64-64h-16c-17.7 0-32 14.3-32 32v112c0 17.7 14.3 32 32 32h16zM256 0C113.2 0 4.6 118.8 0 256v16c0 8.8 7.2 16 16 16h16c8.8 0 16-7.2 16-16v-16c0-114.7 93.3-208 208-208s208 93.3 208 208h-.1c.1 2.4 .1 165.7 .1 165.7 0 23.4-18.9 42.3-42.3 42.3H320c0-26.5-21.5-48-48-48h-32c-26.5 0-48 21.5-48 48s21.5 48 48 48h181.7c49.9 0 90.3-40.4 90.3-90.3V256C507.4 118.8 398.8 0 256 0z"/></svg>
                                            </span>
                                        </div>
                                        <a href="https://wordpress.org/support/theme/consult-element/" target="_blank"></a>
                                        <p class="consult-el-paragraph">Need help or have feedback? Join our Support Forum for quick answers and friendly advice!</p>   
                                        <a href="https://wordpress.org/support/theme/consult-element/" target="_blank" class="consult-el-sm-link">Ask for Help</a>                             
                                    </div>

                                    <div class="consult-el-quick-link-box">
                                        <div class="consult-el-item-icon-title">
                                            <h2 class="consult-el-heading">Feature Request</h2>
                                            <span class="consult-el-item-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" style="fill: white; width: 25px; height: 25px;" viewBox="0 0 512 512"><path d="M168.2 384.9c-15-5.4-31.7-3.1-44.6 6.4c-8.2 6-22.3 14.8-39.4 22.7c5.6-14.7 9.9-31.3 11.3-49.4c1-12.9-3.3-25.7-11.8-35.5C60.4 302.8 48 272 48 240c0-79.5 83.3-160 208-160s208 80.5 208 160s-83.3 160-208 160c-31.6 0-61.3-5.5-87.8-15.1zM26.3 423.8c-1.6 2.7-3.3 5.4-5.1 8.1l-.3 .5c-1.6 2.3-3.2 4.6-4.8 6.9c-3.5 4.7-7.3 9.3-11.3 13.5c-4.6 4.6-5.9 11.4-3.4 17.4c2.5 6 8.3 9.9 14.8 9.9c5.1 0 10.2-.3 15.3-.8l.7-.1c4.4-.5 8.8-1.1 13.2-1.9c.8-.1 1.6-.3 2.4-.5c17.8-3.5 34.9-9.5 50.1-16.1c22.9-10 42.4-21.9 54.3-30.6c31.8 11.5 67 17.9 104.1 17.9c141.4 0 256-93.1 256-208S397.4 32 256 32S0 125.1 0 240c0 45.1 17.7 86.8 47.7 120.9c-1.9 24.5-11.4 46.3-21.4 62.9zM144 272a32 32 0 1 0 0-64 32 32 0 1 0 0 64zm144-32a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm80 32a32 32 0 1 0 0-64 32 32 0 1 0 0 64z"/></svg>
                                            </span>
                                        </div>
                                        <a href="https://wordpress.org/support/theme/consult/reviews/?rate=5#new-post" target="_blank"></a>
                                        <p class="consult-el-paragraph">We’d love to hear your ideas—share any features you think could make our product even better!</p>   
                                        <a href="https://anantsites.com/support/support-ticket/" target="_blank" class="consult-el-sm-link">Send Feedback</a>                             
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="tab-content starter-sites">
                            <?php if(!$this->is_plugin_installed('ananta-sites') || !is_plugin_active($this->retrive_plugin_install_path('ananta-sites'))){ ?>
                                <div class="modal-main">
                                    <div class="modal-image overlay">
                                        <img src="<?php echo CONSULT_ELEMENT_URI . 'images/demos.jpg' ?>" alt="">
                                    </div>
                                    <div class="modal-popup">
                                        <div class="modal-popup-content">
                                            <div class="modal-icon">
                                                <img src="<?php echo CONSULT_ELEMENT_URI . 'images/anantsite-logo.png' ?>" alt="">
                                            </div>
                                            <div>
                                                <h4>Anant Sites</h4>
                                                <p>Elevate Your Consulting Website Instantly with 10+ Elementor Templates from Anant Sites.</p>
                                                <a href="#" class="btn-default ins-ant-site" plug="ananta-sites" status="<?php echo $this->plugin_status_check('ananta-sites'); ?>">
                                                    <?php if (!$this->is_plugin_installed('ananta-sites')) {
                                                        esc_html_e('Install Anant Sites', 'consult-element');
                                                    } elseif (!is_plugin_active($this->retrive_plugin_install_path('ananta-sites'))) {
                                                        esc_html_e('Activate Anant Sites', 'consult-element');
                                                    } else {
                                                        esc_html_e( 'Import Demo', 'consult-element' );
                                                    }
                                                    ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { 
                                    
                                $theme_data_api = wp_remote_get(esc_url_raw("https://template.anantaddons.com/wp-json/wp/v2/demos/?per_page=100"));

                                $theme_data_api_body = wp_remote_retrieve_body($theme_data_api);
                                $all_demos = json_decode($theme_data_api_body, TRUE);
                                if ($all_demos === null) { ?>
                                    <script type="text/javascript">
                                        window.location.reload();
                                    </script>
                                <?php }
                                foreach($all_demos as $key => $demo){
                                    if( in_array("block-editor", $demo['meta']['template_type']) ) {
                                        unset($all_demos[$key]);
                                    }
                                }
                                array_values($all_demos);

                                if (count($all_demos) == 0) {
                                    wp_die('There are no demos available for this theme!');
                                } ?>
                                <section class="ali-templates-main">
                                    <!-- Start: Demo Grid -->
                                    <div class="algrid-wrap theme-grid-wrap">
                                        <?php foreach($all_demos as $demo) { ?>
                                            <div class="grid-item" data-theme_type="<?php echo esc_attr(strtolower($demo['meta']['plugin_type'][0])); ?>" data-name="<?php echo esc_attr(strtolower($demo['title']['rendered'])); ?>" >
                                                <?php 
                                                if(strtolower($demo['meta']['plugin_type'][0]) == "pro"){ ?>
                                                    <span class="alribbon <?php echo esc_attr(strtolower($demo['meta']['plugin_type'][0])); ?>">
                                                        <?php echo esc_attr(ucfirst($demo['meta']['plugin_type'][0])); ?>
                                                    </span>
                                                <?php } ?>
                                                <div class="grid-item-images">
                                                    <img src="<?php echo esc_url($demo['meta']['preview_url'][0]); ?>" />
                                                    <div class="grid-item-overlay flex items-center justify-center">
                                                        <?php if ($this->is_plugin_installed('anant-addons-for-elementor-pro') === false && strtolower($demo['meta']['plugin_type'][0]) == "pro"): ?>
                                                            <a class="demos-link" target="_blank"
                                                                href="<?php echo esc_url($demo['meta']['pro_link'][0]);?>">
                                                                <?php esc_html_e('Buy Now', 'consult-element'); ?>
                                                            </a>
                                                        <?php else: ?>
                                                            <a class="demos-link" href="<?php echo esc_url(admin_url().'admin.php?page=ananta-demo-import&step=2&editor=elementor&theme_id='.$demo['id'].'&preview_url='.esc_url($demo['meta']['preview_link'][0]));?>">
                                                                <?php esc_html_e('Import', 'consult-element'); ?>
                                                            </a>
                                                        <?php endif; ?>
                                                        <a aria-current="page" href="<?php echo !empty($demo['meta']['preview_link'][0]) ? esc_url(admin_url().'admin.php?page=ananta-demo-import&step=preview&editor=elementor&theme_id='.$demo['id'].'&preview_url='.esc_url($demo['meta']['preview_link'][0]).'&pro_link='.esc_url($demo['meta']['pro_link'][0]).'&dtn='.esc_attr($demo['meta']['plugin_name'][0])) : '#'; ?>" class="demos-preview-link">
                                                            <?php esc_html_e('Preview', 'consult-element'); ?>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="grid-item-content flex justify-between align-center">
                                                <h5><?php echo esc_html($demo['title']['rendered']); ?></h5>
                                                <?php if ($this->is_plugin_installed('anant-addons-for-elementor-pro') === false && strtolower($demo['meta']['plugin_type'][0]) == "pro"): ?>
                                                    <a class="pro-demos-link" target="_blank"
                                                        href="<?php echo esc_url($demo['meta']['pro_link'][0]);?>">
                                                        <?php esc_html_e('Buy Now', 'consult-element'); ?>
                                                    </a>
                                                <?php else: ?>
                                                    <a class="import" href="<?php echo esc_url(admin_url().'admin.php?page=ananta-demo-import&&step=2&editor=elementor&theme_id='.$demo['id'].'&preview_url='.esc_url($demo['meta']['preview_link'][0]));?>">Import</a>
                                                <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <!-- End: Demo Grid -->
                                </section>
                            <?php } ?>
                        </div>

                        <div class="tab-content plugins">
                            <!-- plugin area -->
                            <div class="grid column-4 gap-30">
                                <div class="consult-el-quick-link-box">
                                    <div class="consult-el-img">
                                        <img src="<?php echo CONSULT_ELEMENT_URI . 'images/anantsite-logo.png' ?>" alt="">
                                        <h2 class="consult-el-img-heading">Anant Sites</h2>
                                    </div>
                                    <p class="consult-el-paragraph">Anant Sites offers 40+ pre-made Elementor templates, including Woo-ready designs.</p>
                                    <a href="#" class="consult-el-btn-link" plug="ananta-sites" status="<?php echo $this->plugin_status_check('ananta-sites'); ?>">
                                        <?php if (!$this->is_plugin_installed('ananta-sites')) {
                                                esc_html_e('Install', 'consult-element');
                                            } elseif (!is_plugin_active($this->retrive_plugin_install_path('ananta-sites'))) {
                                                esc_html_e('Activate', 'consult-element');
                                            } else {
                                                esc_html_e('Activated', 'consult-element' );
                                            }
                                        ?>
                                    </a>
                                </div>
                                <div class="consult-el-quick-link-box">
                                    <div class="consult-el-img">
                                        <img src="<?php echo CONSULT_ELEMENT_URI . 'images/anantsite-logo.png' ?>" alt="">
                                        <h2 class="consult-el-img-heading">Anant Addons</h2>
                                    </div>
                                    <p class="consult-el-paragraph">Enhance your Elementor experience with 90+ essential elements and extensions.</p>
                                    <a href="#" class="consult-el-btn-link" plug="anant-addons-for-elementor" status="<?php echo $this->plugin_status_check('anant-addons-for-elementor'); ?>">
                                        <?php if (!$this->is_plugin_installed('anant-addons-for-elementor')) {
                                                esc_html_e('Install', 'consult-element');
                                            } elseif (!is_plugin_active($this->retrive_plugin_install_path('anant-addons-for-elementor'))) {
                                                esc_html_e('Activate', 'consult-element');
                                            } else {
                                                esc_html_e('Activated', 'consult-element' );
                                            }
                                        ?>
                                    </a>
                                </div>
                                <div class="consult-el-quick-link-box">
                                    <div class="consult-el-img">
                                        <img src="<?php echo CONSULT_ELEMENT_URI . 'images/postextra-logo.png' ?>" alt="">
                                        <h2 class="consult-el-img-heading">Post Extra</h2>
                                    </div>
                                    <p class="consult-el-paragraph">Boost your content with Post Extra’s customizable Gutenberg posts blocks.</p>
                                    <a href="#" class="consult-el-btn-link" plug="post-extra" status="<?php echo $this->plugin_status_check('post-extra'); ?>">
                                        <?php if (!$this->is_plugin_installed('post-extra')) {
                                                esc_html_e('Install', 'consult-element');
                                            } elseif (!is_plugin_active($this->retrive_plugin_install_path('post-extra'))) {
                                                esc_html_e('Activate', 'consult-element');
                                            } else {
                                                esc_html_e('Activated', 'consult-element' );
                                            }
                                        ?>
                                    </a>
                                </div>
                                <div class="consult-el-quick-link-box">
                                    <div class="consult-el-img">
                                        <img src="<?php echo CONSULT_ELEMENT_URI . 'images/gutenwawe-logo.png' ?>" alt="">
                                        <h2 class="consult-el-img-heading">Gutenwave</h2>
                                    </div>
                                    <p class="consult-el-paragraph">Create WordPress pages effortlessly with Gutenwave, your ultimate tool for seamless design and innovation.</p>
                                    <a href="#" class="consult-el-btn-link" plug="gutenwave-blocks" status="<?php echo $this->plugin_status_check('gutenwave-blocks'); ?>">
                                        <?php if (!$this->is_plugin_installed('gutenwave-blocks')) {
                                                esc_html_e('Install', 'consult-element');
                                            } elseif (!is_plugin_active($this->retrive_plugin_install_path('gutenwave-blocks'))) {
                                                esc_html_e('Activate', 'consult-element');
                                            } else {
                                                esc_html_e('Activated', 'consult-element' );
                                            }
                                        ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    <?php }

    public function install_plug_ajax() {
        // // Verify nonce
        if (!isset($_POST['esh_admin_nonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['esh_admin_nonce'])), 'esh_admin_nonce_check')) {
            wp_send_json_error('Nonce verification failed');
        }

        // Check user capabilities
        if (!current_user_can('edit_posts')) {
            wp_send_json_error('Insufficient permissions');
        }

        // check plugin name
        if (isset($_POST['plugin_name'])) {
            $plugin = $_POST['plugin_name'];
        }

        /* Get All Plugins Installed In Wordpress */
        $all_wp_plugins = get_plugins();
        $installed_plugins = [];

        $plugin_status = $this->consult_element_get_required_plugin_status($plugin, $all_wp_plugins);
        if ($plugin_status == 'not-installed') {
            $this->install_plugin(['slug' => $plugin]);
            $installed_plugins['installed'][] = $plugin;
            $myplugin = $this->get_plugin_install_path($plugin);
            if ($myplugin) {
                $installed_plugins['activated'][] = !is_null(activate_plugin($myplugin, '', false, false)) ?: $plugin;
            }
            wp_send_json_success('Plugin Installed and activated Successfully');
        } else if ($plugin_status == 'inactive') {
            $myplugin = $this->get_plugin_install_path($plugin);
            if ($myplugin) {
                $installed_plugins['activated'][] = !is_null(activate_plugin($myplugin, '', false, false)) ?: $plugin;
            }
            wp_send_json_success('Plugin Activated Successfully');
        } else if ($plugin_status == 'active') {
            $installed_plugins['activated'][] = $plugin;
            wp_send_json_success('Plugin Installed Successfully');
        }else{
            wp_send_json_error('Something is wrong');
        }
    }

    public function is_plugin_installed($plugin_slug) {
        $all_plugins = get_plugins();
        foreach ($all_plugins as $key => $wp_plugin) {
            $folder_arr = explode("/", $key);
            $folder = $folder_arr[0];
            if ($folder == $plugin_slug) {
                return true;
            }
        }
        return false;
    }

    private function get_plugin_install_path($plugin_slug) {
        $all_plugins = get_plugins();
        foreach ($all_plugins as $key => $wp_plugin) {
            $folder_arr = explode("/", $key);
            $folder = $folder_arr[0];
            if ($folder == $plugin_slug) {
                return (string) $key;
                break;
            }
        }
        return false;
    }

    /**
     * Install Plugin
     *
     * @param array $plugin Required Plugin.
     */
    public function install_plugin($plugin = array()) {

        if (!isset($plugin['slug']) || empty($plugin['slug'])) {
            return esc_html__('Invalid plugin slug', 'consult-element');
        }

        include_once ABSPATH . 'wp-admin/includes/plugin.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        include_once ABSPATH . 'wp-admin/includes/plugin-install.php';



        $api = plugins_api(
            'plugin_information',
            array(
                'slug' => sanitize_key(wp_unslash($plugin['slug'])),
                'fields' => array(
                    'sections' => false,
                ),
            )
        );

        if (is_wp_error($api)) {
            $status['errorMessage'] = $api->get_error_message();
            return $status;
        }

        $skin = new WP_Ajax_Upgrader_Skin();
        $upgrader = new Plugin_Upgrader($skin);
        $result = $upgrader->install($api->download_link);

        if (is_wp_error($result)) {
            return $result->get_error_message();
        } elseif (is_wp_error($skin->result)) {
            return $skin->result->get_error_message();
        } elseif ($skin->get_errors()->has_errors()) {
            return $skin->get_error_messages();
        } elseif (is_null($result)) {
            global $wp_filesystem;

            // Pass through the error from WP_Filesystem if one was raised.
            if ($wp_filesystem instanceof WP_Filesystem_Base && is_wp_error($wp_filesystem->errors) && $wp_filesystem->errors->has_errors()) {
                return esc_html($wp_filesystem->errors->get_error_message());
            }

            return esc_html__('Unable to connect to the filesystem. Please confirm your credentials.', 'consult-element');
        }

        /* translators: %s plugin name. */
        return sprintf(esc_html__('Successfully installed "%s" plugin!', 'consult-element'), $api->name);
    }

    public function retrive_plugin_install_path($plugin_slug) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
        $all_plugins = get_plugins();
        foreach ($all_plugins as $key => $wp_plugin) {
            $folder_arr = explode("/", $key);
            $folder = $folder_arr[0];
            if ($folder == $plugin_slug) {
                return (string) $key;
                break;
            }
        }
        return false;
    }

    private function consult_element_get_required_plugin_status($plugin, $all_plugins) {
        $response = 'not-installed';
        foreach ($all_plugins as $key => $wp_plugin) {
            $folder_arr = explode("/", $key);
            $folder = $folder_arr[0];
            if ($folder == $plugin) {
                if (is_plugin_inactive($key)) {
                    $response = 'inactive';
                } else {
                    $response = 'active';
                }
                return $response;
            }
        }
        return $response;

    }

    private function plugin_status_check($plug_slug){
        $status = '';
        if (!$this->is_plugin_installed($plug_slug)) {
            $status = 'not-installed';
        } elseif (!is_plugin_active($this->retrive_plugin_install_path($plug_slug))) {
            $status = 'not-active';
        } else {
            $status = 'active';
        }
        return $status;
    }
}

$admin_page = new Admin();
