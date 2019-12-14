<template>
    <v-app dark>
        <navigation></navigation>
<!--        <v-toolbar app></v-toolbar>-->
        <v-content fluid>
            <v-container fluid cols="12">
                <router-view></router-view>
            </v-container>
        </v-content>
        <v-footer app></v-footer>
    </v-app>
</template>

<script>
    import Navigation from "./navigation";
    export default {
        name: "admin",
      components: {Navigation}
    }
</script>

<style scoped>

</style>
