<template>
    <div>
        <v-simple-table fixed-header :height="750" v-show="! loading && categories.length">
            <template v-slot:default>
                <thead>
                <th v-for="header in table.headers" :key="header.text" class="text-left">
                    {{ header.text }}
                </th>
                </thead>
                <tbody>
                <tr v-for="category in categories" :key="category.id">
                    <td class="text-left">
                        {{ category.name }}
                    </td>
                    <td class="text-right">
                        <v-tooltip top color="primary">
                            <template v-slot:activator="{ on }">
                                <v-icon v-on="on"
                                        small
                                        @click="$router.push(`/admin/categories/${category.id}`)"
                                >
                                    mdi-pencil
                                </v-icon>
                            </template>
                            <span>
                                {{ $t('buttons.edit') }}
                            </span>
                        </v-tooltip>

                        <v-tooltip top color="error">
                            <template v-slot:activator="{ on }">
                                <v-icon color="red"
                                        v-on="on"
                                        small
                                        @click="remove(category.id)"
                                >
                                    mdi-delete
                                </v-icon>
                            </template>
                            <span>
                                {{ $t('buttons.delete') }}
                            </span>
                        </v-tooltip>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td></td>
                    <td class="text-right">
                        {{ $t('total', {total: categories.length}) }}
                    </td>
                </tr>
                </tfoot>
            </template>
        </v-simple-table>

        <v-skeleton-loader type="table-row-divider@6" v-show="loading">

        </v-skeleton-loader>

        <v-alert color="info" outlined v-if="! loading && ! categories.length">
            <div class="">
                <span v-show="! searching">
                    {{ $t('categories.no_categories') }}
                </span>
                <span v-show="searching">
                    {{ $t('no_results') }}
                </span>
            </div>
        </v-alert>
    </div>
</template>

<script>
  import {mapActions} from 'vuex';

  import debounce from '../../../debounce';

  export default {
    name: "CategoryList",

    data() {
      return {
        table: {
          headers: [
            {
              text: this.$t('categories.table.headers.title'),
              align: 'left',
              sortable: true,
              value: 'name'
            },
            {}
          ]
        },

        categories: [],

        searching: false,

        pagination: {
          last_page: 1,
          page: 1
        },

        loading: false
      }
    },

    computed: {
      // ...mapGetters({
      //   'categories': 'category/categories',
      //   'loading':    'category/loading'
      // })
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
       * Загрузка списка категорий
       */
      // async getCategories() {
      //   this.$store.dispatch('category/getCategories');
      // },
      async getCategories(page = 1) {
        this.loading = true;

        try {
          const response = await axios.get(`/categories`, {
            params: {
              page: this.pagination.page
            }
          });

          this.categories = response.data.categories;
        } catch (e) {
          this.showNotification({type: 'error', message: e.response.data.message});
        } finally {
          this.loading = false;
        }
      },

      /**
       * Обработчик события debounce-поиска категорий
       */
      onSearch({search, page}) {
        this.pagination.page = page;

        this.searching = true;

        this.searchData(this, search);
      },

      /**
       * Поиск по категориям
       *
       * Category search
       *
       */
      searchData: debounce((vm, search) => {
        axios.get(`/categories`, {
          params: {
            page: vm.pagination.page,
            keyword: search.keyword
          }
        }).then(response => {
          vm.categories = response.data.categories;
          vm.pagination.last_page = response.data.categories.last_page;
        }).catch(error => {
          vm.showNotification({type: 'error', message: error.data.message});
        });
      }, 300),

      /**
       * Удаление категории
       *
       * Remove category
       *
       * @param id
       * @returns {Promise<boolean>}
       */
      async remove(id) {
        try {
          const response = await axios.delete(`/categories/${id}`);

          this.showNotification({type: 'success', message: response.data.message});
          this.categories = this.categories.filter(category => category.id !== id);
        } catch (e) {
          this.showNotification({type: 'error', message: e.response.data.errors || e.response.data.message});
        }
      },

      /**
       * Инициализация категорий компонента
       *
       * Initialize categories
       *
       * @returns {Promise<void>}
       */
      async initData() {
        this.loading = true;

        await this.getCategories();

        this.loading = false;
      }
    },

    created() {
      this.initData();
    },

    mounted() {
      this.$root.$on('category-created', this.initData);

      this.$root.$on('category-searching', this.onSearch);
      this.$root.$on('category-search-reset', this.onSearch);
    }
  }
</script>