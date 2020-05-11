<template>
    <v-row>
        <v-col cols="4">
            <v-text-field v-model="search.keyword" append-icon="search"
                          :label="$t('placeholders.search')"
                          single-line>
            </v-text-field>
        </v-col>

        <v-spacer />

        <v-col cols="4">
            <v-menu ref="search-calendar-menu"
                    v-model="calendar"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    max-width="290px"
                    min-width="200px">
                <template v-slot:activator="{ on }">
                    <v-text-field
                            @click="calendar = true"
                            v-model="search.display_publication_date"
                            :label="$t('placeholders.publication_date')"
                            prepend-icon="event"
                            :hint="$t('placeholders.publication_date')"
                            dense
                            readonly
                            clearable
                            persistent-hint
                            single-line

                    ></v-text-field>
                </template>

                <v-date-picker :locale="$i18n.locale"
                               width="290"
                               @input="calendar = false"
                               no-title
                               scrollable
                               first-day-of-week="1"
                               color="primary"
                               v-if="calendar"
                               v-model="search.publication_date">
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1"
                           @click="calendar = false"
                           text>
                        {{ $t('buttons.cancel') }}
                    </v-btn>
                    <v-btn color="primary"
                           outlined
                           @click="calendar = false">
                        OK
                    </v-btn>
                </v-date-picker>
            </v-menu>
        </v-col>

        <v-spacer />

        <v-col cols="4">
            <v-checkbox v-model="search.is_publicated" :label="$t('articles.checkboxes.publication')"></v-checkbox>
        </v-col>
    </v-row>
</template>

<script>
  export default {
    name: "ArticleToolbarSearch",

    data() {
      return {
        search: {
          keyword: '',
          publication_date: null,
          display_publication_date: null,
          is_publicated: Number(false)
        },

        calendar: false,
      }
    },

    watch: {
      /**
       * отслеживание поиска в целом
       *
       */
      'search': {
        handler: function(after, before) {
          if (after.keyword.length > 3 || after.publication_date || after.is_publicated || (! after.keyword.length && ! after.publication_date && ! after.is_publicated)) {
            this.$root.$emit('article-searching', {search: this.search, page: 1})
          }
        },

        deep: true
      },

      'search.is_publicated': function (after) {
        this.search.is_publicated = Number(after);
      },

      /**
       * отслеживание форматирования даты публикации в  поисковой форме
       */
      'search.publication_date': function (after, before) {
        if (after !== null) {
          this.search.display_publication_date = after.split('-').reverse().join('.');
        }
      },

      'search.display_publication_date': function (after, before) {
        if (after === null) {
          this.search.publication_date = null;
        }
      },
    }
  }
</script>