<?php

namespace taobig\yii;

use yii\console\Controller;

class BaseCronController extends Controller
{

    protected $start_time = 0;
    protected $end_time = 0;

    /**
     * @param string $message
     * @return string
     */
    protected function actionStart(string $message)
    {
        $this->start_time = microtime(true);
        return $this->formatLog("[start]{$message}");
    }

    /**
     * @param string $message
     * @return string
     */
    protected function actionEnd(string $message)
    {
        $this->end_time = microtime(true);
        $seconds = number_format($this->end_time - $this->start_time, 5);
        return $this->formatLog("[end]{$message}...total-seconds:{$seconds}");
    }

    /**
     * @param string $message
     * @param bool $showMemoryUsage
     * @return string
     */
    protected function formatLog(string $message, bool $showMemoryUsage = false)
    {
        if ($showMemoryUsage) {
            $memoryUsage1 = (memory_get_usage(true) / 1024 / 1024) . " MB";
            $memoryUsage2 = number_format(memory_get_usage() / 1024 / 1024, 4) . " MB";
            $peakMemoryUsage1 = (memory_get_peak_usage(true) / 1024 / 1024) . " MB";
            $peakMemoryUsage2 = number_format(memory_get_peak_usage() / 1024 / 1024, 4) . " MB";
            return sprintf("[%s]%s[memory_usage:%s,%s; peak_memory_usage:%s,%s]\n", date('Y-m-d H:i:s'), $message, $memoryUsage1, $memoryUsage2, $peakMemoryUsage1, $peakMemoryUsage2);
        }
        return sprintf("[%s]%s\n", date('Y-m-d H:i:s'), $message);
    }

    protected function formatSql(string $sql, bool $showMemoryUsage = false): string
    {
        return $this->formatLog(str_replace("\n", ' ', $sql), $showMemoryUsage);
    }


}