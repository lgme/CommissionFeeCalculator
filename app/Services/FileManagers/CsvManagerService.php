<?php

declare(strict_types = 1);

namespace App\Services\FileManagers;

use App\Enums\FileTypes;

class CsvManagerService extends FileManagerService
{
    public function read(): array
    {
        if (!$this->fileExists()) {
            return ['error' => 'File not found.'];
        }

        if ($this->getFileExtension() !== FileTypes::Csv->value) {
            return ['error' => 'File type not supported.'];
        }

        $csvData = [];
        if (($handle = fopen($this->filePath, 'r')) !== false) {
            $firstLine = true;
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if ($firstLine) {
                    $data[0] = preg_replace('/^\xEF\xBB\xBF/', '', $data[0]);
                    $firstLine = false;
                }
                $csvData[] = $data;
            }
            fclose($handle);
        }

        return $csvData;
    }
}