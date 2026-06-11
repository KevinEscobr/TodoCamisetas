<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    description: "API RESTful para la gestión del catálogo de camisetas, clientes B2B y tallas.",
    title: "TodoCamisetas API"
)]
#[OA\Server(url: "/api", description: "Servidor Principal")]
#[OA\Schema(
    schema: "Camiseta",
    title: "Camiseta",
    description: "Modelo que representa una camiseta deportiva",
    required: ["titulo", "club", "pais", "tipo", "color", "precio", "sku"],
    properties: [
        new OA\Property(property: "id", type: "integer", readOnly: true, example: 1),
        new OA\Property(property: "titulo", type: "string", example: "Camiseta Local 2026"),
        new OA\Property(property: "club", type: "string", example: "Real Madrid"),
        new OA\Property(property: "pais", type: "string", example: "España"),
        new OA\Property(property: "tipo", type: "string", example: "Local"),
        new OA\Property(property: "color", type: "string", example: "Blanco"),
        new OA\Property(property: "precio", type: "integer", example: 85000),
        new OA\Property(property: "precio_oferta", type: "integer", nullable: true, example: 70000),
        new OA\Property(property: "cantidad", type: "integer", example: 50),
        new OA\Property(property: "detalles", type: "string", nullable: true, example: "Poliéster reciclado, versión jugador."),
        new OA\Property(property: "sku", type: "string", example: "RM-LOC-2026"),
        new OA\Property(property: "precio_final", type: "integer", readOnly: true, example: 70000, description: "Precio final calculado dinámicamente para el cliente")
    ]
)]
#[OA\Schema(
    schema: "Cliente",
    title: "Cliente",
    description: "Modelo que representa un cliente B2B",
    required: ["nombre_comercial", "rut_id_comercial", "direccion", "categoria", "contacto_nombre", "contacto_correo", "porcentaje_oferta"],
    properties: [
        new OA\Property(property: "id", type: "integer", readOnly: true, example: 1),
        new OA\Property(property: "nombre_comercial", type: "string", example: "Todo Deportes S.A."),
        new OA\Property(property: "rut_id_comercial", type: "string", example: "77.777.777-7"),
        new OA\Property(property: "direccion", type: "string", example: "Av. Providencia 1234, Santiago"),
        new OA\Property(property: "categoria", type: "string", enum: ["Regular", "Preferencial"], example: "Preferencial"),
        new OA\Property(property: "contacto_nombre", type: "string", example: "Juan Pérez"),
        new OA\Property(property: "contacto_correo", type: "string", format: "email", example: "juan@tododeportes.cl"),
        new OA\Property(property: "porcentaje_oferta", type: "number", format: "float", example: 15.00)
    ]
)]
#[OA\Schema(
    schema: "Talla",
    title: "Talla",
    description: "Modelo que representa una talla de prenda",
    required: ["nombre"],
    properties: [
        new OA\Property(property: "id", type: "integer", readOnly: true, example: 1),
        new OA\Property(property: "nombre", type: "string", example: "M")
    ]
)]
class ApiDocs
{
    // ==========================================
    // CAMISETAS
    // ==========================================
    #[OA\Get(path: '/camisetas', summary: 'Listar todas las camisetas', tags: ['Camisetas'])]
    #[OA\Parameter(name: 'cliente_id', in: 'query', required: false, description: 'ID del cliente para calcular el precio dinámico', schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Lista de camisetas obtenida correctamente')]
    public function getCamisetas() {}

