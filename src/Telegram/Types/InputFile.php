<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InputFile
 * @package TelegramBot\Telegram\Types
 */
class InputFile
{
    /**
     * @var
     */
    public $input_file;
    /**
     * @var
     */
    public $is_local;

    /**
     * InputFile constructor.
     * @param string $input_file
     * @param bool $is_local
     */
    function __construct(string $input_file, bool $is_local = false)
    {
        if ($is_local) $input_file = fopen($input_file, 'r');
        $this->input_file = $input_file ?? null;
        $this->is_local = $is_local ?? false;
    }


    /**
     * @param string|resource $input_file
     * @param bool $is_local
     * @return null|InputFile
     */
    public static function parseInputFile(string $input_file, bool $is_local = false): self
    {
        return new self($input_file ?? null, $is_local ?? false);
    }

}