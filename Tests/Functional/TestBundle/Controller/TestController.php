<?php

namespace Irozgar\GulpRevVersionsBundle\Tests\Functional\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
    public function testAction()
    {
        return $this->render('test.html.twig');
    }
}
