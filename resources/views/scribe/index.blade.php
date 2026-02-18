<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Laravel API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.7.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.7.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-endpoints" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                                    <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-dev-token">
                                <a href="#endpoints-GETapi-dev-token">GET api/dev/token</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-latihan-menulis-ai-vision" class="tocify-header">
                <li class="tocify-item level-1" data-unique="latihan-menulis-ai-vision">
                    <a href="#latihan-menulis-ai-vision">✍️ Latihan Menulis (AI Vision)</a>
                </li>
                                    <ul id="tocify-subheader-latihan-menulis-ai-vision" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="latihan-menulis-ai-vision-GETapi-characters">
                                <a href="#latihan-menulis-ai-vision-GETapi-characters">Daftar Karakter & Skor Penguasaan
* Mengambil seluruh daftar karakter Hiragana dan Katakana yang tersedia di sistem, lengkap dengan persentase `mastery_level` (skor tertinggi) yang pernah diraih user.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="latihan-menulis-ai-vision-GETapi-characters--id-">
                                <a href="#latihan-menulis-ai-vision-GETapi-characters--id-">Detail Karakter Tunggal
* @authenticated</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="latihan-menulis-ai-vision-POSTapi-writing-analyze">
                                <a href="#latihan-menulis-ai-vision-POSTapi-writing-analyze">Analisis Coretan Canvas (Submit AI)
* Endpoint krusial untuk fitur menulis. Frontend harus mengkonversi hasil goresan HTML5 Canvas menjadi format Base64 PNG/JPEG dan mengirimkannya ke endpoint ini. AI akan menilai kemiripannya dengan huruf asli.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="latihan-menulis-ai-vision-POSTapi-writing-progress">
                                <a href="#latihan-menulis-ai-vision-POSTapi-writing-progress">Simpan Skor Menulis
* Menyimpan hasil kemiripan tertinggi (skor) ke database setelah user berhasil berlatih.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-engine-kuis-latihan" class="tocify-header">
                <li class="tocify-item level-1" data-unique="engine-kuis-latihan">
                    <a href="#engine-kuis-latihan">🎮 Engine Kuis & Latihan</a>
                </li>
                                    <ul id="tocify-subheader-engine-kuis-latihan" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="engine-kuis-latihan-GETapi-quiz-start--unit_id-">
                                <a href="#engine-kuis-latihan-GETapi-quiz-start--unit_id-">Start Kuis (Ambil Soal)
* Endpoint ini digunakan saat user menekan tombol "Mulai Belajar" di suatu Unit.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="engine-kuis-latihan-POSTapi-quiz-submit">
                                <a href="#engine-kuis-latihan-POSTapi-quiz-submit">Submit Jawaban Kuis
* Endpoint pamungkas untuk mengirim semua jawaban user.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-profil-papan-peringkat" class="tocify-header">
                <li class="tocify-item level-1" data-unique="profil-papan-peringkat">
                    <a href="#profil-papan-peringkat">👤 Profil & Papan Peringkat</a>
                </li>
                                    <ul id="tocify-subheader-profil-papan-peringkat" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="profil-papan-peringkat-GETapi-me">
                                <a href="#profil-papan-peringkat-GETapi-me">Get Profil Saya (Me)
* Mengambil informasi lengkap pengguna yang sedang login.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="profil-papan-peringkat-POSTapi-me-update">
                                <a href="#profil-papan-peringkat-POSTapi-me-update">Update Profil
* Mengubah nama atau foto profil (Avatar) pengguna.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="profil-papan-peringkat-GETapi-leaderboard">
                                <a href="#profil-papan-peringkat-GETapi-leaderboard">Papan Peringkat (Leaderboard)
* Menampilkan daftar 50 besar pengguna dengan skor XP (Experience Points) tertinggi.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-autentikasi-akun" class="tocify-header">
                <li class="tocify-item level-1" data-unique="autentikasi-akun">
                    <a href="#autentikasi-akun">🔐 Autentikasi & Akun</a>
                </li>
                                    <ul id="tocify-subheader-autentikasi-akun" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="autentikasi-akun-POSTapi-auth-register">
                                <a href="#autentikasi-akun-POSTapi-auth-register">Register Manual
* Mendaftarkan pengguna baru dengan email dan password. Otomatis memberikan 5 Energy awal dan token akses.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="autentikasi-akun-POSTapi-auth-login">
                                <a href="#autentikasi-akun-POSTapi-auth-login">Login Manual
* Mendapatkan token akses (Bearer Token) untuk user yang sudah terdaftar.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="autentikasi-akun-GETapi-auth-google-redirect">
                                <a href="#autentikasi-akun-GETapi-auth-google-redirect">Google Login: Redirect
