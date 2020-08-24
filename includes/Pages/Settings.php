<?php

/**
 * Manages and handles the settings page
 *
 * @package productive-laziness/content-audit-exporter
 */

namespace PLContentAuditExporter\Pages;

use PLContentAuditExporter\Base\BaseController;
use kasparsd\MiniSheets\XlsxBuilder;

class Settings extends BaseController
{
    /**
     * register our settings page
     */
    public function register()
    {
        add_action('admin_menu', array($this, 'create_menus'));
        add_action('admin_post_create_content_audit', array($this, 'create_content_audit'));
        add_action('admin_post_delete_content_audit', array($this, 'delete_content_audit'));
        add_action('admin_enqueue_scripts', array($this, 'admin_script_and_styles'));
    }

    /**
     * add a settings option to the menu
     */
    public function create_menus()
    {
        // add a "Content Audit Exporter" link to the Tools menu
        add_management_page('Content Audit Exporter', __('Content Audit', 'pl-content-audit-exporter'), 'manage_options', 'content-audit-exporter', array($this, 'render_settings_page'));
    }

    /**
     * renders the settings page
     */
    function render_settings_page()
    {
        require_once $this->plugin_path . 'templates/admin/settings.php';
    }

    /**
     * enqueue our stylesheet and scripts
     *
     * @param $hook
     */
    function admin_script_and_styles($hook)
    {
        // if we are in the settings page
        if ($hook == 'tools_page_content-audit-exporter') {
            wp_enqueue_style('admin_ca_stylesheet', $this->plugin_url . 'assets/admin/css/ca-admin.css');
        }
    }

    /**
     * create our content audit
     */
    function create_content_audit()
    {
        // check that user has proper security level
        if (!current_user_can('manage_options')) {
            wp_die('Not allowed');
        }

        // check if nonce field configuration form is present
        check_admin_referer('ca_create_content_audit_nonce');

        // get the wordpress file system
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        global $wp_filesystem;
        WP_Filesystem();

        // get our upload directory information
        $upload_dir = wp_upload_dir();
        $dir = trailingslashit($upload_dir['basedir']) . 'content-audits/';

        // check if our upload folder exists and it not make it
        if (!$wp_filesystem->is_dir($dir)) {
            $wp_filesystem->mkdir($dir);
        }

        // create our xlsx file
        $zipper = new \ZipArchive;
        $filename = $dir . 'content-audit-' . date('Y-m-j-h-i-A') . '.xlsx';
        $zipper->open($filename, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        // add our content to the file
        $builder = new XlsxBuilder($zipper);

        // add our headers
        $builder->add_rows(
            [
                [
                    'ID',
                    'Post Type',
                    'status',
                    'Title',
                    'Slug',
                    'Author',
                    'Date Published',
                    'Date Modified',
                    'Permalink'
                ],
            ]
        );

        // if post type options are available
        if (isset($_POST['post_type_option']) && is_array(($_POST['post_type_option']))) {
            // for each post type
            foreach ((array)$_POST['post_type_option'] as $post_type) {
                // get our post type posts
                $query = new \WP_Query(
                    array(
                        'post_type' => sanitize_text_field($post_type),
                        'posts_per_page' => -1
                    )
                );
                $posts = $query->posts;

                // for each post
                foreach ($posts as $post) {
                    // out put the information
                    $builder->add_rows(
                        [
                            [
                                $post->ID,
                                $post->post_type,
                                $post->post_status,
                                $post->post_title,
                                $post->post_name,
                                get_the_author_meta('nickname', $post->post_author),
                                $post->post_date,
                                $post->post_modified,
                                get_the_permalink($post->ID)
                            ],
                        ]
                    );
                }
            }

            // build our file
            $builder->build();

            // clear the xlsx file
            $zipper->close();

            // redirect the page to the settings page
            wp_redirect(add_query_arg(array('page' => 'content-audit-exporter', 'message' => '1'), admin_url('tools.php')));
        } else {
            // redirect the page to the settings page
            wp_redirect(add_query_arg(array('page' => 'content-audit-exporter', 'message' => '3'), admin_url('tools.php')));
        }

        exit;
    }

    /**
     * delete our content audit
     */
    function delete_content_audit()
    {
        // check that user has proper security level
        if (!current_user_can('manage_options')) {
            wp_die('Not allowed');
        }

        // check if nonce field configuration form is present
        check_admin_referer('ca_delete_content_audit_nonce');

        // if a file path exists
        if (isset($_POST['file_path'])) {
            wp_delete_file(sanitize_text_field($_POST['file_path']));
        }

        // redirect the page to the configuration form
        wp_redirect(add_query_arg(array('page' => 'content-audit-exporter', 'message' => '2'), admin_url('tools.php')));
        exit;
    }
}