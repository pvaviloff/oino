<?php
namespace Oino;

use \Oino\Constants\CommandConst;
use Symfony\Component\Yaml\Yaml;

class Args
{
    private $args;

    public function __construct(array $args)
    {
        $this->args = $args;
    }

    public static function create(): Args
    {
        $sortOpts = '';
        $sortOpts .= CommandConst::COMMAND_SHORT_OPTION . CommandConst::REQUIRED_SIGN;
        $sortOpts .= CommandConst::CONFIG_SHORT_OPTION . CommandConst::REQUIRED_SIGN;

        $longOpts = [
            CommandConst::FILE_OPTION . CommandConst::REQUIRED_SIGN,
            CommandConst::TAB_OPTION . CommandConst::REQUIRED_SIGN,
            CommandConst::PROJECT_PATH_OPTION . CommandConst::REQUIRED_SIGN,
            CommandConst::EXPORT_DIR . CommandConst::REQUIRED_SIGN,
            CommandConst::EXPORT_FILE_NAME . CommandConst::REQUIRED_SIGN,
        ];

        $options = getopt($sortOpts, $longOpts);
        if(!empty($options[CommandConst::CONFIG_SHORT_OPTION]) && is_file($options[CommandConst::CONFIG_SHORT_OPTION])) {
            $configPath = $options[CommandConst::CONFIG_SHORT_OPTION];
        } else {
            $configPath = __DIR__ . DIRECTORY_SEPARATOR . 'config/oino.yaml';
        }

        $config = Yaml::parseFile($configPath);
        $args = $config['args'];
        foreach ($args as $command => $value) {
            if (!empty($value) && empty($options[$command])) {
                $options[$command] = $value;
            }
        }

        return new static($options);
    }

    public function commandExist(string $command): bool
    {
        return array_key_exists($command, $this->args);
    }

    public function getValue(string $command, $default = null)
    {
        if ($this->commandExist($command)) {
            return $this->args[$command];
        }

        return $default;
    }
}