* API ini mengembalikan URL otorisasi Google. Frontend harus me-redirect user ke URL ini agar mereka bisa login menggunakan akun Google.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="autentikasi-akun-POSTapi-logout">
                                <a href="#autentikasi-akun-POSTapi-logout">Logout
* Menghancurkan token sesi saat ini agar tidak bisa digunakan lagi.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-peta-kurikulum-materi" class="tocify-header">
                <li class="tocify-item level-1" data-unique="peta-kurikulum-materi">
                    <a href="#peta-kurikulum-materi">🗺️ Peta Kurikulum & Materi</a>
                </li>
                                    <ul id="tocify-subheader-peta-kurikulum-materi" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="peta-kurikulum-materi-GETapi-chapters">
                                <a href="#peta-kurikulum-materi-GETapi-chapters">Tampilkan Learning Map (Homepage)
* Mengambil daftar seluruh Chapter dan Unit secara berurutan. API ini otomatis menyisipkan status progress dari user yang sedang login (apakah unit tersebut `locked`, `open`, atau `completed`), serta jumlah bintang yang diraih.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="peta-kurikulum-materi-GETapi-units--id-">
                                <a href="#peta-kurikulum-materi-GETapi-units--id-">Detail Materi Unit (Guide)
* Mengambil detail satu unit beserta teks materinya (`guide_md`). Ini ditampilkan saat user menekan Unit di peta, sebelum mereka menekan tombol "Mulai Kuis".</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: February 18, 2026</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<p>API Documentations for Duolingo Clone + AI</p>
<aside>
    <strong>Base URL</strong>: <code>http://localhost</code>
</aside>
<h1 id="introduction">Introduction</h1>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>This API is not authenticated.</p>

        <h1 id="endpoints">Endpoints</h1>

    

                                <h2 id="endpoints-GETapi-dev-token">GET api/dev/token</h2>

<p>
</p>



<span id="example-requests-GETapi-dev-token">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/dev/token" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/dev/token"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-dev-token">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
vary: Origin
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;token&quot;: &quot;15|Gtbrsz9lNuYAz3gjETbEwhw2uMs9bWv3L3CDBO6q84d27176&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-dev-token" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-dev-token"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-dev-token"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-dev-token" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-dev-token">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-dev-token" data-method="GET"
      data-path="api/dev/token"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-dev-token', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-dev-token"
                    onclick="tryItOut('GETapi-dev-token');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-dev-token"
                    onclick="cancelTryOut('GETapi-dev-token');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-dev-token"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/dev/token</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-dev-token"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-dev-token"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="latihan-menulis-ai-vision">✍️ Latihan Menulis (AI Vision)</h1>

    <p>API untuk sistem latihan menulis huruf Jepang (Drawing Canvas) yang dinilai langsung oleh AI Gemini Vision.</p>

                                <h2 id="latihan-menulis-ai-vision-GETapi-characters">Daftar Karakter &amp; Skor Penguasaan
* Mengambil seluruh daftar karakter Hiragana dan Katakana yang tersedia di sistem, lengkap dengan persentase `mastery_level` (skor tertinggi) yang pernah diraih user.</h2>

<p>
</p>

<ul>
<li>@authenticated</li>
</ul>

