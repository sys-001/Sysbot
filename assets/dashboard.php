<?php /** @noinspection ALL */

if (!isset($this)) exit;
if (true == $_GET['flush_peers']) {
    $this->logger->log(sprintf("TelegramBot: ATTENTION! Peers DB has been reset by %s!", $_SERVER['REMOTE_ADDR'] ?? "NULL"));
    $this->resetPeers();
}
$bot_info = $this->getMe()->result;
$webhook_info = $this->getWebhookInfo()->result;
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Sysbot - Dashboard</title>
        <link rel="stylesheet" type="text/css" href="assets/styles.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>

    <div class="container">
        <div class="hero">
            <h1 class="name"><strong><?= $bot_info->first_name ?? 'Sysbot' ?></strong></h1>
            <span class="ver">Version <?= self::BOT_VERSION ?></span>
            <span class="email"><a href="https://github.com/sys-001/Sysbot">https://github.com/sys-001/Sysbot</a></span>

            <h2 class="lead">
                <?php
                $request = $this->client->get('https://raw.githubusercontent.com/sys-001/Sysbot/master/.ver');
                $response = $request->getBody() ?? null;
                if (version_compare(self::BOT_VERSION, $response, '>=')) {
                    echo 'Sysbot is up-to-date.';
                } else {
                    printf('Sysbot v%s is available, please upgrade.', $response);
                }
                ?>
            </h2>
        </div>
    </div>

    <div class="container">

        <div class="sections">
            <h2 class="section-title">Telegram Bot API</h2>

            <div class="list-card">
                <span class="sect">General</span>
                <div>
                    <h3>ID: <?= $bot_info->id ?? 'N/A' ?></h3>
                    <h3>Username: @<?= $bot_info->username ?? 'N/A' ?></h3>
                    <span><a href="tg://resolve?domain=<?= $bot_info->username ?? 'telegram' ?>">Open Chat</a></span>
                </div>
            </div>

            <div class="list-card">
                <span class="sect">Webhook</span>
                <div>
                    <h3>URL: <?= empty($webhook_info->url) ? 'N/A (Bot in polling mode)' : $webhook_info->url ?></h3>
                    <h3>Pending updates: <?= $webhook_info->pending_update_count ?? 'N/A' ?></h3>
                    <h3>Last error
                        date: <?= null != $webhook_info->last_error_date ? date('d/m/Y H:i:s', $webhook_info->last_error_date) : 'N/A' ?></h3>
                    <span><a href="https://api.telegram.org/bot<?= $this->token ?>/deleteWebhook" style="color:red">Delete webhook</a></span>
                </div>
            </div>


        </div>
        <div class="sections">
            <h2 class="section-title">Backend</h2>

            <div class="list-card">
                <span class="sect">Peer DB</span>
                <div>
                    <h3>Users: <?= $this->entity_manager->getRepository('TelegramBot\DBEntity\User')->count([]) ?></h3>
                    <h3>Chats: <?= $this->entity_manager->getRepository('TelegramBot\DBEntity\Chat')->count([]) ?></h3>
                    <span><a href="bot.php?<?= $_SERVER['QUERY_STRING'] ?>&flush_peers=true" style="color:red">Reset peer DB</a></span>
                </div>
            </div>

            <div class="list-card">
                <span class="sect">Settings</span>
                <div>
                    <h3>Check
                        IP: <?= $this->settings->getGeneralSection()->getCheckIp() ? "Enabled" : "Disabled" ?></h3>
                    <h3>Use Test
                        DC: <?= $this->settings->getTelegramSection()->getUseTestApi() ? "Enabled" : "Disabled" ?></h3>
                    <h3>Maintenance
                        mode: <?= $this->settings->getMaintenanceSection()->isEnabled() ? "Enabled" : "Disabled" ?></h3>
                </div>
            </div>

            <div class="list-card">
                <span class="sect">Anti-flood</span>
                <div>
                    <h3>Limit: <?= $this->settings->getAntifloodSection()->getMessagesNumber() ?> messages</h3>
                    <h3>Time range: <?= $this->settings->getAntifloodSection()->getMessagesSeconds() ?> seconds</h3>
                    <h3>Ban duration: <?= $this->settings->getAntifloodSection()->getBanSeconds() ?> seconds</h3>
                </div>
            </div>
        </div>
    </div>

    </body>
    </html>

<?php exit;
