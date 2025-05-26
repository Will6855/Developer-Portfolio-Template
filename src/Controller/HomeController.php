<?php

namespace App\Controller;

use App\Service\ContactService;
use App\Service\PortfolioService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends AbstractController
{
    private PortfolioService $portfolioService;
    private ContactService $contactService;

    public function __construct(
        PortfolioService $portfolioService,
        ContactService $contactService
    ) {
        $this->portfolioService = $portfolioService;
        $this->contactService = $contactService;
    }

    #[Route('/switch-locale/{locale}', name: 'switch_locale', methods: ['POST', 'GET'])]
    public function switchLocale(Request $request, string $locale, TranslatorInterface $translator): JsonResponse
    {
        $supportedLocales = ['en', 'fr'];
        if (!in_array($locale, $supportedLocales)) {
            return new JsonResponse(['success' => false, 'message' => 'Unsupported locale'], 400);
        }

        // Set locale in session
        $request->getSession()->set('_locale', $locale);
        
        // Get translation keys from request body
        $content = json_decode($request->getContent(), true);
        $keys = $content['keys'] ?? [];
        
        // Translate all requested keys
        $translations = [];
        foreach ($keys as $key) {
            $translations[$key] = $translator->trans($key, [], null, $locale);
        }

        return new JsonResponse([
            'success' => true,
            'locale' => $locale,
            'translations' => $translations
        ]);
    }

    #[Route('/', name: 'home')]
    public function index(Request $request, TranslatorInterface $translator): Response
    {
        // Contact form handling
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        $personalInfo = $this->portfolioService->getPersonalInfo();
        $birthdate = $personalInfo['birthdate'];
        $locale = $request->getLocale();

        if($form->isSubmitted() && $form->isValid()) {
            $contactFormData = $form->getData();
            
            if ($this->contactService->sendContactEmails($contactFormData, $locale)) {
                $this->addFlash('success', 'contact_form.success_message');
                $this->addFlash('reopen-modal', true);
                return $this->redirectToRoute('home');
            } else {
                $this->addFlash('error', 'contact_form.error_message');
                $this->addFlash('reopen-modal', true);
            }
        }

        // Calculate age
        $birthDate = new \DateTime($birthdate);
        $today = new \DateTime();
        $age = $today->diff($birthDate)->y;

        // Get data from service
        $personalInfo = $this->portfolioService->getPersonalInfo();
        $skills = $this->portfolioService->getSkills();
        $experiences = $this->portfolioService->getExperiences();
        $educations = $this->portfolioService->getEducations();
        $projects = $this->portfolioService->getProjects();

        return $this->render('home/index.html.twig', [
            'personal_info' => $personalInfo,
            'skills' => $skills,
            'experiences' => $experiences,
            'educations' => $educations,
            'projects' => $projects,
            'age' => $age,
            'form' => $form->createView()
        ]);
    }

    #[Route('/legal-notice', name: 'legal_notice')]
    public function legalNotice(): Response
    {
        return $this->render('home/legal_notice.html.twig');
    }

    #[Route('/privacy-policy', name: 'privacy_policy')]
    public function privacyPolicy(): Response
    {
        return $this->render('home/privacy_policy.html.twig');
    }
}
