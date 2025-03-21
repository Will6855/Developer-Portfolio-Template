<?php

namespace App\Controller;

use App\Service\PortfolioService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends AbstractController
{

    private PortfolioService $portfolioService;

    public function __construct(PortfolioService $portfolioService)
    {
        $this->portfolioService = $portfolioService;
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
    public function index(Request $request, MailerInterface $mailer): Response
    {
        // Contact form handling
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $contactFormData = $form->getData();
                
                $adminEmail = (new Email())
                    ->from(new Address($contactFormData->getEmail()))
                    ->to(new Address('contact@guillaume-piard.fr'))
                    ->subject('Nouveau message de ' . $contactFormData->getEmail())
                    ->html(
                        '<p><strong>Nom:</strong> ' . htmlspecialchars($contactFormData->getName()) . '</p>' .
                        '<p><strong>Email:</strong> ' . htmlspecialchars($contactFormData->getEmail()) . '</p>' .
                        '<p><strong>Message:</strong><br>' . nl2br(htmlspecialchars($contactFormData->getMessage())) . '</p>'
                    );
                $mailer->send($adminEmail);

                $userEmail = (new Email())
                    ->from(new Address('contact@guillaume-piard.fr', 'Guillaume PIARD'))
                    ->to(new Address($contactFormData->getEmail()))
                    ->subject('Message reçu')
                    ->html(
                        '<p><strong>Nom:</strong> ' . htmlspecialchars($contactFormData->getName()) . '</p>' .
                        '<p><strong>Email:</strong> ' . htmlspecialchars($contactFormData->getEmail()) . '</p>' .
                        '<p><strong>Message:</strong><br>' . nl2br(htmlspecialchars($contactFormData->getMessage())) . '</p>' .
                        '<br>' .
                        '<p>Merci pour votre message et de votre intérêt, je reviens vers vous dans les meilleurs délais. </p>' .
                        '<p>Cordialement,</p>' .
                        '<p>Guillaume PIARD</p>');
                $mailer->send($userEmail);

                $this->addFlash('success', 'Votre message a bien été envoyé');
                $this->addFlash('reopen-modal', true);
                return $this->redirectToRoute('home');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi du message');
                $this->addFlash('reopen-modal', true);
            }
        }

        // Calculate age
        $birthDate = new \DateTime('2004-01-06');
        $today = new \DateTime();
        $age = $today->diff($birthDate)->y;

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
