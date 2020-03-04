<template>
    <v-app dark>
        <v-container>
            <navigation api-route="/api">

            </navigation>
            <router-view apiRoute="/api"></router-view>
        </v-container>
    </v-app>
</template>

<script>
    import Navigation from './navigation';
  export default {
    name: "dashboard",

    components: {
      Navigation
    },
  }
</script>

<style scoped>

</style>