<span id="example-requests-GETapi-characters">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/characters" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/characters"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-characters">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;hiragana&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;char&quot;: &quot;あ&quot;,
                &quot;romaji&quot;: &quot;a&quot;,
                &quot;type&quot;: &quot;hiragana&quot;,
                &quot;mastery_level&quot;: 95
            }
        ],
        &quot;katakana&quot;: [
            {
                &quot;id&quot;: 47,
                &quot;char&quot;: &quot;ア&quot;,
                &quot;romaji&quot;: &quot;a&quot;,
                &quot;type&quot;: &quot;katakana&quot;,
                &quot;mastery_level&quot;: 0
            }
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-characters" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-characters"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-characters"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-characters" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-characters">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-characters" data-method="GET"
      data-path="api/characters"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-characters', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-characters"
                    onclick="tryItOut('GETapi-characters');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-characters"
                    onclick="cancelTryOut('GETapi-characters');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-characters"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/characters</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-characters"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-characters"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="latihan-menulis-ai-vision-GETapi-characters--id-">Detail Karakter Tunggal
* @authenticated</h2>

<p>
</p>



<span id="example-requests-GETapi-characters--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/characters/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/characters/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-characters--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;char&quot;: &quot;あ&quot;,
        &quot;romaji&quot;: &quot;a&quot;,
        &quot;type&quot;: &quot;hiragana&quot;,
        &quot;stroke_count&quot;: 3
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-characters--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-characters--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-characters--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-characters--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-characters--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-characters--id-" data-method="GET"
      data-path="api/characters/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-characters--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-characters--id-"
                    onclick="tryItOut('GETapi-characters--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-characters--id-"
                    onclick="cancelTryOut('GETapi-characters--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-characters--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/characters/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-characters--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-characters--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-characters--id-"
               value="1"
               data-component="url">
    <br>
<p>ID Karakter yang ada di database. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="latihan-menulis-ai-vision-POSTapi-writing-analyze">Analisis Coretan Canvas (Submit AI)
* Endpoint krusial untuk fitur menulis. Frontend harus mengkonversi hasil goresan HTML5 Canvas menjadi format Base64 PNG/JPEG dan mengirimkannya ke endpoint ini. AI akan menilai kemiripannya dengan huruf asli.</h2>

<p>
</p>

<ul>
<li>@authenticated</li>
</ul>

<span id="example-requests-POSTapi-writing-analyze">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/writing/analyze" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"image\": \"data:image\\/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=\",
    \"character_id\": 1
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/writing/analyze"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "image": "data:image\/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=",
    "character_id": 1
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-writing-analyze">
            <blockquote>
            <p>Example response (500, AI Gagal Membaca):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Gagal menganalisis gambar. Pastikan gambar jelas.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-writing-analyze" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-writing-analyze"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-writing-analyze"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-writing-analyze" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-writing-analyze">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-writing-analyze" data-method="POST"
      data-path="api/writing/analyze"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-writing-analyze', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-writing-analyze"
                    onclick="tryItOut('POSTapi-writing-analyze');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-writing-analyze"
                    onclick="cancelTryOut('POSTapi-writing-analyze');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-writing-analyze"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/writing/analyze</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-writing-analyze"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-writing-analyze"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>image</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="image"                data-endpoint="POSTapi-writing-analyze"
               value="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
               data-component="body">
    <br>
<p>Gambar hasil canvas dalam format Base64 (Data URI scheme). Example: <code>data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>character_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="character_id"                data-endpoint="POSTapi-writing-analyze"
               value="1"
               data-component="body">
    <br>
<p>ID karakter target yang sedang dipelajari. Example: <code>1</code></p>
        </div>
        </form>

                    <h2 id="latihan-menulis-ai-vision-POSTapi-writing-progress">Simpan Skor Menulis
* Menyimpan hasil kemiripan tertinggi (skor) ke database setelah user berhasil berlatih.</h2>

<p>
</p>

<ul>
<li>@authenticated</li>
</ul>

<span id="example-requests-POSTapi-writing-progress">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/writing/progress" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"character_id\": 1,
    \"score\": 85
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/writing/progress"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "character_id": 1,
    "score": 85
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-writing-progress">
</span>
<span id="execution-results-POSTapi-writing-progress" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-writing-progress"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-writing-progress"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-writing-progress" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-writing-progress">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-writing-progress" data-method="POST"
      data-path="api/writing/progress"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-writing-progress', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-writing-progress"
                    onclick="tryItOut('POSTapi-writing-progress');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-writing-progress"
                    onclick="cancelTryOut('POSTapi-writing-progress');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-writing-progress"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/writing/progress</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-writing-progress"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-writing-progress"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>character_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="character_id"                data-endpoint="POSTapi-writing-progress"
               value="1"
               data-component="body">
    <br>
<p>ID Karakter target. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>score</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="score"                data-endpoint="POSTapi-writing-progress"
               value="85"
               data-component="body">
    <br>
<p>Skor kemiripan yang didapat dari AI (0-100). Example: <code>85</code></p>
        </div>
        </form>

                <h1 id="engine-kuis-latihan">🎮 Engine Kuis & Latihan</h1>

    <p>API untuk memulai kuis, memotong energy, dan menghitung skor kelulusan.
Termasuk di dalamnya adalah sistem Remedial otomatis dan Feedback AI.</p>

                                <h2 id="engine-kuis-latihan-GETapi-quiz-start--unit_id-">Start Kuis (Ambil Soal)
* Endpoint ini digunakan saat user menekan tombol &quot;Mulai Belajar&quot; di suatu Unit.</h2>

<p>
</p>

<p>Memanggil endpoint ini akan otomatis <strong>MEMOTONG 1 ENERGY</strong> milik user.
Jika ada soal yang sebelumnya salah dijawab oleh user (di unit ini), soal tersebut akan dimunculkan kembali (Remedial Mode).</p>
<ul>
<li>@authenticated</li>
</ul>

<span id="example-requests-GETapi-quiz-start--unit_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/quiz/start/5" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/quiz/start/5"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-quiz-start--unit_id-">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
vary: Origin
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Energy Habis):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Nyawa habis! Tunggu regenerasi.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Unit Kosong):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Soal belum tersedia untuk unit ini.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-quiz-start--unit_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-quiz-start--unit_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-quiz-start--unit_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-quiz-start--unit_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-quiz-start--unit_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-quiz-start--unit_id-" data-method="GET"
      data-path="api/quiz/start/{unit_id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-quiz-start--unit_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-quiz-start--unit_id-"
                    onclick="tryItOut('GETapi-quiz-start--unit_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-quiz-start--unit_id-"
                    onclick="cancelTryOut('GETapi-quiz-start--unit_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-quiz-start--unit_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/quiz/start/{unit_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-quiz-start--unit_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-quiz-start--unit_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>unit_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="unit_id"                data-endpoint="GETapi-quiz-start--unit_id-"
               value="5"
               data-component="url">
    <br>
