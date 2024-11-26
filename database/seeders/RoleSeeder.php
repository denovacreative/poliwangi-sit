<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $developer = Role::create([
            'name' => 'Developer',
            'guard_name' => 'web',
            'group' => 'root',
            'is_default' => true,
        ]);
        $default = Role::create([
            'name' => 'Default',
            'guard_name' => 'web',
            'group' => 'root',
            'is_default' => true,
        ]);
        $administrator = Role::create([
            'name' => 'Administrator',
            'guard_name' => 'web',
            'group' => 'admin',
            'is_default' => true,
        ]);
        $headOfProgram = Role::create([
            'name' => 'Ketua Jurusan',
            'guard_name' => 'web',
            'group' => 'jurusan',
            'is_default' => true,
        ]);
        $headOfTheStudyProgram = Role::create([
            'name' => 'Ketua Program Studi',
            'guard_name' => 'web',
            'group' => 'prodi',
            'is_default' => true,
        ]);
        $lecturer = Role::create([
            'name' => 'Dosen',
            'guard_name' => 'web',
            'group' => 'dosen',
            'is_default' => true,
        ]);
        $student = Role::create([
            'name' => 'Mahasiswa',
            'guard_name' => 'web',
            'group' => 'mahasiswa',
            'is_default' => true,
        ]);
        $studyProgramAdmin = Role::create([
            'name' => 'Admin Program Studi',
            'guard_name' => 'web',
            'group' => 'prodi',
            'is_default' => true,
        ]);
        $academic = Role::create([
            'name' => 'Akademik',
            'guard_name' => 'web',
            'group' => 'admin',
            'is_default' => true,
        ]);
        $feederAdmin = Role::create([
            'name' => 'Admin Feeder',
            'guard_name' => 'web',
            'group' => 'admin',
            'is_default' => true,
        ]);

        $director = Role::create([
            'name' => 'Direktur',
            'guard_name' => 'web',
            'group' => 'direktur',
            'is_default' => true,
        ]);
        $director1 = Role::create([
            'name' => 'Wakil Direktur',
            'guard_name' => 'web',
            'group' => 'direktur',
            'is_default' => true,
        ]);
        $developer->givePermissionTo([
            'read-dashboard',
            'create-college-students', 'read-college-students', 'update-college-students', 'delete-college-students',
            'create-employees', 'read-employees', 'update-employees', 'delete-employees',
            'create-employee-statuses', 'read-employee-statuses', 'update-employee-statuses', 'delete-employee-statuses',
            'create-employee-types', 'read-employee-types', 'update-employee-types', 'delete-employee-types',
            'create-employee-types', 'read-employee-types', 'update-employee-types', 'delete-employee-types',
            'create-courses', 'read-courses', 'update-courses', 'delete-courses',
            'create-course-types', 'read-course-types', 'update-course-types', 'delete-course-types',
            'create-course-groups', 'read-course-groups', 'update-course-groups', 'delete-course-groups',
            'create-scientific-fields', 'read-scientific-fields', 'update-scientific-fields', 'delete-scientific-fields',
            'create-users', 'read-users', 'update-users', 'delete-users', 'login-users',
            'create-roles', 'read-roles', 'update-roles', 'delete-roles', 'change-roles',
            'create-majors', 'read-majors', 'update-majors', 'delete-majors',
            'create-study-programs', 'read-study-programs', 'update-study-programs', 'delete-study-programs',
            // 'create-education-level-univ', 'read-education-level-univ', 'update-education-level-univ', 'delete-education-level-univ',
            'create-rooms', 'read-rooms', 'update-rooms', 'delete-rooms',
            'read-academic-activities', 'update-academic-activities', 'delete-academic-activities', 'create-academic-activities',
            'read-universities', 'update-universities', 'delete-universities', 'create-universities',
            'read-meeting-types', 'update-meeting-types', 'delete-meeting-types', 'create-meeting-types',
            'read-religions', 'update-religions', 'delete-religions', 'create-religions',
            'read-ethnics', 'update-ethnics', 'delete-ethnics', 'create-ethnics',
            'read-countries', 'update-countries', 'delete-countries', 'create-countries',
            'read-sub-districts', 'update-sub-districts', 'delete-sub-districts', 'create-sub-districts',
            'read-lecture-systems', 'update-lecture-systems', 'delete-lecture-systems', 'create-lecture-systems',
            'read-time-slots', 'update-time-slots', 'delete-time-slots', 'create-time-slots',
            'read-class-groups', 'update-class-groups', 'delete-class-groups', 'create-class-groups',
            'read-user-auth-logs',
            'read-class-recap',
            'read-transcripts', 'update-transcripts',
            'read-student-statuses', 'update-student-statuses', 'delete-student-statuses', 'create-student-statuses',
            'read-contact-persons', 'update-contact-persons', 'delete-contact-persons', 'create-contact-persons',
            'read-agencies', 'update-agencies', 'delete-agencies', 'create-agencies',
            'read-education-levels', 'update-education-levels', 'delete-education-levels', 'create-education-levels',
            'read-student-activity-categories', 'update-student-activity-categories', 'delete-student-activity-categories', 'create-student-activity-categories',
            'read-disabilities', 'update-disabilities', 'delete-disabilities', 'create-disabilities',
            'read-type-of-stays', 'update-type-of-stays', 'delete-type-of-stays', 'create-type-of-stays',
            'read-transportations', 'update-transportations', 'delete-transportations', 'create-transportations',
            'read-provinces', 'update-provinces', 'delete-provinces', 'create-provinces',
            'read-cities', 'update-cities', 'delete-cities', 'create-cities',
            'read-semesters', 'update-semesters', 'delete-semesters', 'create-semesters',
            'read-academic-calendars', 'update-academic-calendars', 'delete-academic-calendars', 'create-academic-calendars',
            'read-announcements', 'update-announcements', 'delete-announcements', 'create-announcements',
            'read-university-profiles', 'update-university-profiles',
            'read-academic-years', 'update-academic-years', 'delete-academic-years', 'create-academic-years',
            'read-professions', 'update-professions', 'delete-professions', 'create-professions',
            'read-academic-calendar-monitorings',
            'read-score-scales', 'update-score-scales', 'delete-score-scales', 'create-score-scales',
            'read-incomes', 'update-incomes', 'delete-incomes', 'create-incomes',
            'read-employee-active-statuses',
            'read-education-level-settings', 'update-education-level-settings', 'delete-education-level-settings', 'create-education-level-settings',
            'read-employee-active-statuses', 'update-employee-active-statuses', 'delete-employee-active-statuses', 'create-employee-active-statuses',
            'read-judicial-periods', 'update-judicial-periods', 'delete-judicial-periods', 'create-judicial-periods',
            'read-judicial-requirements', 'update-judicial-requirements', 'delete-judicial-requirements', 'create-judicial-requirements',
            'read-achievement-groups', 'update-achievement-groups', 'delete-achievement-groups', 'create-achievement-groups',
            'read-achievement-types', 'update-achievement-types', 'delete-achievement-types', 'create-achievement-types',
            'read-achievement', 'create-achievement', 'delete-achievement', 'update-achievement',
            // 'read-thesis-requirements', 'update-thesis-requirements', 'delete-thesis-requirements', 'create-thesis-requirements',
            'read-theses', 'update-theses', 'delete-theses', 'create-theses',
            'read-scholarship-types', 'update-scholarship-types', 'delete-scholarship-types', 'create-scholarship-types',
            'read-diploma-companions', 'update-diploma-companions', 'create-diploma-companions',
            'read-graduation-predicates', 'update-graduation-predicates', 'create-graduation-predicates', 'delete-graduation-predicates',
            'read-class-schedules', 'delete-class-schedules', 'update-class-schedules', 'create-class-schedules',
            'read-college-class-schedules', 'update-college-class-schedules', 'delete-college-class-schedules', 'create-college-class-schedules',
            'read-courses', 'update-courses', 'delete-courses', 'create-courses',
            'read-guardianships', 'update-guardianships', 'delete-guardianships', 'create-guardianships',
            'read-scheduling', 'update-scheduling', 'delete-scheduling', 'create-scheduling',
            'read-curriculums', 'update-curriculums', 'delete-curriculums', 'create-curriculums',
            'read-course-curiculums', 'update-course-curiculums', 'delete-course-curiculums', 'create-course-curiculums',
            'read-student-grades', 'update-student-grades',
            'read-presences', 'update-presences',
            'read-college-contracts', 'update-college-contracts',
            'read-class-attendances', 'update-class-attendances',
            'read-teaching-lecturers', 'update-teaching-lecturers', 'delete-teaching-lecturers', 'create-teaching-lecturers',
            'read-exam-schedules', 'update-exam-schedules', 'delete-exam-schedules', 'create-exam-schedules',
            'read-score-conversions', 'update-score-conversions', 'delete-score-conversions', 'create-score-conversions',
            'read-class-participants', 'delete-class-participants', 'create-class-participants', 'update-class-participants',
            'read-weekly-schedules', 'delete-weekly-schedules', 'create-weekly-schedules', 'update-weekly-schedules',
            'read-scholarships', 'delete-scholarships', 'create-scholarships', 'update-scholarships',
            'read-activities', 'delete-activities', 'create-activities', 'update-activities',
            'read-feeders',
            'read-report-final-level',
            'read-report-empolyee',
            'read-recap-schedule-employee',
            'read-report-administration',
            'read-report-students',
            'read-report-presentase-students',
            'read-report-grades',
            'read-her-registration',

            'read-status-semester','update-status-semester','create-status-semester',
            'read-graduations', 'update-graduations', 'create-graduations', 'delete-graduations',
            // 'read-judicial-participants', 'create-judicial-participants', 'update-judicial-participants', 'delete-judicial-participants',
            'read-judicials', 'update-judicials', 'delete-judicials', 'create-judicials',
            'read-study-program-settings', 'update-study-program-settings',
            'read-application-settings', 'update-application-settings'
        ]);

        $administrator->givePermissionTo([
            'read-dashboard',
        ]);

        $headOfProgram->givePermissionTo([
            'read-dashboard',
            'create-college-students', 'read-college-students', 'update-college-students', 'delete-college-students',
            'create-courses', 'read-courses', 'update-courses', 'delete-courses',
            'create-rooms', 'read-rooms', 'update-rooms', 'delete-rooms',
            'read-score-scales', 'update-score-scales', 'delete-score-scales', 'create-score-scales',
            'read-curriculums', 'update-curriculums', 'delete-curriculums', 'create-curriculums',
            'read-scheduling', 'update-scheduling', 'delete-scheduling', 'create-scheduling',
            'read-class-groups', 'update-class-groups', 'delete-class-groups', 'create-class-groups',
            'read-university-profiles', 'update-university-profiles',
            'read-academic-calendar-monitorings',
            'read-courses', 'update-courses', 'delete-courses', 'create-courses',
            'read-class-schedules', 'delete-class-schedules', 'update-class-schedules', 'create-class-schedules',
            'read-college-class-schedules', 'update-college-class-schedules', 'delete-college-class-schedules', 'create-college-class-schedules',
            'read-guardianships', 'update-guardianships', 'delete-guardianships', 'create-guardianships',
            'read-course-curiculums', 'update-course-curiculums', 'delete-course-curiculums', 'create-course-curiculums',
            'read-student-grades', 'update-student-grades',
            'read-presences', 'update-presences',
            'read-college-contracts', 'update-college-contracts',
            'read-class-attendances', 'update-class-attendances',
            'read-teaching-lecturers', 'update-teaching-lecturers', 'delete-teaching-lecturers', 'create-teaching-lecturers',
            'read-exam-schedules', 'update-exam-schedules', 'delete-exam-schedules', 'create-exam-schedules',
            'read-score-conversions', 'update-score-conversions', 'delete-score-conversions', 'create-score-conversions',
            'read-class-participants', 'delete-class-participants', 'create-class-participants', 'update-class-participants',
            'read-weekly-schedules', 'delete-weekly-schedules', 'create-weekly-schedules', 'update-weekly-schedules',
            'read-scholarships', 'delete-scholarships', 'create-scholarships', 'update-scholarships',
            'read-activities', 'delete-activities', 'create-activities', 'update-activities',
        ]);
        $headOfTheStudyProgram->givePermissionTo([
            'read-dashboard',
            'create-college-students', 'read-college-students', 'update-college-students', 'delete-college-students',
            'create-courses', 'read-courses', 'update-courses', 'delete-courses',
            'create-rooms', 'read-rooms', 'update-rooms', 'delete-rooms',
            'read-score-scales', 'update-score-scales', 'delete-score-scales', 'create-score-scales',
            'read-curriculums', 'update-curriculums', 'delete-curriculums', 'create-curriculums',
            'read-scheduling', 'update-scheduling', 'delete-scheduling', 'create-scheduling',
            'read-class-groups', 'update-class-groups', 'delete-class-groups', 'create-class-groups',
            'read-university-profiles', 'update-university-profiles',
            'read-academic-calendar-monitorings',
            'read-courses', 'update-courses', 'delete-courses', 'create-courses',
            'read-class-schedules', 'delete-class-schedules', 'update-class-schedules', 'create-class-schedules',
            'read-college-class-schedules', 'update-college-class-schedules', 'delete-college-class-schedules', 'create-college-class-schedules',
            'read-guardianships', 'update-guardianships', 'delete-guardianships', 'create-guardianships',
            'read-course-curiculums', 'update-course-curiculums', 'delete-course-curiculums', 'create-course-curiculums',
            'read-student-grades', 'update-student-grades',
            'read-presences', 'update-presences',
            'read-college-contracts', 'update-college-contracts',
            'read-class-attendances', 'update-class-attendances',
            'read-teaching-lecturers', 'update-teaching-lecturers', 'delete-teaching-lecturers', 'create-teaching-lecturers',
            'read-exam-schedules', 'update-exam-schedules', 'delete-exam-schedules', 'create-exam-schedules',
            'read-score-conversions', 'update-score-conversions', 'delete-score-conversions', 'create-score-conversions',
            'read-class-participants', 'delete-class-participants', 'create-class-participants', 'update-class-participants',
            'read-weekly-schedules', 'delete-weekly-schedules', 'create-weekly-schedules', 'update-weekly-schedules',
            'read-scholarships', 'delete-scholarships', 'create-scholarships', 'update-scholarships',
            'read-activities', 'delete-activities', 'create-activities', 'update-activities',
        ]);
        $lecturer->givePermissionTo([
            'read-lecturer-dashboard',
            'read-lecturer-schedule-semester',
            'read-lecturer-activity',
            'read-lecturer-profile',
            'read-lecturer-weekly-schedules',
            'read-lecturer-presences',
            'read-lecturer-calendars',
            'update-lecturer-presences',
            'read-lecturer-guardianships',
            'read-lecturer-scheduling',
            'update-lecturer-college-contracts',
            'read-lecturer-college-class-schedules', 'update-lecturer-college-class-schedules', 'delete-lecturer-college-class-schedules', 'create-lecturer-college-class-schedules',
            'read-lecturer-student-grades', 'update-lecturer-student-grades',
            'read-lecturer-announcements'
        ]);
        $student->givePermissionTo([
            'read-student-dashboard',
            'read-student-krs',
            'read-student-semester-status',
            'read-student-curriculums',
            'read-student-schedule-semester',
            'read-student-her-registration',
            'read-student-profile',
            'update-student-profile',
            'read-student-her-registration',
            'read-student-announcements',
            'read-student-scores',
            'read-student-schedule-weekly',
            'read-student-activities',
            'read-student-calendars',
            'read-student-presences',
            'read-student-achievements',
            'read-student-study-result-cards',
            'read-student-transcript',
        ]);
        $academic->givePermissionTo([
            'read-dashboard',
            'create-courses', 'read-courses', 'update-courses', 'delete-courses',
            'create-college-students', 'read-college-students', 'update-college-students', 'delete-college-students',
            'create-rooms', 'read-rooms', 'update-rooms', 'delete-rooms',
            'read-score-scales', 'update-score-scales', 'delete-score-scales', 'create-score-scales',
            'read-curriculums', 'update-curriculums', 'delete-curriculums', 'create-curriculums',
            'read-scheduling', 'update-scheduling', 'delete-scheduling', 'create-scheduling',
            'read-class-groups', 'update-class-groups', 'delete-class-groups', 'create-class-groups',
            'read-university-profiles', 'update-university-profiles',
            'read-academic-calendar-monitorings',
            'read-courses', 'update-courses', 'delete-courses', 'create-courses',
            'read-class-schedules', 'delete-class-schedules', 'update-class-schedules', 'create-class-schedules',
            'read-college-class-schedules', 'update-college-class-schedules', 'delete-college-class-schedules', 'create-college-class-schedules',
            'read-guardianships', 'update-guardianships', 'delete-guardianships', 'create-guardianships',
            'read-course-curiculums', 'update-course-curiculums', 'delete-course-curiculums', 'create-course-curiculums',
            'read-student-grades', 'update-student-grades',
            'read-presences', 'update-presences',
            'read-college-contracts', 'update-college-contracts',
            'read-class-attendances', 'update-class-attendances',
            'read-teaching-lecturers', 'update-teaching-lecturers', 'delete-teaching-lecturers', 'create-teaching-lecturers',
            'read-exam-schedules', 'update-exam-schedules', 'delete-exam-schedules', 'create-exam-schedules',
            'read-score-conversions', 'update-score-conversions', 'delete-score-conversions', 'create-score-conversions',
            'read-class-participants', 'delete-class-participants', 'create-class-participants', 'update-class-participants',
            'read-weekly-schedules', 'delete-weekly-schedules', 'create-weekly-schedules', 'update-weekly-schedules',
            'read-scholarships', 'delete-scholarships', 'create-scholarships', 'update-scholarships',
            'read-activities', 'delete-activities', 'create-activities', 'update-activities',
        ]);
        $studyProgramAdmin->givePermissionTo([
            'read-dashboard',
            'create-college-students', 'read-college-students', 'update-college-students', 'delete-college-students',
            'create-courses', 'read-courses', 'update-courses', 'delete-courses',
            'create-rooms', 'read-rooms', 'update-rooms', 'delete-rooms',
            'read-score-scales', 'update-score-scales', 'delete-score-scales', 'create-score-scales',
            'read-curriculums', 'update-curriculums', 'delete-curriculums', 'create-curriculums',
            'read-scheduling', 'update-scheduling', 'delete-scheduling', 'create-scheduling',
            'read-class-groups', 'update-class-groups', 'delete-class-groups', 'create-class-groups',
            'read-university-profiles', 'update-university-profiles',
            'read-academic-calendar-monitorings',
            'read-courses', 'update-courses', 'delete-courses', 'create-courses',
            'read-class-schedules', 'delete-class-schedules', 'update-class-schedules', 'create-class-schedules',
            'read-college-class-schedules', 'update-college-class-schedules', 'delete-college-class-schedules', 'create-college-class-schedules',
            'read-guardianships', 'update-guardianships', 'delete-guardianships', 'create-guardianships',
            'read-course-curiculums', 'update-course-curiculums', 'delete-course-curiculums', 'create-course-curiculums',
            'read-student-grades', 'update-student-grades',
            'read-presences', 'update-presences',
            'read-college-contracts', 'update-college-contracts',
            'read-class-attendances', 'update-class-attendances',
            'read-teaching-lecturers', 'update-teaching-lecturers', 'delete-teaching-lecturers', 'create-teaching-lecturers',
            'read-exam-schedules', 'update-exam-schedules', 'delete-exam-schedules', 'create-exam-schedules',
            'read-score-conversions', 'update-score-conversions', 'delete-score-conversions', 'create-score-conversions',
            'read-class-participants', 'delete-class-participants', 'create-class-participants', 'update-class-participants',
            'read-weekly-schedules', 'delete-weekly-schedules', 'create-weekly-schedules', 'update-weekly-schedules',
            'read-scholarships', 'delete-scholarships', 'create-scholarships', 'update-scholarships',
            'read-activities', 'delete-activities', 'create-activities', 'update-activities',
        ]);
        $director->givePermissionTo([
            'read-dashboard',
        ]);
        $director1->givePermissionTo([
            'read-dashboard',
        ]);
        $feederAdmin->givePermissionTo([
            'read-dashboard',
            'read-feeders',
        ]);
    }
}
