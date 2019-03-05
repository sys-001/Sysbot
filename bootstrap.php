<?php /** @noinspection PhpUnhandledExceptionInspection */

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

require_once __DIR__ . '/vendor/autoload.php';

$config = Setup::createAnnotationMetadataConfiguration([__DIR__ . "/src/DBEntity"], true);
$conn = [
    'driver' => 'pdo_mysql',
    'dbname' => 'Sysbot',
    'user' => 'user',
    'password' => 'password',
    'host' => 'localhost'
];

/* //use this if you don't want to install MySQL (please, install it)
$conn = [
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/db.sqlite'
];
*/

/* //use this if you plan to use Redis as caching system (improves speed)
$redis = new Redis();
$redis->connect('localhost');
$redis->auth('foobared');
$cache_driver = new Doctrine\Common\Cache\RedisCache();
$cache_driver->setRedis($redis);
$config->setQueryCacheImpl($cache_driver);
$config->setResultCacheImpl($cache_driver);
$config->setMetadataCacheImpl($cache_driver);
*/

$entity_manager = EntityManager::create($conn, $config);
