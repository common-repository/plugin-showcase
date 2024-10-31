<?php
/*
Plugin Name: Plugin Showcase
Plugin URI: http://wordpress.org/extend/plugins/plugin-showcase/ 
Description: A plugin for Wordpress plugin developers to showcase their work.
Author: Brad Touesnard
Version: 0.1.1
Author URI: http://bradt.ca/
*/

// Copyright (c) 2008 Brad Touesnard. All rights reserved.
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// **********************************************************************
//
// Borrowed a bunch of code from the WP-DB-Backup plugin
// which in turn borrowed from the phpMyAdmin project.
// Thanks to both for GPL.


// Define the directory seperator if it isn't already
if (!defined('DS')) {
    if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
        define('DS', '\\');
    }
    else {
        define('DS', '/');
    }
}

// If the plugin showcase directory is not defined,
// use the 'siteurl'/'plugin-showcase' directory by default
if (!defined('PLUGIN_SHOWCASE_DIR')) {
	$plugins_dir = realpath(dirname(__FILE__) . DS . '..' . DS . '..' . DS . '..');

	if (get_bloginfo('siteurl') != get_bloginfo('wpurl')) {
		$wp_dir = str_replace(get_bloginfo('siteurl'), '', get_bloginfo('wpurl'));
		$wp_dir = str_replace('/', DS, $wp_dir);
		$plugins_dir = str_replace($wp_dir, '', $plugins_dir);
	}
	
	define('PLUGIN_SHOWCASE_DIR', $plugins_dir . DS . 'plugin-showcase');
}

if (!is_admin()) {
	require_once(dirname(__FILE__) . '/parse-readme/parse-readme.php');
	require_once(ABSPATH . 'wp-admin/includes/plugin.php');
	
	
	/* Retrieve information for all the plugins in the provided directory
	 * If not directory is provided, the default is bloginfo('siteurl')
	 * 
	 * @param string $plugins_dir optional The directory containing the plugins
	 * @return array associative array with the plugin directory names as the keys
	*/
	function ps_get_plugins($plugins_dir = '') {
		if (!$plugins_dir)
			$plugins_dir = PLUGIN_SHOWCASE_DIR;
		
		$plugins = array();

		$readme_files = glob($plugins_dir . DS . '*' . DS . 'readme.txt');

		if (!$plugins_dir || !$readme_files )
			return $plugins;
	
		foreach ($readme_files as $file) {
			$slug = dirname($file);
			$slug = substr($slug, strrpos($slug, DS) + 1);

			$plugin_data = ps_get_plugin($slug, $plugins_dir);

			if (!$plugin_data)
				continue;

			$plugins[$slug] = $plugin_data;
		}
	
		return $plugins;
	}
	
	/* Retrieve meta data contained in the readme.txt and the PHP file for the given plugin
	 * If not directory is provided, the default is bloginfo('siteurl')
	 *
	 * @param string $slug The name of the plugin directory
	 * @param string $plugins_dir optional The directory containing the plugins
	 * @return array An array containing the plugin meta data retrieved
	*/
	function ps_get_plugin($slug, $plugins_dir = '') {
		if (!$plugins_dir)
			$plugins_dir = PLUGIN_SHOWCASE_DIR;

		$cache = wp_cache_get($slug, 'plugin-showcase');
		if ($cache !== false) {
			return $cache;
		}

		$dir = $plugins_dir . DS . $slug;
		$readme = $dir . DS . 'readme.txt';
		
		if (!is_readable($readme))
			return false;

		$parser = new Automattic_Readme();
		$readme_data = $parser->parse_readme($readme);

		if (!$readme_data)
			return false;

		// Get the screenshot files names
		if (isset($readme_data['screenshots']) && is_array($readme_data['screenshots'])) {
			foreach ($readme_data['screenshots'] as $i => $screenshot) {
				$num = $i + 1;
				$images = glob($dir . DS . "screenshot-$num.*");
				if ($images) {
					$readme_data['screenshots'][$i] = array(
						'image' => basename($images[0]),
						'caption' => $screenshot
					);
				}
			}
		}
		
		$readme_data['slug'] = $slug;
		
		$php_files = glob($dir . DS . '*.php');
		
		// There could be several plugins contained for a single readme.txt
		// Here we're finding the primary plugin file and retrieving its header data
		$plugin_data = array();
		foreach ($php_files as $file) {
			$plugin_data = get_plugin_data($file, false, false);
			$plugin_data = array_change_key_case($plugin_data);
	
			if (empty($plugin_data['name']))
				continue;
			
			if (strtolower(trim($plugin_data['name'])) == strtolower(trim($readme_data['name'])))
				break;
		}

		$plugin_data = array_merge($plugin_data, $readme_data);

		wp_cache_set($slug, $plugin_data, 'plugin-showcase', 60*60*4);
		
		return $plugin_data;
	}
}

?>
