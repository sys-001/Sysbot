<?php


namespace TelegramBot\Telegram\Methods;

use TelegramBot\Telegram\Types\InlineKeyboardMarkup;

/**
 * Class SendInvoice
 * @package TelegramBot\Telegram\Methods
 */
class SendInvoice implements MethodInterface
{

    /**
     *
     */
    private const METHOD_NAME = "sendInvoice";
    /**
     *
     */
    private const RESULT_TYPE = "Message";
    /**
     *
     */
    private const MULTIPLE_RESULTS = false;

    /**
     * @var string
     */
    private $chat_id;
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $description;
    /**
     * @var string
     */
    private $payload;
    /**
     * @var string
     */
    private $provider_token;
    /**
     * @var string
     */
    private $start_parameter;
    /**
     * @var string
     */
    private $currency;
    /**
     * @var array
     */
    private $prices;
    /**
     * @var string
     */
    private $provider_data;
    /**
     * @var string
     */
    private $photo_url;
    /**
     * @var int
     */
    private $photo_size;
    /**
     * @var int
     */
    private $photo_width;
    /**
     * @var int
     */
    private $photo_height;
    /**
     * @var bool
     */
    private $need_name;
    /**
     * @var bool
     */
    private $need_phone_number;
    /**
     * @var bool
     */
    private $need_email;
    /**
     * @var bool
     */
    private $need_shipping_address;
    /**
     * @var bool
     */
    private $send_phone_number_to_provider;
    /**
     * @var bool
     */
    private $send_email_to_provider;
    /**
     * @var bool
     */
    private $is_flexible;
    /**
     * @var bool
     */
    private $disable_notification;
    /**
     * @var int
     */
    private $reply_to_message_id;
    /**
     * @var InlineKeyboardMarkup
     */
    private $reply_markup;

    /**
     * @var bool
     */
    private $multipart = false;

    /**
     * SendInvoice constructor.
     * @param string $chat_id
     * @param string $title
     * @param string $description
     * @param string $payload
     * @param string $provider_token
     * @param string $start_parameter
     * @param string $currency
     * @param array $prices
     * @param string|null $provider_data
     * @param string|null $photo_url
     * @param int|null $photo_size
     * @param int|null $photo_width
     * @param int|null $photo_height
     * @param bool $need_name
     * @param bool $need_phone_number
     * @param bool $need_email
     * @param bool $need_shipping_address
     * @param bool $send_phone_number_to_provider
     * @param bool $send_email_to_provider
     * @param bool $is_flexible
     * @param bool $disable_notification
     * @param int|null $reply_to_message_id
     * @param InlineKeyboardMarkup|null $reply_markup
     */
    function __construct(string $chat_id, string $title, string $description, string $payload, string $provider_token, string $start_parameter, string $currency, array $prices, string $provider_data = null, string $photo_url = null, int $photo_size = null, int $photo_width = null, int $photo_height = null, bool $need_name = false, bool $need_phone_number = false, bool $need_email = false, bool $need_shipping_address = false, bool $send_phone_number_to_provider = false, bool $send_email_to_provider = false, bool $is_flexible = false, bool $disable_notification = false, int $reply_to_message_id = null, InlineKeyboardMarkup $reply_markup = null)
    {
        $this->chat_id = $chat_id;
        $this->title = $title;
        $this->description = $description;
        $this->payload = $payload;
        $this->provider_token = $provider_token;
        $this->start_parameter = $start_parameter;
        $this->currency = $currency;
        $this->prices = $prices;
        $this->provider_data = $provider_data;
        $this->photo_url = $photo_url;
        $this->photo_size = $photo_size;
        $this->photo_width = $photo_width;
        $this->photo_height = $photo_height;
        $this->need_name = $need_name;
        $this->need_phone_number = $need_phone_number;
        $this->need_email = $need_email;
        $this->need_shipping_address = $need_shipping_address;
        $this->send_phone_number_to_provider = $send_phone_number_to_provider;
        $this->send_email_to_provider = $send_email_to_provider;
        $this->is_flexible = $is_flexible;
        $this->disable_notification = $disable_notification;
        $this->reply_to_message_id = $reply_to_message_id;
        $this->reply_markup = $reply_markup;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'chat_id' => $this->chat_id,
            'title' => $this->title,
            'description' => $this->description,
            'payload' => $this->payload,
            'provider_token' => $this->provider_token,
            'start_parameter' => $this->start_parameter,
            'currency' => $this->currency,
            'prices' => $this->prices,
            'provider_data' => $this->provider_data,
            'photo_url' => $this->photo_url,
            'photo_size' => $this->photo_size,
            'photo_width' => $this->photo_width,
            'photo_height' => $this->photo_height,
            'need_name' => $this->need_name,
            'need_phone_number' => $this->need_phone_number,
            'need_email' => $this->need_email,
            'need_shipping_address' => $this->need_shipping_address,
            'send_phone_number_to_provider' => $this->send_phone_number_to_provider,
            'send_email_to_provider' => $this->send_email_to_provider,
            'is_flexible' => $this->is_flexible,
            'disable_notification' => $this->disable_notification,
            'reply_to_message_id' => $this->reply_to_message_id,
            'reply_markup' => $this->reply_markup
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