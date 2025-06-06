@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Agregar nuevo cliente
        </button>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>id</th>
                <th>Nombre completo</th>
                <th>Domicilio</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tbody_clients">
        </tbody>
    </table>

</div>

@include('clients.modalAddClient')
@include('clients.modalEditClient')

<script>
    const clientsList = [];
    const getAllClients = async() => {
        try {
            const response = await fetch('/clients/get/all');
            const {data }  = await response.json();
            console.log('data ',data);
            clientsList.splice(0, clientsList.length, ...data); 

            const tbody = document.getElementById('tbody_clients');
            tbody.innerHTML = '';

            clientsList.forEach((client, i) => {
                const tr = document.createElement('tr');

                const fullName = client.user?.name || '---';
                const address  = client.address || '---';
                const email    = client.user?.email || '---';
                const phone    = client.user?.phone || '---';

                tr.innerHTML = `
                    <td>${i+1}</td>
                    <td>${fullName}</td>
                    <td>${address}</td>
                    <td>${email}</td>
                    <td>${phone}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="editatClient(${client.id})">Editar</button>
                        <button class="btn btn-sm btn-danger" onclick="handleDeleteClient(${client.id}, '${client.user.name}')">Eliminar</button>
                    </td>
                `;
                tbody.appendChild(tr);
         });
            
        } catch (error) {
            console.log('Ocurrió un error al mostrar los expositores ',error);
        }
    }

    getAllClients();

    const editatClient = (id) => {
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
            confirmButtonText: "Si, eliminar!"
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
