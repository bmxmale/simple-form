<?php

namespace App\Controller;

use App\Entity\SimpleFormInput;
use App\Form\SimpleFormInputType;
use App\Repository\SimpleFormInputsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SimplyFormController extends AbstractController
{
    public function __construct(private readonly SimpleFormInputsRepository $simpleFormInputsRepository)
    {
    }

    #[Route('/', name: 'app_simply_form', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $simpleFormInput = new SimpleFormInput();
        $form = $this->createForm(SimpleFormInputType::class, $simpleFormInput);

        $this->addFlash('success', 'Uzupełnij formularz!');

        return $this->render('form/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/submit', name: 'app_simply_form_submit', methods: ['POST'])]
    public function submit(Request $request): Response
    {
        $simpleFormInput = new SimpleFormInput();
        $form = $this->createForm(SimpleFormInputType::class, $simpleFormInput);
        $form->handleRequest($request);

        try {
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var UploadedFile $file */
                if ($file = $form->get('attachment')->getData()) {
                    $fileContent = file_get_contents($file->getPathname());

                    $simpleFormInput->setAttachment($fileContent);
                    $simpleFormInput->setAttachmentFilename($file->getClientOriginalName());
                }

                $simpleFormInput->setCreatedAt();

                $this->simpleFormInputsRepository->save($simpleFormInput);

                $this->addFlash('success', 'Formularz został wysłany pomyślnie!');
                $response = [
                    'success' => true,
                    'messages' => $this->renderView('_flash.html.twig')
                ];
            } else {
                /** @var FormError $error */
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('danger', $error->getMessage());
                }
                $response = [
                    'success' => false,
                    'messages' => $this->renderView('_flash.html.twig')
                ];
            }
            return $this->json($response);
        } catch (\Throwable $throwable) {
            $this->addFlash('danger', $throwable->getMessage());

            $response = [
                'success' => false,
                'messages' => $this->renderView('_flash.html.twig')
            ];
            return $this->json($response);
        }
    }
}
