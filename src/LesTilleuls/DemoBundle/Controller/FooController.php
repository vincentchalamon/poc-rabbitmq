<?php

namespace LesTilleuls\DemoBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use LesTilleuls\DemoBundle\Entity\Foo;
use LesTilleuls\DemoBundle\Form\Type\FooFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Foo controller.
 *
 * @Route("/foo")
 */
class FooController extends Controller
{
    /**
     * @Route("/index", methods={"GET"})
     * @View()
     */
    public function indexAction()
    {
        $client = $this->get('old_sound_rabbit_mq.integer_store_rpc');
        $client->addRequest(serialize(array('min' => 0, 'max' => 10)), 'random_int', 'request_id');

        return $client->getReplies();
    }

    /**
     * @Route("", methods={"GET"})
     * @View()
     */
    public function listAction()
    {
        return $this->getDoctrine()->getManager()->getRepository('LesTilleulsDemoBundle:Foo')->findAll();
    }

    /**
     * @Route("/{id}", methods={"GET"}, requirements={"id"="\d+"})
     * @View()
     */
    public function getAction(Foo $foo)
    {
        return $foo;
    }

    /**
     * @Route("", methods={"POST"})
     * @View(statusCode=201)
     */
    public function createAction(Request $request)
    {
        return $this->get('les_tilleuls_demo.form.handler')->handle(
            $this->get('form.factory')->createNamed('', new FooFormType()),
            $request
        );
    }

    /**
     * @Route("/{id}", methods={"PUT", "PATCH"}, requirements={"id"="\d+"})
     * @View()
     */
    public function updateAction(Request $request, Foo $foo)
    {
        return $this->get('les_tilleuls_demo.form.handler')->handle(
            $this->get('form.factory')->createNamed('', new FooFormType(), $foo, ['method' => $request->getMethod()]),
            $request
        );
    }

    /**
     * @Route("/{id}", methods={"DELETE"}, requirements={"id"="\d+"})
     * @View()
     */
    public function deleteAction(Foo $foo)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $em->remove($foo);
        $em->flush();
        $this->get('old_sound_rabbit_mq.les_tilleuls_foo_producer')->publish(serialize($foo));

        return '';
    }
}
