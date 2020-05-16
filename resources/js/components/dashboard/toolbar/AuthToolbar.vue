<template>
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

        <v-spacer />

        <logout :text="true" :outlined="true" :block="false" />
    </div>
</template>

<script>
  import Login from '../../auth/Login';
  import Logout from '../../auth/Logout';
  import Registration from '../../auth/Registration';
  import { mapActions, mapGetters } from 'vuex';

  export default {
    name: "AuthToolbar",

    components: {
      Login,
      Registration,
      Logout,
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
      ])
    },

  }
</script>