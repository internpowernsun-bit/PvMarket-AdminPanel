<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageSection;

class PrivacyPageSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [

            [
                'page'      => 'privacy',
                'section'   => 'privacy_header',
                'type'      => 'text_block',
                'title'     => 'Privacy and Cookies Policy',
                'subtitle'  => null,
                'extra'     => ['content' => ''],
                'order'     => 1,
                'is_active' => true,
            ],

            [
                'page'      => 'privacy',
                'section'   => 'privacy_content',
                'type'      => 'richtext',
                'title'     => 'Privacy Policy Content',
                'subtitle'  => null,
                'extra'     => [
                    'content' => '
<h2>PRIVACY POLICY</h2>
<p>This Privacy Policy describes how your personal information is collected, used, and shared when you visit or make a purchase from <a href="https://pv.market/" target="_blank">https://pv.market/</a>.</p>

<h2>PERSONAL INFORMATION WE COLLECT</h2>
<p>When you visit the Site, we automatically collect certain information about your device, including information about your web browser, IP address, time zone, and some of the cookies that are installed on your device. We may also collect information about you through social media sites you use to access our Site depending the permissions you have given for access to your information. Additionally, as you browse the Site, we collect information about the individual web pages or products that you view, what websites or search terms referred you to the Site, and information about how you interact with the Site. We refer to this automatically-collected information as "Device Information."</p>

<h2>COOKIES &amp; OTHER TRACKING TECHNOLOGIES THAT WE USE</h2>
<p>We and our service providers use cookies, beacons &amp; embedded scripts in connection with the Sites. "Cookies" are data files that are placed on your device or computer and often include an anonymous unique identifier. For more information about cookies, and how to disable cookies, visit <a href="http://www.allaboutcookies.org" target="_blank">http://www.allaboutcookies.org</a> "Log files" track actions occurring on the Site, and collect data including your IP address, browser type, Internet service provider, referring/exit pages, and date/time stamps. "Web beacons," "tags," and "pixels" are electronic files used to record information about how you browse the Site. An "Embedded script" is programming code that is designed to collect information about your interactions with the Site, such as the links you click on. The code is temporarily downloaded onto your computer or device from our web server or a third-party service provider, is active only while you are connected to the Site, and is deactivated or deleted thereafter. The above technologies are used in analyzing trends, administering the Sites, services and products, tracking users\' movements around the Sites and to gather demographic information about our user base as a whole. We may receive reports based on the use of these technologies by these companies on an individual as well as aggregated basis. We use cookies to remember users\' settings, market products and services to users, and for authentication purposes.</p>

<h3>Third-Party Advertising Services</h3>
<p>We partner with third parties that may use technologies such as cookies (and local stored objects as described above) to gather information about your activities on the Sites and elsewhere on the Internet in order to provide you with relevant advertising based upon your browsing activities and interests. This type of advertising is sometimes called interest-based advertising. No personally-identifiable information is collected or used in this process.</p>

<h2>HOW DO WE USE YOUR PERSONAL INFORMATION?</h2>
<p>We use the Order Information that we collect generally to fulfill any orders placed through the Site (including processing your payment information, arranging for shipping, and providing you with invoices and/or order confirmations). Additionally, we use personal information to: We may also use your Personal Information to improve and personalize your experience with us, and, with your consent where necessary, send you information about us and keep you informed of our other products and services that may be of interest to you. Communicate with you &amp; send marketing mailers to which you have consented ; Screen our orders for potential risk or fraud; When in line with the preferences you have shared with us, provide you with information or advertising relating to our products or services. We use passively collected information to monitor and maintain the performance of our Sites, analyze trends, usage and activities in connection with our services, validate users and ensure their technological compatibility with users, and optimise our marketing efforts. We use the Device Information that we collect to help us screen for potential risk and fraud (in particular, your IP address), and more generally to improve and optimize our Site (for example, by generating analytics about how our customers browse and interact with the Site, and to assess the success of our marketing and advertising campaigns).</p>

<h2>SHARING YOUR PERSONAL INFORMATION</h2>
<p>We may share your Personal Information with third parties as described in this Privacy Policy or otherwise with your permission. We reserve the right to transfer data, including aggregate and de-identified data derived from Personal Information, in connection with a merger, acquisition, or sale of assets, or in the unlikely event of bankruptcy.</p>

<h2>OUR OPT-OUT POLICY</h2>
<p>As described above, we use your Personal Information to provide you with targeted advertisements or marketing communications we believe may be of interest to you. For more information about how targeted advertising works, you can visit the Network Advertising Initiative\'s ("NAI") educational page at <a href="http://www.networkadvertising.org/understanding-online-advertising/how-does-it-work" target="_blank">http://www.networkadvertising.org/understanding-online-advertising/how-does-it-work</a>.</p>

<p><strong>You can opt out of targeted advertising by:</strong></p>
<ul>
    <li>FACEBOOK - <a href="https://www.facebook.com/settings/?tab=ads" target="_blank">https://www.facebook.com/settings/?tab=ads</a></li>
    <li>GOOGLE - <a href="https://www.google.com/settings/ads/anonymous" target="_blank">https://www.google.com/settings/ads/anonymous</a></li>
    <li>BING - <a href="https://about.ads.microsoft.com/en-us/resources/policies/personalized-ads" target="_blank">https://about.ads.microsoft.com/en-us/resources/policies/personalized-ads</a></li>
</ul>

<p>Additionally, If you wish to not have this information collected and used for interest-based advertising, you may opt-out by clicking here for partners that participate in the TRUSTe opt-out tool (or if located in the European Union click here). If you wish to opt-out from this type of advertising for companies that participate in the Network Advertising Initiative, please click here. If you wish to opt-out from this type of advertising for companies that participate in the Digital Advertising Alliance ("DAA"), you can do so here. Please note that this does not opt you out of being served ads.</p>

<h3>Email Marketing</h3>
<p>We may use your Personal Information to contact you with newsletters, marketing or promotional materials and other information that may be of interest to you. You may opt out of receiving any, or all, of these communications from us by following the unsubscribe link or instructions provided in any email we send.</p>

<p>Visitors under 18 years of age are not permitted to use and/or submit their Personal Information at any Site. We do not knowingly solicit or collect information from visitors under 18 years of age. If you are under 18 years of age, please do not submit any information to us. In the event that we learn that a person under the age of 18 has provided us with personal information, we will delete such personal information. Visitors older than the age of 18, but younger than their country\'s legal age of majority are permitted to use and/or submit their Personal Information only with parental supervision.</p>

<h3>CHANGES</h3>
<p>We may update this privacy policy from time to time in order to reflect, for example, changes to our practices or for other operational, legal or regulatory reasons.</p>

<h2>CONTACT US</h2>
<p>For more information about our privacy practices, if you have questions, or if you would like to make a complaint, please contact us by e-mail or by mail using the details provided below:</p>
<p>Should you have other questions or concerns about these privacy policies, or wish to exercise any of the above data subject rights, please contact us using our Contact Us section: <a href="https://pv.market/contact-us/" target="_blank">https://pv.market/contact-us/</a></p>
<p>Write a mail on <a href="mailto:info@pv.market">info@pv.market</a> with Order number.</p>
<p>Call us on <a href="tel:+971523825549">+971 523825549</a> with Order number.</p>
                    ',
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

        $this->command->info('✅ Privacy page seeded!');
    }
}