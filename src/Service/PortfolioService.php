<?php

namespace App\Service;

use Symfony\Contracts\Translation\TranslatorInterface;

class PortfolioService
{
    public function __construct(
        private TranslatorInterface $translator
    ) {
    }

    /**
     * Helper to automatically retrieve all indexed translation keys for a given prefix.
     * Searches for keys like prefix.0, prefix.1, etc. until one is not found.
     */
    private function getTranslationList(string $prefix): array
    {
        $keys = [];
        $i = 0;
        while (true) {
            $key = "$prefix.$i";
            // In Symfony, if a translation doesn't exist, it returns the key itself.
            if ($this->translator->trans($key) === $key) {
                break;
            }
            $keys[] = $key;
            $i++;
        }
        return $keys;
    }

    /**
     * Helper to automatically retrieve all project sections for a project.
     */
    private function getProjectSections(string $prefix): array
    {
        $sections = [];
        $i = 0;
        while (true) {
            $base = "$prefix.$i";
            $titleKey = "$base.title";
            // Check if section exists by checking if title translation key exists
            if ($this->translator->trans($titleKey) === $titleKey) {
                break;
            }

            $type = $this->translator->trans("$base.type");
            $section = [
                'title' => $titleKey,
                'type' => $type,
            ];

            if ($type === 'list') {
                $section['items'] = $this->getTranslationList("$base.items");
            } elseif ($type === 'text') {
                $section['content'] = "$base.content";
            } elseif ($type === 'keyvalue') {
                $section['items'] = $this->getKeyValueList("$base.items");
            }
            
            $sections[] = $section;
            $i++;
        }
        return $sections;
    }

