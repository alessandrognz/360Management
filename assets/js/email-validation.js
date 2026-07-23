// Validación de correo en cliente para todos los formularios con campo de email.
// No altera la lógica de servidor: solo bloquea el envío y avisa por pantalla
// cuando el formato del correo no es válido.

// Lista de extensiones (TLD) válidas más comunes. Sirve para rechazar dominios
// mal escritos como "gmail.comasdsadsa" o "hotmail.con", que sí cumplen el
// formato general pero cuya extensión no existe.
const TLDS_VALIDOS = [
    'com', 'org', 'net', 'edu', 'gov', 'int', 'mil', 'info', 'biz', 'name',
    'es', 'eu', 'io', 'co', 'dev', 'app', 'me', 'tv', 'us', 'uk', 'de',
    'fr', 'it', 'pt', 'nl', 'mx', 'ar', 'cl', 'pe', 'br', 'ca'
];

function validarEmail(email) {
    // 1) Formato general del correo.
    const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.([a-zA-Z]{2,})$/;
    const coincidencia = regex.exec(email);
    if (!coincidencia) {
        return false;
    }

    // 2) La extensión debe ser un TLD conocido (comparación sin mayúsculas).
    //    Así "gmail.comasdsadsa" se rechaza porque "comasdsadsa" no está en la lista.
    const tld = coincidencia[1].toLowerCase();
    return TLDS_VALIDOS.includes(tld);
}

document.addEventListener('DOMContentLoaded', function () {
    // Devuelve (creando si hace falta) el elemento donde se muestra el aviso,
    // situado justo debajo del campo de correo.
    function obtenerMensaje(campo) {
        let msg = campo.parentNode.querySelector('.email-error');
        if (!msg) {
            msg = document.createElement('span');
            msg.className = 'email-error';
            msg.setAttribute('role', 'alert');
            msg.style.display = 'none';
            msg.style.color = '#c0392b';
            msg.style.fontSize = '0.85rem';
            msg.style.marginTop = '4px';
            campo.parentNode.insertBefore(msg, campo.nextSibling);
        }
        return msg;
    }

    function mostrarError(campo, texto) {
        const msg = obtenerMensaje(campo);
        msg.textContent = texto;
        msg.style.display = 'block';
    }

    function limpiarError(campo) {
        const msg = campo.parentNode.querySelector('.email-error');
        if (msg) msg.style.display = 'none';
    }

    // Recorre todos los campos de correo de la página, sea cual sea el formulario.
    document.querySelectorAll('input[type="email"], input[name="email"]').forEach(function (campo) {
        const form = campo.form;
        if (!form) return;

        // Al enviar: si el correo no es válido, se cancela el envío y se avisa.
        form.addEventListener('submit', function (e) {
            if (!validarEmail(campo.value.trim())) {
                e.preventDefault();
                mostrarError(campo, 'Introduce un correo electrónico válido.');
                campo.focus();
            }
        });

        // Al corregir el valor, se oculta el aviso.
        campo.addEventListener('input', function () {
            limpiarError(campo);
        });
    });
});
