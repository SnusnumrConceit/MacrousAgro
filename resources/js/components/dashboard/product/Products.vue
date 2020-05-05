<template>
    <div>
        <v-card>
            <v-card-title>
                   {{ categoryName }}
            </v-card-title>
            <v-card-text>
                <v-row>
                    <v-col lg="4" md="12">
                        <v-card v-for="product in products" :key="product.key">
                            <v-img :src="product.url || 'https://cdn.vuetifyjs.com/images/cards/docks.jpg'">
                                <v-card-title class="text--white">
                                    {{ product.title }}
                                </v-card-title>
                            </v-img>
                            <v-card-text class="text-justify">
                                {{ product.description }}
                            </v-card-text>
                            <v-card-actions>
                                <v-btn outlined color="primary" @click="toDetail(product.id)">
                                    {{ $t('buttons.details') }}
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
  import debounce from '../../../debounce';
  import scroll from '../../../mixins/infinite_scroll';

  export default {
    name: "products",

    mixins: [scroll],

    data() {
      return {
        products: [],

        pagination: {
          page: 1,
          last_page: 1
        },

        is_search: false,
        keyword: '',

        category_name: ''
      }
    },

    computed: {
      categoryID() {
        return this.$route.params.category_id;
      },

      categoryName() {
        return this.$route.params.category_name || this.category_name;
      }
    },

    methods: {
      toDetail(id) {
        this.$router.push(`/products/${id}`);
      },

      /**
       * Получить список товаров
       *
       * @return {Promise<void>}
       */
      async getProducts() {
        const response = await axios.get(`${this.$attrs.apiRoute}/category/${this.categoryID}/products`, {
          params: {
            page: this.pagination.page
          }
        });

        this.products = (this.pagination.page === 1)
            ? response.data.products.data
            : this.products.concat(response.data.products.data);

        this.pagination.last_page = response.data.products.last_page;
        this.category_name = response.data.category_name;
      },

      /**
       * Поиск товаров
       *
       * @return {Promise<void>}
       */
      async searchData() {
        const response = await axios.get(`${this.$attrs.apiRoute}/category/${this.categoryID}/products`, {
          params: {
            keyword: this.keyword,
            page: this.pagination.page
          }
        });
      },

      /**
       * Слушатель на скролле
       */
      onScroll() {
        this.paginationScroll(this, document, 'getProducts');
      }
    },

    created() {
      this.getProducts();
    },

    mounted() {
      document.addEventListener('scroll', this.onScroll);
    }
  }
</script>

<style scoped>

</style>