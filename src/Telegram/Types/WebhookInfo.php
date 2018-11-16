<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class WebhookInfo
 * @package TelegramBot\Telegram\Types
 */
class WebhookInfo
{

    /**
     * @var
     */
    public $url;
    /**
     * @var
     */
    public $has_custom_certificate;
    /**
     * @var
     */
    public $pending_update_count;
    /**
     * @var
     */
    public $last_error_date;
    /**
     * @var
     */
    public $last_error_message;
    /**
     * @var
     */
    public $max_connections;
    /**
     * @var
     */
    public $allowed_updates;


    /**
     * @param null|\stdClass $webhook_info
     * @return null|WebhookInfo
     */
    public static function parseWebhookInfo(?\stdClass $webhook_info): ?self
    {
        if (is_null($webhook_info)) return null;
        return (new self())
            ->setUrl($webhook_info->url ?? null)
            ->setHasCustomCertificate($webhook_info->has_custom_certificate ?? null)
            ->setPendingUpdateCount($webhook_info->pending_update_count ?? null)
            ->setLastErrorDate($webhook_info->last_error_date ?? null)
            ->setLastErrorMessage($webhook_info->last_error_message ?? null)
            ->setMaxConnections($webhook_info->max_connections ?? null)
            ->setAllowedUpdates($webhook_info->allowed_updates ?? null);
    }

    /**
     * @param array|null $allowed_updates
     * @return WebhookInfo
     */
    public function setAllowedUpdates(?array $allowed_updates): self
    {
        $this->allowed_updates = $allowed_updates;
        return $this;
    }

    /**
     * @param int|null $max_connections
     * @return WebhookInfo
     */
    public function setMaxConnections(?int $max_connections): self
    {
        $this->max_connections = $max_connections;
        return $this;
    }

    /**
     * @param null|string $last_error_message
     * @return WebhookInfo
     */
    public function setLastErrorMessage(?string $last_error_message): self
    {
        $this->last_error_message = $last_error_message;
        return $this;
    }

    /**
     * @param int|null $last_error_date
     * @return WebhookInfo
     */
    public function setLastErrorDate(?int $last_error_date): self
    {
        $this->last_error_date = $last_error_date;
        return $this;
    }

    /**
     * @param int|null $pending_update_count
     * @return WebhookInfo
     */
    public function setPendingUpdateCount(?int $pending_update_count): self
    {
        $this->pending_update_count = $pending_update_count;
        return $this;
    }

    /**
     * @param bool|null $has_custom_certificate
     * @return WebhookInfo
     */
    public function setHasCustomCertificate(?bool $has_custom_certificate): self
    {
        $this->has_custom_certificate = $has_custom_certificate;
        return $this;
    }

    /**
     * @param null|string $url
     * @return WebhookInfo
     */
    public function setUrl(?string $url): self
    {
        $this->url = $url;
        return $this;
    }

}