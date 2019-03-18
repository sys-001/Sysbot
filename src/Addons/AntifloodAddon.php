<?php /** @noinspection PhpUnusedPrivateFieldInspection */

namespace TelegramBot\Addons;


use TelegramBot\TelegramBot;

/**
 * Class AntifloodAddon
 * @package TelegramBot\Addons
 */
class AntifloodAddon extends DefaultAddon
{

    /**
     * @var array
     */
    private $update_paths = ["message::text"];

    /**
     * AntifloodAddon constructor.
     */
    function __construct()
    {

        $this->callback = function (TelegramBot $bot) {
            $cache = $bot->getEntityManager()->getConfiguration()->getQueryCacheImpl() ?? null;
            if (null == $cache) return true;
            $settings = $bot->getProvider()->getSettings();
            $antiflood_settings = $settings->getAntifloodSection();
            $update = $bot->getCurrentUpdate();
            if ($update->message->chat->id <= 0) return true;
            $user_id = $update->message->from->id;
            if ($settings->getGeneralSection()->getAdminHandler()->isAdmin($user_id)) return true;
            $data = $cache->fetch($user_id);
            $is_banned = $data["is_banned"] ?? false;
            if ($is_banned) return false;
            $timestamp = $data["first_date"] ?? $update->message->date;
            $messages = $data["messages"] ?? 0;
            $messages++;
            $time_diff = $update->message->date - $timestamp;
            $antiflood_seconds = $antiflood_settings->getMessagesSeconds();
            if ($messages >= $antiflood_settings->getMessagesNumber() and $time_diff <= $antiflood_seconds) {
                $ban_duration = $antiflood_settings->getBanSeconds();
                $cache->save($user_id, ["messages" => $messages, "first_date" => $timestamp, "is_banned" => true], $ban_duration);
                $bot->sendMessage($antiflood_settings->getBanMessage());
                return false;
            } elseif ($time_diff > $antiflood_seconds) {
                $cache->delete($user_id);
                return true;
            }
            $cache->save($user_id, ["messages" => $messages, "first_date" => $timestamp, "is_banned" => $is_banned], 10);
            return true;
        };
    }

    /**
     * @return array
     */
    public function getUpdatePaths(): array
    {
        return $this->update_paths;
    }

}