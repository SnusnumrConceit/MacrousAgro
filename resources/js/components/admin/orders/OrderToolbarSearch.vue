<template>
    <v-row>
        <v-col cols="4" sm="4">
            <v-text-field v-model="search.keyword"
                          @keyup.enter="onSearch"
                          append-icon="search"
                          :label="$t('placeholders.search')"
                          single-line>
            </v-text-field>
        </v-col>

        <v-spacer />

        <v-col cols="4" sm="4">
            <v-select v-model="search.status"
                      clearable
                      :items="status_codes"
                      :label="$t('orders.placeholders.status')">
                <template v-slot:item="{item}">
                    <v-list>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title>
                                    {{ $t(`orders.statuses.${item}`) }}
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </template>
                <template v-slot:selection="{item}">
                            <span>
                                {{ $t(`orders.statuses.${item}`) }}
                            </span>
                </template>
            </v-select>
        </v-col>

        <v-spacer />

        <v-col cols="4" sm="4">
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
                            :hint="$t('placeholders.created_at')"
                            dense
                            readonly
                            clearable
                            persistent-hint
                            single-line

                    ></v-text-field>
                </template>

                <v-date-picker :locale="$i18n.locale"
                               width="290"
                               no-title
                               scrollable
                               first-day-of-week="1"
                               color="primary"
                               v-if="calendar"
                               @input="calendar = false"
                               v-model="search.created_at">

                    <v-spacer />

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
    </v-row>
</template>

<script>
  import {mapActions} from 'vuex';

  export default {
    name: "OrderToolbarSearch",

    data() {
      return {
        status_codes: [],

        search: {
          keyword: '',
          created_at: null,
          display_created_at: null, // new Date().toLocaleString('ru', {year: 'numeric', month: 'numeric', day: 'numeric', timezone: 'utc'}),
          status: ''
        },

        calendar: false,
      }
    },

    methods: {
      /**
       * Показ ошибок в форме
       */
      ...mapActions('errors', [
        'resetErrors',
        'setErrors'
      ]),

      /**
       * Показ / обнуление уведомлений
       */
      ...mapActions('notifications', [
        'showNotification'
      ]),

      /**
       * Получить список статус кодов
       */
      async getStatusCodes() {
        try {
          const response = await axios.get('/order_status_codes');
          this.status_codes = response.data;
        } catch (e) {
          this.showNotification({ type: 'error', message: e.data.message});
        }
      }
    },

    watch: {
      'search': {
        handler: function(after, before) {
          if (after.status || after.created_at || after.keyword.length || (! after.status && ! after.created_at && ! after.keyword.length)) {
            this.$root.$emit('order-searching', {search: this.search, page: 1});
          }
        },

        deep: true
      },

      'search.created_at': function (after, before) {
        if (after !== null) {
          this.search.display_created_at = after.split('-').reverse().join('.');
        }
      },

      'search.display_created_at': function (after, before) {
        if (after === null) {
          this.search.created_at = null;
        }
      }
    },

    created() {
      this.getStatusCodes();
    }
  }
</script>