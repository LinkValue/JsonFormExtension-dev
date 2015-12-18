<?php

namespace FE\testBundle\Form\Extension;

use Symfony\Component\Form\AbstractExtension;
use FE\testBundle\Form\Extension\Type;

class JsonExtension extends AbstractExtension
{
    public function loadTypeExtension()
    {
        new \FE\testBundle\Form\Extension\Type\FormTypeJsonExtension();
        return array(
          new  FomTypeJsonExtension()
        );
    }
}