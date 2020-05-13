<template>
    <v-card>
        <role-toolbar />

        <v-card-text>
            <role-list />
        </v-card-text>
    </v-card>
</template>

<script>
  import RoleToolbar from './RoleToolbar';
  import RoleList from './RoleList';

  export default {
    name: "roles",

    components: {
      RoleToolbar,
      RoleList
    }
  }
</script>
