@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <i class="fas fa-add"></i> Agregar nuevo cliente
        </button>
    </div>

    <div class="mb-4 text-center">
        <h2 class="fw-bold text-primary">Lista de clientes de la empresa</h2>
        <hr class="w-10 mx-auto">
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre completo</th>
                    <th scope="col">Domicilio</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Teléfono</th>
                    <th scope="col" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody id="tbody_clients">
            </tbody>
        </table>
    </div>
    <div id="pagination-links" class="d-flex justify-content-center">
    </div>

</div>

@include('clients.modalAddClient')
@include('clients.modalEditClient')

<script>
    const clientsList = [];
    const getAllClients = async (url = '/clients/get/all') => {
        try {
            const response = await fetch(url);
            const { data } = await response.json();

            clientsList.splice(0, clientsList.length, ...data.data);

            const tbody = document.getElementById('tbody_clients');
            tbody.innerHTML = '';

            clientsList.forEach((client, i) => {
                const tr = document.createElement('tr');

                const fullName = client.user?.name || '---';
                const address = client.address || '---';
                const email = client.user?.email || '---';
                const phone = client.user?.phone || '---';

                tr.innerHTML = `
                    <td>${data.from + i}</td>
                    <td>${fullName}</td>
                    <td>${address}</td>
                    <td>${email}</td>
                    <td>${phone}</td>
                    <td>
                        <div class="d-flex flex-row gap-1 justify-content-center">
                            <button class="btn btn-xs btn-warning d-flex align-items-center gap-1 py-0 px-2" style="font-size: 0.8rem; height: 28px;" onclick="editClient(${client.id})">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button class="btn btn-xs btn-danger d-flex align-items-center gap-1 py-0 px-2" style="font-size: 0.8rem; height: 28px;" onclick="handleDeleteClient(${client.id}, '${client.user?.name}')">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            renderPagination(data);

        } catch (error) {
            console.log('Ocurrió un error al mostrar los clientes ', error);
        }
    }

    const renderPagination = (paginationData) => {
        const paginationContainer = document.getElementById('pagination-links');
        paginationContainer.innerHTML = '';

        const ul = document.createElement('ul');
        ul.className = 'pagination';

        paginationData.links.forEach(link => {
            const li = document.createElement('li');
            li.className = `page-item ${link.active ? 'active' : ''} ${!link.url ? 'disabled' : ''}`;

            const a = document.createElement('a');
            a.className = 'page-link';
            a.href = '#';
            a.innerHTML = link.label;
            if (link.url) {
                a.addEventListener('click', (e) => {
                    e.preventDefault();
                    getAllClients(link.url);
                });
            }

            li.appendChild(a);
            ul.appendChild(li);
        });

        paginationContainer.appendChild(ul);
    }


    getAllClients();

    const editClient = (id) => {
        const client = clientsList.find(c => c.id === id);
        console.log('Cliente seleccionado:', client);

        document.getElementById('client_id').value  = client.id || '';
        document.getElementById('name_').value      = client.user.name || '';
        document.getElementById('phone_').value     = client.user.phone || '';
        document.getElementById('email_').value     = client.user.email || '';
        document.getElementById('address_').value   = client.address || '';

        const modal = new bootstrap.Modal(document.getElementById('exampleModalEdit'));
        modal.show();
    }

    const handleDeleteClient = (client_id, client_name) => {
        Swal.fire({
            title: `Seguro de eliminar a ${client_name}`,
            text: "No se podrán revertir los cambios",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, eliminar!",
            cancelButtonText: 'Cancelar'
            }).then((result) => {
            if (result.isConfirmed) {
                deleteClient(client_id);
            }
        });
    }

    const deleteClient = async(client_id) => {
        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const response = await fetch(`/clients/delete/${client_id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            });
            const { ok, msg } = await response.json();
            
            ok ? showEditMsg('success', msg) : showEditMsg('error', msg);
            getAllClients();
        } catch (error) {
            showEditMsg('error', 'Error al realizar la petición')
        }
    }
</script>
@endsection
