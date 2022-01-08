<?php
namespace App\Controller;

use App\Entity\Dish;
use App\Form\DishType;
use App\Repository\DishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dish", name="dish.")
 */
class DishController extends AbstractController
{
    /**
     * @Route("/", name="edit")
     */
    public function index(DishRepository $dr): Response
    {
        $dishes = $dr->findAll();

        return $this->render('dish/index.html.twig', [
            'dishes' => $dishes,
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request): Response
    {
        $dish = new Dish();

        // Form
        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);

        if ($form->isSubmitted()){
            // EntityManager
            $em = $this->getDoctrine()->getManager();

            $image = $request->files->get('dish')['attachment'];

            if($image){
                $filename = md5(uniqid()).'.'.$image->guessClientExtension();
            };

            $image->move(
                $this->getParameter('image_folder'), $filename
            );

            $dish->setImage($filename);

            $em->persist($dish);
            $em->flush();

            return $this->redirect($this->generateUrl('dish.edit'));
        }
        
        // Response
        return $this->render('dish/create.html.twig', [
            'createForm' => $form->createView()
        ]);

    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete($id, DishRepository $dr): Response
    {
        $dish = $dr->find($id);
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($dish);
        $em->flush();

        // Message
        $this->addFlash('success', 'Dish was removed successfully');

        return $this->redirect($this->generateUrl('dish.edit'));
    }

    /**
     * @Route("/show/{id}", name="show")
     */
    public function show(Dish $dish)
    {
        // dump($dish);
        
        return $this->render('dish/show.html.twig', [
            'dish' => $dish,
        ]);
    }

    /**
     * @Route("/price/{id}", name="price")
     */
    public function price($id, DishRepository $dishRepository)
    {
        $dish = $dishRepository->find2Usd($id);

        dump($dish);
    }


}






