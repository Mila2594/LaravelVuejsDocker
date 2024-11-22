<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HelloWorldController extends Controller
{
    /**
     * Lista todos los ficheros de la carpeta storage/app.
     *
     * @return JsonResponse La respuesta en formato JSON.
     *
     * El JSON devuelto debe tener las siguientes claves:
     * - mensaje: Un mensaje indicando el resultado de la operación.
     * - contenido: Un array con los nombres de los ficheros.
     */
    public function index()
    {
        // Obtén la lista de archivos del almacenamiento local
        $files = Storage::disk('local')->files();

        // Devuelve la respuesta en formato JSON con los archivos encontrados
        return response()->json([
            'mensaje' => 'Listado de ficheros',
            'contenido' => $files,
        ]);
    }

     /**
     * Recibe por parámetro el nombre de fichero y el contenido. Devuelve un JSON con el resultado de la operación.
     * Si el fichero ya existe, devuelve un 409.
     *
     * @param filename Parámetro con el nombre del fichero. Devuelve 422 si no hay parámetro.
     * @param content Contenido del fichero. Devuelve 422 si no hay parámetro.
     * @return JsonResponse La respuesta en formato JSON.
     *
     * El JSON devuelto debe tener las siguientes claves:
     * - mensaje: Un mensaje indicando el resultado de la operación.
     */
    public function store(Request $request): JsonResponse
    {
        // Validar que los parámetros filename y content están presentes en la solicitud
        $request->validate([
            'filename' => 'required|string',
            'content' => 'required|string',
        ]);

        $filename = $request->input('filename');
        $content = $request->input('content');

        // Verificar si el archivo ya existe
        if (Storage::disk('local')->exists($filename)) {
            return response()->json([
                'mensaje' => 'El archivo ya existe',
            ], 409); // HTTP 409 Conflict
        }

        // Crear el archivo con el contenido proporcionado
        Storage::disk('local')->put($filename, $content);

        // Responder con éxito con código 200 y el mensaje esperado
        return response()->json([
            'mensaje' => 'Guardado con éxito',
        ], 200); // HTTP 200 OK
    }

     /**
     * Recibe por parámetro el nombre de fichero y devuelve un JSON con su contenido
     *
     * @param name Parámetro con el nombre del fichero.
     * @return JsonResponse La respuesta en formato JSON.
     *
     * El JSON devuelto debe tener las siguientes claves:
     * - mensaje: Un mensaje indicando el resultado de la operación.
     * - contenido: El contenido del fichero si se ha leído con éxito.
     */
    public function show(string $filename): JsonResponse
    {
        // Verificar si el archivo existe
        if (!Storage::disk('local')->exists($filename)) {
            return response()->json([
                'mensaje' => 'Archivo no encontrado',
            ], 404); // HTTP 404 Not Found
        }

        // Obtener el contenido del archivo
        $content = Storage::disk('local')->get($filename);

        // Responder con éxito y el contenido del archivo
        return response()->json([
            'mensaje' => 'Archivo leído con éxito',
            'contenido' => $content,
        ], 200); // HTTP 200 OK
    }

    /**
     * Recibe por parámetro el nombre de fichero, el contenido y actualiza el fichero.
     * Devuelve un JSON con el resultado de la operación.
     * Si el fichero no existe devuelve un 404.
     *
     * @param filename Parámetro con el nombre del fichero. Devuelve 422 si no hay parámetro.
     * @param content Contenido del fichero. Devuelve 422 si no hay parámetro.
     * @return JsonResponse La respuesta en formato JSON.
     *
     * El JSON devuelto debe tener las siguientes claves:
     * - mensaje: Un mensaje indicando el resultado de la operación.
     */
    public function update(Request $request, string $filename): JsonResponse
    {
        // Validar que el parámetro content está presente
        $request->validate([
            'content' => 'required|string',
        ]);

        // Verificar si el archivo existe
        if (!Storage::disk('local')->exists($filename)) {
            return response()->json([
                'mensaje' => 'El archivo no existe',
            ], 404); // HTTP 404 Not Found
        }

        // Sobreescribir el contenido del archivo
        Storage::disk('local')->put($filename, $request->input('content'));

        // Responder con éxito
        return response()->json([
            'mensaje' => 'Actualizado con éxito',
        ], 200); // HTTP 200 OK
    }

    /**
     * Recibe por parámetro el nombre de ficher y lo elimina.
     * Si el fichero no existe devuelve un 404.
     *
     * @param filename Parámetro con el nombre del fichero. Devuelve 422 si no hay parámetro.
     * @return JsonResponse La respuesta en formato JSON.
     *
     * El JSON devuelto debe tener las siguientes claves:
     * - mensaje: Un mensaje indicando el resultado de la operación.
     */
    public function destroy(string $filename): JsonResponse
    {
        // Verificar si el archivo existe
        if (!Storage::disk('local')->exists($filename)) {
            return response()->json([
                'mensaje' => 'El archivo no existe',
            ], 404); // HTTP 404 Not Found
        }

        // Eliminar el archivo
        Storage::disk('local')->delete($filename);

        // Responder con éxito
        return response()->json([
            'mensaje' => 'Eliminado con éxito',
        ], 200); // HTTP 200 OK
    }

    /**comentario para actualizar y forzas actions y revisar test ejecutados */
}
