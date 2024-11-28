<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $skills = [
            'Languages' => [
                'icon' => 'fa-solid fa-code',
                'color_bg' => 'blue-500/20',
                'color_text' => 'blue-400',
                'color_hover' => 'blue-800',
                'items' => [
                    [ 'icon' => 'fa-brands fa-html5', 'name' => 'HTML', 'display' => true ],
                    [ 'icon' => 'fa-brands fa-css3-alt', 'name' => 'CSS', 'display' => true ],
                    [ 'icon' => 'fa-brands fa-js', 'name' => 'JavaScript', 'display' => true ],
                    [ 'icon' => 'fa-brands fa-php', 'name' => 'PHP', 'display' => true ],
                    [ 'icon' => 'fa-brands fa-python', 'name' => 'Python', 'display' => true ],
                    [ 'icon' => 'fa-brands fa-java', 'name' => 'Java', 'display' => true ],
                    [ 'icon' => 'fa-brands fa-kotlin', 'name' => 'Kotlin', 'display' => false ],
                    [ 'icon' => 'fa-brands fa-swift', 'name' => 'Swift', 'display' => false ]
                ]
            ],
            'Frameworks' => [
                'icon' => 'fa-solid fa-layer-group',
                'color_bg' => 'red-500/20',
                'color_text' => 'red-400',
                'color_hover' => 'red-800',
                'items' => [
                    [ 'icon' => 'fa-brands fa-symfony', 'name' => 'Symfony', 'display' => true ],
                    [ 'image' => 'flask.png', 'name' => 'Flask', 'display' => true ],
                    [ 'image' => 'nextjs.png', 'name' => 'Next.js', 'display' => true ],
                    [ 'icon' => 'fa-brands fa-bootstrap', 'name' => 'Bootstrap', 'display' => true ],
                    [ 'icon' => 'fa-solid fa-leaf', 'name' => 'Twig', 'display' => true ]
                ]
            ],
            'Backend' => [
                'icon' => 'fa-solid fa-server',
                'color_bg' => 'green-500/20',
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
                'color_bg' => 'yellow-500/20',
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
                'title' => 'Full-Stack Developer',
                'company' => 'Klimber-Kids',
                'location' => 'Guichainville, France',
                'period' => 'Jul 2024 - Aug 2024',
                'logo' => 'companies/klimber-kids.svg',
                'description' => [
                    '↳ Development of a showcase website (PHP / Symfony)',
                    '↳ Implementation of an Anti-Spam protection (Honeypot)'
                ],
                'technologies' => [
                    $findTechByName('Symfony'),
                    $findTechByName('Bootstrap'),
                    $findTechByName('Twig'),
                    $findTechByName('PHP'),
                    $findTechByName('HTML'),
                    $findTechByName('CSS'),
                    $findTechByName('JavaScript'),
                    $findTechByName('Git'),
                ]
            ],
            [
                'title' => 'Full-Stack Developer (Internship)',
                'company' => 'CS-Lane',
                'location' => 'Elbeuf, France',
                'period' => 'Jan 2024 - Feb 2024',
                'logo' => 'companies/cs-lane.png',
                'description' => [
                    '↳ Development of Android (Kotlin) & iOS (Swift) mobile applications',
                    '↳ Implementation of a complete appointment system (PHP)',
                    '↳ Creation of a REST API (PHP & MySQL)',
                    '↳ Team project management (SCRUMBAN)'
                ],
                'technologies' => [
                    $findTechByName('PHP'),
                    $findTechByName('Kotlin'),
                    $findTechByName('Swift'),
                    $findTechByName('MySQL'),
                    $findTechByName('REST APIs'),
                    $findTechByName('Agile'),
                    $findTechByName('Git')
                ]
                ],
                [
                    'title' => 'Full-Stack Developer (Internship)',
                    'company' => 'UIMM Eure Seine Estuaire',
                    'location' => 'Evreux, France',
                    'period' => 'May 2023 - Jun 2023',
                    'logo' => 'companies/uimm.png',
                    'description' => [
                        '↳ Development of a Web App for the IT Department (Python / Flask)',
                        '↳ Utilization of a REST API (Microsoft Graph API)'
                    ],
                    'technologies' => [
                        $findTechByName('Flask'),
                        $findTechByName('Python'),
                        $findTechByName('REST APIs'),
                    ]
                ]
        ];

        $educations = [
            [
                'degree' => 'BTS SIO (Information Systems and Services)',
                'school' => 'Lycée Gustave Flaubert',
                'location' => 'Rouen, France',
                'period' => '2022 - 2024',
                'logo' => 'schools/gustave-flaubert.png',
                'description' => [
                    '↳ SLAM Option (Software Solutions & Business Applications)',                    
                ]
            ],
            [
                'degree' => 'General Baccalaureate',
                'school' => 'Lycée Aristide Briand',
                'location' => 'Evreux, France',
                'period' => '2022',
                'logo' => 'schools/aristide-briand.webp',
                'description' => [
                    '↳ Specialty in Mathematics and Contemporary English World',
                ]
            ]
        ];

        $projects = [
            [
                'title' => 'New Portfolio v2',
                'description' => 'My personal portfolio website built with Symfony, featuring a modern UI and showcasing my latest projects and experiences.',
                'image' => 'projects/portfolio-v2.png',
                'github' => 'https://github.com/yourusername/portfolio-v2',
                'website' => 'https://yourwebsite.com',
                'demo' => null,
                'technologies' => [
                    $findTechByName('Symfony'),
                    $findTechByName('Twig'),
                    $findTechByName('Bootstrap'),
                    $findTechByName('PHP')
                ]
            ],
            [
                'title' => 'Klimber-Kids',
                'description' => 'A web application for managing climbing courses and student progress, with features for tracking achievements and scheduling sessions.',
                'image' => 'projects/klimber-kids.png',
                'github' => 'https://github.com/yourusername/klimber-kids',
                'website' => null,
                'demo' => 'https://demo.klimber-kids.com',
                'technologies' => [
                    $findTechByName('Symfony'),
                    $findTechByName('Bootstrap'),
                    $findTechByName('Twig'),
                    $findTechByName('PHP')
                ]
            ]
        ];

        return $this->render('home/index.html.twig', [
            'skills' => $skills,
            'experiences' => $experiences,
            'educations' => $educations,
            'projects' => $projects
        ]);
    }
}
