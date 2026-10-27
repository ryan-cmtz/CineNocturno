/* Muestra el año actual en el pie de cada página */
const anios = document.querySelectorAll(".anio");

anios.forEach(function(anio) {
    anio.textContent = new Date().getFullYear();
});

/* Busca y filtra las películas del catálogo */
const buscador = document.querySelector("#buscarPelicula");
const filtro = document.querySelector("#filtroGenero");
const tarjetas = document.querySelectorAll(".tarjeta-pelicula");
const mensajeCatalogo = document.querySelector("#mensajeCatalogo");

function filtrarPeliculas() {
    const texto = buscador.value.toLowerCase().trim();
    const generoElegido = filtro.value;
    let cantidad = 0;

    tarjetas.forEach(function(tarjeta) {
        const titulo = tarjeta.dataset.titulo.toLowerCase();
        const genero = tarjeta.dataset.genero;
        const coincideTexto = titulo.includes(texto);
        const coincideGenero = generoElegido === "todos" || generoElegido === genero;

        if (coincideTexto && coincideGenero) {
            tarjeta.classList.remove("oculta");
            cantidad++;
        } else {
            tarjeta.classList.add("oculta");
        }
    });

    mensajeCatalogo.textContent = "Películas encontradas: " + cantidad;
}

if (buscador && filtro) {
    buscador.addEventListener("input", filtrarPeliculas);
    filtro.addEventListener("change", filtrarPeliculas);
}

/* Guarda las películas favoritas en el navegador */
const botonesFavoritas = document.querySelectorAll(".boton-favorita");
const mensajeFavoritas = document.querySelector("#mensajeFavoritas");
let favoritas = JSON.parse(localStorage.getItem("cineNocturnoFavoritas")) || [];

function actualizarBotonFavorita(boton) {
    const pelicula = boton.dataset.pelicula;
    const guardada = favoritas.includes(pelicula);

    boton.classList.toggle("favorita", guardada);

    if (guardada) {
        boton.textContent = "Quitar de favoritas";
    } else {
        boton.textContent = "Guardar en favoritas";
    }
}

botonesFavoritas.forEach(function(boton) {
    actualizarBotonFavorita(boton);

    boton.addEventListener("click", function() {
        const pelicula = boton.dataset.pelicula;
        const posicion = favoritas.indexOf(pelicula);

        if (posicion === -1) {
            favoritas.push(pelicula);
            mensajeFavoritas.textContent = "Película guardada en favoritas.";
        } else {
            favoritas.splice(posicion, 1);
            mensajeFavoritas.textContent = "Película quitada de favoritas.";
        }

        localStorage.setItem("cineNocturnoFavoritas", JSON.stringify(favoritas));
        actualizarBotonFavorita(boton);
    });
});

/* Cuenta los caracteres del mensaje de contacto */
const mensaje = document.querySelector("#mensaje");
const contador = document.querySelector("#contador");

if (mensaje && contador) {
    mensaje.addEventListener("input", function() {
        contador.textContent = mensaje.value.length + " de 300 caracteres";
    });
}

/* Valida el formulario y muestra una respuesta */
const formulario = document.querySelector("#formularioContacto");
const respuesta = document.querySelector("#respuestaFormulario");

if (formulario && respuesta) {
    formulario.addEventListener("submit", function(evento) {
        evento.preventDefault();
        respuesta.classList.remove("mensaje-exito", "mensaje-error");

        if (!formulario.checkValidity()) {
            respuesta.textContent = "Revisa los campos obligatorios antes de enviar.";
            respuesta.classList.add("mensaje-error");
            formulario.reportValidity();
            return;
        }

        const nombre = document.querySelector("#nombre").value.trim();
        respuesta.textContent = "Gracias, " + nombre + ". La recomendación quedó registrada en esta demostración.";
        respuesta.classList.add("mensaje-exito");
        formulario.reset();

        if (contador) {
            contador.textContent = "0 de 300 caracteres";
        }
    });
}
