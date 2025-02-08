<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Création des rôles
        $admin = Role::create(['name' => 'admin']);
        $manager = Role::create(['name' => 'manager']);
        $client = Role::create(['name' => 'client']);

        // Permissions générales
        Permission::create(['name' => 'manage products']);
        Permission::create(['name' => 'manage orders']);
        Permission::create(['name' => 'manage users']);

        // Assignation des permissions aux rôles
        $admin->givePermissionTo(['manage products', 'manage orders', 'manage users']);
        $manager->givePermissionTo(['manage products', 'manage orders']);
        $client->givePermissionTo([]); // Le client n’a pas de permissions spéciales
    }
}

