<template>
  <div class="app">
    <AppHeader />
    <div class="content">
      <NavMenu
        :menu-options="menuOptions"
        :selected-menu="selectedMenu"
        @select-menu="selectMenu"
      />
      <MainContent :selected-menu="selectedMenu" />
    </div>
  </div>
</template>

<script>
import AppHeader from './components/AppHeader.vue';
import NavMenu from './components/NavMenu.vue';
import MainContent from './components/MainContent.vue';

export default {
  name: 'App',
  components: {
    AppHeader,
    NavMenu,
    MainContent,
  },
  data() {
    return {
      menuOptions: ['Class Storage', 'JSON', 'CSV', 'XML'],
      selectedMenu: 'Class Storage',
    };
  },
  methods: {
    selectMenu(option) {
      this.selectedMenu = option;
    },
  },
};
</script>

<style scoped>
.app {
  max-width: 1280px;
  margin: 0 auto;
  font-family: Arial, sans-serif;
}

.content {
  display: flex;
  flex-wrap: no-wrap;
  margin-top: 22px;
}

@media (max-width: 768px) {
  .content {
    flex-direction: column;
  }
}
</style>
