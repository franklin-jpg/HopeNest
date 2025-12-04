<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\ImpactStory;
use App\Models\Donation;
use App\Models\Volunteer;

class AboutUsController extends Controller
{
    public function aboutUs()
    {
        // Get statistics for About Us page
        $stats = [
            'total_campaigns' => Campaign::count(),
            'active_campaigns' => Campaign::where('status', 'active')->count(),
            'total_raised' => Donation::where('status', 'completed')->sum('amount'),
            'total_donors' => Donation::where('status', 'completed')->distinct('user_id')->count('user_id'),
            'impact_stories' => ImpactStory::published()->count(),
            'volunteers' => Volunteer::where('status', 'approved')->count(),
        ];

        // Get team members (you can create a Team model or hardcode)
        $teamMembers = $this->getTeamMembers();

        // Get recent achievements/milestones
        $milestones = $this->getMilestones();

        return view('AboutPages.about-us', compact('stats', 'teamMembers', 'milestones'));
    }

    public function faq()
    {
        // Get FAQs organized by category
        $faqs = $this->getFaqs();

        return view('aboutPages.faq', compact('faqs'));
    }

    private function getTeamMembers()
    {
        // You can replace this with database queries if you have a Team model
        return [
            [
                'name' => 'John Doe',
                'position' => 'Executive Director',
                'image' => 'team/john-doe.jpg',
                'bio' => 'Leading our mission with 15+ years of nonprofit experience.',
                'social' => [
                    'twitter' => 'https://twitter.com/johndoe',
                    'linkedin' => 'https://linkedin.com/in/johndoe',
                ]
            ],
            [
                'name' => 'Jane Smith',
                'position' => 'Operations Manager',
                'image' => 'team/jane-smith.jpg',
                'bio' => 'Ensuring smooth operations and community engagement.',
                'social' => [
                    'twitter' => 'https://twitter.com/janesmith',
                    'linkedin' => 'https://linkedin.com/in/janesmith',
                ]
            ],
            [
                'name' => 'Mike Johnson',
                'position' => 'Fundraising Director',
                'image' => 'team/mike-johnson.jpg',
                'bio' => 'Connecting donors with causes that matter.',
                'social' => [
                    'linkedin' => 'https://linkedin.com/in/mikejohnson',
                ]
            ],
            [
                'name' => 'Sarah Williams',
                'position' => 'Community Outreach',
                'image' => 'team/sarah-williams.jpg',
                'bio' => 'Building bridges between communities and resources.',
                'social' => [
                    'twitter' => 'https://twitter.com/sarahw',
                ]
            ],
        ];
    }

    private function getMilestones()
    {
        return [
            [
                'year' => '2020',
                'title' => 'Organization Founded',
                'description' => 'Started our journey to make a difference in communities.'
            ],
            [
                'year' => '2021',
                'title' => 'First 100 Campaigns',
                'description' => 'Reached milestone of 100 successful campaigns launched.'
            ],
            [
                'year' => '2022',
                'title' => '$1M Raised',
                'description' => 'Surpassed one million dollars in donations for various causes.'
            ],
            [
                'year' => '2023',
                'title' => 'Global Expansion',
                'description' => 'Extended our reach to support international communities.'
            ],
            [
                'year' => '2024',
                'title' => '10,000+ Lives Impacted',
                'description' => 'Directly helped over 10,000 individuals through our programs.'
            ],

             [
                'year' => '2025',
                'title' => '100,000+ Lives Impacted',
                'description' => 'Directly helped over 100,000 individuals through our programs.'
            ],
        ];
    }

