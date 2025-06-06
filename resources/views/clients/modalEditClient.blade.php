<div class="modal fade" id="exampleModalEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar cliente</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form id="formClientEdit" name="formClientEdit">
                    @csrf
                    <input type="hidden" class="form-control" id="client_id" name="client_id" required>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name_" class="form-label">Nombre completo</label>
                                <input type="text" class="form-control" id="name_" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone_" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="phone_" name="phone" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email_" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="email_" name="email" disabled required>
                            </div>
                            <div class="mb-3">
                                <label for="address_" class="form-label">Domicilio</label>
                                <input type="text" class="form-control" id="address_" name="address" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" >Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const formClientEdit = document.getElementById('formClientEdit');

    formClientEdit.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(formClientEdit);
        formData.append('_method', 'PUT');

        const options = {
            method: 'POST',
            body: formData
        };

        try {
            const response    = await fetch('/clients/edit', options);
            const { ok, msg } = await response.json();
            
            ok ? showEditMsg('success', msg) : showEditMsg('error', msg);

            if (ok) {
                formClientEdit.reset();
                const modalElement  = document.getElementById('exampleModalEdit');
                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                modalInstance.hide();
                getAllClients();
            }
        } catch (error) {
            showEditMsg('error', 'Error al realizar la petición')
        }
    });

    const showEditMsg = (icon, text) => {
        Swal.fire({
            icon,
            title: 'Respuesta',
            text
        });
    }
</script>