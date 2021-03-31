<?php
namespace Oino\Services;

use \Oino\Helpers\StringHelper;
use \Oino\Exceptions\TabNotFoundException;
use \Oino\Exceptions\ExportFileNotFoundException;
use \Oino\Exceptions\ParseFileException;

class FileParser
{
    private $filePath;

    private $data;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function execute(): void
    {
        if (!file_exists($this->filePath)) {
            throw new ExportFileNotFoundException();
        }

        $fileContent = file_get_contents($this->filePath);
        $this->data = json_decode($fileContent);
    }

    public function getByTab(string $tabName): \stdClass
    {
        if (empty($this->data->tabs)) {
            throw new ParseFileException();
        }

        foreach ($this->data->tabs as $tab) {
            if (StringHelper::equals($tab->tabName, $tabName)) {
                return $tab;
            }
        }

        throw new TabNotFoundException();
    }
}