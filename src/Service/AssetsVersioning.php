<?php

namespace App\Service;

use Symfony\Component\Asset\Packages;

class AssetsVersioning
{
    private Packages $packages;
    private string $publicDir;

    public function __construct(Packages $packages, $publicDir)
    {
        $this->packages = $packages;
        $this->publicDir = (string)$publicDir;
    }

    public function asset($assetPath)
    {
        $path = $this->publicDir . DIRECTORY_SEPARATOR . $assetPath;
        return $this->packages->getUrl($assetPath) . '?v=' . hash_file('md5', $path);
    }
}