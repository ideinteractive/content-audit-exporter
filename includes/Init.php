<?php
/**
 * @package productive-laziness/content-audit-exporter
 */

namespace PLContentAuditExporter;

final class Init
{
    /**
     * store all the classes inside an array
     * @return array full list of classes
     */
    public static function get_services()
    {
        return [
            Pages\Settings::class,
        ];
    }

    /**
     * loop through the classes, initialize them
     * and call the register() method if it exists
     * @return void
     */
    public static function register_services()
    {
        foreach (self::get_services() as $class) {
            $service = self::instantiate($class);
            if (method_exists($service, 'register')) {
                $service->register();
            }
        }
    }

    /**
     * initialize the class
     * @param class $class class from the services array
     * @return class instance  new instance of the class
     */
    private static function instantiate($class)
    {
        return new $class();
    }
}
