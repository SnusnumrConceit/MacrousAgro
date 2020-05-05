<template>
    <div>
        <v-navigation-drawer v-model="panel.display"
                             :mini-variant="panel.mini"
                             color="success"
                             ref="navigation_drawer"
                             dark
                             expand-on-hover
                             absolute
                             temporary
        >

            <v-list dark dense nav class="py-0">

                <v-list-item two-line :class="panel.mini && 'px-0'" @click="toCabinet" v-if="user">
                    <v-list-item-avatar>
                        <v-avatar @click="toCabinet" color="primary" size="36">
                            <span class="white--text headline">{{ initials }}</span>
                        </v-avatar>
                    </v-list-item-avatar>

                    <v-list-item-content>
                        <v-list-item-title>
                            {{ fullName }}
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item v-for="category in nav_categories" :key="category.slug" link
                             @click="toggleChildCategories(category)" dense>
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

                        <v-list-item link v-for="product_category in product_categories" :key="product_category.id"
                                     v-if="displayChildCategories && isProductCategory(category)">
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
                <div class="pa-2" v-if="user">
                    <logout color="black"></logout>
                </div>
            </template>


        </v-navigation-drawer>
    </div>
</template>

<script>
  import {mapGetters} from 'vuex';
  import Logout from '../auth/Logout';

  export default {
    name: "navigation",

    components: {
      Logout
    },

    props: {
      apiRoute: {
        default: '/api',
        type: String,
        required: true
      },

      display: {
        default: false,
        type: Boolean,
        required: true
      }
    },

    data() {
      return {
        nav_categories: [
          {
            title: this.$t('nav.links.production'),
            slug: 'Products',
            icon: 'mdi-dolly'
          },
          {
            title: this.$t('nav.links.resumes'),
            slug: 'Articles',
            icon: 'mdi-newspaper-variant-outline',
            path: '/articles'
          },
          {
            title: this.$t('nav.links.photos'),
            slug: 'Photos',
            icon: 'mdi-image-outline',
            path: '/photos'
          },
          {
            title: this.$t('nav.links.videos'),
            slug: 'Videos',
            icon: 'mdi-video-outline',
            path: '/videos'
          },
        ],

        product_categories: [],

        panel: {
          display: this.display,
          mini: true
        },

        displayChildCategories: false

      }
    },

    computed: {
      ...mapGetters('auth', {
        'user': 'user'
      }),

      isProductCategory() {
        return category => category.slug === 'Products';
      },

      fullName() {
        return `${this.user.first_name} ${this.user.last_name}`;
      },

      initials() {
        return `${this.user.first_name[0]}${this.user.last_name[0]}`
      }
    },

    methods: {
      async loadCategories() {
        const response = await axios.get(`${this.apiRoute}/categories`);

        this.product_categories = response.data.categories;
      },

      toCabinet() {
        if  (this.$route.name === 'Cabinet') {
          return ;
        }

        this.$router.push({name: 'Cabinet'});
      },

      toggleChildCategories(category) {
        return this.isProductCategory(category) ? this.displayChildCategories = !this.displayChildCategories : '';
      },

      async initData() {
        await this.loadCategories();
      }
    },

    watch: {
      display: function (after, before) {
        if (after) {
          this.panel.display = after;
        }
      },

      'panel.display': function (after) {
        if (! after) {
          this.$emit('hide-navigation-drawer')
        }
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