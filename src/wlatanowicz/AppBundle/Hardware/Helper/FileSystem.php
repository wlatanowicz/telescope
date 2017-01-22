<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Hardware\Helper;

class FileSystem
{
    public function filePutContents(string $path, string $data)
    {
        $dir = dirname($path);
        if (strlen($dir) > 0) {
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
        }
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
