<template>
    <div>
        <v-row v-show="! loading && videos.length">
            <v-col col="6" v-for="video in videos" :key="video.id" height="100%"
                   class="flex-card-full-size">
                <v-card height="100%" class="flex-card-full-size">
                    <v-card-title>
                        {{ video.title }}
                    </v-card-title>
                    <v-card-text class="m-b-md">
                        <video :src="video.src" controls height="240" width="320"></video>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn color="error" outlined @click="remove(video.id)">
                            {{ $t('buttons.delete' )}}
                        </v-btn>
                        <v-btn color="warning" outlined @click="$router.push('/admin/videos/' + video.id)">
                            {{ $t('buttons.edit' )}}
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>

        <v-skeleton-loader type="card" v-show="loading" />

        <v-alert color="info" outlined v-if="! loading && ! videos.length">
            <span v-show="! searching">
                {{ $t('videos.no_videos') }}
            </span>
            <span v-show="searching">
                {{ $t('no_results') }}
            </span>
        </v-alert>
    </div>
</template>

<script>
  import {mapActions} from 'vuex';

  import scroll from '../../../mixins/infinite_scroll';
  import debounce from '../../../debounce';

  export default {
    name: "VideoList",

    mixins: [scroll],

    data() {
      return {
        videos: [],

        pagination: {
          last_page: 1,
          page: 1
        },

        loading: false,
        searching: false,
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
       * Загрузка списка видео
       *
       * @returns {Promise<void>}
       */
      async getVideos() {
        try {
          const response = await axios.get(`/videos`, {
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
        } finally {
          this.loading = false;
        }
      },

      /**
       * Удаление видео
       *
       * @returns {Promise<void>}
       */
      async remove(id) {
        try {
          const response = await axios.delete(`/videos/${id}`);

          this.showNotification({ type: 'success', message: response.data.message});
          this.videos = this.videos.filter(video => video.id !== id);
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message});
        }
      },

      /**
       * Обработчик события для debounce-поиска видео
       */
      onSearch({search, page}) {
        if (page) {
          this.pagination.page = page;
        }

        this.searching = true;

        this.searchData(this, search);
      },

      /**
       * Поиск видео
       *
       */
      searchData: debounce((vm, search) => {
        vm.loading = true;
        axios.get(`/videos`, {
          params: {
            page: vm.pagination.page,
            ...search,
          }
        }).then(response => {
          vm.videos = (vm.pagination.page === 1)
              ? response.data.videos.data
              : vm.videos.concat(response.data.videos.data);

          vm.pagination.last_page = response.data.videos.last_page;
          vm.loading = false;
        }).catch(error => {
          vm.showNotification({ type: 'error', message: error.data.message});
        });
      }, 300),

      /**
       * Инициализация видео данными
       *
       * Initialize video data
       */
      async initData() {
        this.loading = true;

        await this.getVideos();

        this.loading = false;
      },

      /**
       * Обработчик события скролла в таблице
       */
      onScroll: function () {
        this.paginationScroll(this, document, 'getVideos');
      },
    },

    created() {
      this.initData();
    },

    mounted() {
      document.addEventListener('scroll', this.onScroll);

      this.$root.$on('video-created', this.initData);

      this.$root.$on('video-searching', this.onSearch);
    },

    destroyed() {
      document.removeEventListener('scroll', this.onScroll);
    }
  }
</script>

<style scoped>
    .flex-card-full-size {
        display: flex;
        flex-direction: column;
    }
</style>