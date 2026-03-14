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
                'title' => 'experiences.company.title',
                'contract' => 'experiences.company.contract',
                'company' => 'COMPANY NAME',
                'location' => 'experiences.company.location',
                'period' => 'experiences.company.period',
                'logo' => 'companies/company.png',
                'logo_large' => 'companies/company_large.webp',
                'description' => $this->getTranslationList('experiences.company.description'),
                'technologies' => array_map(fn($tech) => $this->findTechByName($tech), 
                    ['HTML', 'CSS', 'JavaScript', 'TypeScript', 'Next.js', 'Tailwind CSS', 'Node.js', 'SQLite', 'PostgreSQL', 'REST APIs', 'Agile', 'Git'])
            ]
        ];
    }

    public function getEducations(): array
    {
        return [
            [
                'degree' => 'education.school.degree',
                'school' => 'SCHOOL NAME',
                'location' => 'education.school.location',
                'period' => 'education.school.period',
                'logo' => 'schools/school.webp',
                'logo_large' => 'schools/school_large.png',
                'description' => $this->getTranslationList('education.school.description')
            ]
        ];
    }

    public function getProjects(): array
    {
        return [
            [
                'title' => 'projects.project.title',
                'description' => 'projects.project.description',
                'image' => 'projects/project.png',
                'details' => 'projects.project.details',
                'details_data' => [
                    'intro' => 'projects.project.details.intro',
                    'sections' => $this->getProjectSections('projects.project.details.sections'),
                    'conclusion' => 'projects.project.details.conclusion'
                ],
                'github' => 'https://google.com',
                'website' => 'https://google.com',
                'demo' => 'https://google.com',
                'technologies' => array_map(fn($tech) => $this->findTechByName($tech), 
                    ['HTML', 'CSS', 'JavaScript', 'TypeScript', 'Next.js', 'Tailwind CSS', 'Node.js', 'MongoDB', 'REST APIs', 'Agile', 'Git', 'Docker'])
            ]
        ];
    }

    public function getPersonalInfo(): array
    {
        return [
            'name' => 'Your Name',
            'email' => 'contact@your-website.dev',
            'birthdate' => '1970-01-01',
            'files' => [
                'cv' => 'files/cv.pdf',
                'profile_image' => 'images/profile-image.jpg'
            ],
            'social' => [
                'github' => 'https://github.com/your-name',
                'email' => 'your-name@gmail.com',
                'linkedin' => 'https://linkedin.com/in/your-name'
            ]
        ];
    }
}