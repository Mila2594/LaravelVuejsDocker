<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
class CsvTest extends TestCase
{
    public function testIndex()
    {
        Storage::fake('local');
        Storage::put('app/file1.csv', 'header1,header2\nvalue1,value2');
        Storage::put('app/file2.csv', 'header1,header2\nvalue1,value2');
        Storage::put('app/valid.json', json_encode(['key' => 'value']));
        $response = $this->getJson('/api/csv');
        $response->assertStatus(200)
                 ->assertJson([
                     'mensaje' => 'Listado de ficheros',
                     'contenido' => ['file1.csv', 'file2.csv'],
                 ]);
    }
    public function testShow()
    {
        Storage::fake('local');
        // Crear archivo con el prefijo correcto (app/)
        Storage::put('app/existingfile.csv', "header1,header2\nvalue1,value2");
        $response = $this->get('/api/csv/existingfile.csv');
        $response->assertStatus(200)
                 ->assertJson([
                     'mensaje' => 'Fichero leído con éxito',
                     'contenido' => [
                         ['header1' => 'value1', 'header2' => 'value2']
                     ],
                 ]);
    }
    public function testStore()
    {
        Storage::fake('local');
        $response = $this->postJson('/api/csv', [
            'filename' => 'file1.csv',
            'content' => "header1,header2\nvalue1,value2",
        ]);
        $response->assertStatus(200)
                 ->assertJson([
                     'mensaje' => 'Guardado con éxito',
                 ]);
        Storage::disk('local')->assertExists('app/file1.csv');
        $this->assertEquals(
            "header1,header2\nvalue1,value2",
            Storage::disk('local')->get('app/file1.csv')
        );
    }
    public function testUpdate()
    {
        Storage::fake('local');
        Storage::put('app/existingfile.csv', "header1,header2\nvalue1,value2");
        $response = $this->put('/api/csv/existingfile.csv', [
            'content' => "header1,header2\nvalue3,value4",
        ]);
        $response->assertStatus(200)
                 ->assertJson(['mensaje' => 'Fichero actualizado exitosamente']);
        $this->assertEquals(
            "header1,header2\nvalue3,value4",
            Storage::disk('local')->get('app/existingfile.csv')
        );
    }
    public function testDestroy()
    {
        Storage::fake('local');
        Storage::put('app/existingfile.csv', "header1,header2\nvalue1,value2");
        $response = $this->delete('/api/csv/existingfile.csv');
        $response->assertStatus(200)
                 ->assertJson(['mensaje' => 'Fichero eliminado exitosamente']);
        Storage::disk('local')->assertMissing('app/existingfile.csv');
    }
}