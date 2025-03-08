<?php

declare(strict_types = 1);

namespace App\Services\FileManagers;

abstract class FileManagerService
{
    protected string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Read the content of the file.
     *
     * @return mixed
     */
    abstract public function read(): mixed;

    /**
     * Check if the file exists.
     *
     * @return bool
     */
    protected function fileExists(): bool
    {
        return file_exists($this->filePath);
    }

    /**
     * Get the file extension.
     *
     * @return string
     */
    protected function getFileExtension(): string
    {
        return pathinfo($this->filePath, PATHINFO_EXTENSION);
    }
}