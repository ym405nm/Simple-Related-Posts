<?php 
/*
Plugin Name: WP Simple Related Posts
Plugin URI: http://www.kakunin-pl.us/
Description: Display Related Posts. Very Simple.
Author: horike takahiro
Version: 1.2.2
Author URI: http://www.kakunin-pl.us/


Copyright 2014 horike takahiro (email : horike37@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'SRP_DOMAIN' ) )
	define( 'SRP_DOMAIN', 'simple-related-posts' );
	
if ( ! defined( 'SRP_PLUGIN_URL' ) )
	define( 'SRP_PLUGIN_URL', plugins_url() . '/' . dirname( plugin_basename( __FILE__ ) ));

if ( ! defined( 'SIRP_PLUGIN_DIR' ) )
	define( 'SIRP_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ));

require_once(SIRP_PLUGIN_DIR . '/modules/base.php');

$srp_dirs = array(
	SIRP_PLUGIN_DIR . '/admin/',
	SIRP_PLUGIN_DIR . '/modules/'
);
foreach ( $srp_dirs as $dir ) {
	opendir($dir);
	while(($ent = readdir()) !== false) {
	    if(!is_dir($ent) && strtolower(substr($ent,-4)) == ".php") {
	        require_once($dir.$ent);
		}
	}
	closedir();
}

add_action( 'admin_init', 'simple_related_posts_install' );
function simple_related_posts_install() {
	if ( !get_option('simple_related_posts_installed') ) {
		$args = array(
			'target'       => 'Simple_Related_Posts_Tag',
			'display_num'  => 5,
			'post_content' => 1,
			'post_thumbnail'   => 1,
			'original_css' => 1,
			'rss_post_content' => ''
		);
		update_option('srp_options', $args);
		update_option('simple_related_posts_installed', 1);
	}
}

class Simple_Related_Posts {
	private $related = '';
	
	public function __construct() {
		$option = get_option('srp_options');
		if ( class_exists($option['target']) ) {
			$this->related = new $option['target'];
		}
	}
	
	public function get_data_original($num = '') {
		return $this->related->get_data_original($num);
	} 
	
	public function get_data($num = '') {
		return $this->related->get_data($num);
	} 
}

$simple_related_posts = new Simple_Related_Posts();