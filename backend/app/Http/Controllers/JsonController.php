<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class JsonController extends Controller
{
    private function isValidJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    /**
     * Lista todos los ficheros JSON de la carpeta storage/app.
     * Se debe comprobar fichero a fichero si su contenido es un JSON válido.
     * para ello, se puede usar la función json_decode y json_last_error.
     *
     * @return JsonResponse La respuesta en formato JSON.
     *
     * El JSON devuelto debe tener las siguientes claves:
     * - mensaje: Un mensaje indicando el resultado de la operación.
     * - contenido: Un array con los nombres de los ficheros.
     */
    public function index()
    {
        $files = Storage::files('app');
        $validJsonFiles = [];

        foreach ($files as $file) {
            $content = Storage::get($file);
            if ($this->isValidJson($content)) {
                $validJsonFiles[] = basename($file);
            }
        }

        return response()->json([
            'mensaje' => 'Operación exitosa',
            'contenido' => $validJsonFiles,
        ]);
    }

   /**
     * Recibe por parámetro el nombre de fichero y el contenido. Devuelve un JSON con el resultado de la operación.
     * Si el fichero ya existe, devuelve un 409.
     * Si el contenido no es un JSON válido, devuelve un 415.
     *
     * @param filename Parámetro con el nombre del fichero. Devuelve 422 si no hay parámetro.
     * @param content Contenido del fichero. Devuelve 422 si no hay parámetro.
     * @return JsonResponse La respuesta en formato JSON.
     *
     * El JSON devuelto debe tener las siguientes claves:
     * - mensaje: Un mensaje indicando el resultado de la operación.
     */
    public function store(Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
            'content' => 'required',
        ]);

        $filename = 'app/' . $request->filename;
        $content = $request->content;

        if (Storage::exists($filename)) {
            return response()->json(['mensaje' => 'El fichero ya existe'], 409);
        }

        if (!$this->isValidJson($content)) {
            return response()->json(['mensaje' => 'Contenido no es un JSON válido'], 415);
        }

        Storage::put($filename, $content);

        return response()->json(['mensaje' => 'Fichero guardado exitosamente'], 200);
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
    public function show(string $id)
    {
        $filename = 'app/' . $id;

        if (!Storage::exists($filename)) {
            return response()->json(['mensaje' => 'El fichero no existe'], 404);
        }

        $content = Storage::get($filename);

        return response()->json([
            'mensaje' => 'Operación exitosa',
            'contenido' => json_decode($content, true),
        ], 200);
    }

   /**
     * Recibe por parámetro el nombre de fichero, el contenido y actualiza el fichero.
     * Devuelve un JSON con el resultado de la operación.
     * Si el fichero no existe devuelve un 404.
     * Si el contenido no es un JSON válido, devuelve un 415.
     * 
     * @param filename Parámetro con el nombre del fichero. Devuelve 422 si no hay parámetro.
     * @param content Contenido del fichero. Devuelve 422 si no hay parámetro.
     * @return JsonResponse La respuesta en formato JSON.
     *
     * El JSON devuelto debe tener las siguientes claves:
     * - mensaje: Un mensaje indicando el resultado de la operación.
     */
    public function update(Request $request, string $id)
    {
        $filename = 'app/' . $id;

        if (!Storage::exists($filename)) {
            return response()->json(['mensaje' => 'El fichero no existe'], 404);
        }

        $request->validate([
            'content' => 'required',
        ]);

        $content = $request->content;

        if (!$this->isValidJson($content)) {
            return response()->json(['mensaje' => 'Contenido no es un JSON válido'], 415);
        }

        Storage::put($filename, $content);

        return response()->json(['mensaje' => 'Fichero actualizado exitosamente'], 200);
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
    public function destroy(string $id)
    {
        $filename = 'app/' . $id;

        if (!Storage::exists($filename)) {
            return response()->json(['mensaje' => 'El fichero no existe'], 404);
        }

        Storage::delete($filename);

        return response()->json(['mensaje' => 'Fichero eliminado exitosamente'], 200);
    
    }
}