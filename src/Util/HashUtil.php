<?php

/** @noinspection SpellCheckingInspection */

/** @noinspection PhpMissingBreakStatementInspection */

namespace TelegramBot\Util;

use TelegramBot\Exception\UtilException;

/**
 * Trait HashUtil
 * @package TelegramBot\Util
 */
trait HashUtil
{

    /**
     * @param string $type
     * @param int $dc_id
     * @param int $id
     * @param int $access_hash
     * @param int|null $volume_id
     * @param int|null $secret
     * @param int|null $local_id
     * @return string
     * @throws UtilException
     */
    public static function encodeFileIdInfo(
        string $type,
        int $dc_id,
        int $id,
        int $access_hash,
        int $volume_id = null,
        int $secret = null,
        int $local_id = null
    ) {
        switch ($type) {
            case 'Thumb':
                $file_type = '0';
            case 'Photo':
                if (empty($file_type)) {
                    $file_type = '2';
                }
                if (empty($volume_id) or empty($secret) or empty($local_id)) {
                    throw new UtilException("Missing required parameters for file type");
                }
                $file_id = pack('VVqqqqV', $file_type, $dc_id, $id, $access_hash, $volume_id, $secret, $local_id);
                break;
            case 'Voice':
                $file_type = '3';
            case 'Video':
                if (empty($file_type)) {
                    $file_type = '4';
                }
            case 'Document':
                if (empty($file_type)) {
                    $file_type = '5';
                }
            case 'Sticker':
                if (empty($file_type)) {
                    $file_type = '8';
                }
            case 'Audio':
                if (empty($file_type)) {
                    $file_type = '9';
                }
            case 'Animation':
                if (empty($file_type)) {
                    $file_type = 'A';
                }
            case 'VideoNote':
                if (empty($file_type)) {
                    $file_type = 'D';
                }
                $file_id = pack('VVqq', $file_type, $dc_id, $id, $access_hash);
                break;
            default:
                $file_id = null;
                break;
        }
        return self::base64UrlEncode(self::rleEncode($file_id . chr(2)));
    }

    /**
     * @param string $string
     * @return string
     */
    protected static function base64UrlEncode(string $string): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($string));
    }

    /**
     * @param string $string
     * @return string
     */
    protected static function rleEncode(string $string): string
    {
        $new = '';
        $count = 0;
        $null = chr(0);
        foreach (str_split($string) as $cur) {
            if ($cur === $null) {
                $count++;
            } else {
                if ($count > 0) {
                    $new .= $null . chr($count);
                    $count = 0;
                }
                $new .= $cur;
            }
        }
        return $new;
    }

    /**
     * @param string $file_id
     * @return array
     */
    public static function decodeFileIdInfo(string $file_id)
    {
        $file_info = self::rleDecode(self::base64UrlDecode($file_id));
        $file_type = unpack('V', substr($file_info, 0, 4))[1];
        switch ($file_type) {
            case '0':
                $type = 'Thumb';
            case '2':
                if (empty($type)) {
                    $type = 'Photo';
                }
                $decrypted_info = unpack('Vtype/Vdc_id/qid/qaccess_hash/qvolume_id/qsecret/Vlocal_id', $file_info);
                break;
            case '3':
                $type = 'Voice';
            case '4':
                if (empty($type)) {
                    $type = 'Video';
                }
            case '5':
                if (empty($type)) {
                    $type = 'Document';
                }
            case '8':
                if (empty($type)) {
                    $type = 'Sticker';
                }
            case '9':
                if (empty($type)) {
                    $type = 'Audio';
                }
            case 'A':
                if (empty($type)) {
                    $type = 'Animation';
                }
            case 'D':
                if (empty($type)) {
                    $type = 'VideoNote';
                }
                $decrypted_info = unpack('Vtype/Vdc_id/qid/qaccess_hash', $file_info);
                break;
            default:
                $type = null;
                break;
        }
        $decrypted_info['type'] = $type;
        return $decrypted_info;
    }

    /**
     * @param string $string
     * @return string
     */
    protected static function rleDecode(string $string): string
    {
        $new = '';
        $last = '';
        $null = chr(0);
        foreach (str_split($string) as $cur) {
            if ($last === $null) {
                $new .= str_repeat($last, ord($cur));
                $last = '';
            } else {
                $new .= $last;
                $last = $cur;
            }
        }
        return $new . $last;
    }

    /**
     * @param string $string
     * @return string
     */
    protected static function base64UrlDecode(string $string): string
    {
        return base64_decode(str_pad(strtr($string, '-_', '+/'), strlen($string) % 4, '=', STR_PAD_RIGHT));
    }

    /**
     * @param int $chat_creator
     * @param int $chat_id
     * @param int $access_hash
     * @return string
     */
    public static function encodeChatInviteHashInfo(int $chat_creator, int $chat_id, int $access_hash): string
    {
        $inverted_hash = pack('q', $access_hash);
        $inverted_hash = unpack('q', strrev($inverted_hash))[1];
        if (strpos((string)$chat_id, '-100') === 0) {
            $chat_id = substr($chat_id, 4);
            $chat_id = (int)$chat_id;
        } else {
            $chat_id = substr($chat_id, 1);
        }
        $chat_id = (int)$chat_id;
        $chat_invite_hash = pack('NNq', $chat_creator, $chat_id, $inverted_hash);
        return self::base64UrlEncode($chat_invite_hash);
    }

    /**
     * @param string $chat_invite_hash
     * @return array
     */
    public static function decodeChatInviteHashInfo(string $chat_invite_hash): array
    {
        $chat_invite_info = self::base64UrlDecode($chat_invite_hash);
        $decrypted_info = unpack('Nchat_creator/Nchat_id/qaccess_hash', $chat_invite_info);
        if (strlen($decrypted_info['chat_id']) < 10) {
            $chat_id = '-' . $decrypted_info['chat_id'];
            $decrypted_info['chat_id'] = (int)$chat_id;
            return $decrypted_info;
        }
        $chat_id = '-100' . $decrypted_info['chat_id'];
        $decrypted_info['chat_id'] = (int)$chat_id;
        $real_hash = pack('q', $decrypted_info['access_hash']);
        $decrypted_info['access_hash'] = unpack('q', strrev($real_hash))[1];
        return $decrypted_info;
    }

    /**
     * @param int $dc_id
     * @param int $message_id
     * @param int $chat_id
     * @param int $access_hash
     * @return string
     */
    public static function encodeInlineMessageIdInfo(
        int $dc_id,
        int $message_id,
        int $chat_id,
        int $access_hash
    ): string {
        if ($chat_id < 0) {
            $chat_id = substr($chat_id, 4);
            $chat_id = (int)$chat_id * -1;
        }
        $inline_message_id = pack('VVlq', $dc_id, $message_id, $chat_id, $access_hash);
        return self::base64UrlEncode($inline_message_id);
    }

    /**
     * @param string $inline_message_id
     * @return array
     */
    public static function decodeInlineMessageIdInfo(string $inline_message_id): array
    {
        $inline_message_info = self::base64UrlDecode($inline_message_id);
        $decrypted_info = unpack('Vdc_id/Vmessage_id/lchat_id/qaccess_hash', $inline_message_info);
        if ($decrypted_info['chat_id'] < 0) {
            $chat_id = '-100' . ($decrypted_info['chat_id'] * -1);
            $decrypted_info['chat_id'] = (int)$chat_id;
            return $decrypted_info;
        }
        $decrypted_info['user_id'] = $decrypted_info['chat_id'];
        unset($decrypted_info['chat_id']);
        return $decrypted_info;
    }
}