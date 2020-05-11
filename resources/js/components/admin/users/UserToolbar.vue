<template>
    <v-toolbar color="white">
        <v-toolbar-title>
            {{ $t('users.users') }}
        </v-toolbar-title>

        <v-divider class="mx-4" vertical inset></v-divider>

        <v-spacer />

        <user-toolbar-search />

        <v-spacer />

        <user-modal-create-form />

    </v-toolbar>
</template>

<script>
  import UserModalCreateForm from './UserModalCreateForm'
  import UserToolbarSearch from './UserToolbarSearch';

  export default {
    name: "UserToolbar",

    components: {
      UserToolbarSearch,
      UserModalCreateForm
    }
  }
</script>