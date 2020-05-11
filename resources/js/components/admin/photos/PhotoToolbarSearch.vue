<template>
    <v-row>
        <v-text-field v-model="search.keyword"
                      append-icon="search"
                      :label="$t('placeholders.search')"
                      single-line></v-text-field>

        <v-spacer></v-spacer>

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
                        v-model="search.display_created_at"
                        :label="$t('placeholders.uploaded_at')"
                        prepend-icon="event"
                        :hint="$t('placeholders.uploaded_at')"
                        dense
                        readonly
                        clearable
                        persistent-hint
                        single-line
                ></v-text-field>
            </template>

            <v-date-picker
                    @input="calendar = false"
                    :locale="$i18n.locale"
                    width="290"
                    no-title
                    scrollable
                    first-day-of-week="1"
                    color="primary"
                    v-if="calendar"
                    v-model="search.created_at">

                <v-spacer></v-spacer>

                <v-btn color="blue darken-1" @click="calendar = false" text>
                    {{ $t('buttons.cancel') }}
                </v-btn>

                <v-btn color="primary" outlined @click="calendar = false">
                    OK
                </v-btn>
            </v-date-picker>
        </v-menu>
    </v-row>
</template>

<script>
  export default {
    name: "PhotoToolbarSearch",

    data() {
      return {
        search: {
          keyword: '',
          created_at: null,
          display_created_at: null /// new Date().toLocaleString('ru', {year: 'numeric', month: 'numeric', day: 'numeric', timezone: 'utc'})
        },

        calendar: false
      }
    },

    watch: {
      'search': {
        handler: function(after, before) {
          if (after.created_at || after.keyword.length > 3 || (! after.created_at && ! after.keyword.length)) {
            this.$root.$emit('photo-searching', {page: 1, search: this.search})
          }
        },
        deep: true
      },

      'search.created_at': function (after) {
        if (after !== null) {
          this.search.display_created_at = after.split('-').reverse().join('.');
        }
      },

      'search.display_created_at': function (after) {
        if (after === null) {
          this.search.created_at = null;
        }
      }
    },
  }
</script>