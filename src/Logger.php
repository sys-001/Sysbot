<?php

namespace TelegramBot;


/**
 * Class Logger
 * @package TelegramBot
 */
class Logger
{
    /**
     * @var int
     */
    private $verbosity;
    /**
     * @var bool
     */
    private $is_cli;
    /**
     * @var bool|resource
     */
    private $log_file;

    /**
     * @var array
     */
    private $verbosity_levels = [0, 1, 2];

    /**
     * Logger constructor.
     * @param int $verbosity
     * @param string|null $file_path
     * @throws \Exception
     */
    function __construct(int $verbosity = 0, string $file_path = null)
    {
        if (!in_array($verbosity, $this->verbosity_levels)) $verbosity = 0;
        $this->verbosity = $verbosity;
        $start = sprintf("[%s] Logger: Logger started.%s", date("d/m/y - H:i:s e"), PHP_EOL);
        if (empty($file_path) and php_sapi_name() == "cli") {
            $this->is_cli = true;
            echo $start;
            printf("[%s] Logger: Logger mode set to 'echo'.%s", date("d/m/y - H:i:s e"), PHP_EOL);
        } else {
            if (empty($file_path)) $file_path = "Sysbot.log";
            $log_file = fopen($file_path, "w");
            if (false == $log_file) throw new \Exception("Could not create log file");
            fwrite($log_file, $start);
            $start = sprintf("[%s] Logger: Logger mode set to 'file'.%s", date("d/m/y - H:i:s e"), PHP_EOL);
            fwrite($log_file, $start);
            fflush($log_file);
            $this->log_file = $log_file;
        }
    }

    /**
     * @param string $message
     */
    function log(string $message): void
    {
        $message = sprintf("[%s] %s%s", date("d/m/y - H:i:s e"), $message, PHP_EOL);
        if ($this->is_cli) {
            echo $message;
            return;
        }
        fwrite($this->log_file, $message);
        fflush($this->log_file);
        return;
    }

    /**
     * @return int
     */
    public function getVerbosity(): int
    {
        return $this->verbosity;
    }
}