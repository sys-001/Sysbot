<?php


namespace TelegramBot\Telegram\Methods;

use TelegramBot\Telegram\Types\InputFile;

/**
 * Class SetChatPhoto
 * @package TelegramBot\Telegram\Methods
 */
class SetChatPhoto implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "setChatPhoto";
    /**
     *
     */
    private const RESULT_TYPE = null;
    /**
     *
     */
    private const MULTIPLE_RESULTS = false;

    /**
     * @var string
     */
    private $chat_id;
    /**
     * @var InputFile
     */
    private $photo;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * SetChatPhoto constructor.
     * @param string $chat_id
     * @param InputFile $photo
     */
    function __construct(string $chat_id, InputFile $photo)
    {
        if ($photo->is_local) $this->multipart = true;
        $this->chat_id = $chat_id;
        $this->photo = $photo->input_file;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'chat_id' => $this->chat_id,
            'photo' => $this->photo
        ];
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return self::METHOD_NAME;
    }

    /**
     * @return bool
     */
    public function isMultipart(): bool
    {
        return $this->multipart;
    }

    /**
     * @return array
     */
    public function getResultParams(): array
    {
        return [
            'type' => self::RESULT_TYPE,
            'multiple' => self::MULTIPLE_RESULTS
        ];
    }

}