<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HelloWorldControllerTest extends TestCase
{

    public function testIndex()
    {
        Storage::fake('local');

        Storage::disk('local')->put('file1.txt', 'Content 1');
        Storage::disk('local')->put('file2.txt', 'Content 2');

        $response = $this->getJson('/api/hello');

        $response->assertStatus(200)
                 ->assertJson([
                     'mensaje' => 'Listado de ficheros',
                     'contenido' => ['file1.txt', 'file2.txt'],
                 ]);
    }

    public function testStore()
    {
        Storage::fake('local');

        $response = $this->postJson('/api/hello', [
            'filename' => 'file1.txt',
            'content' => 'Content 1',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'mensaje' => 'Guardado con éxito',
                 ]);

        Storage::disk('local')->assertExists('file1.txt');
        $this->assertEquals('Content 1', Storage::disk('local')->get('file1.txt'));
    }

    public function testStoreFileAlreadyExists()
    {
        Storage::fake('local');

        Storage::disk('local')->put('file1.txt', 'Content 1');

        $response = $this->postJson('/api/hello', [
            'filename' => 'file1.txt',
            'content' => 'Content 2',
        ]);

        $response->assertStatus(409)
                 ->assertJson([
                     'mensaje' => 'El archivo ya existe',
                 ]);
    }

    public function testShow()
    {
        Storage::fake('local');

        Storage::disk('local')->put('file1.txt', 'Content 1');

        $response = $this->getJson('/api/hello/file1.txt');

        $response->assertStatus(200)
                 ->assertJson([
                     'mensaje' => 'Archivo leído con éxito',
                     'contenido' => 'Content 1',
                 ]);
    }

    public function testShowFileNotFound()
    {
        Storage::fake('local');

        $response = $this->getJson('/api/hello/nonexistent.txt');

        $response->assertStatus(404)
                 ->assertJson([
                     'mensaje' => 'Archivo no encontrado',
                 ]);
    }

    public function testUpdate()
    {
        Storage::fake('local');

        Storage::disk('local')->put('file1.txt', 'Content 1');

        $response = $this->putJson('/api/hello/file1.txt', [
            'content' => 'Updated Content',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'mensaje' => 'Actualizado con éxito',
                 ]);

        Storage::disk('local')->assertExists('file1.txt');
        $this->assertEquals('Updated Content', Storage::disk('local')->get('file1.txt'));
    }

    public function testUpdateFileNotFound()
    {
        Storage::fake('local');

        $response = $this->putJson('/api/hello/nonexistent.txt', [
            'content' => 'Updated Content',
        ]);

        $response->assertStatus(404)
                 ->assertJson([
                     'mensaje' => 'El archivo no existe',
                 ]);
    }

    public function testDestroy()
    {
        Storage::fake('local');

        Storage::disk('local')->put('file1.txt', 'Content 1');

        $response = $this->deleteJson('/api/hello/file1.txt');

        $response->assertStatus(200)
                 ->assertJson([
                     'mensaje' => 'Eliminado con éxito',
                 ]);

        Storage::disk('local')->assertMissing('file1.txt');
    }

    public function testDestroyFileNotFound()
    {
        Storage::fake('local');

        $response = $this->deleteJson('/api/hello/nonexistent.txt');

        $response->assertStatus(404)
                 ->assertJson([
                     'mensaje' => 'El archivo no existe',
                 ]);
    }
}