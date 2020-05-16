<template>
    <v-row>
        <v-col cols="2">
            <v-menu offset-y>
                <template v-slot:activator="{on}">
                    <v-btn text v-on="on" @click="menu = true">
                        <v-img :src="`/icons/${locale}.svg`" max-width="32px" max-height="32px" />
                    </v-btn>
                </template>
                <v-list v-show="menu">
                    <v-list-item v-for="locale in locales" :key="locale" @click="setLocale(locale)">
                        <v-list-item-title>
                            <v-img :src="`/icons/${locale}.svg`" max-width="32px" max-height="32px" />
                        </v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-col>
    </v-row>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';

  export default {
    name: "LocaleSwitcher",

    data() {
      return {
        locales: ['ru', 'en'],

        menu: false
      }
    },

    computed: {
      ...mapGetters('locale', [
        'locale'
      ])
    },

    methods: {
      ...mapActions('locale', [
        'setLocale'
      ])
    }
  }
</script>