<?php

require_once('includes/class-tgm-plugin-activation.php');


add_action('init', function() {
    /* Remove emoji support */
    remove_action('wp_head', 'print_emoji_detection_script', 7); 
    remove_action('admin_print_scripts', 'print_emoji_detection_script'); 
    remove_action('wp_print_styles', 'print_emoji_styles'); 
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji'); 
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('tiny_mce_plugins', function($plugins) {
        if (is_array($plugins)) {
            return array_diff($plugins, array('wpemoji'));
        }
        return array();
    });
    add_filter('wp_resource_hints', function($urls, $relationType) {
        if ('dns-prefetch' === $relationType) {
            $svgUrl = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');
            $urls = array_diff($urls, array($svgUrl));
        }
        return $urls;
    }, 10, 2);

    /* Remove other junk */
    remove_action('welcome_panel', 'wp_welcome_panel');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_generator');
    add_filter('the_generator', '__return_false');


    add_theme_support('html5');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-background');
    add_theme_support('custom-logo');
    register_nav_menu('header-menu', __( 'Primary Header Menu'));
    register_nav_menu('footer-menu', __( 'Primary Footer Menu'));

});


add_action('admin_init', function() {
    $image_set = get_option('image_default_link_type');
     
    if ($image_set !== 'none') {
        update_option('image_default_link_type', 'none');
    }
});


add_filter('post_thumbnail_html', function($htmlCode, $postId, $imageId) {
    if (!is_singular()) { 
        $htmlCode = '<a href="' . get_permalink($postId) . '" title="' . esc_attr(get_the_title()) . '">' . $htmlCode . '</a>';
    }
    $htmlCode = preg_replace('/(width|height)=\"\d*\"\s/', '', $htmlCode);
    return $htmlCode;
}, 10, 3);

add_filter('image_send_to_editor',function($htmlCode) {
    $htmlCode = preg_replace('/(width|height)=\"\d*\"\s/', '', $htmlCode);
    return $htmlCode;
});

add_action('wp_enqueue_scripts', function() {
    wp_deregister_script('wp-embed');
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', 'http://code.jquery.com/jquery-3.3.1.min.js', array(), '3.3.1', true);
    wp_enqueue_script('modernizr', 'https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js', array(), '2.8.3', true);
    
    wp_enqueue_style('theme', get_template_directory_uri() . '/css/style.css');
    wp_enqueue_script('owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js', array('jquery'), '2.2.1', false);
    wp_enqueue_style('owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.carousel.min.css');
});
add_action('login_enqueue_scripts', function() {
    wp_enqueue_style('login', get_template_directory_uri() . '/login.css');
});

$bodyClass = function($classes) {
    global $post;
    global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

    
	foreach ((get_the_category($post->ID)) as $category) {
        $classes[] = 'category-id-' . $category->cat_ID;
        $classes[] = 'category-' . $category->slug;
    }

    $currentPage = sanitize_post($GLOBALS['wp_the_query']->get_queried_object());
    if ($currentPage) {
        $classes[] = 'slug-' . $currentPage->post_name;
        if ($post->post_parent) { 
            $parentPage = get_post($post->post_parent);
            $classes[] = 'parent-' . $parentPage->post_name;
        }
    }
    if (!empty(get_page_template_slug())) { $classes[] = 'template-' . str_replace('.php', '', basename(get_page_template_slug())); }

    if ($is_lynx) $classes[] = 'browser-lynx';
    elseif ($is_gecko) $classes[] = 'browser-gecko';
    elseif ($is_opera) $classes[] = 'browser-opera';
    elseif ($is_NS4) $classes[] = 'browser-ns4';
    elseif ($is_safari) $classes[] = 'browser-safari';
    elseif ($is_chrome) $classes[] = 'browser-chrome';
    elseif ($is_IE) { 
        $classes[] = 'browser-ie';
        if (preg_match('/MSIE ([0-9]+)([a-zA-Z0-9.]+)/', $_SERVER['HTTP_USER_AGENT'], $browserVersion)) $classes[] = 'ie' . $browserVersion[1];
    }
    else $classes[] = 'browser-unknown';
    if ($is_iphone) $classes[] = 'iphone';

    if (stristr($_SERVER['HTTP_USER_AGENT'], 'mac')) $classes[] = 'osx';
    elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'linux')) $classes[] = 'linux';
    elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'windows')) $classes[] = 'windows';

    return $classes;
};
add_filter('body_class', $bodyClass);

add_action('after_theme_setup', function() {

});

