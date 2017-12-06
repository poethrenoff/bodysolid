<?php

namespace AppExtraBundle\Entity;

use Gregwar\Image\Image as BaseImage;

/**
 * Image manipulation class.
 */
class Image extends BaseImage
{
    /**
     * @var string
     */
    protected $fileCallback = null;

    /**
     * Defines the callback to call to compute the new filename.
     */
    public function setFileCallback($fileCallback)
    {
        $this->fileCallback = $fileCallback;
    }

    /**
     * When processing the filename, call the callback.
     */
    protected function getFilename($filename)
    {
        $callback = $this->fileCallback;

        if (null === $callback || substr($filename, 0, 1) == '/') {
            return $filename;
        }

        return $callback($filename);
    }
}