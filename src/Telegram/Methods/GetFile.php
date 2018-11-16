<?php


namespace TelegramBot\Telegram\Methods;

/**
 * Class GetFile
 * @package TelegramBot\Telegram\Methods
 */
class GetFile implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "getFile";
    /**
     *
     */
    private const RESULT_TYPE = "File";
    /**
     *
     */
    private const MULTIPLE_RESULTS = false;

    /**
     * @var string
     */
    private $file_id;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * GetFile constructor.
     * @param string $file_id
     */
    function __construct(string $file_id)
    {
        $this->file_id = $file_id;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'file_id' => $this->file_id
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