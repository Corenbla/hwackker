<?php

namespace App\Controller;

use App\Entity\Hwack;
use App\Entity\User;
use App\Form\HwackType;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/hwack")
 * @method User getUser()

 */
class HwackController extends AbstractController
{
    /**
     * @Route("/", name="hwack_index", methods={"GET"})
     */
    public function index(): Response
    {

        return $this->render('user/index.html.twig', [

        ]);
    }

    /**
     * @Route("/new", name="hwack_new", methods={"GET","POST"})
     * @param Request $request
     * @param MarkdownParserInterface $markdownParser
     * @return Response
     */
    public function new(Request $request,MarkdownParserInterface $markdownParser): Response
    {
        $hwack = new Hwack();
        $form = $this->createForm(HwackType::class, $hwack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hwack->setContent($markdownParser->transformMarkdown($hwack->getContent()));
            $hwack->setAuthor($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($hwack);
            $entityManager->flush();

            return $this->redirectToRoute('user');
        }
        return $this->redirectToRoute('user');

//        return $this->render('hwack/new.html.twig', [
////            'hwack' => $hwack,
//            'form' => $form->createView(),
//        ]);
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
