<template>
    <v-toolbar>
        <v-toolbar-title>
            {{ $t('roles.roles') }}
        </v-toolbar-title>

        <v-divider class="mx-4" vertical inset />

        <v-spacer />

        <role-toolbar-search></role-toolbar-search>

        <v-spacer />

        <role-modal-create-form></role-modal-create-form>

    </v-toolbar>
</template>

<script>
  import RoleModalCreateForm from './RoleModalCreateForm';
  import RoleToolbarSearch from './RoleToolbarSearch';

  export default {
    name: "RoleToolbar",

    components: {
      RoleModalCreateForm,
      RoleToolbarSearch
    }
  }
</script>

<style scoped>

</style>