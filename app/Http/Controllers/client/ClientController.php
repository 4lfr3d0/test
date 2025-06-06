<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use App\Models\Client;
use App\Models\User;

class ClientController extends Controller {
    
    public function getAllClients() {
        try {
            $clients = Client::with('user')->paginate(10);

            return  response()->json(['ok'=>true, 'msg'=>'Petición realizada correctamente', 'data'=>$clients], 200);
        } catch (\Exception $error) {
            \Log::error('Error en el HomeController -> getAllClients(): ' . $error->getMessage());
            return  response()->json(['ok'=>false, 'msg'=>'Error al obtener los clientes', 'data'=>''], 500);
        }
    }

    public function saveNewClient(Request $request) {
        try {
            $existe = $this->validateEmail($request->email);
            if ($existe) return response()->json(['ok' => false, 'msg' => 'El email ya está registrado.'], 409);

            $isRight = $this->verifyDataToSave($request);
            if (!$isRight) return response()->json(['ok' => false, 'msg' => 'Faltan datos por completar.'], 409); 

            $user = $this->saveUser($request);

            $this->saveClient($request, $user->id);

            return response()->json(['ok' => true, 'msg' => 'Usuario creado correctamente',], 201);
        } catch (\Exception $error) {
             \Log::error('Error en el ClientController -> saveNewClient(): ' . $error->getMessage());
            return  response()->json(['ok'=>false, 'msg'=>'Error al guardar el cliente'], 500);
        }
    }

    public function saveClient($request, $user_id) {
        try {
            $full_name = $request->input('name') . ' '.$request->input('last_name');

            Client::create([
                'last_name'  => $request->input('last_name'),
                'address'    => $request->input('address'),
                'user_id'    => $user_id,
            ]);
        } catch (\Exception $error) {
            \Log::error('Error al crear el cliente ClientController -> saveClient() '. $error->getMessage());
            throw new \Exception('Error al guardar el usuario');
        }
    }

    public function saveUser($request) {
        try {
            $full_name = $request->input('name') . ' '.$request->input('last_name');

            return User::create([
                'rol_id'   => '3',
                'name'     => $full_name,
                'phone'    => $request->input('phone'),
                'email'    => $request->input('email'),
                'password' => Hash::make('1234567890'),
            ]);
        } catch (\Exception $error) {
            \Log::error('Error al crear el usuario ClientController -> saveUser() '. $error->getMessage());
            throw new \Exception('Error al guardar el usuario');
        }
    }

    public function validateEmail($email) {
        try {
            return User::where('email', $email)->exists();
        } catch (\Exception $error) {
            \Log::error('Error al validar el correo ClientController -> validateEmail() '. $error->getMessage());
            throw new \Exception('Error al verificar el correo');
        }
    }

    public function editClient(Request $request) {
        try {
            $isRight = $this->verifyDataToEdit($request);
            if (!$isRight) return response()->json(['ok' => false, 'msg' => 'Faltan datos por completar.'], 409);

            $client = Client::findOrFail($request->client_id);
            $user   = $client->user;

            $user->name  = $request->name;
            $user->phone = $request->phone;
            $user->save();

            $client->last_name = $request->last_name;
            $client->address   = $request->address;
            $client->save();

        return response()->json(['ok' => true, 'msg' => 'Datos guardados correctamente',], 201);
        } catch (\Exception $error) {
             \Log::error('Error en el ClientController -> editClient(): ' . $error->getMessage());
            return  response()->json(['ok'=>false, 'msg'=>'Error al guardar cambios del cliente'], 500);
        }
    }

    public function deleteClient($client_id) {
        try {
            $client = Client::findOrFail($client_id);

            $user = $client->user;
            $client->delete();

            if ($user) {
                $user->delete();
            }

            return response()->json(['ok' => true, 'msg' => 'Cliente eliminado correctamente'], 200);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar cliente ClientController -> deleteClient() : ' . $e->getMessage());
            return response()->json(['ok' => false, 'msg' => 'Error al eliminar cliente'], 500);
        }
    }

    public function verifyDataToSave($request) {
        $requiredFields = ['name', 'last_name', 'phone', 'email', 'address'];

        foreach ($requiredFields as $field) {
            if (!$request->filled($field)) { 
                return false;
            }
        }
        return true;
    }

    public function verifyDataToEdit($request) {
        $requiredFields = ['name', 'phone', 'address'];

        foreach ($requiredFields as $field) {
            if (!$request->filled($field)) { 
                return false;
            }
        }
        return true;
    }

}
