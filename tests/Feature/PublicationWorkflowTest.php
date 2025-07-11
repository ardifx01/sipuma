<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Models\Publication;
use App\Models\StudentProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $dosen;
    protected $mahasiswa;

    protected function setUp(): void
    {
        parent::setUp();
        // Buat user admin, dosen, mahasiswa
        $this->admin = User::factory()->create(['role' => 'admin', 'email' => 'admin@sipuma.test']);
        $this->dosen = User::factory()->create(['role' => 'dosen', 'email' => 'dosen@sipuma.test']);
        $this->mahasiswa = User::factory()->create(['role' => 'mahasiswa', 'email' => 'mahasiswa@sipuma.test']);
        StudentProfile::factory()->create([
            'user_id' => $this->mahasiswa->id,
            'supervisor_id' => $this->dosen->id,
        ]);
    }

    public function test_mahasiswa_submit_dosen_approve_admin_approve()
    {
        // Mahasiswa submit publikasi
        $publication = Publication::factory()->create([
            'student_id' => $this->mahasiswa->id,
            'dosen_status' => 'pending',
            'admin_status' => 'pending',
        ]);

        // Dosen approve
        $this->actingAs($this->dosen)
            ->post(route('dashboard.dosen-approve', $publication->id), ['feedback' => 'OK'])
            ->assertRedirect();
        $publication->refresh();
        $this->assertEquals('approved', $publication->dosen_status);
        $this->assertEquals('pending', $publication->admin_status);

        // Admin approve
        $this->actingAs($this->admin)
            ->post(route('dashboard.admin-approve', $publication->id), ['feedback' => 'OK'])
            ->assertRedirect();
        $publication->refresh();
        $this->assertEquals('approved', $publication->admin_status);
    }

    public function test_mahasiswa_submit_dosen_reject_admin_cannot_review()
    {
        // Mahasiswa submit publikasi
        $publication = Publication::factory()->create([
            'student_id' => $this->mahasiswa->id,
            'dosen_status' => 'pending',
            'admin_status' => 'pending',
        ]);

        // Dosen reject
        $this->actingAs($this->dosen)
            ->post(route('dashboard.dosen-reject', $publication->id), ['feedback' => 'Tidak layak'])
            ->assertRedirect();
        $publication->refresh();
        $this->assertEquals('rejected', $publication->dosen_status);
        $this->assertEquals('rejected', $publication->admin_status);

        // Admin mencoba approve (harus gagal)
        $this->actingAs($this->admin)
            ->post(route('dashboard.admin-approve', $publication->id), ['feedback' => 'Tetap ingin approve'])
            ->assertRedirect()
            ->assertSessionHas('error');
        $publication->refresh();
        $this->assertEquals('rejected', $publication->admin_status);
    }

    public function test_admin_cannot_review_before_dosen_approve()
    {
        // Mahasiswa submit publikasi
        $publication = Publication::factory()->create([
            'student_id' => $this->mahasiswa->id,
            'dosen_status' => 'pending',
            'admin_status' => 'pending',
        ]);

        // Admin mencoba approve sebelum dosen approve
        $this->actingAs($this->admin)
            ->post(route('dashboard.admin-approve', $publication->id), ['feedback' => 'Tidak boleh'])
            ->assertRedirect()
            ->assertSessionHas('error');
        $publication->refresh();
        $this->assertEquals('pending', $publication->admin_status);
    }
} 