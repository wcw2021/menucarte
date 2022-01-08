<?php

namespace App\Controller;

use App\Repository\DishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(DishRepository $dr)
    {
        $dishes = $dr->findAll();

        $randompic = array_rand($dishes, 2);

        // dump($dishes[$randompic[0]]);

        return $this->render('home/index.html.twig', [
            'dish1' => $dishes[$randompic[0]],
            'dish2' => $dishes[$randompic[1]],
        ]);
    }





}
