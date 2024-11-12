<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'invoices',
            'invoices-list',
            'invoices-paid',
            'invoices-partial-paid',
            'invoices-unpaid',
            'invoices-archive',

            'reports',
            'reports-invoice',
            'reports-users',

            'users',
            'users-list',
            'users-roles',

            'settings',
            'product-list',
            'sections-list',

            'invoices-create',
            'invoices-delete',
            'invoices-excel',
            'invoices-change-payment-status',
            'invoices-edit',
            'invoices-archived',
            'invoices-print',
            'invoices-add-attachment',
            'invoices-delete-attachment',

            'users-add',
            'users-edit',
            'users-delete',

            'roles-list',
            'roles-add',
            'roles-edit',
            'roles-delete',

            'products-add',
            'products-edit',
            'products-delete',

            'sections-add',
            'sections-edit',
            'sections-delete',

            'notifications',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
