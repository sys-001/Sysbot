<?php


namespace TelegramBot\Event;


use TelegramBot\Addons\AddonHandler;
use TelegramBot\Addons\DefaultAddon;
use TelegramBot\Telegram\Types\Update;
use TelegramBot\TelegramBot;

/**
 * Class EventHandler
 * @package TelegramBot\Event
 */
class EventHandler
{
    /**
     *
     */
    private const UPDATE_EVENTS = [
        'message',
        'edited_message',
        'channel_post',
        'edited_channel_post',
        'callback_query',
        'inline_query'
    ];
    /**
     *
     */
    private const UPDATE_EVENT_TYPES = ['text', 'caption', 'data', 'query'];
    /**
     * @var array
     */
    private $events = [];

    /**
     * @var array
     */
    private $internal_events = [];

    /**
     * @var array
     */
    private $addons = [];

    /**
     * EventHandler constructor.
     */
    public function __construct()
    {
        $addon_handler = new AddonHandler();
        $this->addons = $addon_handler->getAddons();
    }

    /**
     * @param DefaultEvent $event
     * @return EventHandler
     */
    public function addEvent(DefaultEvent $event): self
    {
        $event_path = $event::$update_path;
        $event_uuid = $event->getUuid();
        $this->events[$event_path[0]][$event_path[1]][$event_uuid] = $event;
        return $this;
    }

    /**
     * @param DefaultEvent $event
     * @return EventHandler
     */
    public function addInternalEvent(DefaultEvent $event): self
    {
        $event_path = $event::$update_path;
        $event_uuid = $event->getUuid();
        $this->internal_events[$event_path[0]][$event_path[1]][$event_uuid] = $event;
        return $this;
    }

    /**
     * @param DefaultEvent $event
     * @return bool
     */
    public function removeEvent(DefaultEvent $event): bool
    {
        $event_path = $event::$update_path;
        $event_uuid = $event->getUuid();
        $events_list = $this->events[$event_path[0]][$event_path[1]] ?? null;
        if (is_null($events_list)) {
            return false;
        }
        if (!isset($events_list[$event_uuid])) {
            return false;
        }
        unset($events_list[$event_uuid]);
        return true;
    }

    /**
     * @param TelegramBot $bot
     * @param Update $update
     * @return array
     */
    public function processEvents(TelegramBot $bot, Update $update): array
    {
        $update_path = $this->getUpdatePath($update);
        $results = [];
        if (empty($update_path)) {
            return $results;
        }
        $events_list = $this->events[$update_path[0]][$update_path[1]] ?? null;
        if (!isset($events_list)) {
            return $results;
        }
        $update_event = $update->{$update_path[0]}->{$update_path[1]};
        foreach ($events_list as $event_uuid => $event) {
            /* @var DefaultEvent $event */
            $event_regex = $event->getTrigger()->getRegex();
            if (1 === preg_match($event_regex, $update_event)) {
                if ($event->admins_only) {
                    $user_id = $update->{$update_path[0]}->from->id ?? 0;
                    $is_admin = $bot->getProvider()->getSettings()->getGeneralSection()->getAdminHandler()->isAdmin($user_id);
                    if (!$is_admin) {
                        continue;
                    }
                }
                $event_callback = $event->getCallback();
                $result = $event_callback($bot);
                $results[$event_uuid] = $result;
            }
        }
        return $results;
    }

    /**
     * @param Update $update
     * @return array|null
     */
    private function getUpdatePath(Update $update): ?array
    {
        $main_path = '';
        $sub_path = '';
        foreach (self::UPDATE_EVENTS as $update_event) {
            if (isset($update->$update_event)) {
                $main_path = $update_event;
            }
        }
        if (empty($main_path)) {
            return null;
        }
        foreach (self::UPDATE_EVENT_TYPES as $update_event_type) {
            if (isset($update->$main_path->$update_event_type)) {
                $sub_path = $update_event_type;
            }
        }
        if (empty($sub_path)) {
            return null;
        }
        return [$main_path, $sub_path];
    }

    /**
     * @param TelegramBot $bot
     * @param Update $update
     */
    public function processInternalEvents(TelegramBot $bot, Update $update): void
    {
        $update_path = $this->getUpdatePath($update);
        if (empty($update_path)) {
            return;
        }
        $events_list = $this->internal_events[$update_path[0]][$update_path[1]] ?? null;
        if (!isset($events_list)) {
            return;
        }
        $update_event = $update->{$update_path[0]}->{$update_path[1]};
        foreach ($events_list as $event_uuid => $event) {
            /* @var DefaultEvent $event */
            $event_regex = $event->getTrigger()->getRegex();
            if (1 === preg_match($event_regex, $update_event)) {
                if ($event->admins_only) {
                    $user_id = $update->{$update_path[0]}->from->id ?? 0;
                    $is_admin = $bot->getProvider()->getSettings()->getGeneralSection()->getAdminHandler()->isAdmin($user_id);
                    if (!$is_admin) {
                        continue;
                    }
                }
                $event_callback = $event->getCallback();
                $event_callback->call($bot);
            }
        }
        return;
    }

    /**
     * @param TelegramBot $bot
     * @param Update $update
     * @return bool
     */

    public function processAddons(TelegramBot $bot, Update $update): bool
    {
        $update_path = $this->getUpdatePath($update);
        $path_string = implode("::", $update_path);
        foreach ($this->addons as $addon) {
            /* @var DefaultAddon $addon */
            if (!in_array($path_string, $addon->getUpdatePaths())) {
                continue;
            }
            $callback = $addon->getCallback();
            $result = $callback($bot);
            if (false == $result) {
                return false;
            }
        }
        return true;
    }
}