<?php
// database/seeders/ContactPageSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageSection;

class ContactPageSeeder extends Seeder
{
    public function run(): void
    {
        // Avoid duplicate runs
        PageSection::where('page', 'contact')->delete();

        PageSection::insert([
            [
                'page'        => 'contact',
                'section'     => 'hero',
                'title'       => 'Get in Touch',
                'subtitle'    => null,
                'description' => 'Have questions about our solar products or services? Our team is ready to help you find the perfect energy solution.',
                'button_text' => null,
                'button_link' => null,
                'image'       => null,
                'alt_tag'     => null,
                'is_active'   => true,
                'order'       => 1,
                'extra'       => [
                    'email'            => 'info@pv.market',
                    'email_label'      => 'Send us a message anytime',
                    'phone'            => '+971 523825549',
                    'phone_label'      => 'Mon-Fri from 8am to 6pm',
                    'address'          => 'Masdar City, Abu Dhabi, UAE',
                    'social_facebook'  => '#',
                    'social_twitter'   => '#',
                    'social_instagram' => '#',
                    'social_linkedin'  => '#',
                    'social_youtube'   => '#',
                ],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page'        => 'contact',
                'section'     => 'form',
                'title'       => 'Send a Message',
                'subtitle'    => null,
                'description' => "Fill out the form and we'll get back to you within 24 hours.",
                'button_text' => 'Send Message',
                'button_link' => null,
                'image'       => null,
                'alt_tag'     => null,
                'is_active'   => true,
                'order'       => 2,
                'extra'       => [],
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}