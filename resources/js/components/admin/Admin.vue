<template>
    <v-app dark>
        <notification />

        <navigation />

        <v-content fluid>
            <v-container fluid cols="12">
                <router-view apiRoute="/api/admin" />
            </v-container>
        </v-content>
        <v-footer app></v-footer>
    </v-app>
</template>

<script>
  import Navigation from "./Navigation";
  import { mapActions } from 'vuex';

  export default {
    name: "admin",

    components: {Navigation},

    methods: {
      ...mapActions('errors', [
        'resetErrors'
      ]),

      ...mapActions('notifications', [
          'hideNotification'
      ])
    },

    mounted() {
      window.addEventListener('beforeunload', (e) => {
        this.resetErrors();
        this.hideNotification();
      });
    }
  }
</script>

<style scoped>

</style>
