<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $databaseType = DB::getDriverName();

        switch ($databaseType) {
            case 'mysql':
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                break;
            case 'sqlite':
                DB::statement('PRAGMA foreign_keys = OFF;');
                break;
            case 'pgsql':
                DB::statement('SET CONSTRAINTS ALL DEFERRED;');
                break;
        }

        \App\Models\User::truncate();
        \App\Models\Company::truncate();
        \App\Models\JobRole::truncate();
        \App\Models\Skill::truncate();
        \App\Models\JobOffer::truncate();
        \App\Models\Skill::truncate();
        \App\Models\JobRole::truncate();
        \App\Models\Industry::truncate();
        \App\Models\Category::truncate();
        \App\Models\SubCategory::truncate();
        \App\Models\Milestone::truncate();
        \App\Models\Portfolio::truncate();
        \App\Models\Product::truncate();
        \App\Models\Project::truncate();
        \App\Models\Skillable::truncate();
        \App\Models\Verification::truncate();
        \App\Models\View::truncate();
        \App\Models\Client::truncate();
        \App\Models\ClientOffer::truncate();
        \App\Models\Freelancer::truncate();
        \App\Models\Like::truncate();
        \App\Models\File::truncate();
        \App\Models\Image::truncate();
        \App\Models\ContactMessage::truncate();
        \App\Models\Conversation::truncate();
        \App\Models\FreelancerOffer::truncate();
        \App\Models\Invitation::truncate();
        \App\Models\JobOfferProposal::truncate();
        \App\Models\Message::truncate();

        switch ($databaseType) {
            case 'mysql':
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                break;
            case 'sqlite':
                DB::statement('PRAGMA foreign_keys = ON;');
                break;
            case 'pgsql':
                DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');
                break;
        }

        

        $this->call([
            NoRoleUserSeeder::class,
            IndustrySeeder::class,
            CategorySeeder::class,
            Job_RoleSeeder::class,
            SkillSeeder::class,
            JobOffersSeeder::class,
            SuperAdminSeeder::class,
            AdminSeeder::class,
            FreelancerSeeder::class,
            CompanySeeder::class,
            ClientSeeder::class,
            ClientOfferSeeder::class,
            ClientOfferProposalSeeder::class,
            ContactMessageSeeder::class,
            ConversationSeeder::class,
            ConversationUserBanSeeder::class,
            FreelancerOfferSeeder::class,
            FreelancerOfferProposalSeeder::class,
            InvitationSeeder::class,
            JobOfferProposalSeeder::class,
            ImageSeeder::class,
            ProjectSeeder::class,
            MilestoneSeeder::class,
            PortfolioSeeder::class,
            ProductSeeder::class,
            SkillableSeeder::class,
            ViewSeeder::class,
            LikeSeeder::class,
            FileSeeder::class,
            MessageSeeder::class,
            VerificationSeeder::class,
        ]);
    }
}
