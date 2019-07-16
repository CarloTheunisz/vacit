<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use App\Service\UserService;
use App\Form\CandidateForm;
use App\Form\EmployerForm;

class UserController extends AbstractController
{
    private $service;

    public function __construct(UserService $us) {
        $this->service = $us;
    }

    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->redirectToRoute('profiel');
    }

    /**
     * @Route("/profiel", name="profiel")
     * @Template()
     */
    public function profiel(Request $request) {
        $data = $this->getIngelogdeUser();

        if ($data) {
            if (\in_array("ROLE_CANDIDATE", $data->getRoles())) {
                $form = $this->createForm(CandidateForm::class, $data);
                
            }
            if (\in_array("ROLE_EMPLOYER", $data->getRoles())) {
                $form = $this->createForm(EmployerForm::class, $data);
            }
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if (\in_array("ROLE_CANDIDATE", $data->getRoles())) {
                    /**@var UploadedFile $cvFile */
                    $cvFile = $form['cv']->getData();
                    if ($cvFile) {
                        $newCv = sha1(uniqid(mt_rand(), true)).'.'.$cvFile->guessExtension();

                        try {
                            $cvFile->move(
                                $this->getParameter('cv_directory'),
                                $newCv
                            );
                        } catch (FileException $e) {
                        }
                        $data->setCv($newCv);
                    }
                }

                /**@var UploadedFile $fotoFile */
                $fotoFile = $form['afbeelding']->getData();
                if ($fotoFile) {
                    $newFoto = sha1(uniqid(mt_rand(), true)).'.'.$fotoFile->guessExtension();

                    try {
                        $fotoFile->move(
                            $this->getParameter('foto_directory'),
                            $newFoto
                        );
                    } catch (FileException $e) {
                    }
                    $data->setAfbeelding($newFoto);
                }

                $em = $this->getDoctrine()->getManager();
                $em->flush();
            }

            return [
                'data' => $data,
                'form' => $form->createView()
            ];
        }
    }

    /**
     * @Route("/profiel/sollicitaties", name="sollicitaties")
     * @Template()
     */
    public function sollicitaties() {
        $data = $this->getIngelogdeUser();

        if ($data) {
            if (isset($_POST["userId"]) && isset($_POST["vacId"]) && !$this->service->findSollicitatieByFK($_POST["userId"], $_POST["vacId"], true) &&
                \in_array("ROLE_CANDIDATE", $data->getRoles())) {
                    $this->service->setSollicitatie($_POST["userId"], $_POST["vacId"]);
            }

            if (\in_array("ROLE_CANDIDATE", $data->getRoles())) {
                $vacatures = new \stdClass();
                $sollicitaties = $this->service->findSollicitatiesFromUser($data);
            }
            
            if (\in_array("ROLE_EMPLOYER", $data->getRoles())) {
                $vacatures = $this->service->findVacaturesFromUser($data);
                $sollicitaties = $this->service->findSollicitatiesFromVacatures($vacatures);
            }
            
            return ['data' => $data, 'vacatures' => $vacatures, 'sollicitaties' => $sollicitaties];
        }
    }

    /**
     * @Route("/profiel/{id}", name="zieProfiel")
     * @Template()
     */
    public function zieProfiel($id) {
        $data = $this->service->find($id);

        return ['data' => $data];
    }

    /**
     * @Route("/profiel/sollicitaties/uitnodigen", name="uitnodigen")
     */
    public function uitnodigen(Request $request) {
        $sId = $request->request->get('sId');
        $isChecked = $request->request->get('isChecked');

        return $this->service->toggleUitgenodigd($sId, $isChecked);
    }

    private function getIngelogdeUser() {
        $loggedIn = $this->getUser();
        if ($loggedIn) {
            return $this->service->find($this->getUser());
        }
        return null;

    }
}
