<?php

/**
 * Job Order Statuses Library
 * @package OpenCATS
 * @subpackage Library
 * @copyright (C) OpenCats
 */

/* Configuration in config.php. Ad if missing or rewrite these constants there. */
/* Job Order statuses (not pipeline statuses)
const JOB_ORDER_STATUS_DEFAULT = 'Active';
 */


class JobOrderStatuses
{
    private static $_defaultStatusGroups = [
        'Open' => ['Active', 'On Hold', 'Full'],
        'Closed' => ['Closed', 'Canceled'],
        'Pre-Open' => ['Upcoming', 'Lead'],
    ];

    private static $_defaultFilters = [
        'Active / On Hold / Full',
        'Active',
        'On Hold / Full',
        'Closed / Canceled',
        'Upcoming / Lead',
    ];

    private static $_defaultSharingStatuses = ['Active'];

    private static $_defaultStatisticsStatuses = ['Active', 'OnHold', 'Full', 'Closed'];

    private static $_defaultStatus = 'Active';

    /**
     * Returns job order statuses from config or default
     *
     * @return array job order statuses from config or if undefined, then default
     */
    public static function getAll()
    {
        if (defined('JOB_ORDER_STATUS_GROUP')) {
            return JOB_ORDER_STATUS_GROUP;
        } else {
            return self::$_defaultStatusGroups;
        }
    }

    /**
     * Returns job order searches from config or default
     *
     * @return array job order searches from config or if undefined, then default
     */
    public static function getFilters()
    {
        if (defined('JOB_ORDER_STATUS_FILTERING')) {
            return JOB_ORDER_STATUS_FILTERING;
        } else {
            return self::$_defaultFilters;
        }
    }

    /**
     * Returns job order statuses for sharing (XML, RSS, Career portal) in a format for MySQL IN() query
     */
    public static function getShareStatusSQL()
    {
        $result = "";
        if (! defined('JOB_ORDER_STATUS_SHARING')) {
            $array = self::$_defaultSharingStatuses;
        } else {
            $array = JOB_ORDER_STATUS_SHARING;
        }
        foreach ($array as $status) {
            $result .= "'" . $status . "',";
        }
        if (strlen($result) > 0) {
            $result = substr($result, 0, strlen($result) - 1);
            $result = "(" . $result . ")";
        }
        return $result;
    }

    /**
     * Returns job order statuses for statistics (submission/placement) in a format for MySQL IN() query
     */
    public static function getStatisticsStatusSQL()
    {
        $result = "";
        if (! defined('JOB_ORDER_STATUS_STATISTICS')) {
            $array = self::$_defaultStatisticsStatuses;
        } else {
            $array = JOB_ORDER_STATUS_STATISTICS;
        }
        foreach ($array as $status) {
            $result .= "'" . $status . "',";
        }
        if (strlen($result) > 0) {
            $result = substr($result, 0, strlen($result) - 1);
            $result = "(" . $result . ")";
        }
        return $result;
    }

    /**
     * Returns job order statuses for important candidates on home page in a format for MySQL IN() query
     */
    public static function getOpenStatusSQL()
    {
        $result = "";
        $array = self::getAll()['Open'];
        foreach ($array as $status) {
            $result .= "'" . $status . "',";
        }
        if (strlen($result) > 0) {
            $result = substr($result, 0, strlen($result) - 1);
            $result = "(" . $result . ")";
        }
        return $result;
    }

    public static function getDefaultStatus()
    {
        if (defined('JOB_ORDER_STATUS_DEFAULT')) {
            return JOB_ORDER_STATUS_DEFAULT;
        } else {
            return self::$_defaultStatus;
        }
    }
}
