/* ============================================================================
   DADO D20 3D (widget flotante de Inicio)
   Se carga de forma diferida desde js/pages/inicio.js: three.js (≈500 KB sin
   comprimir) NO bloquea el primer pintado y solo viaja a quien ve esta página.

   Lógica de animación y texturas = la versión afinada del widget original,
   sin cambios de comportamiento. Añadidos: pausa fuera de pantalla, reposo a
   ~20 fps, respeto de "reducir movimiento" y degradación sin WebGL.
   ============================================================================ */
import {
    AmbientLight,
    BufferAttribute,
    CanvasTexture,
    Color,
    DirectionalLight,
    EdgesGeometry,
    FrontSide,
    IcosahedronGeometry,
    LineBasicMaterial,
    LineSegments,
    MathUtils,
    Mesh,
    MeshStandardMaterial,
    NoToneMapping,
    PerspectiveCamera,
    Quaternion,
    Scene,
    Vector3,
    WebGLRenderer,
} from 'three';

/** Muestra el resultado y, si es 1 o 20, el aviso a pantalla completa. */
function mostrarResultado({ container, badge, critOverlay, critText }, resultado) {
    badge.textContent = resultado;
    container.classList.add('result-show');
    window.clearTimeout(container._badgeHideTimer);
    container._badgeHideTimer = window.setTimeout(() => {
        container.classList.remove('result-show');
    }, 2000);

    if (resultado === 20 || resultado === 1) {
        critText.textContent = resultado === 20 ? '¡CRÍTICO!' : '¡PIFIA!';
        critOverlay.classList.toggle('fail', resultado === 1);
        critOverlay.classList.add('show');
        window.clearTimeout(critOverlay._hideTimer);
        critOverlay._hideTimer = window.setTimeout(() => {
            critOverlay.classList.remove('show');
        }, 2200);
    }
}

/** Respaldo sin WebGL: el marcador SVG se queda y el dado devuelve un número. */
function iniciarSinWebGL(els) {
    const { container, critOverlay } = els;
    let bloqueado = false;
    const lanzar = () => {
        if (bloqueado) return;
        bloqueado = true;
        container.classList.remove('result-show');
        critOverlay.classList.remove('show', 'fail');
        window.setTimeout(() => {
            mostrarResultado(els, Math.floor(Math.random() * 20) + 1);
            bloqueado = false;
        }, 350);
    };
    container.addEventListener('click', (e) => {
        e.stopPropagation();
        lanzar();
    });
    container.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            lanzar();
        }
    });
    critOverlay.addEventListener('click', () => critOverlay.classList.remove('show'));
    return { lanzar };
}

/**
 * Monta el dado dentro de #d20-container.
 * @param {HTMLElement} container
 * @returns {{lanzar: () => void} | null}
 */
