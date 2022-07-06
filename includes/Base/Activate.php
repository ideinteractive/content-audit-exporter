<?php
/**
 * Manages our plugin activation
 *
 * @package ide-interactive/content-audit-exporter
 */

namespace PLContentAuditExporter\Base;

class Activate
{
    /**
     * runs on plugin activation
     */
    public static function activate()
    {
        // flush rewrite rules
        flush_rewrite_rules();
    }
}
