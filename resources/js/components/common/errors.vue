<template>
    <div>
        <v-alert color="red" outlined v-show="Object.keys(errors).length">
            <v-list v-for="(fields, index) in errors" :key="index">
                <v-list-item v-for="error in fields" :key="error">
                    <v-list-item-content>
                        <v-list-item-title class="text-sm-left">
                            <span class="red--text">{{ error }}</span>
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-alert>
    </div>
</template>

<script>
  export default {
    name: "errors",

    props: ['errors']
  }
</script>

<style scoped>

</style>