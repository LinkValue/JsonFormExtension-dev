<?php

namespace FE\testBundle\Controller;

use FE\testBundle\Form\TestType;
use FE\testBundle\Entity\Test;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
    public function testAction()
    {
        $form = $this->container->get('form.factory')->create(TestType::class, new Test());
        return $this->render('FEtestBundle:Default:test.html.twig', array('form' => $form->createView()));
    }
}