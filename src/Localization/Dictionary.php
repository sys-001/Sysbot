<?php


namespace TelegramBot\Localization;


use TelegramBot\Exception\LocalizationProviderException;

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
     * @throws LocalizationProviderException
     */
    function __construct(string $file_path)
    {
        $data = file_get_contents($file_path);
        $fields = json_decode($data ?? []);
        if (!($fields instanceof \stdClass) or empty($fields)) {
            throw new LocalizationProviderException('Could not load language file');
        }
        $this->fields = $fields;
    }

    /**
     * @return \stdClass|null
     */
    public function getFields(): ?\stdClass
    {
        return $this->fields;
    }

}