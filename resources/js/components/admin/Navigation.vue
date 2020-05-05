<template>
    <v-navigation-drawer app
                         permanent
                         bottom
                         :mini-variant="mini">
        <v-list-item>
            <v-list-item-content>
                <v-list-item-title>
                    {{ $t('app_name')}}
                </v-list-item-title>
                <v-list-item-subtitle>
                    {{ $t('nav.subtitle') }}
                </v-list-item-subtitle>
            </v-list-item-content>
        </v-list-item>

        <v-divider></v-divider>

        <v-list nav rounded shaped flat>
            <v-list-item-group color="primary">
                <v-list-item v-for="link in links" :key="link.title" class="text-left" v-if="isGuarded(link.guard)">
                    <v-list-item-content>
                        <v-list-item-title>
                            <router-link :to="link.href" class="d-block">
                                <i :class="link.icon"></i>
                                <span>
                                   {{ link.title }}
                                </span>

                            </router-link>
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list-item-group>
        </v-list>

        <template v-slot:append>
            <div class="pa-2">
                <v-list>
                    <v-list-item>
                        <v-list-item-avatar>
                            <v-avatar color="primary" size="36">
                                <span class="white--text headline">{{ initials }}</span>
                            </v-avatar>
                            <!--<img src="https://avatarfiles.alphacoders.com/144/144986.jpg">-->
                        </v-list-item-avatar>

                        <v-list-item-content>
                            <v-list-item-title>
                                {{ fullName }}
                            </v-list-item-title>
                            <v-list-item-subtitle>
                                {{ role }}
                            </v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
                <logout />
            </div>
        </template>
    </v-navigation-drawer>
</template>

<script>
  import { mapGetters } from 'vuex';
  import Logout from '../auth/Logout';

  export default {
    name: "navigation",

    components: {
      Logout
    },

    data() {
      return {
        links: [
          {
            title: this.$t('nav.links.products'),
            href: '/admin/products',
            icon: 'pe-7s-box1',
            guard: null
          },
          {
            title: this.$t('nav.links.orders'),
            href: '/admin/orders',
            icon: 'pe-7s-cash',
            guard: null
          },
          {
            title: this.$t('nav.links.categories'),
            href: '/admin/categories',
            icon: 'pe-7s-network',
            guard: null
          },
          {
            title: this.$t('nav.links.articles'),
            href: '/admin/articles',
            icon: 'pe-7s-news-paper',
            guard: null
          },
          {
            title: this.$t('nav.links.users'),
            href: '/admin/users',
            icon: 'pe-7s-users',
            guard: 'isAdmin'
          },
          {
            title: this.$t('nav.links.photos'),
            href: '/admin/photos',
            icon: 'pe-7s-photo-gallery',
            guard: null
          },
          {
            title: this.$t('nav.links.videos'),
            href: '/admin/videos',
            icon: 'pe-7s-film',
            guard: null
          }
        ],

        mini: false
      }
    },

    computed: {
      ...mapGetters('auth', {
        'user': 'user',
        'isAdmin': 'isAdmin'
      }),

      fullName() {
        return `${this.user.first_name} ${this.user.last_name}`;
      },

      initials() {
        return `${this.user.first_name[0]}${this.user.last_name[0]}`
      },

      role() {
        return this.user.role.toUpperCase();
      }
    },

    methods: {
      isGuarded(guard) {
       return ! guard || this[guard];
      }
    }
  }
</script>

<style scoped>

</style>
