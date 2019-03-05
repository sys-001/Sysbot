<?php


namespace TelegramBot\Telegram\Methods;

/**
 * Class GetWebhookInfo
 * @package TelegramBot\Telegram\Methods
 */
class GetWebhookInfo implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "getWebhookInfo";
    /**
     *
     */
    private const RESULT_TYPE = "WebhookInfo";
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