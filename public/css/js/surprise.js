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

        tulips: [
            { message: 'Write your first message here.',  image: '/css/assets/surprise/1.jpg' },
            { message: 'Write your second message here.', image: '/css/assets/surprise/2.jpg' },
            { message: 'Write your third message here.',  image: '/css/assets/surprise/3.jpg' },
            { message: 'Write your fourth message here.', image: '/css/assets/surprise/4.jpg' },
            { message: 'Write your fifth message here.',  image: '/css/assets/surprise/5.jpg' }
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

    document.addEventListener('DOMContentLoaded', function () {
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
    });
})();