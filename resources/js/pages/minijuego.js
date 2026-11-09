/* ============================================================================
   PÁGINA: RETO DIARIO — componente Alpine
   Alpine solo se carga aquí (antes viajaba en el JS de todas las páginas).
   ============================================================================ */
import Alpine from "alpinejs";

export function retoDiario({ estadoInicial, vidaInicial, vidaMax, rondaInicial, rachaInicial, urlResponder, proximoCambioInicial }) {
    return {
        estado: estadoInicial,
        vida: vidaInicial,
        vidaMax: vidaMax,
        ronda: rondaInicial,
        racha: rachaInicial,
        proximoCambio: proximoCambioInicial,
        enviando: false,
        feedback: '',
        marcada: null,
        ultimaCorrecta: null,
        cuentaAtras: '',
        _timer: null,

        init() {
            if (this.estado !== 'en_curso') {
                this.iniciarCuentaAtras();
            }
        },

        async responder(indice) {
            if (this.enviando) return;
            this.enviando = true;
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
                    body: JSON.stringify({ opcion: indice }),
                });

                const data = await res.json();

                if (!res.ok) {
                    this.feedback = data.error || 'Ha ocurrido un error.';
                    this.enviando = false;
                    return;
                }

                this.ultimaCorrecta = data.correcta;
                this.vida = data.vida_restante;
                this.racha = data.racha;
                this.proximoCambio = data.proximo_cambio;

                this.feedback = data.correcta
                    ? `¡Correcto! (${data.aciertos}/4 aciertos)`
                    : `Incorrecto (${data.fallos}/4 fallos)`;

                await this.esperar(900);

                this.estado = data.estado;
                this.ronda = data.ronda;
                this.marcada = null;
                this.ultimaCorrecta = null;
                this.feedback = '';

                if (this.estado !== 'en_curso') {
                    this.iniciarCuentaAtras();
                }
            } catch (e) {
                this.feedback = 'No se pudo conectar. Inténtalo de nuevo.';
            } finally {
                this.enviando = false;
            }
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
