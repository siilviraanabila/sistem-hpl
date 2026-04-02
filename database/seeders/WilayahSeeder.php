<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class WilayahSeeder extends Seeder
{
    public function run()
    {
        // 1. Matikan Foreign Key agar tabel bisa dikosongkan tanpa error
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kabupaten')->truncate();
        DB::table('provinsi')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Baca dan Insert Data Provinsi
        $jsonProvinsi = File::get(database_path('data/provinces.json'));
        $dataProvinsi = json_decode($jsonProvinsi, true);

        $insertProvinsi = [];
        foreach ($dataProvinsi as $prov) {
            $insertProvinsi[] = [
                'id'            => $prov['id'],
                'nama_provinsi' => $prov['name'],
            ];
        }
        DB::table('provinsi')->insert($insertProvinsi);
        $this->command->info('Data Provinsi berhasil dimasukkan!');

        // 3. Baca dan Insert Data Kabupaten
        $jsonKabupaten = File::get(database_path('data/regencies.json'));
        $dataKabupaten = json_decode($jsonKabupaten, true);

        $insertKabupaten = [];
        foreach ($dataKabupaten as $kab) {
            $insertKabupaten[] = [
                'id'             => $kab['id'],
                'provinsi_id'    => $kab['province_id'],
                'nama_kabupaten' => $kab['name'],
            ];
        }

        // Insert kabupaten menggunakan chunk agar prosesnya ringan dan cepat
        $chunks = array_chunk($insertKabupaten, 500);
        foreach ($chunks as $chunk) {
            DB::table('kabupaten')->insert($chunk);
        }
        $this->command->info('Data Kabupaten berhasil dimasukkan!');
    }
}