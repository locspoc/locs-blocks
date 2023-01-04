<?php

if (!defined('ABSPATH')) {
	exit; // disable direct access.
}

/**
 * Cache Busting Version Number
 */

$borderBoxAssets = include 'build/border-box.asset.php'; // Build version number

class LocsBlocksBorderBox
{

	private static $Instance;
	private static $Name = 'locs-blocks/border-box';

	/**
	 * Register WP block type
	 */
	public static function registerBlock()
	{
		// die("border box");
		if (empty(self::$Instance)) {

			self::$Instance = new self();

			register_block_type(
				self::$Name,
				array(
					'render_callback' => array(self::$Instance, 'render'),
					'category' => 'locs_blocks',
				)
			);
		}
	}

	public function render($props)
	{

		if (!is_admin()) {

			// Add scripts and styles
			wp_enqueue_script('locs-blocks-border-box', plugin_dir_url(__FILE__) . 'build/border-box.js', array(), $boderBoxAssets['version'], true);
			wp_enqueue_style('locs-blocks-border-box', plugin_dir_url(__FILE__) . 'build/border-box.css', [], $boderBoxAssets['version']);
		}

		return Locs_Blocks::render('border-box', $props);
	}
}
