<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BalitaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('balitas')->insert(
            [
                [
                    "nama" => "Fitri Anggraini",
                    "tgl_lahir" => "2022-10-05",
                    "nik" => "2110051001990001",
                    "jenis_kelamin" => "pr",
                    "nama_ibu" => "Siti Rahayu",
                    "nik_ibu" => "4510202001990002",
                    "nama_ayah" => "Rudi Prabowo",
                    "nik_ayah" => "3504302001880003",
                    "no_kk" => "1234567801",
                    "kelurahan" => "DEGAYU",
                    "kecamatan" => "PEKALONGAN UTARA",
                    "posyandu" => 67
                ],
                [
                    "nama" => "Rizky Fadillah",
                    "tgl_lahir" => "2022-08-15",
                    "nik" => "2108151002990004",
                    "jenis_kelamin" => "lk",
                    "nama_ibu" => "Dewi Lestari",
                    "nik_ibu" => "4510202001990005",
                    "nama_ayah" => "Budi Santoso",
                    "nik_ayah" => "3504302001880006",
                    "no_kk" => "1234567802",
                    "kelurahan" => "DEGAYU",
                    "kecamatan" => "PEKALONGAN UTARA",
                    "posyandu" => 67
                ],
                [
                    "nama" => "Nur Adisantoso",
                    "tgl_lahir" => "2023-06-22",
                    "nik" => "2106221003990007",
                    "jenis_kelamin" => "lk",
                    "nama_ibu" => "Lina Amelia",
                    "nik_ibu" => "4510202001990008",
                    "nama_ayah" => "Yudi Pratama",
                    "nik_ayah" => "3504302001880009",
                    "no_kk" => "1234567803",
                    "kelurahan" => "KRAPYAK",
                    "kecamatan" => "PEKALONGAN UTARA",
                    "posyandu" => 45
                ],
                [
                    "nama" => "Rafi Pratama",
                    "tgl_lahir" => "2022-05-11",
                    "nik" => "2105111004990010",
                    "jenis_kelamin" => "lk",
                    "nama_ibu" => "Nina Fitriana",
                    "nik_ibu" => "4510202001990011",
                    "nama_ayah" => "Rian Nugroho",
                    "nik_ayah" => "3504302001880012",
                    "no_kk" => "1234567804",
                    "kelurahan" => "KRAPYAK",
                    "kecamatan" => "PEKALONGAN UTARA",
                    "posyandu" => 45
                ],
                [
                    "nama" => "Revina Cahya",
                    "tgl_lahir" => "2023-03-27",
                    "nik" => "2103271005990013",
                    "jenis_kelamin" => "pr",
                    "nama_ibu" => "Eva Sari",
                    "nik_ibu" => "4510202001990014",
                    "nama_ayah" => "Fauzan Akbar",
                    "nik_ayah" => "3504302001880015",
                    "no_kk" => "1234567805",
                    "kelurahan" => "KANDANG PANJANG",
                    "kecamatan" => "PEKALONGAN UTARA",
                    "posyandu" => 29
                ],
                [
                    "nama" => "Faisal Rahman",
                    "tgl_lahir" => "2022-11-18",
                    "nik" => "2011181006990016",
                    "jenis_kelamin" => "lk",
                    "nama_ibu" => "Rina Septiani",
                    "nik_ibu" => "4510202001990017",
                    "nama_ayah" => "Hendra Wijaya",
                    "nik_ayah" => "3504302001880018",
                    "no_kk" => "1234567806",
                    "kelurahan" => "PANJANG WETAN",
                    "kecamatan" => "PEKALONGAN UTARA",
                    "posyandu" => 1
                ],
                [
                    "nama" => "Asih Setiani",
                    "tgl_lahir" => "2023-01-09",
                    "nik" => "2009091007990019",
                    "jenis_kelamin" => "pr",
                    "nama_ibu" => "Linda Indah",
                    "nik_ibu" => "4510202001990020",
                    "nama_ayah" => "Iqbal Maulana",
                    "nik_ayah" => "3504302001880021",
                    "no_kk" => "1234567807",
                    "kelurahan" => "PANJANG BARU",
                    "kecamatan" => "PEKALONGAN UTARA",
                    "posyandu" => 16
                ],
                [
                    "nama" => "Ilham Pratama",
                    "tgl_lahir" => "2022-07-14",
                    "nik" => "2007141008990022",
                    "jenis_kelamin" => "lk",
                    "nama_ibu" => "Fitri Handayani",
                    "nik_ibu" => "4510202001990023",
                    "nama_ayah" => "Surya Wijaya",
                    "nik_ayah" => "3504302001880024",
                    "no_kk" => "1234567808",
                    "kelurahan" => "BANDENGAN",
                    "kecamatan" => "PEKALONGAN UTARA",
                    "posyandu" => 94
                ],
                [
                    "nama" => "Yoga Ahsan",
                    "tgl_lahir" => "2023-04-30",
                    "nik" => "2004301009990025",
                    "jenis_kelamin" => "lk",
                    "nama_ibu" => "Diana Sari",
                    "nik_ibu" => "4510202001990026",
                    "nama_ayah" => "Rahman Hakim",
                    "nik_ayah" => "3504302001880027",
                    "no_kk" => "1234567809",
                    "kelurahan" => "PADUKUHAN KRATON",
                    "kecamatan" => "PEKALONGAN UTARA",
                    "posyandu" => 77
                ],
                [
                    "nama" => "Ridwan Abdullah",
                    "tgl_lahir" => "2023-02-19",
                    "nik" => "2002191010990028",
                    "jenis_kelamin" => "lk",
                    "nama_ibu" => "Astuti Lestari",
                    "nik_ibu" => "4510202001990029",
                    "nama_ayah" => "Haris Suryadi",
                    "nik_ayah" => "3504302001880030",
                    "no_kk" => "1234567810",
                    "kelurahan" => "KRAPYAK",
                    "kecamatan" => "PEKALONGAN UTARA",
                    "posyandu" => 45
                ]
            ]
        );
    }
}
