<template>
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
</template>

<script>
export default {
  name: 'NavMenu',
  props: {
    menuOptions: Array,
    selectedMenu: String,
  },
  methods: {
    selectMenu(option) {
      this.$emit('select-menu', option);
    },
  },
};
</script>

<style scoped>
.menu {
  flex: 0 0 12%;
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
</style>
