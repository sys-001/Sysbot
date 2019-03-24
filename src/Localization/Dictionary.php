<?php


namespace TelegramBot\Localization;


/**
 * Class Dictionary
 * @package TelegramBot\Localization
 */
class Dictionary
{

    /**
     * @var \stdClass|null
     */
    private $fields = null;

    /**
     * Language constructor.
     * @param string $file_path
     */
    function __construct(string $file_path)
    {
        $data = file_get_contents($file_path);
        $fields = json_decode($data ?? []);
        if ($fields instanceof \stdClass) {
            $this->fields = $fields;
        }
    }

    /**
     * @return \stdClass|null
     */
    public function getFields(): ?\stdClass
    {
        return $this->fields;
    }

}