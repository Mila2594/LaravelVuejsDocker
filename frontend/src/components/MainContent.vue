<template>
  <main class="main-content">
    <div class="content-wrapper">
      <div class="actions">
        <button 
          @click="handleAction('Get Files')" 
          :class="{ 'active-button': selectedAction === 'Get Files' }">
          Get Files
        </button>
        <button 
          @click="handleAction('Store')" 
          :class="{ 'active-button': selectedAction === 'Store' }">
          Store
        </button>
        <button 
          @click="handleAction('Show')" 
          :class="{ 'active-button': selectedAction === 'Show' }">
          Show
        </button>
        <button 
          @click="handleAction('Update')" 
          :class="{ 'active-button': selectedAction === 'Update' }">
          Update
        </button>
        <button 
          @click="handleAction('Delete')" 
          :class="{ 'active-button': selectedAction === 'Delete' }">
          Delete
        </button>
      </div>
      <div class="form-content">
        <h2>{{ getTitle() }}</h2>
        <p>{{ getDescription() }}</p>
        <input 
          v-if="selectedAction === 'Store' || selectedAction === 'Update' || selectedAction === 'Delete' || selectedAction === 'Show'" 
          v-model="inputContent" 
          class="input" 
          placeholder="Ingrese el nombre del fichero"
        />
        <textarea
          v-if="selectedAction !== 'Delete'"
          v-model="textAreaContent"
          class="textarea"
          :placeholder="getPlaceholder()"
          :readonly="!(selectedAction === 'Store' || selectedAction === 'Update')"
        ></textarea>
        <button class="send-button" @click="sendData">SEND</button>
      </div>
    </div>
  </main>
</template>

<script>
export default {
  name: 'MainContent',
  props: {
    selectedMenu: String,
  },
  data() {
    return {
      buttons: ['Get Files', 'Store', 'Show', 'Update', 'Delete'],
      selectedAction: 'Get Files', // Inicializa con 'Get Files'
      textAreaContent: '',
      inputContent: '', // Añade una propiedad para el contenido del input
    };
  },
  methods: {
    handleAction(action) {
      this.selectedAction = action;
      this.textAreaContent = ''; // Limpia el contenido del textarea
      console.log('Selected Action:', this.selectedAction);
    },
    sendData() {

      let apiUrl = 'http://localhost:8000/api/json'; // Default URL
      let headers = {
        'Content-Type': 'application/json',
      };

      if (this.selectedMenu === 'CSV') {
        apiUrl = 'http://localhost:8000/api/csv';
        headers['Content-Type'] = 'text/csv';
      } else if (this.selectedMenu === 'XML') {
        apiUrl = 'http://localhost:8000/api/xml';
      }
      else if (this.selectedMenu === 'Class Storage') {
        apiUrl = 'http://localhost:8000/api/hello';
      }

      let method = 'GET';
      let body = null;

      // se verifica si selectedAction es 'Store' o 'Update' y si los campos inputContent y textAreaContent están vacíos.
      if ((this.selectedAction === 'Store' || this.selectedAction === 'Update') && (!this.inputContent || !this.textAreaContent)) {
        alert('Por favor, ingrese el nombre del fichero y su contenido');
        return;
      }

      if (this.selectedAction === 'Store') {
        method = 'POST';
        body = JSON.stringify({
          filename: this.inputContent,
          content: this.textAreaContent
        });
        console.log('Input Content:', this.inputContent); // Imprime el contenido del input
        console.log('Textarea Content:', body); // Imprime el contenido del textarea
      } else if (this.selectedAction === 'Show') {
        method = 'GET';
        apiUrl += `/${this.inputContent}`; // Concatena el nombre del fichero al final de la URL
      } else if (this.selectedAction === 'Update') {

        apiUrl += `/${this.inputContent}`; // Concatena el nombre del fichero al final de la URL
        body = JSON.stringify({
          filename: this.inputContent,
          content: this.textAreaContent
        });

        console.log('Input Content:', this.inputContent); // Imprime el contenido del input
        console.log('Textarea Content:', body); // Imprime el contenido del textarea
        method = 'PUT';
        body = JSON.stringify({
          queryParam: 'update',
          content: this.textAreaContent
        });
        console.log('Input Content:', this.inputContent); // Imprime el contenido del input
      } else if (this.selectedAction === 'Delete') {
        method = 'DELETE';
        apiUrl += `/${this.inputContent}`; // Concatena el nombre del fichero al final de la URL
      }

      console.log(`Request URL: ${apiUrl}`);

      const fetchOptions = {
        method: method,
        headers: {
          'Content-Type': 'application/json',
        },
      };

      // Agrega el cuerpo de la solicitud si es necesario en Store y Update
      if (this.selectedAction === 'Store' || this.selectedAction === 'Update') {
        fetchOptions.body = body;
      }
      
      fetch(apiUrl, fetchOptions)
        .then((response) => {
          if (response.ok) {
            if (this.selectedAction === 'Delete') {
              alert('Archivo Eliminado Correctamente');
            } else if (this.selectedAction === 'Store' ) {
              alert('El fichero se ha creado correctamente');
            }
            else if (this.selectedAction === 'Update' ) {
              alert('El fichero se ha actualizado correctamente');
            }
          }
          
          if (response.status === 409) {
            alert('El archivo ya existe');
          }
          if (response.status === 415) {
            alert('Contenido no es un JSON válido');
          }
          if (response.status === 404) {
            alert('El fichero no existe');
          }
          if (response.status === 400) {
            alert('El fichero no tiene el formato correcto');
          }

          // Si esta marcado Json, se devuelve el contenido a JSON, si esta marcado CSV, se devuelve CSV
          return this.selectedMenu === 'JSON' ? response.json() : response.text();
        })
        .then((data) => {
          console.log('Data retrieved successfully:', data);
          

        // Si la opcion seleccionada es Json
        if (this.selectedMenu === 'JSON') {
          if (this.selectedAction === 'Get Files' || this.selectedAction === 'Show') {
            
            // el contenido de data es: {"mensaje":"Listado de ficheros","contenido":["app\/1","app\/mi_archivo2.json","app\/qwewq","app\/sadasd.json"]} , y quiero obtener el contenido de la clave contenido
            this.textAreaContent = JSON.stringify(data.contenido, null, 2);
            
          }
        }

        if (this.selectedMenu === 'Class Storage' ) {
          if (this.selectedAction === 'Get Files') {
            data = JSON.parse(data); // Convierte la cadena JSON en un objeto  
            const contenido = data?.contenido;
            if (contenido) {
              console.log('Contenido:', contenido);
              this.textAreaContent = contenido.join('\n');

            } else {
              console.warn('La clave "contenido" no existe en data:', data);
            }
          }
          if (this.selectedAction === 'Show') {
            data = JSON.parse(data); // Convierte la cadena JSON en un objeto  
            const contenido = data?.contenido;
            if (contenido) {
              console.log('Contenido:', contenido);
              this.textAreaContent = contenido;

            } else {
              console.warn('La clave "contenido" no existe en data:', data);
            }
          
          }
        }

        // Si la opcion seleccionada es CSV
        if (this.selectedMenu === 'CSV') {
          if (this.selectedAction === 'Get Files') {
            
            data = JSON.parse(data); // Convierte la cadena JSON en un objeto
            const contenido = data?.contenido;

            if (contenido) {
              console.log('Contenido:', contenido);
              // Convierte el array de objetos en una cadena JSON
              this.textAreaContent = JSON.stringify(contenido, null, 2); // El parámetro `null, 2` es para formato legible
            } else {
              console.warn('La clave "contenido" no existe en data:', data);
            }
          }

          if (this.selectedAction === 'Show') {

            data = JSON.parse(data); // Convierte la cadena JSON en un objeto
            const contenido = data?.contenido;

            if (contenido) {
              console.log('Contenido:', contenido);
              
              // Crear una cadena sin los corchetes al principio y al final
              this.textAreaContent = contenido
                .map(item => {
                  // Itera sobre las claves del objeto y construye una cadena dinámica
                  return '{ ' + Object.entries(item)
                    .map(([key, value]) => `${key}: ${value}`)
                    .join(', ') + ' }';
                })
                .join('\n'); // Agregar salto de línea entre los objetos

            } else {
              console.warn('La clave "contenido" no existe en data:', data);
            }

          }

          
        }


        })
        .catch((error) => {
          console.error('Error retrieving data:', error);
        });
    },
    getPlaceholder() {
      if (this.selectedAction === 'Store' || this.selectedAction === 'Update') {
        return 'Ingrese el contenido del fichero';
      } else if (this.selectedAction === 'Show') {
        return 'El contenido del fichero se mostrará acá';
      } else {
        return '';
      }
    },
    getTitle() {
      switch (this.selectedAction) {
        case 'Get Files':
          return 'Obtener Archivos';
        case 'Store':
          return 'Almacenar Archivo';
        case 'Show':
          return 'Mostrar Archivo';
        case 'Update':
          return 'Actualizar Archivo';
        case 'Delete':
          return 'Eliminar Archivo';
        default:
          return '';
      }
    },
    getDescription() {
      switch (this.selectedAction) {
        case 'Get Files':
          return 'Obtén la lista de archivos disponibles.';
        case 'Store':
          return 'Almacena un nuevo archivo en el sistema.';
        case 'Show':
          return 'Muestra el contenido de un archivo específico.';
        case 'Update':
          return 'Actualiza el contenido de un archivo existente.';
        case 'Delete':
          return 'Elimina un archivo del sistema.';
        default:
          return '';
      }
    }
  }
};
</script>

