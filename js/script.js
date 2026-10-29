const buscador = document.querySelector("#buscarPelicula");
const filtro = document.querySelector("#filtroGenero");
const tarjetas = document.querySelectorAll(".tarjeta-pelicula");
const mensajeCatalogo = document.querySelector("#mensajeCatalogo");

function filtrarPeliculas() {
    const texto = buscador.value.toLowerCase().trim();
    let cantidad = 0;

    tarjetas.forEach(function(tarjeta) {
        const titulo = tarjeta.querySelector("h2").textContent.toLowerCase();
        const genero = tarjeta.dataset.genero;
        const coincideTexto = titulo.includes(texto);
        const coincideGenero = filtro.value === "todos" || filtro.value === genero;

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

    const parametros = new URLSearchParams(window.location.search);
    const genero = parametros.get("genero");

    if (genero) {
        filtro.value = genero;
        filtrarPeliculas();
    }
}

let favoritas = JSON.parse(localStorage.getItem("cineNocturnoFavoritas") || "[]");
const botones = document.querySelectorAll(".boton-favorita");
const mensajeFavoritas = document.querySelector("#mensajeFavoritas");

botones.forEach(function(boton) {
    const pelicula = boton.dataset.pelicula;

    if (favoritas.includes(pelicula)) {
        boton.textContent = "Quitar de favoritas";
        boton.classList.add("favorita");
        boton.setAttribute("aria-pressed", "true");
    }

    boton.addEventListener("click", function() {
        if (favoritas.includes(pelicula)) {
            favoritas = favoritas.filter(function(nombre) {
                return nombre !== pelicula;
            });
            boton.textContent = "Guardar en favoritas";
            boton.classList.remove("favorita");
            boton.setAttribute("aria-pressed", "false");
            mensajeFavoritas.textContent = "Película quitada de favoritas.";
        } else {
            favoritas.push(pelicula);
            boton.textContent = "Quitar de favoritas";
            boton.classList.add("favorita");
            boton.setAttribute("aria-pressed", "true");
            mensajeFavoritas.textContent = "Película guardada en favoritas.";
        }

        localStorage.setItem("cineNocturnoFavoritas", JSON.stringify(favoritas));
    });
});

const listaFavoritas = document.querySelector("#listaFavoritas");
const estadoFavoritas = document.querySelector("#estadoFavoritas");

if (listaFavoritas && estadoFavoritas) {
    if (favoritas.length === 0) {
        estadoFavoritas.textContent = "Aún no guardaste películas favoritas.";
    } else {
        favoritas.forEach(function(pelicula) {
            const elemento = document.createElement("li");
            elemento.textContent = pelicula;
            listaFavoritas.appendChild(elemento);
        });

        estadoFavoritas.textContent = "Estas son tus películas guardadas.";
    }
}

const campoMensaje = document.querySelector("#mensaje");
const contador = document.querySelector("#contador");

if (campoMensaje && contador) {
    campoMensaje.addEventListener("input", function() {
        contador.textContent = campoMensaje.value.length + " de 300 caracteres";
    });
}

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