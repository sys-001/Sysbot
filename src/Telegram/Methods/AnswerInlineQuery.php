<?php


namespace TelegramBot\Telegram\Methods;

/**
 * Class AnswerInlineQuery
 * @package TelegramBot\Telegram\Methods
 */
class AnswerInlineQuery implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "answerInlineQuery";
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
    private $inline_query_id;
    /**
     * @var array
     */
    private $results;
    /**
     * @var int
     */
    private $cache_time;
    /**
     * @var bool
     */
    private $is_personal;
    /**
     * @var string
     */
    private $next_offset;
    /**
     * @var string
     */
    private $switch_pm_text;
    /**
     * @var string
     */
    private $switch_pm_parameter;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * AnswerInlineQuery constructor.
     * @param string $inline_query_id
     * @param array $results
     * @param int $cache_time
     * @param bool $is_personal
     * @param string|null $next_offset
     * @param string|null $switch_pm_text
     * @param string|null $switch_pm_parameter
     */
    function __construct(string $inline_query_id, array $results, int $cache_time = 300, bool $is_personal = false, string $next_offset = null, string $switch_pm_text = null, string $switch_pm_parameter = null)
    {
        $this->inline_query_id = $inline_query_id;
        $this->results = $results;
        $this->cache_time = $cache_time;
        $this->is_personal = $is_personal;
        $this->next_offset = $next_offset;
        $this->switch_pm_text = $switch_pm_text;
        $this->switch_pm_parameter = $switch_pm_parameter;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'inline_query_id' => $this->inline_query_id,
            'results' => json_encode($this->results),
            'cache_time' => $this->cache_time,
            'is_personal' => $this->is_personal,
            'next_offset' => $this->next_offset,
            'switch_pm_text' => $this->switch_pm_text,
            'switch_pm_parameter' => $this->switch_pm_parameter
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