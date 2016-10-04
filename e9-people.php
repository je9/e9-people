<?php

/*
 * Plugin Name: e9-people
 * GitHub Plugin URI: je9/e9-people
 * Description: E9 Person CPT and team page for WordPress.
 * Version: 0.1.0
 * Author: justin@e9.nz
 * Author URI: http://e9.nz
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * RequiresWP: 4.6
 * RequiresPHP: 5.6.23
*/


// create person custom post type
// add meta fields
// save meta fields
require('inc/e9-person-cpt.php');


// use a shortcode to display people html
include('inc/e9-people-template.php');
function e9_people_template_shortcode() {
  return e9_people_template();
}
add_shortcode(
  'e9_people', 
  'e9_people_template_shortcode');


// add team page styles and scripts
add_action('wp_enqueue_scripts', 'e9_team_css');
function e9_team_css() {
  wp_enqueue_style(
    'e9-team-css',
    plugins_url() . '/e9-people/assets/e9-team.css' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_e9_scripts' );
function enqueue_e9_scripts() {
  wp_register_script( 
    'e9-team-js', 
    plugins_url() . '/e9-people/assets/e9-team.js', 
    [], 
    '1', 
    false );
  wp_enqueue_script( 'e9-team-js' );
}