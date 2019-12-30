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
              'user' => 'Javra IM',
              'email' => 'satya.maharjan@javra.com',
          ],
          'JWNM' => [
              'user' => 'Javra Web',
              'email' => 'ravindra.fauzdar@javra.com',
          ],

          'Operation' => [
              'user' => 'Javra Operation',
              'email' => 'manoj.giri@javra.com',
          ],

            'Java' => [
                'user' => 'Javra Java',
                'email' => 'jayraj.bhatta@javra.com',
            ],

            'QA' => [
                'user' => 'Javra QA',
                'email' => 'devnarayan.pandey@javra.com',
            ],

            '.NET' => [
                'user' => 'Javra .NET',
                'email' => 'dibyamani.suvedi@javra.com',
            ],

            'R&D' => [
                'user' => 'Javra R&D',
                'email' => 'thaman.singh.thapa@javra.com',
            ],

            'Managers' => [
                'user' => 'Managers',
                'email' => 'devnarayan.pandey@javra.com',
            ],
        ];

        foreach ($departmentsAndHeads as $departmentName => $departmentInfo) {
            $department = \App\Department::create([
               'name' => $departmentName,
               'head' => $departmentInfo['email'],
            ]);
        }

        User::create([
           'email' => 'infra@javra.com',
           'department_id' => '1',
           'password' => bcrypt('javra123$'),
           'name' => 'Infra Javra',
        ]);

        User::create([
            'email' => 'puran.shrestha@javra.com',
            'department_id' => '1',
            'password' => bcrypt('javra123$'),
            'name' => 'Puran Shrestha',
        ]);

        User::create([
            'email' => 'satya.maharjan@javra.com',
            'department_id' => '1',
            'password' => bcrypt('javra123$'),
            'name' => 'Satya Maharjan',
        ]);

        User::create([
            'email' => 'manoj.shrestha@javra.com',
            'department_id' => '1',
            'password' => bcrypt('javra123$'),
            'name' => 'Manoj Shrestha',
        ]);

        User::create([
            'email' => 'pratap.kurumphang@javra.com',
            'department_id' => '1',
            'password' => bcrypt('javra123$'),
            'name' => 'Pratap Kurumphang',
        ]);
    }
}
