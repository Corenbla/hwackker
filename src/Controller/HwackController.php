<?php

namespace App\Controller;

use App\Entity\Hwack;
use App\Form\HwackType;
use App\Repository\HwackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/hwack")
 */
class HwackController extends AbstractController
{
    /**
     * @Route("/", name="hwack_index", methods={"GET"})
     * @param HwackRepository $hwackRepository
     * @return Response
     */
    public function index(HwackRepository $hwackRepository): Response
    {
        return $this->render('hwack/index.html.twig', [
            'hwacks' => $hwackRepository->findAll(),
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


}
