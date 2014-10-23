<?php
/*
Plugin Name: The Publisher Desk
Plugin URI: http://wordpress.org/plugins/the-publisher-desk/
Description: Allows for easy integration for any Publisher Desk customer using Wordpress.
Version: 1.0.12
Author: The Publisher Desk
Author URI: http://www.publisherdesk.com
License: GPL2
*/
/*
Copyright 2014 The Publisher Desk  (email : webmaster@publisherdesk.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( ! defined( 'ABSPATH' ) ) exit;

define ( 'PUBLISHER_DESK_VERSION', '1.0.12' );

/**
 * Creates the Publisher Desk admin menu link in the sidebar
 */
function publisher_desk_admin_menu() {
  add_menu_page( 'The Publisher Desk', 'The Publisher Desk', 'manage_options', __FILE__, 'publisher_desk_settings', plugins_url( 'files/images/favicon.png', __FILE__ ) );
}

/**
 * Creates the admin page using an external settings file
 */
function publisher_desk_settings() {
  include( sprintf( "%s/templates/settings.php", dirname( __FILE__ ) ) );
}

/**
 * Appends the Publisher Desk JS code to the <head> of the page
 */
function publisher_desk_wp_head() {

  $output = '';

  if ( get_option( 'publisher_desk_id' ) !== FALSE && get_option( 'publisher_desk_id' ) !== '' ) {

    $attr = ' ';
    $attrVal = trim( get_option( 'publisher_desk_script_attributes' ) );

    // Create HTML attributes from $attr value
    if ( $attrVal != '' ) {
      $attrArray = explode( '&', $attrVal );
      foreach ( $attrArray as &$item ) {
        if ( strpos( $item, '=' ) !== false ) {
          $item = str_replace( '=', '="', $item ) . '"';
        }
      }
      $attr = ' ' . implode ( ' ', $attrArray ) . ' ';
    }

    $output .= "\n<!-- The Publisher Desk -->\n";

    if ( get_option( 'publisher_desk_bidder_id' ) !== FALSE && get_option( 'publisher_desk_bidder_id' ) !== '' ) {
      $bidderId = get_option( 'publisher_desk_bidder_id' );
      $output .= "<script" . $attr . "src=\"//www.googletagservices.com/tag/js/gpt.js\"></script>\n";
      $output .= "<script" . $attr . "src=\"//ox-d.publisherdesk.servedbyopenx.com/w/1.0/jstag?nc=$bidderId\"></script>\n";
    }

    $id = get_option( 'publisher_desk_id' );

    $output .= "<script" . rtrim( $attr ) . ">\n";
    $output .= "window.twoOhSixId = '$id';\n";
    $output .= "window.twoOhSixVersion = '" . PUBLISHER_DESK_VERSION . "';\n";
    $output .= "window.twoOhSixCmd = window.twoOhSixCmd || [];\n";
    $output .= "window.twoOhSixCmd.push(function() {\n";

    if ( is_page() || is_single() ) {
      $output .= "twoOhSix.setTargeting('PostID', ['" . get_the_ID() . "']);\n";
    }

    if ( is_front_page() || is_home() ) {

      $output .= "twoOhSix.setTargeting('Page-Type', ['front-page']);\n";
      $output .= "twoOhSix.setTargeting('Post-Type', ['page']);\n";

    } elseif ( is_single() ) {

      $output .= "twoOhSix.setTargeting('Page-Type', ['single']);\n";
      $output .= "twoOhSix.setTargeting('Post-Type', ['post']);\n";

      $post = $wp_query->post;

      $tags = get_the_tags( $post->ID );
      if ( is_array( $tags ) && sizeof( $tags ) > 0 ) {
        $tagsArray = array();
        foreach ( $tags as $tag ) {
          $tagsArray[] = '\'' . $tag->slug . '\'';
        }
        if ( sizeof( $tagsArray ) > 0 ) {
          $output .= "twoOhSix.setTargeting('Tag', [" . implode( $tagsArray, ', ' ) . "]);\n";
        }
      }

      $cats = get_the_category( $post->ID );

      if ( is_array( $cats ) && sizeof( $cats ) > 0 ) {
        $catsArray = array();
        foreach( $cats as $cat ) {
          $cat = get_category( $cat );
          $catsArray[] = '\'' . $cat->slug . '\'';
        }
        if ( sizeof( $catsArray ) > 0 ) {
          $output .= "twoOhSix.setTargeting('Category', [" . implode( $catsArray, ', ' ) . "]);\n";
        }
      }

    } else {

      $output .= "twoOhSix.setTargeting('Page-Type', ['archive']);\n";
      $output .= "twoOhSix.setTargeting('Post-Type', ['page']);\n";

    }
    $output .= "});\n";
    $output .= "</script>\n";
    $output .= "<script" . $attr . "src=\"//s.206ads.com/init.js\"></script>\n";
    $output .= "<!-- / The Publisher Desk -->\n";
  }
  echo $output;
}

/**
 * Appends a contextual div to the end of the content item if selected via the admin
 */
function publisher_desk_the_content( $content ) {

  $contextual = get_option( 'publisher_desk_contextual' );

  if ( is_single() && $contextual !== FALSE && $contextual == 1 ) {
    $content .= '<div id="contextual-a"></div>';
  }

  return $content;
}

/**
 * Filters requests for framebuster URLs if selected by the user via the admin
 */
function publisher_desk_init() {

  $framebusters = get_option( 'publisher_desk_framebusters' );

  if ( $framebusters == 1 ) {

    $paths = array(
      '/adcom/aceFIF.html',
      '/doubleclick/DARTIframe.html',
      '/eyeblaster/addineyeV2.html',
      '/jivox/jivoxIBuster.html',
      '/pointroll/PointRollAds.htm',
      '/rubicon/rp-smartfile.html',
      '/saymedia/iframebuster.html',
      '/undertone/UT_iframe_buster.html',
    );

    $request = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
    $index = array_search( $request, $paths );

    if ( false === $index )
      return;

    $file = plugin_dir_path( __FILE__ ) . 'files/framebusters' . $paths[$index];

    if ( ! file_exists( $file ) )
      return;

    header( 'Content-type: text/html' );

    readfile( $file );

    exit;

  }

}

/**
 * Wordpress action hooks
 */
add_action( 'admin_menu', 'publisher_desk_admin_menu' );
add_action( 'init', 'publisher_desk_init' );
add_action( 'wp_head', 'publisher_desk_wp_head' );

/** 
 * Wordpress filter hooks
 */
add_filter( 'the_content', 'publisher_desk_the_content' );
