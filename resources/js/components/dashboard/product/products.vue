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
                            <v-card-title>
                                {{ product.title }}
                            </v-card-title>
                            <v-card-text>
                                <v-img :src="product.url"></v-img>
                                {{ product.description }}
                            </v-card-text>
                            <v-card-actions>
                                <v-btn outlined color="primary" @click="toDetail(product.id)">
                                    Подробнее
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

  export default {
    name: "products",

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

      async loadData() {
        const response = await axios.get(`${this.$attrs.apiRoute}/category/${this.categoryID}/products`, {
          params: {
            page: this.pagination.page
          }
        });

        this.products = response.data.products.data;
        this.pagination.last_page = response.data.products.last_page;
        this.category_name = response.data.category_name;
      },

      async searchData() {
        const response = await axios.get(`${this.$attrs.apiRoute}/category/${this.categoryID}/products`, {
          params: {
            keyword: this.keyword,
            page: this.pagination.page
          }
        });
      },

      onScroll() {
        this.paginationScroll(this, document);
      },

      paginationScroll: debounce((vm, element) => {
        if (vm.loading) {
          return ;
        }

        let atTheBottom = false;
        let scrollTop = (element === document) ? element.documentElement.scrollTop : element.scrollTop;
        let viewportHeight = (element === document) ? window.innerHeight : element.offsetHeight;
        let totalHeight = (element === document) ? element.documentElement.offsetHeight : element.scrollHeight;

        atTheBottom = scrollTop + viewportHeight === totalHeight;

        if (atTheBottom && vm.pagination.page !== vm.pagination.last_page) {
          vm.pagination.page++;
          vm.is_search ? vm.searchData(vm) : vm.loadData();
        }
      }, 300),
    },

    created() {
      this.loadData();
    },

    mounted() {
      document.addEventListener('scroll', this.onScroll);
    }
  }
</script>

<style scoped>

</style>