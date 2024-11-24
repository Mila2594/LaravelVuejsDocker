<template>
  <textarea
    v-model="localValue"
    class="textarea"
    placeholder="Write here..."
  ></textarea>
</template>

<script>
export default {
  name: 'TextArea',
  props: {
    text: String,  // Cambié el nombre de la prop a 'text' por consistencia
  },
  data() {
    return {
      localValue: this.text,  // Copiamos el valor de la prop en una variable local
    };
  },
  watch: {
    localValue(newValue) {
      this.$emit('update:text', newValue);  // Emitimos el cambio a través de un evento
    },
  },
};
</script>

<style scoped>
.textarea {
  width: 100%;
  height: 200px;
  margin-bottom: 20px;
}
</style>
