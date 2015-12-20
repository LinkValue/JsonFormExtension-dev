<?php

namespace FE\testBundle\Form\Extension;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class JsonExtensionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SUBMIT => 'onPreSubmit',
            );
    }

    public function onPreSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $baseData = $data;
        $data = @json_decode($data, true);
        if (null === $data) {
            throw new HttpException(400, sprintf(
                'Invalid submitted json data, error %s : %s, json : %s',
                json_last_error(),
                json_last_error_msg(),
                $baseData
            ));
        }
        $event->setData($data);
    }
}
