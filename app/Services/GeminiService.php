<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\matches;

class GeminiService
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
        $this->model = config('services.gemini.model');
    }

    private function cleanAndDecodeJson(string $rawText)
    {
        $startPos = strpos($rawText, '{');
        $endPos = strrpos($rawText, '}');

        if ($startPos === false || $endPos === false) {
            $startPos = strpos($rawText, '[');
            $endPos = strrpos($rawText, ']');
        }

        if ($startPos !== false && $endPos !== false) {
            $cleanText = substr($rawText, $startPos, $endPos - $startPos + 1);
            $cleanText = preg_replace('/[\x00-\x1F\x7F]/u', '', $cleanText);

            $data = json_decode($cleanText, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $data;
            }
        }

        $cleanText = str_replace(['```json', '```', 'json'], '', $rawText);
        $data = json_decode($cleanText, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON Decode Error: ' . json_last_error_msg() . ' | Raw: ' . $rawText);
            return null;
        }

        return $data;
    }

    private function askGemini(string $prompt, bool $isJsonMode = false)
    {
        // 1. Pastikan URL menggunakan v1beta (lebih pintar) atau v1 (lebih stabil)
        // Kita coba v1beta lagi karena biasanya lebih pintar bikin JSON,
        // tapi TANPA parameter response_mime_type yang bikin error tadi.
        $url = "{$this->baseUrl}{$this->model}:generateContent?key={$this->apiKey}";

        $finalPrompt = $prompt;
        if ($isJsonMode) {
            $finalPrompt .= "\n\nCRITICAL INSTRUCTION: Output MUST be valid JSON only. Do not include 'Here is the JSON' or markdown formatting. Start with { and end with }.";
        }

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withoutVerifying()
                ->timeout(60)
                ->post($url, [
                    'contents' => [
                        ['parts' => [['text' => $finalPrompt]]]
                    ],
                ]);

            if ($response->failed()) {
                echo "\n\n🔥 API ERROR DARI GOOGLE:\n";
                echo "------------------------------------------------\n";
                echo "Status Code: " . $response->status() . "\n";
                echo "Response Body: " . $response->body() . "\n";
                echo "------------------------------------------------\n";
                die(); // Matikan program biar kita bisa baca errornya
            }

            if ($response->successful()) {
                $text = $response->json('candidates.0.content.parts.0.text');

                return $isJsonMode ? $this->cleanAndDecodeJson($text) : $text;
            }

            Log::error('Gemini API Error: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            echo "\n\n SYSTEM ERROR:\n" . $e->getMessage() . "\n";
            return null;
        }
    }

    /**
     * 1. Generate Units berdasarkan Chapter
     */
    public function generateLessonGuide(string $chapterName)
    {
        $prompt = "Kamu adalah guru bahasa Jepang profesional setingkat Native Level. Buatlah sebuah unit materi pelajaran singkat untuk chapter berikut.
        Chapter topik: '$chapterName'.

    ATURAN MUTLAK:
    1. Jangan membuat sub-judul seperti 'Unit 1', 'Unit 2', dll. Fokus HANYA pada topik yang diberikan.
    2. Format output WAJIB Markdown (Gunakan Heading tingkat 2 '##' untuk judul utama, Bold, List, Table).
    3. Langsung berikan isi materi, tanpa kata pengantar apapun seperti 'Berikut adalah materinya', dll.
    4. Berikan tabel hiragana/katakana/kanji yang relevan, cara baca, dan 3 contoh kosakata.
    5. Singkat, padat, jelas (Maksimal 250 kata).";

        return $this->askGemini($prompt, false);
    }

    /**
     * 2. Generate Question Pool (Batch)
     */
    public function generateQuestions(string $topic, int $count = 10, string $difficulty = 'easy')
    {
        $difficultyInstruction = match ($difficulty) {
            'medium' => 'Gunakan kosakata yang sedikit lebih variatif namun tetap level N5.',
            'hard' => 'Gunakan kalimat yang lebih kompleks dan campuran Kanji N5.',
            default => 'Fokus pada pengenalan dasar dan kosakata simpel.',
        };

        $upperDifficulty = strtoupper($difficulty);

        $prompt = "Kamu adalah guru bahasa Jepang profesional setingkat Native Level. Buatlah $count soal kuis untuk topik: '$topic'.

        LEVEL KESULITAN: $upperDifficulty.
        INSTRUKSI KHUSUS: $difficultyInstruction

        ATURAN PENTING:
        1. Gunakan BAHASA INDONESIA untuk instruksi, penjelasan soal, dan feedback.
        2. Gunakan huruf Hiragana, Katakana, dan Kanji (N5) dasar sesuai konteks.
        3. Field 'explanation' WAJIB ADA dan menjelaskan secara edukatif.
        4. Buat variasi dari 3 tipe soal berikut secara acak: 'multiple_choice', 'missing_sentence', dan 'arrange_words'.
        5. Pastikan field 'difficulty' di dalam JSON bernilai '$difficulty' (huruf kecil).

        CONTOH FORMAT JSON YANG DIWAJIBKAN (Berikan variasi tipe soal seperti ini):
        {
            \"questions\": [
                {
                    \"type\": \"multiple_choice\",
                    \"difficulty\": \"$difficulty\",
                    \"content\": {
                        \"question\": \"Apa arti dari kata 'Konnichiwa'?\",
                        \"options\": [\"Selamat Pagi\", \"Selamat Siang\", \"Selamat Malam\", \"Halo\"],
                        \"answer\": \"Selamat Siang\",
                        \"explanation\": \"Konnichiwa digunakan untuk sapaan dari siang hingga sore hari.\"
                    }
                },
                {
                    \"type\": \"missing_sentence\",
                    \"difficulty\": \"$difficulty\",
                    \"content\": {
                        \"question\": \"Lengkapi kalimat berikut: Kore ___ pen desu. (Ini adalah pulpen)\",
                        \"options\": [\"wa\", \"ga\", \"wo\", \"ni\"],
                        \"answer\": \"wa\",
                        \"explanation\": \"Partikel 'wa' digunakan sebagai penanda subjek atau topik kalimat.\"
                    }
                },
                {
                    \"type\": \"arrange_words\",
                    \"difficulty\": \"$difficulty\",
                    \"content\": {
                        \"question\": \"Terjemahkan dan susun kata berikut: 'Saya makan sushi.'\",
                        \"words\": [\"sushi\", \"Watashi\", \"tabemasu\", \"wa\", \"wo\"],
                        \"answer\": \"Watashi wa sushi wo tabemasu\",
                        \"explanation\": \"Pola kalimat bahasa Jepang adalah SOP (Subjek-Objek-Predikat).\"
                    }
                }
            ]
        }

        Return ONLY raw JSON. Start with { and end with }.";

        return $this->askGemini($prompt, true);
    }

    /**
     * 3. Generate Feedback Personal
     */
    public function generateFeedback(array $results)
    {
        $resultsJson = json_encode($results);
        $prompt = "Kamu adalah guru bahasa Jepang profesional setingkat Native Level. Berikan feedback personal untuk setiap soal berikut:
        Hasil: $resultsJson.
        Skip feedback untuk jawaban benar, dan berikan penjelasan singkat untuk jawaban salah dan alasan kenapa jawaban tersebut salah.
        Return JSON: { 'ai_feedback_summary': '...' }";

        return $this->askGemini($prompt);
    }

    public function analyzeHandwriting(string $base64Image, string $targetChar)
    {

        preg_match('#^data:(image/\w+);base64,#i', $base64Image, $matches);
        $mimeType = $matches[1] ?? 'image/png';
        $rawData = preg_replace('#^data:image/\w+;base64,#i', '', $base64Image);

        $prompt = "Kamu adalah guru kaligrafi Jepang (Sensei). Nilai gambar coretan murid ini. Apakah ini huruf Jepang '$targetChar'? "
            . "Berikan 'score' (0-100) berdasarkan kemiripan proporsinya. "
            . "Berikan 'feedback' singkat (1-2 kalimat) dalam bahasa Indonesia, beritahu letak kesalahan goresannya jika ada. "
            . "Output WAJIB berformat JSON murni.";

        $url = "{$this->baseUrl}{$this->model}:generateContent?key={$this->apiKey}";

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withoutVerifying()->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'inlineData' => [
                                    'mimeType' => $mimeType,
                                    'data' => $rawData
                                ]
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json',
                ]
            ]);

            if ($response->successful()) {
                $text = $response->json('candidates.0.content.parts.0.text');
                return $this->cleanAndDecodeJson($text);
            }

            Log::error('Vision API Error: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('Vision Exception: ' . $e->getMessage());
            return null;
        }
    }
}
