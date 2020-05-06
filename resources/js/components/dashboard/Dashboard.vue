<template>
    <v-app dark>
        <notification></notification>
        <v-card height="100%">
            <navigation api-route="/api" :display="displayNavbar" @hide-navigation-drawer="displayNavbar = false">

            </navigation>
            <v-toolbar color="green" class="white--text font-weight-bold">
                <v-btn text @click="displayNavbar = true">
                    <v-icon color="white">
                        mdi-format-list-bulleted
                    </v-icon>
                </v-btn>

                <v-spacer></v-spacer>

                <v-toolbar-title @click="$router.push('/')" style="cursor: pointer">
                    <h2 class="display-1">
                        {{ $t('app_name')}}
                    </h2>
                    <h6>
                        {{ $t('app_subtitle')}}
                    </h6>
                </v-toolbar-title>

                <v-spacer></v-spacer>

                <div v-if="! user">
                    <login />

                    <registration />
                </div>

                <div v-else-if="user" class="d-flex align-center">
                    <div class="px-4" @click="toCabinet" style="cursor: pointer">
                        <v-avatar color="primary" size="48">
                            <span class="white--text headline">{{ initials }}</span>
                        </v-avatar>

                        <span>{{ fullName }}</span>
                    </div>

                    <v-spacer></v-spacer>

                    <logout :text="true" :outlined="true" :block="false" />
                </div>
            </v-toolbar>
            <router-view apiRoute="/api" />
        </v-card>
    </v-app>
</template>

<script>
  import Navigation from './Navigation';
  import Login from '.././auth/Login';
  import Logout from '.././auth/Logout';
  import Registration from '.././auth/Registration';
  import { mapActions, mapGetters } from 'vuex';

  export default {
    name: "dashboard",

    components: {
      Navigation,
      Login,
      Registration,
      Logout
    },

    data() {
      return {
        displayNavbar: false
      }
    },

    computed: {
      ...mapGetters('auth', {
        'user': 'user'
      }),

      fullName() {
        return `${this.user.full_name}`;
      },

      initials() {
        return `${this.user.first_name[0]}${this.user.last_name[0]}`;
      }
    },

    methods: {
      toCabinet() {
        if  (this.$route.name === 'Cabinet') {
            return ;
        }

        this.$router.push('/cabinet');
      },

      ...mapActions('errors', {
        'resetErrors': 'setErrors'
      }),

      ...mapActions('notifications', [
          'hideNotification'
      ]),
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