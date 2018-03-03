<?php

function encrypt($aes_key, $aes_iv, $string) {
    $output = false;
    $encrypt_method = 'AES-256-CBC';
    $key = hash('sha256', $aes_key);
    $iv = substr(hash('sha256', $aed_iv), 0, 16);
	$output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    return $output;
}

if(!empty($_POST)){
	if(!file_exists("DATA")){
		mkdir("DATA");
		mkdir("DATA/management");
		mkdir("DATA/users");
		mkdir("DATA/groups");
		mkdir("DATA/antiflood");
    }
	$admins = explode(",", str_replace(" ", "", $_POST["admins"]));
	$send_actions = !empty($_POST["send_actions"]) ? true : false;
	$in_maintenance = !empty($_POST["in_maintenance"]) ? true : false;
	$test_mode = !empty($_POST["test_mode"]) ? true : false;
	$getupdates_enabled = !empty($_POST["getupdates"]) ? true : false;
	$getupdates_key = $_POST["key"];
	$getupdates_iv = $_POST["iv"];
	$getupdates_token = !empty($getupdates_key) && !empty($getupdates_iv) ? encrypt($getupdates_key, $getupdates_iv, str_replace("bot", "", $_POST['token'])) : "";
	$settings_array = array("admins" => $admins, "parse_mode" => $_POST["parse_mode"], "send_actions" => $send_actions, "in_maintenance" => $in_maintenance, "maintenance_msg" => $_POST["maintenance_msg"], "upgrade_password" => hash("sha512", $_POST["upgrade_password"]), "test_mode" => $test_mode, "getUpdates" => array("enabled" => $getupdates_enabled, "token" => $getupdates_token), "antiflood" => array("seconds" => $_POST["seconds"], "messages_number" => $_POST["messages_number"], "ban_minutes" => $_POST["ban_minutes"], "ban_message" => $_POST["ban_message"]));
	file_put_contents("DATA/management/settings.json", json_encode($settings_array, 128));
	$test_string = $test_mode ? "/test" : "";
	$ch = curl_init("https://api.telegram.org/bot".$_POST["token"].$test_string."/deleteWebhook");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  	curl_exec($ch);
  	curl_close($ch);
	if(!empty($getupdates_token)){
		$decipher = "<?php

function decrypt(\$aes_key, \$aes_iv, \$string) {
    \$output = false;
    \$encrypt_method = 'AES-256-CBC';
    \$key = hash('sha256', \$aes_key);
    \$iv = substr(hash('sha256', \$aed_iv), 0, 16);
    \$output = openssl_decrypt(base64_decode(\$string), \$encrypt_method, \$key, 0, \$iv);
    return \$output;
}

\$getUpdates_token = decrypt('$getupdates_key', '$getupdates_iv', \$settings->getUpdates->token);";
		file_put_contents("crypto.php", $decipher);
		sleep(1);
		echo "Setup complete. <a href='bot.php'>Start bot in getUpdates mode.</a>";
	}
	else{
		$webhook_target = str_replace("http", "https", str_replace("setup.php", "bot.php", $_SERVER["REDIRECT_SCRIPT_URI"]));
		$params = array("url" => $webhook_target."?token=".$_POST["token"]);
		$curl = curl_init("https://api.telegram.org/bot".$_POST["token"].$test_string."/setWebhook");
  		curl_setopt($curl, CURLOPT_POST, true);
  		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
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
<center>
	<script>
		function toggle() {
     	var checkbox = document.getElementById("getupdates");
     	var key = document.getElementById("key");
        var iv = document.getElementById("iv");
     	updateKey = checkbox.checked ? key.disabled=false : key.disabled=true;
        updateIv = checkbox.checked ? iv.disabled=false : iv.disabled=true;
		}
	</script>
	<h1>Sysbot Setup Menu</h1>
	<form action="#" method="post">
        <h3>Main Settings</h3>
        	Bot Token:<br>
			<input type="text" name="token" required/>
            <br><br>Admin IDs (comma-separated - ex. 123456789, 234567890):<br>
			<input type="text" name="admins" required/>
            <br><br>Parse Mode:
            <br><select name="parse_mode">
   			<option value="HTML" selected="selected">HTML</option>
   			<option value="Markdown">Markdown</option>
  			</select>
            <br><br><input type="checkbox" name="send_actions" checked="checked" />Send Bot Actions
            <br><br><input type="checkbox" name="in_maintenance"/>Disable Bot (Temporary) For Maintenance
            <br><br>Maintenance message:<br>
			<input type="text" name="maintenance_msg" value="Bot is under maintenance, please wait until I finish my work." required/>
            <br><br>Upgrade Password:<br>
			<input type="password" name="upgrade_password" minlength="8" required/>
            <br><br><input type="checkbox" name="test_mode"/>Use Telegram Test API (Deep Telegram)
            <h3>Updates Settings</h3>
            <input type="checkbox" id="getupdates" onClick="toggle()" name="getupdates"/>Use getUpdates instead of webhook
			<br><br>Token Encryption Password #1:<br>
			<input type="password" id="key" name="key" minlength="8" disabled required/>
			<br><br>Token Encryption Password #2:<br>
			<input type="password" id="iv" name="iv" minlength="8" maxlength="16" disabled required/>
            <h3>Anti-flood settings</h3>
            Minimum seconds to trigger Anti-flood:<br>
            <input type="number" name="seconds" value="1" required/>
            <br><br>Minimum messages to trigger Anti-flood:<br>
            <input type="number" name="messages_number" value="3" required/>
            <br><br>Ban duration (minutes):<br>
            <input type="number" name="ban_minutes" value="2" required/>
            <br><br>Ban message:<br>
            <input type="text" name="ban_message" value="Flood detected, you're banned from using the bot for 2 minutes." required/>
			<br><br><input type="submit" name="submit" value="Save settings and start bot" /><br><br>
        	<b>Note: This page will be destroyed after saving settings. Have a nice day!</b>
		</form>
	</center>
<?php
exit;