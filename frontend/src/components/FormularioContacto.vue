<template>
  <div class="app">
    <header class="header">
      <h1>PR UD 2</h1>
    </header>
    <div class="content">
      <nav class="menu">
        <ul>
          <li
            v-for="option in menuOptions"
            :key="option"
            :class="{ active: selectedMenu === option }"
            @click="selectMenu(option)"
          >
            {{ option }}
          </li>
        </ul>
      </nav>
      <main class="main-content">
        <div class="actions">
          <button v-for="btn in buttons" :key="btn" @click="handleAction(btn)">
            {{ btn }}
          </button>
        </div>
        <textarea
          v-model="textAreaContent"
          class="textarea"
          placeholder="Write here..."
        ></textarea>
        <button class="send-button" @click="sendData">SEND</button>
      </main>
    </div>
  </div>
</template>

<script>
export default {
  name: "App",
  data() {
    return {
      menuOptions: ["Class Storage", "JSON", "CSV", "XML"],
      selectedMenu: "Class Storage",
      buttons: ["Get Files", "Store", "Show", "Update", "Delete"],
      textAreaContent: "",
    };
  },
  methods: {
    selectMenu(option) {
      this.selectedMenu = option;
    },
    handleAction(action) {
      console.log(`Action triggered: ${action}`);
    },
    sendData() {
  const payload = {
    selectedMenu: this.selectedMenu,
    textAreaContent: this.textAreaContent,
  };

  // Convertir el payload a parámetros de consulta
  const queryParams = new URLSearchParams(payload).toString();

  // Simulación de un GET request
  fetch(`http://localhost:8000/api/json?${queryParams}`, {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
    console.log("Data retrieved successfully:", data);

    // Asignar el valor al textarea
    const textarea = document.querySelector('.textarea'); // Selecciona el textarea
    if (textarea) {
      textarea.value = JSON.stringify(data, null, 2); // Formatea la data como texto legible (opcional)
    }
  })
    .catch((error) => {
      console.error("Error retrieving data:", error);
    });
},
  },
};
</script>

<style scoped>
/* General layout */
.app {
  max-width: 1280px;
  margin: 0 auto;
  text-align: center;
  font-family: Arial, sans-serif;
}

.header {
  border-bottom: 2px solid #ddd;
  padding: 20px 0;
  width: 17%;
  margin: 0 auto;
}

.header h1 {
  font-size: 2rem;
  text-transform: uppercase;
}

/* Content layout */
.content {
  display: flex;
  flex-wrap: no-wrap;
}

.menu {
  flex: 0 0 25%;
  border-right: 1px solid #ddd;
  padding: 20px;
  min-height: 200px;
}

.menu ul {
  list-style: none;
  padding: 0;
}

.menu li {
  padding: 10px 0;
  cursor: pointer;
}

.menu li.active {
  font-weight: bold;
  color: #42b983;
}

/* Main content */
.main-content {
  flex: 0 0 75%;
  padding: 20px;
  display: flex;
  flex-direction: column;
}

.actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-bottom: 20px;
}

textarea.textarea {
  width: 100%;
  height: 200px;
  margin-bottom: 20px;
}

.send-button {
  align-self: flex-start;
  background-color: #42b983;
  color: white;
  border: none;
  padding: 10px 20px;
  cursor: pointer;
  font-size: 1rem;
}

.send-button:hover {
  background-color: #369e6e;
}

/* Responsive design */
@media (max-width: 768px) {
  .content {
    flex-direction: column;
  }

  .menu {
    flex: 1 1 auto;
    border-right: none;
    border-bottom: 1px solid #ddd;
  }

  .main-content {
    flex: 1 1 auto;
  }
}
</style>
