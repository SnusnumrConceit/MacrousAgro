<template>
    <v-row>
        <v-col cols="4">
            <v-text-field v-model="search.keyword"
                          @keyup.enter="onSearch"
                          append-icon="search"
                          :label="$t('placeholders.search')"
                          single-line>
            </v-text-field>
        </v-col>

        <v-spacer />

        <v-col cols="4">
            <v-select :items="categories"
                      v-model="search.category"
                      :label="$t('products.placeholders.category')"
                      single-line
                      item-text="name"
                      item-value="id"
                      clearable>
            </v-select>
        </v-col>

        <v-spacer></v-spacer>

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
                            v-on="on"
                            v-model="search.display_created_at"
                            :label="$t('placeholders.created_at')"
                            prepend-icon="event"
                            dense
                            readonly
                            clearable
                            :hint="$t('placeholders.created_at')"
                            persistent-hint
                            single-line

                    ></v-text-field>
                </template>

                <v-date-picker :locale="$i18n.locale"
                               width="290"
                               no-title
                               scrollable
                               @input="calendar = false"
                               first-day-of-week="1"
                               color="primary"
                               v-if="calendar"
                               v-model="search.created_at">
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" @click="calendar = false" text>
                        {{ $t('buttons.cancel') }}
                    </v-btn>
                    <v-btn color="primary" outlined @click="calendar = false">OK</v-btn>
                </v-date-picker>
            </v-menu>
        </v-col>
    </v-row>
</template>

<script>
  export default {
    name: "ProductToolbarSearch",

    data() {
      return {
        categories: [],

        search: {
          keyword: '',
          created_at: null,
          display_created_at: null, //new Date().toLocaleString('ru', {year: 'numeric', month: 'numeric', day: 'numeric', timezone: 'utc'}),
          category: ''
        },

        calendar: false
      }
    },

    methods: {
      /**
       * Загрузка списка категорий
       *
       * @returns {Promise<void>}
       */
      async getCategories() {
        try {
          const response = await axios.get(`/categories`);

          this.categories = response.data.categories;
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message});
        }
      },
    },

    watch: {
      'search': {
        handler: function(after, before) {
          if (after.category || after.created_at || after.keyword.length > 3 || (! after.category && ! after.created_at && ! after.keyword.length)) {
            this.$root.$emit('product-searching', {search: this.search, page: 1});
          }
        },

        deep: true
      },

      'search.display_created_at': function (after) {
        if (after === null) {
          this.search.created_at = null;
        }
      },

      'search.created_at': function (after) {
        if (after !== null) {
          this.search.display_created_at = after.split('-').reverse().join('.');
        }
      }
    },

    created() {
      this.getCategories();
    }
  }
</script>