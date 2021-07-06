<?php

namespace App\Controller;

use App\Entity\Version;
use App\Form\VersionType;
use App\Repository\VersionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/versions")
 */
class VersionController extends AbstractController
{
    /**
     * @Route("/", name="version_index", methods={"GET"})
     */
    public function index(VersionRepository $versionRepository): Response
    {
        return $this->render('admin/version/index.html.twig', [
            'versions' => $versionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="version_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $version = new Version();
        $form = $this->createForm(VersionType::class, $version);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($version);
            $entityManager->flush();
            $this->addFlash('success', 'La version ' . $version->getVersion() . ' du projet "' . $version->getProjet()->getName() . '" a bien été créée');
            return $this->redirectToRoute('version_index');
        }

        return $this->render('admin/version/new.html.twig', [
            'version' => $version,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="version_show", methods={"GET"})
     */
    public function show(Version $version): Response
    {
        return $this->render('admin/version/show.html.twig', [
            'version' => $version,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="version_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Version $version): Response
    {
        $form = $this->createForm(VersionType::class, $version);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'La version ' . $version->getVersion() . ' du projet "' . $version->getProjet()->getName() . '" a bien été modifiée');
            return $this->redirectToRoute('version_index');
        }

        return $this->render('admin/version/edit.html.twig', [
            'version' => $version,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="version_delete", methods={"POST"})
     */
    public function delete(Request $request, Version $version): Response
    {
        if ($this->isCsrfTokenValid('delete' . $version->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($version);
            $entityManager->flush();
        }

        return $this->redirectToRoute('version_index');
    }
}
