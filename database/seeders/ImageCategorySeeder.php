<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImageCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Slide',
            'Khoảnh khắc khách hàng',
            'Không gian',
            'Google Review',
            'Văn hóa doanh nghiệp',
            'Về chúng tôi',
            'Dịch vụ nổi bật',
            'Đội ngũ',
        ];

        foreach ($categories as $name) {

            DB::table('image_categories')->updateOrInsert(
                ['name' => $name],
                [
                    'slug' => Str::slug($name),
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}
