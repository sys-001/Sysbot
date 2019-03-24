<?php


namespace TelegramBot\Telegram\Methods;

use TelegramBot\Exception\TelegramBotException;

/**
 * Class AnswerPreCheckoutQuery
 * @package TelegramBot\Telegram\Methods
 */
class AnswerPreCheckoutQuery implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "answerPreCheckoutQuery";
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
    private $pre_checkout_query_id;
    /**
     * @var bool
     */
    private $ok;
    /**
     * @var string
     */
    private $error_message;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * AnswerPreCheckoutQuery constructor.
     * @param string $pre_checkout_query_id
     * @param bool $ok
     * @param string|null $error_message
     * @throws TelegramBotException
     */
    function __construct(string $pre_checkout_query_id, bool $ok, string $error_message = null)
    {
        if (!$ok and empty($error_message)) {
            throw new TelegramBotException("Error message required");
        }
        $this->pre_checkout_query_id = $pre_checkout_query_id;
        $this->ok = $ok;
        $this->error_message = $error_message;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'pre_checkout_query_id' => $this->pre_checkout_query_id,
            'ok' => $this->ok,
            'error_message' => $this->error_message
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