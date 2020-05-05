<template>
    <div>
        <v-alert color="red" outlined v-show="Object.keys(errors).length" v-if="isObject(errors)">
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
        <v-alert color="red" outlined v-else>
            <v-list>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title class="text-sm-left">
                            <span class="red--text">{{ errors }}</span>
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-alert>
    </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default {
    name: "errors",

    computed: {
      ...mapGetters('errors', {
        'errors': 'errors'
      }),
    },

    methods: {
      isObject(obj) {
        return typeof obj === 'object';
      }
    }
  }
</script>

<style scoped>

</style>