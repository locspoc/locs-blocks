<?php

/**
 * Plugin Name: Loc's Blocks
 * Description: Custom blocks developed by Loc Tran
 * Version: 0.0
 * Author: Loc Tran
 * Author URI: https://loctran.com.au/
 * 
 * Dependencies:
 * npm install @wordpress/scripts
 * npm install @wordpress/components --save
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Cache Busting Version Number
 */

$locsBlocksAssets = include 'build/locs-blocks.asset.php'; // Build version number

/**
 * Require Once
 */
require_once __DIR__ . '/Blocks/BorderBox.php';
require_once __DIR__ . '/Blocks/Demo.php';
require_once __DIR__ . '/Blocks/FeaturedPage.php';
require_once __DIR__ . '/Blocks/MultipleChoice.php';
require_once __DIR__ . '/Blocks/PageTree.php';

/**
 * Loc's Blocks
 */

if (!class_exists('Locs_Blocks')) :

    class Locs_Blocks
    {

        /**
         * Initialize the plugin
         */
        public static function init()
        { // static means don't have to create a new instance to use it, can call it directly

            $plugin = new self();
        }

        /**
         * PHP class constructor
         */
        function __construct()
        {

            LocsBlocksBorderBox::registerBlock();
            LocsBlocksDemo::registerBlock();
            LocsBlocksFeaturedPage::registerBlock();
            LocsBlocksMultipleChoice::registerBlock();
            LocsBlocksPageTree::registerBlock();

            // die("construct");

            add_action('init', array($this, 'addAssets'));
            add_action('block_categories', array($this, 'locs_blocks_categories'));
        }

        /**
         * Plugin Directory URL
         */
        public static function pluginUrl()
        {

            return plugin_dir_url(__FILE__);
        }

        /**
         * Add Script & Style Assets
         */
        function addAssets()
        {

            if (is_admin()) {

                wp_enqueue_style('locs-blocks-css', plugin_dir_url(__FILE__) . 'build/locs-blocks.css', [], $locsBlocksAssets['version']);
                wp_enqueue_script('locs-blocks-js', plugin_dir_url(__FILE__) . 'build/locs-blocks.js', array('wp-blocks', 'wp-element', 'wp-editor'), $locsBlocksAssets['version'], false);
            }

            wp_enqueue_style('bootstrap-css', plugin_dir_url(__FILE__) . 'vendor/bootstrap/css/bootstrap.min.css');
            wp_enqueue_script('bootstrap-js', plugin_dir_url(__FILE__) . 'vendor/bootstrap/js/bootstrap.min.js');
        }

        /**
         * Register Custom Block Category
         */
        public function locs_blocks_categories($categories)
        {

            return array_merge(
                $categories,
                [
                    [
                        'slug'  => 'locs_blocks',
                        'title' => __("Loc's Blocks", 'locs_blocks'),
                    ],
                ]
            );
        }

        /**
         * Render front end from template
         */
        public static function render($name, $props)
        {

            ob_start();
            include 'Blocks/tmpl/' . $name . '.php';
            return ob_get_clean();
        }
    }

    add_action('plugins_loaded', array('Locs_Blocks', 'init'), 10);

endif;
