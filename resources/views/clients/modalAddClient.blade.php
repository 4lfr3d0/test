<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar cliente</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form id="formClient" name="formClient">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Domicilio</label>
                                <input type="text" class="form-control" id="address" name="address"  required>
                            </div>
                        </div>
                    </div>

                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" >Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const formClient = document.getElementById('formClient');

    formClient.addEventListener('submit', async function(e) {
        e.preventDefault();

        const options = {
            method: 'POST',
            body: new FormData(formClient)
        }

        try {
            const response    = await fetch('/clients/save', options);
            const { ok, msg } = await response.json();
            
            ok ? showMsg('success', msg) : showMsg('error', msg);

            if(ok) {
                formClient.reset();
                const modalElement  = document.getElementById('exampleModal');
                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                modalInstance.hide();
                getAllClients();
            }
        } catch (error) {
            showMsg('error', 'Error al realizar la petición')
        }
    });

    const showMsg = (icon, text) => {
        Swal.fire({
            icon,
            title: 'Respuesta',
            text
        });
    }
</script>