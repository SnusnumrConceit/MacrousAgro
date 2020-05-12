<template>
    <div>
        <v-simple-table v-show="! loading && products.length" :height="750">
            <thead>
            <th v-for="header in table.headers" :key="header">
                {{ header }}
            </th>
            </thead>
            <tbody>
            <tr v-for="product in products" :key="product.id">
                <td class="text-left">
                    <v-row>
                        <v-col cols="2">
                            <v-img :src="product.src" :aspect-ratio="1.5" contain position="left" v-if="product.src.length"></v-img>
                            <v-icon v-else>
                                mdi-image
                            </v-icon>
                        </v-col>
                        <v-col cols="10" class="align-self-sm-center">
                                        <span>
                                            {{ product.title }}
                                        </span>
                        </v-col>
                    </v-row>
                </td>
                <td class="text-left">
                    {{ product.price }} руб.
                </td>
                <td class="text-left">
                    {{ product.created_at }}
                </td>
                <td class="text-left">
                    {{ product.updated_at }}
                </td>
                <td class="text-right">
                    <v-tooltip top color="primary">
                        <template v-slot:activator="{ on }">
                            <v-icon v-on="on"
                                    small
                                    @click="$router.push(`/admin/products/${product.id}`)"
                            >
                                mdi-pencil
                            </v-icon>
                        </template>
                        <span>
                                    {{ $t('tooltips.edit') }}
                                </span>
                    </v-tooltip>

                    <v-tooltip top color="error">
                        <template v-slot:activator="{ on }">
                            <v-icon color="red"
                                    v-on="on"
                                    small
                                    @click="remove(product.id)"
                            >
                                mdi-delete
                            </v-icon>
                        </template>
                        <span>
                                    {{ $t('tooltips.delete') }}
                                </span>
                    </v-tooltip>
                </td>
            </tr>
            </tbody>
        </v-simple-table>

        <span v-show="! loading && products.length" class="d-flex flex-row-reverse">
            {{ $t('total', {total: pagination.total}) }}
        </span>

        <v-skeleton-loader type="table-row-divider@6" v-show="loading" />

        <v-alert color="info" outlined v-if="! loading && ! products.length">

            <span v-show="! searching">
                {{ $t('products.no_products') }}
            </span>
            <span v-show="searching">
                {{ $t('no_results') }}
            </span>
        </v-alert>
    </div>
</template>

<script>
  import debounce from '../../../debounce';
  import scroll from "../../../mixins/infinite_scroll";

  import { mapActions } from 'vuex';

  export default {
    name: "ProductList",

    mixins: [scroll],

    data() {
      return {
        products: [],

        table: {
          headers: [
            this.$t('products.table.headers.title'),
            this.$t('products.table.headers.price'),
            this.$t('products.table.headers.created_at'),
            this.$t('products.table.headers.updated_at'),
            ''
          ]
        },

        pagination: {
          page: 1,
          last_page: 1,
          total: ''
        },

        searching: false,
        loading: false,
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
       * Загрузка списка товаров
       *
       * @returns {Promise<void>}
       */
      async getProducts() {
        this.loading = true;

        try {
          const response = await axios.get(`/products`, {
            params: {
              page: this.pagination.page
            }
          });

          this.products = (this.pagination.page === 1)
              ? response.data.products.data
              : this.products.concat(response.data.products.data);

          this.pagination.last_page = response.data.products.last_page;
          this.pagination.total = response.data.products.total;
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message});
        } finally {
          this.loading = false;
        }
      },

      /**
       * Обработчик события debounce-поиска товаров
       */
      onSearch({search, page}) {
        if (page) {
          this.pagination.page = page;
        }

        this.searching = true;

        this.searchData(this, search);
      },

      /**
       * Поиск по товарам
       */
      searchData: debounce((vm, search) => {
        axios.get(`/products`, {
          params: {
            page: vm.page,
            keyword: search.keyword,
            created_at: search.created_at,
            category: search.category
          }
        }).then(response => {
          vm.products = (vm.pagination.page === 1)
              ? response.data.products.data
              : vm.products.concat(response.data.products.data);

          vm.pagination.last_page = response.data.products.last_page;
          vm.pagination.total = response.data.products.total;
        }).catch(error => console.error(error));
      }, 300),

      /**
       * Удаление товара
       *
       * @param id
       * @returns {Promise<void>}
       */
      async remove(id) {
        try {
          const response = await axios.delete(`/products/${id}`);

          this.showNotification({ type: 'success', message: response.data.message});
          this.products = this.products.filter(product => product.id !== id);
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message});
        }
      },

      /**
       * Инициализация компонента
       *
       * @returns {Promise<void>}
       */
      async initData() {
        this.loading = true;

        await this.getProducts();

        this.loading = false;
      },

      /**
       * Обработчик события скролла в таблице
       */
      onScroll: function() {
        this.paginationScroll(this, $('.v-data-table__wrapper')[0], 'getProducts');
      },
    },

    created() {
      this.initData();
    },

    mounted() {
      $('.v-data-table__wrapper')[0].addEventListener('scroll', this.onScroll);

      this.$root.$on('product-searching', this.onSearch);
      this.$root.$on('product-created', this.initData);
    },

    destroyed() {
      $('.v-data-table__wrapper')[0].removeEventListener('scroll', this.onScroll);
    }
  }
</script>