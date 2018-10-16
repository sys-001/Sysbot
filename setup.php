<?php

if (!empty($_POST)) {
    if (!is_dir("config")) mkdir("config");
    $admins = explode(",", str_replace(" ", "", $_POST["admins"]));
    $send_actions = !empty($_POST["send_actions"]) ? true : false;
    $in_maintenance = !empty($_POST["in_maintenance"]) ? true : false;
    $test_mode = !empty($_POST["test_mode"]) ? true : false;
    $long_polling = !empty($_POST["long_polling"]) ? true : false;
    $settings_array = array("admins" => $admins, "parse_mode" => $_POST["parse_mode"], "send_actions" => $send_actions, "in_maintenance" => $in_maintenance, "maintenance_msg" => $_POST["maintenance_msg"], "upgrade_password" => hash("sha512", $_POST["upgrade_password"]), "test_mode" => $test_mode, "antiflood" => array("seconds" => $_POST["seconds"], "messages_number" => $_POST["messages_number"], "ban_minutes" => $_POST["ban_minutes"], "ban_message" => $_POST["ban_message"]));
    file_put_contents("config/settings.json", json_encode($settings_array, 128));
    $test_string = $test_mode ? "/test" : "";
    $ch = curl_init("https://api.telegram.org/bot" . $_POST["token"] . $test_string . "/deleteWebhook");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
    if ($long_polling) {
        echo "Setup complete. <a href='bot.php'>Start bot.</a>";
    } else {
        $webhook_target = str_replace("http", "https", str_replace("setup.php", "bot.php", $_SERVER["REDIRECT_SCRIPT_URI"]));
        $params = array("url" => $webhook_target . "?token=" . $_POST["token"]);
        $curl = curl_init("https://api.telegram.org/bot" . $_POST["token"] . $test_string . "/setWebhook");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        echo "Setup complete. Webhook set, too:";
        echo "<br><br>";
        echo $response;
    }
    unlink("setup.php");
    exit;
}
?>
    <div style="text-align: center;">
        <h1>Sysbot Setup Menu</h1>
        <form action="#" method="post">
            <h3>Main Settings</h3>
            Bot Token:<br>
            <input type="text" name="token" title="Bot Token" required/>
            <br><br>Admin IDs (comma-separated - ex. 123456789, 234567890):<br>
            <input type="text" name="admins" title="Admin IDs" required/>
            <br><br>Parse Mode:
            <br><select name="parse_mode" title="Parse Mode">
                <option value="HTML" selected="selected">HTML</option>
                <option value="Markdown">Markdown</option>
            </select>
            <br><br><input type="checkbox" name="send_actions" checked="checked" title="Send Bot Actions"/>Send Bot
            Actions
            <br><br><input type="checkbox" name="in_maintenance" title="Maintenance Mode"/>Disable Bot (Temporary) For
            Maintenance
            <br><br>Maintenance message:<br>
            <input type="text" name="maintenance_msg"
                   value="Bot is under maintenance, please wait until I finish my work."
                   title="Maintenance Message" required/>
            <br><br><input type="checkbox" name="test_mode" title="Test Mode"/>Use Telegram Test API (Deep Telegram)
            <h3>Updates Settings</h3>
            <input type="checkbox" id="long_polling" name="long_polling" title="Long Polling"/>Use getUpdates instead of
            webhook
            <br><br><input type="submit" name="submit" value="Save settings and start bot"/><br><br>
            <b>Note: This page will be destroyed after saving settings. Have a nice day!</b>
        </form>
    </div>
<?php
exit;