<template>
    <div>
        <v-card>
            <user-toolbar />

            <v-card-text>
                <user-export />

                <user-list />
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
  import UserList from './UserList'
  import UserExport from './UserExport';
  import UserToolbar from './UserToolbar';

  export default {
    name: "index",

    components: {
      UserList,
      UserExport,
      UserToolbar
    }
  }
</script>