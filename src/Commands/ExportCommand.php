<?php
namespace Oino\Commands;

use Oino\Args;
use \Oino\Constants\CommandConst;
use \Oino\Helpers\CliHelper;
use \Oino\Services\DBFormatter;
use \Oino\Services\FileUploader;

class ExportCommand implements ICommand
{
    public function handle(Args $args)
    {
        $dbFormatter = new DBFormatter();
        $fileUploader = new FileUploader();
        $directory = $args->getValue(CommandConst::EXPORT_DIR);
        $fileName = $args->getValue(CommandConst::EXPORT_FILE_NAME, 'oino-' . time());
        $filePath = $directory . DIRECTORY_SEPARATOR . "$fileName.json";
        if($fileUploader->uploadJson($dbFormatter->toJson(), $filePath)) {
            CliHelper::write("Exported to: $filePath", CliHelper::SUCCESS);
        } else {
            CliHelper::write("Can`t save data to $filePath", CliHelper::ERROR);
        }
    }
}