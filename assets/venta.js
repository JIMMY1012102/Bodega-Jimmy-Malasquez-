const contenedor = document.querySelector('#lineas');
let siguiente = 1;
document.querySelector('#agregar').addEventListener('click', () => {
    if (contenedor.children.length >= 100) return;
    const linea = contenedor.firstElementChild.cloneNode(true);
    const indice = siguiente++;
    linea.querySelector('.numero').textContent = indice + 1;
    for (const campo of linea.querySelectorAll('select,input')) {
        const tipo = campo.tagName === 'SELECT' ? 'producto' : 'cantidad';
        const anterior = campo.id;
        campo.name = campo.name.replace(/\[\d+\]/, `[${indice}]`);
        campo.id = `${tipo}-${indice}`;
        linea.querySelector(`label[for="${anterior}"]`).htmlFor = campo.id;
        campo.value = tipo === 'producto' ? '' : '1';
    }
    const boton = linea.querySelector('.quitar');
    boton.disabled = false;
    boton.setAttribute('aria-label', `Quitar producto ${indice + 1}`);
    contenedor.append(linea);
    linea.querySelector('select').focus();
});
contenedor.addEventListener('click', e => {
    if (e.target.matches('.quitar') && contenedor.children.length > 1) {
        e.target.closest('fieldset').remove();
        document.querySelector('#agregar').focus();
    }
});
