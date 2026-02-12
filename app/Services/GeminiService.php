<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

    private function cleanAndDecodeJSON(string $rawText)
    {
        $cleanText = str_replace(['```json', '```', 'json'], '', $rawText);
        $cleanText = trim($cleanText);

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
            $finalPrompt .= "\n\nIMPORTANT: Return ONLY raw JSON. No Markdown block. No ```json wrapper.";
        }

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withoutVerifying()->post($url, [
                'contents' => [
                    ['parts' => [['text' => $finalPrompt]]]
                ],
            ]);

            if ($response->successful()) {
                $text = $response->json('candidates.0.content.parts.0.text');

                return $isJsonMode ? $this->cleanAndDecodeJson($text) : $text;
            }

            Log::error('Gemini API Error: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('Gemini Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * 1. Generate Units berdasarkan Chapter
     */
    public function generateLessonGuide(string $chapterName)
    {
        $prompt = "Kamu adalah guru bahasa Jepang profesional setingkat Native Level. Buatlah 5 unit materi pelajaran singkat untuk chapter berikut.
        Chapter topik: '$chapterName'.

    ATURAN PENTING:
    1. Gunakan BAHASA INDONESIA untuk penjelasan materi unit. Gunakan bahasa Indonesia yang ringan dan menarik namun tetap jelas.
    2. Gunakan huruf latin untuk penjelasan dan bila perlu gunakan Hiragana/Katakana/Kanji (N5) dasar sesuai konteks penjelasan materi.
    3. Langsung berikan isi materi tanpa basa-basi pembuka.
    4. Gunakan contoh kalimat atau frasa penting dalam materi unit.
    5. Berikan penjelasan yang sesuai konteks chapter namun jangan terlalu panjang (maks: 300 kata).
    6. Output wajib markdown (Heading, Bold, List, Table)";

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

        $prompt = "Kamu adalah guru bahasa Jepang profesional setingkat Native Level. Buatlah $count soal kuis untuk topik: '$topic'.

        LEVEL KESULITAN: $difficulty (Upper Case).
        INSTRUKSI KHUSUS: $difficultyInstruction

        ATURAN PENTING:
        1. Gunakan BAHASA INDONESIA untuk instruksi, penjelasan soal, dan feedback.
        2. Gunakan huruf Hiragana, Katakana, dan Kanji (N5) dasar sesuai konteks.
        3. Field 'answer' HARUS sama persis dengan salah satu string di dalam 'options'.
        4. Field 'explanation' WAJIB ADA dan menjelaskan kenapa jawaban tersebut benar secara edukatif.
        5. Tipe soal berupa 'multiple_choice', 'matching', 'missing_sentence'.
        6. Pastikan field 'difficulty' di dalam JSON bernilai '$difficulty'.

        Return ONLY raw JSON with this exact structure:
        {
            \"questions\": [
                {
                    \"type\": \"tipe_soal\",
                    \"difficulty\": \"$difficulty\",
                    \"content\": {
                        \"question\": \"Pertanyaan dalam bahasa Jepang/Indonesia?\",
                        \"options\": [\"Pilihan A\", \"Pilihan B\", \"Pilihan C\", \"Pilihan D\"],
                        \"answer\": \"Pilihan B\",
                        \"explanation\": \"Penjelasan detail kenapa Pilihan B benar dan kenapa yang lain salah.\"
                    }
                }
            ]
        }";

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
        $rawData = preg_replace('#^data:image/\w+;base64,#i', '', $base64Image);

        $prompt = "Nilai tulisan tangan ini. Apakah ini huruf Jepang '$targetChar'?
        Berikan skor (0-100) dan saran perbaikan singkat.
        Output WAJIB JSON: {\"score\": 80, \"feedback\": \"...\"}";

        $url = "{$this->baseUrl}{$this->model}:generateContent?key={$this->apiKey}";

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withoutVerifying()->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'inline_data' => [
                                    'mime_type' => 'image/png',
                                    'data' => $rawData
                                ]
                            ]
                        ]
                    ]
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