    /**
     * Helper to retrieve key-value pairs for project sections.
     */
    private function getKeyValueList(string $prefix): array
    {
        $items = [];
        $i = 0;
        while (true) {
            $base = "$prefix.$i";
            $keyKey = "$base.key";
            if ($this->translator->trans($keyKey) === $keyKey) {
                break;
            }
            
            $items[] = [
                'key' => $keyKey,
                'value' => "$base.value"
            ];
            $i++;
        }
        return $items;
    }
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
                    [ 'image' => 'skills/typescript.svg', 'name' => 'TypeScript', 'display' => true ],
                    [ 'icon' => 'fa-brands fa-php', 'name' => 'PHP', 'display' => true ],
                    [ 'icon' => 'fa-brands fa-python', 'name' => 'Python', 'display' => true ],
                    [ 'icon' => 'fa-brands fa-java', 'name' => 'Java', 'display' => true ],
                    [ 'icon' => 'fa-brands fa-rust', 'name' => 'Rust', 'display' => true ],
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
                    [ 'image' => 'skills/flask.png', 'name' => 'Flask', 'display' => true ],
                    [ 'image' => 'skills/next-js.svg', 'name' => 'Next.js', 'display' => true ],
                    [ 'icon' => 'fa-brands fa-bootstrap', 'name' => 'Bootstrap', 'display' => true ],
                    [ 'image' => 'skills/tailwind.png', 'name' => 'Tailwind CSS', 'display' => true ],
                    [ 'image' => 'skills/vite.svg', 'name' => 'Vite', 'display' => false ],
                    [ 'icon' => 'fa-brands fa-react', 'name' => 'React', 'display' => true ]
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
                    [ 'icon' => 'fa-solid fa-database', 'name' => 'SQLite', 'display' => true ],
                    [ 'icon' => 'fa-solid fa-database', 'name' => 'PostgreSQL', 'display' => true ],
                    [ 'icon' => 'fa-solid fa-database', 'name' => 'MongoDB', 'display' => true ],
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
                    [ 'icon' => 'fa-brands fa-git-alt', 'name' => 'Git', 'display' => true ],
                    [ 'image' => 'skills/postman.svg', 'name' => 'Postman', 'display' => true ],
                ]
            ],
            'DevOps' => [
                'icon' => 'fa-solid fa-server',
                'color_bg' => 'gray-500/40',
                'color_text' => 'gray-400',
                'color_hover' => 'gray-800',
                'items' => [
                    [ 'icon' => 'fa-brands fa-docker', 'name' => 'Docker', 'display' => false ],
                    [ 'icon' => 'fa-brands fa-linux', 'name' => 'Linux', 'display' => false ],
                    [ 'icon' => 'fa-brands fa-github', 'name' => 'Github Action', 'display' => false ],
                    [ 'icon' => 'fa-brands fa-nginx', 'name' => 'Nginx', 'display' => false ],
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
                'title' => 'experiences.sogea3.title',
                'contract' => 'experiences.sogea3.contract',
                'company' => 'SOGEA Environnement (Groupe VINCI)',
                'location' => 'experiences.sogea3.location',
                'period' => 'experiences.sogea3.period',
                'logo' => 'companies/sogea.png',
                'logo_large' => 'companies/sogea_large.webp',
                'description' => $this->getTranslationList('experiences.sogea3.description'),
                'technologies' => '',
            ],
            [
                'title' => 'experiences.sogea2.title',
                'contract' => 'experiences.sogea2.contract',
                'company' => 'SOGEA Environnement (Groupe VINCI)',
                'location' => 'experiences.sogea2.location',
                'period' => 'experiences.sogea2.period',
                'logo' => 'companies/sogea.png',
                'logo_large' => 'companies/sogea_large.webp',
                'description' => $this->getTranslationList('experiences.sogea2.description'),
                'technologies' => '',
                // 'technologies' => array_map(fn($tech) => $this->findTechByName($tech), 
                //     ['HTML', 'CSS', 'TypeScript', 'Rust', 'Next.js', 'Tailwind CSS', 'SQLite', 'Agile', 'Git'])
            ],
            [
                'title' => 'experiences.sogea.title',
                'contract' => 'experiences.sogea.contract',
                'company' => 'SOGEA Environnement (Groupe VINCI)',
                'location' => 'experiences.sogea.location',
                'period' => 'experiences.sogea.period',
                'logo' => 'companies/sogea.png',
                'logo_large' => 'companies/sogea_large.webp',
                'description' => $this->getTranslationList('experiences.sogea.description'),
                'technologies' => array_map(fn($tech) => $this->findTechByName($tech), 
                    ['HTML', 'CSS', 'JavaScript', 'TypeScript', 'Next.js', 'Tailwind CSS', 'Node.js', 'SQLite', 'PostgreSQL', 'REST APIs', 'Agile', 'Git'])
            ],
            [
                'title' => 'experiences.klimber_kids.title',
                'contract' => 'experiences.klimber_kids.contract',
                'company' => 'Klimber-Kids',
                'location' => 'experiences.klimber_kids.location',
                'period' => 'experiences.klimber_kids.period',
                'logo' => 'companies/klimber-kids.svg',
                'logo_large' => 'companies/klimber-kids.svg',
                'description' => $this->getTranslationList('experiences.klimber_kids.description'),
                'technologies' => array_map(fn($tech) => $this->findTechByName($tech), 
                    ['PHP', 'HTML', 'CSS', 'JavaScript', 'Symfony', 'Bootstrap', 'MySQL', 'REST APIs', 'Git'])
            ],
            [
                'title' => 'experiences.cs_lane.title',
                'contract' => 'experiences.cs_lane.contract',
                'company' => 'CS-Lane',
                'location' => 'experiences.cs_lane.location',
                'period' => 'experiences.cs_lane.period',
                'logo' => 'companies/cs-lane.svg',
                'logo_large' => 'companies/cs-lane.svg',
                'description' => $this->getTranslationList('experiences.cs_lane.description'),
                'technologies' => array_map(fn($tech) => $this->findTechByName($tech), 
                    ['PHP', 'HTML', 'CSS', 'JavaScript', 'Kotlin', 'Swift', 'Bootstrap', 'MySQL', 'REST APIs', 'Agile', 'Git', 'Postman'])
            ],
            [
                'title' => 'experiences.uimm.title',
                'contract' => 'experiences.uimm.contract',
                'company' => 'UIMM Eure Seine Estuaire',
                'location' => 'experiences.uimm.location',
                'period' => 'experiences.uimm.period',
                'logo' => 'companies/uimm.png',
                'logo_large' => 'companies/uimm.png',
                'description' => $this->getTranslationList('experiences.uimm.description'),
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
                'logo' => 'schools/gustave-flaubert.webp',
                'logo_large' => 'schools/gustave-flaubert_large.png',
                'description' => $this->getTranslationList('education.bts.description')
            ],
            [
                'degree' => 'education.bac.degree',
                'school' => 'Lycée Aristide Briand',
                'location' => 'education.bac.location',
                'period' => 'education.bac.period',
                'logo' => 'schools/aristide-briand.webp',
                'logo_large' => 'schools/aristide-briand.webp',
                'description' => $this->getTranslationList('education.bac.description')
            ]
        ];
    }

    public function getProjects(): array
    {
        return [
            [
                'title' => 'projects.annas-library.title',
                'description' => 'projects.annas-library.description',
                'image' => 'projects/annas-library.png',
                'details' => 'projects.annas-library.details',
                'details_data' => [
                    'intro' => 'projects.annas-library.details.intro',
                    'sections' => $this->getProjectSections('projects.annas-library.details.sections'),
                    'conclusion' => 'projects.annas-library.details.conclusion'
                ],
                'github' => null,
                'website' => 'https://annas-library.guillaume-piard.fr',
                'demo' => null,
                'technologies' => array_map(fn($tech) => $this->findTechByName($tech), 
                    ['HTML', 'CSS', 'JavaScript', 'TypeScript', 'Vite', 'Tailwind CSS', 'Node.js', 'REST APIs', 'Git', 'Docker'])
            ],
            [
                'title' => 'projects.play-with-your-friends.title',
                'description' => 'projects.play-with-your-friends.description',
                'image' => 'projects/play-with-your-friends.png',
                'details' => 'projects.play-with-your-friends.details',
                'details_data' => [
                    'intro' => 'projects.play-with-your-friends.details.intro',
                    'sections' => $this->getProjectSections('projects.play-with-your-friends.details.sections'),
                    'conclusion' => 'projects.play-with-your-friends.details.conclusion'
                ],
                'github' => null,
                'website' => 'https://playwithyourfriends.com/',
                'demo' => null,
                'technologies' => array_map(fn($tech) => $this->findTechByName($tech), 
                    ['HTML', 'CSS', 'JavaScript', 'TypeScript', 'Next.js', 'Tailwind CSS', 'Node.js', 'MongoDB', 'REST APIs', 'Agile', 'Git', 'Docker'])
            ],
            [
                'title' => 'projects.inventory-management.title',
                'description' => 'projects.inventory-management.description',
                'image' => 'projects/inventory-management.png',
                'details' => 'projects.inventory-management.details',
                'details_data' => [
                    'intro' => 'projects.inventory-management.details.intro',
                    'sections' => $this->getProjectSections('projects.inventory-management.details.sections'),
                    'conclusion' => 'projects.inventory-management.details.conclusion'
                ],
                'github' => null,
                'website' => null,
                'demo' => null,
                'technologies' => array_map(fn($tech) => $this->findTechByName($tech), 
                    ['HTML', 'CSS', 'JavaScript', 'TypeScript', 'Rust', 'Next.js', 'Tailwind CSS', 'Node.js', 'SQLite', 'PostgreSQL', 'REST APIs', 'Agile', 'Git'])
            ],
            [
                'title' => 'projects.email-sender.title',
                'description' => 'projects.email-sender.description',
                'image' => 'projects/email-sender.png',
                'details' => 'projects.email-sender.details',
                'details_data' => [
                    'intro' => 'projects.email-sender.details.intro',
                    'sections' => $this->getProjectSections('projects.email-sender.details.sections'),
                    'conclusion' => 'projects.email-sender.details.conclusion'
                ],
                'github' => 'https://github.com/Will6855/HTML-Email-Sender',
                'website' => null,
                'demo' => null,
                'technologies' => array_map(fn($tech) => $this->findTechByName($tech), 
                    ['HTML', 'CSS', 'JavaScript', 'TypeScript', 'Next.js', 'Tailwind CSS', 'Node.js', 'SQLite', 'PostgreSQL', 'REST APIs', 'Git'])
            ],
            [
                'title' => 'projects.klimber_kids.title',
                'description' => 'projects.klimber_kids.description',
                'image' => 'projects/klimber-kids.png',
                'details' => 'projects.klimber_kids.details',
                'details_data' => [
                    'intro' => 'projects.klimber_kids.details.intro',
                    'sections' => $this->getProjectSections('projects.klimber_kids.details.sections'),
                    'conclusion' => 'projects.klimber_kids.details.conclusion'
                ],
                'github' => null,
                'website' => 'https://klimber-kids.com',
                'demo' => null,
                'technologies' => array_map(fn($tech) => $this->findTechByName($tech), 
                    ['PHP', 'HTML', 'CSS', 'JavaScript', 'Symfony', 'Bootstrap', 'MySQL', 'REST APIs', 'Git'])
            ],
            [
                'title' => 'projects.dashboard-si.title',
                'description' => 'projects.dashboard-si.description',
                'image' => 'projects/dashboard-si.png',
                'details' => 'projects.dashboard-si.details',
                'details_data' => [
                    'intro' => 'projects.dashboard-si.details.intro',
                    'sections' => $this->getProjectSections('projects.dashboard-si.details.sections'),
                    'conclusion' => 'projects.dashboard-si.details.conclusion'
                ],
                'github' => 'https://github.com/Will6855/IT-Department-Dashboard',
                'website' => null,
                'demo' => null,
                'technologies' => array_map(fn($tech) => $this->findTechByName($tech), 
                    ['Python', 'HTML', 'CSS', 'JavaScript', 'Flask', 'REST APIs'])
            ]
        ];
    }

    public function getPersonalInfo(): array
    {
        return [
            'name' => 'Guillaume PIARD',
            'email' => 'contact@guillaume-piard.fr',
            'birthdate' => '2004-01-06',
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