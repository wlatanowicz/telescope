<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Helper;

class FileSystem
{
    public function filePutContents(string $path, string $data)
    {
        file_put_contents($path, $data);
    }

    public function fileGetContents(string $path): string
    {
        return file_get_contents($path);
    }

    public function tempName(string $temp): string
    {
        return tempnam($temp, "");
    }

    public function unlink(string $path)
    {
        unlink($path);
    }
}
