<?php

namespace App\Service;

class PortfolioService
{
    public function getSkills(): array
    {
        return [
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
                    [ 'icon' => 'fa-solid fa-wind', 'name' => 'Tailwind CSS', 'display' => true ]
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
    }

    public function findTechByName(string $name): ?array
    {
        $skills = $this->getSkills();
        
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
    }

    public function getExperiences(): array
    {
        return [
            [
                'title' => 'experiences.klimber_kids.title',
                'company' => 'Klimber-Kids',
                'location' => 'experiences.klimber_kids.location',
                'period' => 'experiences.klimber_kids.period',
                'logo' => 'companies/klimber-kids.svg',
                'description' => [
                    'experiences.klimber_kids.description.1',
                    'experiences.klimber_kids.description.2',
                    'experiences.klimber_kids.description.3'
                ],
                'technologies' => array_map(fn($tech) => $this->findTechByName($tech), 
                    ['PHP', 'HTML', 'CSS', 'JavaScript', 'Symfony', 'Bootstrap', 'MySQL', 'Git'])
            ],
            [
                'title' => 'experiences.cs_lane.title',
                'company' => 'CS-Lane',
                'location' => 'experiences.cs_lane.location',
                'period' => 'experiences.cs_lane.period',
                'logo' => 'companies/cs-lane.png',
                'description' => [
                    'experiences.cs_lane.description.1',
                    'experiences.cs_lane.description.2',
                    'experiences.cs_lane.description.3',
                    'experiences.cs_lane.description.4'
                ],
                'technologies' => array_map(fn($tech) => $this->findTechByName($tech), 
                    ['PHP', 'HTML', 'CSS', 'JavaScript', 'Kotlin', 'Swift', 'Bootstrap', 'MySQL', 'REST APIs', 'Agile', 'Git'])
            ],
            [
                'title' => 'experiences.uimm.title',
                'company' => 'UIMM Eure Seine Estuaire',
                'location' => 'experiences.uimm.location',
                'period' => 'experiences.uimm.period',
                'logo' => 'companies/uimm.png',
                'description' => [
                    'experiences.uimm.description.1',
                    'experiences.uimm.description.2'
                ],
                'technologies' => array_map(fn($tech) => $this->findTechByName($tech), 
                    ['Python', 'HTML', 'CSS', 'JavaScript', 'Flask', 'Bootstrap', 'REST APIs'])
            ]
        ];
    }

    public function getEducations(): array
    {
        return [
            [
                'degree' => 'education.bts.degree',
                'school' => 'Lycée Gustave Flaubert',
                'location' => 'education.bts.location',
                'period' => 'education.bts.period',
                'logo' => 'schools/gustave-flaubert.png',
                'description' => [
                    'education.bts.description.1',
                ]
            ],
            [
                'degree' => 'education.bac.degree',
                'school' => 'Lycée Aristide Briand',
                'location' => 'education.bac.location',
                'period' => 'education.bac.period',
                'logo' => 'schools/aristide-briand.webp',
                'description' => [
                    'education.bac.description.1',
                ]
            ]
        ];
    }

    public function getProjects(): array
    {
        return [
            [
                'title' => 'projects.klimber_kids.title',
                'description' => 'projects.klimber_kids.description',
                'image' => 'projects/klimber-kids.png',
                'github' => null,
                'website' => 'https://klimber-kids.com',
                'demo' => null,
                'technologies' => array_map(fn($tech) => $this->findTechByName($tech), 
                    ['PHP', 'HTML', 'CSS', 'JavaScript', 'Symfony', 'Bootstrap', 'MySQL', 'Git'])
            ],
            [
                'title' => 'projects.dashboard-si.title',
                'description' => 'projects.dashboard-si.description',
                'image' => 'projects/dashboard-si.png',
                'github' => 'https://github.com/Will6855/IT-Department-Dashboard',
                'website' => null,
                'demo' => 'https://it-department-dashboard-demo.vercel.app/',
                'technologies' => array_map(fn($tech) => $this->findTechByName($tech), 
                    ['Python', 'HTML', 'CSS', 'JavaScript', 'Flask', 'REST APIs'])
            ],
            [
                'title' => 'projects.email-sender.title',
                'description' => 'projects.email-sender.description',
                'image' => 'projects/email-sender.png',
                'github' => 'https://github.com/Will6855/HTML-Email-Sender',
                'website' => null,
                'demo' => null,
                'technologies' => array_map(fn($tech) => $this->findTechByName($tech), 
                    ['HTML', 'CSS', 'JavaScript', 'TypeScript', 'Next.js', 'Tailwind CSS', 'Node.js', 'Git'])
            ],
        ];
    }

    public function getPersonalInfo(): array
    {
        return [
            'name' => 'Guillaume PIARD',
            'files' => [
                'cv' => 'files/cv_guillaume_piard.pdf',
                'profile_image' => 'images/profile-image.webp'
            ],
            'social' => [
                'github' => 'https://github.com/Will6855',
                'email' => 'gpiard27@gmail.com',
                'linkedin' => 'https://linkedin.com/in/piard-guillaume'
            ]
        ];
    }
}