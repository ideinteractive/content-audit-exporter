<?php
/*
Plugin Name: Content Audit Exporter
Plugin URI: https://www.ideinteractive.com/products/content-audit-exporter/
Description: Make content auditing easy by exporting your post, pages, and custom post types to an XLSX file.
Version: 1.1
Author: IDE Interactive
Author URI: https://www.ideinteractive.com/
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

// prevent this file from being accessed directly
defined('ABSPATH') or die('Permission denied.');

// load our autoload if the file exists
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

/**
 * run our during plugin activation
 */
function activate_plcae_plugin()
{
    PLContentAuditExporter\Base\Activate::activate();
}

register_activation_hook(__FILE__, 'activate_plcae_plugin');

/**
 * run when plugin gets deactivated
 */
function deactivate_plcae_plugin()
{
    PLContentAuditExporter\Base\Deactivate::deactivate();
}

register_deactivation_hook(__FILE__, 'deactivate_plcae_plugin');

// register the services
if (class_exists('PLContentAuditExporter\\Init')) {
    PLContentAuditExporter\Init::register_services();
}