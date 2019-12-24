<?php

use App\User;
use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departmentsAndHeads = [
          'IM' => [
              'user' => 'Javra Software',
              'email' => 'infra@javra.com',
          ],
          'JWNM' => [
              'user' => 'Javra Web',
              'email' => 'ravindra.fauzdar@javra.com',
          ],
        ];

        foreach ($departmentsAndHeads as $departmentName => $departmentInfo) {
            $department = \App\Department::create([
               'name' => $departmentName,
               'head' => $departmentInfo['email'],
            ]);

            User::create([
               'name' => $departmentInfo['user'],
               'email' => $departmentInfo['email'],
               'department_id' => $department->id,
               'email_verified_at' => now(),
               'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            ]);
        }
    }
}
