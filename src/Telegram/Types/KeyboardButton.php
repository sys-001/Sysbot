<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class KeyboardButton
 * @package TelegramBot\Telegram\Types
 */
class KeyboardButton
{

    /**
     * @var
     */
    public $text;
    /**
     * @var
     */
    public $request_contact;
    /**
     * @var
     */
    public $request_location;


    /**
     * @param null|\stdClass $keyboard_button
     * @return null|KeyboardButton
     */
    public static function parseKeyboardButton(?\stdClass $keyboard_button): ?self
    {
        if (is_null($keyboard_button)) return null;
        return (new self())
            ->setText($keyboard_button->text ?? null)
            ->setRequestContact($keyboard_button->request_contact ?? false)
            ->setRequestLocation($keyboard_button->request_location ?? false);
    }

    /**
     * @param bool|null $request_location
     * @return KeyboardButton
     */
    public function setRequestLocation(?bool $request_location): self
    {
        $this->request_location = $request_location;
        return $this;
    }

    /**
     * @param bool|null $request_contact
     * @return KeyboardButton
     */
    public function setRequestContact(?bool $request_contact): self
    {
        $this->request_contact = $request_contact;
        return $this;
    }

    /**
     * @param null|string $text
     * @return KeyboardButton
     */
    public function setText(?string $text): self
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @param array|null $keyboard_buttons
     * @return array|null
     */
    public static function parseKeyboardButtons(?array $keyboard_buttons): ?array
    {
        if (is_null($keyboard_buttons)) return null;
        return array_map(['self', 'parseKeyboardButton'], $keyboard_buttons);
    }

}