/* Display debug stats if logged in */
add_action('wp_footer', function() {
    if (is_user_logged_in() && current_user_can('manage_options')) {
        echo sprintf('%d queries in %.3f seconds, using %.2fMB memory', get_num_queries(), timer_stop(0, 3), memory_get_peak_usage() / 1024 / 1024);
    }
}, 20);


/* Prevent single view of certain post type entries */
add_action('template_redirect', function() {
    $postTypes = array();
	if (!empty($postTypes) && is_singular($postTypes)) {
		wp_redirect(get_post_type_archive_link(get_post_type()), 301);
		exit;
    }
});

/* Prevent username leak with /?author=1 */
add_action('template_redirect', function() {
    $fAuthorSet = get_query_var('author', '');
    if (!empty($fAuthorSet) && !is_admin()) {
        wp_redirect(home_url(), 301);
		exit;
    }
});

/** Add default settings page **/
add_filter('mb_settings_pages', function($settingsPages) {
    $settingsPages[] = array(
        'id' => 'site-options',
        'option_name' => 'site_options',
        'menu_title' => __('Site Options', 'textdomain'),
        //'parent' => 'index.php',
        'tabs' => array(
            'general' => __('General Settings', 'textdomain'),
            'design'  => __('Design Customization', 'textdomain'),
            'faq'     => __('FAQ & Help', 'textdomain'),
        ),
    );
    return $settingsPages;
});

add_filter('rwmb_meta_boxes', function($meta_boxes) {

    $meta_boxes[] = array(
      'id' => 'misc_settings',
      'title' => __('Misc Settings', 'textdomain'),
      'settings_pages' => 'site-options',
      'tab' => 'general',
      'context' => 'normal',
      'priority' => 'high',
      'autosave' => false,
      'fields' => array(
        array(
          'id' => 'google_analytics_id',
          'type' => 'text',
          'name' => __('Google Analytics ID', 'textdomain'),
          'desc' => __('GA ID (Begins with UA-)', 'textdomain'),
          'placeholder' => __('UA-XXXXX-Y', 'textdomain'),
        ),
      ),
    );
    return $meta_boxes;
});

 
add_action('wp_dashboard_setup', function() {
    global $wp_meta_boxes;
 
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);


    wp_add_dashboard_widget('custom_help_widget', 'Theme Support', function() {
        echo '<p>' . __('Welcome to your custom theme! Need help? Contact the developer <a href="mailto:contact@novastream.ca">here</a>.', 'textdomain') . '</p>';
    });
 
});



/** Setup required plugins **/
add_action('tgmpa_register', function() {
    $plugins = array(
        array(
            'name'               => __('Meta Box', 'textdomain'),
            'slug'               => 'meta-box',
            'required'           => true,
            'force_activation'   => true,
            'force_deactivation' => false,
        ),
        // Premium extensions
        array(
            'name'               => __('Meta Box Tabs', 'textdomain'),
            'slug'               => 'meta-box-tabs',
            'source'             => get_template_directory() . '/includes/plugins/meta-box-tabs.zip',
            'required'           => true,
            'force_activation'   => false,
            'force_deactivation' => false,
        ),
        array(
            'name'               => __('Meta Box Settings Page', 'textdomain'),
            'slug'               => 'meta-box-settings-page',
            'source'             => get_template_directory() . '/includes/plugins/mb-settings-page.zip',
            'required'           => true,
            'force_activation'   => false,
            'force_deactivation' => false,
        ),
        // You can add more plugins here if you want
    );
    $config  = array(
        'domain' => 'textdomain',
        'default_path' => '',
        'menu' => 'install-required-plugins',
        'has_notices' => true,
        'is_automatic' => false,
        'message' => '',
        'strings' => array(
            'page_title' => __( 'Install Required Plugins', 'textdomain' ),
            'menu_title' => __( 'Install Plugins', 'textdomain' ),
            'installing' => __( 'Installing Plugin: %s', 'textdomain' ),
            'oops' => __( 'Something went wrong with the plugin API.', 'textdomain' ),
            'notice_can_install_required' => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ),
            'notice_can_install_recommended' => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ),
            'notice_cannot_install' => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ),
            'notice_can_activate_required' => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ),
            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ),
            'notice_cannot_activate' => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ),
            'notice_ask_to_update' => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ),
            'notice_cannot_update' => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ),
            'install_link' => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
            'activate_link' => _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
            'return' => __( 'Return to Required Plugins Installer', 'textdomain' ),
            'plugin_activated' => __( 'Plugin activated successfully.', 'textdomain' ),
            'complete' => __( 'All plugins installed and activated successfully. %s', 'textdomain' ),
            'nag_type' => 'updated',
        )
    );
    tgmpa($plugins, $config);
});
