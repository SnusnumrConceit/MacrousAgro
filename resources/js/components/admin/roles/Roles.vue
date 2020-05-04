<template>

</template>

<script>
  import {mapActions} from 'vuex';

  export default {
    name: "index",

    methods: {
      /**
       * Показ ошибок в форме
       */
      ...mapActions('errors', {
        'resetErrors': 'resetErrors',
        'setErrors': 'setErrors'
      }),

      /**
       * Показ / обнуление уведомлений
       */
      ...mapActions('notifications', {
        'showNotification': 'showNotification',
        'hideNotification': 'hideNotification'
      }),
    },

    beforeDestroy() {
      this.hideNotification();
    }
  }
</script>

<style scoped>

</style>
