<?php

namespace FE\testBundle\Tests\Form\Extension;

use FE\testBundle\Form\Extension\JsonExtensionListener;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class JsonExtensionListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JsonExtensionListener
     */
    private $jsonExtensionListener;

    /**
     * @var FormEvent
     */
    private $formEvent;

    public function setUp()
    {
        $this->jsonExtensionListener = new JsonExtensionListener();
        $this->formEvent = \Phake::mock('Symfony\Component\Form\FormEvent');
    }

    public function testShouldSubscribedPreSubmitEvents()
    {
        $this->assertEquals(
            array(
                FormEvents::PRE_SUBMIT => 'onPreSubmit',
            ),
            JsonExtensionListener::getSubscribedEvents()
        );
    }

    /**
     * @dataProvider jsonProvider
     */
    public function testValidJsonShouldBeDecoded($json, $data)
    {
        \Phake::when($this->formEvent)
            ->getData()
            ->thenReturn($json);

        $this->jsonExtensionListener->onPreSubmit($this->formEvent);

        \Phake::verify($this->formEvent, \Phake::times(1))->setData($data);
    }

    public function testInvalidJsonShouldThrowInvalidArgumentExceptionError()
    {
        $this->setExpectedExceptionRegExp(
          'InvalidArgumentException',
          '/^Invalid submitted json data, error (.*) : (.*), json : invalid json$/'
        );
        $json = 'invalid json';

        \Phake::when($this->formEvent)
            ->getData()
            ->thenReturn($json);

        $this->jsonExtensionListener->onPreSubmit($this->formEvent);
    }

    public function testEventWithoutStringShouldThrowInvalidArgumentExceptionError()
    {
        $this->setExpectedExceptionRegExp(
          'InvalidArgumentException',
          '/^Invalid argument: the submitted variable must be a string when you enable the json_format option.$/'
        );
        $json = ['invalid json'];

        \Phake::when($this->formEvent)
            ->getData()
            ->thenReturn($json);

        $this->jsonExtensionListener->onPreSubmit($this->formEvent);
    }

    public function jsonProvider()
    {
        return array(
            array(
                '{ "name": "test" }',
                array('name' => 'test'),
            ),
            array(
                '{ "name": "Robert", "lastname": "Michel", "parent": { "name": "Michel", "lastname": "Robert" } }',
                array(
                    'name' => 'Robert',
                    'lastname' => 'Michel',
                    'parent' => array(
                        'name' => 'Michel',
                        'lastname' => 'Robert',
                    ),
                ),
            ),
        );
    }
}
