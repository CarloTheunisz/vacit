<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Vacature;
use App\Service\VacatureService;

class VacatureController extends AbstractController
{
    private $service;

    public function __construct(VacatureService $vs) {
        $this->service = $vs;
    }

    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function index()
    {
        $data = $this->service->getAllVacatures();

        return [
            'data' => $data
        ];
    }

    /**
     * @Route("/vacature", name="vacature")
     * @Template()
     */
    public function vacature() {
        $data = $this->service->getAllVacatures();

        return ['data' => $data];
    }

    /**
     * @Route("/vacature/detail/{id}", name="detail")
     * @Template()
     */
    public function detail($id) {
        $data = $this->service->find($id);
        $andereVacatures = new \stdClass();
        if ($data) {
            $andereVacatures = $this->service->getAndereVacatures($data);
        }
        $user = $this->getUser();
        $isGesolliciteerd = $this->service->findSollicitatieByFK($user, $id);

        return ['data' => $data, 'andereVacatures' => $andereVacatures, 'user' => $user, 'isGesolliciteerd' => $isGesolliciteerd];
    }

    /**
     * @Route("/vacature/nieuw", name="nieuw")
     * @Template()
     */
    public function nieuw(Request $request) {
        $user = $this->getUser();
        $vacature = new Vacature();

        $form = $this->service->getNewVacatureForm($vacature, $user, 'Maak nieuwe vacature');
        $form->handleRequest();

        if ($form->isSubmitted() && $form->isValid()) {
            $vacature = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($vacature);
            $em->flush();
        
            return $this->redirectToRoute('vacature');
        }

        return ['user' => $user, 'form' => $form->createView()];
    }

    /**
     * @Route("/vacature/bewerk/{id}", name="bewerk")
     * @Template()
     */
    public function bewerk($id) {
        $user = $this->getUser();
        $vacature = $this->service->find($id);

        $form = $this->service->getNewVacatureForm($vacature, $user, 'Bewerk vacature');
        $form->handleRequest();

        if ($form->isSubmitted() && $form->isValid()) {
            $vacature = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        
            return $this->redirectToRoute('vacature');
        }

        return ['user' => $user, 'form' => $form->createView()];
    }

    /**
     * @return string
     */
    private function generateUniqueFileName() {
        return md5(uniqid());
    }
}
