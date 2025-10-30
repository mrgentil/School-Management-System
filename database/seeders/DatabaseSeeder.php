<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BloodGroupsTableSeeder::class);
        $this->call(GradesTableSeeder::class);
        $this->call(DormsTableSeeder::class);
        $this->call(ClassTypesTableSeeder::class);
        $this->call(UserTypesTableSeeder::class);
        $this->call(MyClassesTableSeeder::class);
        $this->call(NationalitiesTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(LgasTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(SubjectsTableSeeder::class);
        $this->call(SectionsTableSeeder::class);
        $this->call(StudentRecordsTableSeeder::class);
        $this->call(SkillsTableSeeder::class);
        
        // Nouveaux seeders pour les étudiants, devoirs et paiements
        $this->call(PaymentTableSeeder::class); // Doit être avant PaymentSeeder
        $this->call(StudentSeeder::class);
        $this->call(AssignmentSeeder::class);
        $this->call(AssignmentSubmissionSeeder::class);
        $this->call(PaymentSeeder::class);
        
        // Nouveaux seeders pour les données de test
        $this->call(AttendanceSeeder::class);
        $this->call(LibrarySeeder::class);
        $this->call(SchoolEventSeeder::class);
    }
}
