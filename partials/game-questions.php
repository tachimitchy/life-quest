<script>
// Reads the reviewer (PDF text, or a scanned page with text recognition) and turns it into
// multiple-choice questions. Everything happens in the player's browser.
window.LQQ = (function () {
  var PDFJS   = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js';
  var PDFWORK = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
  var TESS    = 'https://cdn.jsdelivr.net/npm/tesseract.js@5.1.1/dist/tesseract.min.js';

  var STOP = {};
  ('the a an and or but of to in on at for with by from as is are was were be been being this that these those it its into than then so such can could may might will would should have has had not no yes you your we our they their there here also more most other some any each both which who whom whose what when where why how if about over under between within without through during before after above below up down out off again further once only own same very just'
  ).split(' ').forEach(function (w) { STOP[w] = 1; });

  function loadScript(src) {
    return new Promise(function (res, rej) {
      var s = document.createElement('script'); s.src = src; s.onload = res;
      s.onerror = function () { rej(new Error('Could not load ' + src)); };
      document.head.appendChild(s);
    });
  }

  // ---------- reading the file ----------
  async function pdfText(url, status) {
    await loadScript(PDFJS);
    window.pdfjsLib.GlobalWorkerOptions.workerSrc = PDFWORK;
    var pdf = await window.pdfjsLib.getDocument({ url: url }).promise;
    var out = [], n = Math.min(pdf.numPages, 40);
    for (var p = 1; p <= n; p++) {
      status('Reading page ' + p + ' of ' + n + '...');
      var tc = await (await pdf.getPage(p)).getTextContent();
      var line = '', lastY = null;
      tc.items.forEach(function (it) {
        var y = it.transform[5];
        if (lastY !== null && Math.abs(y - lastY) > 3) { out.push(line); line = ''; }
        line += it.str; lastY = y;
      });
      out.push(line);
    }
    return { text: out.join('\n'), pdf: pdf };
  }
  async function ocrPdf(pdf, status) {
    await loadScript(TESS);
    var n = Math.min(pdf.numPages, 5), t = [];
    for (var p = 1; p <= n; p++) {
      status('Reading scanned page ' + p + ' of ' + n + ' (this can take a minute)...');
      var page = await pdf.getPage(p), vp = page.getViewport({ scale: 1.6 });
      var c = document.createElement('canvas'); c.width = vp.width; c.height = vp.height;
      await page.render({ canvasContext: c.getContext('2d'), viewport: vp }).promise;
      t.push((await window.Tesseract.recognize(c, 'eng')).data.text);
    }
    return t.join('\n');
  }
  async function ocrImage(url, status) {
    await loadScript(TESS);
    status('Reading the scanned image (this can take a minute)...');
    return (await window.Tesseract.recognize(url, 'eng')).data.text;
  }

  // ---------- helpers ----------
  function shuffle(a) { for (var i = a.length - 1; i > 0; i--) { var j = Math.floor(Math.random() * (i + 1)); var t = a[i]; a[i] = a[j]; a[j] = t; } return a; }
  function clip(s, n) {
    s = String(s).replace(/\s+/g, ' ').trim();
    if (s.length <= n) return s;
    var c = s.slice(0, n), i = c.lastIndexOf(' ');
    return (i > n * 0.6 ? c.slice(0, i) : c).replace(/[,;:\-\u2013]+$/, '') + '\u2026';
  }
  function lines(text) {
    var raw = String(text).replace(/\r/g, '').split('\n')
      .map(function (l) { return l.replace(/\s+/g, ' ').trim(); })
      .filter(function (l) { return l.length > 3 && !/^(page\s*)?\d+(\s*of\s*\d+)?$/i.test(l); });
    var out = [];
    raw.forEach(function (l) {
      var prev = out[out.length - 1];
      if (prev && prev.length > 25 && !/[.:;?!]$/.test(prev) && /^[a-z]/.test(l)) out[out.length - 1] = prev + ' ' + l;
      else out.push(l);
    });
    return out;
  }
  function mc(q, correct, pool) {
    var seen = {}; seen[correct.toLowerCase()] = 1;
    var wrong = [];
    shuffle(pool.slice()).forEach(function (w) {
      var k = w.toLowerCase();
      if (wrong.length < 3 && !seen[k]) { seen[k] = 1; wrong.push(w); }
    });
    if (wrong.length < 3) return null;
    var opts = shuffle([correct].concat(wrong));
    return { q: q, options: opts, answer: opts.indexOf(correct) };
  }
  function keywords(s) {
    var words = s.replace(/[(),;:"]/g, ' ').split(/\s+/), out = [];
    words.forEach(function (w, i) {
      var b = w.replace(/^[^A-Za-z0-9]+|[^A-Za-z0-9]+$/g, '');
      if (b.length >= 5 && !STOP[b.toLowerCase()] && (/^[A-Za-z][a-z\-]+$/.test(b) || /^\d{2,}$/.test(b))) out.push(b);
    });
    return out;
  }

  // ---------- question making ----------
  var DEF = /^(.{3,45}?)\s+(?:is|are|refers to|means|is called|are called|is known as|can be defined as)\s+(.{12,})$/i;
  var PRON = /^(this|that|it|there|these|those|they|we|you|he|she|which|what)\b/i;

  function build(text) {
    var ls = lines(text), qas = [], defs = [], sents = [];
    for (var i = 0; i < ls.length; i++) {
      var l = ls[i], m, m2;
      m = /^(?:Q\d*[:.)]\s*)?(.{8,160}\?)\s*$/.exec(l);
      if (m && ls[i + 1] && (m2 = /^(?:A\d*[:.)]|Ans(?:wer)?\s*[:.]?)\s*(.{2,200})$/i.exec(ls[i + 1]))) { qas.push({ q: m[1], a: m2[1] }); i++; continue; }
      var t = l.replace(/^[\u2022\u25CF\u25AA\-\*\d.)\s]+/, '');
      m = /^([^:\u2013\u2014=]{2,60}?)\s*[:=]\s*(.{8,220})$/.exec(t) || /^(.{2,50}?)\s+[\u2013\u2014-]\s+(.{8,220})$/.exec(t);
      if (m && m[1].split(' ').length <= 7 && !/[.?!]$/.test(m[1])) { defs.push({ term: m[1].trim(), def: m[2].trim() }); continue; }
      (l.match(/[^.!?]+[.!?]*/g) || []).forEach(function (x) {
        x = x.trim(); if (x.length >= 40 && x.length <= 240 && /[a-z]/.test(x)) sents.push(x);
      });
    }
    sents.forEach(function (s) {
      var d = DEF.exec(s);
      if (d && !PRON.test(d[1])) defs.push({ term: d[1].replace(/^(the|a|an)\s+/i, '').trim(), def: d[2].replace(/[.]+$/, '').trim() });
    });
    var seenT = {}; defs = defs.filter(function (d) { var k = d.term.toLowerCase(); if (seenT[k]) return false; seenT[k] = 1; return true; });

    var qa = [], df = [], cz = [];
    var qaAns = qas.map(function (x) { return clip(x.a, 90); });
    qas.forEach(function (x) { var q = mc(x.q, clip(x.a, 90), qaAns); if (q) qa.push(q); });

    var terms = defs.map(function (d) { return clip(d.term, 40); });
    var shortDefs = defs.map(function (d) { return clip(d.def, 90); });
    defs.forEach(function (d) {
      var q = (d.def.length <= 80 && Math.random() < 0.3)
        ? mc('What is ' + clip(d.term, 50) + '?', clip(d.def, 90), shortDefs)
        : mc('Which term matches: "' + clip(d.def, 140) + '"?', clip(d.term, 40), terms);
      if (q) df.push(q);
    });

    var kwAll = [];
    sents.forEach(function (s) { kwAll = kwAll.concat(keywords(s)); });
    var kwUniq = kwAll.filter(function (w, i) { return kwAll.indexOf(w) === i; });
    shuffle(sents.slice()).forEach(function (s) {
      var ks = keywords(s); if (!ks.length) return;
      var k = ks[Math.floor(Math.random() * ks.length)];
      var q = mc('Fill in the blank: ' + s.replace(k, '_____'), k, kwUniq);
      if (q) cz.push(q);
    });

    var all = [], buckets = [shuffle(qa), shuffle(df), cz], seenQ = {};
    var more = true;
    while (more) {
      more = false;
      buckets.forEach(function (b) { if (b.length) { more = true; var q = b.shift(); if (!seenQ[q.q]) { seenQ[q.q] = 1; all.push(q); } } });
    }
    return all.slice(0, 40);
  }

  var DEMO = [
    'Heart: The organ that pumps blood throughout the body.', 'Lungs: The organs that let us breathe in oxygen and breathe out carbon dioxide.',
    'Brain: The organ that controls thinking, memory, and movement.', 'Stomach: The organ that breaks down food with strong acids.',
    'Skeleton: The frame of bones that supports and protects the body.', 'Kidneys: The organs that filter waste from the blood to make urine.',
    'Liver: The largest internal organ, which cleans the blood and helps digestion.', 'Skin: The largest organ, which covers and protects the body.',
    'Muscles: Tissues that contract to help the body move.', 'Esophagus: The tube that carries food from the mouth to the stomach.',
    'Small intestine: The long tube where most nutrients from food are absorbed.', 'Large intestine: The part of the digestive system that absorbs water.',
    'Spinal cord: The bundle of nerves that carries messages between the brain and the body.', 'Pancreas: The organ that makes insulin and digestive juices.',
    'Bladder: The organ that stores urine until it leaves the body.', 'Femur: The longest and strongest bone in the human body.',
    'Skull: The set of bones that protects the brain.', 'Ribs: The curved bones that protect the heart and lungs.',
    'Red blood cells: Cells that carry oxygen to every part of the body.', 'Pupil: The dark opening in the eye that lets light in.'
  ].join('\n');

  return { pdfText: pdfText, ocrPdf: ocrPdf, ocrImage: ocrImage, build: build, demoText: DEMO };
})();
</script>