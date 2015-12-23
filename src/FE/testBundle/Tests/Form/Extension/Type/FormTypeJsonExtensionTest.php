<?php

namespace FE\testBundle\Tests\Form\Extension\Type;

use FE\testBundle\Form\Extension\Type\FormTypeJsonExtension;
use FE\testBundle\Form\Extension\JsonExtensionListener;
use Symfony\Component\Form\RequestHandlerInterface;

class FormTypeJsonExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FormTypeJsonExtension
     */
    private $formTypeJsonExtension;

    /**
     * @var RequestHandlerInterface
     */
    private $requestHandler;

    public function setUp()
    {
        $this->requestHandler = \Phake::mock('Symfony\Component\Form\RequestHandlerInterface');
        $this->formTypeJsonExtension = new FormTypeJsonExtension($this->requestHandler);
    }

    public function testExtendedTypeShouldBeFormType()
    {
        $this->assertEquals('Symfony\Component\Form\Extension\Core\Type\FormType', $this->formTypeJsonExtension->getExtendedType());
    }

    public function testShouldAddNewOptionJsonFormatAsFalse()
    {
        $resolver = \Phake::mock('Symfony\Component\OptionsResolver\OptionsResolver');

        $this->formTypeJsonExtension->configureOptions($resolver);

        \Phake::verify($resolver, \Phake::times(1))->setDefault('json_format', false);
        \Phake::verify($resolver, \Phake::times(1))->setAllowedTypes('json_format', 'boolean');
    }

    public function testShouldAddRequestHandlerToBuilder()
    {
        $builder = \Phake::mock('Symfony\Component\Form\FormBuilderInterface');

        $this->formTypeJsonExtension->buildForm($builder, ['json_format' => true]);

        \Phake::verify($builder, \Phake::times(1))->setRequestHandler($this->requestHandler);
    }

    public function testShouldBindEventListenerWhenJsonFormatOptionIsTrue()
    {
        $builder = \Phake::mock('Symfony\Component\Form\FormBuilderInterface');

        $this->formTypeJsonExtension->buildForm($builder, ['json_format' => true]);

        \Phake::verify($builder, \Phake::times(1))->addEventSubscriber(new JsonExtensionListener());
    }

    public function testShouldNotBindEventListenerWhenJsonFormatOptionIsFalse()
    {
        $builder = \Phake::mock('Symfony\Component\Form\FormBuilderInterface');

        $this->formTypeJsonExtension->buildForm($builder, ['json_format' => false]);

        \Phake::verify($builder, \Phake::never())->addEventSubscriber(new JsonExtensionListener());
    }
}
