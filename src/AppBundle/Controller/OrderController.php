<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * REST контроллер заказов
 *
 *	Доступ к контроллеру только для аутентифицированных пользователей (авторизацию настраивай в security.yml)
 *
 *	GET /orders - список всех заказов, если текущий пользователь администратор, или только тех заказов, которые созданы текущим пользователем
 *	GET /orders/1 - просмотр заказа с ID = 1, если он был создан текущим пользователем или если текущий пользователь администратор
 *	POST /orders - добавление заказа
 *	PUT /orders/1 - обновление заказа с ID = 1, если он был создан текущим пользователем или если текущий пользователь администратор
 *	DELETE /orders/1 - удаление заказа с ID = 1, если он был создан текущим пользователем или если текущий пользователь администратор
 *
 * Class OrderController
 * @package AppBundle\Controller
 */
class OrderController extends Controller
{

    /**
     * @Route("/orders/{id}")
     * @Method("DELETE")
     *
     * @param $id Order's unique ID
     * @return Response
     */

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Order::class)->find($id);

        if (!$order) {
            return new Response('', 404);
        } else {

            $user = $this->get('security.token_storage')->getToken()->getUser();

            if (
                $user->getId() != $order->getUser()->getId() &&
                !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')
            ) {
                return new Response('', 403);
            }

            $em->remove($order);
            $em->flush();

            return new Response('');
        }

    }

    /**
     * @Route("/orders/{id}")
     * @Method("PUT")
     *
     * @param $id Order's unique ID
     * @param $request
     * @return Response
     */

    public function updateAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Order::class)->find($id);

        if (!$order) {
            return new Response('', 404);
        } else {

            $user = $this->get('security.token_storage')->getToken()->getUser();

            if (
                $user->getId() != $order->getUser()->getId() &&
                !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')
            ) {
                return new Response('', 403);
            }

            $order->setDescr($request->request->get('descr'));
            $order->setCost($request->request->get('cost'));

            $validator = $this->get('validator');
            $errors = $validator->validate($order);

            if (count($errors) > 0) {

                return new Response('', 500);

            } else {

                $em->flush();

                return new Response(json_encode($this->orderToArr($order)));
            }
        }

    }

    /**
     * @Route("/orders")
     * @Method("POST")
     *
     * @param $request
     * @return Response
     */

    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $order = new Order();

        $order->setDescr($request->request->get('descr'));
        $order->setCost($request->request->get('cost'));
        $order->setUser($this->get('security.token_storage')->getToken()->getUser());

        $validator = $this->get('validator');
        $errors = $validator->validate($order);

        if (count($errors) > 0) {

            return new Response('', 500);

        } else {

            $em->persist($order);
            $em->flush();

            return new Response(json_encode($this->orderToArr($order)));
        }

    }

    /**
     * @Route("/orders/{id}")
     * @Method("GET")
     *
     * @param $id Order's unique ID
     * @return Response
     */

    public function viewAction($id)
    {
        $criteria = ["id" => $id];

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $criteria += ["user" => $this->get('security.token_storage')->getToken()->getUser()->getId()];
        }

        $order = $this->getDoctrine()
            ->getRepository("AppBundle:Order")
            ->findOneBy($criteria);

        if ($order !== null) {
            return new Response(json_encode($this->orderToArr($order)));
        } else {
            return new Response('', 404);
        }
    }

    /**
     * @Route("/orders")
     * @Method("GET")
     */

    public function indexAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $orders = $this->getDoctrine()
                ->getRepository("AppBundle:Order")
                ->findAll();
        } else {
            $orders = $this->getDoctrine()
                ->getRepository("AppBundle:Order")
                ->findBy(
                    ["user" => $this->getUser()->getId()]
                );
        }

        $result = [];
        foreach ($orders as $order) {
            $result[] = $this->orderToArr($order);
        }

        return new Response(json_encode($result), count($result) == 0 ? 204 : 200);
    }

    /**
     * Returns an array representation of the Order's object
     *
     * @param Order $order
     * @return array
     */
    private function orderToArr(Order $order)
    {
        return [
            "id" => $order->getId(),
            "order_date" => $order->getOrderDate()->format('Y-m-d'),
            "descr" => $order->getDescr(),
            "cost" => $order->getCost(),
            "userid" => $order->getUser()->getId(),
            "username" => $order->getUser()->getUsername(),
        ];
    }
}