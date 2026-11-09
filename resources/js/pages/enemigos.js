/* ============================================================================
   PÁGINA: enemigos — lógica extraída de la vista (antes un <script> en línea).
   Se carga como módulo (diferido): no bloquea el pintado y el navegador lo cachea.
   Las funciones usadas desde atributos onclick/onchange del HTML se exponen en window.
   ============================================================================ */

function buscarEnemigo() {
    const texto = document.getElementById('buscador').value.toLowerCase();
    document.querySelectorAll('.tarjeta-enemigo').forEach(tarjeta => {
        const nombre = tarjeta.dataset.nombre.toLowerCase();
        const coincide = nombre.includes(texto);
        const filtroOk = !filtroBossActivo || tarjeta.dataset.boss === '1';
        tarjeta.style.display = (coincide && filtroOk) ? 'block' : 'none';
    });
}

let filtroBossActivo = false;

function filtrarBoss() {
    filtroBossActivo = !filtroBossActivo;
    const btn = document.getElementById('btn-boss');
    btn.style.background = filtroBossActivo ? 'rgba(179,3,3,0.25)' : '';
    btn.style.borderColor = filtroBossActivo ? 'var(--color-rojo)' : '';
    buscarEnemigo();
}

function verEnemigo(id) {
    fetch(`/enemigos/${id}`)
        .then(r => r.json())
        .then(e => {
            document.getElementById('ver-titulo').textContent = e.nombre;
            document.getElementById('form-editar-enemigo').action = `/enemigos/${id}/update`;
            document.getElementById('ver-nombre').value = e.nombre;
            document.getElementById('ver-tipo').value = e.tipo;
            document.getElementById('ver-tamaño').value = e.tamaño;
            document.getElementById('ver-alineamiento').value = e.alineamiento || '';
            document.getElementById('ver-cr').value = e.clase_de_desafio;
            document.getElementById('ver-xp').value = e.puntos_de_experiencia;
            document.getElementById('ver-ca').value = e.clase_de_armadura;
            document.getElementById('ver-tipo-armadura').value = e.tipo_armadura || '';
            document.getElementById('ver-pg').value = e.puntos_de_golpe;
            document.getElementById('ver-velocidad').value = e.velocidad;
            document.getElementById('ver-fuerza').value = e.fuerza;
            document.getElementById('ver-destreza').value = e.destreza;
            document.getElementById('ver-constitucion').value = e.constitucion;
            document.getElementById('ver-inteligencia').value = e.inteligencia;
            document.getElementById('ver-sabiduria').value = e.sabiduria;
            document.getElementById('ver-carisma').value = e.carisma;
            document.getElementById('ver-descripcion').value = e.descripcion || '';
            document.getElementById('ver-resistencias').value = e.resistencias || '';
            document.getElementById('ver-inmunidades').value = e.inmunidades_daño || '';
            document.getElementById('ver-sentidos').value = e.sentidos || '';
            document.getElementById('ver-idiomas').value = e.idiomas || '';
            document.getElementById('ver-acciones').value = e.acciones || '';
            document.getElementById('ver-rasgos').value = e.rasgos_especiales || '';
            document.getElementById('ver-boss').checked = e.es_boss == 1;
            const preview = document.getElementById('ver-imagen-preview') || document.getElementById('ver-imagen-preview');
            if (e.imagen) {
                console.log('Imagen:', e.imagen);
                console.log('URL:', '/storage/' + e.imagen);
                preview.src = '/storage/' + e.imagen;
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
            setTimeout(() => {
                document.getElementById('modal-ver-enemigo').scrollTop = 0;
            }, 10);
            document.getElementById('modal-ver-enemigo').style.display = 'flex';
        });
}

let ordenAscendente = true;
let ordenNombreAsc = true;

function ordenarPorCR() {
    const lista = document.getElementById('lista-enemigos');
    const tarjetas = Array.from(lista.querySelectorAll('.tarjeta-enemigo'));

    tarjetas.sort((a, b) => {
        const crA = parseFloat(a.dataset.cr);
        const crB = parseFloat(b.dataset.cr);
        return ordenAscendente ? crA - crB : crB - crA;
    });

    ordenAscendente = !ordenAscendente;
    const btn = document.getElementById('btn-orden');
    btn.textContent = ordenAscendente ? '↑↓ Ordenar por CR' : '↓↑ Ordenar por CR';
    btn.style.color = ordenAscendente ? 'var(--color-gris)' : 'white';
    btn.style.borderColor = ordenAscendente ? 'var(--color-gris)' : 'var(--color-rojo)';

    // Resetear botón de nombre
    ordenNombreAsc = true;
    const btnNombre = document.getElementById('btn-nombre');
    btnNombre.textContent = '↑↓ Ordenar por Nombre';
    btnNombre.style.color = 'var(--color-gris)';
    btnNombre.style.borderColor = 'var(--color-gris)';

    tarjetas.forEach(t => lista.appendChild(t));
}

function ordenarPorNombre() {
    const lista = document.getElementById('lista-enemigos');
    const tarjetas = Array.from(lista.querySelectorAll('.tarjeta-enemigo'));

    tarjetas.sort((a, b) => {
        const nombreA = a.dataset.nombre.toLowerCase();
        const nombreB = b.dataset.nombre.toLowerCase();
        return ordenNombreAsc ? nombreA.localeCompare(nombreB) : nombreB.localeCompare(nombreA);
    });

    ordenNombreAsc = !ordenNombreAsc;
    const btn = document.getElementById('btn-nombre');
    btn.textContent = ordenNombreAsc ? '↑↓ Ordenar por Nombre' : '↓↑ Ordenar por Nombre';
    btn.style.color = ordenNombreAsc ? 'var(--color-gris)' : 'white';
    btn.style.borderColor = ordenNombreAsc ? 'var(--color-gris)' : 'var(--color-rojo)';

    // Resetear botón de CR
    ordenAscendente = true;
    const btnCR = document.getElementById('btn-orden');
    btnCR.textContent = '↑↓ Ordenar por CR';
    btnCR.style.color = 'var(--color-gris)';
    btnCR.style.borderColor = 'var(--color-gris)';

    tarjetas.forEach(t => lista.appendChild(t));
}

function abrirModalNuevo() {
    setTimeout(() => {
        document.getElementById('modal-enemigo').scrollTop = 0;
    }, 10);
    document.getElementById('modal-enemigo').style.display = 'flex';
}

// Accesibles desde los atributos on*="…" del marcado
Object.assign(window, {
    abrirModalNuevo,
    buscarEnemigo,
    filtrarBoss,
    ordenarPorCR,
    ordenarPorNombre,
    verEnemigo,
});
