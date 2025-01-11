<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

class HomeController extends AbstractController
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    #[Route('/switch-locale/{locale}', name: 'switch_locale', methods: ['POST', 'GET'])]
    public function switchLocale(Request $request, string $locale): Response
    {
        $request->getSession()->set('_locale', $locale);
        
        if ($request->isXmlHttpRequest()) {
            return $this->json(['success' => true, 'locale' => $locale]);
        }
        
        return $this->redirect($request->headers->get('referer', $this->generateUrl('home')));
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

        $skills = [
            'Languages' => [
                'icon' => 'fa-solid fa-code',
                'color_bg' => 'blue-500/40',
                'color_text' => 'blue-400',
                'color_hover' => 'blue-800',
                'items' => [
                    [ 'icon' => 'fa-brands fa-html5', 'name' => 'HTML', 'display' => true ],
                    [ 'icon' => 'fa-brands fa-css3-alt', 'name' => 'CSS', 'display' => true ],
                    [ 'icon' => 'fa-brands fa-js', 'name' => 'JavaScript', 'display' => true ],
                    [ 'image' => 'skills/typescript.svg', 'name' => 'TypeScript', 'display' => false ],
                    [ 'icon' => 'fa-brands fa-php', 'name' => 'PHP', 'display' => true ],
                    [ 'icon' => 'fa-brands fa-python', 'name' => 'Python', 'display' => true ],
                    [ 'icon' => 'fa-brands fa-java', 'name' => 'Java', 'display' => true ],
                    [ 'icon' => 'fa-brands fa-kotlin', 'name' => 'Kotlin', 'display' => false ],
                    [ 'icon' => 'fa-brands fa-swift', 'name' => 'Swift', 'display' => false ]
                ]
            ],
            'Frameworks' => [
                'icon' => 'fa-solid fa-layer-group',
                'color_bg' => 'red-500/40',
                'color_text' => 'red-400',
                'color_hover' => 'red-800',
                'items' => [
                    [ 'icon' => 'fa-brands fa-symfony', 'name' => 'Symfony', 'display' => true ],
                    [ 'image' => 'skills/flask.svg', 'name' => 'Flask', 'display' => true ],
                    [ 'image' => 'skills/next-js.svg', 'name' => 'Next.js', 'display' => false ],
                    [ 'icon' => 'fa-brands fa-bootstrap', 'name' => 'Bootstrap', 'display' => true ],
                    [ 'icon' => 'fa-solid fa-wind', 'name' => 'Tailwind CSS', 'display' => false ],
                    // [ 'icon' => 'fa-solid fa-leaf', 'name' => 'Twig', 'display' => true ]
                ]
            ],
            'Backend' => [
                'icon' => 'fa-solid fa-server',
                'color_bg' => 'green-500/40',
                'color_text' => 'green-400',
                'color_hover' => 'green-800',
                'items' => [
                    [ 'icon' => 'fa-brands fa-node', 'name' => 'Node.js', 'display' => true ],
                    [ 'icon' => 'fa-solid fa-database', 'name' => 'MySQL', 'display' => true ],
                    [ 'icon' => 'fa-solid fa-server', 'name' => 'REST APIs', 'display' => true ]
                ]
            ],
            'Practices' => [
                'icon' => 'fa-solid fa-list-check',
                'color_bg' => 'yellow-500/40',
                'color_text' => 'yellow-400',
                'color_hover' => 'yellow-800',
                'items' => [
                    [ 'icon' => 'fa-solid fa-arrows-spin', 'name' => 'Agile', 'display' => true ],
                    [ 'icon' => 'fa-brands fa-git-alt', 'name' => 'Git', 'display' => true ]
                ]
            ]
        ];

        $findTechByName = function($name) use ($skills) {
            foreach ($skills as $category) {
                foreach ($category['items'] as $item) {
                    if ($item['name'] === $name) {
                        return array_merge($item, [
                            'color_bg' => $category['color_bg'],
                            'color_text' => $category['color_text'],
                            'color_hover' => $category['color_hover']
                        ]);
                    }
                }
            }
            return null;
        };

        $experiences = [
            [
                'title' => $this->translator->trans('experiences.klimber_kids.title'),
                'company' => 'Klimber-Kids',
                'location' => $this->translator->trans('experiences.klimber_kids.location'),
                'period' => $this->translator->trans('experiences.klimber_kids.period'),
                'logo' => 'companies/klimber-kids.svg',
                'description' => [
                    $this->translator->trans('experiences.klimber_kids.description.1'),
                    $this->translator->trans('experiences.klimber_kids.description.2'),
                    $this->translator->trans('experiences.klimber_kids.description.3')
                ],
                'technologies' => [
                    $findTechByName('PHP'),
                    $findTechByName('HTML'),
                    $findTechByName('CSS'),
                    $findTechByName('JavaScript'),
                    $findTechByName('Symfony'),
                    $findTechByName('Bootstrap'),
                    // $findTechByName('Twig'),
                    $findTechByName('MySQL'),
                    $findTechByName('Git'),
                ]
            ],
            [
                'title' => $this->translator->trans('experiences.cs_lane.title'),
                'company' => 'CS-Lane',
                'location' => $this->translator->trans('experiences.cs_lane.location'),
                'period' => $this->translator->trans('experiences.cs_lane.period'),
                'logo' => 'companies/cs-lane.png',
                'description' => [
                    $this->translator->trans('experiences.cs_lane.description.1'),
                    $this->translator->trans('experiences.cs_lane.description.2'),
                    $this->translator->trans('experiences.cs_lane.description.3'),
                    $this->translator->trans('experiences.cs_lane.description.4')
                ],
                'technologies' => [
                    $findTechByName('PHP'),
                    $findTechByName('HTML'),
                    $findTechByName('CSS'),
                    $findTechByName('JavaScript'),
                    $findTechByName('Kotlin'),
                    $findTechByName('Swift'),
                    $findTechByName('Bootstrap'),
                    $findTechByName('MySQL'),
                    $findTechByName('REST APIs'),
                    $findTechByName('Agile'),
                    $findTechByName('Git')
                ]
            ],
            [
                'title' => $this->translator->trans('experiences.uimm.title'),
                'company' => 'UIMM Eure Seine Estuaire',
                'location' => $this->translator->trans('experiences.uimm.location'),
                'period' => $this->translator->trans('experiences.uimm.period'),
                'logo' => 'companies/uimm.png',
                'description' => [
                    $this->translator->trans('experiences.uimm.description.1'),
                    $this->translator->trans('experiences.uimm.description.2')
                ],
                'technologies' => [
                    $findTechByName('Python'),
                    $findTechByName('HTML'),
                    $findTechByName('CSS'),
                    $findTechByName('JavaScript'),
                    $findTechByName('Flask'),
                    $findTechByName('Bootstrap'),
                    $findTechByName('REST APIs'),
                ]
            ]
        ];

        $educations = [
            [
                'degree' => $this->translator->trans('education.bts.degree'),
                'school' => 'Lycée Gustave Flaubert',
                'location' => $this->translator->trans('education.bts.location'),
                'period' => $this->translator->trans('education.bts.period'),
                'logo' => 'schools/gustave-flaubert.png',
                'description' => [
                    $this->translator->trans('education.bts.description.1'),
                ]
            ],
            [
                'degree' => $this->translator->trans('education.bac.degree'),
                'school' => 'Lycée Aristide Briand',
                'location' => $this->translator->trans('education.bac.location'),
                'period' => $this->translator->trans('education.bac.period'),
                'logo' => 'schools/aristide-briand.webp',
                'description' => [
                    $this->translator->trans('education.bac.description.1'),
                ]
            ]
        ];

        $projects = [
            [
                'title' => $this->translator->trans('projects.klimber_kids.title'),
                'description' => $this->translator->trans('projects.klimber_kids.description'),
                'image' => 'projects/klimber-kids.png',
                'github' => null,
                'website' => 'https://klimber-kids.com',
                'demo' => null,
                'technologies' => [
                    $findTechByName('PHP'),
                    $findTechByName('HTML'),
                    $findTechByName('CSS'),
                    $findTechByName('JavaScript'),
                    $findTechByName('Symfony'),
                    $findTechByName('Bootstrap'),
                    // $findTechByName('Twig'),
                    $findTechByName('MySQL'),
                    $findTechByName('Git'),
                ]
                ],
                [
                    'title' => $this->translator->trans('projects.dashboard-si.title'),
                    'description' => $this->translator->trans('projects.dashboard-si.description'),
                    'image' => 'projects/dashboard-si.png',
                    'github' => 'https://github.com/Will6855/IT-Department-Dashboard',
                    'website' => null,
                    'demo' => '',
                    'technologies' => [
                        $findTechByName('Python'),
                        $findTechByName('HTML'),
                        $findTechByName('CSS'),
                        $findTechByName('JavaScript'),
                        $findTechByName('Flask'),
                        $findTechByName('REST APIs'),
                    ]
                ],
                [
                    'title' => $this->translator->trans('projects.email-sender.title'),
                    'description' => $this->translator->trans('projects.email-sender.description'),
                    'image' => 'projects/email-sender.png',
                    'github' => 'https://github.com/Will6855/HTML-Email-Sender',
                    'website' => null,
                    'demo' => '',
                    'technologies' => [
                        $findTechByName('HTML'),
                        $findTechByName('CSS'),
                        $findTechByName('JavaScript'),
                        $findTechByName('TypeScript'),
                        $findTechByName('Next.js'),
                        $findTechByName('Tailwind CSS'),
                        $findTechByName('Node.js'),
                        $findTechByName('Git'),
                    ]
                ],
        ];

        return $this->render('home/index.html.twig', [
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
