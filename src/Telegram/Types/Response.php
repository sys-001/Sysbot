<?php

namespace TelegramBot\Telegram\Types;


/**
 * Class Response
 * @package TelegramBot\Telegram\Types
 */
class Response
{
    /**
     * @var bool
     */
    public $ok;
    /**
     * @var \stdClass
     */
    public $result;
    /**
     * @var int
     */
    public $error_code;
    /**
     * @var string
     */
    public $description;

    /**
     * @param null|\stdClass $response
     * @return null|Response
     */
    public static function parseResponse(?\stdClass $response): ?self
    {
        if (is_null($response)) return null;
        $parsed_response = (new self())
            ->setOk($response->ok ?? null)
            ->setErrorCode($response->error_code ?? null)
            ->setDescription($response->description ?? null);
        if (empty($response->result)) {
            $parsed_response->setResult(null);
        } elseif (!empty($response->result->migrate_to_chat_id) or !empty($response->result->retry_after)) {
            $parsed_response->setResult(ResponseParameters::parseResponseParameters($response->result ?? null));
        } elseif (!empty($response->result_type)) {
            $result_class = sprintf('TelegramBot\Telegram\Types\%s', $response->result_type->class);
            $parsed_response->setResult(call_user_func([$result_class, $response->result_type->method], $response->result ?? null));
        } else {
            $parsed_response->setResult($response->result ?? null);
        }
        return $parsed_response;
    }

    /**
     * @param string $description
     * @return Response
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param int $error_code
     * @return Response
     */
    public function setErrorCode(?int $error_code): self
    {
        $this->error_code = $error_code;
        return $this;
    }

    /**
     * @param bool $ok
     * @return Response
     */
    public function setOk(?bool $ok): self
    {
        $this->ok = $ok;
        return $this;
    }

    /**
     * @param mixed $result
     * @return Response
     */
    public function setResult($result): self
    {
        $this->result = $result;
        return $this;
    }

}