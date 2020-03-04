<template>
    <v-navigation-drawer app
                         permanent
                         bottom
                         :mini-variant="mini">
        <v-list-item>
            <v-list-item-content>
                <v-list-item-title>
                    Мокроус Агро
                </v-list-item-title>
                <v-list-item-subtitle>
                    {{ $t('nav.subtitle') }}
                </v-list-item-subtitle>
            </v-list-item-content>
        </v-list-item>

        <v-divider></v-divider>

        <v-list nav rounded shaped flat>
            <v-list-item-group color="primary">
                <v-list-item v-for="link in links" :key="link.title" class="text-left">
                    <v-list-item-content>
                        <v-list-item-title>
                            <router-link :to="link.href">
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
    </v-navigation-drawer>
</template>

<script>
  export default {
    name: "navigation",

    data() {
      return {
        links: [
          {
            title: this.$t('nav.links.products'),
            href: '/admin/products',
            icon: 'pe-7s-box1'
          },
          {
            title: this.$t('nav.links.orders'),
            href: '/admin/orders',
            icon: 'pe-7s-cash'
          },
          {
            title: this.$t('nav.links.categories'),
            href: '/admin/categories',
            icon: 'pe-7s-network'
          },
          {
            title: this.$t('nav.links.articles'),
            href: '/admin/news',
            icon: 'pe-7s-news-paper'
          },
          {
            title: this.$t('nav.links.users'),
            href: '/admin/users',
            icon: 'pe-7s-users'
          },
          {
            title: this.$t('nav.links.photos'),
            href: '/admin/photos',
            icon: 'pe-7s-photo-gallery'
          },
          {
            title: this.$t('nav.links.videos'),
            href: '/admin/videos',
            icon: 'pe-7s-film'
          }
        ],

        mini: false
      }
    }
  }
</script>

<style scoped>

</style>
