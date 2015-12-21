<?php

namespace FE\testBundle\Tests\Form\Extension\Type;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class FormTypeJsonExtensionIntegrationTest extends KernelTestCase
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Form
     */
    private $form;

    public function setUp()
    {
        static::bootKernel([]);
        $this->container = static::$kernel->getContainer();
        $this->form = $this->container->get('form.factory')
            ->createBuilder(
                'Symfony\Component\Form\Extension\Core\Type\FormType',
                null,
                ['json_format' => true]
                )
            ->add('name', TextType::class)
            ->add('lastname', TextType::class)
            ->getForm();
    }

    public function testSubmitValidJsonShouldPopulateForm()
    {
        $this->form->submit('{ "name": "test1" }');
        $this->assertEquals(['name' => 'test1', 'lastname' => null], $this->form->getData());
        $this->assertEquals(['name' => 'test1', 'lastname' => null], $this->form->getNormData());
        $this->assertEquals(['name' => 'test1', 'lastname' => null], $this->form->getViewData());
    }

    public function testSubmitInvalidJsonShouldThrowException()
    {
        $this->setExpectedExceptionRegExp(
          'Symfony\Component\HttpKernel\Exception\HttpException',
          '/^Invalid submitted json data, error (.*) : (.*), json : invalid json$/'
        );
        $this->form->submit('invalid json');
    }

    public function testRequestWithValidJsonShouldPopulateForm()
    {
        $request = $this->getRequest('{ "name": "test1" }');
        $this->form->handleRequest($request);
        $this->assertEquals(['name' => 'test1', 'lastname' => null], $this->form->getData());
        $this->assertEquals(['name' => 'test1', 'lastname' => null], $this->form->getNormData());
        $this->assertEquals(['name' => 'test1', 'lastname' => null], $this->form->getViewData());
    }

    public function testRequestWithInvalidJsonShouldTHrowException()
    {
        $this->setExpectedExceptionRegExp(
          'Symfony\Component\HttpKernel\Exception\HttpException',
          '/^Invalid submitted json data, error (.*) : (.*), json : invalid json$/'
        );
        $request = $this->getRequest('invalid json');
        $this->form->handleRequest($request);
    }

    protected function getRequest($content)
    {
        return new Request(
            [],
            [],
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $content
        );
    }
}
