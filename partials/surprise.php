<div class="sx" id="sx" hidden>
    <div class="sx-petals" id="sxPetals" aria-hidden="true"></div>

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
        <p class="sx-label">Tap each tulip 🌷</p>
        <div class="sx-bouquet" id="sxBouquet"></div>

        <!-- Appears after every tulip has been opened -->
        <div class="sx-end" id="sxEnd" hidden>
            <p class="sx-final" id="sxFinal"></p>
            <button type="button" class="sx-letter-btn" id="sxLetterBtn">Read my letter 💌</button>
            <!-- Appears only after the letter has been read -->
            <button type="button" class="sx-back" id="sxBack" hidden>Back to Life Quest 💗</button>
        </div>
    </section>

    <!-- Tulip message + photo popup -->
    <div class="sx-modal" id="sxModal" hidden>
        <div class="sx-card" role="dialog" aria-modal="true">
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
    align-items: center;
    justify-content: center;
    padding: 24px 16px;
    font-family: 'Poppins', sans-serif;
    color: #6b1f45;
    background: linear-gradient(160deg, #ffe3ee 0%, #ffc1d9 55%, #ff9fc4 100%);
    animation: sxFade 0.8s ease-out;
}
.sx[hidden] { display: none; }
.sx input { user-select: text; -webkit-user-select: text; }

.sx-step { position: relative; z-index: 2; width: 100%; max-width: 420px; text-align: center; }
.sx-step[hidden] { display: none; }

