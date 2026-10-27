const anios = document.querySelectorAll(".anio");

anios.forEach(function(anio) {
    anio.textContent = new Date().getFullYear();
});

function quitarAcentos(texto) {
    return texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

const buscador = document.querySelector("#buscarPelicula");
const filtro = document.querySelector("#filtroGenero");
const tarjetas = document.querySelectorAll(".tarjeta-pelicula");
const mensajeCatalogo = document.querySelector("#mensajeCatalogo");

function filtrarPeliculas() {
    const texto = quitarAcentos(buscador.value.toLowerCase().trim());
    const generoElegido = filtro.value;
    let cantidad = 0;

    tarjetas.forEach(function(tarjeta) {
        const titulo = quitarAcentos(tarjeta.dataset.titulo.toLowerCase());
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

    const parametros = new URLSearchParams(window.location.search);
    const generoInicial = parametros.get("genero");

    if (generoInicial) {
        filtro.value = generoInicial;
        filtrarPeliculas();
    }
}

const botonesFavoritas = document.querySelectorAll(".boton-favorita");
const mensajeFavoritas = document.querySelector("#mensajeFavoritas");
let favoritas = JSON.parse(localStorage.getItem("cineNocturnoFavoritas") || "[]");

function actualizarBotonFavorita(boton) {
    const pelicula = boton.dataset.pelicula;
    const guardada = favoritas.includes(pelicula);

    boton.classList.toggle("favorita", guardada);
    boton.setAttribute("aria-pressed", guardada);

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


const mensaje = document.querySelector("#mensaje");
const contador = document.querySelector("#contador");

if (mensaje && contador) {
    mensaje.addEventListener("input", function() {
        contador.textContent = mensaje.value.length + " de 300 caracteres";
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