<p>ID dari Unit yang akan dimainkan. Example: <code>5</code></p>
            </div>
                    </form>

                    <h2 id="engine-kuis-latihan-POSTapi-quiz-submit">Submit Jawaban Kuis
* Endpoint pamungkas untuk mengirim semua jawaban user.</h2>

<p>
</p>

<p>Sistem akan menghitung skor, mengupdate XP dan Streak, membuka gembok unit selanjutnya jika lulus (skor &gt;= 70), dan meminta Gemini AI untuk merangkum kesalahan user.</p>
<ul>
<li>@authenticated</li>
</ul>

<span id="example-requests-POSTapi-quiz-submit">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/quiz/submit" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"unit_id\": 5,
    \"answers\": [
        {
            \"question_id\": 50,
            \"selected\": \"\\\"I\\\"\\n* @response {\\n\\\"score\\\": 100,\\n\\\"is_passed\\\": true,\\n\\\"xp_gained\\\": 100,\\n\\\"energy_left\\\": 4,\\n\\\"unlocked_unit_id\\\": 6,\\n\\\"ai_feedback_summary\\\": \\\"Luar biasa! Kamu menjawab semua pertanyaan dengan sempurna. Pertahankan kerjamu!\\\"\\n}\"
        }
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/quiz/submit"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "unit_id": 5,
    "answers": [
        {
            "question_id": 50,
            "selected": "\"I\"\n* @response {\n\"score\": 100,\n\"is_passed\": true,\n\"xp_gained\": 100,\n\"energy_left\": 4,\n\"unlocked_unit_id\": 6,\n\"ai_feedback_summary\": \"Luar biasa! Kamu menjawab semua pertanyaan dengan sempurna. Pertahankan kerjamu!\"\n}"
        }
    ]
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-quiz-submit">
</span>
<span id="execution-results-POSTapi-quiz-submit" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-quiz-submit"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-quiz-submit"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-quiz-submit" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-quiz-submit">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-quiz-submit" data-method="POST"
      data-path="api/quiz/submit"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-quiz-submit', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-quiz-submit"
                    onclick="tryItOut('POSTapi-quiz-submit');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-quiz-submit"
                    onclick="cancelTryOut('POSTapi-quiz-submit');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-quiz-submit"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/quiz/submit</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-quiz-submit"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-quiz-submit"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>unit_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="unit_id"                data-endpoint="POSTapi-quiz-submit"
               value="5"
               data-component="body">
    <br>
<p>ID dari unit kuis yang baru diselesaikan. Example: <code>5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>answers</code></b>&nbsp;&nbsp;
<small>object[]</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Array berisi kumpulan jawaban user.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>question_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="answers.0.question_id"                data-endpoint="POSTapi-quiz-submit"
               value="50"
               data-component="body">
    <br>
<p>ID soal. Example: <code>50</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>selected</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="answers.0.selected"                data-endpoint="POSTapi-quiz-submit"
               value=""I"
* @response {
"score": 100,
"is_passed": true,
"xp_gained": 100,
"energy_left": 4,
"unlocked_unit_id": 6,
"ai_feedback_summary": "Luar biasa! Kamu menjawab semua pertanyaan dengan sempurna. Pertahankan kerjamu!"
}"
               data-component="body">
    <br>
<p>Teks jawaban yang dipilih user. Untuk tipe susun kata, gabungkan katanya. Example: `&quot;I&quot;</p>
<ul>
<li>@response {
&quot;score&quot;: 100,
&quot;is_passed&quot;: true,
&quot;xp_gained&quot;: 100,
&quot;energy_left&quot;: 4,
&quot;unlocked_unit_id&quot;: 6,
&quot;ai_feedback_summary&quot;: &quot;Luar biasa! Kamu menjawab semua pertanyaan dengan sempurna. Pertahankan kerjamu!&quot;
}`</li>
</ul>
                    </div>
                                    </details>
        </div>
        </form>

                <h1 id="profil-papan-peringkat">👤 Profil & Papan Peringkat</h1>

    <p>API untuk mengambil data statistik pribadi pengguna, mengubah profil, serta melihat peringkat global (Leaderboard).</p>

                                <h2 id="profil-papan-peringkat-GETapi-me">Get Profil Saya (Me)
