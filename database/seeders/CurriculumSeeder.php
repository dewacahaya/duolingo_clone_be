<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurriculumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ==========================================
        // CHAPTER 1: PONDASI (AKSARA & BUNYI)
        // ==========================================
        $aksara = Chapter::create([
            'name' => 'Gerbang Awal: Huruf Jepang',
            'description' => 'Kuasai Hiragana dan Katakana sebagai kunci utama membaca tulisan Jepang.',
            'order_sequence' => 1
        ]);

        $unitsAksara = [
            [
                'name' => 'Hiragana Bagian 1: Vokal & K-S',
                'description' => 'Mengenal 15 huruf pertama: A-I-U-E-O, Ka-Ki-Ku-Ke-Ko, dan Sa-Shi-Su-Se-So.',
                'topic_keyword' => 'Introduction to Japanese Hiragana. Focus on Vowels (A, I, U, E, O), K-column (Ka, Ki, Ku, Ke, Ko), and S-column (Sa, Shi, Su, Se, So). Writing stroke order and pronunciation basics.',
                'order_sequence' => 1
            ],
            [
                'name' => 'Hiragana Bagian 2: T-N-H',
                'description' => 'Melanjutkan baris Ta, Na, dan Ha. Perhatikan perubahan bunyi pada Chi, Tsu, dan Fu.',
                'topic_keyword' => 'Japanese Hiragana characters: T-column (Ta, Chi, Tsu, Te, To), N-column (Na, Ni, Nu, Ne, No), and H-column (Ha, Hi, Fu, He, Ho). Pay attention to irregular sounds: Chi, Tsu, and Fu.',
                'order_sequence' => 2
            ],
            [
                'name' => 'Hiragana Bagian 3: M-Y-R-W-N',
                'description' => 'Baris Ma, Ya, Ra, Wa, dan huruf N (ん). Hati-hati dengan pelafalan R Jepang!',
                'topic_keyword' => 'Japanese Hiragana characters: M-column (Ma, Mi, Mu, Me, Mo), Y-column (Ya, Yu, Yo), R-column (Ra, Ri, Ru, Re, Ro), W-column (Wa, Wo), and single N. Focus on the Japanese "R" sound.',
                'order_sequence' => 3
            ],
            [
                'name' => 'Hiragana Spesial: Tanda Baca & Bunyi Panjang',
                'description' => 'Tanda kutip (Dakuten), Lingkaran (Handakuten), Tsu Kecil (Jeda), dan Bunyi Panjang.',
                'topic_keyword' => 'Advanced Hiragana rules: 1. Dakuten (Ga, Za, Da). 2. Handakuten (Pa). 3. Sokuon (Small Tsu/Double Consonants like "Kitte"). 4. Chouon (Long Vowels like "Okaa-san", "Onee-san").',
                'order_sequence' => 4
            ],
            [
                'name' => 'Katakana Bagian 1: Vokal & K-S',
                'description' => 'Mengenal huruf dasar Katakana: A-I-U-E-O, Ka-Ki-Ku-Ke-Ko, dan Sa-Shi-Su-Se-So.',
                'topic_keyword' => 'Introduction to Japanese Katakana characters. Vowels (A, I, U, E, O), K-column, and S-column. Compare shapes with Hiragana counterpart.',
                'order_sequence' => 5
            ],
            [
                'name' => 'Katakana Bagian 2: T-N-H',
                'description' => 'Melanjutkan baris Ta, Na, dan Ha dalam Katakana.',
                'topic_keyword' => 'Japanese Katakana characters: T-column (Ta, Chi, Tsu, Te, To), N-column, and H-column. Identify sharp angular strokes of Katakana.',
                'order_sequence' => 6
            ],
            [
                'name' => 'Katakana Bagian 3: M-Y-R-W-N',
                'description' => 'Baris Ma, Ya, Ra, Wa, dan N dalam Katakana.',
                'topic_keyword' => 'Japanese Katakana characters: M-column, Y-column (Ya, Yu, Yo), R-column, W-column (Wa, Wo), and N. Reading practice.',
                'order_sequence' => 7
            ],
            [
                'name' => 'Katakana Dakuten & Handakuten',
                'description' => 'Mengubah bunyi huruf Katakana dengan tanda tenten (") dan maru (o).',
                'topic_keyword' => 'Katakana modified sounds: Dakuten (Ga, Gi, Gu... Za, Ji, Zu... Da, Ji, Zu...) and Handakuten (Pa, Pi, Pu, Pe, Po).',
                'order_sequence' => 8
            ],
            [
                'name' => 'Katakana Kombinasi & Bunyi Panjang',
                'description' => 'Gabungan kecil (Kya, Nyu) dan tanda strip panjang (ー) pada kata serapan.',
                'topic_keyword' => 'Advanced Katakana rules: 1. Yoon (Combination sounds like Kya, Shu, Cho). 2. Choonpu (Long Vowel Dash "ー"). 3. Reading Loanwords (Gairaigo) like "Koohii" (Coffee), "Sakka" (Soccer).',
                'order_sequence' => 9
            ],
            [
                'name' => 'Basic Kanji N5: Angka & Alam',
                'description' => 'Belajar huruf kanji dasar N5 (Angka dan Elemen Alam).',
                'topic_keyword' => 'JLPT N5 Basic Kanji. Focus ONLY on: Numbers (一, 二, 三, 四, 五, 六, 七, 八, 九, 十, 百, 千, 万) and Nature Elements (日, 月, 火, 水, 木, 金, 土, 山, 川). Onyomi and Kunyomi readings.',
                'order_sequence' => 10
            ]
        ];

        foreach ($unitsAksara as $unit) {
            Unit::create(array_merge($unit, ['chapter_id' => $aksara->id]));
        }

        // ==========================================
        // CHAPTER 2: SURVIVAL DI JEPANG (IRODORI A1)
        // ==========================================
        $irodori = Chapter::create([
            'name' => 'Misi 1: Salam & Perkenalan',
            'description' => 'Mulai berbicara! Pelajari frasa vital untuk berinteraksi dengan orang Jepang.',
            'order_sequence' => 2
        ]);

        $unitsIrodori = [
            [
                'name' => 'Aisatsu (Salam Harian)',
                'description' => 'Mengucapkan selamat pagi, siang, malam, dan terima kasih dengan sopan.',
                'topic_keyword' => 'Basic Japanese Greetings (Aisatsu). Vocabulary: Ohayou gozaimasu, Konnichiwa, Konbanwa, Oyasuminasai, Arigatou gozaimasu, Sumimasen, Sayounara. Context: Politeness levels.',
                'order_sequence' => 1
            ],
            [
                'name' => 'Jikoshoukai (Perkenalan Diri)',
                'description' => 'Memperkenalkan nama, asal negara, dan status (mahasiswa/karyawan).',
                'topic_keyword' => 'Self Introduction (Jikoshoukai) in Japanese. Grammar: "Watashi wa [Name] desu", "Watashi wa [Country]-jin desu". Vocabulary: Gakusei (Student), Kaishain (Employee), Indoneshia. Context: Meeting new people.',
                'order_sequence' => 2
            ],
            [
                'name' => 'Angka & Belanja',
                'description' => 'Berhitung 1-100 dan menanyakan harga barang saat belanja.',
                'topic_keyword' => 'Japanese Numbers 1-100 (Ichi, Ni, San...). Shopping phrase: "Kore wa ikura desu ka?" (How much is this?). Vocabulary: En (Yen), Hyaku (100), Sen (1000).',
                'order_sequence' => 3
            ],
            [
                'name' => 'Kore, Sore, Are (Menunjuk Benda)',
                'description' => 'Menunjuk benda di sekitar: Ini, Itu, dan Yang di sana.',
                'topic_keyword' => 'Japanese Demonstratives: Kore (This), Sore (That), Are (That over there). Grammar: "Kore wa nan desu ka?", "Sore wa [Item] desu". Vocabulary: Hon (Book), Pen, Kaban (Bag), Tokei (Watch).',
                'order_sequence' => 4
            ],
            [
                'name' => 'Memesan Makanan (Restoran)',
                'description' => 'Survival di restoran: Memesan menu dan bilang enak (Oishii).',
                'topic_keyword' => 'Ordering food in a Japanese restaurant. Phrases: "Sumimasen", "[Menu] wo kudasai". Adjectives: Oishii (Delicious), Karai (Spicy), Amai (Sweet). Vocabulary: Mizu (Water), Gohan (Rice).',
                'order_sequence' => 5
            ],
            [
                'name' => 'Waktu & Jam',
                'description' => 'Menyebutkan jam, hari, dan membuat janji sederhana.',
                'topic_keyword' => 'Telling time in Japanese. Grammar: "Ima nan-ji desu ka?". Vocabulary: Hours (1-12 ji), Minutes (fun/pun), Days of Week (Getsuyoubi to Nichiyoubi).',
                'order_sequence' => 6
            ],
            [
                'name' => 'Lokasi & Arah Jalan',
                'description' => 'Menanyakan lokasi dan arah jalan.',
                'topic_keyword' => 'Asking for directions. Grammar: "[Place] wa doko desu ka?". Vocabulary: Koko/Soko/Asoko, Migi (Right), Hidari (Left), Massugu (Straight), Toire (Toilet), Eki (Station).',
                'order_sequence' => 7
            ],
            [
                'name' => 'Keluarga & Orang',
                'description' => 'Menyebutkan anggota keluarga dan hubungan sosial.',
                'topic_keyword' => 'Talking about Family (Kazoku). Distinguish own family (Chichi, Haha, Ani) vs others (Otousan, Okaasan, Oniisan). Counting people (Hitori, Futari, Sannin).',
                'order_sequence' => 8
            ],
            [
                'name' => 'Aktivitas Sehari-hari (Verba)',
                'description' => 'Menceritakan kegiatan rutin harian.',
                'topic_keyword' => 'Daily Routine Verbs (Masu-form). Vocabulary: Okimasu (Wake up), Nemasu (Sleep), Tabemasu (Eat), Ikimasu (Go), Benkyoushimasu (Study). Grammar: Time + ni + Verb.',
                'order_sequence' => 9
            ],
            [
                'name' => 'Suka & Hobi',
                'description' => 'Mengatakan apa yang disukai dan hobi pribadi.',
                'topic_keyword' => 'Expressing Likes and Hobbies. Grammar: "[Noun] ga suki desu". Vocabulary: Supootsu (Sports), Ongaku (Music), Eiga (Movies), Anime, Geemu (Game). Negative: "Suki ja arimasen".',
                'order_sequence' => 10
            ],

        ];

        foreach ($unitsIrodori as $unit) {
            Unit::create(array_merge($unit, ['chapter_id' => $irodori->id]));
        }
    }
}