<style scoped>
.main-content {
  flex: 0 0 75%;
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  height: 100vh; /* Ajusta según sea necesario */
}

.content-wrapper {
  width: 100%;
  max-width: 600px; /* Ajusta el ancho máximo según sea necesario */
  display: flex;
  flex-direction: column;
  align-items: center;
}

.actions {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 30px; /* Ajusta este valor para controlar la separación entre los botones */
  margin-bottom: 20px; /* Añade un margen inferior para separar del textarea */
}

.form-content {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: flex-start; /* Alinea el input y el textarea a la izquierda */
}

button {
  padding: 10px 20px;
  font-size: 16px;
  border: 2px solid #007bff;
  border-radius: 5px;
  background-color: white;
  color: #007bff;
  cursor: pointer;
  transition: background-color 0.3s, color 0.3s;
}

button:hover {
  background-color: #007bff;
  color: white;
}

.active-button {
  background-color: #007bff;
  color: white;
}

input.input {
  width: 50%;
  margin-bottom: 20px;
  padding: 10px;
  font-size: 16px;
  border: 2px solid #ccc;
  border-radius: 5px;
}

textarea.textarea {
  width: 100%;
  height: 200px;
  margin-bottom: 20px;
  cursor: default;
}

/* Estilos para los placeholders */
input::placeholder,
textarea::placeholder {
  font-size: 15px;
  font-family: inherit; /* Usa la misma fuente que el elemento */
}

.send-button {
  align-self: flex-end;
  background-color: #42b983;
  color: white;
  border: none;
  padding: 10px 20px;
  cursor: pointer;
  font-size: 1rem;
}

.send-button:hover {
  background-color: #28a745;
  color: white;
}
</style>