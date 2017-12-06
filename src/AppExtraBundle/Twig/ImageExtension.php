<?php

namespace AppExtraBundle\Twig;

use AppExtraBundle\Service\ImageManager;

/**
 * ImageExtension
 */
class ImageExtension extends \Twig_Extension
{
    /**
     * @var ImageManager
     */
    private $imageHandling;

    /**
     * @param ImageManager $imageHandling
     */
    public function __construct(ImageManager $imageHandling)
    {
        $this->imageHandling = $imageHandling;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('image', array($this, 'image'), array('is_safe' => array('html'))),
        );
    }

    /**
     * @param string $path
     *
     * @return object
     */
    public function image($path)
    {
        return $this->imageHandling->open($path);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'image';
    }
}
