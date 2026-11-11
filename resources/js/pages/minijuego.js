/* ============================================================================
   PÁGINA: RETO DIARIO — componente Alpine
   Alpine solo se carga aquí (antes viajaba en el JS de todas las páginas).
   ============================================================================ */
import Alpine from "alpinejs";

export function retoDiario({ estadoInicial, vidaInicial, vidaMax, rondaInicial, rachaInicial, aciertosInicial = 0, fallosInicial = 0, nombreEnemigo = '', urlResponder, proximoCambioInicial }) {
    return {
        estado: estadoInicial,
        vida: vidaInicial,
        vidaMax: vidaMax,
        ronda: rondaInicial,
        racha: rachaInicial,
        proximoCambio: proximoCambioInicial,
        aciertos: aciertosInicial,
        fallos: fallosInicial,
        enviando: false,
        revelada: false,
        indiceCorrecta: null,
        explicacion: '',
        siguienteEstado: null,
        limite: 20,
        segundos: 20,
        _reloj: null,
        feedback: '',
        marcada: null,
        ultimaCorrecta: null,
        cuentaAtras: '',
        _timer: null,

        init() {
            if (this.estado !== 'en_curso') {
                this.iniciarCuentaAtras();
            } else {
                this.iniciarReloj();
            }
        },

        iniciarReloj() {
            this.detenerReloj();
            this.segundos = this.limite;
            this._reloj = setInterval(() => {
                this.segundos--;
                if (this.segundos <= 0) {
                    this.detenerReloj();
                    this.responder(null);
                }
            }, 1000);
        },

        detenerReloj() {
            if (this._reloj) clearInterval(this._reloj);
            this._reloj = null;
        },

        async responder(indice) {
            if (this.enviando || this.revelada) return;
            this.enviando = true;
            this.detenerReloj();
            this.marcada = indice;
            this.feedback = '';

            try {
                const res = await fetch(urlResponder, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(indice === null ? { tiempo_agotado: true } : { opcion: indice }),
                });

                const data = await res.json();

                if (!res.ok) {
                    this.feedback = data.error || 'Ha ocurrido un error.';
                    this.enviando = false;
                    return;
                }

                this.ultimaCorrecta = data.correcta;
                this.indiceCorrecta = data.indice_correcta;
                this.explicacion = data.explicacion || '';
                this.vida = data.vida_restante;
                this.racha = data.racha;
                this.aciertos = data.aciertos;
                this.fallos = data.fallos;
                this.proximoCambio = data.proximo_cambio;
                this.siguienteEstado = { estado: data.estado, ronda: data.ronda };
                this.revelada = true;

                this.feedback = data.tiempo_agotado
                    ? '¡Se acabó el tiempo!'
                    : data.correcta ? `¡Golpe certero! (-${data.danio} PG)` : 'Fallaste: el enemigo te hiere.';
                if (data.correcta) this.golpe();
            } catch (e) {
                this.feedback = 'No se pudo conectar. Inténtalo de nuevo.';
                this.marcada = null;
                this.iniciarReloj();
            } finally {
                this.enviando = false;
            }
        },

        golpe() {
            const hero = document.querySelector('.rd-hero');
            if (!hero) return;
            hero.classList.remove('rd-golpeado');
            void hero.offsetWidth;
            hero.classList.add('rd-golpeado');
        },

        siguiente() {
            if (!this.siguienteEstado) return;
            const { estado, ronda } = this.siguienteEstado;
            this.estado = estado;
            this.ronda = ronda;
            this.marcada = null;
            this.revelada = false;
            this.ultimaCorrecta = null;
            this.indiceCorrecta = null;
            this.explicacion = '';
            this.feedback = '';
            this.siguienteEstado = null;
            if (estado === 'en_curso') {
                this.iniciarReloj();
            } else {
                this.iniciarCuentaAtras();
            }
        },

        async compartir() {
            const texto = `Reto Diario · ${nombreEnemigo}: ${this.estado === 'ganado' ? 'victoria' : 'derrota'} (${this.aciertos} aciertos, ${this.fallos} fallos)`;
            try {
                if (navigator.share) {
                    await navigator.share({ text: texto });
                } else {
                    await navigator.clipboard.writeText(texto);
                    window.toast?.('Resultado copiado', 'exito');
                }
            } catch (e) { /* cancelado */ }
        },

        esperar(ms) {
            return new Promise((resolve) => setTimeout(resolve, ms));
        },

        iniciarCuentaAtras() {
            if (this._timer) clearInterval(this._timer);

            const actualizar = () => {
                const objetivo = new Date(this.proximoCambio);
                const diff = objetivo - new Date();

                if (diff <= 1000) {
                    window.location.reload();
                    return;
                }

                const h = String(Math.floor(diff / 3600000)).padStart(2, '0');
                const m = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
                const s = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');

                this.cuentaAtras = `${h}:${m}:${s}`;
            };

            actualizar();
            this._timer = setInterval(actualizar, 1000);
        },
    };
}

Alpine.data("retoDiario", retoDiario);
window.Alpine = Alpine;
Alpine.start();
