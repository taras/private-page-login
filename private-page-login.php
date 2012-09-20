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

            add_action( 'wp', array( $this, 'wp' ));

        }

        public function wp() {

            global $wp_query;

            $post = $wp_query->get_queried_object();

            if ( $post && $post->post_status == 'private' && !is_user_logged_in() ) {

                echo wp_login_url( get_permalink($post->ID));

                wp_redirect( wp_login_url( get_permalink($post->ID)) );
                exit();

            }

        }

    }

}

new WRKTGPrivatePageLogin();