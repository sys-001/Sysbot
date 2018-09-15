<?php

class SettingsProvider{
	
	private $settings_path = "data/management/bot.settings";
	
	private function validateGeneralSection($general_section){
		if(!is_array($general_section->admins) or !is_string($general_section->administration_password)) return false;
		return true;
	}
	
	private function validateTelegramSection($telegram_section){
		if(!in_array($telegram_section->parse_mode, ["HTML", "Markdown"]) or !is_bool($telegram_section->send_actions) or !is_bool($telegram_section->use_test_api)) return false;
		return true;
	}
	
	private function validateMaintenanceSection($maintenance_section){
		if(!is_bool($maintenance_section->enabled) or !is_string($maintenance_section->message)) return false;
		return true;
	}
	
	private function validateAntiFloodSection($antiflood_section){
		if(!is_bool($antiflood_section->enabled) or !is_int($antiflood_section->seconds) or !is_int($antiflood_section->messages_number) or !is_int($antiflood_section->ban_minutes) or !is_string($antiflood_section->ban_message)) return false;
		return true;
	}
	
	function validateSettings($settings){
		if(empty($settings) or !$this->validateGeneralSection($settings->general) or !$this->validateTelegramSection($settings->telegram) or !$this->validateMaintenanceSection($settings->maintenance) or !$this->validateAntiFloodSection($settings->antiflood)) return false;
		return true;
	}
	
	private function generateGeneralSection(array $admins, string $administration_password){
		return (object) ['admins' => $admins,
		'administration_password' => hash("sha512", $administration_password),
		];
		
	}
	
	private function generateTelegramSection(string $parse_mode, bool $send_actions, bool $use_test_api){
		return (object) ['parse_mode' => $parse_mode,
		'send_actions' => $send_actions,
		'use_test_api' => $use_test_api
		];
	}
	
	private function generateMaintenanceSection(bool $enabled, string $message){
		return (object) ['enabled' => $enabled,
		'message' => $message
		];
		
	}
	
	private function generateAntiFloodSection(bool $enabled, int $seconds, int $messages_number, int $ban_minutes, string $ban_message){
		return (object) ['enabled' => $enabled,
		'seconds' => $seconds,
		'messages_number' => $messages_number,
		'ban_minutes' => $ban_minutes,
		'ban_message' => $ban_message
		];
	}
	
	function createSettings(array $admins = [], string $administration_password = "password", string $parse_mode = "HTML", bool $send_actions = true, bool $use_test_api = false, bool $maintenance_enabled = false, string $maintenance_message = "Bot in maintenance, please wait.", bool $antiflood_enabled = true, int $antiflood_seconds = 5, int $antiflood_messages_number = 5, int $antiflood_ban_minutes = 2, string $antiflood_ban_message = "Flood detected, you're banned for 2 minutes."){
		$general = $this->generateGeneralSection($admins, $administration_password);
		if(!$this->validateGeneralSection($general)) die("Invalid arguments provided");
		$telegram = $this->generateTelegramSection($parse_mode, $send_actions, $use_test_api);
		if(!$this->validateTelegramSection($telegram)) die("Invalid arguments provided");
		$maintenance = $this->generateMaintenanceSection($maintenance_enabled, $maintenance_message);
		if(!$this->validateMaintenanceSection($maintenance)) die("Invalid arguments provided");
		$antiflood = $this->generateAntiFloodSection($antiflood_enabled, $antiflood_seconds, $antiflood_messages_number, $antiflood_ban_minutes, $antiflood_ban_message);
		if(!$this->validateAntiFloodSection($antiflood)) die("Invalid arguments provided");
		$settings = ['general' => $general,
		'telegram' => $telegram,
		'maintenance' => $maintenance,
		'antiflood' => $antiflood
		];
		return (object) $settings;
	}
	
	function saveSettings($settings){
		if(!$this->validateSettings($settings)) die("Invalid settings provided");
		$result = file_put_contents($this->settings_path, serialize($settings));
		if($result === false) return false;
		return true;
	}
	
	function loadSettings(){
		$settings_file = file_get_contents($this->settings_path);
		if($settings_file === false or empty($settings_file)) die("Could not load settings");
		$settings = unserialize($settings_file);
		if(!$this->validateSettings($settings)) die("Settings file invalid");
		return $settings;
	}
	
	function __construct(string $settings_path = null){
		if(!empty($settings_path)) $this->settings_path = $settings_path;
	}
	
	
}