.sx-eyebrow { font-family: 'Press Start 2P', monospace; font-size: 0.8rem; line-height: 1.7; color: #c2185b; margin-bottom: 18px; }
.sx-title   { font-family: 'Press Start 2P', monospace; font-size: 1rem; line-height: 1.7; color: #c2185b; margin-bottom: 10px; }
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

/* Bouquet */
.sx-bouquet {
    position: relative;
    height: 320px;
    margin: 10px auto 0;
    max-width: 340px;
}
.sx-tulip {
    position: absolute;
    bottom: 40px;
    left: 50%;
    width: 64px;
    height: 150px;
    margin-left: -32px;
    padding: 0;
    background: none;
    border: 0;
    cursor: pointer;
    transform-origin: 50% 100%;
    transform: rotate(var(--r)) scaleY(0);
    animation: sxGrow 0.7s cubic-bezier(.2, 1.4, .4, 1) forwards;
    animation-delay: var(--d);
}
.sx-tulip svg { width: 100%; height: 100%; display: block; overflow: visible; filter: drop-shadow(0 3px 4px rgba(122, 31, 75, 0.25)); }
.sx-tulip:hover svg, .sx-tulip:focus-visible svg { filter: drop-shadow(0 0 8px #fff); }
.sx-tulip:focus-visible { outline: 3px solid #c2185b; outline-offset: 4px; border-radius: 10px; }
.sx-tulip.seen::after {
    content: '💗';
    position: absolute; top: -14px; left: 50%;
    transform: translateX(-50%);
    font-size: 1.2rem;
}
.sx-ribbon {
    position: absolute; bottom: 20px; left: 50%;
    width: 110px; height: 34px; margin-left: -55px;
    background: #ff4d94; border: 3px solid #7a1f4b; border-radius: 10px;
}
.sx-ribbon::before {
    content: '🎀'; position: absolute; top: -16px; left: 50%;
    transform: translateX(-50%); font-size: 2rem;
}

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

.sx-letter-btn, .sx-back {
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
.sx-back[hidden] { display: none; }
.sx-letter-btn:active, .sx-back:active { transform: translateY(4px); box-shadow: 0 1px 0 #7a1f4b; }
.sx-letter-btn:focus-visible, .sx-back:focus-visible { outline: 3px solid #c2185b; outline-offset: 4px; }

/* Popups (tulip message, letter, leave confirmation) */
.sx-modal {
    position: fixed; inset: 0; z-index: 5;
    display: flex; align-items: center; justify-content: center;
    padding: 20px;
    background: rgba(122, 31, 75, 0.55);
}
.sx-modal[hidden] { display: none; }
.sx-card {
    position: relative;
    width: 100%; max-width: 360px; max-height: 92vh; overflow-y: auto;
    padding: 18px 18px 22px;
    text-align: center;
    background: #fff;
    border: 3px solid #ff6fa5;
    border-radius: 20px;
    animation: sxPop 0.35s ease-out;
}
.sx-close {
    position: absolute; top: 6px; right: 10px;
    width: 40px; height: 40px;
    font-size: 1.8rem; line-height: 1;
    color: #c2185b; background: none; border: 0; cursor: pointer;
}
.sx-photo { display: block; width: 100%; max-height: 300px; object-fit: cover; margin: 26px 0 14px; border-radius: 14px; }
.sx-photo[hidden] { display: none; }
.sx-msg { line-height: 1.7; white-space: pre-line; }

/* Leave confirmation */
.sx-leave { padding: 26px 20px 24px; }
.sx-leave-title { margin-bottom: 12px; font-size: 1.15rem; color: #c2185b; }
.sx-leave-text { line-height: 1.6; margin-bottom: 20px; }
.sx-leave-actions { display: flex; flex-direction: column; align-items: center; gap: 14px; }

/* Letter */
.sx-letter {
    position: relative;
    width: 100%; max-width: 440px; max-height: 90vh; overflow-y: auto;
    padding: 34px 26px 30px;
    background: #fffaf3;
    border: 3px solid #ff6fa5;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(122, 31, 75, 0.35);
    animation: sxPop 0.4s ease-out;
    -webkit-overflow-scrolling: touch;
}
.sx-letter-title {
    margin-bottom: 18px;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 1.35rem;
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
@keyframes sxGrow   { to { transform: rotate(var(--r)) scaleY(1); } }
@keyframes sxPop    { from { transform: scale(0.85); opacity: 0; } to { transform: scale(1); opacity: 1; } }
@keyframes sxFall   {
    0%   { transform: translate3d(0, 0, 0) rotate(0deg); }
    50%  { transform: translate3d(30px, 55vh, 0) rotate(180deg); }
    100% { transform: translate3d(-20px, 110vh, 0) rotate(360deg); }
}

@media (prefers-reduced-motion: reduce) {
    .sx, .sx-gift, .sx-card, .sx-letter, .sx-end { animation: none; }
    .sx-petals { display: none; }
    .sx-tulip { animation: none; transform: rotate(var(--r)); }
}
</style>

<script>
(function () {
    /* ====== EDIT THIS PART ====== */
    var CONFIG = {
        code: '2716',
        name: 'Her Name',          // her name goes in the title
        hint: '',                  // optional hint under the code, e.g. 'Think of a special date'

        // Short line shown above the letter button (leave '' for none)
        finalNote: 'One last thing for you 💗',

        // The long letter. Write between the backticks. Line breaks and blank lines are kept.
        // Do not type a backtick ( ` ) or the characters ${ inside the letter.
        letter: {
            button: 'Read my letter 💌',
            title: 'To my favorite person,',
            body: `Write your long message here.

You can use as many paragraphs as you like.
Just leave a blank line between them.`,
            from: 'Forever yours,\nYour Name'
        },

        // Photos live in the surprise-photos folder (see instructions). Use the file name after simg=
        tulips: [
            { message: 'Write your first message here.',  image: '/play?simg=1.jpg' },
            { message: 'Write your second message here.', image: '/play?simg=2.jpg' },
            { message: 'Write your third message here.',  image: '/play?simg=3.jpg' },
            { message: 'Write your fourth message here.', image: '/play?simg=4.jpg' },
            { message: 'Write your fifth message here.',  image: '/play?simg=5.jpg' }
        ]
    };
    /* ============================ */

    var $ = function (id) { return document.getElementById(id); };
    var opened = 0;
    var letterRead = false;

    function tulipSVG() {
        return '<svg viewBox="0 0 60 150" aria-hidden="true">' +
            '<path d="M30 62 C30 100 30 125 30 150" stroke="#3f9b5a" stroke-width="5" fill="none" stroke-linecap="round"/>' +
            '<path d="M30 118 C10 110 6 92 8 80 C22 84 30 98 30 118Z" fill="#4caf6a"/>' +
            '<path d="M12 30 C10 8 22 2 30 2 C38 2 50 8 48 30 C46 52 38 64 30 64 C22 64 14 52 12 30Z" fill="#f48fb1"/>' +
            '<path d="M12 30 C14 52 22 64 30 64 C26 48 24 30 22 10 C16 12 12 20 12 30Z" fill="#f06292"/>' +
            '<path d="M48 30 C46 52 38 64 30 64 C34 48 36 30 38 10 C44 12 48 20 48 30Z" fill="#f06292"/>' +
            '<path d="M22 10 C26 2 34 2 38 10 C38 34 34 52 30 64 C26 52 22 34 22 10Z" fill="#f8bbd0"/>' +
            '</svg>';
    }

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

    function openModal(t) {
        var img = $('sxPhoto');
        $('sxMsg').textContent = t.message;
        img.hidden = !t.image;
        if (t.image) {
            img.onerror = function () { img.hidden = true; };
            img.src = t.image;
        }
        $('sxModal').hidden = false;
        $('sxClose').focus();
    }

    function buildBouquet() {
        var box = $('sxBouquet');
        box.innerHTML = '';
        var n = CONFIG.tulips.length;
        var ribbon = document.createElement('div');
        ribbon.className = 'sx-ribbon';

        CONFIG.tulips.forEach(function (t, i) {
            var spread = n > 1 ? (i / (n - 1)) * 2 - 1 : 0;     // -1 ... 1
            var b = document.createElement('button');
            b.type = 'button';
            b.className = 'sx-tulip';
            b.setAttribute('aria-label', 'Open message ' + (i + 1));
            b.style.setProperty('--r', (spread * 26) + 'deg');
            b.style.setProperty('--d', (i * 0.15) + 's');
            b.style.height = (150 + (1 - Math.abs(spread)) * 40) + 'px';
            b.innerHTML = tulipSVG();
            b.onclick = function () {
                if (!b.classList.contains('seen')) {
                    b.classList.add('seen');
                    opened++;
                }
                openModal(t);
            };
            box.appendChild(b);
        });
        box.appendChild(ribbon);
    }

    function showBouquet() {
        $('sxLock').hidden = true;
        $('sxBouquetStep').hidden = false;
        $('sxTitle').textContent = 'Happy Birthday, ' + CONFIG.name + '!';
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

    // Close the tulip popup. When every tulip has been opened, show the letter button.
    function closeModal() {
        $('sxModal').hidden = true;
        if (opened >= CONFIG.tulips.length) {
            $('sxFinal').textContent = CONFIG.finalNote || '';
            $('sxLetterBtn').textContent = CONFIG.letter.button || 'Read my letter 💌';
            $('sxEnd').hidden = false;
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
    // Closing the letter counts as "read it": now the Back button is allowed to appear.
    function closeLetter() {
        $('sxLetterModal').hidden = true;
        letterRead = true;
        $('sxBack').hidden = false;
        $('sxLetterBtn').focus();
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
        root.hidden = false;
        document.body.style.overflow = 'hidden';
        $('sxLock').hidden = false;
        $('sxBouquetStep').hidden = true;
        $('sxEnd').hidden = true;
        $('sxBack').hidden = true;
        $('sxModal').hidden = true;
        $('sxLetterModal').hidden = true;
        $('sxLeaveModal').hidden = true;
        $('sxGift').classList.remove('open');
        $('sxError').hidden = true;
        $('sxHint').hidden = !CONFIG.hint;
        $('sxHint').textContent = CONFIG.hint;
        startPetals();
        setupCode();
    };

    (function wire() {
        var c  = $('sxClose'),       m  = $('sxModal');
        var lc = $('sxLetterClose'), lm = $('sxLetterModal'), lb = $('sxLetterBtn');
        var bk = $('sxBack'),        vm = $('sxLeaveModal'),  st = $('sxStay'), lv = $('sxLeave');

        if (c)  c.onclick  = closeModal;
        if (m)  m.onclick  = function (e) { if (e.target === m)  closeModal(); };
        if (lb) lb.onclick = openLetter;
        if (lc) lc.onclick = closeLetter;
        if (lm) lm.onclick = function (e) { if (e.target === lm) closeLetter(); };
        if (bk) bk.onclick = openLeave;
        if (st) st.onclick = closeLeave;
        if (lv) lv.onclick = leaveNow;
        if (vm) vm.onclick = function (e) { if (e.target === vm) closeLeave(); };

        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') return;
            if (vm && !vm.hidden) closeLeave();
            else if (lm && !lm.hidden) closeLetter();
            else if (m && !m.hidden) closeModal();
        });
    })();
})();
</script>