<div class="sx" id="sx" hidden>
    <div class="sx-petals" id="sxPetals" aria-hidden="true"></div>

    <!-- Music on/off -->
    <button type="button" class="sx-music" id="sxMusic" aria-label="Turn music off" aria-pressed="true">🔊</button>

    <!-- Step 1: locked gift -->
    <section class="sx-step" id="sxLock">
        <p class="sx-eyebrow">A little surprise for you</p>

        <div class="sx-gift" id="sxGift" aria-hidden="true">
            <span class="sx-lid"></span>
            <span class="sx-body"></span>
            <span class="sx-glow"></span>
        </div>

        <p class="sx-label">Enter the 4-digit code to open it</p>
        <div class="sx-code" id="sxCode">
            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" aria-label="Digit 1" autocomplete="off">
            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" aria-label="Digit 2" autocomplete="off">
            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" aria-label="Digit 3" autocomplete="off">
            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" aria-label="Digit 4" autocomplete="off">
        </div>
        <p class="sx-error" id="sxError" role="alert" hidden>Hmm, that's not it. Try again 💗</p>
        <p class="sx-hint" id="sxHint" hidden></p>
    </section>

    <!-- Step 2: bouquet -->
    <section class="sx-step" id="sxBouquetStep" hidden>
        <h2 class="sx-title" id="sxTitle"></h2>
        <p class="sx-label" id="sxTap">Tap each tulip 🌷</p>
        <div class="sx-bouquet" id="sxBouquet"></div>

        <!-- Appears after every tulip has been opened -->
        <div class="sx-end" id="sxEnd" hidden>
            <p class="sx-final" id="sxFinal"></p>
            <button type="button" class="sx-letter-btn" id="sxLetterBtn">Read my letter 💌</button>
            <!-- Appears after she has read the letter and the warning note -->
            <button type="button" class="sx-note-btn" id="sxNoteBtn" hidden>⚠️ Read this again</button>
            <button type="button" class="sx-back" id="sxBack" hidden>Back to Life Quest 💗</button>
        </div>
    </section>

    <!-- Tulip message + photo popup -->
    <div class="sx-modal" id="sxModal" hidden>
        <div class="sx-card sx-wide" role="dialog" aria-modal="true">
            <button type="button" class="sx-close" id="sxClose" aria-label="Close">&times;</button>
            <img id="sxPhoto" class="sx-photo" alt="">
            <p class="sx-msg" id="sxMsg"></p>
        </div>
    </div>

    <!-- Letter popup -->
    <div class="sx-modal" id="sxLetterModal" hidden>
        <div class="sx-letter" role="dialog" aria-modal="true" aria-labelledby="sxLetterTitle">
            <button type="button" class="sx-close" id="sxLetterClose" aria-label="Close letter">&times;</button>
            <h3 class="sx-letter-title" id="sxLetterTitle"></h3>
            <div class="sx-letter-body" id="sxLetterBody"></div>
            <p class="sx-letter-from" id="sxLetterFrom"></p>
        </div>
    </div>

    <!-- Warning note (appears after the letter is closed) -->
    <div class="sx-modal sx-note-modal" id="sxNoteModal" hidden>
        <div class="sx-note" role="alertdialog" aria-modal="true" aria-labelledby="sxNoteTitle" aria-describedby="sxNoteText">
            <div class="sx-note-icon" aria-hidden="true">⚠️</div>
            <h3 class="sx-note-title" id="sxNoteTitle"></h3>
            <p class="sx-note-text" id="sxNoteText"></p>
            <button type="button" class="sx-letter-btn" id="sxNoteOk"></button>
        </div>
    </div>

    <!-- "Are you sure you want to leave?" popup -->
    <div class="sx-modal" id="sxLeaveModal" hidden>
        <div class="sx-card sx-leave" role="dialog" aria-modal="true" aria-labelledby="sxLeaveTitle">
            <h3 class="sx-leave-title" id="sxLeaveTitle">Leave the surprise? 💗</h3>
            <p class="sx-leave-text">You can stay and look at the photos again. If you leave, you'll need to play the game again to see it.</p>
            <div class="sx-leave-actions">
                <button type="button" class="sx-letter-btn" id="sxStay">Stay 💗</button>
                <button type="button" class="sx-back" id="sxLeave">Leave</button>
            </div>
        </div>
    </div>
</div>

