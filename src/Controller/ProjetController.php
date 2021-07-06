<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\SearchData;
use App\Form\ProjetType;
use App\Form\SearchForm;
use App\Repository\ProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;



class ProjetController extends AbstractController
{
    /**
     * @Route("admin/", name="admin_index", methods={"GET"})
     * @Route("admin/projets/", name="projet_index", methods={"GET"})
     */
    public function index(ProjetRepository $projetRepository): Response
    {
        return $this->render('admin/projet/index.html.twig', [
            'projets' => $projetRepository->findAll(),
        ]);
    }

    /**
     * @Route("admin/projets/new", name="projet_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $projet = new Projet();
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $slugger = new AsciiSlugger();
            $slug = $slugger->slug($projet->getName());
            $projet->setSlug($slug);

            $entityManager->persist($projet);
            $entityManager->flush();

            $this->addFlash('success', 'Le projet ' . $projet->getName() . ' a bien été créé');
            return $this->redirectToRoute('projet_index');
        }

        return $this->render('admin/projet/new.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("projets/{slug}", name="projet_show", methods={"GET"})
     */
    public function show(Projet $projet): Response
    {
        return $this->render('user/show.html.twig', [
            'projet' => $projet,
            'technologies' => $projet->getTechnologies()
        ]);
    }

    /**
     * @Route("admin/projets/{id}/edit", name="projet_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Projet $projet): Response
    {
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Le projet "' . $projet->getName() . '" a bien été modifié');
            return $this->redirectToRoute('projet_index');
        }

        return $this->render('admin/projet/edit.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/projets/{id}", name="projet_delete", methods={"POST"})
     */
    public function delete(Request $request, Projet $projet): Response
    {
        if ($this->isCsrfTokenValid('delete' . $projet->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($projet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('projet_index');
    }

    /**
     * @Route("projets", name="projects_user", methods={"GET"})
     */
    public function index_user(ProjetRepository $projetRepository, Request $request): Response
    {

        $data = new SearchData();
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);

        return $this->render('user/projets.html.twig', [
            'projets' => $projetRepository->getPaginedProjets((int) $request->query->get("page", 1)),
            'totalProjets' => $projetRepository->countProjets(),
            'page' => $request->query->get("page", 1),
            'form' => $form->createView()
        ]);
    }
}
