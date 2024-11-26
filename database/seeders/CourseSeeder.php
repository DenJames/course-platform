<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            // Laravel (category_id: 2) courses
            [
                'category_id' => 2,
                'title' => 'Laravel fra bunden',
                'description' => 'I dette omfattende kursus vil du lære Laravel framework fra bunden af. Vi starter med de grundlæggende koncepter og arbejder os frem til avancerede emner. Du vil lære om MVC-arkitektur, routing, controllers, Eloquent ORM, database migrationer, authentication, authorization, API udvikling, og meget mere. Kurset indeholder praktiske øvelser og projekter, der vil hjælpe dig med at blive en dygtig Laravel udvikler.',
                'price' => 100,
                'image' => 'https://img-c.udemycdn.com/course/240x135/6199917_2f82.jpg'
            ],
            [
                'category_id' => 2,
                'title' => 'Laravel API Udvikling',
                'description' => 'Specialisér dig i at bygge robuste og skalerbare API\'er med Laravel. Dette kursus dækker REST API design, authentication med Sanctum, API ressourcer, validering, rate limiting, og beste praksis for API udvikling. Du vil også lære om API dokumentation, versioning, og hvordan du håndterer fejl og exceptions professionelt.',
                'price' => 120,
                'image' => 'https://img-c.udemycdn.com/course/240x135/6199917_2f82.jpg'
            ],
            [
                'category_id' => 2,
                'title' => 'Laravel Security Masterclass',
                'description' => 'Sikkerhed er afgørende i moderne webapplikationer. Dette kursus fokuserer på sikkerhedsaspekter i Laravel, herunder CSRF beskyttelse, XSS prevention, SQL injection forsvar, authentication systemer, password hashing, og meget mere. Du vil lære at implementere security best practices og beskytte dine Laravel applikationer mod almindelige sikkerhedstrusler.',
                'price' => 150,
                'image' => 'https://img-c.udemycdn.com/course/240x135/6199917_2f82.jpg'
            ],

            // React (category_id: 4) courses
            [
                'category_id' => 4,
                'title' => 'React JS fra bunden',
                'description' => 'Start din rejse med React JS fra det absolutte begynderniveau. Dette kursus guider dig gennem komponent-baseret udvikling, state management, props, hooks, og moderne React patterns. Du vil lære at bygge responsive og dynamiske brugergrænseflader med React\'s deklarative tilgang til UI udvikling.',
                'price' => 200,
                'image' => 'https://img-c.udemycdn.com/course/240x135/4471614_361e_8.jpg'
            ],
            [
                'category_id' => 4,
                'title' => 'Advanced React og Redux',
                'description' => 'Tag dit React game til næste niveau med dette advanced kursus. Vi dykker ned i kompleks state management med Redux, Redux Toolkit, og Redux Saga. Du vil lære om performance optimering, code splitting, lazy loading, og hvordan du strukturerer store React applikationer. Kurset omfatter også testing med Jest og React Testing Library.',
                'price' => 250,
                'image' => 'https://img-c.udemycdn.com/course/240x135/4471614_361e_8.jpg'
            ],
            [
                'category_id' => 4,
                'title' => 'React Native Mobile Apps',
                'description' => 'Lær at bygge native mobile apps til iOS og Android med React Native. Dette kursus dækker platform-specifik styling, navigation, device features, og deployment. Du vil bygge flere fuldt funktionelle apps og lære best practices for mobile app development med React Native.',
                'price' => 300,
                'image' => 'https://img-c.udemycdn.com/course/240x135/4471614_361e_8.jpg'
            ],

            // Vue.js (category_id: 4) courses
            [
                'category_id' => 4,
                'title' => 'Vue JS fra bunden',
                'description' => 'Start din rejse med Vue.js 3 og lær at bygge moderne web applikationer. Dette kursus dækker Vue\'s reaktive data system, komponenter, directives, computed properties, watchers, og Composition API. Du vil også lære om Vue Router, Vuex for state management, og hvordan du integrerer med backend API\'er.',
                'price' => 150,
                'image' => 'https://img-c.udemycdn.com/course/240x135/995016_ebf4_3.jpg'
            ],
            [
                'category_id' => 4,
                'title' => 'Vue.js 3 Composition API',
                'description' => 'Fordyb dig i Vue.js 3\'s Composition API og lær hvordan du kan skrive mere genanvendelig og vedligeholdbar kode. Vi gennemgår refs, reactive, computed, watch, lifecycle hooks, og custom composables. Du vil også lære om TypeScript integration og testing af Vue komponenter.',
                'price' => 180,
                'image' => 'https://img-c.udemycdn.com/course/240x135/995016_ebf4_3.jpg'
            ],

            // Angular (category_id: 4) courses
            [
                'category_id' => 4,
                'title' => 'Angular fra bunden',
                'description' => 'Bliv fortrolig med Angular framework og TypeScript. Dette omfattende kursus dækker komponenter, services, dependency injection, routing, forms, HTTP kommunikation, og RxJS observables. Du vil lære at bygge enterprise-level applikationer med Angular\'s robuste arkitektur.',
                'price' => 250,
                'image' => 'https://img-b.udemycdn.com/course/240x135/756150_c033_4.jpg'
            ],
            [
                'category_id' => 4,
                'title' => 'Angular Testing Masterclass',
                'description' => 'Lær at skrive pålidelige tests for dine Angular applikationer. Vi dækker unit testing med Jasmine, end-to-end testing med Cypress, component testing, service testing, og test coverage. Du vil også lære om test driven development (TDD) i Angular kontekst.',
                'price' => 280,
                'image' => 'https://img-b.udemycdn.com/course/240x135/756150_c033_4.jpg'
            ],

            // Node.js (category_id: 4) courses
            [
                'category_id' => 4,
                'title' => 'Node JS fra bunden',
                'description' => 'Start din rejse med Node.js og server-side JavaScript. Dette kursus dækker asynchronous programming, event loop, streams, buffers, og file system operations. Du vil lære at bygge REST APIs med Express.js, arbejde med MongoDB, og implementere authentication og authorization.',
                'price' => 300,
                'image' => 'https://img-c.udemycdn.com/course/240x135/461160_8d87_6.jpg'
            ],
            [
                'category_id' => 4,
                'title' => 'Node.js Microservices',
                'description' => 'Lær at designe og implementere mikroservice arkitekturer med Node.js. Vi dykker ned i service discovery, load balancing, message queues med RabbitMQ, containerization med Docker, og orchestration med Kubernetes. Praktiske øvelser vil hjælpe dig med at mestre mikroservice patterns.',
                'price' => 350,
                'image' => 'https://img-c.udemycdn.com/course/240x135/461160_8d87_6.jpg'
            ],

            // Python (category_id: 5) courses
            [
                'category_id' => 5,
                'title' => 'Python fra bunden',
                'description' => 'Start din programmerings rejse med Python. Dette kursus dækker grundlæggende programmering koncepter, datastrukturer, objekt-orienteret programmering, file handling, og error handling. Du vil bygge flere praktiske projekter og lære best practices for Python udvikling.',
                'price' => 350,
                'image' => 'https://img-b.udemycdn.com/course/240x135/567828_67d0.jpg'
            ],
            [
                'category_id' => 5,
                'title' => 'Python for Data Science',
                'description' => 'Udforsk data science med Python. Lær at arbejde med NumPy, Pandas, Matplotlib, og Scikit-learn. Du vil mestre data manipulation, visualization, statistisk analyse, og machine learning grundbegreber. Kurset inkluderer praktiske projekter med real-world datasets.',
                'price' => 400,
                'image' => 'https://img-b.udemycdn.com/course/240x135/567828_67d0.jpg'
            ],

            // Django (category_id: 5) courses
            [
                'category_id' => 5,
                'title' => 'Django fra bunden',
                'description' => 'Lær at bygge web applikationer med Django framework. Dette kursus dækker models, views, templates, forms, authentication, admin interface, og database management. Du vil bygge flere fuldt funktionelle websites og lære Django best practices.',
                'price' => 400,
                'image' => 'https://img-c.udemycdn.com/course/240x135/2243250_f8ef_10.jpg'
            ],
            [
                'category_id' => 5,
                'title' => 'Django REST Framework',
                'description' => 'Specialisér dig i at bygge RESTful APIs med Django REST Framework. Vi dækker serializers, viewsets, authentication, permissions, filtering, pagination, og API documentation. Du vil også lære om API testing og deployment best practices.',
                'price' => 450,
                'image' => 'https://img-c.udemycdn.com/course/240x135/2243250_f8ef_10.jpg'
            ],

            // Flask (category_id: 5) courses
            [
                'category_id' => 5,
                'title' => 'Flask fra bunden',
                'description' => 'Start med Flask micro-framework og lær at bygge web applikationer. Dette kursus dækker routing, templates, forms, database integration med SQLAlchemy, user authentication, og REST API udvikling. Du vil også lære om deployment og testing af Flask apps.',
                'price' => 450,
                'image' => 'https://img-c.udemycdn.com/course/240x135/1546884_86af.jpg'
            ],
            [
                'category_id' => 5,
                'title' => 'Advanced Flask Development',
                'description' => 'Tag dit Flask game til næste niveau. Lær om blueprints, custom CLI commands, caching, async tasks med Celery, full-text search, WebSocket integration, og performance optimization. Vi vil også dække security best practices og deployment strategier.',
                'price' => 500,
                'image' => 'https://img-c.udemycdn.com/course/240x135/1546884_86af.jpg'
            ],

            // Java (category_id: 3) courses
            [
                'category_id' => 3,
                'title' => 'Java fra bunden',
                'description' => 'Start din rejse med Java programmering. Dette kursus dækker objekt-orienteret programmering, collections framework, exception handling, file I/O, og multithreading. Du vil bygge flere praktiske projekter og lære Java best practices.',
                'price' => 500,
                'image' => 'https://img-c.udemycdn.com/course/240x135/533682_c10c_4.jpg'
            ],
            [
                'category_id' => 3,
                'title' => 'Java Enterprise Development',
                'description' => 'Lær at udvikle enterprise applikationer med Java. Dette kursus dækker Spring Framework, Hibernate ORM, RESTful web services, microservices arkitektur, og cloud deployment. Du vil også lære om testing og continuous integration.',
                'price' => 550,
                'image' => 'https://img-c.udemycdn.com/course/240x135/533682_c10c_4.jpg'
            ],

            // Spring Boot (category_id: 3) courses
            [
                'category_id' => 3,
                'title' => 'Spring Boot fra bunden',
                'description' => 'Kom i gang med Spring Boot og lær at bygge moderne Java applikationer. Dette kursus dækker dependency injection, Spring MVC, Spring Data JPA, Spring Security, og RESTful web services. Du vil også lære om testing og deployment af Spring Boot apps.',
                'price' => 550,
                'image' => 'https://img-c.udemycdn.com/course/240x135/2167814_a0e6_5.jpg'
            ],
            [
                'category_id' => 3,
                'title' => 'Spring Cloud & Microservices',
                'description' => 'Dyk ned i mikroservice arkitektur med Spring Cloud. Dette kursus dækker service discovery, config management, circuit breakers, API gateway, og distributed tracing. Du vil lære at bygge og deploye skalerbare mikroservice systemer.',
                'price' => 600,
                'image' => 'https://img-c.udemycdn.com/course/240x135/2167814_a0e6_5.jpg'
            ]
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
