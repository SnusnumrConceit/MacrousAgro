<template>
    <div>
        <v-row v-show="! loading && photos.length">
            <v-col cols="4" v-for="photo in photos" :key="photo.id">
                <v-card height="100%" class="flex-card-full-size">
                    <v-card-title>
                        {{ photo.title }}
                    </v-card-title>
                    <v-card-text class="m-b-md">
                        <v-img :src="photo.path" alt="" class="m-b-md"></v-img>
                    </v-card-text>

                    <v-card-actions class="mt-auto">
                        <v-btn color="error" outlined @click="remove(photo.id)">
                            {{ $t('buttons.delete')}}
                        </v-btn>
                        <v-btn color="warning" outlined @click="$router.push('/admin/photos/' + photo.id)">
                            {{ $t('buttons.edit')}}
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>

        <v-skeleton-loader type="card" v-show="loading" />

        <v-alert color="info" outlined v-if="! loading && ! photos.length">
            <div class="">
                <span v-show="! searching">
                    {{ $t('photos.no_photos') }}
                </span>
                <span v-show="searching">
                    {{ $t('no_results') }}
                </span>
            </div>
        </v-alert>
    </div>
</template>

<script>
  import debounce from '../../../debounce';
  import scroll from "../../../mixins/infinite_scroll";

  import {mapActions} from 'vuex';

  export default {
    name: "PhotoList",

    mixins: [scroll],

    data() {
      return {
        photos: [],

        pagination: {
          page: 1,
          last_page: 1
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
       * Загрузка списка фотографий
       *
       * @returns {Promise<void>}
       */
      async getPhotos() {
        this.loading = true;

        try {
          const response = await axios.get(`/photos`, {
            params: {
              page: this.pagination.page
            }
          });

          this.photos = (this.pagination.page === 1)
              ? response.data.photos.data
              : this.photos.concat(response.data.photos.data);

          this.pagination.last_page = response.data.photos.last_page;
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message});
        } finally {
          this.loading = false;
        }
      },

      /**
       * Обработчик события для debounce-поиска фотографий
       *
       */
      onSearch({page, search}) {
        this.pagination.page = page;
        this.searching = true;
        this.searchData(this, search);
      },

      /**
       * Поиск фотографий
       *
       * Photo searching
       *
       */
      searchData: debounce((vm, search) => {
        vm.loading = true;
        axios.get(`/photos`, {
          params: {
            page: vm.pagination.page,
            ...search,
          }
        }).then(response => {
          vm.photos = (vm.pagination.page === 1)
              ? response.data.photos.data
              : vm.photos.concat(response.data.photos.data);

          vm.pagination.last_page = response.data.photos.last_page;
          vm.loading = false;
        }).catch(error => {
          vm.loading = false;
          console.log(error);
          vm.showNotification({ type: 'error', message: error.data.message});
        })
        ;
      }, 300),

      /**
       * Удаление фотографии из списка
       *
       * Remove photo
       *
       * @param id
       * @returns {Promise<void>}
       */
      async remove(id) {
        try {
          const response = await axios.delete(`/photos/${id}`);

          this.showNotification({ type: 'success', message: response.data.message});
          this.photos = this.photos.filter(photo => photo.id !== id);
        } catch (e) {
          this.showNotification({ type: 'error', message: e.response.data.message});
        }
      },

      /**
       * Обработчик события скролла
       */
      onScroll: function() {
        this.paginationScroll(this, document, 'getPhotos');
      },

      /**
       * Инициализация фотографий данными
       *
       * Initialize photos data
       *
       * @returns {Promise<void>}
       */
      async initData() {
        this.loading = true;

        await this.getPhotos();

        this.loading = false;
      }
    },

    created() {
      this.initData();
    },

    mounted() {
      document.addEventListener('scroll', this.onScroll);

      this.$root.$on('photo-created', this.initData);

      this.$root.$on('photo-searching', this.onSearch);
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