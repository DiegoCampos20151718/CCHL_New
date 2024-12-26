document.addEventListener('DOMContentLoaded', function () {
    // Acceder a los datos del atributo 'data-*'
    const detailsElement = document.getElementById('folioDetails');

    if (detailsElement) {
        // Mostrar los detalles del curso
        document.getElementById('nocontrolDetail').textContent = detailsElement.getAttribute('data-nocontrol');
        document.getElementById('statusDetail').textContent = detailsElement.getAttribute('data-status');
        document.getElementById('planDetail').textContent = detailsElement.getAttribute('data-plan');
        document.getElementById('nombreplanDetail').textContent = detailsElement.getAttribute('data-nombreplan');
        document.getElementById('areaDetail').textContent = detailsElement.getAttribute('data-area');
        document.getElementById('finicioDetail').textContent = detailsElement.getAttribute('data-finicio');
        document.getElementById('fterminoDetail').textContent = detailsElement.getAttribute('data-ftermino');

        // Mostrar la sección con los detalles
        detailsElement.classList.remove('hidden');

        // Botón para subir certificados
        const uploadBtn = document.getElementById('uploadCertificatesBtn');
        if (uploadBtn) {
            uploadBtn.addEventListener('click', function () {
                const nocontrol = detailsElement.getAttribute('data-nocontrol');
                if (nocontrol) {
                    // Redirigir a la página para subir certificados
                    const popup = window.open(
                        'upload_certificates.php?nocontrol=' + encodeURIComponent(nocontrol),
                        'Subir Certificados',
                        'width=800,height=600'
                    );

                    // Verificar si el popup se abrió correctamente
                    if (!popup) {
                        alert('Por favor, habilita los pop-ups en tu navegador.');
                    }
                } else {
                    alert('Número de control no encontrado.');
                }
            });
        }

        // Mostrar las carpetas y archivos en un modal
// Mostrar las carpetas y archivos en un modal
$('#viewCertificatesBtn').on('click', function() {
    $.ajax({
        url: 'consultar_certificados.php',
        type: 'GET',
        success: function(response) {
            console.log(response);  // Ver la respuesta cruda en la consola
            var carpetas = JSON.parse(response); // Parsear la respuesta JSON

            var contenido = '';

            if (Array.isArray(carpetas) && carpetas.length > 0) {
                carpetas.forEach(function(carpeta) {
                    console.log(carpeta);  // Ver cada carpeta en la consola

                    // Verificar si 'archivos' es un objeto y convertirlo a un arreglo
                    if (typeof carpeta.archivos === 'object') {
                        var archivosArray = Object.values(carpeta.archivos);  // Convertir objeto a arreglo

                        contenido += '<div class="carpeta mb-4">';
                        contenido += '<h4 class="text-primary" data-bs-toggle="collapse" href="#collapse-' + carpeta.carpeta + '" role="button" aria-expanded="false" aria-controls="collapse-' + carpeta.carpeta + '">';
                        contenido += 'Carpeta: ' + carpeta.carpeta + ' <i class="bi bi-chevron-down"></i>';
                        contenido += '</h4>';
                        contenido += '<div class="collapse" id="collapse-' + carpeta.carpeta + '">';
                        contenido += '<ul class="list-group">';

                        archivosArray.forEach(function(archivo) {
                            contenido += '<li class="list-group-item d-flex justify-content-between align-items-center">';
                            contenido += '<a href="assets/Certificados/' + carpeta.carpeta + '/' + archivo + '" target="_blank" class="text-decoration-none">' + archivo + '</a>';
                            contenido += '<button class="btn btn-danger btn-sm delete-btn" data-carpeta="' + carpeta.carpeta + '" data-archivo="' + archivo + '">Eliminar</button>';
                            contenido += '<button class="btn btn-warning btn-sm rename-btn" data-carpeta="' + carpeta.carpeta + '" data-archivo="' + archivo + '">Renombrar</button>';
                            contenido += '</li>';
                        });

                        contenido += '</ul>';
                        contenido += '</div>';  // Cierra el div de collapse
                        contenido += '</div>';  // Cierra el div de carpeta
                    } else {
                        contenido += '<p>No se encontraron archivos PDF en la carpeta ' + carpeta.carpeta + '.</p>';
                    }
                });
            } else {
                contenido = '<p>No se encontraron carpetas ni certificados.</p>';
            }

            // Mostrar los resultados en el contenedor adecuado
            $('#certificadosModalContent').html(contenido);

            // Abrir el modal
            $('#certificadosModal').modal('show');

            // Agregar funcionalidad de eliminación
            $('.delete-btn').on('click', function() {
                var carpeta = $(this).data('carpeta');
                var archivo = $(this).data('archivo');

                // Confirmar eliminación
                if (confirm('¿Estás seguro de que deseas eliminar el archivo: ' + archivo + '?')) {
                    eliminarArchivo(carpeta, archivo);
                }
            });

            // Agregar funcionalidad de renombrar archivo
            $('.rename-btn').on('click', function() {
                var carpeta = $(this).data('carpeta');
                var archivo = $(this).data('archivo');

                // Mostrar el campo para renombrar
                var nuevoNombre = prompt("Introduce el nuevo nombre para el archivo:", archivo);
                if (nuevoNombre && nuevoNombre !== archivo) {
                    renombrarArchivo(carpeta, archivo, nuevoNombre);
                }
            });
        },
        error: function() {
            alert("Hubo un error al obtener los certificados.");
        }
    });
});

// Función para eliminar el archivo
function eliminarArchivo(carpeta, archivo) {
    $.ajax({
        url: 'eliminar_archivo.php',
        type: 'POST',
        data: {
            carpeta: carpeta,
            archivo: archivo
        },
        success: function(response) {
            if (response === 'success') {
                alert('Archivo eliminado exitosamente.');
                location.reload(); // Recargar la página para ver los cambios
            } else {
                alert('Hubo un error al eliminar el archivo.');
            }
        },
        error: function() {
            alert("Hubo un error al intentar eliminar el archivo.");
        }
    });
}

// Función para renombrar el archivo
function renombrarArchivo(carpeta, archivo, nuevoNombre) {
    $.ajax({
        url: 'renombrar_archivo.php',
        type: 'POST',
        data: {
            carpeta: carpeta,
            archivo: archivo,
            nuevoNombre: nuevoNombre
        },
        success: function(response) {
            if (response === 'success') {
                alert('Archivo renombrado exitosamente.');
                location.reload(); // Recargar la página para ver los cambios
            } else {
                alert('Hubo un error al renombrar el archivo.');
            }
        },
        error: function() {
            alert("Hubo un error al intentar renombrar el archivo.");
        }
    });
}

        
        // Manejo del botón 'Guardar Archivos' dentro de 'DOMContentLoaded'
        const saveFilesBtn = document.getElementById('saveFilesBtn');
        if (saveFilesBtn) {
            saveFilesBtn.addEventListener('click', function () {
                const form = document.getElementById('uploadForm');
                const formData = new FormData(form);

                const nocontrol = detailsElement.getAttribute('data-nocontrol');
                if (nocontrol) {
                    formData.append('nocontrol', nocontrol);

                    fetch('upload_certificates.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Archivos subidos correctamente');
                            window.close(); // Cerrar el popup después de una carga exitosa
                        } else {
                            alert('Error al subir archivos: ' + data.errors.join(', '));
                        }
                    })
                    .catch(error => {
                        alert('Error al procesar la solicitud: ' + error.message);
                    });
                } else {
                    alert('Número de control no encontrado.');
                }
            });
        }
    } else {
        console.error('Elemento con id "folioDetails" no encontrado en la página.');
    }
});
