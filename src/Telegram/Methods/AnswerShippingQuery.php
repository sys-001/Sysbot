<?php


namespace TelegramBot\Telegram\Methods;

use TelegramBot\Exception\TelegramBotException;

/**
 * Class AnswerShippingQuery
 * @package TelegramBot\Telegram\Methods
 */
class AnswerShippingQuery implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "answerShippingQuery";
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
    private $shipping_query_id;
    /**
     * @var bool
     */
    private $ok;
    /**
     * @var array
     */
    private $shipping_options;
    /**
     * @var string
     */
    private $error_message;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * AnswerShippingQuery constructor.
     * @param string $shipping_query_id
     * @param bool $ok
     * @param array|null $shipping_options
     * @param string|null $error_message
     * @throws TelegramBotException
     */
    function __construct(
        string $shipping_query_id,
        bool $ok,
        array $shipping_options = null,
        string $error_message = null
    ) {
        if ($ok) {
            if (empty($shipping_options)) {
                throw new TelegramBotException("Shipping options required");
            }
        } else {
            if (empty($error_message)) {
                throw new TelegramBotException("Error message required");
            }
        }
        $this->shipping_query_id = $shipping_query_id;
        $this->ok = $ok;
        $this->shipping_options = $shipping_options;
        $this->error_message = $error_message;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'shipping_query_id' => $this->shipping_query_id,
            'ok' => $this->ok,
            'shipping_options' => $this->shipping_options,
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