<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'audit_log_show',
            ],
            [
                'id'    => 18,
                'title' => 'audit_log_access',
            ],
            [
                'id'    => 19,
                'title' => 'epaper_create',
            ],
            [
                'id'    => 20,
                'title' => 'epaper_edit',
            ],
            [
                'id'    => 21,
                'title' => 'epaper_show',
            ],
            [
                'id'    => 22,
                'title' => 'epaper_delete',
            ],
            [
                'id'    => 23,
                'title' => 'epaper_access',
            ],
            [
                'id'    => 24,
                'title' => 'profile_password_edit',
            ],
            [
                'id'    => 25,
                'title' => 'product_access',
            ],
            [
                'id'    => 26,
                'title' => 'product_create',
            ],
            [
                'id'    => 27,
                'title' => 'product_edit',
            ],
            [
                'id'    => 28,
                'title' => 'product_show',
            ],
            [
                'id'    => 29,
                'title' => 'product_delete',
            ],
            [
                'id'    => 30,
                'title' => 'category_access',
            ],
            [
                'id'    => 31,
                'title' => 'category_create',
            ],
            [
                'id'    => 32,
                'title' => 'category_edit',
            ],
            [
                'id'    => 33,
                'title' => 'category_show',
            ],
            [
                'id'    => 34,
                'title' => 'category_delete',
            ],
            [
                'id'    => 35,
                'title' => 'subcategory_access',
            ],
            [
                'id'    => 36,
                'title' => 'subcategory_create',
            ],
            [
                'id'    => 37,
                'title' => 'subcategory_edit',
            ],
            [
                'id'    => 38,
                'title' => 'subcategory_show',
            ],
            [
                'id'    => 39,
                'title' => 'subcategory_delete',
            ],
            [
                'id'    => 40,
                'title' => 'order_access',
            ],
            [
                'id'    => 41,
                'title' => 'order_create',
            ],
            [
                'id'    => 42,
                'title' => 'order_edit',
            ],
            [
                'id'    => 43,
                'title' => 'order_show',
            ],
            [
                'id'    => 44,
                'title' => 'order_delete',
            ],
            [
                'id'    => 45,
                'title' => 'brand_access',
            ],
            [
                'id'    => 46,
                'title' => 'brand_create',
            ],
            [
                'id'    => 47,
                'title' => 'brand_edit',
            ],
            [
                'id'    => 48,
                'title' => 'brand_show',
            ],
            [
                'id'    => 49,
                'title' => 'brand_delete',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['id' => $permission['id']],
                ['title' => $permission['title']]
            );
        }
    }
}
