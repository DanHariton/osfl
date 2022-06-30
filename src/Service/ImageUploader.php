<?php

namespace App\Service;

use DateTime;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Exception;

class ImageUploader
{
    const TYPE_1280x720 = 1;
    const TYPE_1600x900 = 2;
    const TYPE_1920x720 = 3;
    const TYPE_550x400 = 4;
    private static $size_1280x720 = [1280, 720];
    private static $size_1600x900 = [1600, 900];
    private static $size_1920x720 = [1920, 720];
    private static $size_550x400 = [550, 400];

    private string $targetDirectoryImg;
    private Filesystem $filesystem;
    private ImageResizer $resizer;

    public function __construct(string $targetDirectoryImg, Filesystem $filesystem, ImageResizer $resizer)
    {
        $this->targetDirectoryImg = $targetDirectoryImg;
        $this->resizer = $resizer;
        $this->filesystem = $filesystem;
    }

    public function remove($fileName)
    {
        $this->filesystem->remove($this->getTargetDirectory() . DIRECTORY_SEPARATOR . $fileName);
    }

    public function upload(UploadedFile $file, int $imageType = 0)
    {
        $fileName = (new DateTime())->getTimestamp() . '_' . bin2hex(random_bytes(10)) . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);

            $newPath = $this->getTargetDirectory() . DIRECTORY_SEPARATOR . $fileName;
            $this->resizer->setImage($newPath);
            switch ($imageType) {
                case self::TYPE_1280x720: $size = self::$size_1280x720; break;
                case self::TYPE_1600x900: $size = self::$size_1600x900; break;
                case self::TYPE_1920x720: $size = self::$size_1920x720; break;
                case self::TYPE_550x400: $size = self::$size_550x400; break;
                default: $size = self::$size_1280x720;
            }
            $this->resizer->resizeTo(...$size);
            $this->resizer->save($newPath);

        } catch (FileException $e) {
            throw new Exception('Soubor se nepodařilo uložit, ' . $e->getMessage());
        }

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectoryImg;
    }
}