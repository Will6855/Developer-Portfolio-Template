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
                    [ 'image' => 'skills/typescript.svg', 'name' => 'TypeScript', 'display' => true ],
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
                    [ 'image' => 'skills/flask.png', 'name' => 'Flask', 'display' => true ],
                    [ 'image' => 'skills/next-js.svg', 'name' => 'Next.js', 'display' => true ],
                    [ 'icon' => 'fa-brands fa-bootstrap', 'name' => 'Bootstrap', 'display' => true ],
                    [ 'image' => 'skills/tailwind.png', 'name' => 'Tailwind CSS', 'display' => true ]
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
                    [ 'icon' => 'fa-solid fa-database', 'name' => 'PostgreSQL', 'display' => false ],
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
                'company' => 'COMPANY NAME',
                'location' => 'experiences.company.location',
                'period' => 'experiences.company.period',
                'logo' => 'companies/company.png',
                'description' => [
                    'experiences.company.description.1',
                    'experiences.company.description.2',
                    'experiences.company.description.3'
                ],
                'technologies' => array_map(fn($tech) => $this->findTechByName($tech), 
                    ['HTML', 'CSS', 'JavaScript', 'TypeScript', 'Next.js', 'Tailwind CSS', 'Node.js', 'SQLite', 'PostgreSQL', 'REST APIs', 'Git'])
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
                'logo' => 'schools/school.png',
                'description' => [
                    'education.school.description.1',
                ]
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
                'github' => 'https://google.com/',
                'website' => 'https://google.com/',
                'demo' => 'https://google.com/',
                'technologies' => array_map(fn($tech) => $this->findTechByName($tech), 
                    ['HTML', 'CSS', 'JavaScript', 'TypeScript', 'Next.js', 'Tailwind CSS', 'Node.js', 'SQLite', 'PostgreSQL', 'REST APIs', 'Git'])
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