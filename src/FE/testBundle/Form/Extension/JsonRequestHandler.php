<?php

namespace FE\testBundle\Form\Extension;

use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationRequestHandler;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class JsonRequestHandler extends HttpFoundationRequestHandler
{
    /**
     * {@inherit}
     */
    public function handleRequest(FormInterface $form, $request = null)
    {
        if (!$request instanceof Request) {
            throw new UnexpectedTypeException($request, 'Symfony\Component\HttpFoundation\Request');
        }

        if ($request->headers->get('content-type') !== 'application/json') {
            parent::handleRequest($form, $request);
            return;
        }

        $form->submit($request->getContent());
    }
}