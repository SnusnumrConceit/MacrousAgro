<template>
    <v-btn @click="logout"
           :block="block"
           :text="text"
           :outlined="outlined"
           :color="color">
        Выйти
    </v-btn>
</template>

<script>
  import {mapActions} from 'vuex';

  export default {
    name: "logout",

    props: {
      text: {
        default: false,
        type: Boolean
      },

      color: {
        default: 'white',
        type: String
      },

      outlined: {
        default: false,
        type: Boolean
      },

      block: {
        default: true,
        type: Boolean
      },
    },

    methods: {
      ...mapActions('auth', {
        'logout': 'logout'
      })
    }
  }
</script>

<style scoped>

</style>