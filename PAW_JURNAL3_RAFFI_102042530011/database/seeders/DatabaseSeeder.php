<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\MataKuliah;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Pengguna LMS (1 Dosen & 1 Mahasiswa)
        // TODO 1 : Buat akun awal untuk Dosen.
        // Hint:
        // - Gunakan email sebagai identitas akun.
        // - Lengkapi nama, password yang di-hash, role Dosen,dan nomor induk.
        
        // Akun Dosen
        $dosen = User::updateOrCreate(
            ['email' => 'dosen@celoe.test'],
            [
                'name' => 'Bu dewi S.kom M.kom',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
                'nomor_induk' => '198001012010041001',
            ]
        );

        // Akun Mahasiswa
        $mahasiswa = User::updateOrCreate(
            ['email' => 'mahasiswa@celoe.test'],
            [
                'name' => 'Raffi Fernandes',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '1303240143',
            ]
        );

        // Data awal: 1 mata kuliah milik dosen
        $course = MataKuliah::updateOrCreate(
            ['kode_mk' => 'PAW'],
            [
                'nama_mk' => 'Pengembangan Aplikasi Website',
                'deskripsi' => 'Mempelajari pengembangan aplikasi web berbasis framework Laravel.',
                'sks' => 3,
                'semester' => 'Ganjil 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        // Mahasiswa terdaftar (approved) pada mata kuliah tersebut
        $course->students()->syncWithoutDetaching([
            $mahasiswa->id => ['status' => 'approved'],
        ]);

        // 1 tugas
        $assignment = Assignment::updateOrCreate(
            ['course_id' => $course->id, 'judul' => 'Tugas 01 - Bikin Sistem Kasir pake Laravel'],
            [
                'deskripsi' => 'Buatlah sistem kasir sederhana menggunakan Laravel, lengkap dengan operasi CRUD.',
                'deadline' => '2026-10-22 23:59:00',
            ]
        );

        // Mahasiswa sudah mengumpulkan tugas tersebut
        Submission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'user_id' => $mahasiswa->id],
            [
                'jawaban' => 'Sistem kasir sudah selesai dikerjakan. Repository: https://github.com/username/kasir-laravel',
                'submitted_at' => '2026-10-20 10:00:00',
            ]
        );
    }
}
