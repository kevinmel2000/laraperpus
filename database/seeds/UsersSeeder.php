<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Membuat Role Admin
        $adminRole = new Role();
        $adminRole->name = "admin";
        $adminRole->display_name = "Admin";
        $adminRole->save();

        // Membuat Role Member
        $memberRole = new Role();
        $memberRole->name = "member";
        $memberRole->display_name = "Member";
        $memberRole->save();

        // Membuat Sample Admin
        $admin = new User();
        $admin->name = "Admin Larapus";
        $admin->email = "admin@gmail.com";
        $admin->password = bcrypt( 'rahasia' );
        //sample verified admin
        $admin->is_verified = 1;
        $admin->save();
        $admin->attachRole( $adminRole );

        // Membuat Sample Member
        $member = new User();
        $member->name = "Sample Member";
        $member->email = "member@gmail.com";
        $member->password = bcrypt( 'rahasia' );
        $member->is_verified = 1;
        $member->save();
        $member->attachRole( $memberRole );


    }
}
