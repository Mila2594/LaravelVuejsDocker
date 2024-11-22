<template>
  <div>
    <h1>Bienvenido a la página principal</h1>
    <!-- Cargar el formulario aquí -->
    <FormularioContacto />
  </div>
</template>

<script>
import FormularioContacto from "../components/FormularioContacto.vue";

export default {
  name: "HomePage",
  components: {
    FormularioContacto,
  },
};
</script>

<style scoped>
h1 {
  color: #42b983;
}
</style>