<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Membership;

class MembershipSeeder extends Seeder
{
     public function run(): void
    {
        $memberships = [
            [
                'code' => 'bronze',
                'name' => 'Đồng',
                'min_total_spent' => 0,
                'priority' => 1,
                'benefits' => null,
                'is_active' => true,
            ],
            [
                'code' => 'silver',
                'name' => 'Bạc',
                'min_total_spent' => 5000000,
                'priority' => 2,
                'benefits' => null,
                'is_active' => true,
            ],
            [
                'code' => 'gold',
                'name' => 'Vàng',
                'min_total_spent' => 20000000,
                'priority' => 3,
                'benefits' => null,
                'is_active' => true,
            ],
        ];

        foreach ($memberships as $membership) {
            Membership::updateOrCreate(
                ['code' => $membership['code']], 
                $membership                    
            );
        }
    }
}
