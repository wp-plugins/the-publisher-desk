<?php
/*
Plugin Name: The Publisher Desk
Plugin URI: http://code.publisherdesk.com/wordpress/plugins/the-publisher-desk
Description: Allows for easy integration for any Publisher Desk customer using Wordpress.
Version: 1.0.1
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


function publisher_desk_wp_head() {

  $output = '';

  if (get_option('publisher_desk_id') !== FALSE) {

    $id = get_option('publisher_desk_id');

    $output .= "<script>window.twoOhSixId = '$id';</script>\n";
    $output .= "<script>\n";
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

      $tags = get_the_tags($post->ID);

      if (sizeof($tags) > 0) {
        $tagsArray = array();
        foreach ($tags as $tag) {
          $tagsArray[] = '\'' . $tag->slug . '\'';
        }
        if (sizeof($tagsArray) > 0) {
          $output .= "twoOhSix.setTargeting('Tag', [" . implode($tagsArray, ', ') . "]);\n";
        }
      }

      $cats = get_the_category($post->ID);

      if (sizeof($cats) > 0) {
        $catsArray = array();
        foreach($cats as $cat) {
          $cat = get_category($cat);
          $catsArray[] = '\'' . $cat->slug . '\'';
        }
        if (sizeof($catsArray) > 0) {
          $output .= "twoOhSix.setTargeting('Category', [" . implode($catsArray, ', ') . "]);\n";
        }
      }

    } else {

      $output .= "twoOhSix.setTargeting('Page-Type', ['archive']);\n";
      $output .= "twoOhSix.setTargeting('Post-Type', ['page']);\n";

    }
    $output .= "});\n";
    $output .= "</script>\n";
    $output .= "<script src=\"//s.206ads.com/init.js\"></script>\n";
  }
  echo $output;
}

function publisher_desk_settings() {
  include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
}

function publisher_desk_admin_menu() {
  add_options_page('The Publisher Desk', 'The Publisher Desk', 1, 'publisher-desk.php', 'publisher_desk_settings');
}

function publisher_desk_the_content($content) {
  $contextual = get_option('publisher_desk_contextual');
  if (is_single() && $contextual !== FALSE && $contextual == 1) {
    $content .= '<div id="contextual-a"></div>';
  }
  return $content;
}

add_action('admin_menu',  'publisher_desk_admin_menu');
add_filter('the_content', 'publisher_desk_the_content' );
add_action('wp_head',     'publisher_desk_wp_head');