* Mengambil informasi lengkap pengguna yang sedang login.</h2>

<p>
</p>

<p>Setiap kali endpoint ini dipanggil, sistem akan melakukan <em>Lazy Evaluation</em> untuk meregenerasi Energy (nyawa) jika waktu tunggunya sudah terpenuhi.</p>
<ul>
<li>@authenticated</li>
</ul>

<span id="example-requests-GETapi-me">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/me" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/me"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-me">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Budi Santoso&quot;,
        &quot;email&quot;: &quot;budi@mail.com&quot;,
        &quot;avatar&quot;: null,
        &quot;stats&quot;: {
            &quot;xp&quot;: 1250,
            &quot;gems&quot;: 0,
            &quot;streak&quot;: 5,
            &quot;energy&quot;: 4,
            &quot;rank&quot;: 12,
            &quot;next_energy_in&quot;: &quot;15m 30s&quot;
        },
        &quot;joined_at&quot;: &quot;18 Feb 2026&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-me" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-me"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-me"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-me" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-me">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-me" data-method="GET"
      data-path="api/me"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-me', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-me"
                    onclick="tryItOut('GETapi-me');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-me"
                    onclick="cancelTryOut('GETapi-me');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-me"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/me</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="profil-papan-peringkat-POSTapi-me-update">Update Profil
* Mengubah nama atau foto profil (Avatar) pengguna.</h2>

<p>
</p>

<p>Untuk mengunggah avatar, pastikan mengirim request dalam bentuk <code>multipart/form-data</code>.</p>
<ul>
<li>@authenticated</li>
</ul>

<span id="example-requests-POSTapi-me-update">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/me/update" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "name=Budi Keren"\
    --form "avatar=@C:\Users\Pongo\AppData\Local\Temp\phpAE53.tmp" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/me/update"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('name', 'Budi Keren');
