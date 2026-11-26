const buscador = document.querySelector("#buscarPelicula");
const filtro = document.querySelector("#filtroGenero");
const tarjetas = document.querySelectorAll(".pelicula");
const mensaje = document.querySelector("#mensajeCatalogo");

function filtrarPeliculas() {
    const texto = buscador.value.toLowerCase().trim();
    let cantidad = 0;
    tarjetas.forEach(function(tarjeta) {
        const titulo = tarjeta.dataset.titulo.toLowerCase();
        const generoCoincide = filtro.value === "todos" || filtro.value === tarjeta.dataset.genero;
        const textoCoincide = titulo.includes(texto);
        tarjeta.classList.toggle("oculta", !(generoCoincide && textoCoincide));
        if (generoCoincide && textoCoincide) cantidad++;
    });
    mensaje.textContent = "Películas encontradas: " + cantidad;
}
if (buscador && filtro) {
    buscador.addEventListener("input", filtrarPeliculas);
    filtro.addEventListener("change", filtrarPeliculas);
    const generoInicial = new URLSearchParams(window.location.search).get("genero");
    if (generoInicial) { filtro.value = generoInicial; filtrarPeliculas(); }
}

let favoritas = JSON.parse(localStorage.getItem("cineNocturnoFavoritas") || "[]");
const mensajeFavoritas = document.querySelector("#mensajeFavoritas");
function actualizarBoton(boton) {
    const estaGuardada = favoritas.includes(boton.dataset.pelicula);
    boton.textContent = estaGuardada ? "Quitar de favoritas" : "Guardar en favoritas";
    boton.classList.toggle("favorita", estaGuardada);
    boton.setAttribute("aria-pressed", estaGuardada);
}
document.querySelectorAll(".boton-favorita").forEach(function(boton) {
    actualizarBoton(boton);
    boton.addEventListener("click", function() {
        const pelicula = boton.dataset.pelicula;
        if (favoritas.includes(pelicula)) {
            favoritas = favoritas.filter(function(titulo) { return titulo !== pelicula; });
            if (mensajeFavoritas) mensajeFavoritas.textContent = "Película quitada de favoritas.";
        } else {
            favoritas.push(pelicula);
            if (mensajeFavoritas) mensajeFavoritas.textContent = "Película guardada en favoritas.";
        }
        localStorage.setItem("cineNocturnoFavoritas", JSON.stringify(favoritas));
        actualizarBoton(boton);
    });
});
const listaFavoritas = document.querySelector("#listaFavoritas");
const estadoFavoritas = document.querySelector("#estadoFavoritas");
if (listaFavoritas && estadoFavoritas) {
    if (favoritas.length) {
        favoritas.forEach(function(titulo) { const li = document.createElement("li"); li.textContent = titulo; listaFavoritas.appendChild(li); });
        estadoFavoritas.textContent = "Estas son tus películas guardadas en este navegador.";
    } else estadoFavoritas.textContent = "Aún no guardaste películas favoritas.";
}
const mensajeContacto = document.querySelector("#mensajeContacto");
const contadorContacto = document.querySelector("#contadorContacto");
if (mensajeContacto && contadorContacto) mensajeContacto.addEventListener("input", function() { contadorContacto.textContent = mensajeContacto.value.length + " de 300 caracteres"; });
const formularioContacto = document.querySelector("#formularioContacto");
if (formularioContacto) formularioContacto.addEventListener("submit", function(evento) {
    evento.preventDefault();
    const respuesta = document.querySelector("#respuestaContacto");
    respuesta.classList.remove("mensaje-error", "mensaje-exito");
    if (!formularioContacto.checkValidity()) { formularioContacto.reportValidity(); respuesta.textContent = "Revisa los campos antes de enviar."; respuesta.classList.add("mensaje-error"); return; }
    respuesta.textContent = "Gracias, " + formularioContacto.nombre.value.trim() + ". Tu recomendación quedó registrada en esta demostración.";
    respuesta.classList.add("mensaje-exito");
    formularioContacto.reset();
    contadorContacto.textContent = "0 de 300 caracteres";
});