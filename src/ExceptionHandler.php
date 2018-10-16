<?php

namespace TelegramBot;


/**
 * Class ExceptionHandler
 * @package TelegramBot
 */
class ExceptionHandler
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * ExceptionHandler constructor.
     * @param Logger $logger
     */
    function __construct(Logger $logger)
    {
        $this->logger = $logger;
        set_error_handler([$this, 'errorToException']);
        set_exception_handler([$this, 'realHandler']);
    }

    /**
     * @param $severity
     * @param $message
     * @param $file
     * @param $line
     * @throws \ErrorException
     */
    function errorToException($severity, $message, $file, $line): void
    {
        if (!(error_reporting() & $severity)) {
            return;
        }
        throw new \ErrorException($message, 0, $severity, $file, $line);
    }

    /**
     * @param \Throwable $throwable
     * @return bool
     */
    function realHandler(\Throwable $throwable): bool
    {
        $type = get_class($throwable);
        $trace = $throwable->getTrace();
        $trace_call = "";
        if (!empty($trace[0]['class'])) $trace_call = $trace[0]['class'] . '->';
        $trace_call .= $trace[0]['function'];
        $trace_params = json_encode($trace[0]['args'], JSON_UNESCAPED_SLASHES);
        $log_message = sprintf("ExceptionHandler: '%s' with message '%s' occurred in '%s' (line %d) @ %s (args: '%s')", $type, $throwable->getMessage(), $trace[0]['file'], $trace[0]['line'], $trace_call, $trace_params);
        $this->logger->log($log_message);
        return true;
    }
}