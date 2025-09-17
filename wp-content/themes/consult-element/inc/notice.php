<?php class Notice {

    public function __construct() {
        add_action('admin_notices', array($this, 'custom_admin_notice'));
        add_action('admin_enqueue_scripts', array($this, 'consult_element_admin_assests'), 999);
        add_action('wp_ajax_install_plug', array($this, 'install_plug_ajax'));
        add_action('wp_ajax_nopriv_install_plug', array($this, 'install_plug_ajax'));

        add_action( 'wp_ajax_dismissed_notice', array($this, 'dismiss_notice_handler') );
    }

    public function custom_admin_notice() {

        $screen = get_current_screen();
          if ( !$this->is_plugin_installed('ananta-sites') || !is_plugin_active($this->retrive_plugin_install_path('ananta-sites')) ) {
            if ( ! get_option('dismissed-esh-el-notice-option', FALSE ) ) { ?>
                <div class="wrap">
                    <div class="esh-el-notice" data-notice="esh-el-notice-option">
                        <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>                   
                        <div class="esh-el-notice-inner">
                            <div class="esh-el-content">
                                <div class="esh-el-heading">
                                    <h3 class="esh-el-title"> 
                                        <?php esc_html_e( 'Welcome to ', 'consult-element' ); 
                                        $current_theme = wp_get_theme();
                                        echo esc_html( $current_theme->get( 'Name' ) );
                                        ?>
                                    </h3>
                                </div>
                                <div class="esh-el-details">
                                    <p><?php esc_html_e( 'Thanks for choosing the Consult Element theme! With 80+ widgets, you can fully customize your site. Install the Anant Sites plugin to import demo content and launch your site quickly.', 'consult-element' ); ?></p>
                                </div>
                                <div class="esh-el-notice-btn">
                                    <a class="btn notice-action url_ins" href="#">
                                        <?php if(!$this->is_plugin_installed('ananta-sites')){
                                            esc_html_e( 'Get Started with Anant Sites', 'consult-element' );
                                        } elseif (!is_plugin_active($this->retrive_plugin_install_path('ananta-sites'))) {
                                            esc_html_e( 'Activate Anant Sites', 'consult-element' );
                                        } else {
                                            esc_html_e( 'Import Demo', 'consult-element' );
                                        }
                                        ?>
                                        <i class="dashicons dashicons-arrow-right-alt"></i>
                                    </a>
                                    <!-- <a class="btn notice-action" href="https://www.youtube.com/watch?v=mf549otb_hI" target="_blank">
                                        <php esc_html_e( 'Video Tutorial', 'consult-element' );?>
                                        <span class="dashicons dashicons-video-alt3"></span>
                                    </a> -->
                                </div>
                            </div>
                            <div class="esh-el-content-img">
                                <?php 
                                $image_url = get_theme_file_uri( '/images/admin-image.jpg' );
                                // Check if the file exists
                                if ( file_exists( get_theme_file_path( '/images/admin-image.jpg' ) ) ) { ?>
                                    <img src="<?php echo esc_url( $image_url ); ?>" alt="Notice Image">
                                <?php } else { ?>
                                    <img src="<?php echo esc_url( CONSULT_ELEMENT_URI . 'images/admin-image.jpg' ); ?>" alt="Notice Image">
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            
        } 
    }
    

    public function consult_element_admin_assests() {
        wp_enqueue_script('consult-element-ins-plug', get_template_directory_uri() . '/js/init.js', array('jquery'), '', true);
        wp_localize_script('consult-element-ins-plug',
            'ins_plug_ajax_obj', 
                array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('nonce_check'),
                    'import_url' => admin_url('admin.php?page=consult-admin&tab=welcome')
                )
            );
        wp_enqueue_style( 'admin-notice-styles', get_template_directory_uri() . '/css/notice.css', array(), '1.0.0' );
        
    }
            
    public function install_plug_ajax() {
        // Verify nonce
        if ( ! isset( $_POST['check_plug_install_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['check_plug_install_nonce'] ) ) , 'nonce_check' ) ) {
            wp_send_json_error('Nonce verification failed');
        }
    
        // Check user capabilities
        if ( !current_user_can('edit_posts') ) {
            wp_send_json_error('Insufficient permissions');
        }
        /* Get All Plugins Installed In Wordpress */
        $all_wp_plugins = get_plugins();
        $installed_plugins = [];
        
        $plugin_status = $this->consult_element_get_required_plugin_status('ananta-sites', $all_wp_plugins);     
        if($plugin_status == 'not-installed'){
            $this->install_plugin(['name' => 'Anant Sites', 'slug' => 'ananta-sites']);
            $installed_plugins['installed'][] = 'ananta-sites';
            $myplugin = $this->get_plugin_install_path('ananta-sites');
            if($myplugin){
                $installed_plugins['activated'][] = !is_null(activate_plugin( $myplugin, '', false, false )) ?: 'ananta-sites';
            }
        } else if($plugin_status == 'inactive'){
            $myplugin = $this->get_plugin_install_path('ananta-sites');
            if($myplugin){
                $installed_plugins['activated'][] = !is_null(activate_plugin( $myplugin, '', false, false )) ?: 'ananta-sites';
            }
            
        } else if($plugin_status == 'active') {
            $installed_plugins['activated'][] = 'ananta-sites';
        }
    }
    function dismiss_notice_handler() {
        if ( isset( $_POST['type'] ) ) {
            // Pick up the notice "type" - passed via jQuery (the "data-notice" attribute on the notice)
            $type = sanitize_text_field( wp_unslash( $_POST['type'] ) );
            // Store it in the options table
            update_option( 'dismissed-' . $type, TRUE );
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
        foreach($all_plugins as $key => $wp_plugin) {
            $folder_arr = explode("/", $key);
            $folder = $folder_arr[0];
            if($folder == $plugin_slug) {
                return (string)$key;
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
         
     public function install_plugin( $plugin = array() ) {
 
         if ( ! isset( $plugin['slug'] ) || empty( $plugin['slug'] ) ) {
                 return esc_html__( 'Invalid plugin slug', 'consult-element' );
         }
 
         include_once ABSPATH . 'wp-admin/includes/plugin.php';
         include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
         include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
 
         
 
         $api = plugins_api(
                 'plugin_information',
                 array(
                     'slug'   => sanitize_key( wp_unslash( $plugin['slug'] ) ),
                     'fields' => array(
                         'sections' => false,
                     ),
                 )
         );
 
         if ( is_wp_error( $api ) ) {
                 $status['errorMessage'] = $api->get_error_message();
                 return $status;
         }
 
         $skin     = new WP_Ajax_Upgrader_Skin();
         $upgrader = new Plugin_Upgrader( $skin );
         $result   = $upgrader->install( $api->download_link );
 
         if ( is_wp_error( $result ) ) {
                 return $result->get_error_message();
         } elseif ( is_wp_error( $skin->result ) ) {
                 return $skin->result->get_error_message();
         } elseif ( $skin->get_errors()->has_errors() ) {
                 return $skin->get_error_messages();
         } elseif ( is_null( $result ) ) {
                 global $wp_filesystem;
 
                 // Pass through the error from WP_Filesystem if one was raised.
                 if ( $wp_filesystem instanceof WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->has_errors() ) {
                         return esc_html( $wp_filesystem->errors->get_error_message() );
                 }
 
                 return esc_html__( 'Unable to connect to the filesystem. Please confirm your credentials.', 'consult-element' );
         }
 
         /* translators: %s plugin name. */
         return sprintf( esc_html__( 'Successfully installed "%s" plugin!', 'consult-element' ), $api->name );
     }

    public function retrive_plugin_install_path($plugin_slug) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
        $all_plugins = get_plugins();
        foreach ($all_plugins as $key => $wp_plugin) {
            $folder_arr = explode("/", $key);
            $folder = $folder_arr[0];
            if ($folder == $plugin_slug) {
                return (string)$key;
                break;
            }
        }
        return false;
    }

    private function consult_element_get_required_plugin_status($plugin, $all_plugins) {
        $response = 'not-installed';
        foreach($all_plugins as $key => $wp_plugin) {
            $folder_arr = explode("/", $key);
            $folder = $folder_arr[0];
            if($folder == $plugin) {
                if(is_plugin_inactive( $key ) ) {
                    $response = 'inactive';
                } else {
                    $response = 'active';
                }
                return $response;
            }
        }
        return $response;
            
    }
}

$notice = new Notice();
