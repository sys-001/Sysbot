<?php


namespace TelegramBot\Telegram\Types;


/**
 * Class UserProfilePhotos
 * @package TelegramBot\Telegram\Types
 */
class UserProfilePhotos
{

    /**
     * @var
     */
    public $total_count;
    /**
     * @var
     */
    public $photos;


    /**
     * @param null|\stdClass $user_profile_photos
     * @return null|UserProfilePhotos
     */
    public static function parseUserProfilePhotos(?\stdClass $user_profile_photos): ?self
    {
        $user_profile_photos->photos = (array)$user_profile_photos->photos;
        if (is_null($user_profile_photos)) return null;
        return (new self())
            ->setTotalCount($user_profile_photos->total_count ?? null)
            ->setPhotos(array_map(['PhotoSize', 'parsePhotoSizes'], $user_profile_photos->photos ?? null));
    }

    /**
     * @param array|null $photos
     * @return UserProfilePhotos
     */
    public function setPhotos(?array $photos): self
    {
        $this->photos = $photos;
        return $this;
    }

    /**
     * @param int|null $total_count
     * @return UserProfilePhotos
     */
    public function setTotalCount(?int $total_count): self
    {
        $this->total_count = $total_count;
        return $this;
    }

}