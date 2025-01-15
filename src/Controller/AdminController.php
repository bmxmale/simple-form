<?php

namespace App\Controller;

use App\Repository\SimpleFormInputsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    public function __construct(private readonly SimpleFormInputsRepository $simpleFormInputsRepository)
    {
    }

    #[Route('/admin/{token}', name: 'admin_index', methods: ['GET'])]
    public function index(Request $request, string $token): Response
    {
        $adminToken = $this->getParameter('admin.token');

        if ($token !== $adminToken) {
            $this->addFlash('danger', 'Błędny token');

            return $this->redirectToRoute('app_simply_form');
        }

        $simpleFormInputs = $this->simpleFormInputsRepository->getInputs();

        return $this->render('admin/index.html.twig', [
            'simpleFormInputs' => $simpleFormInputs,
        ]);
    }

    #[Route('/admin/download/{id}', name: 'admin_download_attachment', methods: ['GET'])]
    public function downloadAttachment(int $id): Response
    {
        $formInput = $this->simpleFormInputsRepository->find($id);

        if (!$formInput || !$formInput->getAttachmentFilename()) {
            throw $this->createNotFoundException('Załącznik nie został znaleziony.');
        }

        $fileContent = stream_get_contents($formInput->getAttachment());
        $fileName = $formInput->getAttachmentFilename();

        $response = new Response($fileContent);
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set(
            'Content-Disposition',
            ResponseHeaderBag::DISPOSITION_ATTACHMENT . '; filename=' . $fileName
        );

        return $response;
    }
}
