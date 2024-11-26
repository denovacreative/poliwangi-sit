<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $this->call([
            ReligionSeeder::class,
            RegionSeeder::class,
            DaySeeder::class,
            CountrySeeder::class,
            CourseTypeSeeder::class,
            TimeSlotSeeder::class,
            ScientificFieldSeeder::class,
            AcademicYearSeeder::class,
            ProfessionSeeder::class,
            IncomeSeeder::class,
            CourseGroupSeeder::class,
            CourseSeeder::class,
            EmployeeStatusSeeder::class,
            StudentStatusSeeder::class,
            EmployeeTypeSeeder::class,
            EthnicSeeder::class,
            DisabilitySeeder::class,
            EducationLevelSeeder::class,
            EducationLevelSettingSeeder::class,
            MeetingTypeSeeder::class,
            LectureSystemSeeder::class,
            RegistrationPathSeeder::class,
            RegistrationTypeSeeder::class,
            ScholarshipTypeSeeder::class,
            TransportationSeeder::class,
            TypeOfStaySeeder::class,
            UniversitySeeder::class,
            EmployeeSeeder::class,
            MajorSeeder::class,
            StudyProgramSeeder::class,
            ClassGroupSeeder::class,
            UniversityProfileSeeder::class,
            RoomSeeder::class,
            UniversitySeeder::class,
            AgencySeeder::class,
            AcademicPeriodSeeder::class,
            CurriculumSeeder::class,
            StudentActivityCategorySeeder::class,
            CollegeClassSeeder::class,
            ClassScheduleSeeder::class,
            EvaluationTypeSeeder::class,
            ScorePrecentageSeeder::class,
            TeachingLecturerSeeder::class,
            // -------------------------------
            AcademicActivitySeeder::class,
            AchievementFieldSeeder::class,
            AchievementGroupSeeder::class,
            AchievementLevelSeeder::class,
            AchievementTypeSeeder::class,
            ActivityCategorySeeder::class,
            OriginSchoolSeeder::class,
            StudentSeeder::class,
            CourseCurriculumSeeder::class,
            SettingSeeder::class,
            ClassParticipantSeeder::class,
            ScoreSeeder::class,
            PresenceSeeder::class,
            // -------------------------
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
        ]);
    }
}
