<?php


namespace TelegramBot\Telegram\Methods;

/**
 * Class SetPassportDataErrors
 * @package TelegramBot\Telegram\Methods
 */
class SetPassportDataErrors implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "setPassportDataErrors";
    /**
     *
     */
    private const RESULT_TYPE = null;
    /**
     *
     */
    private const MULTIPLE_RESULTS = false;

    /**
     * @var int
     */
    private $user_id;
    /**
     * @var array
     */
    private $errors;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * SetPassportDataErrors constructor.
     * @param int $user_id
     * @param array $errors
     */
    function __construct(int $user_id, array $errors)
    {
        $this->user_id = $user_id;
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'user_id' => $this->user_id,
            'errors' => $this->errors
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