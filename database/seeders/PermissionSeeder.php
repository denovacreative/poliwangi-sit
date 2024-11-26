<?php

namespace Database\Seeders;

use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use PHPUnit\TextUI\XmlConfiguration\Group;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $webPermission = collect([
            # Dashboard related permission
            ['name' => 'read-dashboard', 'label' => 'Baca Dashboard', 'group' => null],

            # Users related permission
            ['name' => 'read-users', 'label' => 'Baca User', 'group' => null],
            ['name' => 'create-users', 'label' => 'Buat User', 'group' => null],
            ['name' => 'update-users', 'label' => 'Edit User', 'group' => null],
            ['name' => 'delete-users', 'label' => 'Hapus User', 'group' => null],
            ['name' => 'login-users', 'label' => 'Login As', 'group' => null],

            # Major related permission
            ['name' => 'read-majors', 'label' => 'Baca Jurusan', 'group' => null],
            ['name' => 'create-majors', 'label' => 'Buat Jurusan', 'group' => null],
            ['name' => 'update-majors', 'label' => 'Edit Jurusan', 'group' => null],
            ['name' => 'delete-majors', 'label' => 'Hapus Jurusan', 'group' => null],

            # Study Program related permission
            ['name' => 'read-study-programs', 'label' => 'Baca Program Studi', 'group' => null],
            ['name' => 'create-study-programs', 'label' => 'Buat Program Studi', 'group' => null],
            ['name' => 'update-study-programs', 'label' => 'Edit Program Studi', 'group' => null],
            ['name' => 'delete-study-programs', 'label' => 'Hapus Program Studi', 'group' => null],
            # Room related permission
            ['name' => 'read-rooms', 'label' => 'Baca Ruangan', 'group' => null],
            ['name' => 'create-rooms', 'label' => 'Buat Ruangan', 'group' => null],
            ['name' => 'update-rooms', 'label' => 'Edit Ruangan', 'group' => null],
            ['name' => 'delete-rooms', 'label' => 'Hapus Ruangan', 'group' => null],

            # Education Level Univ related permission
            // ['name' => 'read-education-level-univ', 'label' => 'Baca Jenjang Pendidikan Univ', 'group' => null],
            // ['name' => 'create-education-level-univ', 'label' => 'Buat Jenjang Pendidikan Univ', 'group' => null],
            // ['name' => 'update-education-level-univ', 'label' => 'Edit Jenjang Pendidikan Univ', 'group' => null],
            // ['name' => 'delete-education-level-univ', 'label' => 'Hapus Jenjang Pendidikan Univ', 'group' => null],

            # College Students related permission
            ['name' => 'read-college-students', 'label' => 'Baca Mahasiswa', 'group' => null],
            ['name' => 'create-college-students', 'label' => 'Buat Mahasiswa', 'group' => null],
            ['name' => 'update-college-students', 'label' => 'Edit Mahasiswa', 'group' => null],
            ['name' => 'delete-college-students', 'label' => 'Hapus Mahasiswa', 'group' => null],

            # Courses related permission
            ['name' => 'read-courses', 'label' => 'Baca Mata Kuliah', 'group' => null],
            ['name' => 'create-courses', 'label' => 'Buat Mata Kuliah', 'group' => null],
            ['name' => 'update-courses', 'label' => 'Edit Mata Kuliah', 'group' => null],
            ['name' => 'delete-courses', 'label' => 'Hapus Mata Kuliah', 'group' => null],

            # Courses Type related permission
            ['name' => 'read-course-types', 'label' => 'Baca Jenis Mata Kuliah', 'group' => null],
            ['name' => 'create-course-types', 'label' => 'Buat Jenis Mata Kuliah', 'group' => null],
            ['name' => 'update-course-types', 'label' => 'Edit Jenis Mata Kuliah', 'group' => null],
            ['name' => 'delete-course-types', 'label' => 'Hapus Jenis Mata Kuliah', 'group' => null],

            # Course Group related permission
            ['name' => 'read-course-groups', 'label' => 'Baca Kelompok Mata Kuliah', 'group' => null],
            ['name' => 'create-course-groups', 'label' => 'Buat Kelompok Mata Kuliah', 'group' => null],
            ['name' => 'update-course-groups', 'label' => 'Edit Kelompok Mata Kuliah', 'group' => null],
            ['name' => 'delete-course-groups', 'label' => 'Hapus Kelompok Mata Kuliah', 'group' => null],

            # Scientific Field related permission
            ['name' => 'read-scientific-fields', 'label' => 'Baca Bidang Ilmu', 'group' => null],
            ['name' => 'create-scientific-fields', 'label' => 'Buat Bidang Ilmu', 'group' => null],
            ['name' => 'update-scientific-fields', 'label' => 'Edit Bidang Ilmu', 'group' => null],
            ['name' => 'delete-scientific-fields', 'label' => 'Hapus Bidang Ilmu', 'group' => null],

            # Employee related permission
            ['name' => 'read-employees', 'label' => 'Baca Pegawai', 'group' => null],
            ['name' => 'create-employees', 'label' => 'Buat Pegawai', 'group' => null],
            ['name' => 'update-employees', 'label' => 'Edit Pegawai', 'group' => null],
            ['name' => 'delete-employees', 'label' => 'Hapus Pegawai', 'group' => null],

            # Employee Status related permission
            ['name' => 'read-employee-statuses', 'label' => 'Baca Status Pegawai', 'group' => null],
            ['name' => 'create-employee-statuses', 'label' => 'Buat Status Pegawai', 'group' => null],
            ['name' => 'update-employee-statuses', 'label' => 'Edit Status Pegawai', 'group' => null],
            ['name' => 'delete-employee-statuses', 'label' => 'Hapus Status Pegawai', 'group' => null],

            # Employee Type related permission
            ['name' => 'read-employee-types', 'label' => 'Baca Jenis Pegawai', 'group' => null],
            ['name' => 'create-employee-types', 'label' => 'Buat Jenis Pegawai', 'group' => null],
            ['name' => 'update-employee-types', 'label' => 'Edit Jenis Pegawai', 'group' => null],
            ['name' => 'delete-employee-types', 'label' => 'Hapus Jenis Pegawai', 'group' => null],

            # Roles related permission
            ['name' => 'read-roles', 'label' => 'Baca Role', 'group' => null],
            ['name' => 'create-roles', 'label' => 'Buat Role', 'group' => null],
            ['name' => 'update-roles', 'label' => 'Edit Role', 'group' => null],
            ['name' => 'delete-roles', 'label' => 'Hapus Role', 'group' => null],
            ['name' => 'change-roles', 'label' => 'Edit Hak Akses', 'group' => null],

            # Academic Activity Related Permissions
            ['name' => 'read-academic-activities', 'label' => 'Baca Aktivitas Akademik', 'group' => null],
            ['name' => 'update-academic-activities', 'label' => 'Edit Aktivitas Akademik', 'group' => null],
            ['name' => 'delete-academic-activities', 'label' => 'Hapus Aktivitas Akademik', 'group' => null],
            ['name' => 'create-academic-activities', 'label' => 'Buat Aktivitas Akademik', 'group' => null],

            # Universities Related Permissions
            ['name' => 'read-universities', 'label' => 'Baca Universitas Luar', 'group' => null],
            ['name' => 'update-universities', 'label' => 'Edit Universitas Luar', 'group' => null],
            ['name' => 'delete-universities', 'label' => 'Hapus Universitas Luar', 'group' => null],
            ['name' => 'create-universities', 'label' => 'Buat Universitas Luar', 'group' => null],

            # Meeting Types Related Permissions
            ['name' => 'read-meeting-types', 'label' => 'Baca Jenis Pertemuan', 'group' => null],
            ['name' => 'update-meeting-types', 'label' => 'Edit Jenis Pertemuan', 'group' => null],
            ['name' => 'delete-meeting-types', 'label' => 'Hapus Jenis Pertemuan', 'group' => null],
            ['name' => 'create-meeting-types', 'label' => 'Buat Jenis Pertemuan', 'group' => null],

            # Religions Related Permissions
            ['name' => 'read-religions', 'label' => 'Baca Data Agama', 'group' => null],
            ['name' => 'update-religions', 'label' => 'Edit Data Agama', 'group' => null],
            ['name' => 'delete-religions', 'label' => 'Hapus Data Agama', 'group' => null],
            ['name' => 'create-religions', 'label' => 'Buat Data Agama', 'group' => null],

            # Ethnics Related Permissions
            ['name' => 'read-ethnics', 'label' => 'Baca Data Suku', 'group' => null],
            ['name' => 'update-ethnics', 'label' => 'Edit Data Suku', 'group' => null],
            ['name' => 'delete-ethnics', 'label' => 'Hapus Data Suku', 'group' => null],
            ['name' => 'create-ethnics', 'label' => 'Buat Data Suku', 'group' => null],

            # Lecture System Related Permissions
            ['name' => 'read-lecture-systems', 'label' => 'Baca Sistem Kuliah', 'group' => null],
            ['name' => 'update-lecture-systems', 'label' => 'Edit Sistem Kuliah', 'group' => null],
            ['name' => 'delete-lecture-systems', 'label' => 'Hapus Sistem Kuliah', 'group' => null],
            ['name' => 'create-lecture-systems', 'label' => 'Buat Sistem Kuliah', 'group' => null],

            # Time Slot Related Permissions
            ['name' => 'read-time-slots', 'label' => 'Baca Jam Kuliah', 'group' => null],
            ['name' => 'update-time-slots', 'label' => 'Edit Jam Kuliah', 'group' => null],
            ['name' => 'delete-time-slots', 'label' => 'Hapus Jam Kuliah', 'group' => null],
            ['name' => 'create-time-slots', 'label' => 'Buat Jam Kuliah', 'group' => null],

            # Class Group Related Permissions
            ['name' => 'read-class-groups', 'label' => 'Baca Grup Kelas', 'group' => null],
            ['name' => 'update-class-groups', 'label' => 'Edit Grup Kelas', 'group' => null],
            ['name' => 'delete-class-groups', 'label' => 'Hapus Grup Kelas', 'group' => null],
            ['name' => 'create-class-groups', 'label' => 'Buat Grup Kelas', 'group' => null],
            # Student Status Related Permissions
            ['name' => 'read-student-statuses', 'label' => 'Baca Status Mahasiswa', 'group' => null],
            ['name' => 'update-student-statuses', 'label' => 'Edit Status Mahasiswa', 'group' => null],
            ['name' => 'delete-student-statuses', 'label' => 'Hapus Status Mahasiswa', 'group' => null],
            ['name' => 'create-student-statuses', 'label' => 'Buat Status Mahasiswa', 'group' => null],
            # Contact Person Related Permissions
            ['name' => 'read-contact-persons', 'label' => 'Baca Kontak Person', 'group' => null],
            ['name' => 'update-contact-persons', 'label' => 'Edit Kontak Person', 'group' => null],
            ['name' => 'delete-contact-persons', 'label' => 'Hapus Kontak Person', 'group' => null],
            ['name' => 'create-contact-persons', 'label' => 'Buat Kontak Person', 'group' => null],

            # Country Related Permissions
            ['name' => 'read-countries', 'label' => 'Baca Data Negara', 'group' => null],
            ['name' => 'update-countries', 'label' => 'Edit Data Negara', 'group' => null],
            ['name' => 'delete-countries', 'label' => 'Hapus Data Negara', 'group' => null],
            ['name' => 'create-countries', 'label' => 'Buat Data Negara', 'group' => null],

            # Sub Districts Related Permissions
            ['name' => 'read-sub-districts', 'label' => 'Baca Data Kecamatan', 'group' => null],
            ['name' => 'update-sub-districts', 'label' => 'Baca Data Kecamatan', 'group' => null],
            ['name' => 'delete-sub-districts', 'label' => 'Baca Data Kecamatan', 'group' => null],
            ['name' => 'create-sub-districts', 'label' => 'Baca Data Kecamatan', 'group' => null],

            # Log Auth User Related Permissions
            ['name' => 'read-user-auth-logs', 'label' => 'Baca Log Auth User', 'group' => null],

            # Agency Related Permissions
            ['name' => 'read-agencies', 'label' => 'Baca Instansi', 'group' => null],
            ['name' => 'create-agencies', 'label' => 'Buat Instansi', 'group' => null],
            ['name' => 'update-agencies', 'label' => 'Edit Instansi', 'group' => null],
            ['name' => 'delete-agencies', 'label' => 'Hapus Instansi', 'group' => null],

            # Education Level Related Permissions
            ['name' => 'read-education-levels', 'label' => 'Baca Tingkat Pendidikan', 'group' => null],
            ['name' => 'create-education-levels', 'label' => 'Buat Tingkat Pendidikan', 'group' => null],
            ['name' => 'update-education-levels', 'label' => 'Edit Tingkat Pendidikan', 'group' => null],
            ['name' => 'delete-education-levels', 'label' => 'Hapus Tingkat Pendidikan', 'group' => null],

            # Student Activity Category Related Permissions
            ['name' => 'read-student-activity-categories', 'label' => 'Baca Jenis Kegiatan Pendukung', 'group' => null],
            ['name' => 'create-student-activity-categories', 'label' => 'Buat Jenis Kegiatan Pendukung', 'group' => null],
            ['name' => 'update-student-activity-categories', 'label' => 'Edit Jenis Kegiatan Pendukung', 'group' => null],
            ['name' => 'delete-student-activity-categories', 'label' => 'Hapus Jenis Kegiatan Pendukung', 'group' => null],
            # Student Activity Related Permissions
            ['name' => 'read-activities', 'label' => 'Baca Aktivitas Mahasiswa', 'group' => null],
            ['name' => 'create-activities', 'label' => 'Buat Aktivitas Mahasiswa', 'group' => null],
            ['name' => 'update-activities', 'label' => 'Edit Aktivitas Mahasiswa', 'group' => null],
            ['name' => 'delete-activities', 'label' => 'Hapus Aktivitas Mahasiswa', 'group' => null],

            # Disability Related Permissions
            ['name' => 'read-disabilities', 'label' => 'Baca Kebutuhan Khusus', 'group' => null],
            ['name' => 'create-disabilities', 'label' => 'Buat Kebutuhan Khusus', 'group' => null],
            ['name' => 'update-disabilities', 'label' => 'Edit Kebutuhan Khusus', 'group' => null],
            ['name' => 'delete-disabilities', 'label' => 'Hapus Kebutuhan Khusus', 'group' => null],

            # Type Of Stay Related Permissions
            ['name' => 'read-type-of-stays', 'label' => 'Baca Jenis Tinggal', 'group' => null],
            ['name' => 'create-type-of-stays', 'label' => 'Buat Jenis Tinggal', 'group' => null],
            ['name' => 'update-type-of-stays', 'label' => 'Edit Jenis Tinggal', 'group' => null],
            ['name' => 'delete-type-of-stays', 'label' => 'Hapus Jenis Tinggal', 'group' => null],

            # Transportation Related Permissions
            ['name' => 'read-transportations', 'label' => 'Baca Transportasi', 'group' => null],
            ['name' => 'create-transportations', 'label' => 'Buat Transportasi', 'group' => null],
            ['name' => 'update-transportations', 'label' => 'Edit Transportasi', 'group' => null],
            ['name' => 'delete-transportations', 'label' => 'Hapus Transportasi', 'group' => null],

            # Province Related Permissions
            ['name' => 'read-provinces', 'label' => 'Baca Provinsi', 'group' => null],
            ['name' => 'create-provinces', 'label' => 'Buat Provinsi', 'group' => null],
            ['name' => 'update-provinces', 'label' => 'Edit Provinsi', 'group' => null],
            ['name' => 'delete-provinces', 'label' => 'Hapus Provinsi', 'group' => null],

            # City Related Permissions
            ['name' => 'read-cities', 'label' => 'Baca Kota', 'group' => null],
            ['name' => 'create-cities', 'label' => 'Buat Kota', 'group' => null],
            ['name' => 'update-cities', 'label' => 'Edit Kota', 'group' => null],
            ['name' => 'delete-cities', 'label' => 'Hapus Kota', 'group' => null],
            # Semester Related Permissions
            ['name' => 'read-semesters', 'label' => 'Baca Kota', 'group' => null],
            ['name' => 'create-semesters', 'label' => 'Buat Kota', 'group' => null],
            ['name' => 'update-semesters', 'label' => 'Edit Kota', 'group' => null],
            ['name' => 'delete-semesters', 'label' => 'Hapus Kota', 'group' => null],

            # Academic Calendars Related Permissions
            ['name' => 'read-academic-calendars', 'label' => 'Baca Kalender Akademik', 'group' => null],
            ['name' => 'update-academic-calendars', 'label' => 'Edit Kalender Akademik', 'group' => null],
            ['name' => 'delete-academic-calendars', 'label' => 'Hapus Kalender Akademik', 'group' => null],
            ['name' => 'create-academic-calendars', 'label' => 'Buat Kalender Akademik', 'group' => null],
            # Academic Years Related Permissions
            ['name' => 'read-academic-years', 'label' => 'Baca Tahun Ajaran', 'group' => null],
            ['name' => 'update-academic-years', 'label' => 'Edit Tahun Ajaran', 'group' => null],
            ['name' => 'delete-academic-years', 'label' => 'Hapus Tahun Ajaran', 'group' => null],
            ['name' => 'create-academic-years', 'label' => 'Buat Tahun Ajaran', 'group' => null],

            # University Profiles Related Permissions
            ['name' => 'read-university-profiles', 'label' => 'Baca Profil Universitas/Politeknik', 'group' => null],
            ['name' => 'update-university-profiles', 'label' => 'Edit Profil Universitas/Politeknik', 'group' => null],

            # Announcement Related Permissions
            ['name' => 'read-announcements', 'label' => 'Baca Pengumuman', 'group' => null],
            ['name' => 'update-announcements', 'label' => 'Edit Pengumuman', 'group' => null],
            ['name' => 'delete-announcements', 'label' => 'Hapus Pengumuman', 'group' => null],
            ['name' => 'create-announcements', 'label' => 'Buat Pengumuman', 'group' => null],

            # Herregistration Related Permissions
            ['name' => 'read-her-registration', 'label' => 'Baca Daftar Ulang', 'group' => null],
            # Status Semester Related Permissions
            ['name' => 'read-status-semester', 'label' => 'Baca Status Semester', 'group' => null],
            ['name' => 'update-status-semester', 'label' => 'Edit Status Semester', 'group' => null],
            ['name' => 'create-status-semester', 'label' => 'Buat Status Semester', 'group' => null],

            # Professions Related Permissions
            ['name' => 'read-professions', 'label' => 'Baca Pekerjaan', 'group' => null],
            ['name' => 'update-professions', 'label' => 'Edit Pekerjaan', 'group' => null],
            ['name' => 'delete-professions', 'label' => 'Hapus Pekerjaan', 'group' => null],
            ['name' => 'create-professions', 'label' => 'Buat Pekerjaan', 'group' => null],

            # Academic Calendar Monitoring Related Permissions
            ['name' => 'read-academic-calendar-monitorings', 'label' => 'Baca Monitoring Kalender Akademik', 'group' => null],

            # Score Scale Related Permissions
            ['name' => 'read-score-scales', 'label' => 'Baca Skala Nilai', 'group' => null],
            ['name' => 'update-score-scales', 'label' => 'Edit Skala Nilai', 'group' => null],
            ['name' => 'delete-score-scales', 'label' => 'Hapus Skala Nilai', 'group' => null],
            ['name' => 'create-score-scales', 'label' => 'Buat Skala Nilai', 'group' => null],

            # Incomes Related Permissions
            ['name' => 'read-incomes', 'label' => 'Baca Pendapatan', 'group' => null],
            ['name' => 'update-incomes', 'label' => 'Edit Pendapatan', 'group' => null],
            ['name' => 'delete-incomes', 'label' => 'Hapus Pendapatan', 'group' => null],
            ['name' => 'create-incomes', 'label' => 'Buat Pendapatan', 'group' => null],

            # Employee Transcript (Angkatan) Related Permissions
            ['name' => 'read-transcripts', 'label' => 'Baca Transkrip Angkatan', 'group' => null],
            ['name' => 'update-transcripts', 'label' => 'Edit Transkrip Angkatan', 'group' => null],

            # Employee Active Statuses Related Permissions
            ['name' => 'read-employee-active-statuses', 'label' => 'Baca Status Aktif Pegawai', 'group' => null],
            ['name' => 'update-employee-active-statuses', 'label' => 'Edit Status Aktif Pegawai', 'group' => null],
            ['name' => 'delete-employee-active-statuses', 'label' => 'Hapus Status Aktif Pegawai', 'group' => null],
            ['name' => 'create-employee-active-statuses', 'label' => 'Buat Status Aktif Pegawai', 'group' => null],

            # Judicial Periods Related Permissions
            ['name' => 'read-judicial-periods', 'label' => 'Baca Periode Yudisium', 'group' => null],
            ['name' => 'update-judicial-periods', 'label' => 'Edit Periode Yudisium', 'group' => null],
            ['name' => 'delete-judicial-periods', 'label' => 'Hapus Periode Yudisium', 'group' => null],
            ['name' => 'create-judicial-periods', 'label' => 'Buat Periode Yudisium', 'group' => null],
            # Judicial Requirement Related Permissions
            ['name' => 'read-judicial-requirements', 'label' => 'Baca Syarat Yudisium', 'group' => null],
            ['name' => 'update-judicial-requirements', 'label' => 'Edit Syarat Yudisium', 'group' => null],
            ['name' => 'delete-judicial-requirements', 'label' => 'Hapus Syarat Yudisium', 'group' => null],
            ['name' => 'create-judicial-requirements', 'label' => 'Buat Syarat Yudisium', 'group' => null],
            # Achievement Groups Related Permissions
            ['name' => 'read-achievement-groups', 'label' => 'Baca Kelompok Prestasi', 'group' => null],
            ['name' => 'update-achievement-groups', 'label' => 'Edit Kelompok Prestasi', 'group' => null],
            ['name' => 'delete-achievement-groups', 'label' => 'Hapus Kelompok Prestasi', 'group' => null],
            ['name' => 'create-achievement-groups', 'label' => 'Buat Kelompok Prestasi', 'group' => null],
            # Achievement Types Related Permissions
            ['name' => 'read-achievement-types', 'label' => 'Baca Jenis Prestasi', 'group' => null],
            ['name' => 'update-achievement-types', 'label' => 'Edit Jenis Prestasi', 'group' => null],
            ['name' => 'delete-achievement-types', 'label' => 'Hapus Jenis Prestasi', 'group' => null],
            ['name' => 'create-achievement-types', 'label' => 'Buat Jenis Prestasi', 'group' => null],
            # Achievements Related Permission
            ['name' => 'read-achievement', 'label' => 'Baca Daftar Prestasi', 'group' => null],
            ['name' => 'create-achievement', 'label' => 'Buat Daftar Prestasi', 'group' => null],
            ['name' => 'update-achievement', 'label' => 'Edit Daftar Prestasi', 'group' => null],
            ['name' => 'delete-achievement', 'label' => 'Hapus Daftar Prestasi', 'group' => null],
            # Thesis Requirement Related Permissions
            // ['name' => 'read-thesis-requirements', 'label' => 'Baca Syarat Ujian', 'group' => null],
            // ['name' => 'update-thesis-requirements', 'label' => 'Edit Syarat Ujian', 'group' => null],
            // ['name' => 'delete-thesis-requirements', 'label' => 'Hapus Syarat Ujian', 'group' => null],
            // ['name' => 'create-thesis-requirements', 'label' => 'Buat Syarat Ujian', 'group' => null],
            # Thesis Related Permissions
            ['name' => 'read-theses', 'label' => 'Baca Tugas Akhir', 'group' => null],
            ['name' => 'update-theses', 'label' => 'Edit Tugas Akhir', 'group' => null],
            ['name' => 'delete-theses', 'label' => 'Hapus Tugas Akhir', 'group' => null],
            ['name' => 'create-theses', 'label' => 'Buat Tugas Akhir', 'group' => null],
            # Scholarship Types Related Permissions
            ['name' => 'read-scholarship-types', 'label' => 'Baca Jenis Beasiswa', 'group' => null],
            ['name' => 'update-scholarship-types', 'label' => 'Edit Jenis Beasiswa', 'group' => null],
            ['name' => 'delete-scholarship-types', 'label' => 'Hapus Jenis Beasiswa', 'group' => null],
            ['name' => 'create-scholarship-types', 'label' => 'Buat Jenis Beasiswa', 'group' => null],
            # Diploma Companion Related Permissions
            ['name' => 'read-diploma-companions', 'label' => 'Baca Setting SKPI', 'group' => null],
            ['name' => 'update-diploma-companions', 'label' => 'Edit Setting SKPI', 'group' => null],
            ['name' => 'create-diploma-companions', 'label' => 'Buat Setting SKPI', 'group' => null],

            # Education Level Setting Related Permissions
            ['name' => 'read-education-level-settings', 'label' => 'Baca Tk. Pendidikan Univ', 'group' => null],
            ['name' => 'update-education-level-settings', 'label' => 'Edit Tk. Pendidikan Univ', 'group' => null],
            ['name' => 'delete-education-level-settings', 'label' => 'Hapus Tk. Pendidikan Univ', 'group' => null],
            ['name' => 'create-education-level-settings', 'label' => 'Buat Tk. Pendidikan Univ', 'group' => null],

            # Schedule Related Permissions
            ['name' => 'read-scheduling', 'label' => 'Baca Kelas Kuliah', 'group' => null],
            ['name' => 'update-scheduling', 'label' => 'Edit Kelas Kuliah', 'group' => null],
            ['name' => 'delete-scheduling', 'label' => 'Hapus Kelas Kuliah', 'group' => null],
            ['name' => 'create-scheduling', 'label' => 'Buat Kelas Kuliah', 'group' => null],

            ['name' => 'read-judicials', 'label' => 'Baca Yudisium', 'group' => null],
            ['name' => 'update-judicials', 'label' => 'Edit Yudisium', 'group' => null],
            ['name' => 'delete-judicials', 'label' => 'Hapus Yudisium', 'group' => null],
            ['name' => 'create-judicials', 'label' => 'Buat Yudisium', 'group' => null],

            # Graduation Predicate Related Permissions
            ['name' => 'read-graduation-predicates', 'label' => 'Baca Predikat Kelulusan', 'group' => null],
            ['name' => 'update-graduation-predicates', 'label' => 'Edit Predikat Kelulusan', 'group' => null],
            ['name' => 'delete-graduation-predicates', 'label' => 'Hapus Predikat Kelulusan', 'group' => null],
            ['name' => 'create-graduation-predicates', 'label' => 'Buat Predikat Kelulusan', 'group' => null],

            # Class Schedule Related Permissions
            ['name' => 'read-class-schedules', 'label' => 'Baca Jadwal Kelas', 'group' => null],
            ['name' => 'create-class-schedules', 'label' => 'Buat Jadwal Kelas', 'group' => null],
            ['name' => 'delete-class-schedules', 'label' => 'Hapus Jadwal Kelas', 'group' => null],
            ['name' => 'update-class-schedules', 'label' => 'Edit Presensi Jadwal Kelas', 'group' => null],

            # Class Recap Related Permissions
            ['name' => 'read-class-recap', 'label' => 'Baca Rekap Pengunaan Ruang', 'group' => null],

            # Curriculum Schedule Related Permissions
            ['name' => 'read-curriculums', 'label' => 'Baca Kurikulum', 'group' => null],
            ['name' => 'update-curriculums', 'label' => 'Edit Kurikulum', 'group' => null],
            ['name' => 'delete-curriculums', 'label' => 'Hapus Kurikulum', 'group' => null],
            ['name' => 'create-curriculums', 'label' => 'Buat Kurikulum', 'group' => null],

            # Guardianship Related Permissions
            ['name' => 'read-guardianships', 'label' => 'Baca Bimbingan Akademik', 'group' => null],
            ['name' => 'update-guardianships', 'label' => 'Edit Bimbingan Akademik', 'group' => null],
            ['name' => 'delete-guardianships', 'label' => 'Hapus Bimbingan Akademik', 'group' => null],
            ['name' => 'create-guardianships', 'label' => 'Buat Bimbingan Akademik', 'group' => null],

            # Course Curiculum Schedule Related Permissions
            ['name' => 'read-course-curiculums', 'label' => 'Baca Kurikulum Mata Kuliah', 'group' => null],
            ['name' => 'update-course-curiculums', 'label' => 'Edit Kurikulum Mata Kuliah', 'group' => null],
            ['name' => 'delete-course-curiculums', 'label' => 'Hapus Kurikulum Mata Kuliah', 'group' => null],
            ['name' => 'create-course-curiculums', 'label' => 'Buat Kurikulum Mata Kuliah', 'group' => null],

            ['name' => 'read-college-class-schedules', 'label' => 'Baca Jadwal Kelas Kuliah', 'group' => null],
            ['name' => 'update-college-class-schedules', 'label' => 'Edit Jadwal Kelas Kuliah', 'group' => null],
            ['name' => 'delete-college-class-schedules', 'label' => 'Hapus Jadwal Kelas Kuliah', 'group' => null],
            ['name' => 'create-college-class-schedules', 'label' => 'Buat Jadwal Kelas Kuliah', 'group' => null],

            # Student Grade Related Permissions
            ['name' => 'read-student-grades', 'label' => 'Baca Nilai Mahasiswa', 'group' => null],
            ['name' => 'update-student-grades', 'label' => 'Edit Nilai Mahasiswa', 'group' => null],

            # Student Grade Related Permissions
            ['name' => 'read-presences', 'label' => 'Baca Presensi Mahasiswa', 'group' => null],
            ['name' => 'update-presences', 'label' => 'Edit Presensi Mahasiswa', 'group' => null],

            # Class Attendance Related Permissions
            ['name' => 'read-class-attendances', 'label' => 'Baca Presensi Kelas', 'group' => null],
            ['name' => 'update-class-attendances', 'label' => 'Edit Presensi Kelas', 'group' => null],

            # Teaching Lecturer Related Permissions
            ['name' => 'read-teaching-lecturers', 'label' => 'Baca Dosen Ajar Kelas', 'group' => null],
            ['name' => 'update-teaching-lecturers', 'label' => 'Edit Dosen Ajar Kelas', 'group' => null],
            ['name' => 'create-teaching-lecturers', 'label' => 'Buat Dosen Ajar Kelas', 'group' => null],
            ['name' => 'delete-teaching-lecturers', 'label' => 'Hapus Dosen Ajar Kelas', 'group' => null],
            # Exam Schedule Related Permissions
            ['name' => 'read-exam-schedules', 'label' => 'Baca Jadwal Ujian', 'group' => null],
            ['name' => 'update-exam-schedules', 'label' => 'Edit Jadwal Ujian', 'group' => null],
            ['name' => 'create-exam-schedules', 'label' => 'Buat Jadwal Ujian', 'group' => null],
            ['name' => 'delete-exam-schedules', 'label' => 'Hapus Jadwal Ujian', 'group' => null],
            # Activity Score Conversion Related Permissions
            ['name' => 'read-score-conversions', 'label' => 'Baca Konversi Nilai', 'group' => null],
            ['name' => 'update-score-conversions', 'label' => 'Edit Konversi Nilai', 'group' => null],
            ['name' => 'create-score-conversions', 'label' => 'Buat Konversi Nilai', 'group' => null],
            ['name' => 'delete-score-conversions', 'label' => 'Hapus Konversi Nilai', 'group' => null],

            # Class Participants
            ['name' => 'read-class-participants', 'label' => 'Baca Peserta Kelas', 'group' => null],
            ['name' => 'update-class-participants', 'label' => 'Baca Peserta Kelas', 'group' => null],
            ['name' => 'delete-class-participants', 'label' => 'Hapus Peserta Kelas', 'group' => null],
            ['name' => 'create-class-participants', 'label' => 'Buat Peserta Kelas', 'group' => null],

            # Weekly Schedules
            ['name' => 'read-weekly-schedules', 'label' => 'Baca Jadwal Mingguan', 'group' => null],
            ['name' => 'update-weekly-schedules', 'label' => 'Baca Jadwal Mingguan', 'group' => null],
            ['name' => 'delete-weekly-schedules', 'label' => 'Hapus Jadwal Mingguan', 'group' => null],
            ['name' => 'create-weekly-schedules', 'label' => 'Buat Jadwal Mingguan', 'group' => null],

            # Study Program Settings
            ['name' => 'read-study-program-settings', 'label' => 'Baca Setting Prodi', 'group' => null],
            ['name' => 'update-study-program-settings', 'label' => 'Baca Setting Prodi', 'group' => null],
            
            # Study Program Settings
            ['name' => 'read-application-settings', 'label' => 'Baca Setting Aplikasi', 'group' => null],
            ['name' => 'update-application-settings', 'label' => 'Bua Setting Aplikasi', 'group' => null],

            # Scholarship
            ['name' => 'read-scholarships', 'label' => 'Baca Beasiswa', 'group' => null],
            ['name' => 'update-scholarships', 'label' => 'Baca Beasiswa', 'group' => null],
            ['name' => 'delete-scholarships', 'label' => 'Hapus Beasiswa', 'group' => null],
            ['name' => 'create-scholarships', 'label' => 'Buat Beasiswa', 'group' => null],
            # Scholarship
            ['name' => 'read-feeders', 'label' => 'Baca Feeder PDDIKTI', 'group' => null],

            # Reports
            ['name' => 'read-report-final-level', 'label' => 'Baca Laporan Tinggkat Akhir' , 'group' => null],
            ['name' => 'read-report-administration', 'label' => 'Baca Laporan Administrasi' , 'group' => null],
            ['name' => 'read-report-students', 'label' => 'Baca Laporan Mahasiswa', 'group' => null],
            ['name' => 'read-report-presentase-students', 'label' => 'Baca Laporan Presentase Kehadiran MHS', 'group' => null],
            ['name' => 'read-report-empolyee', 'label' => 'Baca Laporan Dosen', 'group' => null],
            ['name' => 'read-report-grades', 'label' => 'Baca Laporan Nilai', 'group' => null],
            ['name' => 'read-recap-schedule-employee', 'label' => 'Baca Rekap Jadwal Dosen', 'group' => null],


            # Report Final Level

            # Graduations Permissions
            ['name' => 'read-graduations', 'label' => 'Baca Kelulusan / Drop Out', 'group' => null],
            ['name' => 'create-graduations', 'label' => 'Buat Kelulusan / Drop Out', 'group' => null],
            ['name' => 'update-graduations', 'label' => 'Update Kelulusan / Drop Out', 'group' => null],
            ['name' => 'delete-graduations', 'label' => 'Delete Kelulusan / Drop Out', 'group' => null],

            # College Contracts
            ['name' => 'read-college-contracts', 'label' => 'Baca Kelas Kuliah', 'group' => null],
            ['name' => 'update-college-contracts', 'label' => 'Baca Kelas Kuliah', 'group' => null],

            // # Judicial Participants
            ['name' => 'read-judicial-participants', 'label' => 'Baca Peserta Yudisium', 'group' => null],
            ['name' => 'create-judicial-participants', 'label' => 'Buat Peserta Yudisium', 'group' => null],
            ['name' => 'update-judicial-participants', 'label' => 'Update Peserta Yudisium', 'group' => null],
            ['name' => 'delete-judicial-participants', 'label' => 'Hapus Peserta Yudisium', 'group' => null],

            # Lecturer Dashboard related permission
            ['name' => 'read-lecturer-dashboard', 'label' => 'Baca Dashboard', 'group' => 'dosen'],
            ['name' => 'read-lecturer-schedule-semester', 'label' => 'Baca Jadwal Semester', 'group' => 'dosen'],
            ['name' => 'read-lecturer-weekly-schedules', 'label' => 'Baca Jadwal Mingguan', 'group' => 'dosen'],
            ['name' => 'read-lecturer-activity', 'label' => 'Baca Kegiatan Pendukung', 'group' => 'dosen'],
            ['name' => 'read-lecturer-profile', 'label' => 'Baca Profil Dosen', 'group' => 'dosen'],
            ['name' => 'read-lecturer-presences', 'label' => 'Baca Presensi', 'group' => 'dosen'],
            ['name' => 'update-lecturer-presences', 'label' => 'Update Presensi', 'group' => 'dosen'],
            ['name' => 'read-lecturer-calendars', 'label' => 'Baca Kalender Akademik', 'group' => 'dosen'],
            ['name' => 'read-lecturer-guardianships', 'label' => 'Baca Pembimbing Akademik', 'group' => 'dosen'],
            ['name' => 'read-lecturer-scheduling', 'label' => 'Baca Kelas Kuliah', 'group' => 'dosen'],
            ['name' => 'update-lecturer-college-contracts', 'label' => 'Update Kontrak Kuliah', 'group' => 'dosen'],
            ['name' => 'read-lecturer-college-class-schedules', 'label' => 'Baca Jadwal Kelas Kuliah', 'group' => 'dosen'],
            ['name' => 'update-lecturer-college-class-schedules', 'label' => 'Edit Jadwal Kelas Kuliah', 'group' => 'dosen'],
            ['name' => 'delete-lecturer-college-class-schedules', 'label' => 'Hapus Jadwal Kelas Kuliah', 'group' => 'dosen'],
            ['name' => 'create-lecturer-college-class-schedules', 'label' => 'Buat Jadwal Kelas Kuliah', 'group' => 'dosen'],
            ['name' => 'read-lecturer-student-grades', 'label' => 'Baca Nilai Mahasiswa', 'group' => 'dosen'],
            ['name' => 'update-lecturer-student-grades', 'label' => 'Edit Nilai Mahasiswa', 'group' => 'dosen'],
            ['name' => 'read-lecturer-announcements', 'label' => 'Baca Pengumuman', 'group' => 'dosen'],

            # Student Dashboard related permission
            ['name' => 'read-student-dashboard', 'label' => 'Baca Dashboard', 'group' => 'mahasiswa'],
            ['name' => 'read-student-krs', 'label' => 'Baca KRS', 'group' => 'mahasiswa'],
            ['name' => 'read-student-semester-status', 'label' => 'Baca Status Semester', 'group' => 'mahasiswa'],
            ['name' => 'read-student-curriculums', 'label' => 'Baca Kurikulum Mahasiswa', 'group' => 'mahasiswa'],
            ['name' => 'read-student-schedule-semester', 'label' => 'Baca Jadwal Semester', 'group' => 'mahasiswa'],
            ['name' => 'read-student-schedule-weekly', 'label' => 'Baca Jadwal Mingguan', 'group' => 'mahasiswa'],
            ['name' => 'read-student-her-registration', 'label' => 'Baca Daftar Ulang Mahasiswa', 'group' => 'mahasiswa'],
            ['name' => 'read-student-profile', 'label' => 'Baca Profil Mahasiswa', 'group' => 'mahasiswa'],
            ['name' => 'update-student-profile', 'label' => 'Baca Profil Mahasiswa', 'group' => 'mahasiswa'],
            ['name' => 'read-student-announcements', 'label' => 'Baca Pengumuman Mahasiswa', 'group' => 'mahasiswa'],
            ['name' => 'read-student-scores', 'label' => 'Baca Nilai Mahasiswa', 'group' => 'mahasiswa'],
            ['name' => 'read-student-activities', 'label' => 'Baca Kegiatan Pendukung', 'group' => 'mahasiswa'],
            ['name' => 'read-student-calendars', 'label' => 'Baca Kalender Akademik', 'group' => 'mahasiswa'],
            ['name' => 'read-student-presences', 'label' => 'Baca Presensi Mahasiswa', 'group' => 'mahasiswa'],
            ['name' => 'read-student-achievements', 'label' => 'Baca Prestasi Mahasiswa', 'group' => 'mahasiswa'],
            ['name' => 'read-student-study-result-cards', 'label' => 'Baca KHS Mahasiswa', 'group' => 'mahasiswa'],
            ['name' => 'read-student-transcript', 'label' => 'Baca Transkrip Mahasiswa', 'group' => 'mahasiswa'],
            #Update Password

        ]);


        $this->insertPermission($webPermission);
    }

    private function insertPermission(Collection $permissions, $guardName = 'web')
    {
        Permission::insert($permissions->map(function ($permission) use ($guardName) {
            return [
                'name' => $permission['name'],
                'display_name' => $permission['label'],
                'guard_name' => $guardName,
                'group' => $permission['group'],
                'created_at' => Carbon::now()
            ];
        })->toArray());
    }
}
