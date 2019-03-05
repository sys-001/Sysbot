<?php


namespace TelegramBot\Telegram\Methods;

/**
 * Class DeleteWebhook
 * @package TelegramBot\Telegram\Methods
 */
class DeleteWebhook implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "deleteWebhook";
    /**
     *
     */
    private const RESULT_TYPE = null;
    /**
     *
     */
    private const MULTIPLE_RESULTS = false;
    /**
     * @var bool
     */
    private $multipart = false;


    /**
     * @return array
     */
    public function getParams(): array
    {
        return [];
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