body.append('avatar', document.querySelector('input[name="avatar"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-me-update">
</span>
<span id="execution-results-POSTapi-me-update" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-me-update"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-me-update"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-me-update" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-me-update">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-me-update" data-method="POST"
      data-path="api/me/update"
      data-authed="0"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-me-update', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-me-update"
                    onclick="tryItOut('POSTapi-me-update');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-me-update"
                    onclick="cancelTryOut('POSTapi-me-update');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-me-update"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/me/update</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-me-update"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-me-update"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-me-update"
               value="Budi Keren"
               data-component="body">
    <br>
<p>optional Nama baru pengguna. Example: <code>Budi Keren</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>avatar</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="file" style="display: none"
                              name="avatar"                data-endpoint="POSTapi-me-update"
               value=""
               data-component="body">
    <br>
<p>optional File gambar untuk foto profil (JPG/PNG, Max: 2MB).</p>
<ul>
<li>@response {
&quot;message&quot;: &quot;Profile updated successfully&quot;,
&quot;data&quot;: {
&quot;id&quot;: 1,
&quot;name&quot;: &quot;Budi Keren&quot;,
&quot;avatar&quot;: &quot;<a href="http://localhost:8000/storage/avatars/random-string.png">http://localhost:8000/storage/avatars/random-string.png</a>&quot;,
&quot;stats&quot;: {
&quot;xp&quot;: 1250
}
}
} Example: <code>C:\Users\Pongo\AppData\Local\Temp\phpAE53.tmp</code></li>
</ul>
        </div>
        </form>

                    <h2 id="profil-papan-peringkat-GETapi-leaderboard">Papan Peringkat (Leaderboard)
* Menampilkan daftar 50 besar pengguna dengan skor XP (Experience Points) tertinggi.</h2>

<p>
</p>

<ul>
<li>@authenticated</li>
</ul>

<span id="example-requests-GETapi-leaderboard">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/leaderboard" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/leaderboard"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-leaderboard">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Pro Gamer JPN&quot;,
            &quot;email&quot;: &quot;pro@mail.com&quot;,
            &quot;avatar&quot;: &quot;https://url-ke-gambar.com/avatar.jpg&quot;,
            &quot;stats&quot;: {
                &quot;xp&quot;: 9500,
                &quot;gems&quot;: 0,
                &quot;streak&quot;: 30,
                &quot;energy&quot;: 5
            }
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-leaderboard" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-leaderboard"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-leaderboard"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-leaderboard" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-leaderboard">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-leaderboard" data-method="GET"
      data-path="api/leaderboard"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-leaderboard', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-leaderboard"
                    onclick="tryItOut('GETapi-leaderboard');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-leaderboard"
                    onclick="cancelTryOut('GETapi-leaderboard');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-leaderboard"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/leaderboard</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-leaderboard"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-leaderboard"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="autentikasi-akun">🔐 Autentikasi & Akun</h1>

    <p>API untuk pendaftaran, login (Manual &amp; Google OAuth), serta pembuatan token sesi menggunakan Laravel Sanctum.</p>

                                <h2 id="autentikasi-akun-POSTapi-auth-register">Register Manual
* Mendaftarkan pengguna baru dengan email dan password. Otomatis memberikan 5 Energy awal dan token akses.</h2>

<p>
</p>

<ul>
<li>@unauthenticated</li>
</ul>

<span id="example-requests-POSTapi-auth-register">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/auth/register" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Taro Yamada\",
    \"email\": \"taro@gmail.com\",
    \"password\": \"rahasia123\",
    \"password_confirmation\": \"rahasia123\\n* @response {\\n\\\"message\\\": \\\"Registration successful\\\",\\n\\\"token\\\": \\\"1|abcdef1234567890...\\\",\\n\\\"user\\\": {\\n\\\"name\\\": \\\"Taro Yamada\\\",\\n\\\"email\\\": \\\"taro@gmail.com\\\",\\n\\\"energy\\\": 5,\\n\\\"xp_total\\\": 0,\\n\\\"streak\\\": 0,\\n\\\"updated_at\\\": \\\"2026-02-18T10:00:00.000000Z\\\",\\n\\\"created_at\\\": \\\"2026-02-18T10:00:00.000000Z\\\",\\n\\\"id\\\": 5\\n}\\n}\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/auth/register"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Taro Yamada",
    "email": "taro@gmail.com",
    "password": "rahasia123",
    "password_confirmation": "rahasia123\n* @response {\n\"message\": \"Registration successful\",\n\"token\": \"1|abcdef1234567890...\",\n\"user\": {\n\"name\": \"Taro Yamada\",\n\"email\": \"taro@gmail.com\",\n\"energy\": 5,\n\"xp_total\": 0,\n\"streak\": 0,\n\"updated_at\": \"2026-02-18T10:00:00.000000Z\",\n\"created_at\": \"2026-02-18T10:00:00.000000Z\",\n\"id\": 5\n}\n}"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-auth-register">
            <blockquote>
            <p>Example response (422, Validasi Gagal (Email sudah dipakai)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The email has already been taken.&quot;,
    &quot;errors&quot;: {
        &quot;email&quot;: [
            &quot;The email has already been taken.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-auth-register" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-auth-register"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-auth-register"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-auth-register" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-auth-register">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-auth-register" data-method="POST"
      data-path="api/auth/register"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-auth-register', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-auth-register"
                    onclick="tryItOut('POSTapi-auth-register');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-auth-register"
                    onclick="cancelTryOut('POSTapi-auth-register');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-auth-register"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/auth/register</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-auth-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-auth-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-auth-register"
               value="Taro Yamada"
               data-component="body">
    <br>
<p>Nama lengkap pengguna. Example: <code>Taro Yamada</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-auth-register"
               value="taro@gmail.com"
               data-component="body">
    <br>
<p>Email aktif yang belum pernah didaftarkan. Example: <code>taro@gmail.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-auth-register"
               value="rahasia123"
               data-component="body">
    <br>
<p>Password minimal 8 karakter. Example: <code>rahasia123</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password_confirmation</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password_confirmation"                data-endpoint="POSTapi-auth-register"
               value="rahasia123
* @response {
"message": "Registration successful",
"token": "1|abcdef1234567890...",
"user": {
"name": "Taro Yamada",
"email": "taro@gmail.com",
"energy": 5,
"xp_total": 0,
"streak": 0,
"updated_at": "2026-02-18T10:00:00.000000Z",
"created_at": "2026-02-18T10:00:00.000000Z",
"id": 5
}
}"
               data-component="body">
    <br>
<p>Konfirmasi password (wajib sama dengan password). Example: `rahasia123</p>
<ul>
<li>@response {
&quot;message&quot;: &quot;Registration successful&quot;,
&quot;token&quot;: &quot;1|abcdef1234567890...&quot;,
&quot;user&quot;: {
&quot;name&quot;: &quot;Taro Yamada&quot;,
&quot;email&quot;: &quot;taro@gmail.com&quot;,
&quot;energy&quot;: 5,
&quot;xp_total&quot;: 0,
&quot;streak&quot;: 0,
&quot;updated_at&quot;: &quot;2026-02-18T10:00:00.000000Z&quot;,
&quot;created_at&quot;: &quot;2026-02-18T10:00:00.000000Z&quot;,
&quot;id&quot;: 5
}
}`</li>
</ul>
        </div>
        </form>

                    <h2 id="autentikasi-akun-POSTapi-auth-login">Login Manual
* Mendapatkan token akses (Bearer Token) untuk user yang sudah terdaftar.</h2>

<p>
</p>

<ul>
<li>@unauthenticated</li>
</ul>

<span id="example-requests-POSTapi-auth-login">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/auth/login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"taro@gmail.com\",
    \"password\": \"rahasia123\\n* @response {\\n\\\"message\\\": \\\"Login successful\\\",\\n\\\"token\\\": \\\"2|xyz0987654321...\\\",\\n\\\"user\\\": {\\n\\\"id\\\": 5,\\n\\\"name\\\": \\\"Taro Yamada\\\",\\n\\\"email\\\": \\\"taro@gmail.com\\\",\\n\\\"energy\\\": 5,\\n\\\"xp_total\\\": 150,\\n\\\"streak\\\": 2\\n}\\n}\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/auth/login"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "taro@gmail.com",
    "password": "rahasia123\n* @response {\n\"message\": \"Login successful\",\n\"token\": \"2|xyz0987654321...\",\n\"user\": {\n\"id\": 5,\n\"name\": \"Taro Yamada\",\n\"email\": \"taro@gmail.com\",\n\"energy\": 5,\n\"xp_total\": 150,\n\"streak\": 2\n}\n}"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-auth-login">
            <blockquote>
            <p>Example response (422, Password Salah):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The provided credentials are incorrect.&quot;,
    &quot;errors&quot;: {
        &quot;email&quot;: [
            &quot;The provided credentials are incorrect.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-auth-login" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-auth-login"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-auth-login"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-auth-login" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-auth-login">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-auth-login" data-method="POST"
      data-path="api/auth/login"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-auth-login', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-auth-login"
                    onclick="tryItOut('POSTapi-auth-login');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-auth-login"
                    onclick="cancelTryOut('POSTapi-auth-login');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-auth-login"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/auth/login</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-auth-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-auth-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-auth-login"
               value="taro@gmail.com"
               data-component="body">
    <br>
<p>Email user yang valid. Example: <code>taro@gmail.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-auth-login"
               value="rahasia123
* @response {
"message": "Login successful",
"token": "2|xyz0987654321...",
"user": {
"id": 5,
"name": "Taro Yamada",
"email": "taro@gmail.com",
"energy": 5,
"xp_total": 150,
"streak": 2
}
}"
               data-component="body">
    <br>
<p>Password akun. Example: `rahasia123</p>
<ul>
<li>@response {
&quot;message&quot;: &quot;Login successful&quot;,
&quot;token&quot;: &quot;2|xyz0987654321...&quot;,
&quot;user&quot;: {
&quot;id&quot;: 5,
&quot;name&quot;: &quot;Taro Yamada&quot;,
&quot;email&quot;: &quot;taro@gmail.com&quot;,
&quot;energy&quot;: 5,
&quot;xp_total&quot;: 150,
&quot;streak&quot;: 2
}
}`</li>
</ul>
        </div>
        </form>

                    <h2 id="autentikasi-akun-GETapi-auth-google-redirect">Google Login: Redirect
* API ini mengembalikan URL otorisasi Google. Frontend harus me-redirect user ke URL ini agar mereka bisa login menggunakan akun Google.</h2>

<p>
</p>

<ul>
<li>@unauthenticated</li>
</ul>

<span id="example-requests-GETapi-auth-google-redirect">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/auth/google/redirect" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/auth/google/redirect"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-auth-google-redirect">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;url&quot;: &quot;https://accounts.google.com/o/oauth2/auth?client_id=...&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-auth-google-redirect" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-auth-google-redirect"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-auth-google-redirect"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-auth-google-redirect" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-auth-google-redirect">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-auth-google-redirect" data-method="GET"
      data-path="api/auth/google/redirect"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-auth-google-redirect', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-auth-google-redirect"
                    onclick="tryItOut('GETapi-auth-google-redirect');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-auth-google-redirect"
                    onclick="cancelTryOut('GETapi-auth-google-redirect');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-auth-google-redirect"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/auth/google/redirect</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-auth-google-redirect"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-auth-google-redirect"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="autentikasi-akun-POSTapi-logout">Logout
* Menghancurkan token sesi saat ini agar tidak bisa digunakan lagi.</h2>

<p>
</p>

<ul>
<li>@authenticated</li>
</ul>

<span id="example-requests-POSTapi-logout">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/logout" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/logout"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-logout">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Logged out successfully&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-logout" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-logout"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-logout"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-logout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-logout">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-logout" data-method="POST"
      data-path="api/logout"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-logout', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-logout"
                    onclick="tryItOut('POSTapi-logout');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-logout"
                    onclick="cancelTryOut('POSTapi-logout');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-logout"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/logout</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="peta-kurikulum-materi">🗺️ Peta Kurikulum & Materi</h1>

    <p>API untuk menampilkan struktur bab (Chapter), daftar unit materi, dan panduan belajar sebelum kuis.</p>

                                <h2 id="peta-kurikulum-materi-GETapi-chapters">Tampilkan Learning Map (Homepage)
* Mengambil daftar seluruh Chapter dan Unit secara berurutan. API ini otomatis menyisipkan status progress dari user yang sedang login (apakah unit tersebut `locked`, `open`, atau `completed`), serta jumlah bintang yang diraih.</h2>

<p>
</p>

<ul>
<li>@authenticated</li>
</ul>

<span id="example-requests-GETapi-chapters">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/chapters" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/chapters"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-chapters">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Pengenalan Huruf Jepang&quot;,
            &quot;topic_keyword&quot;: &quot;Hiragana &amp; Katakana Dasar&quot;,
            &quot;order_sequence&quot;: 1,
            &quot;units&quot;: [
                {
                    &quot;id&quot;: 1,
                    &quot;name&quot;: &quot;Vokal Hiragana (A, I, U, E, O)&quot;,
                    &quot;topic_keyword&quot;: &quot;Hiragana Vowels&quot;,
                    &quot;order_sequence&quot;: 1,
                    &quot;status&quot;: &quot;completed&quot;,
                    &quot;stars&quot;: 3,
                    &quot;current_level&quot;: 1
                },
                {
                    &quot;id&quot;: 2,
                    &quot;name&quot;: &quot;Baris K &amp; S&quot;,
                    &quot;topic_keyword&quot;: &quot;Hiragana K S&quot;,
                    &quot;order_sequence&quot;: 2,
                    &quot;status&quot;: &quot;open&quot;,
                    &quot;stars&quot;: 0,
                    &quot;current_level&quot;: 0
                }
            ]
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-chapters" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-chapters"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-chapters"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-chapters" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-chapters">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-chapters" data-method="GET"
      data-path="api/chapters"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-chapters', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-chapters"
                    onclick="tryItOut('GETapi-chapters');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-chapters"
                    onclick="cancelTryOut('GETapi-chapters');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-chapters"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/chapters</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-chapters"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-chapters"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="peta-kurikulum-materi-GETapi-units--id-">Detail Materi Unit (Guide)
* Mengambil detail satu unit beserta teks materinya (`guide_md`). Ini ditampilkan saat user menekan Unit di peta, sebelum mereka menekan tombol &quot;Mulai Kuis&quot;.</h2>

<p>
</p>

<ul>
<li>@authenticated</li>
</ul>

<span id="example-requests-GETapi-units--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/units/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/units/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-units--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;id&quot;: 1,
    &quot;name&quot;: &quot;Vokal Hiragana (A, I, U, E, O)&quot;,
    &quot;guide_md&quot;: &quot;# Pengenalan\nHuruf vokal dalam bahasa Jepang terdiri dari あ (a), い (i), う (u), え (e), dan お (o)...&quot;,
    &quot;topic&quot;: &quot;Hiragana Vowels&quot;,
    &quot;progress&quot;: {
        &quot;id&quot;: 15,
        &quot;user_id&quot;: 3,
        &quot;unit_id&quot;: 1,
        &quot;current_level&quot;: 1,
        &quot;is_completed&quot;: true,
        &quot;is_locked&quot;: false,
        &quot;created_at&quot;: &quot;2026-02-18T10:00:00Z&quot;,
        &quot;updated_at&quot;: &quot;2026-02-18T10:15:00Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Unit tidak ditemukan):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No query results for model [App\\Models\\Unit] 999&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-units--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-units--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-units--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-units--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-units--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-units--id-" data-method="GET"
      data-path="api/units/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-units--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-units--id-"
                    onclick="tryItOut('GETapi-units--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-units--id-"
                    onclick="cancelTryOut('GETapi-units--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-units--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/units/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-units--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-units--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-units--id-"
               value="1"
               data-component="url">
    <br>
<p>ID dari Unit yang ingin dilihat materinya. Example: <code>1</code></p>
            </div>
                    </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
