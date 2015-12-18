<?php

namespace FE\testBundle\Controller;

use FE\testBundle\Form\TestType;
use FE\testBundle\Entity\Test;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class TestController extends Controller
{
    public function testAction(Request $request)
    {
        $request->headers->set('Content-Tpe', 'application/json');
        $form = $this->container->get('form.factory')->create(TestType::class, new Test());
        //$form->submit('{ "name": "test1" }');
        // return new Response();
        $form->handleRequest($request);
        return $this->render('FEtestBundle:Default:test.html.twig', array('form' => $form->createView()));
    }
}