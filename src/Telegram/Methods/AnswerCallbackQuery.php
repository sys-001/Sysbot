<?php


namespace TelegramBot\Telegram\Methods;

/**
 * Class AnswerCallbackQuery
 * @package TelegramBot\Telegram\Methods
 */
class AnswerCallbackQuery implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "answerCallbackQuery";
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
    private $callback_query_id;
    /**
     * @var string
     */
    private $text;
    /**
     * @var bool
     */
    private $show_alert;
    /**
     * @var string
     */
    private $url;
    /**
     * @var int
     */
    private $cache_time;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * AnswerCallbackQuery constructor.
     * @param string $callback_query_id
     * @param string|null $text
     * @param bool $show_alert
     * @param string|null $url
     * @param int $cache_time
     */
    function __construct(string $callback_query_id, string $text = null, bool $show_alert = false, string $url = null, int $cache_time = 0)
    {
        $this->callback_query_id = $callback_query_id;
        $this->text = $text;
        $this->show_alert = $show_alert;
        $this->url = $url;
        $this->cache_time = $cache_time;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'callback_query_id' => $this->callback_query_id,
            'text' => $this->text,
            'show_alert' => $this->show_alert,
            'url' => $this->url,
            'cache_time' => $this->cache_time
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