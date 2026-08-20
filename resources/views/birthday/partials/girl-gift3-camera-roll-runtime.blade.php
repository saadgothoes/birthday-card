    }

    function createLetterCard(m) {
        const el = document.createElement('div');
        el.innerHTML = `
            <div class="letter-card" id="letterTrigger" data-letter="${encodeURIComponent(m.text || '')}">
                <div class="letter-fold">
                    <div class="letter-seal">♥</div>
                    <p>A letter, just for you.</p>
                    <p style="font-size:11px; letter-spacing:2px; text-transform:uppercase; opacity:.7;">Tap to open</p>
                </div>
            </div>`;
        return el.firstElementChild;
    }

    function buildCard(m) {
        switch (m.type) {
            case 'photo': return createPhotoCard(m);
            case 'video': return createVideoCard(m);
            case 'chat': return createChatCard(m);
            case 'letter': return createLetterCard(m);
            default: return document.createElement('div');
        }
    }

    MEMORY_DATA.forEach(m => {
        const wrap = document.createElement('div');
        wrap.className = 'memory';
        wrap.appendChild(buildCard(m));
        reel.appendChild(wrap);
    });

    const endMark = document.createElement('div');
    endMark.className = 'end-mark';
    endMark.textContent = '— end of roll —';
    reel.appendChild(endMark);

    const cover = document.getElementById('cover');
    const gallery = document.getElementById('gallery');
    const memories = Array.from(document.querySelectorAll('.memory'));

    function updateCounter(shown) {
        counterEl.textContent = shown + ' / ' + memories.length + ' memories';
    }

    function revealMemoriesSequentially() {
        let i = 0;
        updateCounter(0);
        const step = () => {
            if (i >= memories.length) return;
            memories[i].classList.add('show');
            i++;
            updateCounter(i);
            if (i < memories.length) setTimeout(step, 420);
        };
        setTimeout(step, 250);
    }

    function openGallery() {
        cover.classList.add('hidden');
        gallery.classList.add('active');
        revealMemoriesSequentially();
    }

    cover.addEventListener('click', openGallery);
    cover.addEventListener('keyup', e => {
        if (e.key === 'Enter' || e.key === ' ') openGallery();
    });

    const viewer = document.getElementById('viewer');
    const viewerInner = document.getElementById('viewerInner');
    const viewerClose = document.getElementById('viewerClose');
    const videoModal = document.getElementById('videoModal');
    const modalVideo = document.getElementById('modalVideo');
    const videoClose = document.getElementById('videoClose');
    const letterOverlay = document.getElementById('letterOverlay');
    const letterBody = document.getElementById('letterBody');
    const letterSignoff = document.getElementById('letterSignoff');
    const letterClose = document.getElementById('letterClose');
    let letterTyped = false;

    function openViewer(kind, sourceEl) {
        viewerInner.innerHTML = '';
        if (kind === 'photo') {
            const img = document.createElement('img');
            img.src = sourceEl.dataset.src;
            viewerInner.appendChild(img);
        } else if (kind === 'chat') {
            viewerInner.appendChild(sourceEl.cloneNode(true));
        }
        viewer.classList.add('open');
    }

    function closeViewer() {
        viewer.classList.remove('open');
    }

    function openVideo(src, poster) {
        modalVideo.pause();
        modalVideo.src = src || '';
        if (poster) modalVideo.poster = poster;
        videoModal.classList.add('open');
        if (src) modalVideo.play().catch(() => {});
    }

    function closeVideo() {
        videoModal.classList.remove('open');
        modalVideo.pause();
    }

    reel.addEventListener('click', e => {
        const openTarget = e.target.closest('[data-open]');
        if (openTarget) {
            const kind = openTarget.dataset.open;
            if (kind === 'photo') openViewer('photo', openTarget);
            if (kind === 'chat') openViewer('chat', openTarget);
            if (kind === 'video') openVideo(openTarget.dataset.src, openTarget.querySelector('img')?.src);
            return;
        }

        const trigger = e.target.closest('#letterTrigger, [data-letter]');
        if (!trigger) return;
        const text = decodeURIComponent(trigger.dataset.letter || '');
        letterOverlay.classList.add('open');
        letterSignoff.classList.remove('show');
        if (letterTyped) {
            letterBody.textContent = text;
            letterSignoff.classList.add('show');
            return;
        }
        letterTyped = true;
        letterBody.innerHTML = '';
        const caret = document.createElement('span');
        caret.className = 'pen-caret';
        const textNode = document.createTextNode('');
        letterBody.append(textNode, caret);
        let i = 0;
        const type = () => {
            if (i >= text.length) {
                caret.remove();
                letterSignoff.classList.add('show');
                return;
            }
            textNode.textContent += text[i++];
            setTimeout(type, 40);
        };
        setTimeout(type, 500);
    });

    viewerClose.addEventListener('click', closeViewer);
    viewer.addEventListener('click', e => { if (e.target === viewer) closeViewer(); });
    videoClose.addEventListener('click', closeVideo);
    videoModal.addEventListener('click', e => { if (e.target === videoModal) closeVideo(); });
    letterClose.addEventListener('click', () => letterOverlay.classList.remove('open'));
    letterOverlay.addEventListener('click', e => {
        if (e.target === letterOverlay) letterOverlay.classList.remove('open');
    });

    (function previewCard() {
        const raw = @json(request('preview_card'));
        if (raw === null || raw === undefined || raw === '') return;
        const index = parseInt(raw, 10);
        if (isNaN(index) || index < 0) return;

        cover.classList.add('hidden');
        gallery.classList.add('active');
        memories.forEach(m => m.classList.add('show'));
        updateCounter(memories.length);

        const target = memories[Math.min(index, memories.length - 1)];
        if (target) requestAnimationFrame(() => target.scrollIntoView({ block: 'center' }));
    })();
