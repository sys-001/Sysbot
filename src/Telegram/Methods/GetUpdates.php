<?php


namespace TelegramBot\Telegram\Methods;

/**
 * Class GetUpdates
 * @package TelegramBot\Telegram\Methods
 */
class GetUpdates implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "getUpdates";
    /**
     *
     */
    private const RESULT_TYPE = "Update";
    /**
     *
     */
    private const MULTIPLE_RESULTS = true;

    /**
     * @var int
     */
    private $offset;
    /**
     * @var int
     */
    private $limit;
    /**
     * @var int
     */
    private $timeout;
    /**
     * @var array
     */
    private $allowed_updates;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * GetUpdates constructor.
     * @param int $offset
     * @param int $limit
     * @param int $timeout
     * @param array $allowed_updates
     */
    function __construct(int $offset = 0, int $limit = 100, int $timeout = 0, array $allowed_updates = [])
    {
        $this->offset = $offset;
        $this->limit = $limit;
        $this->timeout = $timeout;
        $this->allowed_updates = $allowed_updates;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'offset' => $this->offset,
            'limit' => $this->limit,
            'timeout' => $this->timeout,
            'allowed_updates' => $this->allowed_updates
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