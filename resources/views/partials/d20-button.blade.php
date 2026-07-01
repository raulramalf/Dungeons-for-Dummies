{{-- d20-button.blade.php --}}
<div id="d20-container" role="button" tabindex="0" aria-label="Lanzar dado de 20 caras"
     style="position: fixed; bottom: 30px; right: 30px; z-index: 9999; cursor: pointer; width: 100px; height: 120px; display: flex; flex-direction: column; align-items: center;">

    <div id="d20-canvas-wrapper" style="width: 100px; height: 100px; position: relative; background: transparent;">
        <div id="result-badge" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(0); background: rgba(180, 0, 0, 0.95); color: #ffd700; font-size: 34px; font-weight: bold; width: 58px; height: 58px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid #ffd700; box-shadow: 0 0 50px rgba(255, 215, 0, 0.4); transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.4s; pointer-events: none; font-family: 'Georgia', serif; text-shadow: 0 0 20px rgba(255, 215, 0, 0.5); opacity: 0;">
            0
        </div>
    </div>

    <div id="d20-shadow" style="width: 86px; height: 16px; background: radial-gradient(ellipse, rgba(0,0,0,0.25) 0%, transparent 70%); border-radius: 50%; margin-top: 4px; filter: blur(8px); transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);"></div>
</div>

<div id="d20-crit-overlay" aria-hidden="true">
    <span id="d20-crit-text">¡CRÍTICO!</span>
</div>

@once
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

<style>
    #d20-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
    }
    #d20-container:hover {
        filter: drop-shadow(0 0 30px rgba(200, 0, 0, 0.3));
    }
    #d20-container:active #d20-canvas-wrapper {
        transform: scale(0.93);
        transition: transform 0.12s ease;
    }
    .result-show #result-badge {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
    }
    .result-show #d20-shadow {
        transform: scale(0.75);
        opacity: 0.3;
    }
    @keyframes badgePop {
        0% { transform: translate(-50%, -50%) scale(0) rotate(-15deg); opacity: 0; }
        55% { transform: translate(-50%, -50%) scale(1.15) rotate(3deg); opacity: 1; }
        100% { transform: translate(-50%, -50%) scale(1) rotate(0deg); opacity: 1; }
    }
    .result-show #result-badge {
        animation: badgePop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
    #d20-canvas-wrapper canvas {
        display: block;
        width: 100% !important;
        height: 100% !important;
        background: transparent !important;
    }

    #d20-crit-overlay {
        position: fixed;
        inset: 0;
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0);
        opacity: 0;
        pointer-events: none;
        transition: background 0.35s ease, opacity 0.35s ease;
    }
    #d20-crit-overlay.show {
        background: rgba(0, 0, 0, 0.55);
        opacity: 1;
        pointer-events: auto;
    }
    #d20-crit-text {
        font-family: 'Cinzel', 'Georgia', serif;
        font-weight: 900;
        font-size: clamp(48px, 10vw, 130px);
        letter-spacing: 4px;
        color: #ffd700;
        text-shadow:
            0 0 20px rgba(255, 215, 0, 0.9),
            0 0 60px rgba(255, 215, 0, 0.6),
            0 4px 0 rgba(120, 80, 0, 0.9);
        transform: scale(0) rotate(-8deg);
        opacity: 0;
    }
    #d20-crit-overlay.fail #d20-crit-text {
        color: #ff2b2b;
        text-shadow:
            0 0 20px rgba(255, 40, 40, 0.9),
            0 0 60px rgba(255, 40, 40, 0.6),
            0 4px 0 rgba(90, 0, 0, 0.9);
    }
    #d20-crit-overlay.show #d20-crit-text {
        animation: d20CritPop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
    @keyframes d20CritPop {
        0% { transform: scale(0) rotate(-8deg); opacity: 0; }
        55% { transform: scale(1.15) rotate(2deg); opacity: 1; }
        100% { transform: scale(1) rotate(0deg); opacity: 1; }
    }
</style>

