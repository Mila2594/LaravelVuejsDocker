<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CsvController extends Controller
{
    /**
     * Lista todos los ficheros JSON de la carpeta storage/app.
     * Se debe comprobar fichero a fichero si su contenido es un JSON válido.
     * para ello, se puede usar la función json_decode y json_last_error.
     *
     * Devuelve el formato csv de lo leido en el storage/app
     * No debe validar el Json
     */
    public function index()
    {
        // Obtén la lista de archivos del almacenamiento local
        $files = Storage::disk('local')->files('app');

        // Devuelve la respuesta en formato CSV con los archivos encontrados
        $csv = "mensaje,contenido\n";
        foreach ($files as $file) {
            $csv .= "Listado de ficheros," . basename($file) . "\n";
        }

        return response($csv)->header('Content-Type', 'text/csv');
    }

    /**
     * Muestra el contenido de un fichero JSON específico.
     *
     * @param  string  $filename
     * @return \Illuminate\Http\Response
     */
    public function show($filename)
    {   
        //ruta
        $path = 'app/' . $filename;
        
        if (Storage::disk('local')->exists($path)) {
            // Lee el contenido del archivo
            $content = Storage::disk('local')->get($path);
        } else {
            // Manejo de error si el archivo no existe
            return response('Fichero no encontrado', 404);
        }
        
        // Devuelve el contenido en formato CSV
        $csv = "contenido\n";
        $csv .= $content;

        return response($csv)->header('Content-Type', 'text/csv');
    }

    /**
     * Guarda un nuevo fichero JSON en el almacenamiento.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Guarda el contenido del fichero
        $filename = $request->input('filename');
        $content = $request->input('content');
        Storage::disk('local')->put("app/{$filename}", $content);

        // Devuelve una respuesta en formato CSV
        $csv = "mensaje,contenido\n";
        $csv .= "Fichero guardado," . $filename . "\n";

        return response($csv)->header('Content-Type', 'text/csv');
    }

    /**
     * Actualiza el contenido de un fichero JSON específico.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $filename
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $filename)
    {
        // Actualiza el contenido del fichero
        $content = $request->input('content');
        Storage::disk('local')->put("app/{$filename}", $content);

        // Devuelve una respuesta en formato CSV
        $csv = "mensaje,contenido\n";
        $csv .= "Fichero actualizado," . $filename . "\n";

        return response($csv)->header('Content-Type', 'text/csv');
    }

    /**
     * Elimina un fichero JSON específico del almacenamiento.
     *
     * @param  string  $filename
     * @return \Illuminate\Http\Response
     */
    public function destroy($filename)
    {
        // Elimina el fichero
        Storage::disk('local')->delete("app/{$filename}");

        // Devuelve una respuesta en formato CSV
        $csv = "mensaje,contenido\n";
        $csv .= "Fichero eliminado," . $filename . "\n";

        return response($csv)->header('Content-Type', 'text/csv');
    }
}