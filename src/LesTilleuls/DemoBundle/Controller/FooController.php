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
     * @Route("", methods={"GET"})
     * @View()
     */
    public function listAction()
    {
        return $this->getDoctrine()->getManager()->getRepository('LesTilleulsDemoBundle:Foo')->findAll();
    }

    /**
     * RPC use.
     *
     * @Route("/{id}", methods={"GET"}, requirements={"id"="\d+"})
     * @View()
     */
    public function getAndReplyAction(Foo $foo)
    {
        $client = $this->get('old_sound_rabbit_mq.foo_rpc');
        $client->addRequest(serialize($foo), 'bar', 'request_id');
        $replies = $client->getReplies();

        return $replies['request_id'];
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
     * @View(statusCode=204)
     */
    public function deleteAction(Foo $foo)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $em->remove($foo);
        $em->flush();
        $this->get('old_sound_rabbit_mq.foo_producer')->publish(serialize($foo));
    }
}
