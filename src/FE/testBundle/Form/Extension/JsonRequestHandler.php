<?php

namespace FE\testBundle\Form\Extension;

use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationRequestHandler;
use Symfony\Component\Form\RequestHandlerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class JsonRequestHandler implements RequestHandlerInterface
{
    /**
     * @var HttpFoundationRequestHandler
     */
    private $httpFoundationRequestHandler;

    public function __construct(HttpFoundationRequestHandler $httpFoundationRequestHandler)
    {
        $this->httpFoundationRequestHandler = $httpFoundationRequestHandler;
    }
    /**
     * {@inheritdoc}
     */
    public function handleRequest(FormInterface $form, $request = null)
    {
        if (!$request instanceof Request) {
            throw new UnexpectedTypeException($request, 'Symfony\Component\HttpFoundation\Request');
        }

        if ($request->getContentType() !== 'json') {
            $this->httpFoundationRequestHandler->handleRequest($form, $request);

            return;
        }

        $form->submit($request->getContent());
    }
}
