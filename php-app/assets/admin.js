const apiPeliculas = "../api/peliculas.php";
const formularioPelicula = document.querySelector("#formularioPelicula");
const listaAdmin = document.querySelector("#listaAdmin");
const token = document.querySelector("meta[name=csrf-token]").content;

async function pedirPeliculas() {
    const respuesta = await fetch(apiPeliculas);
    const resultado = await respuesta.json();
    listaAdmin.innerHTML = "";
    resultado.datos.forEach(function(pelicula) {
        const fila = document.createElement("tr");
        fila.innerHTML = `<td>${pelicula.id}</td><td>${escapar(pelicula.titulo)}</td><td>${escapar(pelicula.genero)}</td><td><button class="btn btn-sm boton-principal editar" data-id="${pelicula.id}">Editar</button> <button class="btn btn-sm btn-danger borrar" data-id="${pelicula.id}">Borrar</button></td>`;
        listaAdmin.appendChild(fila);
    });
}

function escapar(texto) {
    const div = document.createElement("div");
    div.textContent = texto;
    return div.innerHTML;
}

formularioPelicula.addEventListener("submit", async function(evento) {
    evento.preventDefault();
    const respuesta = await fetch(apiPeliculas, {method:"POST", headers:{"X-CSRF-Token":token}, body:new FormData(formularioPelicula)});
    const resultado = await respuesta.json();
    await Swal.fire(resultado.ok ? "Listo" : "Revisa los datos", resultado.mensaje, resultado.ok ? "success" : "error");
    if (resultado.ok) { formularioPelicula.reset(); document.querySelector("#idPelicula").value=""; $("#sinopsis").summernote("reset"); pedirPeliculas(); }
});

listaAdmin.addEventListener("click", async function(evento) {
    const boton = evento.target;
    if (boton.classList.contains("borrar")) {
        const confirmacion = await Swal.fire({title:"¿Borrar película?", text:"Esta acción quitará la película del catálogo.", icon:"warning", showCancelButton:true, confirmButtonText:"Borrar", cancelButtonText:"Cancelar"});
        if (!confirmacion.isConfirmed) return;
        const respuesta = await fetch(apiPeliculas, {method:"DELETE", headers:{"Content-Type":"application/json", "X-CSRF-Token":token}, body:JSON.stringify({id:boton.dataset.id})});
        const resultado = await respuesta.json();
        await Swal.fire(resultado.ok ? "Listo" : "Error", resultado.mensaje, resultado.ok ? "success" : "error");
        pedirPeliculas();
    }
    if (boton.classList.contains("editar")) {
        const respuesta = await fetch(apiPeliculas);
        const resultado = await respuesta.json();
        const pelicula = resultado.datos.find(function(item) { return item.id == boton.dataset.id; });
        if (pelicula) {
            document.querySelector("#idPelicula").value = pelicula.id;
            document.querySelector("#titulo").value = pelicula.titulo;
            document.querySelector("#genero").value = pelicula.genero;
            $("#sinopsis").summernote("code", pelicula.sinopsis);
            window.scrollTo(0,0);
        }
    }
});

pedirPeliculas();