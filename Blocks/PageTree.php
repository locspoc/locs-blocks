<?php

if (!defined('ABSPATH')) {
    exit; // disable direct access.
}

/**
 * Cache Busting Version Number
 */

$pageTreeAssets = include 'build/page-tree.asset.php'; // Build version number

class LocsBlocksPageTree
{

    private static $Instance;
    private static $Name = 'locs-blocks/page-tree';

    /**
     * PHP class constructor
     */
    function __construct()
    {

        add_action('rest_api_init', [$this, 'pageTreeHTML']);
        add_action('add_meta_boxes', [$this, 'register_meta_box']);
        add_action('save_post', [$this, 'exclude_page_sitemap_save']);
    }

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

    /**
     * Custom Page Attribute Meta Box
     */

    public function register_meta_box()
    {
        $screens = ['post', 'page'];
        foreach ($screens as $screen) {
            add_meta_box(
                'locs_sitemap',             // Unique ID
                'Sitemap',                          // Box title
                [$this, 'exclude_page_sitemap_mb'],    // Content callback, must be of type callable
                $screen                             // Post type
            );
        }
    }

    public function exclude_page_sitemap_mb($post)
    {
        $value = get_post_meta($post->ID, 'locs_sitemap', true);
        $checked = (!empty($value)) ? 'checked' : '';
        // $checked = 'checked';
?>
        <div>
            <input type="checkbox" id="exclude_page_sitemap" name="exclude_page_sitemap" <?php echo $checked; ?>>
            <label for="exclude_page_sitemap">Exclude page from sitemap?</label>
        </div>
<?php
    }

    public function exclude_page_sitemap_save($post_id)
    {
        // die('<pre>' . print_r($_POST,true) . '</pre>' );
        // $file = __DIR__ . '/debug_log.txt';
        // Write the contents to the file, 
        // using the FILE_APPEND flag to append the content to the end of the file
        // and the LOCK_EX flag to prevent anyone else writing to the file at the same time
        // file_put_contents($file, print_r($_POST,true));
        if (array_key_exists('exclude_page_sitemap', $_POST)) {
            update_post_meta(
                $post_id,
                'locs_sitemap',
                // $_POST['exclude_page_sitemap']
                true
            );
        } else {
            delete_post_meta(
                $post_id,
                'locs_sitemap',
                true
            );
        }
    }

    /**
     * Page Tree Preview
     */
    function pageTreeHTML()
    {

        // die("pageTreeHTML");

        register_rest_route('pageTree/v1', 'getHTML', array(
            'methods' => WP_REST_SERVER::READABLE,
            'callback' => [$this, 'getPageTreeHTML']
        ));

        // die("pageTreeHTML");

    }

    function getPageTreeHTML($props)
    {

        $props['pages'] = $this->fetchChildPages($props['pageTreeId'], true);

        return Locs_Blocks::render('page-tree', $props);
    }

    private function fetchExcludePages()
    {

        global $wpdb;

        $results = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}postmeta` WHERE meta_key = 'locs_sitemap'", ARRAY_A);

        // die('<pre>' . print_r($results,true) .'</pre>');

        return $results;
    }

    private function fetchChildPages($parentID, $excluded = false)
    {

        $pagesToExclude = [];

        if ($excluded) {

            $results = $this->fetchExcludePages();

            foreach ($results as $result) {
                array_push($pagesToExclude, $result['post_id']);
            }
        }

        // die('<pre>' . print_r($pagesToExclude,true) .'</pre>');

        $args = array(
            'child_of' => $parentID,
            'parent ' => $post->ID,
            'hierarchical' => 0,
            'sort_column' => 'menu_order',
            'sort_order' => 'asc',
            'exclude' => $pagesToExclude
        );

        $pages = get_pages($args);

        // die('<pre>' . print_r($pages,true) . '</pre>');

        return $pages;
    }

    /**
     * Render template frontend
     */
    public function render($props)
    {

        if ($props['pageTreeId']) {

            if (!is_admin()) {

                // Add scripts and styles
                wp_enqueue_script('locks-blocks-page-tree', Locks_Blocks::pluginUrl() . 'build/page-tree.js', array('wp-blocks', 'wp-element', 'wp-editor'), $pageTreeAssets['version'], true);
                wp_enqueue_style('locks-blocks-page-tree', Locks_Blocks::pluginUrl() . 'build/page-tree.css', [], $pageTreeAssets['version']);

                $props['pages'] = $this->fetchChildPages($props['pageTreeId'], true);

                return Locks_Blocks::render('page-tree', $props);
            }
        } else {

            return NULL;
        }
    }
}