<script>
(function initD20Widget() {
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('d20-container');
        if (!container || container.dataset.d20Init) return;
        container.dataset.d20Init = '1';

        const wrapper = document.getElementById('d20-canvas-wrapper');
        const badge = document.getElementById('result-badge');
        const shadow = document.getElementById('d20-shadow');
        const critOverlay = document.getElementById('d20-crit-overlay');
        const critText = document.getElementById('d20-crit-text');
        let isRolling = false;

        // --- Escena y cámara ---
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(35, 1, 0.1, 1000);
        camera.position.set(0.2, 0.3, 5.2);
        camera.lookAt(0, 0, 0);

        const renderer = new THREE.WebGLRenderer({
            antialias: true,
            alpha: true,
            powerPreference: 'high-performance',
        });
        renderer.setSize(100, 100);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        renderer.setClearColor(0x000000, 0);
        renderer.toneMapping = THREE.NoToneMapping;
        wrapper.appendChild(renderer.domElement);

        // --- Luces ---
        scene.add(new THREE.AmbientLight(0x404040, 0.8));

        const mainLight = new THREE.DirectionalLight(0xffffff, 1.8);
        mainLight.position.set(5, 8, 6);
        scene.add(mainLight);

        const fillLight = new THREE.DirectionalLight(0xcc88ff, 0.6);
        fillLight.position.set(-4, 1, -5);
        scene.add(fillLight);

        const rimLight = new THREE.DirectionalLight(0xffffff, 0.4);
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

            const texture = new THREE.CanvasTexture(canvas);
            texture.needsUpdate = true;
            return texture;
        }

        // --- Geometría del D20 ---
        const geometry = new THREE.IcosahedronGeometry(1.45, 0);
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
        geometry.setAttribute('uv', new THREE.BufferAttribute(uvArray, 2));

        const faceNumbers = Array.from({ length: 20 }, (_, i) => i + 1);
        const materials = faceNumbers.map(num => new THREE.MeshStandardMaterial({
            map: createFaceTexture(num),
            roughness: 0.3,
            metalness: 0.0,
            emissive: new THREE.Color(0x330000),
            emissiveIntensity: 0.1,
            side: THREE.FrontSide,
        }));

        const dice = new THREE.Mesh(geometry, materials);
        dice.position.y = 0.1;
        scene.add(dice);

        // Bordes dorados
        const edgesGeo = new THREE.EdgesGeometry(geometry);
        const edgesMat = new THREE.LineBasicMaterial({ color: 0xffd700, transparent: true, opacity: 0.3 });
        dice.add(new THREE.LineSegments(edgesGeo, edgesMat));

        // --- FIX: alinear la cara ganadora con la cámara ---
        // Calculamos la normal (en espacio local) de cada una de las 20 caras
        // a partir de sus 3 vértices, para poder rotar el dado exactamente
        // hasta que la cara del número elegido quede mirando a cámara.
        const posAttr = geometry.attributes.position;
        const faceNormals = [];
        for (let face = 0; face < 20; face++) {
            const vA = new THREE.Vector3().fromBufferAttribute(posAttr, face * 3);
            const vB = new THREE.Vector3().fromBufferAttribute(posAttr, face * 3 + 1);
            const vC = new THREE.Vector3().fromBufferAttribute(posAttr, face * 3 + 2);
            const normal = new THREE.Vector3()
                .subVectors(vB, vA)
                .cross(new THREE.Vector3().subVectors(vC, vA))
                .normalize();
            faceNormals.push(normal);
        }
        const cameraDir = camera.position.clone().normalize();

        function targetQuaternionForResult(result) {
            // result es 1-20; faceNormals[result - 1] es su normal local.
            return new THREE.Quaternion().setFromUnitVectors(faceNormals[result - 1], cameraDir);
        }

        // --- Animación: muelle de rotación (una sola fase, sin salto final) ---
        // En vez de girar libremente y luego "teletransportar" con un slerp
        // al resultado, el dado tiene velocidad angular real (angVel) y desde
        // el primer frame hay una fuerza pequeña que lo atrae hacia la
        // orientación ganadora (targetQuat). Esa fuerza va dominando a medida
        // que la fricción frena el giro libre, así que la curva es continua:
        // no hay cambio de fase ni corrección brusca al final.
        const angVel = new THREE.Vector3();        // eje * rad/s, espacio mundo
        const targetQuat = new THREE.Quaternion();
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

        function animate(time) {
            requestAnimationFrame(animate);
            const dt = lastTime ? Math.min((time - lastTime) / 1000, 0.033) : 0.016;
            lastTime = time;

            if (spinning) {
                const elapsed = time - rollStartTime;
                const springGain = K_SPRING * THREE.MathUtils.clamp(
                    (elapsed - FREE_SPIN_MS) / RAMP_MS, 0, 1
                );

                // Ángulo y eje que faltan para llegar a la orientación ganadora
                const errQ = targetQuat.clone().multiply(dice.quaternion.clone().invert());
                if (errQ.w < 0) errQ.set(-errQ.x, -errQ.y, -errQ.z, -errQ.w); // camino corto
                errQ.normalize();
                const angle = 2 * Math.acos(THREE.MathUtils.clamp(errQ.w, -1, 1));
                const axis = angle > 1e-6
                    ? new THREE.Vector3(errQ.x, errQ.y, errQ.z).normalize()
                    : new THREE.Vector3(0, 1, 0);

                // Mientras springGain es 0 (fase libre), esto es solo fricción:
                // el dado sigue girando por inercia, como al tirarlo de verdad.
                // Al entrar en la rampa añadimos algo más de amortiguación para
                // que, con tanta velocidad acumulada, encaje limpio y no oscile.
                const effectiveDamping = K_DAMPING + (springGain / K_SPRING) * 3.2;
                angVel.addScaledVector(axis, angle * springGain * dt);
                angVel.addScaledVector(angVel, -effectiveDamping * dt);

                const speed = angVel.length();
                if (speed > 1e-6) {
                    const deltaQuat = new THREE.Quaternion().setFromAxisAngle(
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
                    badge.textContent = pendingResult;
                    container.classList.add('result-show');
                    isRolling = false;

                    // El número se muestra 2s y luego se oculta solo.
                    window.clearTimeout(container._badgeHideTimer);
                    container._badgeHideTimer = window.setTimeout(function() {
                        container.classList.remove('result-show');
                    }, 2000);

                    if (pendingResult === 20 || pendingResult === 1) {
                        critText.textContent = pendingResult === 20 ? '¡CRÍTICO!' : '¡PIFIA!';
                        critOverlay.classList.toggle('fail', pendingResult === 1);
                        critOverlay.classList.add('show');
                        window.clearTimeout(critOverlay._hideTimer);
                        critOverlay._hideTimer = window.setTimeout(function() {
                            critOverlay.classList.remove('show');
                        }, 2200);
                    }
                }
            } else if (!isRolling) {
                const floatY = Math.sin(time * 0.0006) * 0.025;
                dice.position.y = 0.1 + floatY;
                shadow.style.transform = `scale(${0.9 + Math.sin(time * 0.0006) * 0.015})`;
                shadow.style.opacity = `${0.45 + Math.sin(time * 0.0006) * 0.04}`;
            }
            renderer.render(scene, camera);
        }
        animate(0);

        // --- Lanzar dado ---
        function rollDice() {
            if (isRolling) return;
            isRolling = true;

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

            const randomAxis = new THREE.Vector3(
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
    });
})();
</script>
@endonce