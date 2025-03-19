<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create roles if they don't exist
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $teacherRole = Role::firstOrCreate(['name' => 'Teacher']);
        $studentRole = Role::firstOrCreate(['name' => 'Student']);

        // Create permissions if they don't exist
        $manageStudentsPermission = Permission::firstOrCreate(['name' => 'manage_students']);
        $viewReportsPermission = Permission::firstOrCreate(['name' => 'view_reports']);

        // Assign permissions to roles
        $adminRole->givePermissionTo(['manage_students', 'view_reports']);
        $teacherRole->givePermissionTo(['view_reports', 'manage_students']);  // Now Teacher can manage students
        $studentRole->givePermissionTo('view_reports');

        // Create users and assign roles
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@edusphere.com',
            'password' => bcrypt('password123'),
        ]);
        $admin->assignRole($adminRole);

        $teacher = User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@edusphere.com',
            'password' => bcrypt('password123'),
        ]);
        $teacher->assignRole($teacherRole);

        $student = User::create([
            'name' => 'Student User',
            'email' => 'student@edusphere.com',
            'password' => bcrypt('password123'),
        ]);
        $student->assignRole($studentRole);

        // Create the Principle and assign the Teacher role
        $newTeacher = User::create([
            'name' => 'Principal',
            'email' => 'principle@edusphere.com',
            'password' => bcrypt('password123'),
        ]);
        $newTeacher->assignRole($teacherRole);  // Assign Teacher role to Principal
        $newTeacher->givePermissionTo('manage_students');  // Explicitly give 'manage_students' permission to Principal
    }
}

