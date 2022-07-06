<?php
/**
 * Manages our plugin deactivation
 *
 * @package ide-interactive/content-audit-exporter
 */

namespace PLContentAuditExporter\Base;

class Deactivate
{
    /**
     * runs on plugin deactivation
     */
    public static function deactivate()
    {
        // flush rewrite rules
        flush_rewrite_rules();
    }
}
