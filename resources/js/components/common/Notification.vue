<template>
    <v-snackbar v-model="notification.show" :color="notification.type" :timer="notification.timer" multi-line>
        <v-icon>
            {{ notification.icon }}
        </v-icon>
        <h3>{{ notification.message }}</h3>
        <v-btn dark text @click="hideNotification">
            {{ $t('buttons.close') }}
        </v-btn>
    </v-snackbar>
</template>

<script>
  import {mapGetters, mapActions} from 'vuex';

  export default {
    name: "Notification",

    computed: {
      ...mapGetters('notifications', {
        'notification': 'notification'
      })
    },

    methods: {
      ...mapActions('notifications', {
        'hideNotification': 'hideNotification'
      })
    }
  }
</script>

<style scoped>

</style>