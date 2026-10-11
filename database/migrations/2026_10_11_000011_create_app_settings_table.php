<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });

        // Seed default institutional settings
        $defaults = [
            [
                'key' => 'institution_name',
                'value' => 'Pesantren Ell Firdaus',
                'group' => 'institution',
            ],
            [
                'key' => 'institution_tagline',
                'value' => 'Kabupaten Labuhanbatu Selatan, Sumatera Utara',
                'group' => 'institution',
            ],
            [
                'key' => 'institution_address',
                'value' => 'Jl. Lintas Sumatera Km. 345, Kec. Kotapinang, Kab. Labuhanbatu Selatan, Sumatera Utara 21464',
                'group' => 'institution',
            ],
            [
                'key' => 'institution_phone',
                'value' => '(0624) 495812 / 0812-6543-2109',
                'group' => 'institution',
            ],
            [
                'key' => 'institution_email',
                'value' => 'info@ellfirdaus.ac.id',
                'group' => 'institution',
            ],
            [
                'key' => 'institution_website',
                'value' => 'https://ellfirdaus.ac.id',
                'group' => 'institution',
            ],
            [
                'key' => 'institution_letterhead_title',
                'value' => 'YAYASAN PONDOK PESANTREN ELL FIRDAUS',
                'group' => 'letterhead',
            ],
            [
                'key' => 'institution_letterhead_subtitle',
                'value' => 'PANITIA PENERIMAAN SANTRI BARU (PSB)',
                'group' => 'letterhead',
            ],
            [
                'key' => 'leader_name',
                'value' => 'H. Ahmad Fauzi, Lc., M.Pd.',
                'group' => 'officials',
            ],
            [
                'key' => 'leader_title',
                'value' => 'Pimpinan Pondok Pesantren',
                'group' => 'officials',
            ],
            [
                'key' => 'leader_nip',
                'value' => 'NIP. 19780512 200604 1 003',
                'group' => 'officials',
            ],
            [
                'key' => 'committee_chairman_name',
                'value' => 'Ust. Muhammad Ridwan, S.Pd.I.',
                'group' => 'officials',
            ],
            [
                'key' => 'committee_chairman_title',
                'value' => 'Ketua Panitia Seleksi PSB',
                'group' => 'officials',
            ],
            [
                'key' => 'logo_path',
                'value' => null,
                'group' => 'appearance',
            ],
        ];

        $now = now();
        foreach ($defaults as &$record) {
            $record['created_at'] = $now;
            $record['updated_at'] = $now;
        }

        DB::table('app_settings')->insert($defaults);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
