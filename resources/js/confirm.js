/* ============================================================================
   CONFIRMACIONES
   Sustituye a window.confirm() (bloqueante, sin estilo, inconsistente en móvil)
   por un <dialog> propio.

   · Formularios:  <form data-confirm="¿Eliminar X?" ...>
   · Botones:      <button type="submit" data-confirm="¿Eliminar X?">
   · Desde JS:     if (!(await confirmar('¿Seguro?'))) return;
   El texto se pasa siempre como textContent: nunca se interpreta como HTML.
   ============================================================================ */
let dialogo;

function crearDialogo() {
    const d = document.createElement("dialog");
    d.className = "confirm";
    d.setAttribute("aria-labelledby", "confirm-msg");
    d.innerHTML = `
        <form method="dialog" class="confirm-cuerpo">
            <p id="confirm-msg" class="confirm-msg"></p>
            <div class="confirm-acciones">
                <button value="cancel" class="btn btn-secundario" autofocus>Cancelar</button>
                <button value="ok" class="btn btn-peligro" data-ok>Confirmar</button>
            </div>
        </form>`;
    document.body.append(d);
    return d;
}

/** @returns {Promise<boolean>} */
export function confirmar(mensaje, { aceptar = "Eliminar" } = {}) {
    dialogo ??= crearDialogo();
    dialogo.querySelector("#confirm-msg").textContent = mensaje;
    dialogo.querySelector("[data-ok]").textContent = aceptar;
    dialogo.returnValue = "cancel";

    return new Promise((resolver) => {
        dialogo.addEventListener(
            "close",
            () => resolver(dialogo.returnValue === "ok"),
            { once: true },
        );
        dialogo.showModal();
    });
}
window.confirmar = confirmar;

const yaConfirmados = new WeakSet();

async function pedir(el, enviar) {
    if (!(await confirmar(el.dataset.confirm))) return;
    enviar();
}

// Formularios con data-confirm
document.addEventListener(
    "submit",
    (e) => {
        const form = e.target;
        if (!(form instanceof HTMLFormElement) || !form.dataset.confirm) return;
        if (yaConfirmados.has(form)) return yaConfirmados.delete(form);

        e.preventDefault();
        e.stopImmediatePropagation();
        const emisor = e.submitter;
        pedir(form, () => {
            yaConfirmados.add(form);
            form.requestSubmit(emisor ?? undefined);
        });
    },
    true,
);

// Botones submit con data-confirm dentro de un formulario sin él
document.addEventListener(
    "click",
    (e) => {
        const btn = e.target.closest("button[data-confirm], input[data-confirm]");
        if (!btn || !btn.form || btn.form.dataset.confirm) return;
        if (yaConfirmados.has(btn)) return yaConfirmados.delete(btn);

        e.preventDefault();
        e.stopImmediatePropagation();
        pedir(btn, () => {
            yaConfirmados.add(btn);
            btn.click();
        });
    },
    true,
);
