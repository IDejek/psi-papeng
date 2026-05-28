/**
 * PSI Papua Pegunungan — Main JavaScript
 * @version 1.0.0
 * @author Iqbal Tombinawa
 */
(function() {
    'use strict';

    /* ═══ REAL-TIME CLOCKS ════════════════════════════════════ */
    function updateClocks() {
        const now = new Date();

        /* WIT (UTC+9) */
        const wit = new Date(now.getTime() + (9 * 60 * 60 * 1000) + (now.getTimezoneOffset() * 60 * 1000));
        const witEl = document.getElementById('witClock');
        if (witEl) witEl.textContent = wit.toTimeString().slice(0, 8);

        /* WIB (UTC+7) */
        const wib = new Date(now.getTime() + (7 * 60 * 60 * 1000) + (now.getTimezoneOffset() * 60 * 1000));
        const wibEl = document.getElementById('wibClock');
        if (wibEl) wibEl.textContent = wib.toTimeString().slice(0, 8);
    }
    updateClocks();
    setInterval(updateClocks, 1000);

    /* ═══ MOBILE MENU ════════════════════════════════════════ */
    const mobileToggle = document.getElementById('psiMobileToggle');
    const mobileMenu = document.getElementById('psiMobileMenu');
    const mobileIcon = document.getElementById('psiMobileIcon');

    if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener('click', function() {
            const isOpen = !mobileMenu.classList.contains('hidden');
            if (isOpen) {
                mobileMenu.classList.add('hidden');
                mobileIcon.classList.remove('fa-times');
                mobileIcon.classList.add('fa-bars');
                mobileToggle.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            } else {
                mobileMenu.classList.remove('hidden');
                mobileIcon.classList.remove('fa-bars');
                mobileIcon.classList.add('fa-times');
                mobileToggle.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = 'hidden';
            }
        });
    }

    /* ═══ NAVBAR SCROLL ═════════════════════════════════════ */
    const navbar = document.getElementById('psiNavbar');
    let lastScroll = 0;
    window.addEventListener('scroll', function() {
        if (!navbar) return;
        const current = window.pageYOffset;
        if (current > 100) {
            navbar.classList.add('shadow-xl');
            navbar.style.top = current > lastScroll && current > 300 ? '-100%' : '0';
        } else {
            navbar.classList.remove('shadow-xl');
            navbar.style.top = '0';
        }
        lastScroll = current;
    }, { passive: true });

    /* ═══ BACK TO TOP ═══════════════════════════════════════ */
    const backToTop = document.getElementById('psiBackToTop');
    if (backToTop) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 500) {
                backToTop.classList.remove('opacity-0', 'invisible');
                backToTop.classList.add('opacity-100', 'visible');
            } else {
                backToTop.classList.add('opacity-0', 'invisible');
                backToTop.classList.remove('opacity-100', 'visible');
            }
        }, { passive: true });
        backToTop.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    /* ═══ HERO SLIDER ════════════════════════════════════════ */
    const slides = document.querySelectorAll('.psi-slide');
    const dotsContainer = document.getElementById('psiSliderDots');
    const prevBtn = document.getElementById('psiSliderPrev');
    const nextBtn = document.getElementById('psiSliderNext');

    if (slides.length > 1 && dotsContainer) {
        let currentSlide = 0;
        let slideInterval;

        /* Create dots */
        slides.forEach(function(_, i) {
            const dot = document.createElement('button');
            dot.className = 'w-3 h-3 rounded-full transition-all duration-300 ' + (i === 0 ? 'bg-white w-8' : 'bg-white/40');
            dot.setAttribute('aria-label', 'Slide ' + (i + 1));
            dot.addEventListener('click', function() { goToSlide(i); });
            dotsContainer.appendChild(dot);
        });

        function goToSlide(index) {
            slides[currentSlide].classList.remove('opacity-100', 'z-10');
            slides[currentSlide].classList.add('opacity-0', 'z-0');
            currentSlide = (index + slides.length) % slides.length;
            slides[currentSlide].classList.remove('opacity-0', 'z-0');
            slides[currentSlide].classList.add('opacity-100', 'z-10');
            updateDots();
            resetInterval();
        }

        function updateDots() {
            const dots = dotsContainer.querySelectorAll('button');
            dots.forEach(function(d, i) {
                d.className = 'h-3 rounded-full transition-all duration-300 ' + (i === currentSlide ? 'bg-white w-8' : 'bg-white/40 w-3');
            });
        }

        function resetInterval() {
            clearInterval(slideInterval);
            slideInterval = setInterval(function() { goToSlide(currentSlide + 1); }, 5000);
        }

        if (prevBtn) prevBtn.addEventListener('click', function() { goToSlide(currentSlide - 1); });
        if (nextBtn) nextBtn.addEventListener('click', function() { goToSlide(currentSlide + 1); });
        resetInterval();
    }

    /* ═══ SCROLL ANIMATIONS ══════════════════════════════════ */
    const animElements = document.querySelectorAll('.psi-animate');
    if (animElements.length && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
        animElements.forEach(function(el) { observer.observe(el); });
    } else {
        animElements.forEach(function(el) { el.classList.add('visible'); });
    }

    /* ═══ VIDEO MODAL ════════════════════════════════════════ */
    const videoModal = document.getElementById('psiVideoModal');
    const videoIframe = document.getElementById('psiVideoIframe');
    const videoClose = document.getElementById('psiVideoModalClose');

    document.querySelectorAll('.psi-video-card [data-video-url]').forEach(function(card) {
        card.addEventListener('click', function() {
            const url = card.getAttribute('data-video-url');
            if (url && videoIframe && videoModal) {
                videoIframe.src = url + (url.indexOf('?') > -1 ? '&autoplay=1' : '?autoplay=1');
                videoModal.classList.remove('hidden');
                videoModal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        });
    });

    function closeVideoModal() {
        if (videoIframe) videoIframe.src = '';
        if (videoModal) {
            videoModal.classList.add('hidden');
            videoModal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    }
    if (videoClose) videoClose.addEventListener('click', closeVideoModal);
    if (videoModal) videoModal.addEventListener('click', function(e) { if (e.target === videoModal) closeVideoModal(); });

    /* ═══ LEADER MODAL ═══════════════════════════════════════ */
    const leaderModal = document.getElementById('psiLeaderModal');
    const leaderContent = document.getElementById('psiLeaderModalContent');
    const leaderClose = document.getElementById('psiLeaderModalClose');

    document.querySelectorAll('.psi-leader-card[data-leader-id]').forEach(function(card) {
        card.addEventListener('click', function() {
            const id = card.getAttribute('data-leader-id');
            if (!id || !leaderModal || !leaderContent) return;

            /* Fetch leader data via AJAX */
            fetch(psiAjax.ajaxurl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=psi_get_leader&nonce=' + psiAjax.nonce + '&leader_id=' + encodeURIComponent(id)
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.success) {
                    const d = res.data;
                    leaderContent.innerHTML =
                        '<div class="aspect-[4/5] bg-gradient-to-br from-gray-200 to-gray-300 relative overflow-hidden rounded-t-2xl">' +
                            (d.thumb ? '<img src="' + d.thumb + '" alt="' + d.name + '" class="w-full h-full object-cover">' : '<div class="w-full h-full flex items-center justify-center"><i class="fas fa-user-tie text-5xl text-gray-400"></i></div>') +
                        '</div>' +
                        '<div class="p-6">' +
                            '<h3 class="text-xl font-bold text-gray-900 mb-1">' + d.name + '</h3>' +
                            '<p class="text-red-600 font-semibold mb-4">' + d.position + '</p>' +
                            (d.excerpt ? '<p class="text-gray-600 text-sm leading-relaxed mb-4">' + d.excerpt + '</p>' : '') +
                            '<a href="' + d.permalink + '" class="inline-flex items-center gap-2 text-red-600 font-semibold text-sm hover:text-red-700">Lihat Profil Lengkap <i class="fas fa-arrow-right text-xs"></i></a>' +
                        '</div>';
                    leaderModal.classList.remove('hidden');
                    leaderModal.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                }
            })
            .catch(function() {});
        });
    });

    function closeLeaderModal() {
        if (leaderModal) {
            leaderModal.classList.add('hidden');
            leaderModal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    }
    if (leaderClose) leaderClose.addEventListener('click', closeLeaderModal);
    if (leaderModal) leaderModal.addEventListener('click', function(e) { if (e.target === leaderModal) closeLeaderModal(); });

    /* ═══ GALLERY LIGHTBOX ═══════════════════════════════════ */
    const lightbox = document.getElementById('psiLightbox');
    const lightboxImg = document.getElementById('psiLightboxImg');
    const lightboxTitle = document.getElementById('psiLightboxTitle');
    const lightboxClose = document.getElementById('psiLightboxClose');
    const lightboxPrev = document.getElementById('psiLightboxPrev');
    const lightboxNext = document.getElementById('psiLightboxNext');
    let galleryItems = [];
    let galleryIndex = 0;

    document.querySelectorAll('.psi-gallery-item').forEach(function(item, i) {
        galleryItems.push({
            src: item.getAttribute('data-full') || '',
            title: item.getAttribute('data-title') || ''
        });
        item.addEventListener('click', function() {
            galleryIndex = i;
            openLightbox();
        });
    });

    function openLightbox() {
        if (!lightbox || galleryItems.length === 0) return;
        const item = galleryItems[galleryIndex];
        if (lightboxImg) lightboxImg.src = item.src;
        if (lightboxTitle) lightboxTitle.textContent = item.title;
        lightbox.classList.remove('hidden');
        lightbox.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        if (lightbox) {
            lightbox.classList.add('hidden');
            lightbox.classList.remove('flex');
            document.body.style.overflow = '';
        }
    }

    if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
    if (lightbox) lightbox.addEventListener('click', function(e) { if (e.target === lightbox) closeLightbox(); });
    if (lightboxPrev) lightboxPrev.addEventListener('click', function() {
        galleryIndex = (galleryIndex - 1 + galleryItems.length) % galleryItems.length;
        openLightbox();
    });
    if (lightboxNext) lightboxNext.addEventListener('click', function() {
        galleryIndex = (galleryIndex + 1) % galleryItems.length;
        openLightbox();
    });

    /* Keyboard navigation for lightbox/video modal */
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLightbox();
            closeVideoModal();
            closeLeaderModal();
        }
        if (lightbox && !lightbox.classList.contains('hidden')) {
            if (e.key === 'ArrowLeft') { galleryIndex = (galleryIndex - 1 + galleryItems.length) % galleryItems.length; openLightbox(); }
            if (e.key === 'ArrowRight') { galleryIndex = (galleryIndex + 1) % galleryItems.length; openLightbox(); }
        }
    });

    /* ═══ GALLERY FILTER ═════════════════════════════════════ */
    document.querySelectorAll('.psi-gallery-filter').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.psi-gallery-filter').forEach(function(b) { b.classList.remove('active'); });
            btn.classList.add('active');
            const cat = btn.getAttribute('data-cat');
            document.querySelectorAll('.psi-gallery-item').forEach(function(item) {
                if (cat === '0' || item.getAttribute('data-cats').indexOf(cat) > -1) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    /* ═══ DPD SEARCH ═════════════════════════════════════════ */
    const dpdSearch = document.getElementById('psiDpdSearch');
    if (dpdSearch) {
        dpdSearch.addEventListener('input', function() {
            const q = this.value.toLowerCase().trim();
            document.querySelectorAll('.psi-dpd-card').forEach(function(card) {
                const name = card.getAttribute('data-name') || '';
                card.style.display = name.indexOf(q) > -1 ? '' : 'none';
            });
        });
    }

    /* ═══ PROFILE TABS ═══════════════════════════════════════ */
    document.querySelectorAll('.psi-profile-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.psi-profile-tab').forEach(function(t) {
                t.classList.remove('active', 'text-red-600', 'border-red-600');
                t.classList.add('text-gray-500', 'border-transparent');
            });
            tab.classList.add('active', 'text-red-600', 'border-red-600');
            tab.classList.remove('text-gray-500', 'border-transparent');

            const target = tab.getAttribute('data-target');
            document.querySelectorAll('.psi-profile-panel').forEach(function(p) { p.classList.add('hidden'); });
            const panel = document.getElementById(target);
            if (panel) panel.classList.remove('hidden');
        });
    });

    /* ═══ READING PROGRESS BAR ═══════════════════════════════ */
    const progressBar = document.getElementById('psi-reading-progress');
    if (progressBar) {
        window.addEventListener('scroll', function() {
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrolled = (window.pageYOffset / docHeight) * 100;
            progressBar.style.width = Math.min(scrolled, 100) + '%';
        }, { passive: true });
    }

    /* ═══ CONTACT FORM ═══════════════════════════════════════ */
    const contactForm = document.getElementById('psiContactForm');
    const contactResult = document.getElementById('psiContactResult');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(contactForm);
            formData.append('action', 'psi_contact_form');
            formData.append('nonce', psiAjax.nonce);

            const btn = contactForm.querySelector('button[type="submit"]');
            const origText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';

            fetch(psiAjax.ajaxurl, {
                method: 'POST',
                body: formData
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (contactResult) {
                    contactResult.classList.remove('hidden');
                    if (res.success) {
                        contactResult.className = 'p-4 bg-green-50 text-green-700 rounded-xl text-sm font-medium';
                        contactResult.innerHTML = '<i class="fas fa-check-circle mr-2"></i>' + res.data;
                        contactForm.reset();
                    } else {
                        contactResult.className = 'p-4 bg-red-50 text-red-700 rounded-xl text-sm font-medium';
                        contactResult.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i>' + res.data;
                    }
                }
                btn.disabled = false;
                btn.innerHTML = origText;
            })
            .catch(function() {
                if (contactResult) {
                    contactResult.classList.remove('hidden');
                    contactResult.className = 'p-4 bg-red-50 text-red-700 rounded-xl text-sm font-medium';
                    contactResult.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i>Terjadi kesalahan. Silakan coba lagi.';
                }
                btn.disabled = false;
                btn.innerHTML = origText;
            });
        });
    }

    /* ═══ SMOOTH ANCHOR LINKS ════════════════════════════════ */
    document.querySelectorAll('a[href^="#"]').forEach(function(a) {
        a.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

})();
