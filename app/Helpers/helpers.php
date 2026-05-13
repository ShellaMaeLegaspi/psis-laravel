<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('hasAccess')) {
    /**
     * Check if the current user has a specific access right.
     * DISABLED FOR TESTING - Always returns true
     *
     * @param int $access_id
     * @return bool
     */
    function hasAccess($access_id)
    {
        // DISABLED: Grant access to all pages for testing
        return true;
    }
}

if (!function_exists('canViewAll')) {
    /**
     * Check if the current user can view all records.
     * DISABLED FOR TESTING - Always returns true
     *
     * @return bool
     */
    function canViewAll()
    {
        // DISABLED: Grant view all access for testing
        return true;
    }
}
