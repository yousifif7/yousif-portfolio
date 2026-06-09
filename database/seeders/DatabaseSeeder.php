<?php

namespace Database\Seeders;

use App\Models\About;
use App\Models\DevelopmentOffering;
use App\Models\Experience;
use App\Models\Review;
use App\Models\Service;
use App\Models\Setting;
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
                'password' => Hash::make('Yousif2001**'),
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
            ]
        );

        // Default social links (now stored in settings)
        $defaultLinks = [
            'github_url' => 'https://github.com/yousifif7',
            'linkedin_url' => 'https://linkedin.com/in/yousifelfarra',
        ];
        foreach ($defaultLinks as $key => $url) {
            Setting::set($key, $url, 'url', 'links');
        }

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

        // Development offerings (Hire page)
        $offerings = [
            [
                'title' => 'Custom CRM Systems',
                'slug' => 'custom-crm-systems',
                'icon' => 'fas fa-users-cog',
                'description' => 'Tailored customer relationship management platforms — leads, pipelines, automations, and reporting built with Laravel.',
                'sort_order' => 1,
            ],
            [
                'title' => 'CMS & Admin Panels',
                'slug' => 'cms-admin-panels',
                'icon' => 'fas fa-columns',
                'description' => 'Custom content management systems and admin dashboards with role-based access, media libraries, and workflow tools.',
                'sort_order' => 2,
            ],
            [
                'title' => 'HRMS Solutions',
                'slug' => 'hrms-solutions',
                'icon' => 'fas fa-id-badge',
                'description' => 'Human resource management systems — employee records, attendance, payroll integrations, and leave management.',
                'sort_order' => 3,
            ],
            [
                'title' => 'Business Websites',
                'slug' => 'business-websites',
                'icon' => 'fas fa-globe',
                'description' => 'Professional corporate and portfolio websites with Laravel backends, contact forms, and content management.',
                'sort_order' => 4,
            ],
            [
                'title' => 'SaaS Platforms',
                'slug' => 'saas-platforms',
                'icon' => 'fas fa-cloud',
                'description' => 'Multi-tenant SaaS applications with subscriptions, billing, user onboarding, and scalable architecture.',
                'sort_order' => 5,
            ],
            [
                'title' => 'RESTful APIs',
                'slug' => 'restful-apis',
                'icon' => 'fas fa-plug',
                'description' => 'Secure, well-documented REST APIs for mobile apps and SPAs — authentication, versioning, and rate limiting.',
                'sort_order' => 6,
            ],
            [
                'title' => 'E-Commerce Backends',
                'slug' => 'ecommerce-backends',
                'icon' => 'fas fa-shopping-cart',
                'description' => 'Order management, inventory, payment gateway integrations, and checkout flows powered by Laravel.',
                'sort_order' => 7,
            ],
            [
                'title' => 'Third-Party Integrations',
                'slug' => 'third-party-integrations',
                'icon' => 'fas fa-puzzle-piece',
                'description' => 'Payment gateways, SMS providers, social APIs, webhooks, and any external service integration.',
                'sort_order' => 8,
            ],
            [
                'title' => 'App Maintenance & Support',
                'slug' => 'app-maintenance-support',
                'icon' => 'fas fa-tools',
                'description' => 'Long-term support for existing Laravel projects — bug fixes, upgrades, performance tuning, and refactoring.',
                'sort_order' => 9,
            ],
        ];

        foreach ($offerings as $offering) {
            DevelopmentOffering::updateOrCreate(
                ['slug' => $offering['slug']],
                array_merge($offering, ['is_active' => true])
            );
        }

        // Sample approved reviews
        $reviews = [
            [
                'name' => 'Ahmed Hassan',
                'email' => 'ahmed.hassan@example.com',
                'rating' => 5,
                'content' => 'Yousif delivered an outstanding CRM for our sales team. Clean architecture, on-time delivery, and excellent communication throughout the project. Highly recommended for any Laravel backend work.',
                'company' => 'SalesFlow Ltd',
                'role' => 'CTO',
                'status' => 'approved',
                'approved_at' => now()->subDays(12),
            ],
            [
                'name' => 'Sarah Mitchell',
                'email' => 'sarah.m@example.com',
                'rating' => 5,
                'content' => 'We hired Yousif to build our REST API and he exceeded expectations. The documentation was thorough, the code was well-structured, and he proactively suggested improvements we hadn\'t considered.',
                'company' => 'NovaTech',
                'role' => 'Product Manager',
                'status' => 'approved',
                'approved_at' => now()->subDays(28),
            ],
            [
                'name' => 'Omar Khalil',
                'email' => 'omar.k@example.com',
                'rating' => 5,
                'content' => 'Professional, reliable, and technically strong. Yousif built our HRMS from scratch with complex payroll rules and third-party integrations. Would definitely work with him again.',
                'company' => 'Gulf HR Solutions',
                'role' => 'Operations Director',
                'status' => 'approved',
                'approved_at' => now()->subDays(45),
            ],
            [
                'name' => 'James Peterson',
                'email' => 'j.peterson@example.com',
                'rating' => 4,
                'content' => 'Great experience working on our SaaS backend. Yousif understood our multi-tenant requirements quickly and delivered a solid foundation we could build on. Minor timezone differences but always responsive.',
                'company' => 'CloudMetrics',
                'role' => 'Founder',
                'status' => 'approved',
                'approved_at' => now()->subDays(60),
            ],
            [
                'name' => 'Layla Mansour',
                'email' => 'layla.m@example.com',
                'rating' => 5,
                'content' => 'Yousif integrated three payment gateways and an SMS provider into our e-commerce platform flawlessly. His Laravel expertise saved us weeks of development time.',
                'company' => 'ShopPalestine',
                'role' => 'Technical Lead',
                'status' => 'approved',
                'approved_at' => now()->subDays(75),
            ],
        ];

        foreach ($reviews as $review) {
            Review::updateOrCreate(
                ['email' => $review['email'], 'name' => $review['name']],
                $review
            );
        }
    }
}
