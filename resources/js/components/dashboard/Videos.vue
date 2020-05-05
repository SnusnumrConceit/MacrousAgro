<template>
    <v-card height="100%" class="flex-card-full-size">
        <v-card-title>
            {{ $t('nav.links.videos') }}
        </v-card-title>
        <v-card-text>
            <v-row>
                <v-col cols="6" v-for="(video, index) in videos" :key="video.id" height="100%" class="flex-card-full-size">
                    <v-card>
                        <v-card-text>
                            <video :src="video.src" controls height="240" width="320"></video>
                        </v-card-text>
                        <v-card-title class="text-center">
                            {{ video.title }}
                        </v-card-title>
                    </v-card>
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>

<script>
  import debounce from '../../debounce';
  import scroll from '../../mixins/infinite_scroll';
  import {mapActions} from 'vuex';

  export default {
    name: "videos",

    mixins: [scroll],

    data() {
      return {
        videos: [],

        pagination: {
          last_page: 1,
          page: 1
        }
      }
    },

    methods: {
      /**
       * Показ ошибок в форме
       */
      ...mapActions('errors', {
        'resetErrors': 'resetErrors',
        'setErrors': 'setErrors'
      }),

      /**
       * Показ / обнуление уведомлений
       */
      ...mapActions('notifications', {
        'showNotification': 'showNotification',
        'hideNotification': 'hideNotification'
      }),

      async getVideos() {
        try {
          const response = await axios.get(`${this.$attrs.apiRoute}/videos`, {
            params: {
              page: this.pagination.page
            }
          });

          this.videos = (this.pagination.page === 1)
              ? response.data.videos.data
              : this.videos.concat(response.data.videos.data);

          this.pagination.last_page = response.data.videos.last_page;
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message});
        }
      },

      onSearch() {
        if (this.search.keyword.length < 3) {
          return ;
        }

        this.searchData(this);
      },

      searchData: debounce((vm) => {
        axios.get(`${vm.$attrs.apiRoute}/videos/search`, {
          params: {
            page: vm.pagination.page,
          }
        })
            .then(response => {
              vm.videos = response.data.videos.data;
              vm.pagination.last_page = response.data.videos.last_page;
            })
            .catch(error => {
              vm.showNotification({ type: 'error', message: error.data.message});
            })
        ;
      }, 300),

      onScroll() {
        this.paginationScroll(this, document, 'getVideos');
      }
    },

    created() {
      this.getVideos();
    },

    mounted() {
      document.addEventListener('scroll', this.onScroll);
    }
  }
</script>

<style scoped>
    .flex-card-full-size {
        display: flex;
        flex-direction: column;
    }
</style>