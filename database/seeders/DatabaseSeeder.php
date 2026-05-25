<?php

namespace Database\Seeders;

use App\Models\About;
use App\Models\Experience;
use App\Models\Service;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@yousifelfarra.com'],
            [
                'name' => 'Yousif Elfarra',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // About / Profile
        About::updateOrCreate(
            ['id' => 1],
            [
                'full_name' => 'Yousif Elfarra',
                'title' => 'Backend Laravel Developer',
                'tagline' => 'Building robust APIs and scalable CRMs with Laravel.',
                'short_bio' => 'I am a passionate Backend Laravel Developer with 4 years of experience building CRMs, RESTful APIs and integrating third-party services.',
                'long_bio' => "Hi, I'm Yousif Elfarra — a backend developer based in Gaza, Palestine. Over the past 4 years I have built a wide range of CRMs and RESTful APIs using Laravel, and integrated many third-party APIs (payment gateways, SMS providers, social platforms, and more).\n\nI hold a bachelor's degree in Networks and Mobile Technology from Al-Aqsa University. I love writing clean, maintainable code, designing solid database schemas, and shipping reliable backends that just work.\n\nI'm always open to interesting collaborations — feel free to reach out!",
                'years_of_experience' => 4,
                'location' => 'Gaza Strip, Palestine',
                'nationality' => 'Palestinian',
                'education_degree' => "Bachelor's in Networks and Mobile Technology",
                'education_university' => 'Al-Aqsa University',
                'email' => 'yousifelfarra0@gmail.com',
                'phone' => '+970-599-761-452',
                'github_url' => 'https://github.com/yousifif7',
                'linkedin_url' => 'https://linkedin.com/in/yousifelfarra',
            ]
        );

        // Skills
        $skills = [
            ['name' => 'PHP', 'category' => 'Backend', 'proficiency' => 95, 'color' => '#777BB4', 'icon' => 'fab fa-php'],
            ['name' => 'Laravel', 'category' => 'Backend', 'proficiency' => 95, 'color' => '#FF2D20', 'icon' => 'fab fa-laravel'],
            ['name' => 'MySQL', 'category' => 'Database', 'proficiency' => 90, 'color' => '#4479A1', 'icon' => 'fas fa-database'],
            ['name' => 'PostgreSQL', 'category' => 'Database', 'proficiency' => 80, 'color' => '#336791', 'icon' => 'fas fa-database'],
            ['name' => 'REST APIs', 'category' => 'Backend', 'proficiency' => 95, 'color' => '#10b981', 'icon' => 'fas fa-plug'],
            ['name' => 'JavaScript', 'category' => 'Frontend', 'proficiency' => 80, 'color' => '#F7DF1E', 'icon' => 'fab fa-js'],
            ['name' => 'Vue.js', 'category' => 'Frontend', 'proficiency' => 75, 'color' => '#4FC08D', 'icon' => 'fab fa-vuejs'],
            ['name' => 'HTML5', 'category' => 'Frontend', 'proficiency' => 90, 'color' => '#E34F26', 'icon' => 'fab fa-html5'],
            ['name' => 'CSS3', 'category' => 'Frontend', 'proficiency' => 85, 'color' => '#1572B6', 'icon' => 'fab fa-css3-alt'],
            ['name' => 'Tailwind CSS', 'category' => 'Frontend', 'proficiency' => 85, 'color' => '#06B6D4', 'icon' => 'fas fa-wind'],
            ['name' => 'Git', 'category' => 'DevOps', 'proficiency' => 90, 'color' => '#F05032', 'icon' => 'fab fa-git-alt'],
            ['name' => 'Docker', 'category' => 'DevOps', 'proficiency' => 70, 'color' => '#2496ED', 'icon' => 'fab fa-docker'],
            ['name' => 'Linux', 'category' => 'DevOps', 'proficiency' => 80, 'color' => '#FCC624', 'icon' => 'fab fa-linux'],
            ['name' => 'Redis', 'category' => 'Database', 'proficiency' => 75, 'color' => '#DC382D', 'icon' => 'fas fa-bolt'],
            ['name' => 'Livewire', 'category' => 'Backend', 'proficiency' => 80, 'color' => '#FB70A9', 'icon' => 'fas fa-bolt-lightning'],
        ];

        foreach ($skills as $i => $skill) {
            Skill::updateOrCreate(
                ['name' => $skill['name']],
                array_merge($skill, ['sort_order' => $i, 'is_active' => true])
            );
        }

        // Services
        $services = [
            [
                'title' => 'Backend Development',
                'icon' => 'fas fa-server',
                'description' => 'Designing and building scalable backends with Laravel — clean code, solid architecture, and best practices.',
                'sort_order' => 1,
            ],
            [
                'title' => 'RESTful API Development',
                'icon' => 'fas fa-plug',
                'description' => 'Building secure, well-documented REST APIs for mobile apps and web frontends, with authentication and rate limiting.',
                'sort_order' => 2,
            ],
            [
                'title' => 'CRM Systems',
                'icon' => 'fas fa-users-cog',
                'description' => 'Building custom CRM applications tailored to your business — leads, contacts, deals, automations, and reporting.',
                'sort_order' => 3,
            ],
            [
                'title' => 'Third-party API Integration',
                'icon' => 'fas fa-puzzle-piece',
                'description' => 'Integrating payment gateways, SMS providers, social platforms, and any external API into your application.',
                'sort_order' => 4,
            ],
            [
                'title' => 'Database Design',
                'icon' => 'fas fa-database',
                'description' => 'Designing normalized, performant database schemas with proper indexing and migrations.',
                'sort_order' => 5,
            ],
            [
                'title' => 'API Maintenance & Support',
                'icon' => 'fas fa-tools',
                'description' => 'Long-term support for existing Laravel projects — bug fixes, refactoring, performance tuning, and upgrades.',
                'sort_order' => 6,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['title' => $service['title']], array_merge($service, ['is_active' => true]));
        }

        // Experience
        $experiences = [
            [
                'position' => 'Backend Laravel Developer',
                'company' => 'Freelance',
                'location' => 'Remote',
                'description' => 'Built CRMs and RESTful APIs for various clients. Integrated multiple third-party services and delivered end-to-end backend solutions.',
                'start_date' => now()->subYears(2)->toDateString(),
                'end_date' => null,
                'is_current' => true,
                'sort_order' => 1,
            ],
            [
                'position' => 'Laravel Developer',
                'company' => 'Tech Company',
                'location' => 'Gaza',
                'description' => 'Developed and maintained Laravel applications, designed database schemas, and built secure REST APIs consumed by mobile apps.',
                'start_date' => now()->subYears(4)->toDateString(),
                'end_date' => now()->subYears(2)->toDateString(),
                'is_current' => false,
                'sort_order' => 2,
            ],
        ];

        foreach ($experiences as $exp) {
            Experience::updateOrCreate(
                ['position' => $exp['position'], 'company' => $exp['company']],
                $exp
            );
        }
    }
}
