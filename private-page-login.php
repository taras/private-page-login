<?php
   /*
   Plugin Name: Private Page Login
   Plugin URI: https://github.com/wrktg/private-page-login
   Description: a plugin that redirects unauthorized users to login when visiting a private page
   Version: 1.0
   Author: Taras Mankovski
   Author URI: http://taras.cc
   License: GPL2
   */

// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

if( !class_exists( 'WRKTGPrivatePageLogin' ) ) {
	class WRKTGPrivatePageLogin {

        function WRKTGPrivatePageLogin() {

            add_action( 'template_redirect', array( $this, 'template_redirect') );

        }

        public function template_redirect() {

            if ( !is_user_logged_in() && !is_admin() ) {
                $posts = get_posts(array('post_status'=>array('private'), 'post_name'=>get_query_var('name')));
                if ( $posts ) $post = $posts[0];
                wp_redirect( wp_login_url( get_permalink($post->ID) ) );
                exit();
            }

        }

    }

}

new WRKTGPrivatePageLogin();