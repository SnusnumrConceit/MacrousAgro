<template>
    <v-app dark>
        <notification></notification>
        <navigation></navigation>
        <v-content fluid>
            <v-container fluid cols="12">
                <router-view apiRoute="/api/admin"></router-view>
            </v-container>
        </v-content>
        <v-footer app></v-footer>
    </v-app>
</template>

<script>
  import Navigation from "./Navigation";

  export default {
    name: "admin",
    components: {Navigation}
  }
</script>

<style scoped>

</style>
