<?php
namespace Oino;

use Oino\Constants\CommandConst;
use Symfony\Component\Yaml\Yaml;

class Settings
{
    private $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public static function load(): Settings
    {
        $args = Args::create();
        $configPath = $args->getValue(CommandConst::CONFIG_SHORT_OPTION, __DIR__ . DIRECTORY_SEPARATOR . 'config/oino.yaml');
        if(!is_file($configPath)) {
            throw new \Exception('Config file not found');
        }

        $config = Yaml::parseFile($configPath);
        $settings = $config['settings'];

        return new static($settings);
    }

    public function get(string $setting, $default = null)
    {
        if (array_key_exists($setting, $this->settings)) {
            return $this->settings[$setting];
        }

        return $default;
    }

    public function isEmpty(string $setting): bool
    {

        return empty($this->settings[$setting]);
    }
}