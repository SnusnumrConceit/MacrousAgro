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

                <!--<locale-switcher />-->

                <v-spacer />

                <app-title />

                <v-spacer />

                <auth-toolbar />
            </v-toolbar>
            <router-view apiRoute="/api" :key="$router.path"/>
        </v-card>
    </v-app>
</template>
<script>
  import Navigation from './Navigation';

  import AppTitle from './toolbar/AppTitle';
  import AuthToolbar from './toolbar/AuthToolbar';
  import LocaleSwitcher from './toolbar/LocaleSwitcher';

  export default {
    name: "dashboard",

    components: {
      AppTitle,
      AuthToolbar,
      Navigation,
      LocaleSwitcher
    },

    data() {
      return {
        displayNavbar: false
      }
    },

    mounted() {
      window.addEventListener('beforeunload', (e) => {
        this.resetErrors();
        this.hideNotification();
      });
    }
  }
</script>