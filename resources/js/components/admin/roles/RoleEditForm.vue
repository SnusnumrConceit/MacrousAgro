<template>

</template>

<script>
  import {mapActions} from 'vuex';

  export default {
    name: "form",

    data() {
      return {
        role: {
          name: '',
          description: '',
          slug: ''
        }
      }
    },

    computed: {
      id() {
        return this.$route.params.id;
      }
    },

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

      /**
       * Получить роль
       *
       * @return {Promise<void>}
       */
      async getRole() {
        try {
          const response = await axios.get(`${this.$attrs.apiRoute}/roles/${this.id}`);

          this.role = response.data.role;
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message });
        }
      },

      /**
       * Сохранить роль
       *
       * @return {Promise<void>}
       */
      async save() {
        try {
          const response = await axios.patch(`${this.$attrs.apiRoute}/roles/${this.id}`, {
            ...this.role
          });

          this.showNotification({ type: 'success', message: response.data.message });
          this.$router.push({ name: 'Roles' });
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message });
        }
      }
    },

    created() {
      if (this.id) {
        this.getRole();
      }
    },

    beforeDestroy() {
      this.hideNotification();
    }
  }
</script>

<style scoped>

</style>
