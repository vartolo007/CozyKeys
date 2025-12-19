<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $damascus_cities = [
            'Old Damascus',
            'Sarouja',
            'Al-Qanawat',
            'Al-Midan',
            'Al-Shaghour',
            'Kafr Sousa',
            'Al-Mazzeh',
            'Dummar',
            'Barzeh',
            'Rukn al-Din',
        ];

        foreach ($damascus_cities as $city) {
            City::firstOrCreate(['name' => $city, 'gov_id' => 1]);
        }
        $damascus_countryside_cities = [


            'Douma',
            'Al-Tall',
            'Al-Nabek',
            'Yabroud',
            'Ma\'aloula',
            'Jaramana',
            'Qatana',

            'Al-Hamah',
            'Beit Jen',
        ];

        foreach ($damascus_countryside_cities as $city) {
            City::firstOrCreate(['name' => $city, 'gov_id' => 2]);
        }
        $aleppo_cities = [
            'Jabal Semaan',
            'Afrin',
            'Azaz',
            'Al-Bab',
            'Manbij',
            'Ain al-Arab',
            'Jarabulus',
            'As-Safira',
            'Al-Atarib',
            'Deir Hafer',
        ];

        foreach ($aleppo_cities as $city) {
            City::firstOrCreate(['name' => $city, 'gov_id' => 3]);
        }
        $homs_cities = [
            'Homs',
            'Palmyra',
            'Al-Rastan',
            'Al-Qusayr',
            'Talkalakh',
            'Al-Mukharram',
            'Al-Qaryatayn',
            'Sadad',
            'Mahin',
            'Hasyaa',
        ];

        foreach ($homs_cities as $city) {
            City::firstOrCreate(['name' => $city, 'gov_id' => 4]);
        }
        $hama_cities = [
            'Hama',
            'Masyaf',
            'Al-Suqaylabiyah',
            'Mahardah',
            'Salamiyah',
            'Suran',
            'Kafr Zita',
            'Taybat al-Imam',
            'Al-Lataminah',
            'Halfaya',
        ];

        foreach ($hama_cities as $city) {
            City::firstOrCreate(['name' => $city, 'gov_id' => 5]);
        }
        $lattakia_cities = [
            'Latakia',
            'Jableh',
            'Qardaha',
            'Al-Haffa',
            'Slunfeh',
            'Kessab',
            'Rabia',
            'Ain al-Bayda',
            'Qastal Maaf',
            'Bustan al-Basha',
        ];

        foreach ($lattakia_cities as $city) {
            City::firstOrCreate(['name' => $city, 'gov_id' => 6]);
        }
        $tartus_cities = [
            'Tartus',
            'Baniyas',
            'Safita',
            'Al-Duraykish',
            'Sheikh Badr',
            'Mashta al-Helu',
            'Al-Qadmous',
            'Khirbet al-Maazeh',
            'Safita',
            'Arwad',
        ];

        foreach ($tartus_cities as $city) {
            City::firstOrCreate(['name' => $city, 'gov_id' => 7]);
        }
        $daraa_cities = [
            'Daraa',
            'Izraa',
            'Al-Sanamayn',
            'Nawa',
            'Jasim',
            'Dael',
            'Bosra al-Sham',
            'Al-Hirak',
            'Khirbet Ghazaleh',
            'Al-Sheikh Maskin',
        ];

        foreach ($daraa_cities as $city) {
            City::firstOrCreate(['name' => $city, 'gov_id' => 8]);
        }
        $suwayda_cities = [
            'As-Suwayda',
            'Salkhad',
            'Shahba',
            'Al-Qurayya',
            'Atil',
            'Qanawat',
            'Shaqqa',
            'Al-Kafr',
            'Al-Mazraa',
            'Malhah',
        ];

        foreach ($suwayda_cities as $city) {
            City::firstOrCreate(['name' => $city, 'gov_id' => 9]);
        }
        $quneitra_cities = [
            'Quneitra',
            'Khan Arnabah',
            'Masada',
            'Al-Khashniyah',
            'Jaba',
            'Hader',
            'Naba al-Sakhr',
            'Mumtana',
            'Beer Ajam',
            'Breika',
        ];

        foreach ($quneitra_cities as $city) {
            City::firstOrCreate(['name' => $city, 'gov_id' => 10]);
        }
        $hasakah_cities = [
            'Hasakah',
            'Qamishli',
            'Ras al-Ayn',
            'Al-Malikiyah',
            'Al-Darbasiyah',
            'Amuda',
            'Tell Tamer',
            'Al-Jawadiyah',
            'Al-Yarubiyah',
            'Al-Shaddadi',
        ];

        foreach ($hasakah_cities as $city) {
            City::firstOrCreate(['name' => $city, 'gov_id' => 11]);
        }
        $deir_ez_zor_cities = [
            'Deir ez-Zor',
            'Al-Bukamal',
            'Al-Mayadin',
            'Al-Quriyah',
            'Hajin',
            'Al-Asharah',
            'Al-Busayrah',
            'Al-Suwar',
            'Khasham',
            'Muhassan',
        ];

        foreach ($deir_ez_zor_cities as $city) {
            City::firstOrCreate(['name' => $city, 'gov_id' => 12]);
        }
        $idlib_cities = [
            'Idlib',
            'Ariha',
            'Jisr al-Shughur',
            'Maarrat al-Numan',
            'Harem',
            'Saraqib',
            'Khan Shaykhun',
            'Kafr Nabl',
            'Salqin',
            'Armanaz',
        ];

        foreach ($idlib_cities as $city) {
            City::firstOrCreate(['name' => $city, 'gov_id' => 13]);
        }
        $raqqa_cities = [
            'Raqqa',
            'Tell Abyad',
            'Tabqa',
            'Ain Issa',
            'Mansoura',
            'Karamah',
            'Sabkha',
            'Maadan',
            'Jurniyah',
            'Suluk',
        ];

        foreach ($raqqa_cities as $city) {
            City::firstOrCreate(['name' => $city, 'gov_id' => 14]);
        }



        // $damascus_cities = [
        //     'Qaboon',
        //     'Jaramana',
        //     'Al-mazza',
        //     'Bagdad.S',
        //     'mhajreen',
        //     'Midan',
        // ];
        // foreach ($damascus_cities as $city) {
        //     City::create(['name' => $city, 'gov_id' => 1]);
        // }

        // $homs_cities = [
        //     'bayada',
        //     'hamra',
        //     'gotaa',
        //     'dablan',
        //     'hadara',
        // ];

        // foreach ($homs_cities as $city) {
        //     City::create(['name' => $city, 'gov_id' => 2]);
        // }

        // $aleepo_cities = [
        //     'elkalase',
        //     'hamadania',
        //     'mansoura',
        //     'jarablous',

        // ];
        // foreach ($aleepo_cities as $city) {
        //     City::create(['name' => $city, 'gov_id' => 3]);
        // }

        // $lazakia_cities = [
        //     'jableh',
        //     'sahel',
        //     'eltakhreme',
        //     'om altanafes el foqa',
        //     'om altanafes el tahta'
        // ];
        // foreach ($lazakia_cities as $city) {
        //     City::create(['name' => $city, 'gov_id' => 4]);
        // }
    }
}