export function iniciarD20(container) {
    if (container.dataset.d20Init) return null;
    container.dataset.d20Init = '1';

    const wrapper = document.getElementById('d20-canvas-wrapper');
    const badge = document.getElementById('result-badge');
    const shadow = document.getElementById('d20-shadow');
    const critOverlay = document.getElementById('d20-crit-overlay');
    const critText = document.getElementById('d20-crit-text');
    let isRolling = false;
    const elementos = { container, badge, critOverlay, critText };

    // --- Escena y cámara ---
    const scene = new Scene();
    const camera = new PerspectiveCamera(35, 1, 0.1, 1000);
    camera.position.set(0.2, 0.3, 5.2);
    camera.lookAt(0, 0, 0);

    let renderer;
    try {
        renderer = new WebGLRenderer({
            antialias: true,
            alpha: true,
            powerPreference: 'high-performance',
        });
    } catch (err) {
        return iniciarSinWebGL({ container, badge, critOverlay, critText });
    }
    renderer.setSize(100, 100);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setClearColor(0x000000, 0);
    renderer.toneMapping = NoToneMapping;
    wrapper.appendChild(renderer.domElement);
    wrapper.querySelector('.d20-placeholder')?.remove();

    // --- Luces ---
    scene.add(new AmbientLight(0x404040, 0.8));

    const mainLight = new DirectionalLight(0xffffff, 1.8);
    mainLight.position.set(5, 8, 6);
    scene.add(mainLight);

    const fillLight = new DirectionalLight(0xcc88ff, 0.6);
    fillLight.position.set(-4, 1, -5);
    scene.add(fillLight);

    const rimLight = new DirectionalLight(0xffffff, 0.4);
    rimLight.position.set(-2, 4, -4);
    scene.add(rimLight);

    // --- Textura de cara: rojo (algo más oscuro) con veteado marmoleado
    //     interior + número dorado, dibujado dentro del mismo triángulo
    //     UV que se usa abajo para cada cara ---
    function createFaceTexture(number) {
        const SIZE = 512;
        const canvas = document.createElement('canvas');
        canvas.width = SIZE;
        canvas.height = SIZE;
        const ctx = canvas.getContext('2d');

        // Rojo base, un punto más oscuro que antes (#cc0000 -> #a80404)
        ctx.fillStyle = '#a80404';
        ctx.fillRect(0, 0, SIZE, SIZE);

        // --- Veteado marmoleado interior ---
        // Varias "vetas" curvas semitransparentes, más claras y más
        // oscuras que el rojo base, para dar sensación de dado macizo
        // con textura interna (como una piedra/resina veteada).
        const veinColors = [
            'rgba(210, 40, 40, 0.35)',   // veta clara
            'rgba(255, 120, 100, 0.18)', // veta clara cálida
            'rgba(70, 0, 0, 0.35)',      // veta oscura
            'rgba(40, 0, 0, 0.25)',      // veta muy oscura
        ];
        for (let i = 0; i < 9; i++) {
            ctx.strokeStyle = veinColors[i % veinColors.length];
            ctx.lineWidth = SIZE * (0.03 + Math.random() * 0.05);
            ctx.lineCap = 'round';
            ctx.beginPath();
            const startX = Math.random() * SIZE;
            const startY = Math.random() * SIZE;
            ctx.moveTo(startX, startY);
            const cp1x = Math.random() * SIZE;
            const cp1y = Math.random() * SIZE;
            const cp2x = Math.random() * SIZE;
            const cp2y = Math.random() * SIZE;
            const endX = Math.random() * SIZE;
            const endY = Math.random() * SIZE;
            ctx.bezierCurveTo(cp1x, cp1y, cp2x, cp2y, endX, endY);
            ctx.stroke();
        }

        // Grano fino para romper la superficie lisa
        for (let i = 0; i < 260; i++) {
            const gx = Math.random() * SIZE;
            const gy = Math.random() * SIZE;
            const r = Math.random() * SIZE * 0.006;
            ctx.fillStyle = Math.random() > 0.5
                ? 'rgba(255,255,255,0.05)'
                : 'rgba(0,0,0,0.08)';
            ctx.beginPath();
            ctx.arc(gx, gy, r, 0, Math.PI * 2);
            ctx.fill();
        }

        // Brillo sutil de esquina para dar sensación 3D (por encima del veteado)
        const reflectGrad = ctx.createRadialGradient(SIZE * 0.2, SIZE * 0.2, 0, SIZE * 0.2, SIZE * 0.2, SIZE * 0.5);
        reflectGrad.addColorStop(0, 'rgba(255,255,255,0.12)');
        reflectGrad.addColorStop(1, 'rgba(255,255,255,0)');
        ctx.fillStyle = reflectGrad;
        ctx.fillRect(0, 0, SIZE, SIZE);

        // Centroide del triángulo UV fijo (ver FACE_UV más abajo),
        // en píxeles de este canvas
        const cx = SIZE * 0.5;
        const cy = SIZE * 0.65;
        const fontSize = Math.round(SIZE * 0.34);

        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.font = `900 ${fontSize}px "Arial Black", "Impact", sans-serif`;

        // Sombra de contraste
        ctx.shadowColor = 'rgba(0,0,0,0.7)';
        ctx.shadowBlur = SIZE * 0.02;
        ctx.fillStyle = 'rgba(0,0,0,0.55)';
        ctx.fillText(number.toString(), cx + SIZE * 0.008, cy + SIZE * 0.008);

        // Número dorado oscuro
        ctx.shadowColor = 'rgba(0,0,0,0.8)';
        ctx.shadowBlur = SIZE * 0.015;
        ctx.fillStyle = '#d4a017';
        ctx.fillText(number.toString(), cx, cy);

        const texture = new CanvasTexture(canvas);
        texture.needsUpdate = true;
        return texture;
    }

    // --- Geometría del D20 ---
    const geometry = new IcosahedronGeometry(1.45, 0);
    // IcosahedronGeometry es no-indexada a nivel 0: 20 caras x 3 vértices = 60.
    // FIX 1: sin esto, el array de 20 materiales no se reparte por cara.
    geometry.clearGroups();
    for (let face = 0; face < 20; face++) {
        geometry.addGroup(face * 3, 3, face);
    }

    // FIX 2: UV fijo (mismo triángulo para las 20 caras) para que el número
    // salga centrado y sin distorsión, sin depender del UV esférico por defecto.
    const FACE_UV = [0.5, 0.95, 0.05, 0.05, 0.95, 0.05]; // A(top), B(bottom-left), C(bottom-right)
    const uvArray = new Float32Array(60 * 2);
    for (let face = 0; face < 20; face++) {
        uvArray.set(FACE_UV, face * 6);
    }
    geometry.setAttribute('uv', new BufferAttribute(uvArray, 2));

    const faceNumbers = Array.from({ length: 20 }, (_, i) => i + 1);
    const materials = faceNumbers.map(num => new MeshStandardMaterial({
        map: createFaceTexture(num),
        roughness: 0.3,
        metalness: 0.0,
        emissive: new Color(0x330000),
        emissiveIntensity: 0.1,
        side: FrontSide,
    }));

    const dice = new Mesh(geometry, materials);
    dice.position.y = 0.1;
    scene.add(dice);

    // Bordes dorados
    const edgesGeo = new EdgesGeometry(geometry);
    const edgesMat = new LineBasicMaterial({ color: 0xffd700, transparent: true, opacity: 0.3 });
    dice.add(new LineSegments(edgesGeo, edgesMat));

    // --- FIX: alinear la cara ganadora con la cámara ---
    // Calculamos la normal (en espacio local) de cada una de las 20 caras
    // a partir de sus 3 vértices, para poder rotar el dado exactamente
    // hasta que la cara del número elegido quede mirando a cámara.
    const posAttr = geometry.attributes.position;
    const faceNormals = [];
    for (let face = 0; face < 20; face++) {
        const vA = new Vector3().fromBufferAttribute(posAttr, face * 3);
        const vB = new Vector3().fromBufferAttribute(posAttr, face * 3 + 1);
        const vC = new Vector3().fromBufferAttribute(posAttr, face * 3 + 2);
        const normal = new Vector3()
            .subVectors(vB, vA)
            .cross(new Vector3().subVectors(vC, vA))
            .normalize();
        faceNormals.push(normal);
    }
    const cameraDir = camera.position.clone().normalize();

    function targetQuaternionForResult(result) {
        // result es 1-20; faceNormals[result - 1] es su normal local.
        return new Quaternion().setFromUnitVectors(faceNormals[result - 1], cameraDir);
    }

    // --- Animación: muelle de rotación (una sola fase, sin salto final) ---
    // En vez de girar libremente y luego "teletransportar" con un slerp
    // al resultado, el dado tiene velocidad angular real (angVel) y desde
    // el primer frame hay una fuerza pequeña que lo atrae hacia la
    // orientación ganadora (targetQuat). Esa fuerza va dominando a medida
    // que la fricción frena el giro libre, así que la curva es continua:
    // no hay cambio de fase ni corrección brusca al final.
    const angVel = new Vector3();        // eje * rad/s, espacio mundo
    const targetQuat = new Quaternion();
    let spinning = false;
    let pendingResult = 1;
    let lastTime = 0;
    let rollStartTime = 0;

    const K_SPRING = 9;      // fuerza de atracción hacia el resultado (ya en rampa)
    const K_DAMPING = 1.8;   // fricción angular (más bajo = gira más tiempo)
    const FREE_SPIN_MS = 900; // giro totalmente libre antes de que "tire" hacia el resultado
    const RAMP_MS = 550;      // tiempo en que la fuerza de atracción pasa de 0 a K_SPRING
    const STOP_ANGLE = 0.01; // rad restantes para considerar "llegado"
    const STOP_SPEED = 0.05; // rad/s restantes para considerar "parado"

    const sinMovimiento = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let visible = true;
    let rafId = 0;
    let ultimoRender = 0;
    const REPOSO_MS = 50; // en reposo basta con ~20 fps: el flotado es casi imperceptible

    function animate(time) {
        rafId = requestAnimationFrame(animate);
        if (!spinning && !isRolling && time - ultimoRender < REPOSO_MS) return;
        ultimoRender = time;
        const dt = lastTime ? Math.min((time - lastTime) / 1000, 0.033) : 0.016;
        lastTime = time;

        if (spinning) {
            const elapsed = time - rollStartTime;
            const springGain = K_SPRING * MathUtils.clamp(
                (elapsed - FREE_SPIN_MS) / RAMP_MS, 0, 1
            );

            // Ángulo y eje que faltan para llegar a la orientación ganadora
            const errQ = targetQuat.clone().multiply(dice.quaternion.clone().invert());
            if (errQ.w < 0) errQ.set(-errQ.x, -errQ.y, -errQ.z, -errQ.w); // camino corto
            errQ.normalize();
            const angle = 2 * Math.acos(MathUtils.clamp(errQ.w, -1, 1));
            const axis = angle > 1e-6
                ? new Vector3(errQ.x, errQ.y, errQ.z).normalize()
                : new Vector3(0, 1, 0);

            // Mientras springGain es 0 (fase libre), esto es solo fricción:
            // el dado sigue girando por inercia, como al tirarlo de verdad.
            // Al entrar en la rampa añadimos algo más de amortiguación para
            // que, con tanta velocidad acumulada, encaje limpio y no oscile.
            const effectiveDamping = K_DAMPING + (springGain / K_SPRING) * 3.2;
            angVel.addScaledVector(axis, angle * springGain * dt);
            angVel.addScaledVector(angVel, -effectiveDamping * dt);

            const speed = angVel.length();
            if (speed > 1e-6) {
                const deltaQuat = new Quaternion().setFromAxisAngle(
                    angVel.clone().normalize(),
                    speed * dt
                );
                dice.quaternion.premultiply(deltaQuat);
            }

            const bounce = Math.min(speed / 8, 1) * 3;
            shadow.style.transform = `scale(${0.85 + bounce * 0.02})`;
            shadow.style.opacity = `${0.4 + bounce * 0.02}`;

            // Solo puede "llegar" una vez que la atracción está a tope,
            // para no cortar el giro libre de golpe.
            if (springGain >= K_SPRING - 1e-3 && angle < STOP_ANGLE && speed < STOP_SPEED) {
                dice.quaternion.copy(targetQuat);
                spinning = false;
                isRolling = false;
                mostrarResultado(elementos, pendingResult);
            }
        } else if (!isRolling && !sinMovimiento) {
            const floatY = Math.sin(time * 0.0006) * 0.025;
            dice.position.y = 0.1 + floatY;
            shadow.style.transform = `scale(${0.9 + Math.sin(time * 0.0006) * 0.015})`;
            shadow.style.opacity = `${0.45 + Math.sin(time * 0.0006) * 0.04}`;
        }
        renderer.render(scene, camera);
    }
    function reanudar() {
        if (!rafId && visible && !document.hidden) {
            lastTime = 0;
            rafId = requestAnimationFrame(animate);
        }
    }
    function pausar() {
        cancelAnimationFrame(rafId);
        rafId = 0;
    }
    // Sin trabajo de GPU cuando el dado no se ve o la pestaña está en segundo plano
    new IntersectionObserver(([entrada]) => {
        visible = entrada.isIntersecting;
        visible ? reanudar() : pausar();
    }).observe(container);
    document.addEventListener('visibilitychange', () => (document.hidden ? pausar() : reanudar()));

    rafId = requestAnimationFrame(animate);

    // --- Lanzar dado ---
    function rollDice() {
        if (isRolling) return;
        isRolling = true;
        reanudar();

        container.classList.remove('result-show');
        badge.style.transform = 'translate(-50%, -50%) scale(0)';
        badge.style.opacity = '0';
        badge.textContent = '0';
        window.clearTimeout(container._badgeHideTimer);
        critOverlay.classList.remove('show', 'fail');
        window.clearTimeout(critOverlay._hideTimer);

        // Elegimos el resultado ANTES de girar; el muelle converge a él
        // tras la fase de giro libre, sin salto al final.
        pendingResult = Math.floor(Math.random() * 20) + 1;
        targetQuat.copy(targetQuaternionForResult(pendingResult));
        rollStartTime = lastTime || performance.now();

        const randomAxis = new Vector3(
            Math.random() - 0.5, Math.random() - 0.5, Math.random() - 0.5
        ).normalize();
        angVel.copy(randomAxis).multiplyScalar(34 + Math.random() * 14);

        spinning = true;
    }

    container.addEventListener('click', function(e) {
        e.stopPropagation();
        rollDice();
    });
    container.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            rollDice();
        }
    });
    critOverlay.addEventListener('click', function() {
        critOverlay.classList.remove('show');
        window.clearTimeout(critOverlay._hideTimer);
    });

    // --- Responsive ---
    function resizeRenderer() {
        const rect = wrapper.getBoundingClientRect();
        const size = Math.min(rect.width, rect.height);
        if (size <= 0) return;
        renderer.setSize(size, size);
        camera.aspect = 1;
        camera.updateProjectionMatrix();
    }
    window.addEventListener('resize', resizeRenderer);
    resizeRenderer();
    new ResizeObserver(resizeRenderer).observe(wrapper);

    return { lanzar: rollDice };
}
