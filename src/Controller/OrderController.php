<?php
namespace App\Controller;
 
use App\Entity\Dish;
use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="order")
     */
    public function index(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBy(['ordertable' => 'table1']);

        return $this->render('order/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("/reserve/{id}", name="reserve")
     */
    public function reserve(Dish $dish): Response
    {
        $order = new Order();
        $order->setOrdertable("table1");
        $order->setOrdername($dish->getName());
        $order->setOrdernumber($dish->getId());
        $order->setOrderprice($dish->getPrice());
        $order->setOrderstatus("open");

        // EntityManager
        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        $this->addFlash('order', $order->getOrdername(). ' was added to the order.');

        return $this->redirect($this->generateUrl('menu'));
    }

    /**
     * @Route("/status/{id}, {status}", name="status")
     */
    public function status($id, $status): Response
    {
        // EntityManager
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Order::class)->find($id);

        $order->setOrderstatus($status);
        $em->flush();

        return $this->redirect($this->generateUrl('order'));
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete($id, OrderRepository $or): Response
    {
        $order = $or->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($order);
        $em->flush();

        return $this->redirect($this->generateUrl('order'));
    }


}





