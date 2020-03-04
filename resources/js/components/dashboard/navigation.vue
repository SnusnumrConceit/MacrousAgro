<template>
    <div>
        <v-navigation-drawer v-model="panel.display"
                             :mini-variant="panel.mini"
                             color="success"
                             dark
                             expand-on-hover
                             absolute
                             >

            <v-list dark dense nav class="py-0">

                <v-list-item two-line :class="panel.mini && 'px-0'" @click="toCabinet">
                    <v-list-item-avatar>
                        <img src="https://avatarfiles.alphacoders.com/144/144986.jpg">
                    </v-list-item-avatar>

                    <v-list-item-content>
                        <v-list-item-title>
                            {{ fullName }}
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item v-for="category in nav_categories" :key="category.slug" link @click="toggleChildCategories(category)" dense>
                    <v-list-item-icon>
                        <v-icon>{{ category.icon }}</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>
                            <span v-if="isProductCategory(category)">{{ category.title }}</span>
                            <router-link :to="{ path: `${category.path}` }" v-else>
                                {{ category.title }}
                            </router-link>
                        </v-list-item-title>

                        <v-divider></v-divider>

                        <v-list-item link v-for="product_category in product_categories" :key="product_category.id" v-if="displayChildCategories && isProductCategory(category)">
                            <v-list-item-content>
                                <v-list-item-title>
                                    <router-link :to="`/categories/${product_category.id}/products`">
                                        {{ product_category.name }}
                                    </router-link>
                                </v-list-item-title>

                            </v-list-item-content>
                        </v-list-item>
                    </v-list-item-content>
                </v-list-item>
            </v-list>

            <template v-slot:append>
                <div class="pa-2">
                    <v-btn block>Выйти</v-btn>
                </div>
            </template>


        </v-navigation-drawer>
    </div>
</template>

<script>
  export default {
    name: "navigation",

    props: {
      apiRoute: {
        default: '/api',
        type: String,
        required: true
      }
    },

    data() {
      return {
        nav_categories: [
          {
            title:  'Продукция',
            slug:   'Products',
            icon:   'mdi-dolly'
          },
          {
            title:  'Сводки',
            slug:   'Articles',
            icon:   'mdi-newspaper-variant-outline',
            path:   '/articles'
          },
          {
            title:  'Фотогалерея',
            slug:   'Photos',
            icon:   'mdi-image-outline',
            path:   '/photos'
          },
          {
            title:  'Видеогалерея',
            slug:   'Videos',
            icon:   'mdi-video-outline',
            path:   '/videos'
          },
        ],

        product_categories: [],

        panel: {
          display: true,
          mini:true
        },

        user: {},

        displayChildCategories: false

      }
    },

    computed: {
      isProductCategory() {
        return category => category.slug === 'Products';
      },

      fullName() {
        return `${this.user.first_name} ${this.user.last_name}`;
      }
    },

    methods: {
      async loadCategories() {
        const response = await axios.get(`${this.apiRoute}/categories`);

        this.product_categories = response.data.categories;
      },

      toCabinet() {
        return this.$router.push({name: 'Cabinet'});
      },

      toggleChildCategories(category) {
        return this.isProductCategory(category) ? this.displayChildCategories = ! this.displayChildCategories : '';
      },

      async getUser() {
        const response = await axios.get(`${this.apiRoute}/admin/users/24`);

        this.user = response.data.user;
      },

      async initData() {
        await this.getUser();

        await this.loadCategories();
      }
    },

    created() {
      this.initData();
    }
  }
</script>

<style scoped>
    a {
        display: block;
        color: #fff !important;
        text-decoration: none;
    }
</style>