    private function getFaqs()
    {
        return [
            'general' => [
                'title' => 'General Questions',
                'questions' => [
                    [
                        'question' => 'What is your organization about?',
                        'answer' => 'We are a nonprofit organization dedicated to creating positive change in communities through fundraising campaigns, volunteer programs, and direct support initiatives. Our mission is to connect generous donors with impactful causes.'
                    ],
                    [
                        'question' => 'How long have you been operating?',
                        'answer' => 'We started our journey in 2020 and have been growing steadily, expanding our reach and impact year after year. We\'ve successfully managed hundreds of campaigns and helped thousands of people.'
                    ],
                    [
                        'question' => 'Where does my donation go?',
                        'answer' => 'Every donation goes directly to the campaign you select. We maintain complete transparency, and you can track how your contribution is being used through our impact stories and campaign updates. A small administrative fee may apply to cover operational costs.'
                    ],
                    [
                        'question' => 'Are donations tax-deductible?',
                        'answer' => 'Yes! We are a registered 501(c)(3) nonprofit organization. All donations are tax-deductible to the extent allowed by law. You will receive a tax receipt via email after making a donation.'
                    ],
                ],
            ],
            'donations' => [
                'title' => 'Donations & Payments',
                'questions' => [
                    [
                        'question' => 'What payment methods do you accept?',
                        'answer' => 'We accept all major credit cards (Visa, Mastercard, American Express), debit cards, and online payment methods through our secure payment processor. All transactions are encrypted and secure.'
                    ],
                    [
                        'question' => 'Can I make recurring donations?',
                        'answer' => 'Absolutely! We offer monthly recurring donation options for most campaigns. This is a great way to provide sustained support to causes you care about. You can cancel or modify your recurring donation at any time.'
                    ],
                    [
                        'question' => 'Is my payment information secure?',
                        'answer' => 'Yes, your security is our top priority. We use industry-standard SSL encryption and our payment processor is PCI-DSS compliant. We never store your complete credit card information on our servers.'
                    ],
                    [
                        'question' => 'Can I donate anonymously?',
                        'answer' => 'Yes, you have the option to make your donation anonymous during the checkout process. Your name will not appear on public donor lists, though we will still send you a tax receipt for your records.'
                    ],
                    [
                        'question' => 'What if I need a refund?',
                        'answer' => 'If you made a donation in error, please contact us within 48 hours. We will process refund requests on a case-by-case basis. Please note that once funds have been transferred to campaign beneficiaries, refunds may not be possible.'
                    ],
                ],
            ],
            'campaigns' => [
                'title' => 'Campaigns',
                'questions' => [
                    [
                        'question' => 'How do you verify campaigns?',
                        'answer' => 'Every campaign goes through a rigorous verification process. We review documentation, verify beneficiary information, and ensure all campaigns align with our mission and legal requirements before they go live.'
                    ],
                    [
                        'question' => 'Can I start my own campaign?',
                        'answer' => 'Yes! If you represent a registered nonprofit or have a verified cause, you can apply to start a campaign. Contact our team through the "Start a Campaign" page and we\'ll guide you through the application process.'
                    ],
                    [
                        'question' => 'How long do campaigns run?',
                        'answer' => 'Campaign duration varies based on the specific cause and fundraising goal. Most campaigns run between 30-90 days, but some ongoing initiatives may have longer timelines. Each campaign page shows its end date.'
                    ],
                    [
                        'question' => 'What happens if a campaign doesn\'t reach its goal?',
                        'answer' => 'Most of our campaigns operate on a flexible funding model, meaning the campaign receives all donations even if the goal isn\'t reached. This ensures that every contribution makes an impact, regardless of the final total.'
                    ],
                ],
            ],
            'volunteering' => [
                'title' => 'Volunteering',
                'questions' => [
                    [
                        'question' => 'How can I volunteer?',
                        'answer' => 'Visit our Volunteer page and fill out the application form. Tell us about your skills, interests, and availability. Our team will review your application and match you with opportunities that align with your profile.'
                    ],
                    [
                        'question' => 'Do I need special skills to volunteer?',
                        'answer' => 'Not at all! We have opportunities for people with all skill levels and backgrounds. Whether you have professional expertise to share or just want to help out, there\'s a place for you in our volunteer community.'
                    ],
                    [
                        'question' => 'How much time do I need to commit?',
                        'answer' => 'Volunteer commitments vary by opportunity. Some activities require just a few hours, while others may involve ongoing weekly commitments. You can choose opportunities that fit your schedule.'
                    ],
                    [
                        'question' => 'Can I volunteer remotely?',
                        'answer' => 'Yes! We offer many remote volunteer opportunities including digital marketing, content creation, virtual tutoring, and administrative support. Check our volunteer page for current remote positions.'
                    ],
                ],
            ],
            'account' => [
                'title' => 'Account & Profile',
                'questions' => [
                    [
                        'question' => 'Do I need an account to donate?',
                        'answer' => 'No, you can make one-time donations as a guest. However, creating an account allows you to track your donation history, receive updates on campaigns you\'ve supported, and manage recurring donations easily.'
                    ],
                    [
                        'question' => 'How do I update my account information?',
                        'answer' => 'Log in to your account and navigate to your profile settings. From there, you can update your personal information, email preferences, and payment methods.'
                    ],
                    [
                        'question' => 'I forgot my password. What should I do?',
                        'answer' => 'Click on the "Forgot Password" link on the login page. Enter your email address and we\'ll send you instructions to reset your password. If you don\'t receive the email, check your spam folder or contact support.'
                    ],
                    [
                        'question' => 'How do I delete my account?',
                        'answer' => 'If you wish to delete your account, please contact our support team. We\'ll process your request while ensuring your donation records are preserved for tax and legal purposes as required by law.'
                    ],
                ],
            ],
        ];
    }
}