<?php
/*
Plugin Name: Content Audit Exporter
Plugin URI: https://productivelaziness.com/products/content-audit-exporter/
Description: TBD
Version: 1.0
Author: Productive Laziness LLC
Author URI: https://productivelaziness.com
License: GPLv2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html

Content Audit Exporter is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Content Audit Exporter is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Content Audit Exporter. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
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