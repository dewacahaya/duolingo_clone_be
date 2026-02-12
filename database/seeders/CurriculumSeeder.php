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
                'topic_keyword' => 'Basic Hiragana characters: Vowels (A, I, U, E, O), Ka row, and Sa row. Focus on writing shape and pronunciation.',
                'order_sequence' => 1
            ],
            [
                'name' => 'Hiragana Bagian 2: T-N-H',
                'description' => 'Melanjutkan baris Ta, Na, dan Ha. Perhatikan perubahan bunyi pada Chi, Tsu, dan Fu.',
                'topic_keyword' => 'Hiragana characters: Ta row (Ta, Chi, Tsu, Te, To), Na row, and Ha row. Highlight the irregular sounds like Chi, Tsu, and Fu.',
                'order_sequence' => 2
            ],
            [
                'name' => 'Hiragana Bagian 3: M-Y-R-W',
                'description' => 'Baris Ma, Ya, Ra, Wa, dan huruf N (ん). Hati-hati dengan pelafalan R Jepang!',
                'topic_keyword' => 'Hiragana characters: Ma row, Ya Yu Yo, Ra row, Wa Wo, and N. Focus on Japanese R pronunciation.',
                'order_sequence' => 3
            ],
            [
                'name' => 'Hiragana Spesial: Dakuten (Tanda Kutip)',
                'description' => 'Mengubah bunyi huruf dengan tanda tenten (") dan maru (o). Contoh: Ka menjadi Ga.',
                'topic_keyword' => 'Japanese Dakuten and Handakuten rules. Changing K to G, S to Z, T to D, H to B and P using Hiragana.',
                'order_sequence' => 4
            ],
            [
                'name' => 'Katakana Bagian 1: Vokal & K-S',
                'description' => 'Mengenal huruf dasar Katakana: A-I-U-E-O, Ka-Ki-Ku-Ke-Ko, dan Sa-Shi-Su-Se-So.',
                'topic_keyword' => 'Katakana characters: Vowels, Ka row, Sa row. Focus on shape difference from Hiragana.',
                'order_sequence' => 5
            ],
            [
                'name' => 'Katakana Bagian 2: T-N-H',
                'description' => 'Melanjutkan baris Ta, Na, dan Ha dalam Katakana.',
                'topic_keyword' => 'Katakana characters: Ta row, Na row, Ha row. Highlight Chi, Tsu, and Fu differences.',
                'order_sequence' => 6
            ],
            [
                'name' => 'Katakana Bagian 3: M-Y-R-W-N',
                'description' => 'Baris Ma, Ya, Ra, Wa, dan N dalam Katakana.',
                'topic_keyword' => 'Katakana characters: Ma row, Ya Yu Yo, Ra row, Wa Wo, N. Focus on reading speed.',
                'order_sequence' => 7
            ],
            [
                'name' => 'Katakana Dakuten & Handakuten',
                'description' => 'Mengubah bunyi huruf dengan tanda tenten (") dan maru (o). Contoh: Ka menjadi Ga.',
                'topic_keyword' => 'Japanese Dakuten and Handakuten rules. Changing K to G, S to Z, T to D, H to B and P using Hiragana.',
                'order_sequence' => 8
            ],
            [
                'name' => 'Katakana Kombinasi & Loanwords',
                'description' => 'Gabungan kecil seperti キャ, シュ, チョ dan membaca kata asing.',
                'topic_keyword' => 'Katakana combinations (Kya, Shu, Cho) and loanwords like Koohii, Pan, Terebi.',
                'order_sequence' => 9
            ],
            [
                'name' => 'Basic Kanji N5',
                'description' => 'Belajar huruf kanji dasar N5.',
                'topic_keyword' => 'Basic Kanji N5, like number kanji (一, 二, etc.) and basic kanji (日, 月, etc.)',
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
                'topic_keyword' => 'Japanese Greetings (Aisatsu): Ohayou, Konnichiwa, Konbanwa, Arigatou, Sumimasen. Focus on appropriate timing and politeness levels.',
                'order_sequence' => 1
            ],
            [
                'name' => 'Jikoshoukai (Perkenalan Diri)',
                'description' => 'Memperkenalkan nama, asal negara, dan status (mahasiswa/karyawan).',
                'topic_keyword' => 'Self Introduction (Jikoshoukai) in Japanese. Grammar pattern: [A] wa [B] desu. Topics: Name, Nationality (Indoneshia-jin), Occupation.',
                'order_sequence' => 2
            ],
            [
                'name' => 'Angka & Uang',
                'description' => 'Berhitung 1-100 dan menanyakan harga barang saat belanja.',
                'topic_keyword' => 'Japanese Numbers 1-100 and asking price (Ikura desu ka). Yen currency context.',
                'order_sequence' => 3
            ],
            [
                'name' => 'Kore, Sore, Are (Benda)',
                'description' => 'Menunjuk benda di sekitar: Ini, Itu, dan Yang di sana.',
                'topic_keyword' => 'Demonstratives: Kore, Sore, Are, Dore. Asking "What is this?" (Kore wa nan desu ka). Vocabulary: Classroom/Daily objects.',
                'order_sequence' => 4
            ],
            [
                'name' => 'Memesan Makanan',
                'description' => 'Survival di restoran: Memesan menu dan bilang enak (Oishii).',
                'topic_keyword' => 'Ordering food in a restaurant. Phrases: Onegaishimasu, Kore wo kudasai. Taste adjectives: Oishii, Karai, Amai.',
                'order_sequence' => 5
            ],
            [
                'name' => 'Waktu & Jadwal',
                'description' => 'Menyebutkan jam, hari, dan membuat janji sederhana.',
                'topic_keyword' => 'Telling time in Japanese. Nanji, Getsuyoubi-Sunday, simple schedule conversation.',
                'order_sequence' => 6
            ],
            [
                'name' => 'Lokasi & Arah',
                'description' => 'Menanyakan lokasi dan arah jalan.',
                'topic_keyword' => 'Asking location: Doko desu ka, Koko/Soko/Asoko. Directions: Migi, Hidari, Massugu.',
                'order_sequence' => 7
            ],
            [
                'name' => 'Keluarga & Orang',
                'description' => 'Menyebutkan anggota keluarga dan hubungan sosial.',
                'topic_keyword' => 'Family vocabulary: Chichi, Haha, Ani, Imouto. Talking about people politely.',
                'order_sequence' => 8
            ],
            [
                'name' => 'Aktivitas Sehari-hari',
                'description' => 'Menceritakan kegiatan rutin harian.',
                'topic_keyword' => 'Daily routine verbs: Okimasu, Tabemasu, Ikimasu, Nemasu. Basic verb conjugation (Masu form).',
                'order_sequence' => 9
            ],
            [
                'name' => 'Suka & Hobi',
                'description' => 'Mengatakan apa yang disukai dan hobi pribadi.',
                'topic_keyword' => 'Expressing likes: Suki desu, Kirai desu. Talking about hobbies: Eiga, Ongaku, Supootsu.',
                'order_sequence' => 10
            ],

        ];

        foreach ($unitsIrodori as $unit) {
            Unit::create(array_merge($unit, ['chapter_id' => $irodori->id]));
        }
    }
}
