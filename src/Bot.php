<?php
/** @noinspection PhpUndefinedMethodInspection */


namespace Sysbot;


use Generator;
use GuzzleHttp\Client;
use InvalidArgumentException;
use stdClass;
use Sysbot\Exceptions\SecurityException;

/**
 * Class Bot
 * @package Sysbot
 */
class Bot
{

    /**
     * @var string $token
     */
    private $token;
    /**
     * @var Client $client
     */
    private $client;
    /**
     * @var stdClass|null $update
     */
    private $update;

    /**
     * Bot constructor.
     * @param string $token
     * @param int $timeout
     * @param string $base_uri
     * @throws InvalidArgumentException
     * @throws SecurityException
     */
    public function __construct(string $token, int $timeout = 0, string $base_uri = 'https://api.telegram.org/bot')
    {
        $token = str_replace('bot', '', $token);
        if (preg_match('/([0-9]{7,10}):AA([0-9a-zA-Z_-]{32,34})/', $token) !== 1) {
            throw new InvalidArgumentException('Invalid token provided.');
        }
        $this->token = $token;
        $this->client = new Client(['base_uri' => $base_uri, 'timeout' => $timeout]);
        $input = file_get_contents('php://input');
        $update = json_decode($input ?? '[]');
        $secret = $update->secret ?? null; //use your token as a secret for a custom request, this will override the IP validation
        if (php_sapi_name() != 'cli' and !RequestUtil::checkRequestOrigin(
                $_SERVER['REMOTE_ADDR']
            ) and $secret !== $token) {
            throw new SecurityException('IP validation failed.');
        }
        if (!$this->getMe()->ok) {
            $this->token .= 'test/'; //test DC, maybe?
            if (!$this->getMe()->ok) {
                throw new InvalidArgumentException('Invalid token provided.'); //okay, maybe not
            }
        }
        $this->update = $update;
    }

    /**
     * @return Generator
     */
    public function iterUpdates(): Generator
    {
        if (!empty($this->update)) {
            yield $this->update;
            return;
        }
        $offset = 0;
        while (true) {
            $response = $this->getUpdates(['offset' => $offset]);
            if (!$response->ok) {
                break;
            }
            foreach ($response->result as $update) {
                yield $update;
            }
            if (!empty($update)) {
                $offset = $update->update_id + 1;
            }
        }
    }

    /**
     * @param string $name
     * @param array|null $params
     * @return stdClass
     */
    public function sendRequest(string $name, ?array $params = []): stdClass
    {
        $endpoint = $this->token . '/' . $name;
        $params['reply_markup'] = empty($params['reply_markup']) ? null : json_encode($params['reply_markup']);
        $params['results'] = empty($params['results']) ? null : json_encode($params['results']);
        $params = array_filter(
            $params,
            function ($value) {
                return null != $value;
            }
        );
        if (empty($params)) {
            $response = $this->client->get($endpoint);
            return json_decode($response->getBody());
        }
        $multipart = [];
        foreach ($params as $param => $value) {
            $multipart[] = [
                'name' => $param,
                'contents' => $value
            ];
        }
        $response = $this->client->post(
            $endpoint,
            [
                'multipart' => $multipart,
                'headers' => ['Connection' => 'Keep-Alive', 'Keep-Alive' => '120']
            ]
        );
        return json_decode($response->getBody());
    }

    /**
     * @param string $name
     * @param array|null $params
     * @return stdClass
     */
    public function __call(string $name, ?array $params = []): stdClass
    {
        return $this->sendRequest($name, $params[0] ?? []);
    }
}