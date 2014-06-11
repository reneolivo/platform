<?php

namespace Thor\Support;

class Bench
{

    protected $start_time;
    protected $end_time;
    protected $memory_usage;

    /**
     * Sets start microtime
     *
     * @return void
     */
    public function start()
    {
        $this->start_time = microtime(true);
    }

    /**
     * Sets end microtime
     *
     * @return void
     */
    public function end()
    {
        $this->end_time = microtime(true);
        $this->memory_usage = memory_get_usage(true);
    }

    /**
     * Returns the elapsed time, readable or not
     *
     * @param  boolean $raw Whether the result must be in raw format or human readable
     * @param  string  $format   The format to display (printf format)
     * @return string|float
     */
    public function time($raw = false, $format = null)
    {
        return $raw ? ($this->end_time - $this->start_time) : $this->formattedElapsedTime($this->start_time, $this->end_time, $format);
    }

    /**
     * Returns the memory usage at the end checkpoint
     *
     * @param  boolean $raw Whether the result must be in raw format or human readable
     * @param  string  $format   The format to display (printf format)
     * @return string|float
     */
    public function memoryUsage($raw = false, $format = null)
    {
        return $raw ? $this->memory_usage : $this->formattedMemorySize($this->memory_usage, $format);
    }

    /**
     * Returns the memory peak, readable or not
     *
     * @param  boolean $raw Whether the result must be in raw format or human readable
     * @param  string  $format   The format to display (printf format)
     * @return string|float
     */
    public function memoryPeak($raw = false, $format = null)
    {
        $memory = memory_get_peak_usage(true);

        return $raw ? $memory : $this->formattedMemorySize($memory, $format);
    }

    /**
     * Returns a human readable memory / disc size
     *
     * @param   int    $size size in bytes
     * @param   string $format   The format to display (printf format)
     * @param   int    $round
     * @return  string
     */
    public function formattedMemorySize($size, $format = null, $round = 3)
    {
        $mod = 1024;

        if (is_null($format)) {
            $format = '%.2f%s';
        }

        $units = explode(' ', 'B Kb Mb Gb Tb');

        for ($i = 0; $size > $mod; $i++) {
            $size /= $mod;
        }

        if (0 === $i) {
            $format = preg_replace('/(%.[\d]+f)/', '%d', $format);
        }

        return sprintf($format, round($size, $round), $units[$i]);
    }

    /**
     *
     * @param float $start_microtime
     * @param float $end_microtime
     * @param string $format The format to display (printf format)
     * @param int $round Decimal precision
     * @return string 
     */
    public function formattedElapsedTime($start_microtime, $end_microtime = null, $format = null, $round = 3)
    {
        if (empty($end_microtime)) {
            $end_microtime = microtime(true);
        }
        $microtime = $end_microtime - $start_microtime;

        if (is_null($format)) {
            $format = '%.3f%s';
        }

        if ($microtime >= 1) {
            $unit = 's';
            $time = round($microtime, $round);
        } else {
            $unit = 'ms';
            $time = round($microtime * 1000);

            $format = preg_replace('/(%.[\d]+f)/', '%d', $format);
        }

        return sprintf($format, $time, $unit);
    }

}
