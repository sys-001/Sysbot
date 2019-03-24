<?php


namespace TelegramBot\Localization;


use TelegramBot\Exception\LocalizationProviderException;

/**
 * Class LocalizationProvider
 * @package TelegramBot\Localization
 */
class LocalizationProvider
{

    /**
     * @var string
     */
    private $fallback_locale;

    /**
     * @var Dictionary[]
     */
    private $languages;

    /**
     * LocalizationProvider constructor.
     * @param string $fallback_locale
     * @param string $file_path
     * @throws LocalizationProviderException
     */
    function __construct(string $fallback_locale, string $file_path)
    {
        $this->fallback_locale = $this->addLanguage($fallback_locale, $file_path);
    }

    /**
     * @param string $locale
     * @param string $file_path
     * @return string
     * @throws LocalizationProviderException
     */
    public function addLanguage(string $locale, string $file_path): string
    {
        $locale = $this->parseLocale($locale);
        if (!file_exists($file_path)) {
            throw new LocalizationProviderException('Dictionary file not found');
        }
        $language = new Dictionary($file_path);
        if ($this->fallback_locale == $locale) {
            return $locale;
        }
        $this->languages[$locale] = $language;
        return $locale;
    }

    /**
     * @param string $locale
     * @return string
     * @throws LocalizationProviderException
     */
    private function parseLocale(string $locale): string
    {
        $locale = trim($locale);
        $locale = strtolower($locale);
        if (!ctype_alpha($locale)) {
            throw new LocalizationProviderException('Invalid locale provided');
        }
        return substr($locale, 0, 2);
    }

    /**
     * @param string $locale
     * @param string $key
     * @return string
     * @throws LocalizationProviderException
     */
    public function getLanguageField(string $locale, string $key): string
    {
        $language = $this->getLanguage($locale);
        $value = $language->$key ?? null;
        if (empty($value)) {
            $error_message = sprintf("Translation for key '%s' not found", $key);
            throw new LocalizationProviderException($error_message);
        }
        return $value;
    }

    /**
     * @param string $locale
     * @return \stdClass
     * @throws LocalizationProviderException
     */
    public function getLanguage(string $locale): \stdClass
    {
        $locale = $this->parseLocale($locale);
        $dictionary = $this->languages[$locale] ?? $this->languages[$this->fallback_locale];
        /* @var Dictionary $dictionary */
        return $dictionary->getFields();
    }

    /**
     * @return array
     */
    public function getLanguages(): array
    {
        return array_keys($this->languages);
    }

}