    #[OA\Post(path: '/camisetas', summary: 'Crear nueva camiseta', tags: ['Camisetas'])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'titulo', type: 'string'),
                new OA\Property(property: 'club', type: 'string'),
                new OA\Property(property: 'pais', type: 'string'),
                new OA\Property(property: 'tipo', type: 'string'),
                new OA\Property(property: 'color', type: 'string'),
                new OA\Property(property: 'precio', type: 'integer'),
                new OA\Property(property: 'precio_oferta', type: 'integer', nullable: true),
                new OA\Property(property: 'cantidad', type: 'integer'),
                new OA\Property(property: 'tallas', type: 'array', items: new OA\Items(type: 'integer')),
                new OA\Property(property: 'sku', type: 'string')
            ]
        )
    )]
    #[OA\Response(response: 201, description: 'Camiseta creada')]
    #[OA\Response(response: 422, description: 'Error de validación')]
    public function storeCamisetas() {}

    #[OA\Get(path: '/camisetas/{id}', summary: 'Mostrar una camiseta', tags: ['Camisetas'])]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'cliente_id', in: 'query', required: false, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Camiseta encontrada')]
    #[OA\Response(response: 404, description: 'Camiseta no encontrada')]
    public function showCamisetas() {}

    #[OA\Put(path: '/camisetas/{id}', summary: 'Actualizar camiseta', tags: ['Camisetas'])]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'precio', type: 'integer'),
                new OA\Property(property: 'cantidad', type: 'integer')
            ]
        )
    )]
    #[OA\Response(response: 200, description: 'Camiseta actualizada')]
    #[OA\Response(response: 404, description: 'No encontrada')]
    public function updateCamisetas() {}

    #[OA\Delete(path: '/camisetas/{id}', summary: 'Eliminar camiseta', tags: ['Camisetas'])]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Camiseta eliminada')]
    #[OA\Response(response: 404, description: 'No encontrada')]
    public function deleteCamisetas() {}

    // ==========================================
    // CLIENTES
    // ==========================================
    #[OA\Get(path: '/clientes', summary: 'Listar todos los clientes', tags: ['Clientes'])]
    #[OA\Response(response: 200, description: 'Lista de clientes')]
    public function getClientes() {}

    #[OA\Post(path: '/clientes', summary: 'Crear nuevo cliente', tags: ['Clientes'])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'nombre_comercial', type: 'string'),
                new OA\Property(property: 'rut_id_comercial', type: 'string'),
                new OA\Property(property: 'direccion', type: 'string'),
                new OA\Property(property: 'categoria', type: 'string', description: 'Regular o Preferencial'),
                new OA\Property(property: 'contacto_nombre', type: 'string'),
                new OA\Property(property: 'contacto_correo', type: 'string'),
                new OA\Property(property: 'porcentaje_oferta', type: 'number', format: 'float')
            ]
        )
    )]
    #[OA\Response(response: 201, description: 'Cliente creado')]
    #[OA\Response(response: 422, description: 'Error de validación')]
    public function storeClientes() {}

    #[OA\Get(path: '/clientes/{id}', summary: 'Mostrar un cliente', tags: ['Clientes'])]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Cliente encontrado')]
    #[OA\Response(response: 404, description: 'No encontrado')]
    public function showClientes() {}

    #[OA\Put(path: '/clientes/{id}', summary: 'Actualizar cliente', tags: ['Clientes'])]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'categoria', type: 'string')
            ]
        )
    )]
    #[OA\Response(response: 200, description: 'Cliente actualizado')]
    #[OA\Response(response: 404, description: 'No encontrado')]
    public function updateClientes() {}

    #[OA\Delete(path: '/clientes/{id}', summary: 'Eliminar cliente', tags: ['Clientes'])]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Cliente eliminado')]
    #[OA\Response(response: 400, description: 'No se puede eliminar porque tiene camisetas asociadas')]
    #[OA\Response(response: 404, description: 'No encontrado')]
    public function deleteClientes() {}

    #[OA\Get(path: '/clientes/{id}/camisetas', summary: 'Listar camisetas de un cliente', tags: ['Clientes'])]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Camisetas listadas correctamente')]
    #[OA\Response(response: 404, description: 'Cliente no encontrado')]
    public function getCamisetasCliente() {}

    // ==========================================
    // TALLAS
    // ==========================================
    #[OA\Get(path: '/tallas', summary: 'Listar todas las tallas', tags: ['Tallas'])]
    #[OA\Response(response: 200, description: 'Operación exitosa')]
    public function getTallas() {}

    #[OA\Post(path: '/tallas', summary: 'Crear nueva talla', tags: ['Tallas'])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'nombre', type: 'string', example: 'XXL')
            ]
        )
    )]
    #[OA\Response(response: 201, description: 'Talla creada')]
    public function storeTallas() {}

    #[OA\Get(path: '/tallas/{id}', summary: 'Mostrar talla', tags: ['Tallas'])]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Talla encontrada')]
    public function showTallas() {}

    #[OA\Put(path: '/tallas/{id}', summary: 'Actualizar talla', tags: ['Tallas'])]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'nombre', type: 'string')
            ]
        )
    )]
    #[OA\Response(response: 200, description: 'Talla actualizada')]
    public function updateTallas() {}

    #[OA\Delete(path: '/tallas/{id}', summary: 'Eliminar talla', tags: ['Tallas'])]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Talla eliminada')]
    public function deleteTallas() {}
}
