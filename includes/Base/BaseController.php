<?php
/**
 * Manages all our Base variables
 *
 * @package ide-interactive/content-audit-exporter
 */

namespace PLContentAuditExporter\Base;

/**
 * @property string plugin_path
 * @property string plugin_url
 * @property string plugin
 * @property string admin_url
 * @property string updater_url
 */
class BaseController
{
    /**
     * create our constructor
     */
    public function __construct()
    {
        $this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
        $this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));
        $this->plugin = plugin_basename(dirname(__FILE__, 3)) . '/settings.php';
        $this->admin_url = admin_url();
    }
}
