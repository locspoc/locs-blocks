<?php

if (!defined('ABSPATH')) {
	exit; // disable direct access.
}

/**
 * Cache Busting Version Number
 */

$multipleChoiceAssets = include 'build/multiple-choice.asset.php'; // Build version number

class LocsBlocksMultipleChoice
{

	private static $Instance;
	private static $Name = 'locs-blocks/multiple-choice';

	/**
	 * Register WP block type
	 */
	public static function registerBlock()
	{

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
			wp_enqueue_script('locs-blocks-multiple-choice', Locs_Blocks::pluginUrl() . 'build/multiple-choice.js', array('wp-blocks', 'wp-element', 'wp-editor'), $multipleChoiceAssets['version'], true);
			wp_enqueue_style('locs-blocks-multiple-choice', Locs_Blocks::pluginUrl() . 'build/multiple-choice.css', [], $multipleChoiceAssets['version']);
		}

		return Locs_Blocks::render('multiple-choice', $props);
	}
}
