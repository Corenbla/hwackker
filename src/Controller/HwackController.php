<?php

namespace App\Controller;

use App\Entity\Hwack;
use App\Form\HwackType;
use App\Repository\HwackRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class HwackController extends AbstractController
{
    /**
     * @Route("/", name="hwack_index", methods={"GET"})
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param HwackRepository $hwackRepository
     * @return Response
     */
    public function index( Request $request, PaginatorInterface  $paginator,HwackRepository $hwackRepository): Response
    {
        $isAdmin= false;
        $isFollower = false;
        $page = $request->query->get('page') ?? 1;
        $search = $request->query->get('search') ?? null ;
        $username = $request->query->get('username') ?? "";
        $private = $isAdmin || $isFollower ? $request->query->get('private') ?? false : false ;


        if(!empty($search)){
            $allHwacks = $hwackRepository->findByContentLike($search);
            $hwacks = $paginator->paginate($allHwacks,1,100);
            return $this->render('hwack/news.html.twig', [
                'hwacks' => $hwacks,
            ]);
        }
//        if(!empty($username)){
//            //TODO if username is follower
//            $this->getUserProfil($username, $isFollower,$hwackRepository);
//        }
        if(!empty($page)){
            $allHwacks = $hwackRepository->findBy(['isPrivate'=>$private],['createdAt'=>'desc']);
            $hwacks = $paginator->paginate($allHwacks,$page,100);
            return $this->render('hwack/news.html.twig', [
                'hwacks' => $hwacks,
            ]);
        }
//        $allHwacks = $hwackRepository->findBy(['isPrivate'=>$private],['createdAt'=>'desc']);
//        $hwacks = $paginator->paginate($allHwacks,$request->query->getInt('page', $page),100);

        return $this->render('user/base.html.twig', [

        ]);
    }

    /**
     * @Route("/new", name="hwack_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $hwack = new Hwack();
        $form = $this->createForm(HwackType::class, $hwack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($hwack);
            $entityManager->flush();

            return $this->redirectToRoute('hwack_index');
        }

        return $this->render('hwack/new.html.twig', [
            'hwack' => $hwack,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="hwack_show", methods={"GET"})
     * @param Hwack $hwack
     * @return Response
     */
    public function show(Hwack $hwack): Response
    {
        return $this->render('hwack/show.html.twig', [
            'hwack' => $hwack,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="hwack_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Hwack $hwack
     * @return Response
     */
    public function edit(Request $request, Hwack $hwack): Response
    {
        $form = $this->createForm(HwackType::class, $hwack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('hwack_index');
        }

        return $this->render('hwack/edit.html.twig', [
            'hwack' => $hwack,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="hwack_delete", methods={"POST"})
     * @param Request $request
     * @param Hwack $hwack
     * @return Response
     */
    public function delete(Request $request, Hwack $hwack): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hwack->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($hwack);
            $entityManager->flush();
        }

        return $this->redirectToRoute('hwack_index');
    }



    /**
     * @param String $search
     * @param HwackRepository $hwackRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function getHwacksByContent(String $search,HwackRepository $hwackRepository, PaginatorInterface $paginator): Response
    {
        $allHwacks = $hwackRepository->findByContentLike($search);
        $hwacks = $paginator->paginate($allHwacks,1,100);
        return $this->render('hwack/show.html.twig', [
            'hwacks' => $hwacks,
        ]);
    }

//    public function getUserProfile()

}
