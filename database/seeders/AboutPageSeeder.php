<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageSection;

class AboutPageSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [

            // ── INTRO ──────────────────────────────────────
            [
                'page'     => 'about',
                'section'  => 'intro',
                'type'     => 'richtext',        // just a big textarea, no title/subtitle
                'title'    => 'About pv.market',
                'subtitle' => null,
                'extra'    => [
                    'content' => '<p><strong>pv.market</strong> is a novel, neutral, transparent, advanced, secure, and highly regulated digital marketplace designed to streamline bulk transactions of PV components.</p>
<p>Accessible via cloud platforms, Android, and iOS, pv.market ensures that only registered and authenticated users can participate—mirroring the disciplined, fair, and controlled structure of modern stock exchanges.</p>
<p>Looking ahead, pv.market plans to broaden its ecosystem by introducing additional renewable energy products and services, including carbon trading and cross-border energy trade.</p>',
                ],
                'order'    => 1,
            ],

            // ── KEY FEATURES ───────────────────────────────
            [
                'page'     => 'about',
                'section'  => 'key_features',
                'type'     => 'cards',           // title + subtitle + card editor
                'title'    => 'Key Features',
                'subtitle' => 'Enhanced features for a superior marketplace experience',
                'extra'    => [
                    'items' => [
                        ['icon' => '🤖', 'title' => 'AI-Enabled Platform',         'desc' => 'Smarter navigation, personalized recommendations, and efficient product discovery.'],
                        ['icon' => '⚡', 'title' => 'pv-AI Chatbot Assistant',     'desc' => 'Instant support, sourcing guidance, and faster decision-making.'],
                        ['icon' => '🔍', 'title' => 'Advanced Search & Filtering', 'desc' => 'Quick, accurate product selection with powerful filtering tools.'],
                        ['icon' => '🏷️', 'title' => 'Exclusive Brand Pages',       'desc' => 'Clearer product comparison and streamlined research.'],
                    ],
                ],
                'order'    => 2,
            ],

            // ── HOW IT WORKS ───────────────────────────────
            [
                'page'     => 'about',
                'section'  => 'how_it_works',
                'type'     => 'cards_no_icon',   // cards without icon field
                'title'    => 'How pv.market Works',
                'subtitle' => null,
                'extra'    => [
                    'items' => [
                        ['title' => 'Demand-Driven Hybrid Distribution Model', 'desc' => 'pv.market operates on a demand-driven hybrid distribution model, combining the strength of both online and offline ecosystems. We do not pre-purchase or stock inventory.'],
                        ['title' => 'Seller Portal Empowerment',               'desc' => 'The Seller Portal empowers manufacturers, distributors, and suppliers with full commercial control over regional sales and brand representation.'],
                    ],
                ],
                'order'    => 3,
            ],

            // ── AI SECTION ─────────────────────────────────
            [
                'page'     => 'about',
                'section'  => 'ai_enabled',
                'type'     => 'cards',
                'title'    => 'Introducing AI-Enabled pv.market',
                'subtitle' => 'A smarter, faster, and more powerful pv.market is coming your way',
                'extra'    => [
                    'description' => 'We are launching next-generation AI features that deliver seamless sourcing, better decision-making, and a significantly enhanced marketplace experience.',
                    'items' => [
                        ['icon' => '🤖', 'title' => 'pv-AI (AI Bot)',         'desc' => 'Intelligent assistant for sourcing and support.'],
                        ['icon' => '🧠', 'title' => 'AI-Driven Discovery',    'desc' => 'Smart product and supplier recommendations.'],
                        ['icon' => '💡', 'title' => 'Intelligent Navigation', 'desc' => 'Seamless, context-aware browsing experience.'],
                        ['icon' => '📈', 'title' => 'Automated Insights',     'desc' => 'Data-driven market and pricing intelligence.'],
                    ],
                ],
                'order'    => 4,
            ],

            // ── DaaS ───────────────────────────────────────
            [
                'page'     => 'about',
                'section'  => 'daas',
                'type'     => 'cards',
                'title'    => 'Introducing Distribution-as-a-Service (DaaS)',
                'subtitle' => 'Transforming the way solar products move across the world',
                'extra'    => [
                    'description' => "Distribution-as-a-Service (DaaS) is not just a model—it is a transformation in the way solar products move across the world.\n\nJust as SaaS revolutionized software, DaaS integrates the entire distribution ecosystem into a single, frictionless solution.\n\nDaaS ensures availability, reliability, and efficiency—empowering partners to scale faster.",
                    'items' => [
                        ['icon' => '🔗', 'title' => 'Smart Supply-Chain Management', 'desc' => 'Efficient and intelligent supply chain operations.'],
                        ['icon' => '⚙️', 'title' => 'Digital Tools & Automation',    'desc' => 'Marketplace automation and digital solutions.'],
                        ['icon' => '💳', 'title' => 'Financing & Credit Support',    'desc' => 'Flexible financing options for partners.'],
                        ['icon' => '🔧', 'title' => 'Technical Services',            'desc' => 'Project lifecycle and technical support.'],
                        ['icon' => '📊', 'title' => 'Performance Visibility',        'desc' => 'End-to-end performance tracking.'],
                    ],
                ],
                'order'    => 5,
            ],

            // ── VISION & MISSION ───────────────────────────
            [
                'page'     => 'about',
                'section'  => 'vision',
                'type'     => 'text_block',      // just title + content textarea
                'title'    => 'Our Vision',
                'subtitle' => null,
                'extra'    => [
                    'content' => 'Our goal is to maximize solarization while minimizing costs and ensuring that solar energy is available in the most straightforward, adaptable, and efficient manner possible.',
                ],
                'order'    => 6,
            ],
            [
                'page'     => 'about',
                'section'  => 'mission',
                'type'     => 'text_block',
                'title'    => 'Our Mission',
                'subtitle' => null,
                'extra'    => [
                    'content' => 'Our aim is to attain our objectives by consolidating and coordinating market demand, notably enhancing product accessibility, guaranteeing convenience of transactions via a unified point of contact.',
                ],
                'order'    => 7,
            ],

            // ── ORANGE GROUP ───────────────────────────────
            [
                'page'     => 'about',
                'section'  => 'orange_group',
                'type'     => 'richtext',
                'title'    => 'About Orange Group',
                'subtitle' => null,
                'extra'    => [
                    'content' => "Established in 2013 by Mr. LK Verma, Founder & Managing Director of Orange Overseas FZE, Power&sun, PnS One, pvmarket, AREEMSS, and hoomi the Orange Group has grown into a trusted, system-driven enterprise headquartered in the Hamriyah Free Zone, UAE.\n\nThe Group began as a specialized import–export company serving the Middle East, Africa, South East Asia, and Europe. In 2024, pv.market Ltd was registered in Masdar City, Abu Dhabi, as a 100% subsidiary of Orange Overseas FZE.\n\nAcross all operations, Orange Group prioritizes efficiency through advanced ERP systems and remains committed to meaningful community engagement through active CSR initiatives.",
                ],
                'order'    => 8,
            ],

            // ── OUR COMPANIES ──────────────────────────────
            [
                'page'     => 'about',
                'section'  => 'companies',
                'type'     => 'logos',           // logo grid editor
                'title'    => 'Our Companies',
                'subtitle' => null,
                'extra'    => [
                    'items' => [
                        ['name' => 'Orange',    'logo' => ''],
                        ['name' => 'Power&Sun', 'logo' => ''],
                        ['name' => 'PnS One',   'logo' => ''],
                        ['name' => 'pvmarket',  'logo' => ''],
                        ['name' => 'AREEMSS',   'logo' => ''],
                        ['name' => 'hoomi',     'logo' => ''],
                    ],
                ],
                'order'    => 9,
            ],

        ];

        foreach ($sections as $data) {
            PageSection::updateOrCreate(
                ['page' => $data['page'], 'section' => $data['section']],
                $data
            );
        }

        $this->command->info('✅ About page seeded!');
    }
}