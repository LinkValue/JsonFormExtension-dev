<?php

namespace FE\testBundle\Form\Extension\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\HttpFoundation\Type\FormTypeHttpFoundationExtension;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationRequestHandler;
use Symfony\Component\Form\RequestHandlerInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FE\testBundle\Form\Extension\JsonExtensionListener;

class FormTypeJsonExtension extends FormTypeHttpFoundationExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (array_key_exists('json_format', $options) && $options['json_format']) {
            $builder->addEventSubscriber(new JsonExtensionListener());
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('json_format', false);
    }

}