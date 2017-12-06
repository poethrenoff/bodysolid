<?php

namespace AppExtraBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class AdminUploadManager
 */
class AdminUploadManager
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * AdminUploadManager constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param UploadedFile $file
     * @param string $directory
     * @param string $alias
     * @return string
     */
    public function upload(UploadedFile $file, string $directory = null, $alias = null)
    {
        $file->move($directory ?: $this->getParams()['directory'], $file->getClientOriginalName());

        return $this->getFilePath($file->getClientOriginalName(), $alias ?: $this->getParams()['alias']);
    }

    /**
     * @param string $class
     * @return array
     */
    public function getFields(string $class)
    {
        if (isset($this->getParams()['entities'][$class])) {
            return $this->getParams()['entities'][$class];
        }
        return [];
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->container->getParameter('upload');
    }

    /**
     * @param string $fileName
     * @param string $alias
     * @return string
     */
    public function getFilePath(string $fileName, string $alias)
    {
        return '/' . trim($alias, '/') . '/' . $fileName;
    }
}
