<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class InputLocationMessageContent
 * @package TelegramBot\Telegram\Types
 */
class InputLocationMessageContent extends InputMessageContent
{

    /**
     * @var
     */
    public $latitude;
    /**
     * @var
     */
    public $longitude;
    /**
     * @var
     */
    public $live_period;


    /**
     * @param null|\stdClass $input_location_message_content
     * @return null|InputLocationMessageContent
     */
    public static function parseInputMessageContent(?\stdClass $input_location_message_content): ?InputMessageContentInterface
    {
        if (is_null($input_location_message_content)) return null;
        return (new self())
            ->setLatitude($input_location_message_content->latitude ?? null)
            ->setLongitude($input_location_message_content->longitude ?? null)
            ->setLivePeriod($input_location_message_content->live_period ?? null);
    }

    /**
     * @param int|null $live_period
     * @return InputLocationMessageContent
     */
    public function setLivePeriod(?int $live_period): InputMessageContentInterface
    {
        $this->live_period = $live_period;
        return $this;
    }

    /**
     * @param float|null $longitude
     * @return InputLocationMessageContent
     */
    public function setLongitude(?float $longitude): InputMessageContentInterface
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @param float|null $latitude
     * @return InputLocationMessageContent
     */
    public function setLatitude(?float $latitude): InputMessageContentInterface
    {
        $this->latitude = $latitude;
        return $this;
    }

}