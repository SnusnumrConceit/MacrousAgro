<template>
    <v-row>
        <v-text-field v-model="search.keyword"
                      @keyup.enter="onSearch"
                      append-icon="search"
                      :label="$t('placeholders.search')"
                      single-line></v-text-field>

        <v-spacer />

        <v-menu ref="search-calendar-menu"
                v-model="calendar"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                max-width="290px"
                min-width="200px">
            <template v-slot:activator="{ on }">
                <v-text-field
                        v-on="on"
                        v-model="search.display_updated_at"
                        :label="$t('placeholders.last_activity')"
                        :hint="$t('placeholders.last_activity')"
                        persistent-hint
                        dense
                        readonly
                        clearable
                        single-line
                        prepend-icon="event"
                ></v-text-field>
            </template>

            <v-date-picker :locale="$i18n.locale"
                           width="290"
                           first-day-of-week="1"
                           color="primary"
                           no-title
                           scrollable
                           @input="calendar = false"
                           v-model="search.updated_at">
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" @click="calendar = false" text>
                    {{ $t('buttons.cancel') }}
                </v-btn>
                <v-btn color="primary" outlined @click="calendar = false">OK</v-btn>
            </v-date-picker>
        </v-menu>
    </v-row>
</template>

<script>
  export default {
    name: "UserToolbarSearch",

    data() {
      return {
        search: {
          keyword: '',
          updated_at: null,
          display_updated_at: null,
          /**
          new Date().toLocaleString('ru', {
            year: 'numeric',
            month: 'numeric',
            day: 'numeric',
            timezone: 'utc'
          })
          */
        },

        calendar: false,
      }
    },

    watch: {
      'search': {
        handler: function (after, before) {
          if (after.updated_at !== null || after.keyword.length > 3 || (! after.updated_at && ! after.keyword.length)) {
            this.$root.$emit('user-searching', {search: this.search, page: 1});
          }
        },

        deep: true
      },

      'search.updated_at': function (after) {
        if (after !== null) {
          this.search.display_updated_at = after.split('-').reverse().join('.');
        }
      },

      'search.display_updated_at': function (after) {
        if (after === null) {
          this.search.updated_at = null;
        }
      },
    }
  }
</script>