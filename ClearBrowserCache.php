<?php

namespace core;

class ClearBrowserCache
{
    private static function setCurrentTimeCache()
    {
        $_SESSION['time_cash'] = time();
    }

    private static function getUpdateTime()
    {
        $time = \g::settings('time_cash');
        if (strlen($time) < 1) {
            \g::db()->query("INSERT INTO `settings` ( `name`, `val`, `placeholder`, `description`, `label`, `type`, `modules`) VALUES
                    ('time_cash', ?s, '', '', '', 'text', 'settings');", time());
        }
        return $time;
    }

    public static function clearCache()
    {
        \g::db()->query("UPDATE `settings` SET val = ?s WHERE name = 'time_cash'", time() + 2);
    }

    private static function checkExitLogin()
    {
        return $_SESSION['checkExitLogin'] ?? false;
    }

    private static function gelLastTimeCache()
    {
        return $_SESSION['time_cash'] ?? 0;
    }

    public static function checkCache()
    {
        $ClearCashTime = ClearBrowserCache::getUpdateTime();

        $lastTime = ClearBrowserCache::gelLastTimeCache();
        ClearBrowserCache::setCurrentTimeCache();
        if (ClearBrowserCache::checkExitLogin()) {
            ClearBrowserCache::clearCacheHeaderAll();
            return;
        }
        if ($lastTime == 0 || $ClearCashTime > $lastTime)
            ClearBrowserCache::clearCacheHeader();

    }

    private static function clearCacheHeader()
    {
        header('Clear-Site-Data: "cache", "storage", "executionContexts"');
    }

    private static function clearCacheHeaderAll()
    {
        $_SESSION['checkExitLogin'] = false;
        header('Clear-Site-Data: "cache", "cookies", "storage", "executionContexts"');
    }
}