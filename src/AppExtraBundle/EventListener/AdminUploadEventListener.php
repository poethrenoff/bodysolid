<?php

namespace AppExtraBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Sonata\AdminBundle\Event\PersistenceEvent;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use AppExtraBundle\Service\AdminUploadManager;

class AdminUploadEventListener implements EventSubscriberInterface
{
    /**
     * @var AdminUploadManager
     */
    protected $uploadManager;

    /**
     * @var PropertyAccessorInterface
     */
    protected $accessor;

    /**
     * UploadEventListener constructor
     *
     * @param AdminUploadManager $uploadManager
     */
    public function __construct(AdminUploadManager $uploadManager)
    {
        $this->uploadManager = $uploadManager;
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'sonata.admin.event.persistence.pre_update' => 'upload',
            'sonata.admin.event.persistence.pre_persist' => 'upload',
        ];
    }

    /**
     * @param PersistenceEvent $event
     */
    public function upload(PersistenceEvent $event)
    {
        $object = $event->getObject();
        foreach ($this->uploadManager->getFields(get_class($object)) as $fieldName => $fieldDesc) {
            $file = $this->accessor->getValue($object, $fieldDesc['fileField']);
            if ($file) {
                $filePath = $this->uploadManager->upload($file, $fieldDesc['directory'], $fieldDesc['alias']);

                $this->accessor->setValue($object, $fieldName, $filePath);
                $this->accessor->setValue($object, $fieldDesc['fileField'], null);
            }
        }
    }
}
