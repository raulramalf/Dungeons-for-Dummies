/* ============================================================================
   PESTAÑAS ACCESIBLES
   <div role="tablist" class="tabs-lista">
     <button role="tab" class="tab-boton" id="tab-a" aria-controls="panel-a" aria-selected="true">…
   <div role="tabpanel" class="tab-panel" id="panel-a" aria-labelledby="tab-a">
   Flechas ← → / Inicio / Fin mueven el foco entre pestañas.
   ============================================================================ */
function seleccionar(tab) {
    const lista = tab.closest('[role="tablist"]');
    lista.querySelectorAll('[role="tab"]').forEach((t) => {
        const activa = t === tab;
        t.setAttribute("aria-selected", String(activa));
        t.tabIndex = activa ? 0 : -1;
        document.getElementById(t.getAttribute("aria-controls"))?.toggleAttribute("hidden", !activa);
    });
}

document.addEventListener("click", (e) => {
    const tab = e.target.closest('[role="tab"]');
    if (tab) seleccionar(tab);
});

document.addEventListener("keydown", (e) => {
    const tab = e.target.closest?.('[role="tab"]');
    if (!tab || !["ArrowLeft", "ArrowRight", "Home", "End"].includes(e.key)) return;
    const tabs = [...tab.closest('[role="tablist"]').querySelectorAll('[role="tab"]')];
    const i = tabs.indexOf(tab);
    const siguiente =
        e.key === "Home" ? 0 : e.key === "End" ? tabs.length - 1 : (i + (e.key === "ArrowRight" ? 1 : -1) + tabs.length) % tabs.length;
    e.preventDefault();
    tabs[siguiente].focus();
    seleccionar(tabs[siguiente]);
});
