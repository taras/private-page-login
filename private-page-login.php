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


            // hook to init because is_user_logged_in is not available before init
            add_action( 'init', array( $this, 'init') );

        }

        /**
         * Make sure that user is not logged in and not in wp-admin
         */
        public function init() {
            if ( !is_user_logged_in() && !is_admin() ) {
                add_action( 'template_redirect', array( $this, 'template_redirect') );
            }
        }

        /**
         * Once query_vars were parsed, check if current post/page is private using its post_name
         */
        public function template_redirect() {

            # attach to post_results to prevent private post from being excluded from results
            add_filter( 'posts_results', array( $this, 'posts_results') );

            if ( $name = get_query_var('name') ) {

                # get all available post types to include them in search
                $post_types = get_post_types(array('public' => true, 'exclude_from_search' => false));

                # find private post with current post
                $query = new WP_Query(array('post_status'=>array('private'), 'name'=>$name, 'post_type'=>$post_types));
            }

            # remove filter so it doesn't execute again after this
            remove_filter( 'posts_results', array( $this, 'posts_results'));

        }

        /**
         * After posts_results, private posts get excluded, so we must do our dirty work here
         * @param $posts
         * @return mixed
         */
        public function posts_results($posts) {

            # make sure that only 1 post was found and its private
            if ( count($posts) == 1 && $posts[0]->post_status == "private" ) {

                # redirect to login page
                wp_redirect( wp_login_url( get_permalink($posts[0]->ID) ) );
                exit();
            }

            return $posts;

        }

    }

}

new WRKTGPrivatePageLogin();