<style>
.sx {
    position: fixed;
    inset: 0;
    z-index: 200;
    overflow-y: auto;
    display: flex;
    padding: 24px 16px;
    font-family: 'Poppins', sans-serif;
    color: #6b1f45;
    background: linear-gradient(160deg, #ffe3ee 0%, #ffc1d9 55%, #ff9fc4 100%);
    animation: sxFade 0.8s ease-out;
}
.sx[hidden] { display: none; }
.sx input { user-select: text; -webkit-user-select: text; }

/* Music button */
.sx-music {
    position: fixed;
    top: 12px;
    right: 12px;
    z-index: 4;
    width: 46px;
    height: 46px;
    padding: 0;
    font-size: 1.35rem;
    line-height: 1;
    cursor: pointer;
    background: #fff;
    border: 3px solid #7a1f4b;
    border-radius: 50%;
    box-shadow: 0 3px 0 #7a1f4b;
}
.sx-music:active { transform: translateY(2px); box-shadow: 0 1px 0 #7a1f4b; }
.sx-music:focus-visible { outline: 3px solid #c2185b; outline-offset: 3px; }

/* margin:auto centers the content but never cuts off the top when it is taller than the screen */
.sx-step { position: relative; z-index: 2; width: 100%; max-width: 640px; margin: auto; text-align: center; }
.sx-step[hidden] { display: none; }

.sx-eyebrow { font-family: 'Press Start 2P', monospace; font-size: 0.8rem; line-height: 1.7; color: #c2185b; margin-bottom: 18px; }
.sx-title   { font-family: 'Press Start 2P', monospace; font-size: clamp(0.85rem, 2.8vw, 1.25rem); line-height: 1.7; color: #c2185b; margin-bottom: 10px; }
.sx-label   { margin: 8px 0 14px; font-weight: 600; }

/* Gift box */
.sx-gift {
    position: relative;
    width: 150px;
    height: 150px;
    margin: 0 auto 18px;
    animation: sxBounce 1.3s ease-in-out infinite;
}
.sx-body, .sx-lid { position: absolute; left: 0; right: 0; margin: auto; border: 3px solid #7a1f4b; }
.sx-body {
    bottom: 0; width: 126px; height: 88px;
    background: linear-gradient(90deg, #ff6fa5 42%, #fff 42%, #fff 58%, #ff6fa5 58%);
    border-radius: 0 0 12px 12px;
}
.sx-lid {
    bottom: 85px; width: 142px; height: 32px;
    background: linear-gradient(90deg, #ff4d94 42%, #fff 42%, #fff 58%, #ff4d94 58%);
    border-radius: 9px;
    transform-origin: left bottom;
    transition: transform 0.6s ease, opacity 0.6s;
}
.sx-glow {
    position: absolute; inset: 10px; border-radius: 50%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.9), transparent 70%);
    opacity: 0; transition: opacity 0.5s; pointer-events: none;
}
.sx-gift.open { animation: none; }
.sx-gift.open .sx-lid  { transform: translateY(-50px) rotate(-28deg); opacity: 0; }
.sx-gift.open .sx-glow { opacity: 1; }

/* Code input */
.sx-code { display: flex; justify-content: center; gap: 12px; }
.sx-code input {
    width: 56px; height: 66px;
    font: 700 1.6rem 'Poppins', sans-serif;
    text-align: center;
    color: #7a1f4b;
    background: #fff;
    border: 3px solid #ff6fa5;
    border-radius: 14px;
}
.sx-code input:focus { outline: none; border-color: #c2185b; box-shadow: 0 0 0 4px rgba(194, 24, 91, 0.2); }
.sx-code.shake { animation: sxShake 0.45s; }
.sx-error { margin-top: 14px; color: #c2185b; font-weight: 600; }
.sx-hint  { margin-top: 8px; font-size: 0.9rem; opacity: 0.85; }

/* ===== Bouquet ===== */
.sx-bouquet {
    position: relative;
    isolation: isolate;
    width: min(94vw, 560px, max(260px, calc((100vh - 200px) / 1.15)));
    width: min(94vw, 560px, max(260px, calc((100dvh - 200px) / 1.15)));
    aspect-ratio: 100 / 115;
    margin: 4px auto 0;
}
.sx-tulip, .sx-leaf {
    position: absolute;
    left: 50%;
    bottom: 24%;
    padding: 0;
    background: none;
    border: 0;
    transform-origin: 50% 100%;
    transform: translateX(-50%) rotate(var(--r)) scale(0);
    animation: sxGrow 0.8s cubic-bezier(.2, 1.3, .4, 1) both;
    animation-delay: var(--d, 0s);
}
.sx-tulip { aspect-ratio: 60 / 150; cursor: pointer; pointer-events: none; }
.sx-leaf  { aspect-ratio: 40 / 150; z-index: 2; pointer-events: none; }
.sx-tulip svg, .sx-leaf svg { width: 100%; height: 100%; display: block; overflow: visible; }
.sx-tulip svg {
    transform-origin: 50% 100%;
    animation: sxSway 4.5s ease-in-out infinite;
    animation-delay: var(--sd, 0s);
    filter: drop-shadow(0 3px 4px rgba(122, 31, 75, 0.25));
}
.sx-tulip svg path { pointer-events: auto; }
.sx-tulip:hover svg, .sx-tulip:focus-visible svg { filter: drop-shadow(0 0 10px #fff) drop-shadow(0 3px 4px rgba(122, 31, 75, 0.25)); }
.sx-tulip:focus-visible { outline: 3px solid #c2185b; outline-offset: 2px; border-radius: 10px; }
.sx-tulip.seen::after {
    content: '💗';
    position: absolute; top: -6px; left: 50%;
    transform: translateX(-50%);
    font-size: clamp(1rem, 3.4vw, 1.5rem);
}
.sx-wrap {
    position: absolute;
    left: 50%;
    bottom: 0;
    width: 64%;
    transform: translateX(-50%);
    z-index: 30;
    pointer-events: none;
    filter: drop-shadow(0 4px 6px rgba(122, 31, 75, 0.25));
}
.sx-wrap svg { display: block; width: 100%; height: auto; }

/* After all tulips are opened */
.sx-end {
    margin-top: 18px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 14px;
    animation: sxPop 0.5s ease-out;
}
.sx-end[hidden] { display: none; }
.sx-final {
    padding: 12px 16px;
    font-weight: 600;
    line-height: 1.6;
    white-space: pre-line;
    background: rgba(255, 255, 255, 0.75);
    border-radius: 14px;
}
.sx-final:empty { display: none; }

.sx-letter-btn, .sx-back, .sx-note-btn {
    display: inline-block;
    padding: 14px 22px;
    font-family: 'Press Start 2P', monospace;
    font-size: 0.7rem;
    line-height: 1.5;
    text-decoration: none;
    cursor: pointer;
    border: 3px solid #7a1f4b;
    border-radius: 14px;
    box-shadow: 0 5px 0 #7a1f4b;
}
.sx-letter-btn { color: #fff; background: #ff4d94; }
.sx-back       { color: #7a1f4b; background: #fff; }
.sx-note-btn   { color: #7a1f4b; background: #fff3b0; }
.sx-back[hidden], .sx-note-btn[hidden] { display: none; }
.sx-letter-btn:active, .sx-back:active, .sx-note-btn:active { transform: translateY(4px); box-shadow: 0 1px 0 #7a1f4b; }
.sx-letter-btn:focus-visible, .sx-back:focus-visible, .sx-note-btn:focus-visible { outline: 3px solid #c2185b; outline-offset: 4px; }

/* Popups (tulip message, letter, leave confirmation) */
.sx-modal {
    position: fixed; inset: 0; z-index: 5;
    display: flex; align-items: center; justify-content: center;
    padding: 14px;
    background: rgba(122, 31, 75, 0.6);
}
.sx-modal[hidden] { display: none; }
.sx-card {
    position: relative;
    width: 100%; max-width: 360px; max-height: 94vh; overflow-y: auto;
    padding: 34px 18px 22px;
    text-align: center;
    background: #fff;
    border: 3px solid #ff6fa5;
    border-radius: 20px;
    animation: sxPop 0.35s ease-out;
    -webkit-overflow-scrolling: touch;
}
.sx-card.sx-wide { max-width: min(94vw, 760px); }
.sx-close {
    position: absolute; top: 4px; right: 8px;
    width: 44px; height: 44px;
    font-size: 2rem; line-height: 1;
    color: #c2185b; background: none; border: 0; cursor: pointer;
}
/* the whole photo is shown, never cropped */
.sx-photo {
    display: block;
    width: auto;
    height: auto;
    max-width: 100%;
    max-height: 70vh;
    max-height: 70dvh;
    margin: 0 auto 14px;
    object-fit: contain;
    border-radius: 14px;
    background: #fff0f6;
}
.sx-photo[hidden] { display: none; }
.sx-msg { line-height: 1.7; font-size: 1.05rem; white-space: pre-line; }
.sx-msg:empty { display: none; }

/* Leave confirmation */
.sx-leave { padding: 26px 20px 24px; }
.sx-leave-title { margin-bottom: 12px; font-size: 1.15rem; color: #c2185b; }
.sx-leave-text { line-height: 1.6; margin-bottom: 20px; }
.sx-leave-actions { display: flex; flex-direction: column; align-items: center; gap: 14px; }

/* Letter */
.sx-letter {
    position: relative;
    width: 100%; max-width: 480px; max-height: 90vh; overflow-y: auto;
    padding: 38px 26px 30px;
    background: #fffaf3;
    border: 3px solid #ff6fa5;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(122, 31, 75, 0.35);
    animation: sxPop 0.4s ease-out;
    -webkit-overflow-scrolling: touch;
}
.sx-letter-title {
    margin-bottom: 18px;
    padding: 0 22px;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 1.25rem;
    line-height: 1.5;
    font-weight: 700;
    color: #c2185b;
    text-align: center;
}
.sx-letter-body {
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 1.05rem;
    line-height: 1.9;
    color: #5a2a3f;
    white-space: pre-line;
    text-align: left;
}
.sx-letter-from {
    margin-top: 22px;
    font-family: Georgia, 'Times New Roman', serif;
    font-style: italic;
    font-size: 1.05rem;
    line-height: 1.7;
    color: #c2185b;
    text-align: right;
    white-space: pre-line;
}
.sx-letter-from:empty { display: none; }

/* Warning note */
.sx-note-modal { z-index: 8; background: rgba(60, 10, 30, 0.8); }
.sx-note {
    width: 100%;
    max-width: 380px;
    max-height: 94vh;
    overflow-y: auto;
    padding: 26px 22px 24px;
    text-align: center;
    background: #fffbe6;
    border: 5px solid #ff3b6b;
    border-radius: 22px;
    animation: sxPop 0.35s ease-out, sxAlert 0.9s ease-in-out 0.35s infinite;
}
.sx-note-icon  { font-size: 3.4rem; line-height: 1; margin-bottom: 8px; animation: sxWiggle 1.1s ease-in-out infinite; }
.sx-note-title { font-family: 'Press Start 2P', monospace; font-size: 1.05rem; line-height: 1.6; color: #d4144f; margin-bottom: 14px; }
.sx-note-text  { font-size: 1.12rem; line-height: 1.7; font-weight: 600; color: #6b1f45; white-space: pre-line; margin-bottom: 20px; }

/* Falling flowers */
.sx-petals { position: absolute; inset: 0; z-index: 1; overflow: hidden; pointer-events: none; }
.sx-petals span {
    position: absolute; top: -40px;
    animation: sxFall linear infinite;
    user-select: none;
}

@keyframes sxFade   { from { opacity: 0; } to { opacity: 1; } }
@keyframes sxBounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
@keyframes sxShake  { 0%, 100% { transform: translateX(0); } 20%, 60% { transform: translateX(-9px); } 40%, 80% { transform: translateX(9px); } }
@keyframes sxGrow   {
    from { transform: translateX(-50%) rotate(var(--r)) scale(0); }
    to   { transform: translateX(-50%) rotate(var(--r)) scale(1); }
}
@keyframes sxSway   { 0%, 100% { transform: rotate(-1.6deg); } 50% { transform: rotate(1.6deg); } }
@keyframes sxPop    { from { transform: scale(0.85); opacity: 0; } to { transform: scale(1); opacity: 1; } }
@keyframes sxAlert  { 0%, 100% { box-shadow: 0 0 0 0 rgba(255, 59, 107, 0.7); border-color: #ff3b6b; } 50% { box-shadow: 0 0 0 16px rgba(255, 59, 107, 0); border-color: #ffb300; } }
@keyframes sxWiggle { 0%, 100% { transform: rotate(-8deg) scale(1); } 50% { transform: rotate(8deg) scale(1.12); } }
@keyframes sxFall   {
    0%   { transform: translate3d(0, 0, 0) rotate(0deg); }
    50%  { transform: translate3d(30px, 55vh, 0) rotate(180deg); }
    100% { transform: translate3d(-20px, 110vh, 0) rotate(360deg); }
}

@media (prefers-reduced-motion: reduce) {
    .sx, .sx-gift, .sx-card, .sx-letter, .sx-end, .sx-note, .sx-note-icon { animation: none; }
    .sx-petals { display: none; }
    .sx-tulip, .sx-leaf { animation: none; transform: translateX(-50%) rotate(var(--r)); }
    .sx-tulip svg { animation: none; }
}
</style>

<script>
(function () {
    /* ====== EDIT THIS PART ====== */
    var CONFIG = {
        code: '2716',
        name: 'Baby Fury',         // shown in the title: "Happy Birthday, Baby Fury!"
        hint: '',                  // optional hint under the code, e.g. 'Think of a special date'

        // Background music (the Happy Birthday melody, played by the page itself)
        music: {
            enabled: true,         // false = no music at all
            volume: 0.14,          // 0.05 (very soft) to 0.4 (loud)
            bpm: 100               // speed: lower = slower
        },

        // Short line shown above the letter button (leave '' for none)
        finalNote: 'One more message. 💌',

        // The long letter. Line breaks and blank lines are kept.
        // Do not type a backtick ( ` ) or the characters ${ inside the letter.
        letter: {
            button: 'Read my letter 💌',
            title: 'HIII PATAKLA! NANAY! OR BABY FURYYYY,',
            body: `HAPPY HAPPY BIRTHDAY AND HOPE HAPPY IKAW SA BIRTHDAY MO DAHIL DESERVE MO SUMAYA SA LIFE. KAHIT ANO ISIPIN MO ULIT ULITIN KO SASABIHIN SAYO NA WORTH IT IKAW! AT DESERVE MO SUMAYA SA ARAW-ARAW DAHIL HINDI KALANG ISANG MABAET NA ANAK, MABAET NA ATEH, OR MABAET NA KAIBIGAN ISA KANG MABAIT NA TAO NA NAKILALA KO KAYA SANA I KEEP MO ANG GANYAN MONG PERSONALITY! I KNOW HINDI ENOUGH TOH PARA MA BUO KO ARAW MO PERO I HOPE MAGING PART ETOH NG MEMORIES NA MERON TAYO, PLEASE ALWAYS REMEMBER NA MAGANDA KAAA ANG KYUTTT MO PA SOBRA! KAYA WAG MO PABAYAHAN SARILI MO AT WAG MAG PA STRESS MASYADO. MABUHAY KAPA NG MATAGAL KASE KASAMA KA NA SA FUTURE PLANS KO. GAGAWEN NATEN SABAY ANG PANGARAP NATIN OWKIE? ALWAYS REMEMBER NA NANDITOH LANG AKO KAYA PLEASE ANYTIME LUMAPIT KALANG SAKIN HANDA KO GAWEN LAHAT PARA SAYO.

IF HINDI ITOH MABASA NI MOMMY, I LOVE YOU SO MUCH BABI! ENJOY YOUR DAY HAVE FUNNN 💞

AT KUNG NABASA NI MOMMY AND DADDY SORRY POOO 🥹
MAHAL KO LANG PO TALAGA ANAK NINYO. LOVE YOU ALL 💓`,
            from: ''
        },

        // Warning note shown right after she closes the letter. Edit the words to whatever you like.
        note: {
            title: 'WAIT, BABY!',
            text: 'Before you leave...\n\nMay naghihintay sayo sa labas ngayon. 👀🌷\n\nTumingin ka sa labas, NGAYON NA!',
            button: 'Titingin na ako! 👀'
        },

        // Photos live in the surprise-photos folder. Use the file name after simg=
        // message: '' = photo only. Write a caption between the quotes if you want one.
        tulips: [
            { message: '', image: '/play?simg=cute.jpg' },
            { message: '', image: '/play?simg=eyes.jpg' },
            { message: '', image: '/play?simg=jen.jpg' },
            { message: '', image: '/play?simg=best.jpg' },
            { message: '', image: '/play?simg=us.jpg' }
        ]
    };
    /* ============================ */

    var $ = function (id) { return document.getElementById(id); };
    var opened = 0;
    var letterRead = false;
    var noteShown = false;

    /* ---------- Background music: the Happy Birthday melody, played with the Web Audio API ---------- */
    var M = { ctx: null, master: null, timer: null, on: true };

    // [frequency in Hz, length in beats]  (3/4 time)
    var SONG = [
        [392.00, 0.75], [392.00, 0.25], [440.00, 1], [392.00, 1], [523.25, 1], [493.88, 2],
        [392.00, 0.75], [392.00, 0.25], [440.00, 1], [392.00, 1], [587.33, 1], [523.25, 2],
        [392.00, 0.75], [392.00, 0.25], [783.99, 1], [659.25, 1], [523.25, 1], [493.88, 1], [440.00, 1],
        [698.46, 0.75], [698.46, 0.25], [659.25, 1], [523.25, 1], [587.33, 1], [523.25, 3]
    ];

    function musicVolume() {
        var v = CONFIG.music && CONFIG.music.volume;
        return (typeof v === 'number') ? Math.max(0, Math.min(0.6, v)) : 0.14;
    }

    function bell(freq, t, dur) {
        var ctx = M.ctx;
        var o1 = ctx.createOscillator(), o2 = ctx.createOscillator();
        var g = ctx.createGain(), g2 = ctx.createGain();
        o1.type = 'sine';     o1.frequency.value = freq;
        o2.type = 'triangle'; o2.frequency.value = freq * 2;
        g2.gain.value = 0.22;
        var end = t + Math.max(0.7, dur * 1.7);
        g.gain.setValueAtTime(0.0001, t);
        g.gain.exponentialRampToValueAtTime(0.55, t + 0.012);
        g.gain.exponentialRampToValueAtTime(0.0001, end);
        o1.connect(g); o2.connect(g2); g2.connect(g); g.connect(M.master);
        o1.start(t); o2.start(t);
        o1.stop(end + 0.05); o2.stop(end + 0.05);
    }

    function playPass() {
        if (!M.ctx) return;
        var beat = 60 / ((CONFIG.music && CONFIG.music.bpm) || 100);
        var t = M.ctx.currentTime + 0.15;
        var total = 0;
        SONG.forEach(function (n) {
            var len = n[1] * beat;
            bell(n[0], t + total, len);
            total += len;
        });
        // short pause, then start the song again
        M.timer = setTimeout(playPass, (total + beat * 2.5) * 1000);
    }

    function pauseGameMusic() {
        try {
            document.querySelectorAll('audio, video').forEach(function (a) { try { a.pause(); } catch (e) {} });
            var gm = window.LQMusic;
            if (gm) ['pause', 'stop'].forEach(function (k) { if (typeof gm[k] === 'function') { try { gm[k](); } catch (e) {} } });
        } catch (e) {}
    }

    function setMusicButton() {
        var b = $('sxMusic');
        if (!b) return;
        b.textContent = M.on ? '🔊' : '🔇';
        b.setAttribute('aria-pressed', M.on ? 'true' : 'false');
        b.setAttribute('aria-label', M.on ? 'Turn music off' : 'Turn music on');
    }

    function applyMusicVolume() {
        if (!M.ctx || !M.master) return;
        M.master.gain.setTargetAtTime(M.on ? musicVolume() : 0, M.ctx.currentTime, 0.05);
    }

    function startMusic() {
        var btn = $('sxMusic');
        if (!CONFIG.music || !CONFIG.music.enabled) { if (btn) btn.hidden = true; return; }
        if (btn) btn.hidden = false;
        pauseGameMusic();
        M.on = true;
        setMusicButton();
        try {
            var AC = window.AudioContext || window.webkitAudioContext;
            if (!AC) return;
            if (M.timer) { clearTimeout(M.timer); M.timer = null; }
            if (M.ctx) { try { M.ctx.close(); } catch (e) {} }
            M.ctx = new AC();
            M.master = M.ctx.createGain();
            M.master.gain.value = musicVolume();
            M.master.connect(M.ctx.destination);
            if (M.ctx.state === 'suspended') M.ctx.resume();
            playPass();
        } catch (e) {}
    }

    // Some phones only allow sound after a tap. Resume on the first touch or key press.
    function resumeOnTouch() {
        if (M.ctx && M.ctx.state === 'suspended') { try { M.ctx.resume(); } catch (e) {} }
    }
    ['pointerdown', 'touchstart', 'keydown', 'click'].forEach(function (ev) {
        document.addEventListener(ev, resumeOnTouch, { passive: true });
    });

    function toggleMusic() {
        M.on = !M.on;
        setMusicButton();
        resumeOnTouch();
        applyMusicVolume();
    }

    /* ---------- Bouquet drawing ---------- */
    var PALETTES = [
        { a: '#f48fb1', b: '#f06292', c: '#f8bbd0' },
        { a: '#f8a5c2', b: '#ec6a9a', c: '#fcd0e0' },
        { a: '#ee7aa6', b: '#e0558c', c: '#f6b3cd' }
    ];

    function tulipSVG(p) {
        return '<svg viewBox="0 0 60 150" aria-hidden="true">' +
            '<path d="M30 62 C30 100 30 125 30 150" stroke="#3f9b5a" stroke-width="5" fill="none" stroke-linecap="round"/>' +
            '<path d="M30 118 C10 110 6 92 8 80 C22 84 30 98 30 118Z" fill="#4caf6a"/>' +
            '<path d="M12 30 C10 8 22 2 30 2 C38 2 50 8 48 30 C46 52 38 64 30 64 C22 64 14 52 12 30Z" fill="' + p.a + '"/>' +
            '<path d="M12 30 C14 52 22 64 30 64 C26 48 24 30 22 10 C16 12 12 20 12 30Z" fill="' + p.b + '"/>' +
            '<path d="M48 30 C46 52 38 64 30 64 C34 48 36 30 38 10 C44 12 48 20 48 30Z" fill="' + p.b + '"/>' +
            '<path d="M22 10 C26 2 34 2 38 10 C38 34 34 52 30 64 C26 52 22 34 22 10Z" fill="' + p.c + '"/>' +
            '</svg>';
    }

    function leafSVG() {
        return '<svg viewBox="0 0 40 150" aria-hidden="true">' +
            '<path d="M20 150 C6 110 4 50 20 4 C36 50 34 110 20 150Z" fill="#5fb77a" stroke="#3f8f5c" stroke-width="1.5"/>' +
            '<path d="M20 146 L20 20" stroke="#3f8f5c" stroke-width="1.5" fill="none" opacity=".6"/>' +
            '</svg>';
    }

    var WRAP_SVG =
        '<svg viewBox="0 0 200 140" aria-hidden="true">' +
        '<path d="M6 6 Q100 -6 194 6 L116 138 Q100 142 84 138 Z" fill="#ffd9e8" stroke="#7a1f4b" stroke-width="3" stroke-linejoin="round"/>' +
        '<path d="M10 8 Q100 -2 190 8 L172 44 Q100 28 28 44 Z" fill="#fff" opacity="0.65"/>' +
        '<path d="M66 50 L98 134 M134 50 L102 134" stroke="#ffb3d1" stroke-width="3" fill="none" stroke-linecap="round"/>' +
        '<path d="M44 68 Q100 84 156 68 L147 88 Q100 104 53 88 Z" fill="#ff4d94" stroke="#7a1f4b" stroke-width="3" stroke-linejoin="round"/>' +
        '<path d="M100 86 C72 64 60 96 86 100 C96 101 100 92 100 86Z" fill="#ff4d94" stroke="#7a1f4b" stroke-width="2.5" stroke-linejoin="round"/>' +
        '<path d="M100 86 C128 64 140 96 114 100 C104 101 100 92 100 86Z" fill="#ff4d94" stroke="#7a1f4b" stroke-width="2.5" stroke-linejoin="round"/>' +
        '<path d="M97 93 L86 124 M103 93 L114 124" stroke="#7a1f4b" stroke-width="3" fill="none" stroke-linecap="round"/>' +
        '<circle cx="100" cy="87" r="6" fill="#ff6fa5" stroke="#7a1f4b" stroke-width="2.5"/>' +
        '</svg>';

    function startPetals() {
        var box = $('sxPetals');
        if (box.children.length) return;
        var icons = ['🌸', '🌷', '🌸', '💮', '🌸'];
        for (var i = 0; i < 28; i++) {
            var s = document.createElement('span');
            s.textContent = icons[i % icons.length];
            s.style.left = Math.random() * 100 + '%';
            s.style.fontSize = 14 + Math.random() * 18 + 'px';
            s.style.animationDuration = 7 + Math.random() * 7 + 's';
            s.style.animationDelay = -Math.random() * 12 + 's';
            s.style.opacity = 0.6 + Math.random() * 0.4;
            box.appendChild(s);
        }
    }

    function updateProgress() {
        var n = CONFIG.tulips.length;
        $('sxTap').textContent = 'Tap each tulip 🌷 (' + Math.min(opened, n) + '/' + n + ')';
    }

    function openModal(t) {
        var img = $('sxPhoto');
        $('sxMsg').textContent = t.message || '';
        img.hidden = !t.image;
        if (t.image) {
            img.onerror = function () { img.hidden = true; };
            img.src = t.image;
        }
        $('sxModal').hidden = false;
        $('sxModal').querySelector('.sx-card').scrollTop = 0;
        $('sxClose').focus();
    }

    function buildBouquet() {
        var box = $('sxBouquet');
        box.innerHTML = '';
        var n = CONFIG.tulips.length;
        var maxA = n > 1 ? Math.min(34, 8 * (n - 1)) : 0;

        [{ a: -58, l: 46 }, { a: -36, l: 54 }, { a: 36, l: 54 }, { a: 58, l: 46 }].forEach(function (lf) {
            var d = document.createElement('div');
            d.className = 'sx-leaf';
            d.style.setProperty('--r', lf.a + 'deg');
            d.style.height = lf.l + '%';
            d.innerHTML = leafSVG();
            box.appendChild(d);
        });

        CONFIG.tulips.forEach(function (t, i) {
            var s = n > 1 ? (i / (n - 1)) * 2 - 1 : 0;
            var len = 72 - 16 * Math.pow(Math.abs(s), 1.5) + (n > 1 ? (i % 2 ? 1.5 : -1.5) : 0);
            var b = document.createElement('button');
            b.type = 'button';
            b.className = 'sx-tulip';
            b.setAttribute('aria-label', 'Open photo ' + (i + 1));
            b.style.height = len + '%';
            b.style.zIndex = 10 + Math.round((1 - Math.abs(s)) * 10);
            b.style.setProperty('--r', (s * maxA) + 'deg');
            b.style.setProperty('--d', (0.15 + i * 0.15) + 's');
            b.style.setProperty('--sd', (-i * 0.7) + 's');
            b.innerHTML = tulipSVG(PALETTES[i % PALETTES.length]);
            b.onclick = function () {
                if (!b.classList.contains('seen')) {
                    b.classList.add('seen');
                    opened++;
                    updateProgress();
                }
                openModal(t);
            };
            box.appendChild(b);
        });

        var w = document.createElement('div');
        w.className = 'sx-wrap';
        w.innerHTML = WRAP_SVG;
        box.appendChild(w);
    }

    function showBouquet() {
        $('sxLock').hidden = true;
        $('sxBouquetStep').hidden = false;
        $('sxTitle').textContent = 'Happy Birthday, ' + CONFIG.name + '!';
        updateProgress();
        buildBouquet();
    }

    function setupCode() {
        var wrap = $('sxCode');
        var inputs = Array.prototype.slice.call(wrap.querySelectorAll('input'));

        function reset() {
            inputs.forEach(function (i) { i.value = ''; i.disabled = false; });
            inputs[0].focus();
        }
        function check() {
            var typed = inputs.map(function (i) { return i.value; }).join('');
            if (typed.length < inputs.length) return;
            if (typed === CONFIG.code) {
                $('sxError').hidden = true;
                inputs.forEach(function (i) { i.disabled = true; });
                $('sxGift').classList.add('open');
                setTimeout(showBouquet, 1100);
            } else {
                $('sxError').hidden = false;
                wrap.classList.add('shake');
                setTimeout(function () { wrap.classList.remove('shake'); reset(); }, 450);
            }
        }

        inputs.forEach(function (input, idx) {
            input.value = '';
            input.oninput = function () {
                input.value = input.value.replace(/\D/g, '').slice(0, 1);
                if (input.value && inputs[idx + 1]) inputs[idx + 1].focus();
                check();
            };
            input.onkeydown = function (e) {
                if (e.key === 'Backspace' && !input.value && inputs[idx - 1]) inputs[idx - 1].focus();
            };
        });
        reset();
    }

    // Close the photo popup. When every tulip has been opened, show the letter button.
    function closeModal() {
        $('sxModal').hidden = true;
        if (opened >= CONFIG.tulips.length) {
            var end = $('sxEnd');
            var firstTime = end.hidden;
            $('sxFinal').textContent = CONFIG.finalNote || '';
            $('sxLetterBtn').textContent = CONFIG.letter.button || 'Read my letter 💌';
            end.hidden = false;
            if (firstTime) {
                try { end.scrollIntoView({ behavior: 'smooth', block: 'center' }); } catch (e) {}
            }
        }
    }

    function openLetter() {
        $('sxLetterTitle').textContent = CONFIG.letter.title || '';
        $('sxLetterBody').textContent  = CONFIG.letter.body || '';
        $('sxLetterFrom').textContent  = CONFIG.letter.from || '';
        $('sxLetterModal').hidden = false;
        $('sxLetterModal').querySelector('.sx-letter').scrollTop = 0;
        $('sxLetterClose').focus();
    }

    // Warning note
    function openNote() {
        $('sxNoteTitle').textContent = CONFIG.note.title || '';
        $('sxNoteText').textContent  = CONFIG.note.text || '';
        $('sxNoteOk').textContent    = CONFIG.note.button || 'OK';
        $('sxNoteModal').hidden = false;
        try { if (navigator.vibrate) navigator.vibrate([250, 120, 250, 120, 400]); } catch (e) {}
        $('sxNoteOk').focus();
    }
    function closeNote() {
        $('sxNoteModal').hidden = true;
        noteShown = true;
        $('sxNoteBtn').hidden = false;
        $('sxBack').hidden = false;
        try { $('sxEnd').scrollIntoView({ behavior: 'smooth', block: 'center' }); } catch (e) {}
    }

    // Closing the letter counts as "read it". The warning note comes next.
    function closeLetter() {
        $('sxLetterModal').hidden = true;
        letterRead = true;
        if (CONFIG.note && !noteShown) {
            openNote();
        } else {
            $('sxBack').hidden = false;
            $('sxLetterBtn').focus();
        }
    }

    function openLeave()  { $('sxLeaveModal').hidden = false; $('sxStay').focus(); }
    function closeLeave() { $('sxLeaveModal').hidden = true; }
    function leaveNow() {
        window.location.href = (window.LQ_CONF && window.LQ_CONF.backUrl) || '/dashboard';
    }

    // Called by the game when it ends (or by test mode).
    window.showBirthdaySurprise = function () {
        var root = $('sx');
        if (!root) return;
        opened = 0;
        letterRead = false;
        noteShown = false;
        root.hidden = false;
        document.body.style.overflow = 'hidden';
        $('sxLock').hidden = false;
        $('sxBouquetStep').hidden = true;
        $('sxEnd').hidden = true;
        $('sxBack').hidden = true;
        $('sxNoteBtn').hidden = true;
        $('sxModal').hidden = true;
        $('sxLetterModal').hidden = true;
        $('sxNoteModal').hidden = true;
        $('sxLeaveModal').hidden = true;
        $('sxGift').classList.remove('open');
        $('sxError').hidden = true;
        $('sxHint').hidden = !CONFIG.hint;
        $('sxHint').textContent = CONFIG.hint;
        startPetals();
        startMusic();
        setupCode();
    };

    (function wire() {
        var c  = $('sxClose'),       m  = $('sxModal');
        var lc = $('sxLetterClose'), lm = $('sxLetterModal'), lb = $('sxLetterBtn');
        var bk = $('sxBack'),        vm = $('sxLeaveModal'),  st = $('sxStay'), lv = $('sxLeave');
        var nb = $('sxNoteBtn'),     nm = $('sxNoteModal'),   no = $('sxNoteOk');
        var mu = $('sxMusic');

        if (c)  c.onclick  = closeModal;
        if (m)  m.onclick  = function (e) { if (e.target === m)  closeModal(); };
        if (lb) lb.onclick = openLetter;
        if (lc) lc.onclick = closeLetter;
        if (lm) lm.onclick = function (e) { if (e.target === lm) closeLetter(); };
        if (nb) nb.onclick = openNote;
        if (no) no.onclick = closeNote;          // the note can only be closed with its button
        if (bk) bk.onclick = openLeave;
        if (st) st.onclick = closeLeave;
        if (lv) lv.onclick = leaveNow;
        if (vm) vm.onclick = function (e) { if (e.target === vm) closeLeave(); };
        if (mu) mu.onclick = toggleMusic;

        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') return;
            if (nm && !nm.hidden) return;        // ignore Esc while the warning note is open
            if (vm && !vm.hidden) closeLeave();
            else if (lm && !lm.hidden) closeLetter();
            else if (m && !m.hidden) closeModal();
        });
    })();
})();
</script>