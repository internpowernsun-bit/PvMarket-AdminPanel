<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageSection;

class FaqPageSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [

            // ── FAQ HEADER ─────────────────────────────────
            [
                'page'     => 'faq',
                'section'  => 'faq_header',
                'type'     => 'text_block',
                'title'    => 'FAQ',
                'subtitle' => null,
                'extra'    => [
                    'content' => 'Find answers to the most commonly asked questions about pv.market.',
                ],
                'order'    => 1,
                'is_active' => true,
            ],

            // ── FAQ ITEMS ──────────────────────────────────
            [
                'page'     => 'faq',
                'section'  => 'faq_items',
                'type'     => 'faq',
                'title'    => 'Frequently Asked Questions',
                'subtitle' => null,
                'extra'    => [
                    'items' => [
                        [
                            'question' => 'What is pvmarket?',
                            'answer'   => 'pvmarket is a novel, neutral, transparent, advanced, secure, and highly regulated B2B digital marketplace for buying, selling, fair pricing, spot pricing, and bidding of renewable energy components, solar projects and services.',
                        ],
                        [
                            'question' => 'What markets and brands are Available for pv market?',
                            'answer'   => '',
                        ],
                        [
                            'question' => 'What are the critical features of pv market?',
                            'answer'   => '',
                        ],
                        [
                            'question' => 'What benefits pv market provides to their buyers?',
                            'answer'   => '',
                        ],
                        [
                            'question' => 'What benefits are for manufacturers, as sellers?',
                            'answer'   => '',
                        ],
                        [
                            'question' => 'How does pv.market ensure quality and authenticity?',
                            'answer'   => '',
                        ],
                        [
                            'question' => 'How do I register on pv.market?',
                            'answer'   => '',
                        ],
                        [
                            'question' => 'Is pv.market available on mobile?',
                            'answer'   => '',
                        ],
                    ],
                ],
                'order'    => 2,
                'is_active' => true,
            ],

        ];

        foreach ($sections as $data) {
            PageSection::updateOrCreate(
                ['page' => $data['page'], 'section' => $data['section']],
                $data
            );
        }

        $this->command->info('✅ FAQ page seeded!');
    }
}