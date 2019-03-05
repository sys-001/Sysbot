<?php


namespace TelegramBot\Telegram\Methods;

/**
 * Class GetUserProfilePhotos
 * @package TelegramBot\Telegram\Methods
 */
class GetUserProfilePhotos implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "getUserProfilePhotos";
    /**
     *
     */
    private const RESULT_TYPE = "UserProfilePhotos";
    /**
     *
     */
    private const MULTIPLE_RESULTS = false;

    /**
     * @var int
     */
    private $user_id;
    /**
     * @var int
     */
    private $offset;
    /**
     * @var int
     */
    private $limit;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * GetUserProfilePhotos constructor.
     * @param int $user_id
     * @param int $offset
     * @param int $limit
     */
    function __construct(int $user_id, int $offset = 0, int $limit = 100)
    {
        $this->user_id = $user_id;
        $this->offset = $offset;
        $this->limit = $limit;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'user_id' => $this->user_id,
            'offset' => $this->offset,
            'limit' => $this->limit
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