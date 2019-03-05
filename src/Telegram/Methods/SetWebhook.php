<?php


namespace TelegramBot\Telegram\Methods;

use TelegramBot\Telegram\Types\InputFile;

/**
 * Class SetWebhook
 * @package TelegramBot\Telegram\Methods
 */
class SetWebhook implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "setWebhook";
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
    private $url;
    /**
     * @var InputFile
     */
    private $certificate;
    /**
     * @var int
     */
    private $max_connections;
    /**
     * @var array
     */
    private $allowed_updates;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * SetWebhook constructor.
     * @param string $url
     * @param InputFile|null $certificate
     * @param int $max_connections
     * @param array $allowed_updates
     */
    function __construct(string $url, InputFile $certificate = null, int $max_connections = 40, array $allowed_updates = [])
    {
        if ($certificate->is_local) $this->multipart = true;
        $this->url = $url;
        $this->certificate = $certificate->input_file;
        $this->max_connections = $max_connections;
        $this->allowed_updates = $allowed_updates;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'url' => $this->url,
            'certificate' => $this->certificate,
            'max_connections' => $this->max_connections,
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