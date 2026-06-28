/* ============================================================================
   DUNGEONS FOR DUMMIES — JS PRINCIPAL
   ============================================================================ */

/* ----- MENÚ LATERAL MÓVIL ----- */
function toggleMenu() {
    document.getElementById('sidebar')?.classList.toggle('open');
    document.getElementById('overlay')?.classList.toggle('active');
}

function cerrarMenu() {
    document.getElementById('sidebar')?.classList.remove('open');
    document.getElementById('overlay')?.classList.remove('active');
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.sidebar nav a').forEach(a => {
        a.addEventListener('click', cerrarMenu);
    });

    // Auto-ocultar alertas tras 5s
    document.querySelectorAll('.alerta-exito, .alerta-info').forEach(al => {
        setTimeout(() => {
            al.style.transition = 'opacity 0.5s, transform 0.5s';
            al.style.opacity = '0';
            al.style.transform = 'translateY(-10px)';
            setTimeout(() => al.remove(), 500);
        }, 5000);
    });
});

/* Exponer para onclick inline */
window.toggleMenu = toggleMenu;
window.cerrarMenu = cerrarMenu;

/* ============================================================================
   CAROUSEL DE PERSONAJES (cinematográfico)
   Se auto-inicializa si encuentra #carouselScene
   ============================================================================ */
(function initCarousel() {
    document.addEventListener('DOMContentLoaded', () => {
        const scene = document.getElementById('carouselScene');
        if (!scene) return;

        const TOTAL = parseInt(scene.dataset.total || '0', 10);
        if (TOTAL < 2) return;

        const CARD_W  = 300;
        const SCALE_1 = 0.78;
        const SCALE_2 = 0.62;
        let current = 0;

        function centroScene() {
            return scene.offsetWidth / 2 - CARD_W / 2;
        }

        function sepLateral1() {
            const pct = (TOTAL === 2) ? 0.28 : 0.32;
            return Math.round(scene.offsetWidth * pct);
        }

        function sepLateral2() {
            return Math.round(scene.offsetWidth * 0.50);
        }

        function diffRel(i) {
            let d = ((i - current) % TOTAL + TOTAL) % TOTAL;
            if (d > TOTAL / 2) d -= TOTAL;
            return d;
        }

        function render() {
            const cx = centroScene();
            const s1 = sepLateral1();
            const s2 = sepLateral2();

            for (let i = 0; i < TOTAL; i++) {
                const slide = document.getElementById('slide-' + i);
                if (!slide) continue;
                const d = diffRel(i);

                slide.classList.remove('es-centro','es-lateral1','es-lateral2','es-oculta');

                let left, scale, clase;

                if (d === 0) {
                    left = cx; scale = 1; clase = 'es-centro';
                    slide.onclick = null;
                } else if (Math.abs(d) === 1) {
                    left = cx + d * s1; scale = SCALE_1; clase = 'es-lateral1';
                    const idx = i; slide.onclick = () => irA(idx);
                } else if (Math.abs(d) === 2) {
                    left = cx + Math.sign(d) * s2; scale = SCALE_2; clase = 'es-lateral2';
                    const idx = i; slide.onclick = () => irA(idx);
                } else {
                    left = d > 0 ? cx + 9999 : cx - 9999; scale = 0.3; clase = 'es-oculta';
                    slide.onclick = null;
                }

                slide.classList.add(clase);
                slide.style.left = left + 'px';
                slide.style.transform = `scale(${scale})`;
                slide.style.transformOrigin = 'center center';
            }

            document.querySelectorAll('.carousel-dot').forEach((dot, i) => {
                dot.classList.toggle('active', i === current);
            });
        }

        function moverSlide(dir) {
            current = ((current + dir) % TOTAL + TOTAL) % TOTAL;
            render();
        }

        function irA(idx) {
            current = idx;
            render();
        }

        window.moverSlide = moverSlide;
        window.irA = irA;

        // Swipe
        let startX = 0;
        scene.addEventListener('touchstart', e => { startX = e.touches[0].clientX; }, { passive: true });
        scene.addEventListener('touchend', e => {
            if (Math.abs(startX - e.changedTouches[0].clientX) > 45)
                moverSlide(startX > e.changedTouches[0].clientX ? 1 : -1);
        }, { passive: true });

        // Teclado
        document.addEventListener('keydown', e => {
            if (e.key === 'ArrowLeft')  moverSlide(-1);
            if (e.key === 'ArrowRight') moverSlide(1);
        });

        window.addEventListener('resize', render);
        render();
    });
})();