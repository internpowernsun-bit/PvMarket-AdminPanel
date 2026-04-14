<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageSection;

class TermsPageSeeder extends Seeder
{
    public function run(): void
    {
        // Clear old records first
        PageSection::where('page', 'terms')->delete();

        $sections = [

            // ── 1. Header ──────────────────────────────────────────────────
            [
                'page'      => 'terms',
                'section'   => 'terms_header',
                'type'      => 'text_block',
                'title'     => 'E-Commerce Vendor Agreement',
                'subtitle'  => 'Last Updated: October 28, 2025',
                'extra'     => ['content' => ''],
                'order'     => 1,
                'is_active' => true,
            ],

            // ── 2. Content Sections (drives both sidebar + content) ─────────
            // Uses 'sections' type: each item has a 'title' (sidebar link)
            // and 'content' (richtext) — admin can edit each section separately
            // and use "Add Section" button to add more, or ✕ to remove
            [
                'page'      => 'terms',
                'section'   => 'terms_content',
                'type'      => 'sections',
                'title'     => 'Terms & Conditions Sections',
                'subtitle'  => null,
                'extra'     => [
                    'items' => [
                        [
                            'title'   => 'Preamble',
                            'content' => '<p>This E-Commerce Vendor Agreement ("Agreement") is entered into as of the date of acceptance through digital acknowledgement ("Effective Date"), BY AND BETWEEN, pv.market BY POWER N SUN, registered address: ABC and the individual or entity agreeing to the terms herein registering to sell Products through the Platform ("Vendor"/ "You"/"Your").</p><p>WHEREAS The Platform is operating an online marketplace accessible through the website (pv.market), or any other platforms or mobile applications ("Platform"/"Us"/"We") operated by pv.market BY POWER N SUN.</p><p>WHEREAS The Platform is commonly known as pv.market.</p><p>AND WHEREAS the Vendor has agreed to comply with the terms and conditions stipulated by the Platform for listing and selling their Products, and the Platform has agreed to provide the Vendor with the necessary digital infrastructure and services to facilitate the sale of their Products to the end customers ("Customer").</p><p>The Platform and Vendor may be referred to individually as the "Party" and collectively as the "Parties".</p><p>NOW, THEREFORE, in consideration of the mutual covenants and agreements hereinafter set forth and for other good and valuable consideration, the receipt and sufficiency of which are hereby acknowledged, the Parties agree as follows:</p>',
                        ],
                        [
                            'title'   => '1. Vendor Eligibility',
                            'content' => '<p>To register as a Vendor on pv.market, you must:</p><ul><li>Be a legally registered business entity or individual with the legal capacity to enter into binding contracts.</li><li>Provide accurate, complete, and up-to-date information during the registration process.</li><li>Comply with all applicable local, national, and international laws and regulations related to your business and the products you intend to sell.</li><li>Not have been previously suspended or removed from pv.market or any other e-commerce platform for policy violations.</li></ul><p>pv.market reserves the right to verify vendor information and reject or terminate any vendor account at its sole discretion.</p>',
                        ],
                        [
                            'title'   => '2. Account and Registration',
                            'content' => '<p>Vendors must create an account on pv.market to list and sell products. You are responsible for:</p><ul><li>Maintaining the confidentiality of your account credentials.</li><li>All activities that occur under your account.</li><li>Notifying pv.market immediately of any unauthorized use of your account.</li></ul><p>pv.market will not be liable for any loss or damage arising from your failure to comply with these obligations.</p>',
                        ],
                        [
                            'title'   => '3. Acceptable Use',
                            'content' => '<p>Vendors agree to use the platform only for lawful purposes and in a manner that does not infringe the rights of others or restrict or inhibit anyone else\'s use and enjoyment of pv.market. Prohibited activities include but are not limited to:</p><ul><li>Listing counterfeit, stolen, or prohibited products.</li><li>Engaging in fraudulent transactions or misrepresentation.</li><li>Manipulating product reviews or ratings.</li><li>Spamming customers or other vendors.</li><li>Violating intellectual property rights.</li></ul>',
                        ],
                        [
                            'title'   => '4. Selling',
                            'content' => '<p>Vendors are responsible for ensuring that all products listed on pv.market:</p><ul><li>Are legally allowed to be sold in the target market(s).</li><li>Meet all applicable safety and quality standards.</li><li>Are accurately described with correct specifications, images, and pricing.</li><li>Are available for dispatch within the promised timeframe.</li></ul><p>pv.market reserves the right to remove any listing that violates its policies without prior notice.</p>',
                        ],
                        [
                            'title'   => '5. Content and Description',
                            'content' => '<p>All product content including titles, descriptions, images, and attributes must:</p><ul><li>Be accurate, truthful, and not misleading.</li><li>Be original or properly licensed — vendors must not use copyrighted content without permission.</li><li>Comply with pv.market\'s content guidelines.</li><li>Be written in clear, professional language.</li></ul><p>pv.market reserves the right to edit or remove content that does not meet these standards.</p>',
                        ],
                        [
                            'title'   => '6. Price and Inventory',
                            'content' => '<p>Vendors are responsible for:</p><ul><li>Setting and maintaining accurate prices for all listed products.</li><li>Ensuring product availability matches listed inventory.</li><li>Promptly updating listings when products are out of stock.</li><li>Honoring all orders placed at the listed price at the time of purchase.</li></ul><p>Vendors must not engage in price manipulation or artificially inflate prices.</p>',
                        ],
                        [
                            'title'   => '7. Shipping and Returns',
                            'content' => '<p>Vendors must:</p><ul><li>Clearly state shipping timelines, methods, and costs in their listings.</li><li>Dispatch orders within the committed timeframe.</li><li>Provide valid tracking information for all shipments.</li><li>Have a clearly defined return and refund policy that complies with pv.market\'s standards.</li><li>Process returns and refunds promptly in accordance with the stated policy.</li></ul>',
                        ],
                        [
                            'title'   => '8. Customer Reviews and Ratings',
                            'content' => '<p>pv.market allows customers to leave reviews and ratings for products and vendors. Vendors must:</p><ul><li>Not attempt to manipulate, falsify, or incentivize reviews.</li><li>Respond professionally to customer feedback.</li><li>Not retaliate against customers for leaving negative reviews.</li></ul><p>pv.market reserves the right to moderate and remove reviews that violate its community guidelines.</p>',
                        ],
                        [
                            'title'   => '9. Vendor Obligations',
                            'content' => '<p>In addition to obligations stated elsewhere in this Agreement, Vendors shall:</p><ul><li>Maintain adequate stock levels to fulfill orders.</li><li>Provide excellent customer service and respond to inquiries within 24 hours.</li><li>Keep their account information current and accurate.</li><li>Comply with all pv.market policies and guidelines as updated from time to time.</li><li>Ensure all products meet applicable regulatory and safety requirements.</li></ul>',
                        ],
                        [
                            'title'   => '11. Payment Terms',
                            'content' => '<p>pv.market will process payments on behalf of Vendors subject to the following:</p><ul><li>Payments will be disbursed to Vendors according to the payment schedule outlined in the Vendor Dashboard.</li><li>pv.market charges a commission on each sale as specified in the fee schedule.</li><li>pv.market reserves the right to withhold payments in cases of suspected fraud, policy violations, or disputes.</li><li>Vendors are responsible for all applicable taxes on their earnings.</li></ul>',
                        ],
                        [
                            'title'   => '12. Platform Obligations',
                            'content' => '<p>pv.market agrees to:</p><ul><li>Provide Vendors with access to a functional marketplace platform.</li><li>Process customer payments securely.</li><li>Provide Vendor support for platform-related issues.</li><li>Maintain reasonable uptime and platform availability.</li><li>Notify Vendors of significant policy changes with reasonable advance notice.</li></ul>',
                        ],
                        [
                            'title'   => '13. Legal Compliances',
                            'content' => '<p>Vendors are solely responsible for ensuring their business operations, products, and use of the pv.market platform comply with all applicable laws and regulations, including but not limited to:</p><ul><li>Consumer protection laws.</li><li>Product safety and labelling regulations.</li><li>Import/export regulations.</li><li>Data protection and privacy laws.</li><li>Tax and financial regulations.</li></ul><p>pv.market shall not be liable for any Vendor\'s failure to comply with applicable laws.</p>',
                        ],
                        [
                            'title'   => '14. Intellectual Property',
                            'content' => '<p>Vendors retain ownership of their original content but grant pv.market a non-exclusive, royalty-free license to use, display, and distribute such content for the purpose of operating the marketplace. Vendors must not infringe upon the intellectual property rights of third parties when listing products or creating content.</p>',
                        ],
                        [
                            'title'   => '15. Confidentiality',
                            'content' => '<p>Both parties agree to keep confidential any proprietary or sensitive information shared in connection with this Agreement and not to disclose such information to third parties without prior written consent, except as required by law.</p>',
                        ],
                        [
                            'title'   => '16. Termination',
                            'content' => '<p>Either party may terminate this Agreement:</p><ul><li>By providing 30 days written notice to the other party.</li><li>Immediately if the other party materially breaches this Agreement.</li></ul><p>Upon termination, all active listings will be removed and any outstanding payments will be settled in accordance with the payment terms, after deducting any applicable fees or penalties.</p>',
                        ],
                        [
                            'title'   => '17. Limitation of Liability',
                            'content' => '<p>To the maximum extent permitted by law, pv.market shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including but not limited to loss of profits, data, or goodwill, arising out of or in connection with this Agreement or the use of the platform.</p>',
                        ],
                        [
                            'title'   => '18. Indemnification',
                            'content' => '<p>Vendors agree to indemnify, defend, and hold harmless pv.market and its affiliates, officers, directors, employees, and agents from and against any claims, liabilities, damages, losses, and expenses arising out of or in connection with the Vendor\'s use of the platform, products listed, or breach of this Agreement.</p>',
                        ],
                        [
                            'title'   => '19. Governing Law',
                            'content' => '<p>This Agreement shall be governed by and construed in accordance with the laws of the United Arab Emirates. Any disputes arising under this Agreement shall be subject to the exclusive jurisdiction of the courts located in the UAE.</p>',
                        ],
                        [
                            'title'   => '20. Amendments',
                            'content' => '<p>pv.market reserves the right to modify this Agreement at any time. Vendors will be notified of significant changes via email or platform notification. Continued use of the platform after such notification constitutes acceptance of the revised Agreement.</p>',
                        ],
                        [
                            'title'   => 'Contact Us',
                            'content' => '<p>If you have any questions about these Terms and Conditions, please contact us:</p><p>Email: <a href="mailto:info@pv.market">info@pv.market</a></p><p>Website: <a href="https://pv.market/contact-us/" target="_blank">https://pv.market/contact-us/</a></p><p>Call us on <a href="tel:+971523825549">+971 523825549</a></p>',
                        ],
                    ],
                ],
                'order'     => 2,
                'is_active' => true,
            ],

        ];

        foreach ($sections as $data) {
            PageSection::updateOrCreate(
                ['page' => $data['page'], 'section' => $data['section']],
                $data
            );
        }

        $this->command->info('✅ Terms page seeded!');
    }
}